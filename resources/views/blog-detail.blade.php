@extends('layouts.app')
@push('schemas')
@php
$regionCode = $region->code ?? ($regionCode ?? 'us');
$baseUrl = rtrim(config('app.url','https://pocketthrift.com'), '/');
$siteUrl = ($regionCode === 'us') ? $baseUrl : $baseUrl . '/' . $regionCode;

$title = $meta['title'] ?? ($post->title ?? 'Blog');
$description = $meta['description'] ?? ($post->excerpt ?? null);
$path = $meta['path'] ?? request()->getPathInfo();
$fullUrl = rtrim($siteUrl, '/') . '/' . ltrim($path, '/');

$breadcrumbs = $meta['breadcrumbs'] ?? [
  ['name'=>'Home','url'=>'/'],
  ['name'=>'Blogs','url'=>'/blogs/'],
  ['name'=>$title,'url'=>$path]
];

$webpage = ["@context"=>"https://schema.org/","@type"=>"WebPage","name"=>$title,"description"=>$description,"url"=>$fullUrl,"publisher"=>["@type"=>"Organization","name"=>"PocketThrift","logo"=>["@type"=>"ImageObject","url"=>$siteUrl . '/images/og-image.webp']]];

$items = []; $pos = 1;
foreach($breadcrumbs as $b){
  $u = (strpos($b['url'],'http') === 0) ? $b['url'] : rtrim($siteUrl,'/').'/'.ltrim($b['url'],'/');
  $items[] = ["@type"=>"ListItem","position"=>$pos++,"name"=>$b['name'],"item"=>$u];
}
$breadcrumbSchema = ["@context"=>"https://schema.org/","@type"=>"BreadcrumbList","itemListElement"=>$items];

$faqsArr = $meta['faqs'] ?? ($faqs ?? []);
$faqSchema = null;
if(!empty($faqsArr) && is_array($faqsArr)){
  $ents = [];
  foreach($faqsArr as $f){
    $q = $f['question'] ?? $f['name'] ?? null;
    $a = $f['answer'] ?? $f['acceptedAnswer'] ?? null;
    if($q && $a) $ents[] = ["@type"=>"Question","name"=>$q,"acceptedAnswer"=>["@type"=>"Answer","text"=>$a]];
  }
  if(count($ents)) $faqSchema = ["@context"=>"https://schema.org/","@type"=>"FAQPage","mainEntity"=>$ents];
}
@endphp

