<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Category;
// addded after error
use App\Models\Region;
use App\Models\User;
// addded after error
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;



class StoreController extends Controller
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
        
        $query = Store::with(['category', 'faqs']);

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

        // Apply filters if present
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('url_slug', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('active')) {
            $query->where('active', $request->active);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by region/country code (only for admins)
        if ($request->has('country') && is_string($request->country) && $request->country != '' && $isAdmin) {
            // Filter stores that contain the selected region code in their country_codes
            $query->where('country_codes', 'LIKE', '%'. $request->country .'%');
        }

        $stores = $query->orderBy('sort', 'asc')->paginate(15); // Changed from get() to paginate()

        // Get categories for filter dropdown
        $categories = Category::orderBy('title')->get();

        // Get regions for filter dropdown (like in category controller)
        $regions = \App\Models\Region::all();

        return view('admin.stores.index', compact('stores', 'categories', 'regions'));
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
            // Admins can see all categories and regions
            $categories = Category::orderBy('title')->get();
            $regions = Region::all();
        } else {
            // Region-wise users only see their assigned region and categories in their region
            $categories = Category::where('country_codes', 'LIKE', '%'.$user->assigned_regions.'%')->orderBy('title')->get();
            $regions = collect();
            if ($user->assigned_regions) {
                $regions = Region::where('code', $user->assigned_regions)->get();
            }
        }
        
        $users = User::all();
        return view('admin.stores.create', compact('categories', 'regions', 'users'));
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
            'category_id' => 'nullable|exists:categories,id',
            'url_slug' => 'required|string|max:255|unique:stores,url_slug',
            'active' => 'boolean',
            'sort' => 'integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'country_codes' => 'nullable|array',
        ]);

        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        $store = new Store();
        $store->title = $request->title;
        $store->category_id = $request->category_id;
        $store->seo_title = $request->seo_title;
        $store->seo_meta_keyword = $request->seo_meta_keyword;
        $store->url_slug = $request->url_slug;
        $store->meta_description = $request->meta_description;
        $store->title_h1 = $request->title_h1;
        $store->subtitle_h2 = $request->subtitle_h2;
        $store->content_body = $request->content_body;
        $store->logo = $request->logo;
        $store->image_alt = $request->image_alt;
        $store->image_title = $request->image_title;
        // $store->meta_robots = $request->meta_robots;
        
        $store->meta_robots = $request->meta_robots ?? 'index,follow';


        $store->affiliate_links = $request->affiliate_links;
        $store->contact_details = $request->contact_details;
        $store->play_store = $request->play_store;
        $store->app_store = $request->app_store;
        
        // For region-wise users, automatically assign their region
        if ($isAdmin) {
            // Admins can assign to any regions if provided
            $regionCodesString = '';
            if ($request->has('country_codes') && is_array($request->country_codes)) {
                $regions = \App\Models\Region::whereIn('id', $request->country_codes)->pluck('code')->toArray();
                $regionCodesString = implode(',', $regions);
            }
            $store->country_codes = $regionCodesString;
        } else {
            // Region-wise users automatically get their region assigned
            $store->country_codes = $user->assigned_regions;
        }
        
        $store->start_date = $request->start_date;
        $store->end_date = $request->end_date;
        $store->active = $request->has('active') ? 1 : 0;
        $store->sort = $request->sort ?? 0;
        $store->created_by = Auth::id();
        $store->save();

        // Handle FAQs
        if ($request->has('faq_question') && is_array($request->faq_question)) {
            for ($i = 0; $i < count($request->faq_question); $i++) {
                if (!empty($request->faq_question[$i])) { // Only create if question is not empty
                    $faq = new \App\Models\Faq();
                    $faq->question = $request->faq_question[$i];
                    $faq->answer = $request->faq_answer[$i] ?? '';
                    $faq->store_id = $store->id;
                    $faq->sort = $request->faq_sort[$i] ?? 0;
                    $faq->created_by = Auth::id();
                    $faq->save();
                }
            }
        }

        return redirect()->route('admin.stores.index')->with('success', 'Store created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        $store->load(['category', 'faqs']);
        return view('admin.stores.show', compact('store'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        // Check if user has permission to edit this store
        if (!$isAdmin) {
            // Region-wise users can only edit stores in their assigned region
            $userRegionCode = $user->assigned_regions;
            $storeRegionCodes = $store->getOriginal('country_codes');
            
            if ($storeRegionCodes && strpos($storeRegionCodes, $userRegionCode) === false) {
                // User doesn't have permission to edit this store
                return redirect()->route('admin.store.index')->with('error', 'You do not have permission to edit this store.');
            }
        }
        
        if ($isAdmin) {
            // Admins can see all categories and regions
            $categories = Category::orderBy('title')->get();
            $regions = Region::all();
        } else {
            // Region-wise users only see their assigned region and categories in their region
            $categories = Category::where('country_codes', 'LIKE', '%'.$user->assigned_regions.'%')->orderBy('title')->get();
            $regions = collect();
            if ($user->assigned_regions) {
                $regions = Region::where('code', $user->assigned_regions)->get();
            }
        }
        
        // Split the comma-separated country codes back to an array of region IDs
        $selectedRegions = [];
        $originalCountryCodes = $store->getOriginal('country_codes');
        if ($originalCountryCodes && !is_array($originalCountryCodes)) {
            // Get region IDs from the codes provided
            $regionCodes = explode(',', $originalCountryCodes);
            $selectedRegions = \App\Models\Region::whereIn('code', $regionCodes)->pluck('id')->toArray();
        }
        
        $users = User::all();
        return view('admin.stores.edit', compact('store', 'categories', 'regions', 'users', 'selectedRegions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'url_slug' => 'required|string|max:255|unique:stores,url_slug,' . $store->id,
            'active' => 'boolean',
            'sort' => 'integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'country_codes' => 'nullable|array',
        ]);

        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        $store->title = $request->title;
        $store->category_id = $request->category_id;
        $store->seo_title = $request->seo_title;
        $store->seo_meta_keyword = $request->seo_meta_keyword;
        $store->url_slug = $request->url_slug;
        $store->meta_description = $request->meta_description;
        $store->title_h1 = $request->title_h1;
        $store->subtitle_h2 = $request->subtitle_h2;
        $store->content_body = $request->content_body;
        $store->logo = $request->logo;
        $store->image_alt = $request->image_alt;
        $store->image_title = $request->image_title;
        $store->meta_robots = $request->meta_robots;
        $store->affiliate_links = $request->affiliate_links;
        $store->contact_details = $request->contact_details;
        $store->play_store = $request->play_store;
        $store->app_store = $request->app_store;
        
        // For region-wise users, automatically assign their region
        if ($isAdmin) {
            // Admins can assign to any regions if provided
            $regionCodesString = '';
            if ($request->has('country_codes') && is_array($request->country_codes)) {
                $regions = \App\Models\Region::whereIn('id', $request->country_codes)->pluck('code')->toArray();
                $regionCodesString = implode(',', $regions);
            }
            $store->country_codes = $regionCodesString;
        } else {
            // Region-wise users automatically get their region assigned
            $store->country_codes = $user->assigned_regions;
        }
        
        $store->start_date = $request->start_date;
        $store->end_date = $request->end_date;
        $store->active = $request->has('active') ? 1 : 0;
        $store->sort = $request->sort ?? 0;
        $store->updated_by = Auth::id();
        $store->save();

        // Handle existing FAQs updates
        if ($request->has('existing_faq_question') && is_array($request->existing_faq_question)) {
            for ($i = 0; $i < count($request->existing_faq_question); $i++) {
                $faqId = $request->faq_id[$i] ?? null;
                if ($faqId) {
                    $faq = \App\Models\Faq::find($faqId);
                    if ($faq && $faq->store_id == $store->id) { // Ensure FAQ belongs to this store
                        $faq->question = $request->existing_faq_question[$i];
                        $faq->answer = $request->existing_faq_answer[$i] ?? '';
                        $faq->sort = $request->existing_faq_sort[$i] ?? 0;
                        $faq->updated_by = Auth::id();
                        $faq->save();
                    }
                }
            }
        }

        // Handle FAQ deletions
        if ($request->has('delete_faq_ids') && is_array($request->delete_faq_ids)) {
            foreach ($request->delete_faq_ids as $faqId) {
                $faq = \App\Models\Faq::find($faqId);
                if ($faq && $faq->store_id == $store->id) { // Ensure FAQ belongs to this store
                    $faq->deleted_by = Auth::id();
                    $faq->save();
                    $faq->delete();
                }
            }
        }

        // Handle new FAQs
        if ($request->has('faq_question') && is_array($request->faq_question)) {
            for ($i = 0; $i < count($request->faq_question); $i++) {
                if (!empty($request->faq_question[$i])) { // Only create if question is not empty
                    $faq = new \App\Models\Faq();
                    $faq->question = $request->faq_question[$i];
                    $faq->answer = $request->faq_answer[$i] ?? '';
                    $faq->store_id = $store->id;
                    $faq->sort = $request->faq_sort[$i] ?? 0;
                    $faq->created_by = Auth::id();
                    $faq->save();
                }
            }
        }

        return redirect()->route('admin.stores.index')->with('success', 'Store updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        $store->deleted_by = Auth::id();
        $store->save();
        $store->delete();

        return redirect()->route('admin.stores.index')->with('success', 'Store deleted successfully.');
    }

    /**
     * Store FAQ for a specific store.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function storeFaq(Request $request, Store $store)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'sort' => 'integer|min:0'
        ]);

        $faq = new Faq();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->store_id = $store->id;
        $faq->sort = $request->sort ?? 0;
        $faq->created_by = Auth::id();
        $faq->save();

        return redirect()->back()->with('success', 'FAQ added successfully.');
    }

    /**
     * Update FAQ.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function updateFaq(Request $request, Store $store, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'sort' => 'integer|min:0'
        ]);

        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->sort = $request->sort ?? 0;
        $faq->updated_by = Auth::id();
        $faq->save();

        return redirect()->back()->with('success', 'FAQ updated successfully.');
    }

    /**
     * Delete FAQ.
     *
     * @param  \App\Models\Store  $store
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function deleteFaq(Store $store, Faq $faq)
    {
        $faq->deleted_by = Auth::id();
        $faq->save();
        $faq->delete();

        return redirect()->back()->with('success', 'FAQ deleted successfully.');
    }
}