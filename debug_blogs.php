<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Blog Debug Information ===\n\n";

// Get all blogs
$allBlogs = App\Models\Blog::all();
echo "Total blogs in database: " . $allBlogs->count() . "\n\n";

// Get active blogs
$activeBlogs = App\Models\Blog::where('active', 1)->get();
echo "Active blogs (active = 1): " . $activeBlogs->count() . "\n\n";

// Get blogs that match the HomeController query
$currentRegion = 'us';
$matchingBlogs = App\Models\Blog::where('active', 1)
    ->where(function($query) use ($currentRegion) {
        $query->whereNull('country_codes')
              ->orWhere('country_codes', '[]')
              ->orWhere('country_codes', '')
              ->orWhere('country_codes', $currentRegion);
    })
    ->get();

echo "Blogs matching HomeController query (should appear on /blogs page): " . $matchingBlogs->count() . "\n\n";

// Show details of first 5 active blogs
echo "=== First 5 Active Blogs Details ===\n";
foreach ($activeBlogs->take(5) as $blog) {
    echo "ID: {$blog->id}\n";
    echo "Title: {$blog->title}\n";
    echo "Slug: {$blog->url_slug}\n";
    echo "Active: " . ($blog->active ? 'Yes' : 'No') . "\n";
    echo "Country Codes (raw): " . var_export($blog->getRawOriginal('country_codes'), true) . "\n";
    echo "Country Codes (cast): " . var_export($blog->country_codes, true) . "\n";
    echo "---\n";
}