<script type="application/ld+json">{!! json_encode($webpage, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>
@if($faqSchema)
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>
@endif
@endpush

@section('title', $blog->title ?? 'Blog Detail')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-4">
        <div class="col-sm-8">
            <div class="card mb-4">
                @if($blog->logo)
                    <img src="{{ asset('uploads/' . $blog->logo) }}" class="card-img-top" alt="{{ $blog->image_alt ?: $blog->title }}" style="height: 400px; object-fit: cover;">
                @else
                    <img src="{{ asset('image.png') }}" class="card-img-top" alt="{{ $blog->title }}" style="height: 400px; object-fit: cover;">
                @endif
                
                <div class="card-body">
                    <h1 class="card-title">{{ $blog->title }}</h1>
                    
                    @if($blog->category)
                        <p class="text-muted">
                            <small>Category: {{ $blog->category->title }}</small>
                        </p>
                    @endif
                    
                    <p class="card-text text-muted">
                        <small>
                            Published: {{ $blog->created_at ? $blog->created_at->format('F j, Y') : '' }}
                            @if($blog->est_read_time)
                                | Read time: {{ $blog->est_read_time }} min
                            @endif
                        </small>
                    </p>
                    
                    <hr>
                    
                    <div class="blog-content">
                        {!! $blog->content_body !!}
                    </div>
                    
                    @if($blog->short_description)
                        <div class="mt-4">
                            <h5>Summary:</h5>
                            <p>{{ $blog->short_description }}</p>
                        </div>
                    @endif
                    
                    @if($blog->meta_robots)
                        <div class="mt-3">
                            <small class="text-muted">Meta Robots: {{ $blog->meta_robots }}</small>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Back to blogs button -->
            <div class="text-center mb-4">
                @if(request()->route('region'))
                    <a href="/{{ request()->route('region') }}/blogs" class="btn btn-outline-primary">
                        ← Back to Blogs
                    </a>
                @else
                    <a href="/blogs" class="btn btn-outline-primary">
                        ← Back to Blogs
                    </a>
                @endif
            </div>
        </div>
        <div class="col-sm-4">
            <div class="sidebar-blogs  pt-2">
                 <div class="widget pt-3">
                    <h3 class="widget-title">Related Category Blogs</h3>
                    @if($blog->category_id)
                        @php
                            $relatedBlogs = \App\Models\Blog::where('category_id', $blog->category_id)
                                ->where('id', '!=', $blog->id) // Exclude current blog
                                ->where('active', true)
                                ->inRandomOrder()
                                ->limit(2)
                                ->get();
                            
                            // If no related blogs found in the same category, get random blogs
                            if($relatedBlogs->isEmpty()) {
                                $relatedBlogs = \App\Models\Blog::where('id', '!=', $blog->id) // Exclude current blog
                                    ->where('active', true)
                                    ->inRandomOrder()
                                    ->limit(2)
                                    ->get();
                            }
                        @endphp
                        @foreach($relatedBlogs as $relatedBlog)
                            <div class="mb-3">
                                <a href="
                                    @if(request()->route('region'))
                                        /{{ request()->route('region') }}/blog/{{ ltrim($relatedBlog->url_slug, '/') }}
                                    @else
                                        /blog/{{ ltrim($relatedBlog->url_slug, '/') }}
                                    @endif
                                " class="text-sm text-decoration-none d-block">
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="rounded bg-white shadow-sm p-2 w-32 h-32 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 60px; height: 60px;">
                                            @if($relatedBlog->logo)
                                                <img src="{{ asset('uploads/' . $relatedBlog->logo) }}"
                                                     class="w-100"
                                                     alt="{{ $relatedBlog->title }}"
                                                     style="max-height: 40px; object-fit: cover;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center" style="height: 40px;">
                                                    <span class="text-muted">{{ substr($relatedBlog->title, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1" style="min-width: 0;">
                                            <div class="related-blog-title text-dark" style="font-size: 0.875rem; line-height: 1.3;">
                                                {{ Str::limit($relatedBlog->title, 50) }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                        @if($relatedBlogs->isEmpty())
                            <p class="text-muted text-center py-2">No related blogs available</p>
                        @endif
                    @else
                        @php
                            // If blog has no category, show random blogs
                            $relatedBlogs = \App\Models\Blog::where('id', '!=', $blog->id) // Exclude current blog
                                ->where('active', true)
                                ->inRandomOrder()
                                ->limit(2)
                                ->get();
                        @endphp
                        @foreach($relatedBlogs as $relatedBlog)
                            <div class="mb-3">
                                <a href="
                                    @if(request()->route('region'))
                                        /{{ request()->route('region') }}/blog/{{ ltrim($relatedBlog->url_slug, '/') }}
                                    @else
                                        /blog/{{ ltrim($relatedBlog->url_slug, '/') }}
                                    @endif
                                " class="text-sm text-decoration-none d-block">
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="rounded bg-white shadow-sm p-2 w-32 h-32 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 60px; height: 60px;">
                                            @if($relatedBlog->logo)
                                                <img src="{{ asset('uploads/' . $relatedBlog->logo) }}"
                                                     class="w-100"
                                                     alt="{{ $relatedBlog->title }}"
                                                     style="max-height: 40px; object-fit: cover;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center" style="height: 40px;">
                                                    <span class="text-muted">{{ substr($relatedBlog->title, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1" style="min-width: 0;">
                                            <div class="related-blog-title text-dark" style="font-size: 0.875rem; line-height: 1.3;">
                                                {{ Str::limit($relatedBlog->title, 50) }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                        @if($relatedBlogs->isEmpty())
                            <p class="text-muted text-center py-2">No related blogs available</p>
                        @endif
                    @endif
                </div>

                   <div class="widget pt-3">
                <h3 class="widget-title">Trending Stores</h3>
                @php
                    // Fetch trending stores from the current region, ordered by the number of active offers
                    $trendingStores = \App\Models\Store::where('active', true)
                        ->byRegionCodes([$currentRegion]) // Filter by current region
                        ->withCount('offers') // Count related offers
                        ->orderByDesc('offers_count') // Order by offer count
                        ->take(5) // Limit to top 10
                        ->get(); // Execute the query
                @endphp
                @foreach($trendingStores as $trendingStore)
                    <div class="mb-3">
                        <a href="{{ route('store.detail', ltrim($trendingStore->url_slug, '/')) }}" 
                           class="text-sm text-decoration-none">
                            <div class="d-flex gap-3 align-items-center mb-3">
                                <div class="rounded bg-white shadow p-2 w-44 h-44 d-flex align-items-center justify-content-center">
                                    @if($trendingStore->logo)
                                        <img src="{{ asset('uploads/' . $trendingStore->logo) }}" 
                                             class="w-100" 
                                             alt="{{ $trendingStore->title }} Logo"
                                             style="max-height: 44px;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center" style="height: 44px;">
                                            <span class="text-muted">{{ substr($trendingStore->title, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="">
                                    <div class="trending-store-title">{{ $trendingStore->title }} Coupon Code</div>
                                    <div class="trending-store-data">
                                        {{ $trendingStore->offers->where('type', 'Code')->count() }} 
                                        Coupons & {{ $trendingStore->offers->where('type', '!=', 'Code')->count() }} Offers
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
                
            </div>
        </div>
    </div>
            @push('breadcrumb')
<nav aria-label="Breadcrumb">
    <div class="border-top bg-white px-md-2 px-3 mt-4">
        <div class="container py-3">
            <ul class="list-unstyled d-flex align-items-center gap-2 mb-0">
                <li class="d-flex align-items-center">
                    <a href="{{ route('home') }}" class="text-decoration-none">Home </a>
                    <svg fill="rgba(0,0,0,1)" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z"></path>
                    </svg>
                </li>
                <li class="d-flex align-items-center">
                    <a href="{{ route('blogs') }}" class="text-decoration-none"> Blogs</a>
                    <svg fill="rgba(0,0,0,1)" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z"></path>
                    </svg>
                </li>
                <li class="d-flex align-items-center">
                    <span class="fw-semibold">{{ ltrim($blog->url_slug, '/') }}</span>

                </li>
            </ul>
        </div>
    </div>
</nav>
@endpush
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any specific blog detail scripts here if needed
});
</script>
@endsection