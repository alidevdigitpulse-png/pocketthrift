



@extends('layouts.app')
@php
    $regionService = app(\App\Services\RegionService::class);
    $currentRegion = $regionService->getCurrentRegion();

    $regionCode = strtolower($currentRegion->code ?? 'us');
    $regionName = $currentRegion->country ?? 'United States';

@endphp
@php
    // Ensure $meta exists
    $meta = $meta ?? [];
    
    // Determine region code from URL
    $firstSegment = strtolower(request()->segment(1) ?? '');
    $regions = [
        'us' => 'United States', 'uk' => 'United Kingdom', 'au' => 'Australia',
        'ca' => 'Canada', 'fr' => 'France', 'de' => 'Germany',
        'it' => 'Italy', 'nl' => 'Netherlands', 'pl' => 'Poland',
        'es' => 'Spain', 'mx' => 'Mexico', 'ch' => 'Switzerland',
        'lu' => 'Luxembourg', 'fi' => 'Finland', 'no' => 'Norway',
        'nz' => 'New Zealand', 'sg' => 'Singapore', 'at' => 'Austria'
    ];
    
    // Check if first segment is a valid region code
    $regionCode = array_key_exists($firstSegment, $regions) ? $firstSegment : 'us';
    $regionName = $regions[$regionCode];

    // Dynamic Meta Content
    $catTitle = __('Best Deals & Promo Codes by Category in :region', ['region' => $regionName]);
    $catDesc = __('Find amazing deals and coupons on auto accessories, fashion, tech gadgets, and more. Save big across all categories and enjoy incredible savings in :region.', ['region' => $regionName]);
    
    // Base URL
    $baseUrl = rtrim(config('app.url','https://pocketthrift.com'), '/');
    
    // Site URL (regionalized)
    $siteUrl = ($regionCode === 'us') ? $baseUrl : $baseUrl . '/' . $regionCode;
    
    // Get current path
    $path = request()->getPathInfo();
    
    // Remove region code from path if it exists to avoid duplication
    $pathWithoutRegion = $path;
    if ($regionCode !== 'us' && strpos($path, '/' . $regionCode . '/') === 0) {
        $pathWithoutRegion = substr($path, strlen('/' . $regionCode));
    }
    
    // Full URL for current page
    $fullUrl = rtrim($siteUrl, '/') . '/' . ltrim($pathWithoutRegion, '/');
    
    // Page title & description
    $title = $page->meta_title ?? $page->name ?? __('Best Deals & Promo Codes by Category in :region', ['region' => $regionName]);
    
    // Replace placeholders in title
    $title = str_replace(['$regionName', ':region'], $regionName, $title);
    $title = str_replace('in the ' . $regionName, 'in ' . $regionName, $title); // Clean up common grammar issues
    
    $description = $page->meta_description ?? $catDesc;
    
    // Replace placeholders in description
    $description = str_replace(['$regionName', ':region'], $regionName, $description);
    
    // Breadcrumbs setup
    $breadcrumbs = [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => __('Home'),
            'item' => $siteUrl
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => __('Categories'),
            'item' => $fullUrl
        ]
    ];
@endphp

@push('schemas')
@section('title', $title)
@section('meta_description', $description)
<meta name="keywords" content="Offers and promo codes">
<meta name="robots" content="index, follow">

<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="https://pocketthrift.com/images/og-image.webp">

