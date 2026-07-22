<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use Illuminate\Support\Str;

use App\Models\Region;
use App\Services\RegionService;
use Illuminate\Http\Request;
use App\Services\SizzlingoBlogProductService;

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
                ->take(12) // Limit to 12 trending items
                ->get();

            $trendingStores = collect();
            foreach ($trendingStoreItems as $trendingItem) {
                $item = $trendingItem->item;
                if ($item && $item->active && $trendingStores->count() < 12) {
                    $trendingStores->push($item);
                }
            }
            $trendingStores = $trendingStores->unique('id')->take(12);

            // Get trending items for categories
            $trendingCategoryItems = \App\Models\TrendingItem::byRegionAndType($regionId, 'category')
                ->with('item') // Eager load item
                ->orderBy('position', 'asc')
                ->take(6) // Limit to 6 trending items
                ->get();

            $trendingCategories = collect();
            foreach ($trendingCategoryItems as $trendingItem) {
                $item = $trendingItem->item;
                if ($item && $item->active && $trendingCategories->count() < 6) {
                    $trendingCategories->push($item);
                }
            }

            // Get trending items for offers - Row 1
            $trendingOfferItemsRow1 = \App\Models\TrendingItem::byRegionAndType($regionId, 'offer')
                ->where('row', 1)
                ->with('item.store') // Eager load item and its store relationship
                ->orderBy('position', 'asc')
                ->take(9) // Limit to 9 trending items
                ->get();

            $trendingOffersRow1 = collect();
            foreach ($trendingOfferItemsRow1 as $trendingItem) {
                $item = $trendingItem->item;
                if ($item && $item->active && $item->store && $trendingOffersRow1->count() < 9) {
                    $trendingOffersRow1->push($item);
                }
            }
            $trendingOffersRow1 = $trendingOffersRow1->unique('id')->take(9);

            // Get trending items for offers - Row 2
            $trendingOfferItemsRow2 = \App\Models\TrendingItem::byRegionAndType($regionId, 'offer')
                ->where('row', 2)
                ->with('item.store') // Eager load item and its store relationship
                ->orderBy('position', 'asc')
                ->take(9) // Limit to 9 trending items
                ->get();

            $trendingOffersRow2 = collect();
            foreach ($trendingOfferItemsRow2 as $trendingItem) {
                $item = $trendingItem->item;
                if ($item && $item->active && $item->store && $trendingOffersRow2->count() < 9) {
                    $trendingOffersRow2->push($item);
                }
            }
            $trendingOffersRow2 = $trendingOffersRow2->unique('id')->take(9);

            // Get trending items for offers - Row 3
            $trendingOfferItemsRow3 = \App\Models\TrendingItem::byRegionAndType($regionId, 'offer')
                ->where('row', 3)
                ->with('item.store') // Eager load item and its store relationship
                ->orderBy('position', 'asc')
                ->take(9) // Limit to 9 trending items
                ->get();

            $trendingOffersRow3 = collect();
            foreach ($trendingOfferItemsRow3 as $trendingItem) {
                $item = $trendingItem->item;
                if ($item && $item->active && $item->store && $trendingOffersRow3->count() < 9) {
                    $trendingOffersRow3->push($item);
                }
            }
            $trendingOffersRow3 = $trendingOffersRow3->unique('id')->take(9);

            // Get trending items for blogs
            $trendingBlogItems = \App\Models\TrendingItem::byRegionAndType($regionId, 'blog')
                ->with('item.category') // Eager load item and its category relationship
                ->orderBy('position', 'asc')
                ->take(8) // Limit to 8 trending items
                ->get();

            $trendingBlogs = collect();
            foreach ($trendingBlogItems as $trendingItem) {
                $item = $trendingItem->item;
                if ($item && $item->active && $trendingBlogs->count() < 8) {
                    $trendingBlogs->push($item);
                }
            }
            $trendingBlogs = $trendingBlogs->unique('id')->take(8);

            // If there are no manually selected trending items, fallback to algorithmic trending
            if ($trendingStores->count() < 12) {
                $needed = 12 - $trendingStores->count();
                $existingIds = $trendingStores->pluck('id')->all();

                $fallbackStores = \App\Models\Store::where('active', true)
                    ->byRegionCodes([$currentRegion])
                    ->whereNotIn('id', $existingIds)
                    ->orderBy('updated_at', 'desc')
                    ->take($needed)
                    ->get();
                $trendingStores = $trendingStores->concat($fallbackStores);
            }

            if ($trendingCategories->count() < 6) {
                $needed = 6 - $trendingCategories->count();
                $existingIds = $trendingCategories->pluck('id')->all();

                $fallbackCategories = \App\Models\Category::where('active', true)
                    ->byRegionCodes([$currentRegion])
                    ->whereNotIn('id', $existingIds)
                    ->orderBy('updated_at', 'desc')
                    ->take($needed)
                    ->get();
                $trendingCategories = $trendingCategories->concat($fallbackCategories);
            }

            // Fallback for each row independently
            if ($trendingOffersRow1->count() < 9) {
                $needed = 9 - $trendingOffersRow1->count();
                $existingIds = $trendingOffersRow1->pluck('id')->all();

                $fallbackOffers = \App\Models\Offer::where('active', true)
                    ->has('store')
                    ->byRegionCodes([$currentRegion])
                    ->whereNotIn('id', $existingIds)
                    ->where(function ($query) {
                        $query->whereNull('end_date')
                            ->orWhere('end_date', '>=', now());
                    })
                    ->orderBy('updated_at', 'desc')
                    ->take($needed)
                    ->get();
                $trendingOffersRow1 = $trendingOffersRow1->concat($fallbackOffers);
            }

            if ($trendingOffersRow2->count() < 9) {
                $needed = 9 - $trendingOffersRow2->count();
                $existingIds = $trendingOffersRow2->pluck('id')->all();

                $fallbackOffers = \App\Models\Offer::where('active', true)
                    ->has('store')
                    ->byRegionCodes([$currentRegion])
                    ->whereNotIn('id', $existingIds)
                    ->where(function ($query) {
                        $query->whereNull('end_date')
                            ->orWhere('end_date', '>=', now());
                    })
                    ->orderBy('updated_at', 'desc')
                    ->take($needed)
                    ->get();
                $trendingOffersRow2 = $trendingOffersRow2->concat($fallbackOffers);
            }

            if ($trendingOffersRow3->count() < 9) {
                $needed = 9 - $trendingOffersRow3->count();
                $existingIds = $trendingOffersRow3->pluck('id')->all();

                $fallbackOffers = \App\Models\Offer::where('active', true)
                    ->has('store')
                    ->byRegionCodes([$currentRegion])
                    ->whereNotIn('id', $existingIds)
                    ->where(function ($query) {
                        $query->whereNull('end_date')
                            ->orWhere('end_date', '>=', now());
                    })
                    ->orderBy('updated_at', 'desc')
                    ->take($needed)
                    ->get();
                $trendingOffersRow3 = $trendingOffersRow3->concat($fallbackOffers);
            }

            // Removed automatic fallback - only show manually selected blogs
            // If we still don't have enough blogs, supplement with algorithmic trending
            // if ($trendingBlogs->count() < 8) {
            //     $needed = 8 - $trendingBlogs->count();
            //     $existingIds = $trendingBlogs->pluck('id')->all();

            //     $trendingBlogs = $trendingBlogs->concat(
            //         \App\Models\Blog::with('category')
            //             ->where('active', true)
            //             ->byRegionCodes([$currentRegion])
            //             ->whereNotIn('id', $existingIds)
            //             ->orderBy('updated_at', 'desc')
            //             ->take($needed)
            //             ->get()
            //     );
            // }
        } else {
            // Fallback to algorithmic trending if region not found
            $trendingStores = \App\Models\Store::where('active', true)
                ->byRegionCodes([$currentRegion])
                ->orderBy('updated_at', 'desc')
                ->take(12)
                ->get();

            $trendingCategories = \App\Models\Category::where('active', true)
                ->byRegionCodes([$currentRegion])
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

            // For fallback, use the same offers for all three rows
            $fallbackOffers = \App\Models\Offer::where('active', true)
                ->byRegionCodes([$currentRegion])
                ->where(function ($query) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', now());
                })
                ->orderBy('updated_at', 'desc')
                ->take(9)
                ->get();

            $trendingOffersRow1 = $fallbackOffers;
            $trendingOffersRow2 = $fallbackOffers;
            $trendingOffersRow3 = $fallbackOffers;

            $trendingBlogs = \App\Models\Blog::with('category')
                ->where('active', true)
                ->byRegionCodes([$currentRegion])
                ->orderBy('updated_at', 'desc')
                ->take(8)
                ->get();
        }


        // Fetch Banners for the current region
        $banners = \App\Models\Banner::byRegionCodes([$currentRegion])->where('type', 'Hero Slider')->get();

        $topBanner = \App\Models\Banner::byRegionCodes([$currentRegion])->where('type', 'Top Banner')->first();
        $bottomBanner = \App\Models\Banner::byRegionCodes([$currentRegion])->where('type', 'Bottom Banner')->first();

        return view('welcome', compact('trendingStores', 'trendingCategories', 'trendingOffersRow1', 'trendingOffersRow2', 'trendingOffersRow3', 'trendingBlogs', 'currentRegion', 'banners','topBanner', 'bottomBanner'));
    }

    public function categories(Request $request)
    {
        $currentRegion = $this->regionService->getCurrentRegionCode();
        $categories = Category::where('active', true)
            ->byRegionCodes([$currentRegion]);

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

        // Fetch the category by either ID or slug, filtered by region
    $categoryRecord = \App\Models\Category::where('active', true)
        ->byRegionCodes([$currentRegion]) // Filter by current region
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

        // Fetch offers for stores in this category, filtered by region
    $storeIds = $categoryRecord->stores()->pluck('id');

    $offersQuery = \App\Models\Offer::whereIn('store_id', $storeIds)
        ->where('active', true)
        ->where(function ($query) use ($currentRegion) {
            $query->whereNull('country_codes')
                ->orWhere('country_codes', '[]')
                ->orWhere('country_codes', '')
                ->orWhere('country_codes', 'NULL') // Handle string "NULL" as available in all regions
                ->orWhereRaw("TRIM(country_codes) = ?", [$currentRegion]) // Exact match with trimmed spaces
                ->orWhereRaw("JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?)", [json_encode($currentRegion)])
                ->orWhereRaw("FIND_IN_SET(?, REPLACE(TRIM(country_codes), ' ', '')) > 0", [$currentRegion])
                ->orWhere('country_codes', 'LIKE', '%' . $currentRegion . '%'); // Fallback for any format
        })
        ->with('store')
        ->orderBy('sort', 'asc');


    // Check all offers for these stores without region filter
    $allOffersForStores = \App\Models\Offer::whereIn('store_id', $storeIds)
        ->where('active', true)
        ->select('id', 'title', 'store_id', 'country_codes')
        ->get();

    $offers = $offersQuery->get();

    return view('category-detail', compact('categoryRecord', 'currentRegion', 'offers'));
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

    public function searchSuggestions(Request $request)
    {
        $term = $request->input('term');
        $currentRegion = $this->regionService->getCurrentRegionCode();
        $isUsRegion = $currentRegion === 'us';

        if (empty($term)) {
            return response()->json([]);
        }

        // Search Stores
        $stores = \App\Models\Store::where('active', true)
            ->byRegionCodes([$currentRegion])
            ->where('title', 'LIKE', "%{$term}%")
            ->orderBy('title')
            ->limit(5)
            ->get()
            ->map(function ($store) use ($currentRegion, $isUsRegion) {
                return [
                    'label' => $store->title,
                    'category' => 'Stores',
                    'url' => $isUsRegion ? route('store.detail', ltrim($store->url_slug, '/')) : route('region.store.detail', ['region' => $currentRegion, 'store' => ltrim($store->url_slug, '/')])
                ];
            });

        // Search Categories
        $categories = Category::where('active', true)
            ->byRegionCodes([$currentRegion])
            ->where('title', 'LIKE', "%{$term}%")
            ->orderBy('title')
            ->limit(5)
            ->get()
            ->map(function ($category) use ($currentRegion, $isUsRegion) {
                return [
                    'label' => $category->title,
                    'category' => 'Categories',
                    'url' => $isUsRegion ? route('category.detail', ltrim($category->url_slug, '/')) : route('region.category.detail', ['region' => $currentRegion, 'category' => ltrim($category->url_slug, '/')])
                ];
            });

        // Search Blogs
        $blogs = \App\Models\Blog::where('active', true)
            ->byRegionCodes([$currentRegion])
            ->where('title', 'LIKE', "%{$term}%")
            ->orderBy('title')
            ->limit(5)
            ->get()
            ->map(function ($blog) use ($currentRegion, $isUsRegion) {
                return [
                    'label' => $blog->title,
                    'category' => 'Blogs',
                    'url' => $isUsRegion ? route('blog.detail', ltrim($blog->url_slug, '/')) : route('region.blog.detail', ['region' => $currentRegion, 'slug' => ltrim($blog->url_slug, '/')])
                ];
            });

        // Search Offers
        $offers = \App\Models\Offer::where('active', true)
             ->byRegionCodes([$currentRegion])
             ->where('title', 'LIKE', "%{$term}%")
             ->with('store')
             ->orderBy('title')
             ->limit(5)
             ->get()
             ->map(function ($offer) use ($currentRegion, $isUsRegion) {
                 $url = '#';
                 if ($offer->store) {
                     $url = $isUsRegion
                        ? route('store.detail', ltrim($offer->store->url_slug, '/'))
                        : route('region.store.detail', ['region' => $currentRegion, 'store' => ltrim($offer->store->url_slug, '/')]);
                 }
                 return [
                     'label' => $offer->title,
                     'category' => 'Offers',
                     'url' => $url
                 ];
             });

        // Search Details Pages (About, Contact, etc.)
        $pages = \App\Models\Page::where('active', true)
            ->byRegionCodes([$currentRegion])
            ->where('title', 'LIKE', "%{$term}%")
            ->orderBy('title')
            ->limit(5)
            ->get()
            ->map(function ($page) use ($currentRegion, $isUsRegion) {
                 // Determine route based on slug
                 $slug = trim($page->url_slug, '/');
                 $url = '#';

                 // Map common pages to their named routes
                 if (Str::contains($slug, 'about')) {
                    $url = $isUsRegion ? route('aboutUs') : route('region.aboutUs', $currentRegion);
                 } elseif (Str::contains($slug, 'contact')) {
                    $url = $isUsRegion ? route('contactUs') : route('region.contactUs', $currentRegion);
                 } elseif (Str::contains($slug, 'privacy')) {
                    $url = $isUsRegion ? route('privacyPolicy') : route('region.privacyPolicy', $currentRegion);
                 } elseif (Str::contains($slug, 'terms')) {
                    $url = $isUsRegion ? route('termsofUse') : route('region.termsofUse', $currentRegion);
                 } elseif (Str::contains($slug, 'affiliate')) {
                    $url = $isUsRegion ? route('affiliateDisclaimer') : route('region.affiliateDisclaimer', $currentRegion);
                 } elseif (Str::contains($slug, 'imprint')) {
                    $url = $isUsRegion ? route('imprint') : route('region.imprint', $currentRegion);
                 }

                 return [
                     'label' => $page->title,
                     'category' => 'Pages',
                     'url' => $url
                 ];
            })
            ->filter(function($item) {
                return $item['url'] !== '#';
            });

        // Merge results
        $results = $stores->concat($categories)->concat($offers)->concat($blogs)->concat($pages);

        return response()->json($results);
    }


    public function blogs(Request $request)
    {
        $currentRegion = $this->regionService->getCurrentRegionCode();

        // Fetch active blogs from the database using the new fields, filtered by region
        $blogs = \App\Models\Blog::where('active', true)
            ->byRegionCodes([$currentRegion])
            ->orderBy('id', 'desc')
            ->paginate(8); // 8 blogs per page

        return view('blogs', compact('blogs'));
    }

    public function blogDetail( SizzlingoBlogProductService $sizzlingoBlogProductService, $slug = null)
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
            ->byRegionCodes([$currentRegion]) // Filter by current region to find the correct blog (since duplicate slugs are allowed across regions)
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

        // Fetch FAQs related to this blog
        $faqs = \App\Models\Faq::where('blog_id', $blog->id)->orderBy('sort')->get();

            /*
            |--------------------------------------------------------------------------
            | Build heading-dependent Shopify products
            |--------------------------------------------------------------------------
            |
            | Sirf Australia ke SizzlinGo category blogs par chalega.
            | Category ID 731 currently SizzlinGo blog category hai.
            |
            */

            $blogContentSections = [];

            if (
                strtolower((string) $currentRegion) === 'au'
                && (int) $blog->category_id === 731
            ) {
                try {
                    $blogContentSections =
                        $sizzlingoBlogProductService
                            ->buildSections($blog);
                } catch (\Throwable $exception) {
                    /*
                    * API ya matching error blog page ko break nahi karega.
                    */
                    report($exception);

                    $blogContentSections = [];
                }
            }

        // return view('blog-detail', compact('blog', 'currentRegion', 'faqs'));

        return view(
            'blog-detail',
            compact(
                'blog',
                'currentRegion',
                'faqs',
                'blogContentSections'
            )
        );
    }


        // Find Terms & Conditions page based on region availability
    public function terms()
    {
        // Get current region from the region service
        $currentRegion = $this->regionService->getCurrentRegionCode();

        // Find the Terms of Use page for the current region with flexible matching
        $page = \App\Models\Page::where('active', true)
            ->byRegionCodes([$currentRegion])
            ->where(function ($query) {
                $query->where('url_slug', '/terms-of-use/')
                    ->orWhere('url_slug', 'terms-of-use')
                    ->orWhere('url_slug', 'terms')
                    ->orWhere('title', 'LIKE', '%terms%');
            })
            ->orderByRaw("
                CASE
                    WHEN JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?) THEN 1
                    WHEN FIND_IN_SET(?, country_codes) > 0 THEN 1
                    WHEN country_codes IS NULL OR country_codes = '[]' OR country_codes = '' THEN 2
                    ELSE 3
                END
            ", [json_encode($currentRegion), $currentRegion])
            ->first();

        // If no page found for current region, fall back to default region (US)
        if (!$page) {
            $page = \App\Models\Page::where('active', true)
                ->byRegionCodes(['us'])
                ->where(function ($query) {
                    $query->where('url_slug', '/terms-of-use/')
                        ->orWhere('url_slug', 'terms-of-use')
                        ->orWhere('url_slug', 'terms')
                        ->orWhere('title', 'LIKE', '%terms%');
                })
                ->orderByRaw("
                    CASE
                        WHEN JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?) THEN 1
                        WHEN FIND_IN_SET(?, country_codes) > 0 THEN 1
                        WHEN country_codes IS NULL OR country_codes = '[]' OR country_codes = '' THEN 2
                        ELSE 3
                    END
                ", [json_encode('us'), 'us'])
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
        $page = \App\Models\Page::where('active', true)
            ->byRegionCodes([$currentRegion])
            ->where(function ($query) {
                $query->where('url_slug', '/about-us/')
                    ->orWhere('url_slug', 'about-us')
                    ->orWhere('url_slug', 'about')
                    ->orWhere('title', 'LIKE', '%about%');
            })
            ->orderByRaw("
                CASE
                    WHEN JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?) THEN 1
                    WHEN FIND_IN_SET(?, country_codes) > 0 THEN 1
                    WHEN country_codes IS NULL OR country_codes = '[]' OR country_codes = '' THEN 2
                    ELSE 3
                END
            ", [json_encode($currentRegion), $currentRegion])
            ->first();

        // If no page found for current region, fall back to default region (US)
        if (!$page) {
            $page = \App\Models\Page::where('active', true)
                ->byRegionCodes(['us'])
                ->where(function ($query) {
                    $query->where('url_slug', '/about-us/')
                        ->orWhere('url_slug', 'about-us')
                        ->orWhere('url_slug', 'about')
                        ->orWhere('title', 'LIKE', '%about%');
                })
                ->orderByRaw("
                    CASE
                        WHEN JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?) THEN 1
                        WHEN FIND_IN_SET(?, country_codes) > 0 THEN 1
                        WHEN country_codes IS NULL OR country_codes = '[]' OR country_codes = '' THEN 2
                        ELSE 3
                    END
                ", [json_encode('us'), 'us'])
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
        $page = \App\Models\Page::where('active', true)
            ->byRegionCodes([$currentRegion])
            ->where(function ($query) {
                $query->where('url_slug', '/privacy-policy/')
                    ->orWhere('url_slug', 'privacy-policy')
                    ->orWhere('url_slug', 'privacy')
                    ->orWhere('title', 'LIKE', '%privacy%');
            })
            ->orderByRaw("
                CASE
                    WHEN JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?) THEN 1
                    WHEN FIND_IN_SET(?, country_codes) > 0 THEN 1
                    WHEN country_codes IS NULL OR country_codes = '[]' OR country_codes = '' THEN 2
                    ELSE 3
                END
            ", [json_encode($currentRegion), $currentRegion])
            ->first();

        // If no page found for current region, fall back to default region (US)
        if (!$page) {
            $page = \App\Models\Page::where('active', true)
                ->byRegionCodes(['us'])
                ->where(function ($query) {
                    $query->where('url_slug', '/privacy-policy/')
                        ->orWhere('url_slug', 'privacy-policy')
                        ->orWhere('url_slug', 'privacy')
                        ->orWhere('title', 'LIKE', '%privacy%');
                })
                ->orderByRaw("
                    CASE
                        WHEN JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?) THEN 1
                        WHEN FIND_IN_SET(?, country_codes) > 0 THEN 1
                        WHEN country_codes IS NULL OR country_codes = '[]' OR country_codes = '' THEN 2
                        ELSE 3
                    END
                ", [json_encode('us'), 'us'])
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
        $page = \App\Models\Page::where('active', true)
            ->byRegionCodes([$currentRegion])
            ->where(function ($query) {
                $query->where('url_slug', '/affiliate-disclaimer/')
                    ->orWhere('url_slug', 'affiliate-disclaimer')
                    ->orWhere('url_slug', 'affiliate')
                    ->orWhere('title', 'LIKE', '%affiliate%');
            })
            ->orderByRaw("
                CASE
                    WHEN JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?) THEN 1
                    WHEN FIND_IN_SET(?, country_codes) > 0 THEN 1
                    WHEN country_codes IS NULL OR country_codes = '[]' OR country_codes = '' THEN 2
                    ELSE 3
                END
            ", [json_encode($currentRegion), $currentRegion])
            ->first();

        // If no page found for current region, fall back to default region (US)
        if (!$page) {
            $page = \App\Models\Page::where('active', true)
                ->byRegionCodes(['us'])
                ->where(function ($query) {
                    $query->where('url_slug', '/affiliate-disclaimer/')
                        ->orWhere('url_slug', 'affiliate-disclaimer')
                        ->orWhere('url_slug', 'affiliate')
                        ->orWhere('title', 'LIKE', '%affiliate%');
                })
                ->orderByRaw("
                    CASE
                        WHEN JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?) THEN 1
                        WHEN FIND_IN_SET(?, country_codes) > 0 THEN 1
                        WHEN country_codes IS NULL OR country_codes = '[]' OR country_codes = '' THEN 2
                        ELSE 3
                    END
                ", [json_encode('us'), 'us'])
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
        $page = \App\Models\Page::where('active', true)
            ->byRegionCodes([$currentRegion])
            ->where(function ($query) {
                $query->where('url_slug', '/imprint/')
                    ->orWhere('url_slug', 'imprint')
                    ->orWhere('title', 'LIKE', '%imprint%');
            })
            ->orderByRaw("
                CASE
                    WHEN JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?) THEN 1
                    WHEN FIND_IN_SET(?, country_codes) > 0 THEN 1
                    WHEN country_codes IS NULL OR country_codes = '[]' OR country_codes = '' THEN 2
                    ELSE 3
                END
            ", [json_encode($currentRegion), $currentRegion])
            ->first();

        // If no page found for current region, fall back to default region (US)
        if (!$page) {
            $page = \App\Models\Page::where('active', true)
                ->byRegionCodes(['us'])
                ->where(function ($query) {
                    $query->where('url_slug', '/imprint/')
                        ->orWhere('url_slug', 'imprint')
                        ->orWhere('title', 'LIKE', '%imprint%');
                })
                ->orderByRaw("
                    CASE
                        WHEN JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?) THEN 1
                        WHEN FIND_IN_SET(?, country_codes) > 0 THEN 1
                        WHEN country_codes IS NULL OR country_codes = '[]' OR country_codes = '' THEN 2
                        ELSE 3
                    END
                ", [json_encode('us'), 'us'])
                ->first();
        }

        $regions = Region::all(); // For region selector dropdown

        return view('imprint', compact('page', 'regions', 'currentRegion'));
    }

    public function contact()
    {
        // Get current region from the region service
        $currentRegion = $this->regionService->getCurrentRegionCode();

        // Find the Terms of Use page for the current region with flexible matching
        $page = Page::where('active', true)
            ->byRegionCodes([$currentRegion])
            ->where(function ($query) {
                $query->where('url_slug', '/contact-us/')
                    ->orWhere('url_slug', 'contact-us')
                    ->orWhere('url_slug', 'contact')
                    ->orWhere('title', 'LIKE', '%contact%');
            })
            ->orderByRaw("
                CASE
                    WHEN JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?) THEN 1
                    WHEN FIND_IN_SET(?, country_codes) > 0 THEN 1
                    WHEN country_codes IS NULL OR country_codes = '[]' OR country_codes = '' THEN 2
                    ELSE 3
                END
            ", [json_encode($currentRegion), $currentRegion])
            ->first();

        // If no page found for current region, fall back to default region (US)
        if (!$page) {
            $page = Page::where('active', true)
                ->byRegionCodes(['us'])
                ->where(function ($query) {
                    $query->where('url_slug', '/contact-us/')
                        ->orWhere('url_slug', 'contact-us')
                        ->orWhere('url_slug', 'contact')
                        ->orWhere('title', 'LIKE', '%contact%');
                })
                ->orderByRaw("
                    CASE
                        WHEN JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?) THEN 1
                        WHEN FIND_IN_SET(?, country_codes) > 0 THEN 1
                        WHEN country_codes IS NULL OR country_codes = '[]' OR country_codes = '' THEN 2
                        ELSE 3
                    END
                ", [json_encode('us'), 'us'])
                ->first();
        }

        // All regions for dropdown
        $regions = Region::all();

        // Return the Terms view
        return view('contact-us', compact('page', 'regions', 'currentRegion'));
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
            ->with(['socialLinks.socialApp', 'offers']) // Eager load social links with their social apps, and offers
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

        // Fetch FAQs for the store
        $faqs = \App\Models\Faq::where('store_id', $store->id)
            ->orderBy('sort', 'asc')
            ->get();

        return view('store-detail', compact('store', 'offers', 'currentRegion', 'trendingStores', 'moreStores', 'faqs'));
    }

}
