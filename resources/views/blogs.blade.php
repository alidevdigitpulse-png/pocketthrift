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
@section('title', "PocketThrift {$country} : ".__('Guides to Smart Savings and Deals!'))

@section('meta_description', __('Explore a variety of Blogs on PocketThrift :region, including shopping tips, exclusive deals, and lifestyle insights to enrich your everyday life.', ['region' => $country]))
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
    // Blogs PAGE SPECIFIC DATA
    // ------------------------------------------
    $title = "Blogs";
    $description = "Blogs";
    $path = "blogs";

    // FULL URL FOR Blogs PAGE
    $fullUrl = rtrim($siteUrl, '/') . "/blogs/";

    // ------------------------------------------
    // BREADCRUMBS (REGION FIX APPLIED)
    // ------------------------------------------
    $breadcrumbs = [
        ['name' => __('Home'), 'url' => $siteUrl],
        ['name' => __('Blogs'), 'url' => 'blogs']
    ];

    // FIX HOME → Always regionized
    $breadcrumbs[0]['url'] = $siteUrl;
@endphp
    <meta name="robots" content="index, follow">
    {{-- OG TAGS --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $baseUrl }}">
    <meta property="og:title" content="PocketThrift {{ $country }} : {{ __('Guides to Smart Savings and Deals!') }}">
    <meta property="og:description"
        content="{{ __('Explore a variety of Blogs on PocketThrift :region, including shopping tips, exclusive deals, and lifestyle insights to enrich your everyday life.', ['region' => $country]) }}">
    <meta property="og:image" content="https://pocketthrift.com/images/og-image.webp">
    {{-- TWITTER TAGS --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $baseUrl }}">
    <meta name="twitter:title" content="PocketThrift {{ $country }} : {{ __('Guides to Smart Savings and Deals!') }}">
    <meta name="twitter:description"
        content="{{ __('Explore a variety of Blogs on PocketThrift :region, including shopping tips, exclusive deals, and lifestyle insights to enrich your everyday life.', ['region' => $country]) }}">
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
<h1 class="text-center mb-2 mt-4">{{ __('Blogs') }}</h1>
<p class="text-center mb-4">
    {{ __('PocketThrift :country Blogs, your ultimate destination for diverse and engaging content that caters to every interest! Our blog is a vibrant tapestry of topics, ranging from savvy financial tips and budget-friendly lifestyle hacks to the latest trends in technology, travel, and personal development. Whether you\'re seeking ways to stretch your dollar further, exploring new gadgets and apps, or looking for inspiration to enhance your everyday life, PocketThrift has something for you. Our expert contributors and passionate writers deliver insightful articles and practical advice designed to help you make the most out of your time and resources.', ['country' => $country]) }}
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
                                <div class="position-absolute bottom-0 start-0 text-white p-1 w-100 text-center" style="font-size: 0.8rem;background-color:#cf5103;">
                                    {{ $blog->category->title }}
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $blog->title }}</h5>
                            <p class="card-text">{{ Str::limit($blog->short_description, 100) }}</p>
                            @if(request()->route('region'))
                                <a href="/{{ request()->route('region') }}/blogs/{{ ltrim($blog->url_slug, '/') }}" class="read-more-blog-btn">{{ __('Read More') }}</a>
                            @else
                                <a href="/blogs/{{ ltrim($blog->url_slug, '/') }}" class="read-more-blog-btn">{{ __('Read More') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
        

    @else
        <div class="col-12">
            <p class="text-center">No blogs available at the moment.</p>
        </div>
    @endif
</div>

<!-- Load More Button -->
@if($blogs && $blogs->hasMorePages())
    <div class="d-flex justify-content-center mt-4 mb-4">
        <button id="load-more-btn" class="btn" style="background-color:#cf5103;color:#fff;" data-page="2">
            {{ __('Load More Blogs') }}
        </button>
    </div>
    <div class="text-center mb-3" id="blog-status">
        <small class="text-muted">{{ __('Showing page') }} 1 {{ __('of') }} {{ $blogs->lastPage() }}</small>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.getElementById('load-more-btn');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const page = this.getAttribute('data-page');
            const currentUrl = window.location.pathname;
            
            // Disable button and show loading state
            loadMoreBtn.disabled = true;
            loadMoreBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
            
            // Fetch more blogs
            fetch(`${currentUrl}?page=${page}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Parse the HTML response
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Extract blog cards from the response
                const newBlogs = doc.querySelectorAll('.col-md-3.mb-3');
                const blogContainer = document.querySelector('.row.mt-4');
                
                // Append new blogs to the container
                newBlogs.forEach(blog => {
                    blogContainer.appendChild(blog);
                });
                
                // Update page number
                const nextPage = parseInt(page) + 1;
                loadMoreBtn.setAttribute('data-page', nextPage);
                
                // Update status text
                const statusElement = document.getElementById('blog-status');
                const lastPage = {{ $blogs->lastPage() }};
                statusElement.innerHTML = `<small class="text-muted">Showing page ${page} of ${lastPage}</small>`;
                
                // Check if there are more pages
                if (nextPage > lastPage) {
                    loadMoreBtn.style.display = 'none';
                    statusElement.innerHTML = '<small class="text-muted">{{ __('All blogs loaded') }}</small>';
                } else {
                    // Re-enable button
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.innerHTML = '{{ __('Load More Blogs') }}';
                }
            })
            .catch(error => {
                console.error('Error loading more blogs:', error);
                loadMoreBtn.disabled = false;
                loadMoreBtn.innerHTML = '{{ __('Load More Blogs') }}';
                alert('Failed to load more blogs. Please try again.');
            });
        });
    }
});
</script>

 @push('breadcrumb')
    <nav aria-label="Breadcrumb">
        <div class="border-top bg-white px-md-2 px-3 mt-4">
            <div class="container py-3">
                <ul class="list-unstyled d-flex align-items-center gap-2 mb-0">
                    <li class="d-flex align-items-center">
                        <a href="{{ ($regionCode === 'us' || !$regionCode) ? route('home') : route('region.home', ['region' => $regionCode]) }}" class="text-decoration-none">{{ __('Home') }}</a>
                        <svg fill="rgba(0,0,0,1)" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z"></path>
                        </svg>
                    </li>
                    <li class="d-flex align-items-center">
                        <span class="fw-semibold">{{ __('Blogs') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endpush
</div>


@endsection
