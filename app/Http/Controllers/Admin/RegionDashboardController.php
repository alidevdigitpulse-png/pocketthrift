<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Region;
use App\Models\Blog;
use App\Models\TrendingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegionDashboardController extends Controller
{
    /**
     * Display the region-wise dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Determine if user is admin (can see all data)
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        // Initialize query builders
        $storesQuery = Store::query();
        $categoriesQuery = Category::query();
        $offersQuery = Offer::query();
        
        // Apply region-based filtering for non-admin users
        if (!$isAdmin) {
            // Get user's regions
            $userRegionCodes = $this->getUserRegionCodes($user);
            
            if (!empty($userRegionCodes)) {
                $storesQuery->byRegionCodes($userRegionCodes);
                $categoriesQuery->byRegionCodes($userRegionCodes);
                $offersQuery->byRegionCodes($userRegionCodes);
            } else {
                // If user has no regions, show empty results
                $storesQuery->whereRaw('1 = 0');
                $categoriesQuery->whereRaw('1 = 0');
                $offersQuery->whereRaw('1 = 0');
            }
        }
        
        // Get counts for dashboard
        $storesCount = $storesQuery->count();
        $categoriesCount = $categoriesQuery->count();
        $offersCount = $offersQuery->count();
        
        // Get recent items
        $recentStores = $storesQuery->with('category')->orderBy('created_at', 'desc')->limit(5)->get();
        $recentCategories = $categoriesQuery->orderBy('created_at', 'desc')->limit(5)->get();
        $recentOffers = $offersQuery->with('store')->orderBy('created_at', 'desc')->limit(5)->get();
        
        // Get user's regions for trending items
        $userRegionIds = [];
        if (!$isAdmin) {
            $userRegionCodes = $this->getUserRegionCodes($user);
            if (!empty($userRegionCodes)) {
                $userRegionIds = Region::whereIn('code', $userRegionCodes)->pluck('id')->toArray();
            }
        } else {
            // Admin can see trending items from all regions, default to showing first region's items
            $userRegionIds = Region::pluck('id')->toArray();
        }

        // Get manually selected trending items for the region
        if (!empty($userRegionIds)) {
            $regionId = $userRegionIds[0]; // Use first region for non-admin users
            
            // Get trending items for stores
            $trendingStoreItems = TrendingItem::byRegionAndType($regionId, 'store')
                ->get();
            
            $trendingStores = collect();
            foreach ($trendingStoreItems as $trendingItem) {
                $item = $trendingItem->item; // Load the related item
                if ($item) {
                    $item->load('category'); // Then load the nested relationship
                    $trendingStores->push($item);
                }
            }
            $trendingStores = $trendingStores->take(5);

            // Get trending items for categories
            $trendingCategoryItems = TrendingItem::byRegionAndType($regionId, 'category')
                ->get();
            
            $trendingCategories = collect();
            foreach ($trendingCategoryItems as $trendingItem) {
                $item = $trendingItem->item; // Load the related item
                if ($item) {
                    $trendingCategories->push($item);
                }
            }
            $trendingCategories = $trendingCategories->take(5);

            // Get trending items for offers
            $trendingOfferItems = TrendingItem::byRegionAndType($regionId, 'offer')
                ->get();
            
            $trendingOffers = collect();
            foreach ($trendingOfferItems as $trendingItem) {
                $item = $trendingItem->item; // Load the related item
                if ($item) {
                    $item->load('store'); // Then load the nested relationship
                    $trendingOffers->push($item);
                }
            }
            $trendingOffers = $trendingOffers->take(5);
        } else {
            // Fallback to algorithmic trending if no regions
            $trendingStores = $storesQuery->with('category')->where('active', true)->orderBy('updated_at', 'desc')->limit(5)->get();
            $trendingCategories = $categoriesQuery->where('active', true)->orderBy('updated_at', 'desc')->limit(5)->get();
            $trendingOffers = $offersQuery->with('store')
                ->where('active', true)
                ->where(function($query) {
                    $query->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                })
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get();
        }
        
        // Get all regions for filter
        $regions = Region::all();
        
        // Get user's specific regions if not admin
        $userRegions = $isAdmin ? $regions : $this->getUserRegions($user);
        
        return view('admin.region-dashboard.index', compact(
            'storesCount',
            'categoriesCount',
            'offersCount',
            'recentStores',
            'recentCategories',
            'recentOffers',
            'trendingStores',
            'trendingCategories',
            'trendingOffers',
            'regions',
            'userRegions',
            'isAdmin'
        ));
    }
    
    /**
     * Get user's region codes
     */
    private function getUserRegionCodes($user)
    {
        // For single region assignment, return as array for compatibility
        if (!empty($user->assigned_regions)) {
            // assigned_regions may be stored as a comma-separated string or JSON/array
            $val = $user->assigned_regions;
            if (is_array($val)) {
                return $val;
            }
            // Try JSON decode
            $decoded = json_decode($val, true);
            if (json_last_error() === JSON_ERROR_NONE && !empty($decoded)) {
                return is_array($decoded) ? $decoded : [$decoded];
            }
            // If comma-separated
            if (is_string($val) && strpos($val, ',') !== false) {
                return array_filter(array_map('trim', explode(',', $val)));
            }
            // Single scalar value
            return [$val];
        }

        // Fallback: try to use session current_region if available (useful for users who switch region in UI)
        $sessionRegion = session('current_region') ?: session('region');
        if (!empty($sessionRegion)) {
            return [$sessionRegion];
        }

        return [];
    }
    
    /**
     * Get user's regions model instances
     */
    private function getUserRegions($user)
    {
        $regionCodes = $this->getUserRegionCodes($user);
        if (empty($regionCodes)) {
            return collect(); // Return empty collection if no regions assigned
        }
        return Region::whereIn('code', $regionCodes)->get();
    }
    
    /**
     * Filter data by region
     */
    public function filterByRegion(Request $request)
    {
        $selectedRegion = $request->input('region');
        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        $storesQuery = Store::query();
        $categoriesQuery = Category::query();
        $offersQuery = Offer::query();
        
        if (!$isAdmin && $selectedRegion) {
            $regionCodes = [$selectedRegion];
            $storesQuery->byRegionCodes($regionCodes);
            $categoriesQuery->byRegionCodes($regionCodes);
            $offersQuery->byRegionCodes($regionCodes);
        } elseif (!$selectedRegion && !$isAdmin) {
            $userRegionCodes = $this->getUserRegionCodes($user);
            if (!empty($userRegionCodes)) {
                $storesQuery->byRegionCodes($userRegionCodes);
                $categoriesQuery->byRegionCodes($userRegionCodes);
                $offersQuery->byRegionCodes($userRegionCodes);
            } else {
                // If user has no regions, show empty results
                $storesQuery->whereRaw('1 = 0');
                $categoriesQuery->whereRaw('1 = 0');
                $offersQuery->whereRaw('1 = 0');
            }
        }
        
        $recentStores = $storesQuery->with('category')->orderBy('created_at', 'desc')->limit(5)->get();
        $recentCategories = $categoriesQuery->orderBy('created_at', 'desc')->limit(5)->get();
        $recentOffers = $offersQuery->with('store')->orderBy('created_at', 'desc')->limit(5)->get();
        
        // Return partial view or JSON based on request
        if ($request->ajax()) {
            return response()->json([
                'recentStores' => $recentStores,
                'recentCategories' => $recentCategories,
                'recentOffers' => $recentOffers
            ]);
        }
        
        return view('admin.region-dashboard.partials.data-tables', compact(
            'recentStores', 
            'recentCategories', 
            'recentOffers'
        ));
    }

    /**
     * Show the form for selecting trending items
     */
    public function trendingForm(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

        // Determine region for the user
        $regionId = null;
        if (!$isAdmin) {
            $userRegionCodes = $this->getUserRegionCodes($user);
            if (!empty($userRegionCodes)) {
                $region = Region::where('code', $userRegionCodes[0])->first();
                $regionId = $region ? $region->id : null;
            }
        }

        // Get available items for trending selection based on region
        $storesQuery = Store::query();
        $categoriesQuery = Category::query();
        $offersQuery = Offer::query();

        if (!$isAdmin) {
            // For non-admin users, get their region codes and filter by them
            $userRegionCodes = $this->getUserRegionCodes($user);
            if (!empty($userRegionCodes)) {
                $storesQuery->byRegionCodes($userRegionCodes);
                $offersQuery->byRegionCodes($userRegionCodes);
                
                // For categories, only include region-specific categories for this user.
                // Do NOT fall back to all active categories — if none exist for the region, show none.
                try {
                    // Try filtering by region codes first
                    $testQuery = clone $categoriesQuery;
                    $testQuery->byRegionCodes($userRegionCodes);
                    $testCategories = $testQuery->limit(1)->get();

                    if ($testCategories->count() > 0) {
                        // If region-specific categories exist, use region filter
                        $categoriesQuery->byRegionCodes($userRegionCodes);
                    } else {
                        // If no region-specific categories, show no categories
                        $categoriesQuery->whereRaw('1 = 0');
                    }
                } catch (\Exception $e) {
                    // If there's an error with region filtering, show no categories to be safe
                    $categoriesQuery->whereRaw('1 = 0');
                }
            } else {
                // If user has no regions assigned, show no items
                $storesQuery->whereRaw('1 = 0');
                $categoriesQuery->whereRaw('1 = 0');
                $offersQuery->whereRaw('1 = 0');
            }
        } elseif ($regionId) {
            // For admin users viewing specific region
            $region = Region::find($regionId);
            if ($region && !empty($region->code)) {
                $storesQuery->byRegionCodes([$region->code]);
                $offersQuery->byRegionCodes([$region->code]);
                
                // For admin users viewing a specific region: require region-specific categories only.
                try {
                    // Try filtering by region codes first
                    $testQuery = clone $categoriesQuery;
                    $testQuery->byRegionCodes([$region->code]);
                    $testCategories = $testQuery->limit(1)->get();

                    if ($testCategories->count() > 0) {
                        // If region-specific categories exist, use region filter
                        $categoriesQuery->byRegionCodes([$region->code]);
                    } else {
                        // If no region-specific categories, show no categories for that region
                        $categoriesQuery->whereRaw('1 = 0');
                    }
                } catch (\Exception $e) {
                    // If there's an error with region filtering, show no categories to be safe
                    $categoriesQuery->whereRaw('1 = 0');
                }
            } else {
                // If region not found, don't show any categories
                $categoriesQuery->whereRaw('1 = 0');
            }
        }

        // Apply search filters if provided
        $search = $request->get('search', '');
        
        if (!empty($search)) {
            $storesQuery->where('title', 'LIKE', "%{$search}%");
            $categoriesQuery->where('title', 'LIKE', "%{$search}%");
            $offersQuery->where('title', 'LIKE', "%{$search}%");
        }
        
        // Separate search per type
        $storeSearch = $request->get('store_search', '');
        if (!empty($storeSearch)) {
            $storesQuery->where('title', 'LIKE', "%{$storeSearch}%");
        }
        
        $categorySearch = $request->get('category_search', '');
        if (!empty($categorySearch)) {
            $categoriesQuery->where('title', 'LIKE', "%{$categorySearch}%");
        }
        
        $offerSearch = $request->get('offer_search', '');
        if (!empty($offerSearch)) {
            $offersQuery->where('title', 'LIKE', "%{$offerSearch}%");
        }

        // Get available items for trending selection based on region (with search applied)
        $blogsQuery = Blog::query();
        
        if (!$isAdmin) {
            // For non-admin users, get their region codes and filter by them
            $userRegionCodes = $this->getUserRegionCodes($user);
            if (!empty($userRegionCodes)) {
                $blogsQuery->byRegionCodes($userRegionCodes);
            } else {
                // If user has no regions assigned, show no items
                $blogsQuery->whereRaw('1 = 0');
            }
        } elseif ($regionId) {
            // For admin users viewing specific region
            $region = Region::find($regionId);
            if ($region && !empty($region->code)) {
                $blogsQuery->byRegionCodes([$region->code]);
            } else {
                // If region not found, don't show any blogs
                $blogsQuery->whereRaw('1 = 0');
            }
        }
        
        // Apply search filters if provided
        $search = $request->get('search', '');
        
        if (!empty($search)) {
            $storesQuery->where('title', 'LIKE', "%{$search}%");
            $categoriesQuery->where('title', 'LIKE', "%{$search}%");
            $offersQuery->where('title', 'LIKE', "%{$search}%");
            $blogsQuery->where('title', 'LIKE', "%{$search}%");
        }
        
        // Separate search per type
        $storeSearch = $request->get('store_search', '');
        if (!empty($storeSearch)) {
            $storesQuery->where('title', 'LIKE', "%{$storeSearch}%");
        }
        
        $categorySearch = $request->get('category_search', '');
        if (!empty($categorySearch)) {
            $categoriesQuery->where('title', 'LIKE', "%{$categorySearch}%");
        }
        
        $offerSearch = $request->get('offer_search', '');
        if (!empty($offerSearch)) {
            $offersQuery->where('title', 'LIKE', "%{$offerSearch}%");
        }
        
        $blogSearch = $request->get('blog_search', '');
        if (!empty($blogSearch)) {
            $blogsQuery->where('title', 'LIKE', "%{$blogSearch}%");
        }

        // Get all items for selection with relationships
        $allStores = $storesQuery->with('category')->where('active', true)->orderBy('title')->get();
        $allCategories = $categoriesQuery->where('active', true)->orderBy('title')->get();
        $allOffers = $offersQuery->with('store')->where('active', true)
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->orderBy('title')
            ->get();
        $allBlogs = $blogsQuery->with('category')->where('active', true)->orderBy('title')->get();

        // Get distinct categories for store filtering (based on currently available stores)
        $availableStoreCategories = collect();
        if ($allStores->count() > 0) {
            $storeCategoryIds = $allStores->pluck('category_id')->unique()->filter();
            if ($storeCategoryIds->count() > 0) {
                // Get only categories that are assigned to current stores and in user's region
                $availableStoreCategories = Category::whereIn('id', $storeCategoryIds)
                    ->where('active', true)
                    ->orderBy('title')
                    ->get();
            }
        }

        // Get distinct stores for offer filtering (based on currently available offers)
        $availableOfferStores = collect();
        if ($allOffers->count() > 0) {
            $offerStoreIds = $allOffers->pluck('store_id')->unique()->filter();
            if ($offerStoreIds->count() > 0) {
                // Get only stores that are assigned to current offers and in user's region
                $availableOfferStores = Store::whereIn('id', $offerStoreIds)
                    ->with('category') // Eager load category for display consistency
                    ->where('active', true)
                    ->orderBy('title')
                    ->get();
            }
        }
        
        // Get distinct categories for blog filtering (based on currently available blogs)
        $availableBlogCategories = collect();
        if ($allBlogs->count() > 0) {
            $blogCategoryIds = $allBlogs->pluck('category_id')->unique()->filter();
            if ($blogCategoryIds->count() > 0) {
                // Get only categories that are assigned to current blogs and in user's region
                $availableBlogCategories = Category::whereIn('id', $blogCategoryIds)
                    ->where('active', true)
                    ->orderBy('title')
                    ->get();
            }
        }

        // If no region-specific categories were found, prepare a fallback of global active categories
        // Only present fallback categories to admins; non-admin users should not see selectable global categories.
        $categoriesFallback = false;
        $fallbackCategories = collect();
        if ($allCategories->count() === 0 && $isAdmin) {
            $fallbackCategories = Category::where('active', true)->orderBy('title')->get();
            if ($fallbackCategories->count() > 0) {
                $categoriesFallback = true;
                // Default to showing fallback in the UI so admin can still pick categories
                $allCategories = $fallbackCategories;
            }
        }

        // Get currently selected trending items for this region
        $currentTrendingStores = collect();
        $currentTrendingCategories = collect();
        $currentTrendingOffers = collect();
        $currentTrendingBlogs = collect();

        if ($regionId) {
            $currentTrendingStores = TrendingItem::byRegionAndType($regionId, 'store')
                ->with('item')
                ->orderBy('position')
                ->get()->pluck('item_id')->toArray();

            $currentTrendingCategories = TrendingItem::byRegionAndType($regionId, 'category')
                ->with('item')
                ->orderBy('position')
                ->get()->pluck('item_id')->toArray();

            $currentTrendingOffers = TrendingItem::byRegionAndType($regionId, 'offer')
                ->with('item')
                ->orderBy('position')
                ->get()->pluck('item_id')->toArray();

            $currentTrendingBlogs = TrendingItem::byRegionAndType($regionId, 'blog')
                ->with('item')
                ->orderBy('position')
                ->get()->pluck('item_id')->toArray();
        }

        return view('admin.region-dashboard.trending-form', compact(
            'allStores',
            'allCategories',
            'allOffers',
            'allBlogs',
            'categoriesFallback',
            'fallbackCategories',
            'currentTrendingStores',
            'currentTrendingCategories',
            'currentTrendingOffers',
            'currentTrendingBlogs',
            'regionId',
            'isAdmin',
            'availableStoreCategories',
            'availableOfferStores',
            'availableBlogCategories'
        ));
    }

    /**
     * Save the trending items selection
     */
    public function saveTrending(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

        $request->validate([
            'region_id' => 'required|exists:regions,id',
            'trending_stores' => 'array|max:5',
            'trending_stores.*' => 'integer',
            'trending_categories' => 'array|max:5',
            'trending_categories.*' => 'integer',
            'trending_offers' => 'array|max:5',
            'trending_offers.*' => 'integer',
            'trending_blogs' => 'array|max:5',
            'trending_blogs.*' => 'integer',
        ]);

        $regionId = $request->input('region_id');

        // Make sure the user has permission to modify this region
        $allowedRegionIds = [];
        if (!$isAdmin) {
            $userRegionCodes = $this->getUserRegionCodes($user);
            if (!empty($userRegionCodes)) {
                $allowedRegionIds = Region::whereIn('code', $userRegionCodes)->pluck('id')->toArray();
            }
        } else {
            $allowedRegionIds = Region::pluck('id')->toArray();
        }

        if (!in_array($regionId, $allowedRegionIds)) {
            return redirect()->back()->with('error', 'You do not have permission to update trending items for this region.');
        }

        // Get user's regions for validation
        $userRegionCodes = $this->getUserRegionCodes($user);
        if (empty($userRegionCodes) && !$isAdmin) {
            return redirect()->back()->with('error', 'You do not have permission to update trending items.');
        }

        // For non-admin users, validate that selected items belong to their regions
        if (!$isAdmin) {
            if (!empty($userRegionCodes) && $request->has('trending_stores')) {
                $selectedStoreIds = $request->input('trending_stores');
                if (!empty($selectedStoreIds)) {
                    $validStores = Store::byRegionCodes($userRegionCodes)->whereIn('id', $selectedStoreIds)->pluck('id')->toArray();
                    $invalidStoreIds = array_diff($selectedStoreIds, $validStores);
                    
                    if (!empty($invalidStoreIds)) {
                        return redirect()->back()->with('error', 'Some selected stores do not belong to your region.');
                    }
                }
            }

            if (!empty($userRegionCodes) && $request->has('trending_categories')) {
                $selectedCategoryIds = $request->input('trending_categories');
                if (!empty($selectedCategoryIds)) {
                    $validCategories = Category::byRegionCodes($userRegionCodes)->whereIn('id', $selectedCategoryIds)->pluck('id')->toArray();
                    $invalidCategoryIds = array_diff($selectedCategoryIds, $validCategories);
                    
                    if (!empty($invalidCategoryIds)) {
                        return redirect()->back()->with('error', 'Some selected categories do not belong to your region.');
                    }
                }
            }

            if (!empty($userRegionCodes) && $request->has('trending_offers')) {
                $selectedOfferIds = $request->input('trending_offers');
                if (!empty($selectedOfferIds)) {
                    $validOffers = Offer::byRegionCodes($userRegionCodes)->whereIn('id', $selectedOfferIds)->pluck('id')->toArray();
                    $invalidOfferIds = array_diff($selectedOfferIds, $validOffers);
                    
                    if (!empty($invalidOfferIds)) {
                        return redirect()->back()->with('error', 'Some selected offers do not belong to your region.');
                    }
                }
            }
        } else {
            // For admin users, validate that items exist in the system
            if ($request->has('trending_stores')) {
                $selectedStoreIds = $request->input('trending_stores');
                if (!empty($selectedStoreIds)) {
                    $validStores = Store::whereIn('id', $selectedStoreIds)->pluck('id')->toArray();
                    $invalidStoreIds = array_diff($selectedStoreIds, $validStores);
                    
                    if (!empty($invalidStoreIds)) {
                        return redirect()->back()->with('error', 'Some selected stores do not exist.');
                    }
                }
            }

            if ($request->has('trending_categories')) {
                $selectedCategoryIds = $request->input('trending_categories');
                if (!empty($selectedCategoryIds)) {
                    $validCategories = Category::whereIn('id', $selectedCategoryIds)->pluck('id')->toArray();
                    $invalidCategoryIds = array_diff($selectedCategoryIds, $validCategories);
                    
                    if (!empty($invalidCategoryIds)) {
                        return redirect()->back()->with('error', 'Some selected categories do not exist.');
                    }
                }
            }

            if ($request->has('trending_offers')) {
                $selectedOfferIds = $request->input('trending_offers');
                if (!empty($selectedOfferIds)) {
                    $validOffers = Offer::whereIn('id', $selectedOfferIds)->pluck('id')->toArray();
                    $invalidOfferIds = array_diff($selectedOfferIds, $validOffers);
                    
                    if (!empty($invalidOfferIds)) {
                        return redirect()->back()->with('error', 'Some selected offers do not exist.');
                    }
                }
            }

            if ($request->has('trending_blogs')) {
                $selectedBlogIds = $request->input('trending_blogs');
                if (!empty($selectedBlogIds)) {
                    $validBlogs = Blog::whereIn('id', $selectedBlogIds)->pluck('id')->toArray();
                    $invalidBlogIds = array_diff($selectedBlogIds, $validBlogs);

                    if (!empty($invalidBlogIds)) {
                        return redirect()->back()->with('error', 'Some selected blogs do not exist.');
                    }
                }
            }
        }

        // Clear existing trending items for this region and type
        TrendingItem::where('region_id', $regionId)->delete();

        // Add new trending stores
        if ($request->has('trending_stores')) {
            foreach ($request->input('trending_stores') as $index => $storeId) {
                TrendingItem::create([
                    'item_type' => 'store',
                    'item_id' => $storeId,
                    'region_id' => $regionId,
                    'user_id' => $user->id,
                    'position' => $index + 1
                ]);
            }
        }

        // Add new trending categories
        if ($request->has('trending_categories')) {
            foreach ($request->input('trending_categories') as $index => $categoryId) {
                TrendingItem::create([
                    'item_type' => 'category',
                    'item_id' => $categoryId,
                    'region_id' => $regionId,
                    'user_id' => $user->id,
                    'position' => $index + 1
                ]);
            }
        }

        // Add new trending offers
        if ($request->has('trending_offers')) {
            foreach ($request->input('trending_offers') as $index => $offerId) {
                TrendingItem::create([
                    'item_type' => 'offer',
                    'item_id' => $offerId,
                    'region_id' => $regionId,
                    'user_id' => $user->id,
                    'position' => $index + 1
                ]);
            }
        }

        // Add new trending blogs
        if ($request->has('trending_blogs')) {
            foreach ($request->input('trending_blogs') as $index => $blogId) {
                TrendingItem::create([
                    'item_type' => 'blog',
                    'item_id' => $blogId,
                    'region_id' => $regionId,
                    'user_id' => $user->id,
                    'position' => $index + 1
                ]);
            }
        }

        return redirect()->back()->with('success', 'Trending items updated successfully!');
    }
}