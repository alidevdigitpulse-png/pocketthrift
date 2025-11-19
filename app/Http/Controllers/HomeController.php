<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;

use App\Models\Region;
use App\Services\RegionService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $regionService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RegionService $regionService)
    {
        $this->regionService = $regionService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $currentRegion = $this->regionService->getCurrentRegionCode();

        // Find the region ID based on the code
        $region   = \App\Models\Region::where('code', $currentRegion)->first();
        $regionId = $region ? $region->id : null;

        // Get manually selected trending items for the region, or fallback to algorithmic trending
        if ($regionId) {
            // Get trending items for stores
            $trendingStoreItems = \App\Models\TrendingItem::byRegionAndType($regionId, 'store')
                ->with('item.category') // Eager load item and its category relationship
                ->orderBy('position', 'asc')
                ->take(5) // Limit to 5 trending items
                ->get();

            $trendingStores = collect();
            foreach ($trendingStoreItems as $trendingItem) {
                $item = $trendingItem->item;
                if ($item && $item->active && $trendingStores->count() < 5) {
                    $trendingStores->push($item);
                }
            }
            $trendingStores = $trendingStores->unique('id')->take(5);

            // Get trending items for categories
            $trendingCategoryItems = \App\Models\TrendingItem::byRegionAndType($regionId, 'category')
                ->with('item') // Eager load item
                ->orderBy('position', 'asc')
                ->take(5) // Limit to 5 trending items
                ->get();

            $trendingCategories = collect();
            foreach ($trendingCategoryItems as $trendingItem) {
                $item = $trendingItem->item;
                if ($item && $item->active && $trendingCategories->count() < 5) {
                    $trendingCategories->push($item);
                }
            }

            // Get trending items for offers
            $trendingOfferItems = \App\Models\TrendingItem::byRegionAndType($regionId, 'offer')
                ->with('item.store') // Eager load item and its store relationship
                ->orderBy('position', 'asc')
                ->take(10) // Limit to 10 trending items
                ->get();

            $trendingOffers = collect();
            foreach ($trendingOfferItems as $trendingItem) {
                $item = $trendingItem->item;
                if ($item && $item->active && $trendingOffers->count() < 10) {
                    $trendingOffers->push($item);
                }
            }

            $trendingOffers = $trendingOffers->unique('id')->take(10);

            // If there are no manually selected trending items, fallback to algorithmic trending
            if ($trendingStores->count() < 5) {
                $needed = 5 - $trendingStores->count();
                $existingIds = $trendingStores->pluck('id')->all();

                $trendingStores = \App\Models\Store::where('active', true)
                    ->byRegionCodes([$currentRegion])
                    ->whereNotIn('id', $existingIds)
                    ->orderBy('updated_at', 'desc')
                    ->take($needed)
                    ->get();
            }

            if ($trendingCategories->count() < 5) {
                $needed = 5 - $trendingCategories->count();
                $existingIds = $trendingCategories->pluck('id')->all();

                $trendingCategories = \App\Models\Category::where('active', true)
                    ->byRegionCodes([$currentRegion])
                    ->whereNotIn('id', $existingIds)
                    ->orderBy('updated_at', 'desc')
                    ->take($needed)
                    ->get();
            }

            // If we still don't have enough offers, supplement with algorithmic trending
            if ($trendingOffers->count() < 10) {
                $needed = 10 - $trendingOffers->count();
                $existingIds = $trendingOffers->pluck('id')->all();

                $fallbackOffers = \App\Models\Offer::where('active', true)
                    ->byRegionCodes([$currentRegion])
                    ->whereNotIn('id', $existingIds) // Exclude already added offers
                    ->where(function ($query) {
                        $query->whereNull('end_date')
                            ->orWhere('end_date', '>=', now());
                    })
                    ->orderBy('updated_at', 'desc')
                    ->take($needed)
                    ->get();
                $trendingOffers = $trendingOffers->concat($fallbackOffers);
            }
        } else {
            // Fallback to algorithmic trending if region not found
            $trendingStores = \App\Models\Store::where('active', true)
                ->byRegionCodes([$currentRegion])
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

            $trendingCategories = \App\Models\Category::where('active', true)
                ->byRegionCodes([$currentRegion])
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

            $trendingOffers = \App\Models\Offer::where('active', true)
                ->byRegionCodes([$currentRegion])
                ->where(function ($query) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', now());
                })
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();
        }

        return view('welcome', compact('trendingStores', 'trendingCategories', 'trendingOffers', 'currentRegion'));
    }

    public function categories(Request $request)
    {
        $currentRegion = $this->regionService->getCurrentRegionCode();

        $categories = Category::where('active', true)
            ->where(function ($query) use ($currentRegion) {
                $query->whereNull('country_codes')
                    ->orWhere('country_codes', '[]')
                    ->orWhere('country_codes', '')
                    ->orWhereRaw("JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?)", [json_encode($currentRegion)])
                    ->orWhereRaw("FIND_IN_SET(?, country_codes)", [$currentRegion]);
            });

        // Apply search filter if provided
        if ($request->has('search') && ! empty($request->search)) {
            $search     = $request->search;
            $categories = $categories->where('title', 'LIKE', "%{$search}%");
        }

        // Apply alphabetical filter if provided
        if ($request->has('letter') && $request->letter && $request->letter !== 'all') {
            $letter     = $request->letter;
            $categories = $categories->where('title', 'LIKE', "{$letter}%");
        }

        $categories = $categories->orderBy('sort', 'asc')->get();

        return view('categories', compact('categories'));
    }

    public function categoryDetail($category = null)
    {
        // Get all route parameters
        $routeParameters = request()->route()->parameters();

        // Determine category slug/ID and region based on route parameters
        if (isset($routeParameters['region']) && isset($routeParameters['category'])) {
            // Regional route: {region}/categories/{category}
            $categorySlug  = $routeParameters['category'];
            $currentRegion = $routeParameters['region'];
        } elseif (isset($routeParameters['category'])) {
            // Default route: categories/{category}
            $categorySlug  = $routeParameters['category'];
            $currentRegion = 'us'; // default fallback
        } else {
            // Fallback to manual parsing if parameters not clearly defined
            $categorySlug = $category;
            $segment1     = request()->segment(1);
            $validRegions = $this->regionService->getRegionCodes();

            if (in_array($segment1, $validRegions)) {
                $currentRegion = $segment1;
            } else {
                $currentRegion = 'us';
            }
        }

        // Try matching with and without leading slash
        $slugWithSlash = '/' . ltrim($categorySlug, '/');

        // Fetch the category by either ID or slug
        $categoryRecord = \App\Models\Category::where('active', true)
            ->where(function ($query) use ($categorySlug, $slugWithSlash) {
                $query->where('id', $categorySlug)
                    ->orWhere('url_slug', $categorySlug)
                    ->orWhere('url_slug', $slugWithSlash);
            })
            ->first();

        if (! $categoryRecord) {
            abort(404, 'Category not found');
        }

        // Check region availability
        $isAvailableInRegion = true;
        $rawCountryCodes     = $categoryRecord->attributes['country_codes'] ?? $categoryRecord->getOriginal('country_codes') ?? $categoryRecord->country_codes;
        if (is_array($rawCountryCodes)) {
            $codeArray = $rawCountryCodes;
        } else {
            if (! is_null($rawCountryCodes) && $rawCountryCodes !== '' && $rawCountryCodes !== '[]') {
                $decoded = json_decode($rawCountryCodes, true);
                if (is_array($decoded)) {
                    $codeArray = $decoded;
                } else {
                    $cleanString = trim(str_replace(['[', ']', '"', "'"], '', $rawCountryCodes), ' ,');
                    if (! empty($cleanString)) {
                        $codeArray = array_filter(array_map('trim', explode(',', $cleanString)));
                    } else {
                        $codeArray = null;
                    }
                }
            } else {
                $codeArray = null;
            }
        }

        if ($codeArray) {
            $isAvailableInRegion = in_array($currentRegion, $codeArray, true);
        }

        if (! $isAvailableInRegion) {
            abort(404, 'Category not available in your region');
        }

        return view('category-detail', compact('categoryRecord', 'currentRegion'));
    }

    public function stores(Request $request)
    {
        $currentRegion = $this->regionService->getCurrentRegionCode();

        // Always filter by current region, including US
        $stores = \App\Models\Store::where('active', true)->byRegionCodes([$currentRegion]);

        // Apply search filter if provided
        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $stores = $stores->where('title', 'LIKE', "%{$search}%");
        }

        // Apply alphabetical filter if provided
        if ($request->has('letter') && $request->letter && $request->letter !== 'all') {
            $letter = $request->letter;
            $stores = $stores->where('title', 'LIKE', "{$letter}%");
        }

        $stores = $stores->orderBy('sort', 'asc')->get();

        return view('stores', compact('stores'));
    }

    public function blogs(Request $request)
    {
        $currentRegion = $this->regionService->getCurrentRegionCode();

        // Fetch active blogs from the database using the new fields, filtered by region
        $blogs = \App\Models\Blog::where('active', true)
            ->where(function ($query) use ($currentRegion) {
                $query->whereNull('country_codes')
                    ->orWhere('country_codes', '[]')
                    ->orWhere('country_codes', '')
                    ->orWhereRaw("JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?)", [json_encode($currentRegion)])
                    ->orWhereRaw("FIND_IN_SET(?, country_codes)", [$currentRegion]);
            })
            ->orderBy('id', 'desc')
            ->paginate(8); // 8 blogs per page

        return view('blogs', compact('blogs'));
    }

    public function blogDetail($slug = null)
    {
        // Get all route parameters
        $routeParameters = request()->route()->parameters();

        // Determine slug and region based on route parameters
        if (isset($routeParameters['region']) && isset($routeParameters['slug'])) {
            // This is a regional route: {region}/blog/{slug}
            $blogSlug      = $routeParameters['slug'];
            $currentRegion = $routeParameters['region'];
        } elseif (isset($routeParameters['slug'])) {
            // This is the default route: blog/{slug}
            $blogSlug      = $routeParameters['slug'];
            $currentRegion = 'us'; // Default to US for non-regional routes
        } else {
            // Fallback to manually parsing the slug parameter that was passed to the function
            $blogSlug     = $slug;
            $segment1     = request()->segment(1);
            $validRegions = $this->regionService->getRegionCodes();

            if (in_array($segment1, $validRegions)) {
                // Regional route: {region}/blog/{slug}
                $currentRegion = $segment1;
            } else {
                // Default route: blog/{slug}
                $currentRegion = 'us';
            }
        }

        // Fetch the specific blog by slug (without region filtering for initial fetch)
        // Since the database might have slugs with leading slashes, try both formats
        $blogWithSlash = '/' . $blogSlug;
        $blog          = \App\Models\Blog::where(function ($query) use ($blogSlug, $blogWithSlash) {
            $query->where('url_slug', $blogSlug)   // without slash
                ->orWhere('url_slug', $blogWithSlash); // with slash
        })
            ->where('active', true)
            ->first();

        if (! $blog) {
            abort(404, 'Blog not found');
        }

                                     // Check if the blog is available in the current region
        $isAvailableInRegion = true; // Default to available globally

        // Access the raw country codes value (before accessor processing)
        $rawCountryCodes = $blog->attributes['country_codes'] ?? $blog->getOriginal('country_codes') ?? $blog->country_codes;

        // Check if raw value is already an array (due to accessor being called before), or a string
        if (is_array($rawCountryCodes)) {
            // Value was already processed by accessor
            $codeArray = $rawCountryCodes;
        } else {
            // Value is a string, process it normally
            if (! is_null($rawCountryCodes) && $rawCountryCodes !== '' && $rawCountryCodes !== '[]') {
                $decoded = json_decode($rawCountryCodes, true);
                if (is_array($decoded)) {
                    // It's a JSON array
                    $codeArray = $decoded;
                } else {
                    // It's a comma-separated string, cleanup and check
                    $cleanString = trim(str_replace(['[', ']', '"', "'"], '', $rawCountryCodes), ' ,');
                    if (! empty($cleanString)) {
                        $codeArray = array_filter(array_map('trim', explode(',', $cleanString)));
                    } else {
                        $codeArray = null; // Set to null to skip the check
                    }
                }
            } else {
                $codeArray = null; // Set to null to skip the check
            }
        }

        // Only check availability if we have country codes to check against
        if ($codeArray) {
            $isAvailableInRegion = in_array($currentRegion, $codeArray, true);
        }

        if (! $isAvailableInRegion) {
            abort(404, 'Blog not available in your region');
        }

        return view('blog-detail', compact('blog', 'currentRegion'));
    }

    public function terms()
    {
        // Get current region code (e.g., "us", "fr", "pk", etc.)
        $currentRegion = $this->regionService->getCurrentRegionCode();

        // Helper query for flexible matching
        $termsQuery = function ($query) {
            $query->where('slug', 'like', '%terms%')
                ->orWhere('name', 'like', '%terms%');
        };

        // Try to find region-specific Terms & Conditions page
        $page = Page::whereHas('region', function ($query) use ($currentRegion) {
            $query->where('code', $currentRegion);
        })
            ->where($termsQuery)
            ->first();

        // Fallback to default region (US) if not found
        if (! $page) {
            $page = Page::whereHas('region', function ($query) {
                $query->where('code', 'us');
            })
                ->where($termsQuery)
                ->first();
        }

        // All regions for dropdown
        $regions = Region::all();

        // Return the Terms view
        return view('terms-of-use', compact('page', 'regions', 'currentRegion'));
    }

    public function aboutUs()
    {
        // Get current region from the region service
        $currentRegion = $this->regionService->getCurrentRegionCode();

        // Find the About Us page for the current region with flexible matching
        $page = Page::whereHas('region', function ($query) use ($currentRegion) {
            $query->where('code', $currentRegion);
        })
            ->where(function ($query) {
                $query->where('slug', 'like', '%about%')
                    ->orWhere('name', 'LIKE', '%About%')
                    ->orWhere('name', 'LIKE', '%about%')
                    ->orWhere('slug', 'like', '%About%');
            })
            ->first();

        // If no page found for current region, fall back to default region (US)
        if (! $page) {
            $page = Page::whereHas('region', function ($query) {
                $query->where('code', 'us');
            })
                ->where(function ($query) {
                    $query->where('slug', 'like', '%about%')
                        ->orWhere('name', 'LIKE', '%About%')
                        ->orWhere('name', 'LIKE', '%about%')
                        ->orWhere('slug', 'like', '%About%');
                })
                ->first();
        }

        $regions = Region::all(); // For region selector dropdown

        return view('about-us', compact('page', 'regions', 'currentRegion'));
    }

    public function privacy()
    {
        // Get current region from the region service
        $currentRegion = $this->regionService->getCurrentRegionCode();

        // Find the Privacy Policy page for the current region with flexible matching
        $page = Page::whereHas('region', function ($query) use ($currentRegion) {
            $query->where('code', $currentRegion);
        })
            ->where(function ($query) {
                $query->where('slug', 'like', '%privacy%')
                    ->orWhere('name', 'LIKE', '%Privacy%')
                    ->orWhere('name', 'LIKE', '%privacy%')
                    ->orWhere('slug', 'like', '%Privacy%');
            })
            ->first();

        // If no page found for current region, fall back to default region (US)
        if (! $page) {
            $page = Page::whereHas('region', function ($query) {
                $query->where('code', 'us');
            })
                ->where(function ($query) {
                    $query->where('slug', 'like', '%privacy%')
                        ->orWhere('name', 'LIKE', '%Privacy%')
                        ->orWhere('name', 'LIKE', '%privacy%')
                        ->orWhere('slug', 'like', '%Privacy%');
                })
                ->first();
        }

        $regions = Region::all(); // For region selector dropdown

        return view('privacy-policy', compact('page', 'regions', 'currentRegion'));
    }

    public function affiliate()
    {
        // Get current region from the region service
        $currentRegion = $this->regionService->getCurrentRegionCode();

        // Find the Affiliate Disclaimer page for the current region with flexible matching
        $page = Page::whereHas('region', function ($query) use ($currentRegion) {
            $query->where('code', $currentRegion);
        })
            ->where(function ($query) {
                $query->where('slug', 'like', '%affiliate%')
                    ->orWhere('name', 'LIKE', '%Affiliate%')
                    ->orWhere('name', 'LIKE', '%affiliate%')
                    ->orWhere('slug', 'like', '%Affiliate%');
            })
            ->first();

        // If no page found for current region, fall back to default region (US)
        if (! $page) {
            $page = Page::whereHas('region', function ($query) {
                $query->where('code', 'us');
            })
                ->where(function ($query) {
                    $query->where('slug', 'like', '%affiliate%')
                        ->orWhere('name', 'LIKE', '%Affiliate%')
                        ->orWhere('name', 'LIKE', '%affiliate%')
                        ->orWhere('slug', 'like', '%Affiliate%');
                })
                ->first();
        }

        $regions = Region::all(); // For region selector dropdown

        return view('affiliate-disclaimer', compact('page', 'regions', 'currentRegion'));
    }

    public function imprint()
    {
        // Get current region from the region service
        $currentRegion = $this->regionService->getCurrentRegionCode();

        // Find the Imprint page for the current region with flexible matching
        $page = Page::whereHas('region', function ($query) use ($currentRegion) {
            $query->where('code', $currentRegion);
        })
            ->where(function ($query) {
                $query->where('slug', 'like', '%imprint%')
                    ->orWhere('name', 'LIKE', '%Imprint%')
                    ->orWhere('name', 'LIKE', '%imprint%')
                    ->orWhere('slug', 'like', '%Imprint%');
            })
            ->first();

        // If no page found for current region, fall back to default region (US)
        if (! $page) {
            $page = Page::whereHas('region', function ($query) {
                $query->where('code', 'us');
            })
                ->where(function ($query) {
                    $query->where('slug', 'like', '%imprint%')
                        ->orWhere('name', 'LIKE', '%Imprint%')
                        ->orWhere('name', 'LIKE', '%imprint%')
                        ->orWhere('slug', 'like', '%Imprint%');
                })
                ->first();
        }

        $regions = Region::all(); // For region selector dropdown

        return view('imprint', compact('page', 'regions', 'currentRegion'));
    }

    public function storeDetail($store = null)
    {
        $routeParameters = request()->route()->parameters();

        // Determine slug and region based on route parameters
        if (isset($routeParameters['region']) && isset($routeParameters['store'])) {
            // Regional route: {region}/stores/{store}
            $storeSlug     = $routeParameters['store'];
            $currentRegion = $routeParameters['region'];
        } elseif (isset($routeParameters['store'])) {
            // Default route: stores/{store}
            $storeSlug     = $routeParameters['store'];
            $currentRegion = 'us'; // Default fallback
        } else {
            // Fallback to manually parsing the slug parameter that was passed
            $storeSlug    = $store;
            $segment1     = request()->segment(1);
            $validRegions = $this->regionService->getRegionCodes();

            if (in_array($segment1, $validRegions)) {
                $currentRegion = $segment1;
            } else {
                $currentRegion = 'us';
            }
        }

        // Normalize slug formats — trim leading slash and prepare alternative match
        $storeSlug     = ltrim($storeSlug, '/');
        $slugWithSlash = '/' . $storeSlug;

        // Build query to fetch a store by id or slug.
        // We prefer stores that explicitly include the current region in country_codes.
        $storeQuery = \App\Models\Store::where('active', true)
            ->where(function ($q) use ($storeSlug, $slugWithSlash) {
                $q->where('id', $storeSlug)
                    ->orWhere('url_slug', $storeSlug)
                    ->orWhere('url_slug', $slugWithSlash);
            })
        // Keep candidate rows that either explicitly include the region or have no region restrictions.
            ->where(function ($q) use ($currentRegion) {
                // country_codes may be null, empty string, '[]' or JSON array or comma-separated string
                $q->whereNull('country_codes')
                    ->orWhere('country_codes', '')
                    ->orWhere('country_codes', '[]')
                // JSON array contains region
                    ->orWhereRaw("JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?)", [json_encode($currentRegion)])
                // or comma separated or plain comma-like text contains region (strip spaces)
                    ->orWhereRaw("FIND_IN_SET(?, REPLACE(country_codes, ' ', '')) > 0", [$currentRegion]);
            });

        // When multiple rows match the slug, prefer the row that explicitly matches region.
        // We use a CASE expression so that rows with explicit JSON_CONTAINS are ordered first,
        // then rows with country_codes null/empty, then others.
        $store = $storeQuery
            ->orderByRaw("
            CASE
                WHEN (JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?)) THEN 0
                WHEN (country_codes IS NULL OR country_codes = '' OR country_codes = '[]') THEN 1
                WHEN (FIND_IN_SET(?, REPLACE(country_codes, ' ', '')) > 0) THEN 0
                ELSE 2
            END
        ", [json_encode($currentRegion), $currentRegion])
            ->orderBy('updated_at', 'desc') // tie-breaker; adjust as needed
            ->first();

        if (! $store) {
            abort(404, 'Store not found');
        }

        // --- REGION AVAILABILITY CHECK (defensive) ---
        $isAvailableInRegion = true;
        $rawCountryCodes     = $store->attributes['country_codes'] ?? $store->getOriginal('country_codes') ?? $store->country_codes;

        if (is_array($rawCountryCodes)) {
            $codeArray = $rawCountryCodes;
        } else {
            if (! is_null($rawCountryCodes) && $rawCountryCodes !== '' && $rawCountryCodes !== '[]') {
                $decoded = json_decode($rawCountryCodes, true);
                if (is_array($decoded)) {
                    $codeArray = $decoded;
                } else {
                    $cleanString = trim(str_replace(['[', ']', '"', "'"], '', $rawCountryCodes), ' ,');
                    $codeArray   = ! empty($cleanString)
                        ? array_filter(array_map('trim', explode(',', $cleanString)))
                        : null;
                }
            } else {
                $codeArray = null;
            }
        }

        if ($codeArray) {
            $isAvailableInRegion = in_array($currentRegion, $codeArray, true);
        }

        if (! $isAvailableInRegion) {
            abort(404, 'Store not available in your region');
        }

        // --- FETCH OFFERS FOR CURRENT REGION ---
        $offers = \App\Models\Offer::where('store_id', $store->id)
            ->where('active', true)
            ->where(function ($query) use ($currentRegion) {
                $query->whereNull('country_codes')
                    ->orWhere('country_codes', '[]')
                    ->orWhere('country_codes', '')
                    ->orWhereRaw("JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?)", [json_encode($currentRegion)])
                    ->orWhereRaw("FIND_IN_SET(?, REPLACE(country_codes, ' ', '')) > 0", [$currentRegion]);
            })
            ->orderBy('sort', 'asc')
            ->get();

        // Fetch trending stores (example: 5 most recently updated stores)
        $trendingStores = \App\Models\Store::where('active', true)
            ->byRegionCodes([$currentRegion])
            ->where('id', '!=', $store->id)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Fetch more stores (example: 10 other stores)
        $moreStores = \App\Models\Store::where('active', true)
            ->byRegionCodes([$currentRegion])
            ->where('id', '!=', $store->id)
            ->inRandomOrder()
            ->take(10)
            ->get();

        return view('store-detail', compact('store', 'offers', 'currentRegion', 'trendingStores', 'moreStores'));
    }

    // public function storeDetail($store = null)
    // {
    //     $routeParameters = request()->route()->parameters();

    //     // Determine slug and region based on route parameters
    //     if (isset($routeParameters['region']) && isset($routeParameters['store'])) {
    //         // Regional route: {region}/stores/{store}
    //         $storeSlug     = $routeParameters['store'];
    //         $currentRegion = $routeParameters['region'];
    //     } elseif (isset($routeParameters['store'])) {
    //         // Default route: stores/{store}
    //         $storeSlug     = $routeParameters['store'];
    //         $currentRegion = 'us'; // Default fallback
    //     } else {
    //         // Fallback to manually parsing the slug parameter that was passed
    //         $storeSlug    = $store;
    //         $segment1     = request()->segment(1);
    //         $validRegions = $this->regionService->getRegionCodes();

    //         if (in_array($segment1, $validRegions)) {
    //             $currentRegion = $segment1;
    //         } else {
    //             $currentRegion = 'us';
    //         }
    //     }

    //     // Handle slug formats — trim leading slash and prepare alternative match
    //     $storeSlug     = ltrim($storeSlug, '/');
    //     $slugWithSlash = '/' . $storeSlug;

    //     // Fetch the specific store by slug or ID
    //     $store = \App\Models\Store::where('active', true)
    //         ->where(function ($query) use ($storeSlug, $slugWithSlash) {
    //             $query->where('id', $storeSlug)
    //                 ->orWhere('url_slug', $storeSlug)
    //                 ->orWhere('url_slug', $slugWithSlash);
    //         })
    //         ->first();
    //     if (!$store) {
    //         abort(404, 'Store not found');
    //     }

    //     // --- REGION AVAILABILITY CHECK (SAME AS BLOG DETAIL) ---
    //     $isAvailableInRegion = true;
    //     $rawCountryCodes     = $store->attributes['country_codes'] ?? $store->getOriginal('country_codes') ?? $store->country_codes;
    //     dd($rawCountryCodes);
    //      // Check if raw value is already an array (due to accessor being called before), or a string
    //     if (is_array($rawCountryCodes)) {
    //         $codeArray = $rawCountryCodes;
    //     } else {
    //         if (! is_null($rawCountryCodes) && $rawCountryCodes !== '' && $rawCountryCodes !== '[]') {
    //             $decoded = json_decode($rawCountryCodes, true);
    //             if (is_array($decoded)) {
    //                 $codeArray = $decoded;
    //             } else {
    //                 $cleanString = trim(str_replace(['[', ']', '"', "'"], '', $rawCountryCodes), ' ,');
    //                 $codeArray   = ! empty($cleanString)
    //                     ? array_filter(array_map('trim', explode(',', $cleanString)))
    //                     : null;
    //             }
    //         } else {
    //             $codeArray = null;
    //         }
    //     }

    //     // Region filtering logic
    //     if ($codeArray) {
    //         $isAvailableInRegion = in_array($currentRegion, $codeArray, true);
    //     }

    //     if (! $isAvailableInRegion) {
    //         abort(404, 'Store not available in your region');
    //     }

    //     // --- FETCH OFFERS FOR CURRENT REGION ---
    //     $offers = \App\Models\Offer::where('store_id', $store->id)
    //         ->where('active', true)
    //         ->where(function ($query) use ($currentRegion) {
    //             $query->whereNull('country_codes')
    //                 ->orWhere('country_codes', '[]')
    //                 ->orWhere('country_codes', '')
    //                 ->orWhereRaw("JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?)", [json_encode($currentRegion)])
    //                 ->orWhereRaw("FIND_IN_SET(?, REPLACE(country_codes, ' ', '')) > 0", [$currentRegion]);
    //         })
    //         ->orderBy('sort', 'asc')
    //         ->get();

    //     // Fetch trending stores (example: 5 most recently updated stores)
    //     $trendingStores = \App\Models\Store::where('active', true)
    //         ->byRegionCodes([$currentRegion])
    //         ->where('id', '!=', $store->id) // Exclude the current store
    //         ->orderBy('updated_at', 'desc')
    //         ->take(5)
    //         ->get();

    //     // Fetch more stores (example: 10 other stores)
    //     $moreStores = \App\Models\Store::where('active', true)
    //         ->byRegionCodes([$currentRegion])
    //         ->where('id', '!=', $store->id) // Exclude the current store
    //         ->inRandomOrder()
    //         ->take(10)
    //         ->get();

    //     return view('store-detail', compact('store', 'offers', 'currentRegion', 'trendingStores', 'moreStores'));
    // }

}
