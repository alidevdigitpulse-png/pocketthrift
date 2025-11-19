@extends('layouts.app')
@php
    $regionService = app(\App\Services\RegionService::class);
    $currentRegion = $regionService->getCurrentRegion();

    // Default Values (USA)
    $country = $currentRegion->country ?? 'USA';
    $regionPrefix = $currentRegion->code ?? 'us';

    $baseUrl = url($regionPrefix . '/blogs'); 
@endphp
@push('schemas')
@php
    // ------------------------------------------
    // REGION DETECTION SAME AS YOUR MAIN LOGIC
    // ------------------------------------------
    $regionCode = $region->code ?? ($regionCode ?? null);
    
    if (!$regionCode) {
        $pathCheck = request()->path();
        $segments = explode('/', $pathCheck);
        if (isset($segments[0]) && strlen($segments[0]) === 2 && ctype_alpha($segments[0])) {
            $regionCode = strtolower($segments[0]);
        } else {
            $regionCode = 'us';
        }
    }

    $regions = [
        'us' => 'USA', 
        'uk' => 'United Kingdom', 
        'au' => 'Australia',
        'ca' => 'Canada', 
        'fr' => 'France', 
        'de' => 'Germany',
        'it' => 'Italy', 
        'nl' => 'Netherlands', 
        'pl' => 'Poland',
        'es' => 'Spain', 
        'mx' => 'Mexico', 
        'ch' => 'Switzerland',
        'lu' => 'Luxembourg', 
        'fi' => 'Finland', 
        'no' => 'Norway',
        'nz' => 'New Zealand', 
        'sg' => 'Singapore', 
        'at' => 'Austria'
    ];

    $regionName = $regions[$regionCode] ?? strtoupper($regionCode);
    $baseUrl = rtrim(config('app.url','https://pocketthrift.com'), '/');

    // REGIONIZED SITE URL
    $siteUrl = ($regionCode === 'us') ? $baseUrl : $baseUrl . '/' . $regionCode;

    // ------------------------------------------
    // ALL REGIONS PAGE SPECIFIC DATA
    // ------------------------------------------
    $title = "All Regions";
    $description = "All Regions";
    $path = "all-regions";

    // FULL URL FOR ALL REGIONS PAGE
    $fullUrl = rtrim($siteUrl, '/') . "/all-regions/";

    // ------------------------------------------
    // BREADCRUMBS (REGION FIX APPLIED)
    // ------------------------------------------
    $breadcrumbs = [
        ['name' => 'Home', 'url' => $siteUrl],
        ['name' => 'All Regions', 'url' => 'all-regions']
    ];

    // FIX HOME → Always regionized
    $breadcrumbs[0]['url'] = $siteUrl;
@endphp
<title>PocketThrift {{ $country }}: Guides to Smart Savings and Deals!</title>

<meta name="description"
      content="Explore a variety of Blogs on PocketThrift {{ $country }}, including shopping tips, exclusive deals, and lifestyle insights to enrich your everyday life.">

<meta name="robots" content="index, follow">

{{-- OG TAGS --}}
<meta property="og:type" content="website">
<meta property="og:url" content="{{ $baseUrl }}">
<meta property="og:title" content="PocketThrift {{ $country }}: Guides to Smart Savings and Deals!">
<meta property="og:description"
      content="Explore a variety of Blogs on PocketThrift {{ $country }}, including shopping tips, exclusive deals, and lifestyle insights to enrich your everyday life.">
<meta property="og:image" content="https://pocketthrift.com/images/og-image.webp">

{{-- TWITTER TAGS --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $baseUrl }}">
<meta name="twitter:title" content="PocketThrift {{ $country }}: Guides to Smart Savings and Deals!">
<meta name="twitter:description"
      content="Explore a variety of Blogs on PocketThrift {{ $country }}, including shopping tips, exclusive deals, and lifestyle insights to enrich your everyday life.">
