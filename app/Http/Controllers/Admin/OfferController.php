<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Store;
use App\Models\Category; // For seasonal reference
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
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

        $query = Offer::with(['store', 'seasonal', 'creator']);

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
                ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('active')) {
            $query->where('active', $request->active);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('store')) {
            $query->where('store_id', $request->store);
        }

        if ($request->filled('verified')) {
            $query->where('verified', $request->verified);
        }

        // Filter by country/region (only for admins)
        if ($request->filled('country') && is_string($request->country) && $request->country != '' && $isAdmin) {
            // Filter offers that contain the selected region code in their country_codes
            $query->where('country_codes', 'LIKE', '%' . $request->country . '%');
        }

        $offers = $query->orderBy('sort', 'asc')->orderBy('created_at', 'desc')->paginate(15);

        // Get related data for filters
        if ($isAdmin) {
            $stores = Store::orderBy('title')->get();
        } else {
            $stores = Store::where('country_codes', 'LIKE', '%' . $user->assigned_regions . '%')->orderBy('title')->get();
        }
        $types = ['Deal', 'Offer', 'Code', 'Sale'];
        $regions = Region::all();

        return view('admin.offers.index', compact('offers', 'stores', 'types', 'regions'));
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
            // Admins can see all stores, seasonals, and regions
            $stores = Store::orderBy('title')->get();
            $seasonals = Category::orderBy('title')->get();
            $regions = Region::all();
        } else {
            // Region-wise users only see their assigned region and stores in their region
            $stores = Store::where('country_codes', 'LIKE', '%' . $user->assigned_regions . '%')->orderBy('title')->get();
            $seasonals = Category::where('country_codes', 'LIKE', '%' . $user->assigned_regions . '%')->orderBy('title')->get();
            $regions = collect();
            if ($user->assigned_regions) {
                $regions = Region::where('code', $user->assigned_regions)->get();
            }
        }

        $types = ['Deal', 'Offer', 'Code', 'Sale'];
        $users = User::orderBy('name')->get();

        return view('admin.offers.create', compact('stores', 'seasonals', 'types', 'regions', 'users'));
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
            'store_id' => 'nullable|exists:stores,id',
            'seasonal_id' => 'nullable|exists:categories,id',
            'code' => 'nullable|string|max:255',
            'discount' => 'nullable|string',
            'type' => 'required|in:Deal,Offer,Code,Sale',
            'free_delivery' => 'in:0,1',
            'terms_and_conditions' => 'nullable|string',
            'verified' => 'in:0,1',
            'new_recently_updated' => 'boolean',
            'country_codes' => 'nullable|array',
            // 'start_date' => 'nullable|date',
            // 'end_date' => 'nullable|date|after_or_equal:start_date',
            'active' => 'boolean',
            'sort' => 'integer|min:0'
        ]);

        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

        $offer = new Offer();
        $offer->title = $request->title;
        $offer->store_id = $request->store_id;
        $offer->seasonal_id = $request->seasonal_id; // Can be null as specified
        $offer->code = $request->code;
        $offer->discount = $request->discount;
        $offer->affiliate_links = $request->affiliate_links;
        $offer->type = $request->type;
        $offer->free_delivery = $request->free_delivery ?? 0;

        $offer->terms_and_conditions = $request->terms_and_conditions;
        $offer->verified = $request->verified ?? 0;


        $offer->new_recently_updated = $request->has('new_recently_updated') ? 1 : 0;


        // For region-wise users, automatically assign their region
        if ($isAdmin) {
            // Admins can assign to any regions if provided
            $regionCodesString = '';
            if ($request->has('country_codes') && is_array($request->country_codes)) {
                $regions = Region::whereIn('id', $request->country_codes)->pluck('code')->toArray();
                $regionCodesString = implode(',', $regions);
            }
            $offer->country_codes = $regionCodesString;
        } else {
            // Region-wise users automatically get their region assigned
            $offer->country_codes = $user->assigned_regions;
        }

        // $offer->start_date = $request->start_date;
        // $offer->end_date = $request->end_date;
        $offer->active = $request->has('active') ? 1 : 0;
        $offer->sort = $request->sort ?? 0;
        $offer->created_by = Auth::id();
        if ($request->hasFile('logo')) {
            // File object
            $file = $request->file('logo');

            // Unique filename
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Path inside public folder
            $file->move(public_path('uploads/store_icons'), $fileName);

            // Save in DB - path relative to uploads folder
            $offer->logo = 'store_icons/' . $fileName;
        }
        $offer->save();

        return redirect()->route('admin.offer.index')->with('success', 'Offer created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        $offer->load(['store', 'seasonal', 'creator', 'updater', 'deleter']);
        return view('admin.offers.show', compact('offer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function edit(Offer $offer)
    {
        $stores = Store::orderBy('title')->get();
        $seasonals = Category::orderBy('title')->get();
        $types = ['Deal', 'Offer', 'Code', 'Sale'];
        $regions = Region::all();
        $users = User::orderBy('name')->get();

        // Split the comma-separated country codes back to an array of region IDs
        $selectedRegions = [];
        $originalCountryCodes = $offer->getOriginal('country_codes');
        if ($originalCountryCodes && !is_array($originalCountryCodes)) {
            $regionCodes = explode(',', $originalCountryCodes);
            $selectedRegions = Region::whereIn('code', $regionCodes)->pluck('id')->toArray();
        }

        return view('admin.offers.edit', compact('offer', 'stores', 'seasonals', 'types', 'regions', 'users', 'selectedRegions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Offer $offer)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'store_id' => 'nullable|exists:stores,id',
            'seasonal_id' => 'nullable|exists:categories,id',
            'code' => 'nullable|string|max:255',
            'discount' => 'nullable|string',
            'type' => 'required|in:Deal,Offer,Code,Sale',
            'free_delivery' => 'in:0,1',
            'terms_and_conditions' => 'nullable|string',
            'verified' => 'in:0,1',
            'new_recently_updated' => 'boolean',
            'country_codes' => 'nullable|array',
            // 'start_date' => 'nullable|date',
            // 'end_date' => 'nullable|date|after_or_equal:start_date',
            'active' => 'boolean',
            'sort' => 'integer|min:0',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

        $offer->title = $request->title;
        $offer->store_id = $request->store_id;
        $offer->seasonal_id = $request->seasonal_id; // Can be null as specified
        $offer->code = $request->code;
        $offer->discount = $request->discount;
        $offer->affiliate_links = $request->affiliate_links;
        $offer->type = $request->type;
        $offer->free_delivery = $request->free_delivery ?? 0;
        $offer->terms_and_conditions = $request->terms_and_conditions;
        $offer->verified = $request->verified ?? 0;
        $offer->new_recently_updated = $request->has('new_recently_updated') ? 1 : 0;

        // For region-wise users, automatically assign their region
        if ($isAdmin) {
            // Admins can assign to any regions if provided
            $regionCodesString = '';
            if ($request->has('country_codes') && is_array($request->country_codes)) {
                $regions = Region::whereIn('id', $request->country_codes)->pluck('code')->toArray();
                $regionCodesString = implode(',', $regions);
            }
            $offer->country_codes = $regionCodesString;
        } else {
            // Region-wise users automatically get their region assigned
            $offer->country_codes = $user->assigned_regions;
        }

        // $offer->start_date = $request->start_date;
        // $offer->end_date = $request->end_date;
        $offer->active = $request->has('active') ? 1 : 0;
        $offer->sort = $request->sort ?? 0;
        $offer->updated_by = Auth::id();
        if ($request->hasFile('logo')) {
            // File object
            $file = $request->file('logo');

            // Unique filename
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Path inside public folder
            $file->move(public_path('uploads/store_icons'), $fileName);

            // Save in DB - path relative to uploads folder
            $offer->logo = 'store_icons/' . $fileName;
        }
        $offer->save();

        return redirect()->route('admin.offer.index')->with('success', 'Offer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Offer $offer)
    {
        // Permanently delete the offer from the database
        $offer->forceDelete();

        return redirect()->route('admin.offer.index')->with('success', 'Offer deleted successfully.');
    }

    /**
     * Update the sort order of offers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSort(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:offers,id'
        ]);

        foreach ($request->order as $index => $offerId) {
            Offer::where('id', $offerId)->update(['sort' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Sort order updated successfully']);
    }

    /**
     * Update the sort order of a single offer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSingleSort(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:offers,id',
            'sort' => 'required|integer'
        ]);

        try {
            $offer = Offer::find($request->id);
            if (!$offer) {
                return response()->json(['success' => false, 'message' => 'Offer not found'], 404);
            }
            $offer->sort = $request->sort;
            $offer->save();

            return response()->json(['success' => true, 'message' => 'Sort order updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
