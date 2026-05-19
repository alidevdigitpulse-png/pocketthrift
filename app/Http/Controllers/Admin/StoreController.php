<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Models\Category;
use App\Models\Faq;
// addded after error
use App\Models\Region;
use App\Models\Store;
use App\Models\SocialLink;
use App\Models\SocialApp;
// addded after error
use App\Models\User;
use Illuminate\Http\Request;
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
        $user    = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

        $query = Store::with(['category', 'faqs']);

        // Apply region-based filtering for non-admin users
        if (! $isAdmin) {
            // Get user's region
            $userRegionCodes = [];
            if ($user->assigned_regions) {
                $userRegionCodes = [$user->assigned_regions]; // Single region assignment
            }

            if (! empty($userRegionCodes)) {
                $query->byRegionCodes($userRegionCodes);
            } else {
                // If user has no regions, show empty results
                $query->whereRaw('1 = 0');
            }
        }

        // Apply filters if present
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('url_slug', 'like', '%' . $request->search . '%');
            });
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
            $query->where('country_codes', 'LIKE', '%' . $request->country . '%');
        }

        $stores = $query->orderBy('sort', 'asc')->paginate(15); // Changed from get() to paginate()

        // Get categories for filter dropdown (region-filtered for non-admin users)
        $categoriesQuery = Category::orderBy('title');
        
        if (!$isAdmin) {
            // Non-admin users only see categories in their assigned region
            $userRegionCodes = [];
            if ($user->assigned_regions) {
                $userRegionCodes = [$user->assigned_regions]; // Single region assignment
            }
            
            if (!empty($userRegionCodes)) {
                $categoriesQuery->byRegionCodes($userRegionCodes);
            } else {
                // If user has no regions, show empty results
                $categoriesQuery->whereRaw('1 = 0');
            }
        }
        
        $categories = $categoriesQuery->get();

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
        $user    = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

        if ($isAdmin) {
            // Admins can see all categories and regions
            $categories = Category::orderBy('title')->get();
            $regions    = Region::all();
        } else {
            // Region-wise users only see their assigned region and categories in their region
            $categories = Category::where('country_codes', 'LIKE', '%' . $user->assigned_regions . '%')->orderBy('title')->get();
            $regions    = collect();
            if ($user->assigned_regions) {
                $regions = Region::where('code', $user->assigned_regions)->get();
            }
        }

        $users = User::all();
        $socialApps = SocialApp::orderBy('sort')->get();
        return view('admin.stores.create', compact('categories', 'regions', 'users', 'socialApps'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStoreRequest $request)
    {
        // Validation is handled by StoreStoreRequest

        $user    = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

        $store                   = new Store();
        $store->title            = $request->title;
        $store->category_id      = $request->category_id;
        $store->seo_title        = $request->seo_title;
        $store->seo_meta_keyword = $request->seo_meta_keyword;
        $store->url_slug         = $request->url_slug;
        $store->meta_description = $request->meta_description;
        $store->title_h1         = $request->title_h1;
        $store->subtitle_h2      = $request->subtitle_h2;
        $store->content_body     = $request->content_body;
        $store->image_alt        = $request->image_alt;
        $store->image_title      = $request->image_title;
        // $store->meta_robots = $request->meta_robots;

        $store->meta_robots = $request->meta_robots ?? 'index,follow';

        $store->affiliate_links = $request->affiliate_links;
        $store->contact_details = $request->contact_details;
        $store->play_store      = $request->play_store;
        $store->app_store       = $request->app_store;

        // For region-wise users, automatically assign their region
        if ($isAdmin) {
            // Admins can assign to any regions if provided
            $regionCodesString = '';
            if ($request->has('country_codes') && is_array($request->country_codes)) {
                $regions           = \App\Models\Region::whereIn('id', $request->country_codes)->pluck('code')->toArray();
                $regionCodesString = implode(',', $regions);
            }
            $store->country_codes = $regionCodesString;
        } else {
            // Region-wise users automatically get their region assigned
            $store->country_codes = $user->assigned_regions;
        }

        // $store->start_date = $request->start_date;
        // $store->end_date   = $request->end_date;
        $store->active     = $request->has('active') ? 1 : 0;
        $store->sort       = $request->sort ?? 0;
        $store->created_by = Auth::id();
        if ($request->hasFile('logo')) {

            // File object
            $file = $request->file('logo');

            // Unique filename
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Path inside public folder
            $file->move(public_path('uploads/store_icons'), $fileName);

            // Save in DB - path relative to uploads folder
            $store->logo = 'store_icons/' . $fileName;
        }
        $store->save();

        // Handle FAQs
        if ($request->has('faq_question') && is_array($request->faq_question)) {
            for ($i = 0; $i < count($request->faq_question); $i++) {
                if (! empty($request->faq_question[$i])) { // Only create if question is not empty
                    $faq             = new \App\Models\Faq();
                    $faq->question   = $request->faq_question[$i];
                    $faq->answer     = $request->faq_answer[$i] ?? '';
                    $faq->store_id   = $store->id;
                    $faq->sort       = $request->faq_sort[$i] ?? 0;
                    $faq->created_by = Auth::id();
                    $faq->save();
                }
            }
        }

        // Handle Social Links
        if ($request->has('social_app_id') && is_array($request->social_app_id)) {
            for ($i = 0; $i < count($request->social_app_id); $i++) {
                if (!empty($request->social_app_id[$i]) && !empty($request->social_link[$i])) {
                    $socialLink = new SocialLink();
                    $socialLink->store_id = $store->id;
                    $socialLink->social_app_id = $request->social_app_id[$i];
                    $socialLink->link = $request->social_link[$i];
                    $socialLink->sort = $request->social_link_sort[$i] ?? 0;
                    $socialLink->save();
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
        $store->load(['category', 'faqs', 'socialLinks.socialApp']);
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
        $user    = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

        // Check if user has permission to edit this store
        if (! $isAdmin) {
            // Region-wise users can only edit stores in their assigned region
            $userRegionCode = $user->assigned_regions;

            if ($userRegionCode) {
                // Get the store's original country codes (raw database value)
                $originalCountryCodes = $store->getOriginal('country_codes');

                // Handle the case where originalCountryCodes might be an array due to accessor
                if (is_array($originalCountryCodes)) {
                    $storeRegions = $originalCountryCodes;
                } else {
                    // If it's a string, split by comma
                    if (!empty($originalCountryCodes)) {
                        $storeRegions = array_map('trim', explode(',', $originalCountryCodes));
                    } else {
                        $storeRegions = [];
                    }
                }

                // If store has no country codes set, allow access for region users
                if (empty($storeRegions)) {
                    // Allow access to region users for stores without specific region codes
                } else {
                    // Check if user's region is in store's country codes
                    if (!in_array($userRegionCode, $storeRegions)) {
                        // User doesn't have permission to edit this store
                        return redirect()->route('admin.stores.index')->with('error', 'You do not have permission to edit this store.');
                    }
                }
            } else {
                // If user has no assigned region, they shouldn't be able to edit stores
                return redirect()->route('admin.stores.index')->with('error', 'You do not have permission to edit this store.');
            }
        }

        if ($isAdmin) {
            // Admins can see all categories and regions
            $categories = Category::orderBy('title')->get();
            $regions    = Region::all();
        } else {
            // Region-wise users only see their assigned region and categories in their region
            $categories = Category::where('country_codes', 'LIKE', '%' . $user->assigned_regions . '%')->orderBy('title')->get();
            $regions    = collect();
            if ($user->assigned_regions) {
                $regions = Region::where('code', $user->assigned_regions)->get();
            }
        }

        // Split the comma-separated country codes back to an array of region IDs
        $selectedRegions      = [];
        $originalCountryCodes = $store->getOriginal('country_codes');
        if ($originalCountryCodes && ! is_array($originalCountryCodes)) {
            // Get region IDs from the codes provided
            $regionCodes     = explode(',', $originalCountryCodes);
            $selectedRegions = \App\Models\Region::whereIn('code', $regionCodes)->pluck('id')->toArray();
        }

        $users = User::all();
        $socialApps = SocialApp::orderBy('sort')->get();
        $store->load('socialLinks');
        return view('admin.stores.edit', compact('store', 'categories', 'regions', 'users', 'selectedRegions', 'socialApps'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStoreRequest $request, Store $store)
    {
        // Validation is handled by UpdateStoreRequest

        $user    = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

        // Check if user has permission to update this store
        if (! $isAdmin) {
            // Region-wise users can only update stores in their assigned region
            $userRegionCode = $user->assigned_regions;

            if ($userRegionCode) {
                // Get the store's original country codes (raw database value)
                $originalCountryCodes = $store->getOriginal('country_codes');

                // Handle the case where originalCountryCodes might be an array due to accessor
                if (is_array($originalCountryCodes)) {
                    $storeRegions = $originalCountryCodes;
                } else {
                    // If it's a string, split by comma
                    if (!empty($originalCountryCodes)) {
                        $storeRegions = array_map('trim', explode(',', $originalCountryCodes));
                    } else {
                        $storeRegions = [];
                    }
                }

                // If store has no country codes set, allow access for region users
                if (empty($storeRegions)) {
                    // Allow access to region users for stores without specific region codes
                } else {
                    // Check if user's region is in store's country codes
                    if (!in_array($userRegionCode, $storeRegions)) {
                        // User doesn't have permission to update this store
                        return redirect()->route('admin.stores.index')->with('error', 'You do not have permission to edit this store.');
                    }
                }
            } else {
                // If user has no assigned region, they shouldn't be able to update stores
                return redirect()->route('admin.stores.index')->with('error', 'You do not have permission to edit this store.');
            }
        }

        $store->title            = $request->title;
        $store->category_id      = $request->category_id;
        $store->seo_title        = $request->seo_title;
        $store->seo_meta_keyword = $request->seo_meta_keyword;
        $store->url_slug         = $request->url_slug;
        $store->meta_description = $request->meta_description;
        $store->title_h1         = $request->title_h1;
        $store->subtitle_h2      = $request->subtitle_h2;
        $store->content_body     = $request->content_body;
        $store->image_alt        = $request->image_alt;
        $store->image_title      = $request->image_title;
        $store->meta_robots      = $request->meta_robots;
        $store->affiliate_links  = $request->affiliate_links;
        $store->contact_details  = $request->contact_details;
        $store->play_store       = $request->play_store;
        $store->app_store        = $request->app_store;

        // For region-wise users, automatically assign their region
        if ($isAdmin) {
            // Admins can assign to any regions if provided
            $regionCodesString = '';
            if ($request->has('country_codes') && is_array($request->country_codes)) {
                $regions           = \App\Models\Region::whereIn('id', $request->country_codes)->pluck('code')->toArray();
                $regionCodesString = implode(',', $regions);
            }
            $store->country_codes = $regionCodesString;
        } else {
            // Region-wise users automatically get their region assigned
            $store->country_codes = $user->assigned_regions;
        }

        // $store->start_date = $request->start_date;
        // $store->end_date   = $request->end_date;
        $store->active     = $request->has('active') ? 1 : 0;
        $store->sort       = $request->sort ?? 0;
        $store->updated_by = Auth::id();

        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            // File object
            $file = $request->file('logo');

            // Unique filename
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Path inside public folder
            $file->move(public_path('uploads/store_icons'), $fileName);

            // Save in DB - path relative to uploads folder
            $store->logo = 'store_icons/' . $fileName;
        }
        // If no new logo file is uploaded, keep the existing logo unchanged

        $store->save();

        // Handle existing FAQs updates
        if ($request->has('existing_faq_question') && is_array($request->existing_faq_question)) {
            for ($i = 0; $i < count($request->existing_faq_question); $i++) {
                $faqId = $request->faq_id[$i] ?? null;
                if ($faqId) {
                    $faq = \App\Models\Faq::find($faqId);
                    if ($faq && $faq->store_id == $store->id) { // Ensure FAQ belongs to this store
                        $faq->question   = $request->existing_faq_question[$i];
                        $faq->answer     = $request->existing_faq_answer[$i] ?? '';
                        $faq->sort       = $request->existing_faq_sort[$i] ?? 0;
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
                if (! empty($request->faq_question[$i])) { // Only create if question is not empty
                    $faq             = new \App\Models\Faq();
                    $faq->question   = $request->faq_question[$i];
                    $faq->answer     = $request->faq_answer[$i] ?? '';
                    $faq->store_id   = $store->id;
                    $faq->sort       = $request->faq_sort[$i] ?? 0;
                    $faq->created_by = Auth::id();
                    $faq->save();
                }
            }
        }

        // Handle Social Links Updates
        
        // 1. Delete removed social links
        if ($request->has('delete_social_link_ids') && is_array($request->delete_social_link_ids)) {
            SocialLink::whereIn('id', $request->delete_social_link_ids)->delete();
        }

        // 2. Update existing social links
        if ($request->has('existing_social_link_id') && is_array($request->existing_social_link_id)) {
            for ($i = 0; $i < count($request->existing_social_link_id); $i++) {
                $socialLinkId = $request->existing_social_link_id[$i];
                $socialLink = SocialLink::find($socialLinkId);
                if ($socialLink && $socialLink->store_id == $store->id) {
                    $socialLink->social_app_id = $request->existing_social_app_id[$i];
                    $socialLink->link = $request->existing_social_link[$i];
                    $socialLink->sort = $request->existing_social_link_sort[$i] ?? 0;
                    $socialLink->save();
                }
            }
        }

        // 3. Create new social links
        if ($request->has('social_app_id') && is_array($request->social_app_id)) {
            for ($i = 0; $i < count($request->social_app_id); $i++) {
                if (!empty($request->social_app_id[$i]) && !empty($request->social_link[$i])) {
                    $socialLink = new SocialLink();
                    $socialLink->store_id = $store->id;
                    $socialLink->social_app_id = $request->social_app_id[$i];
                    $socialLink->link = $request->social_link[$i];
                    $socialLink->sort = $request->social_link_sort[$i] ?? 0;
                    $socialLink->save();
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
        // Permanently delete the store from the database
        $store->forceDelete();

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
            'answer'   => 'required|string',
            'sort'     => 'integer|min:0',
        ]);

        $faq             = new Faq();
        $faq->question   = $request->question;
        $faq->answer     = $request->answer;
        $faq->store_id   = $store->id;
        $faq->sort       = $request->sort ?? 0;
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
            'answer'   => 'required|string',
            'sort'     => 'integer|min:0',
        ]);

        $faq->question   = $request->question;
        $faq->answer     = $request->answer;
        $faq->sort       = $request->sort ?? 0;
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

    /**
     * Update the sort order of stores.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSort(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:stores,id'
        ]);

        foreach ($request->order as $index => $storeId) {
            Store::where('id', $storeId)->update(['sort' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Sort order updated successfully']);
    }
}