<meta name="twitter:image" content="https://pocketthrift.com/images/og-image.webp">
@php
    // ------------------------------------------
    // WEBPAGE SCHEMA
    // ------------------------------------------
    $webpage = [
        "@context" => "https://schema.org/",
        "@type" => "WebPage",
        "name" => $title,
        "description" => $description,
        "url" => $fullUrl,
        "publisher" => [
            "@type" => "Organization",
            "name" => "PocketThrift",
            "logo" => [
                "@type" => "ImageObject",
                "url" => $baseUrl . "/images/og-image.webp"
            ]
        ]
    ];

    // ------------------------------------------
    // BREADCRUMB SCHEMA
    // ------------------------------------------
    $crumbItems = [];
    $pos = 1;

    foreach ($breadcrumbs as $b) {
        if ($pos === 1) {
            // HOME FIX
            $url = $siteUrl;
        } else {
            $url = rtrim($siteUrl, '/') . '/' . ltrim($b['url'], '/') . '/';
        }

        $crumbItems[] = [
            "@type" => "ListItem",
            "position" => $pos++,
            "name" => $b['name'],
            "item" => $url
        ];
    }

    $breadcrumbSchema = [
        "@context" => "https://schema.org/",
        "@type" => "BreadcrumbList",
        "itemListElement" => $crumbItems
    ];
@endphp

<script type="application/ld+json">
{!! json_encode($webpage, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>

<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>

@endpush

@section('content')

<div class="container">
<h1 class="text-center mb-2 mt-4">Blogs</h1>
<p class="text-center mb-4">PocketThrift 
    @if(request()->route('region'))
        {{ \App\Models\Region::where('code', request()->route('region'))->where('active', true)->first()->country ?? strtoupper(request()->route('region')) }}
      @else
        USA
      @endif
    Blogs, your ultimate destination for diverse and engaging content that caters to every interest! Our blog is a vibrant tapestry of topics, ranging from savvy financial tips and budget-friendly lifestyle hacks to the latest trends in technology, travel, and personal development. Whether you're seeking ways to stretch your dollar further, exploring new gadgets and apps, or looking for inspiration to enhance your everyday life, PocketThrift has something for you. Our expert contributors and passionate writers deliver insightful articles and practical advice designed to help you make the most out of your time and resources.

</p>

<!-- New responsive row displaying blogs from database -->
<div class="row mt-4">
    @if($blogs && $blogs->count() > 0)
        @foreach($blogs->chunk(4) as $blogChunk)
            @foreach($blogChunk as $blog)
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="position-relative">
                            @if($blog->logo)
                                <img src="{{ asset('uploads/' . $blog->logo) }}" class="card-img-top" alt="{{ $blog->image_alt ?: $blog->title }}" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="{{ asset('image.png') }}" class="card-img-top" alt="{{ $blog->title }}" style="height: 200px; object-fit: cover;">
                            @endif
                            @if($blog->category)
                                <div class="position-absolute bottom-0 start-0 bg-primary text-white p-1 w-100 text-center" style="font-size: 0.8rem;">
                                    {{ $blog->category->title }}
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $blog->title }}</h5>
                            <p class="card-text">{{ Str::limit($blog->short_description, 100) }}</p>
                            @if(request()->route('region'))
                                <a href="/{{ request()->route('region') }}/blog/{{ ltrim($blog->url_slug, '/') }}" class="read-more-blog-btn">Read More</a>
                            @else
                                <a href="/blog/{{ ltrim($blog->url_slug, '/') }}" class="read-more-blog-btn">Read More</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            
            @if(count($blogChunk) < 4)
                @for($i = count($blogChunk); $i < 4; $i++)
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 bg-light">
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <span class="text-muted">No blog</span>
                            </div>
                        </div>
                    </div>
                @endfor
            @endif
        @endforeach
        

    @else
        <div class="col-12">
            <p class="text-center">No blogs available at the moment.</p>
        </div>
    @endif
</div>
 @push('breadcrumb')
    <nav aria-label="Breadcrumb">
        <div class="border-top bg-white px-md-2 px-3 mt-4">
            <div class="container py-3">
                <ul class="list-unstyled d-flex align-items-center gap-2 mb-0">
                    <li class="d-flex align-items-center">
                        <a href="{{ route('home') }}" class="text-decoration-none">Home</a>
                        <svg fill="rgba(0,0,0,1)" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z"></path>
                        </svg>
                    </li>
                    <li class="d-flex align-items-center">
                        <span class="fw-semibold">Blogs</span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endpush
</div>


@endsection