<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:domain" content="pocketthrift.com">
<meta property="twitter:url" content="{{ url()->current() }}">
<meta property="twitter:title" content="{{ $title }}">
<meta property="twitter:description" content="{{ $description }}">
<meta property="twitter:image" content="https://pocketthrift.com/images/og-image.webp">
<script type="application/ld+json">
{!! json_encode([
    "@context" => "https://schema.org",
    "@type" => "WebPage",
    "name" => str_replace('United States', 'USA', $title),
    "description" => str_replace('United States', 'USA', $description),
    "url" => $fullUrl,
    "publisher" => [
        "@type" => "Organization",
        "name" => "PocketThrift",
        "logo" => [
            "@type" => "ImageObject",
            "url" => $baseUrl . '/images/og-image.webp'
        ]
    ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>

<script type="application/ld+json">
{!! json_encode([
    "@context" => "https://schema.org",
    "@type" => "BreadcrumbList",
    "itemListElement" => $breadcrumbs
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush


@section('content')

    <div class="category-content pb-2 pt-3">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="mb-4 text-dark">{{ __('All Categories') }}</h1>
                    <div class="secondary-heading">{{ __('Verified On') }}: {{ __(date('F')) }}, {{ date('Y') }}</div>
                    <p>{{ __('Are you on the hunt for the best deals on car accessories, fashion, tech gadgets, and more? You’ve come to the right place! Here at PocketThrift. :region , you can find amazing discounts across a wide range of categories, from art supplies to baby products and everything in between. Our carefully curated selection of promo codes and deals helps you save big on top brands and essential items. Explore our site for unbeatable savings, and don’t miss out on fantastic bargains designed just for you. Start shopping smart today and enjoy significant discounts on your favorite products!', ['region' => $regionName]) }}
                    </p>

                </div>
            </div>
        </div>
    </div>

    <div class="category-cards">
        <div class="container">
            <div class="top-categories-widget pb-5">
                <h2 class="mt-5 pb-3">{{ __('Top Categories for Coupons and Deals in the :region', ['region' => $regionName]) }}


                <!-- Alphabet Filter -->
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                    <!-- Alphabet Filter -->
                    <div class="alphabet-filter d-flex flex-wrap align-items-center flex-grow-1">
                        <button class="btn btn-sm btn-primary me-2 active" data-letter="all">All</button>
                        <script>
                            document.write(
                                Array.from("ABCDEFGHIJKLMNOPQRSTUVWXYZ")
                                .map(l => `<button class='btn btn-sm btn-outline-secondary me-1 mb-1' data-letter='${l}'>${l}</button>`)
                                .join("")
                            );
                        </script>
                    </div>

                    <!-- Search Bar -->
                    <div class="search-bar ms-auto" style="min-width: 150px;">
                        <input type="text" id="searchInput" class="form-control form-control-sm"
                            placeholder="{{ __('Search categories...') }}">
                    </div>
                </div>

                <!-- Categories -->
                <div class="row g-3" id="categoryContainer">
                    @if (isset($categories) && $categories->count() > 0)
                        @foreach ($categories as $category) 
                            <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                                <a href="{{ ($regionCode === 'us' || !$regionCode) ? route('category.detail', ltrim($category->url_slug, '/')) : route('region.category.detail', ['region' => $regionCode, 'category' => ltrim($category->url_slug, '/')]) }}"
                                    class="text-decoration-none">
                                    <div class="category-card">
                                        <div class="category-img">
                                            @if ($category->logo)
                                                <img src="{{ asset('uploads/' . $category->logo) }}"
                                                    alt="{{ $category->title }}" class="img-fluid">
                                            @else
                                                <img src="{{ asset('uploads/default-category.png') }}"
                                                    alt="{{ $category->title }}" class="img-fluid">
                                            @endif
                                        </div>
                                        <div class="category-info">
                                            <h6>{{ $category->title }}</h6>
             
                                            <!-- Since parent_id doesn't exist, there are no subcategories -->
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <p class="text-center">No categories available at the moment.</p>
                        </div>
                    @endif
                </div>

                <!-- Server-side filtering via AJAX -->
                <script>
                    // Server-side filtering via AJAX
                    function applyFilters(letter = null, search = null) {
                        // Get current URL parameters
                        const url = new URL(window.location.href);
                        const params = new URLSearchParams(url.search);

                        // Update parameters with new values
                        if (letter && letter !== 'all') {
                            params.set('letter', letter);
                        } else {
                            params.delete('letter');
                        }

                        if (search) {
                            params.set('search', search);
                        } else {
                            params.delete('search');
                        }

                        // Build new URL and redirect
                        url.search = params.toString();
                        window.location.href = url.toString();
                    }

                    // Handle alphabet filter clicks
                    document.querySelectorAll(".alphabet-filter button").forEach(btn => {
                        btn.addEventListener("click", () => {
                            document.querySelector(".alphabet-filter .active")?.classList.remove("active");
                            btn.classList.add("active");
                            const letter = btn.getAttribute("data-letter");
                            const searchValue = document.getElementById("searchInput").value;
                            applyFilters(letter, searchValue);
                        });
                    });

                    // Handle search input with debouncing
                    let searchTimeout;
                    document.getElementById("searchInput").addEventListener("input", e => {
                        clearTimeout(searchTimeout);
                        const searchValue = e.target.value.trim();
                        const letter = document.querySelector(".alphabet-filter .active").getAttribute("data-letter");

                        searchTimeout = setTimeout(() => {
                            applyFilters(letter, searchValue);
                        }, 500); // Debounce search by 500ms
                    });

                    // Set active filter based on URL parameters
                    document.addEventListener('DOMContentLoaded', function() {
                        const urlParams = new URLSearchParams(window.location.search);
                        const letterParam = urlParams.get('letter');
                        const searchParam = urlParams.get('search');

                        // Highlight active letter filter
                        if (letterParam) {
                            const activeBtn = document.querySelector(`.alphabet-filter button[data-letter="${letterParam}"]`);
                            if (activeBtn) {
                                document.querySelector(".alphabet-filter .active")?.classList.remove("active");
                                activeBtn.classList.add("active");
                            }
                        }

                        // Populate search field
                        if (searchParam) {
                            document.getElementById("searchInput").value = searchParam;
                        }
                    });
                </script>

            </div>
        </div>
    </div>

    <style>
        .category-cards {
            background: #f7f7f7;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .alphabet-filter {
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 4px;
        }

        
        .category-img {
            width: 30%;
            
            object-fit: cover;
            flex-shrink: 0;
            border: 2px solid #0d6dfd9a;
            opacity: 0.9;
            border-radius:8px;
        }   

        .category-img img{
            width: 70px;
            height: 50px;
            border-radius:8px;
                
        }

        .alphabet-filter::-webkit-scrollbar {
            height: 6px;
        }

        .alphabet-filter::-webkit-scrollbar-thumb {
            background-color: #dee2e6;
            border-radius: 4px;
        }

        .alphabet-filter button {
            border-radius: 8px;
            margin: 1px;
        }

        .alphabet-filter button.active {
            background-color: #0d6efd;
            color: white;
        }

        .category-card {
            border-radius: 15px;
            background: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            padding: 12px;
            gap: 15px;
            height: 100%;
            transition: transform 0.2s;
        }

        .category-card:hover {
            transform: translateY(-3px);
        }
 

        .category-info h6 {
            margin: 0;
            font-weight: 600;
            color: #000;
            font-size: 14px;
            overflow-wrap: anywhere;
        }

        .category-info p {
            margin: 0;
            color: #6c757d;
            font-size: 0.9rem;
        }

        @media (max-width: 767.98px) {
            .category-content h1 {
                font-size: 1.8rem;
            }
            .top-categories-widget h2 {
                font-size: 1.5rem;
            }
            .search-bar {
                width: 100%;
                margin-left: 0 !important;
            }
        }

        @media (max-width: 575.98px) {
            .category-card {
                flex-direction: column;
                text-align: center;
            }
            .category-img { width: 50%; }
        }

        /* Custom CSS for 5 columns on large screens */
        @media (min-width: 992px) {
            .col-lg-2-4 {
                flex: 0 0 auto;
                width: 20%;
            }
        }

        /* On medium screens show 3 items per row */
        @media (min-width: 768px) and (max-width: 991px) {
            .col-md-4 {
                width: 33.333333%;
            }
        }
    </style>
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
                        <span class="fw-semibold">{{ __('Categories') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endpush

@endsection
