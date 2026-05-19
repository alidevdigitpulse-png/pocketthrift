<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        $query = Blog::with('category', 'creator'); // Eager load the relationships

        // Apply region-based filtering for non-admin users
        if (!$isAdmin) {
            // Get user's region
            $userRegionCodes = [];
            if ($user->assigned_regions) {
                $userRegionCodes = [$user->assigned_regions]; // Single region assignment
            }
            
            if (!empty($userRegionCodes)) {
                $query->byRegionCodes($userRegionCodes);
            } else {
                // If user has no regions, show empty results
                $query->whereRaw('1 = 0');
            }
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('seo_title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('url_slug', 'LIKE', '%' . $request->search . '%');
        }

        // Filter by active status
        if ($request->has('active') && $request->active != '') {
            $query->where('active', $request->active);
        }
        
        
        // Filter by region (only for admins)
        if ($request->has('region') && is_string($request->region) && $request->region != '' && $isAdmin) {
            // Filter blogs that contain the selected region code in their country_codes
            $query->byRegionCodes([$request->region]);
        }

        $blogs = $query->orderBy('sort', 'asc')->orderBy('title', 'asc')->paginate(10);
        $regions = Region::all(); // Get all regions for the filter dropdown

        return view('admin.blogs.index', compact('blogs', 'regions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        if ($isAdmin) {
            // Admins can see all regions
            $regions = Region::all();
            // Admins can see all categories
            $categories = Category::where('active', 1)->orderBy('title', 'asc')->get();
        } else {
            // Region-wise users only see their assigned region
            $regions = collect();
            if ($user->assigned_regions) {
                $regions = Region::where('code', $user->assigned_regions)->get();
                // Filter categories by user's assigned region
                $categories = Category::where('active', 1)
                    ->byRegionCodes([$user->assigned_regions])
                    ->orderBy('title', 'asc')
                    ->get();
            } else {
                $categories = collect();
            }
        }
        
        // Debug: Log to see what's happening
        \Log::info('Blog Create - User Role: ' . $user->role);
        \Log::info('Blog Create - Is Admin: ' . ($isAdmin ? 'Yes' : 'No'));
        \Log::info('Blog Create - Categories Count: ' . $categories->count());
        \Log::info('Blog Create - User Assigned Region: ' . ($user->assigned_regions ?? 'None'));
        
        $users = User::all();
        return view('admin.blogs.create', compact('regions', 'categories', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBlogRequest $request)
    {
        // Validation is handled by StoreBlogRequest

        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        $blog = new Blog();
        $blog->category_id = $request->category_id;
        $blog->title = $request->title;
        $blog->seo_title = $request->seo_title;
        $blog->seo_meta_keyword = $request->seo_meta_keyword;
        $blog->url_slug = $request->url_slug;
        $blog->meta_description = $request->meta_description;
        $blog->short_description = $request->short_description;
        $blog->content_body = $request->content_body;
        $blog->blog_table = $request->blog_table;
        $blog->est_read_time = $request->est_read_time;
        $blog->logo = $request->logo;
        $blog->image_alt = $request->image_alt;
        $blog->image_title = $request->image_title;
        // $blog->start_date = $request->start_date;
        // $blog->end_date = $request->end_date;
        $blog->meta_robots = $request->meta_robots;
        
        // Handle region codes
        if ($isAdmin) {
            // Admins can assign to any regions if provided
            $regionCodesArray = [];
            if ($request->has('country_codes') && is_array($request->country_codes)) {
                $regionCodesArray = $request->country_codes;
            }
            $blog->country_codes = $regionCodesArray;
        } else {
            // Region-wise users automatically get their region assigned
            $blog->country_codes = [$user->assigned_regions];
        }
        
        $blog->active = $request->has('active') ? 1 : 0;
        $blog->sort = $request->sort ?? 0;
        $blog->created_by = Auth::id();
        $blog->updated_by = Auth::id();
        
        // Handle image upload if available
        if ($request->hasFile('image')) {
            // File object
            $file = $request->file('image');
            
            // Unique filename
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Path inside public folder
            $file->move(public_path('uploads/store_icons'), $fileName);
            
            // Save in DB - path relative to uploads folder
            $blog->logo = 'store_icons/' . $fileName;
        }
        
        $blog->save();

        // Handle FAQs
        if ($request->has('faq_question')) {
            $questions = $request->faq_question;
            $answers = $request->faq_answer;
            $sorts = $request->faq_sort;
            
            foreach ($questions as $key => $question) {
                if (!empty($question) && !empty($answers[$key])) {
                    $faq = new \App\Models\Faq();
                    $faq->blog_id = $blog->id;
                    $faq->question = $question;
                    $faq->answer = $answers[$key];
                    $faq->sort = $sorts[$key] ?? 0;
                    $faq->created_by = Auth::id();
                    $faq->updated_by = Auth::id();
                    $faq->save();
                }
            }
        }

        return redirect()->route('admin.blog.index')->with('success', 'Blog created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        $blog->load('category', 'creator', 'updater', 'deleter'); // Load all necessary relationships
        
        // Get the regions based on the stored country codes (JSON array)
        $assignedRegions = collect();
        $regionCodes = $blog->country_codes;

        // Fallback for legacy CSV or bad JSON
        if (empty($regionCodes)) {
            $raw = $blog->getRawOriginal('country_codes');
            if (!empty($raw)) {
                $clean = str_replace(['[', ']', '"', "'"], '', $raw);
                $regionCodes = array_filter(array_map('trim', explode(',', $clean)));
            }
        }

        if (!empty($regionCodes)) {
            $assignedRegions = Region::whereIn('code', $regionCodes)->get();
        }
        
        return view('admin.blogs.show', compact('blog', 'assignedRegions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        // Check if user has permission to edit this blog
        if (!$isAdmin) {
            // Region-wise users can only edit blogs in their assigned region
            $userRegionCode = $user->assigned_regions;
            $blogRegionCodes = $blog->country_codes ?? [];
            
            if (!empty($blogRegionCodes) && !in_array($userRegionCode, $blogRegionCodes)) {
                // User doesn't have permission to edit this blog
                return redirect()->route('admin.blog.index')->with('error', 'You do not have permission to edit this blog.');
            }
        }
        
        if ($isAdmin) {
            // Admins can see all regions
            $regions = Region::all();
            // Admins can see all categories
            $categories = Category::where('active', 1)->orderBy('title', 'asc')->get();
        } else {
            // Region-wise users only see their assigned region
            $regions = collect();
            if ($user->assigned_regions) {
                $regions = Region::where('code', $user->assigned_regions)->get();
                // Filter categories by user's assigned region
                $categories = Category::where('active', 1)
                    ->byRegionCodes([$user->assigned_regions])
                    ->orderBy('title', 'asc')
                    ->get();
            } else {
                $categories = collect();
            }
        }
        
        // Get selected regions from the stored country codes (JSON array)
        $selectedRegions = $blog->country_codes ?? [];
        
        // Fallback for legacy CSV or bad JSON
        if (empty($selectedRegions)) {
            $raw = $blog->getRawOriginal('country_codes');
            if (!empty($raw)) {
                $clean = str_replace(['[', ']', '"', "'"], '', $raw);
                $selectedRegions = array_filter(array_map('trim', explode(',', $clean)));
            }
        }

        if (!is_array($selectedRegions)) {
            $selectedRegions = [];
        }
        
        $users = User::all();
        
        return view('admin.blogs.edit', compact('blog', 'regions', 'categories', 'users', 'selectedRegions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        // Validation is handled by UpdateBlogRequest

        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        $blog->category_id = $request->category_id;
        $blog->title = $request->title;
        $blog->seo_title = $request->seo_title;
        $blog->seo_meta_keyword = $request->seo_meta_keyword;
        $blog->url_slug = $request->url_slug;
        $blog->meta_description = $request->meta_description;
        $blog->short_description = $request->short_description;
        $blog->content_body = $request->content_body;
        $blog->blog_table = $request->blog_table;
        $blog->est_read_time = $request->est_read_time;
        // $blog->logo = $request->logo;
        $blog->image_alt = $request->image_alt;
        $blog->image_title = $request->image_title;
        // $blog->start_date = $request->start_date;
        // $blog->end_date = $request->end_date;
        $blog->meta_robots = $request->meta_robots;
        
        // Handle region codes
        if ($isAdmin) {
            // Admins can assign to any regions if provided
            $regionCodesArray = [];
            if ($request->has('country_codes') && is_array($request->country_codes)) {
                $regionCodesArray = $request->country_codes;
            }
            $blog->country_codes = $regionCodesArray;
        } else {
            // Region-wise users automatically get their region assigned
            $blog->country_codes = [$user->assigned_regions];
        }
        
        $blog->active = $request->has('active') ? 1 : 0;
        $blog->sort = $request->sort ?? 0;
        $blog->updated_by = Auth::id();
        
        // Handle image upload if available
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($blog->logo && file_exists(public_path('uploads/' . $blog->logo))) {
                unlink(public_path('uploads/' . $blog->logo));
            }
            
            // File object
            $file = $request->file('image');
            
            // Unique filename
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Path inside public folder
            $file->move(public_path('uploads/store_icons'), $fileName);
            
            // Save in DB - path relative to uploads folder
            $blog->logo = 'store_icons/' . $fileName;
        }

        \Illuminate\Support\Facades\DB::transaction(function() use ($blog, $request) {
            $blog->save();

            // Handle FAQs - Delete old and create new
            if ($request->has('faq_question')) {
                // Log incoming FAQs for debugging
                \Log::info('Updating Blog FAQs', [
                    'blog_id' => $blog->id,
                    'questions_count' => count($request->faq_question),
                    'questions' => $request->faq_question
                ]);

                // Permanently remove existing FAQs associated with this blog (force delete to avoid soft delete issues)
                $blog->faqs()->forceDelete();
                
                $questions = $request->faq_question;
                $answers = $request->faq_answer;
                $sorts = $request->faq_sort;
                
                foreach ($questions as $key => $question) {
                    if (!empty($question) && !empty($answers[$key])) {
                        $faq = new \App\Models\Faq();
                        $faq->blog_id = $blog->id;
                        $faq->question = $question;
                        $faq->answer = $answers[$key];
                        $faq->sort = $sorts[$key] ?? 0;
                        $faq->created_by = Auth::id();
                        $faq->updated_by = Auth::id();
                        $faq->save();
                    }
                }
            } else {
                // If arrays are empty/missing, it means all FAQs were removed
                $blog->faqs()->forceDelete();
            }
        });

        return redirect()->route('admin.blog.index')->with('success', 'Blog updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        // Permanently delete the blog from the database
        $blog->forceDelete();

        return redirect()->route('admin.blog.index')->with('success', 'Blog deleted successfully.');
    }

    /**
     * Update the sort order of blogs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSort(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:blogs,id'
        ]);

        foreach ($request->order as $index => $blogId) {
            Blog::where('id', $blogId)->update(['sort' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Sort order updated successfully']);
    }

    /**
     * Search blogs for auto-suggestion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $term = $request->term;
        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        $query = Blog::query();

        // Apply region-based filtering for non-admin users
        if (!$isAdmin) {
            $userRegionCodes = [];
            if ($user->assigned_regions) {
                $userRegionCodes = [$user->assigned_regions]; // Single region assignment
            }
            
            if (!empty($userRegionCodes)) {
                $query->byRegionCodes($userRegionCodes);
            } else {
                // If user has no regions, show empty results
                $query->whereRaw('1 = 0');
            }
        }

        if (!empty($term)) {
            $query->where(function($q) use ($term) {
                $q->where('title', 'LIKE', '%' . $term . '%')
                  ->orWhere('seo_title', 'LIKE', '%' . $term . '%')
                  ->orWhere('url_slug', 'LIKE', '%' . $term . '%');
            });
        }
        
        $results = $query->select('title')->distinct()->limit(20)->get()
            ->map(function($blog) {
                return [
                    'id' => $blog->title, // Use title as ID so the search filter works with strings
                    'text' => $blog->title
                ];
            });

        return response()->json(['results' => $results]);
    }
}