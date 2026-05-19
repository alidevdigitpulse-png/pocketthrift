<?php

// Temporary debug script to check all blogs with the slug
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Searching for ALL blogs with slug: como-verificamos-ofertas\n";
echo "=======================================================\n\n";

$blogs = \App\Models\Blog::where('url_slug', 'como-verificamos-ofertas')
    ->orWhere('url_slug', '/como-verificamos-ofertas')
    ->get();

echo "Found " . $blogs->count() . " blog(s):\n";

foreach ($blogs as $blog) {
    echo "--------------------------------------------------\n";
    echo "ID: " . $blog->id . "\n";
    echo "Title: " . $blog->title . "\n";
    echo "Slug: " . $blog->url_slug . "\n";
    echo "Country Codes: " . json_encode($blog->country_codes) . "\n";
    
    // Check match for 'mx'
    $match = in_array('mx', $blog->country_codes ?? []);
    echo "Matches 'mx': " . ($match ? "YES" : "NO") . "\n";
}
