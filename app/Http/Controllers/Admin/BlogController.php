<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
                $query->where(function($q) use ($userRegionCodes) {
                    $q->whereNull('country_codes')
                      ->orWhere('country_codes', '[]')
                      ->orWhere('country_codes', '')
                      ->orWhereRaw("JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?)", [json_encode($userRegionCodes)]);
                });
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
            $regionModel = Region::where('code', $request->region)->first();
            if ($regionModel) {
                $query->whereRaw("JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?)", [json_encode([$request->region])]);
            }
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
        } else {
            // Region-wise users only see their assigned region
            $regions = collect();
            if ($user->assigned_regions) {
                $regions = Region::where('code', $user->assigned_regions)->get();
            }
        }
        
        $categories = Category::all();
        $users = User::all();
        return view('admin.blogs.create', compact('regions', 'categories', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'required|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'seo_meta_keyword' => 'nullable|string',
            'url_slug' => 'required|string|max:255|unique:blogs,url_slug',
            'meta_description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'content_body' => 'nullable|string',
            'blog_table' => 'nullable|string',
            'est_read_time' => 'nullable|integer|min:0',
            'logo' => 'nullable|string|max:255',
            'image_alt' => 'nullable|string|max:255',
            'image_title' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'meta_robots' => 'nullable|string|max:255',
            'country_codes' => 'nullable|array',
            'active' => 'boolean',
            'sort' => 'required|integer',
            'created_by' => 'nullable|exists:users,id'
        ]);

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
        $blog->start_date = $request->start_date;
        $blog->end_date = $request->end_date;
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
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/uploads', $filename);
            $blog->logo = $filename; // Store the filename
        }
        
        $blog->save();

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
        if (!empty($blog->country_codes)) {
            $regionCodes = $blog->country_codes;
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
        } else {
            // Region-wise users only see their assigned region
            $regions = collect();
            if ($user->assigned_regions) {
                $regions = Region::where('code', $user->assigned_regions)->get();
            }
        }
        
        // Get selected regions from the stored country codes (JSON array)
        $selectedRegions = $blog->country_codes ?? [];
        if (!is_array($selectedRegions)) {
            $selectedRegions = [];
        }
        
        $categories = Category::all();
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
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'required|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'seo_meta_keyword' => 'nullable|string',
            'url_slug' => 'required|string|max:255|unique:blogs,url_slug,'.$blog->id,
            'meta_description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'content_body' => 'nullable|string',
            'blog_table' => 'nullable|string',
            'est_read_time' => 'nullable|integer|min:0',
            'logo' => 'nullable|string|max:255',
            'image_alt' => 'nullable|string|max:255',
            'image_title' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'meta_robots' => 'nullable|string|max:255',
            'country_codes' => 'nullable|array',
            'active' => 'boolean',
            'sort' => 'required|integer',
            'updated_by' => 'nullable|exists:users,id'
        ]);

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
        $blog->logo = $request->logo;
        $blog->image_alt = $request->image_alt;
        $blog->image_title = $request->image_title;
        $blog->start_date = $request->start_date;
        $blog->end_date = $request->end_date;
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
            if ($blog->logo) {
                Storage::delete('public/uploads/' . $blog->logo);
            }
            
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/uploads', $filename);
            $blog->logo = $filename; // Store the filename
        }
        
        $blog->save();

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
        $blog->deleted_by = Auth::id();
        $blog->save();
        $blog->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Blog deleted successfully.');
    }
}