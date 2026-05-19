<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Region;
use App\Models\Store;
use App\Models\Category;
use App\Models\Page;
use App\Models\Blog;

class SitemapController extends Controller
{
    protected $regionService;

    public function __construct()
    {
        // We could inject the region service if needed, but for now we're handling regions manually
    }
    public function index(Request $request)
    {
        // Clear any previous output (whitespace, etc.)
        if (ob_get_length() > 0) {
            ob_end_clean();
        }

        // Generate main sitemap for US (default)
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Add static pages
        $xml .= $this->generateStaticPagesSitemap();

        // Add category pages
        $xml .= $this->generateCategorySitemap();

        // Add store pages
        $xml .= $this->generateStoreSitemap();

        // Add blog pages
        $xml .= $this->generateBlogSitemap();

        // Add dynamic pages
        // $xml .= $this->generatePageSitemap();

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }

    public function regionSitemap($regionCode, Request $request)
    {
        // Clear any previous output
        if (ob_get_length() > 0) {
            ob_end_clean();
        }

        // Validate the region exists and is active
        $region = Region::where('code', $regionCode)->where('active', 1)->first();

        if (!$region) {
            abort(404, 'Region not found');
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Add static pages with region prefix
        $xml .= $this->generateStaticPagesSitemap($regionCode);

        // Add category pages with region prefix (filtered by region)
        $xml .= $this->generateCategorySitemap($regionCode);

        // Add store pages with region prefix (filtered by region)
        $xml .= $this->generateStoreSitemap($regionCode);

        // Add blog pages with region prefix (filtered by region)
        $xml .= $this->generateBlogSitemap($regionCode);

        // Add dynamic pages with region prefix
        // $xml .= $this->generatePageSitemap($regionCode);

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }

    private function generateStaticPagesSitemap($regionCode = null)
    {
        $xml = '';
        $baseUrl = $regionCode ? 'https://pocketthrift.com/' . $regionCode : 'https://pocketthrift.com';

        // Get the last modification date for static pages - use a recent timestamp
        $lastMod = now()->format('c');

        $staticPages = [
            '/',
            '/about-us',
            '/contact-us',
            '/privacy-policy',
            '/affiliate-disclaimer',
            '/imprint',
            '/terms-of-use',
            '/all-regions',
            '/blogs',
            '/coupons',
            '/stores',
        ];

        foreach ($staticPages as $page) {
            if ($regionCode && $page === '/') {
                $url = $baseUrl;
            } else {
                $url = $baseUrl . $page;
            }
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($url) . "</loc>\n";
            $xml .= "    <lastmod>" . $lastMod . "</lastmod>\n";
            $xml .= "    <changefreq>hourly</changefreq>\n";
            $xml .= "  </url>\n";
        }

        return $xml;
    }

    private function generateCategorySitemap($regionCode = null)
    {
        $xml = '';
        $baseUrl = $regionCode ? 'https://pocketthrift.com/' . $regionCode : 'https://pocketthrift.com';

        // Filter categories based on region if it's not the default region
        if ($regionCode && $regionCode !== 'us') {
            $categories = Category::where('active', 1)->byRegionCodes([$regionCode])->get();
        } else {
            // For US or default, get categories where country_codes is empty or contains US
            $categories = Category::where('active', 1)
                ->where(function($query) {
                    $query->whereNull('country_codes')
                          ->orWhere('country_codes', '[]')
                          ->orWhere('country_codes', '')
                          ->orWhereRaw("(JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, '\"us\"'))")
                          ->orWhereRaw("FIND_IN_SET('us', country_codes) > 0");
                })
                ->get();
        }

        foreach ($categories as $category) {
            $url = $baseUrl . '/coupons/' . ltrim($category->url_slug, '/');
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($url) . "</loc>\n";
            $xml .= "    <lastmod>" . ($category->updated_at ? $category->updated_at->format('c') : now()->format('c')) . "</lastmod>\n";
            $xml .= "    <changefreq>daily</changefreq>\n";
            $xml .= "  </url>\n";
        }

        return $xml;
    }

    private function generateStoreSitemap($regionCode = null)
    {
        $xml = '';
        $baseUrl = $regionCode ? 'https://pocketthrift.com/' . $regionCode : 'https://pocketthrift.com';

        // Filter stores based on region if it's not the default region
        if ($regionCode && $regionCode !== 'us') {
            $stores = Store::where('active', 1)->byRegionCodes([$regionCode])->get();
        } else {
            // For US or default, get stores where country_codes is empty or contains US
            $stores = Store::where('active', 1)
                ->where(function($query) {
                    $query->whereNull('country_codes')
                          ->orWhere('country_codes', '')
                          ->orWhereRaw("FIND_IN_SET('us', REPLACE(country_codes, ' ', '')) > 0");
                })
                ->get();
        }

        foreach ($stores as $store) {
            $url = $baseUrl . '/stores/' . ltrim($store->url_slug, '/');
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($url) . "</loc>\n";
            $xml .= "    <lastmod>" . ($store->updated_at ? $store->updated_at->format('c') : now()->format('c')) . "</lastmod>\n";
            $xml .= "    <changefreq>daily</changefreq>\n";
            $xml .= "  </url>\n";
        }

        return $xml;
    }

    private function generateBlogSitemap($regionCode = null)
    {
        $xml = '';
        $baseUrl = $regionCode ? 'https://pocketthrift.com/' . $regionCode : 'https://pocketthrift.com';
        
        // Use 'us' as default region code if none specified
        $currentRegion = $regionCode ?? 'us';

        // Filter blogs based on region - using the same logic as HomeController's blogs method
        $blogs = Blog::where('active', 1)
            ->where(function($query) use ($currentRegion) {
                $query->whereNull('country_codes')
                      ->orWhere('country_codes', '[]')
                      ->orWhere('country_codes', '')
                      ->orWhere('country_codes', $currentRegion); // Direct string match for region codes
            })
            ->get();

        foreach ($blogs as $blog) {
            $url = $baseUrl . '/blogs/' . ltrim($blog->url_slug, '/');
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($url) . "</loc>\n";
            $xml .= "    <lastmod>" . ($blog->updated_at ? $blog->updated_at->format('c') : now()->format('c')) . "</lastmod>\n";
            $xml .= "    <changefreq>daily</changefreq>\n";
            $xml .= "  </url>\n";
        }

        return $xml;
    }

    // private function generatePageSitemap($regionCode = null)
    // {
    //     $xml = '';
    //     $baseUrl = $regionCode ? 'https://pocketthrift.com/' . $regionCode : 'https://pocketthrift.com';

    //     // For pages, we'll get all pages since they are not region-specific in the provided model
    //     // If you have region-specific pages, you might need to add a region filter to the Page model
    //     $pages = Page::all();

    //     foreach ($pages as $page) {
    //         $url = $baseUrl . '/page/' . ltrim($page->slug, '/');
    //         $xml .= "  <url>\n";
    //         $xml .= "    <loc>" . htmlspecialchars($url) . "</loc>\n";
    //         $xml .= "    <lastmod>" . ($page->updated_at ? $page->updated_at->format('c') : now()->format('c')) . "</lastmod>\n";
    //         $xml .= "    <changefreq>weekly</changefreq>\n";
    //         $xml .= "  </url>\n";
    //     }

    //     return $xml;
    // }
}