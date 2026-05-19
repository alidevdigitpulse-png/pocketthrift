<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
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
        
        $query = Category::with('user'); // Eager load the user relationship

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
                      ->orWhere('country_codes', '')
                      ->orWhere(function($subQuery) use ($userRegionCodes) {
                          foreach ($userRegionCodes as $code) {
                              $subQuery->orWhereRaw("FIND_IN_SET(?, country_codes)", [$code]);
                          }
                      });
                });
            } else {
                // If user has no regions, show empty results
                $query->whereRaw('1 = 0');
            }
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('title_eng', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('url_slug', 'LIKE', '%' . $request->search . '%');
        }

        // Filter by active status
        if ($request->has('active') && $request->active != '') {
            $query->where('active', $request->active);
        }

        // Filter by seasonal
        if ($request->has('is_seasonal') && $request->is_seasonal != '') {
            $query->where('is_seasonal', $request->is_seasonal);
        }
        
        // Filter by region (only for admins)
        if ($request->has('region') && is_string($request->region) && $request->region != '' && $isAdmin) {
            // Filter categories that contain the selected region code in their country_codes
            $query->where('country_codes', 'LIKE', '%'. $request->region .'%');
        }

        $categories = $query->orderBy('sort', 'asc')->orderBy('title', 'asc')->paginate(10);
        $regions = \App\Models\Region::all(); // Get all regions for the filter dropdown

        return view('admin.categories.index', compact('categories', 'regions'));
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
        
        $users = User::all();
        return view('admin.categories.create', compact('regions', 'users'));
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
            'title' => 'required|string|max:255',
            'title_eng' => 'nullable|string|max:255',
            'url_slug' => 'required|string|max:255|unique:categories,url_slug',
            'seo_title' => 'nullable|string|max:255',
            'seo_meta_keyword' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'title_h1' => 'nullable|string|max:255',
            'subtitle_h2' => 'nullable|string|max:255',
            'content_body' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'image_title' => 'nullable|string|max:255',
            'meta_robots' => 'nullable|string|max:255',
            'country_codes' => 'nullable|array',
            // 'start_date' => 'nullable|date',
            // 'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_seasonal' => 'boolean',
            'active' => 'boolean',
            'sort' => 'required|integer',
            'updated_by' => 'nullable|exists:users,id'
        ]);

        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        $category = new Category();
        $category->title = $request->title;
        $category->title_eng = $request->title_eng;
        $category->seo_title = $request->seo_title;
        $category->seo_meta_keyword = $request->seo_meta_keyword;
        $category->url_slug = $request->url_slug;
        $category->meta_description = $request->meta_description;
        $category->title_h1 = $request->title_h1;
        $category->subtitle_h2 = $request->subtitle_h2;
        $category->content_body = $request->content_body;
        $category->logo = $request->logo;
        $category->image_alt = $request->image_alt;
        $category->image_title = $request->image_title;
        $category->meta_robots = $request->meta_robots;
        
        // For region-wise users, automatically assign their region
        if ($isAdmin) {
            // Admins can assign to any regions if provided
            $regionCodesString = '';
            if ($request->has('country_codes') && is_array($request->country_codes)) {
                $regions = \App\Models\Region::whereIn('id', $request->country_codes)->pluck('code')->toArray();
                $regionCodesString = implode(',', $regions);
            }
            $category->country_codes = $regionCodesString;
        } else {
            // Region-wise users automatically get their region assigned
            $category->country_codes = $user->assigned_regions;
        }
        
        // $category->start_date = $request->start_date;
        // $category->end_date = $request->end_date;
        $category->is_seasonal = $request->has('is_seasonal') ? 1 : 0;
        $category->active = $request->has('active') ? 1 : 0;
        $category->sort = $request->sort ?? 0;
        $category->updated_by = Auth::id();

        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            // File object
            $file = $request->file('logo');

            // Unique filename
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Path inside public folder - using same directory as stores
            $file->move(public_path('uploads/store_icons'), $fileName);

            // Save in DB - path relative to uploads folder, using same directory as stores
            $category->logo = 'store_icons/' . $fileName;
        }

        $category->save();

        return redirect()->route('admin.category.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $category->load('user', 'deleter'); // Load all necessary relationships
        
        // Get the regions based on the stored country codes (comma-separated codes)
        $assignedRegions = collect();
        $regionCodes = $category->country_codes; // Already an array from accessor
        if (!empty($regionCodes)) {
            $assignedRegions = Region::whereIn('code', is_array($regionCodes) ? $regionCodes : explode(',', $regionCodes))->get();
        }
        
        return view('admin.categories.show', compact('category', 'assignedRegions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        // Check if user has permission to edit this category
        if (!$isAdmin) {
            // Region-wise users can only edit categories in their assigned region
            $userRegionCode = $user->assigned_regions;
            $categoryRegionCodes = $category->country_codes; // This will be an array because of the accessor
            
            if (!empty($categoryRegionCodes)) {
                // If it's an array (from accessor), use in_array
                if (is_array($categoryRegionCodes)) {
                    if (!in_array($userRegionCode, $categoryRegionCodes)) {
                        return redirect()->route('admin.category.index')->with('error', 'You do not have permission to edit this category.');
                    }
                } else {
                    // If it's somehow a string, use strpos
                    if (strpos($categoryRegionCodes, $userRegionCode) === false) {
                        return redirect()->route('admin.category.index')->with('error', 'You do not have permission to edit this category.');
                    }
                }
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
        
        // Get region IDs from the codes provided (country_codes is already an array from accessor)
        $regionCodes = $category->country_codes;
        $selectedRegions = [];
        if (!empty($regionCodes)) {
            $selectedRegions = Region::whereIn('code', is_array($regionCodes) ? $regionCodes : explode(',', $regionCodes))->pluck('id')->toArray();
        }
        
        $users = User::all();
        
        return view('admin.categories.edit', compact('category', 'regions', 'users', 'selectedRegions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_eng' => 'nullable|string|max:255',
            'url_slug' => 'required|string|max:255|unique:categories,url_slug,'.$category->id,
            'seo_title' => 'nullable|string|max:255',
            'seo_meta_keyword' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'title_h1' => 'nullable|string|max:255',
            'subtitle_h2' => 'nullable|string|max:255',
            'content_body' => 'nullable|string',
            'logo' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'image_alt' => 'nullable|string|max:255',
            'image_title' => 'nullable|string|max:255',
            'meta_robots' => 'nullable|string|max:255',
            'country_codes' => 'nullable|array',
            // 'start_date' => 'nullable|date',
            // 'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_seasonal' => 'boolean',
            'active' => 'boolean',
            'sort' => 'required|integer',
            'updated_by' => 'nullable|exists:users,id'
        ]);

        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        $category->title = $request->title;
        $category->title_eng = $request->title_eng;
        $category->seo_title = $request->seo_title;
        $category->seo_meta_keyword = $request->seo_meta_keyword;
        $category->url_slug = $request->url_slug;
        $category->meta_description = $request->meta_description;
        $category->title_h1 = $request->title_h1;
        $category->subtitle_h2 = $request->subtitle_h2;
        $category->content_body = $request->content_body;
        $category->logo = $request->logo;
        $category->image_alt = $request->image_alt;
        $category->image_title = $request->image_title;
        $category->meta_robots = $request->meta_robots;
        
        // For region-wise users, automatically assign their region
        if ($isAdmin) {
            // Admins can assign to any regions if provided
            $regionCodesString = '';
            if ($request->has('country_codes') && is_array($request->country_codes)) {
                $regions = \App\Models\Region::whereIn('id', $request->country_codes)->pluck('code')->toArray();
                $regionCodesString = implode(',', $regions);
            }
            $category->country_codes = $regionCodesString;
        } else {
            // Region-wise users automatically get their region assigned
            $category->country_codes = $user->assigned_regions;
        }
        
        // $category->start_date = $request->start_date;
        // $category->end_date = $request->end_date;
        $category->is_seasonal = $request->has('is_seasonal') ? 1 : 0;
        $category->active = $request->has('active') ? 1 : 0;
        $category->sort = $request->sort ?? 0;
        $category->updated_by = Auth::id();

        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            // File object
            $file = $request->file('logo');

            // Unique filename
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Path inside public folder - using same directory as stores
            $file->move(public_path('uploads/store_icons'), $fileName);

            // Save in DB - path relative to uploads folder, using same directory as stores
            $category->logo = 'store_icons/' . $fileName;
        }

        $category->save();

        return redirect()->route('admin.category.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // Permanently delete the category from the database
        $category->forceDelete();

        return redirect()->route('admin.category.index')->with('success', 'Category deleted successfully.');
    }
}