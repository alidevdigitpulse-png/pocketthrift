@extends('layouts.app')

@push('schemas')
@php
    $regionService = app(\App\Services\RegionService::class);
    $currentRegion = $regionService->getCurrentRegion();

    $regionCode = strtolower($currentRegion->code ?? 'us');
    $regionName = $currentRegion->country ?? 'USA';

    $locale = app()->getLocale();

    // Dynamic Meta Title & Description (Translateable)
    $dynamicTitle = __("PocketThrift :region: Guides, Reviews, Coupons & Insights", ['region' => $regionName]);
    $dynamicDesc = __("Save money with PocketThrift :region! Discover the latest guides, product reviews, and exclusive coupons for valuable insights and smart shopping decisions!", ['region' => $regionName]);
@endphp
@section('title', $dynamicTitle)
@section('meta_description', $dynamicDesc)
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@pocketthrift">
<meta name="robots" content="index, follow">

<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="https://pocketthrift.com/uploads/logo.png">
<meta property="og:image:type" content="image/png">
<meta property="og:image:alt" content="PocketThrift">
<meta property="og:site_name" content="PocketThrift">
<meta property="og:title" content="{{ $dynamicTitle }}">
<meta property="og:description" content="{{ $dynamicDesc }}">

<meta property="twitter:title" content="{{ $dynamicTitle }}">
<meta property="twitter:description" content="{{ $dynamicDesc }}">
<meta property="twitter:image" content="https://pocketthrift.com/uploads/logo.png">
<meta property="twitter:image:alt" content="Pocketthrift">

{{-- This block is removed as the logic is now consolidated at the top of the file --}}

@php
    $baseUrl = rtrim(config('app.url', 'https://pocketthrift.com'), '/');
    $siteUrl = $regionCode === 'us' ? $baseUrl : $baseUrl . '/' . $regionCode;

    $faqEntities = [
        [
            'question' => 'What is a Promo Code and how do I use it?',
            'answer' => "Find the Promo Code: Locate a valid promo code on PocketThrift $regionName. We offer a variety of codes for different stores and products. Copy the Code: Click on the promo code to copy it to your clipboard. Shop Online: Go to the online store where you want to make a purchase and add items to your cart. Apply the Code: During checkout, look for a field labeled “Promo Code,” “Coupon Code,” or “Discount Code.” Paste the code into this field and apply it. Enjoy Your Savings: The discount will be applied to your total, and you’ll see the updated amount before you complete your purchase.",
        ],

        [
            'question' => 'Do Promo Codes really work?',
            'answer' => "Yes, Promo Codes often work, but only last for a very short period of time. Retailers frequently run time-limited campaigns and use common coupon phrases throughout the year $regionName.",
        ],

        [
            'question' => 'What are the differences between a Deal, Promotion and a Discount code?',
            'answer' => "A deal is when a product or service is available at a reduced price compared to its regular cost. Promotions are special offers from retailers, like limited-time sales or free items, that provide extra value. A discount code, or promotional code, is a specific code you enter at checkout to receive a percentage or fixed amount off your total purchase.
",
        ],

        [
            'question' => "How frequently are new Deals and Coupon Codes updated on PocketThrift $regionName?",
            'answer' => "PocketThrift $regionName regularly updates new deals and coupon codes to provide you with the latest offers. We refresh our selection several times a week, so be sure to visit often for the newest savings opportunities.",
        ],

        [
            'question' => "How can PocketThrift $regionName help me save money while shopping online?",
            'answer' => "PocketThrift $regionName helps you save money online by providing a variety of coupons and deals from numerous brands and categories. Our platform offers exclusive promo codes, curated deals, and regularly updated promotions, all easily accessible through our user-friendly interface. Use PocketThrift to find the best offers and maximize your savings on your favorite products and brands.",
        ],
    ];

    $faqMain = [];
    foreach ($faqEntities as $f) {
        $faqMain[] = [
            '@type' => 'Question',
            'name' => $f['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $f['answer'],
            ],
        ];
    }

    $faqSchema = [
        '@context' => 'https://schema.org/',
        '@type' => 'FAQPage',
        'mainEntity' => $faqMain,
    ];
@endphp

<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>
@endpush



@push('schemas')
@php
    $org = [
        '@context' => 'https://schema.org/',
        '@type' => 'Organization',
        'name' => 'PocketThrift',
        'url' => $siteUrl . '/',
        'logo' => $siteUrl . '/uploads/logo.png',
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => '+61 414 573 000',
            'contactType' => 'customer service',
            'areaServed' => $regionCode,
            'availableLanguage' => $locale,
        ],
        'sameAs' => [
            'https://www.facebook.com/people/Pocket-Thrift/61562541071877/?mibextid=ZbWKwL',
            'https://x.com/Pocketthrift',
            'https://www.instagram.com/pocketthrift1/',
            'https://www.linkedin.com/in/pocket-thrift-40336131b',
            'https://www.pinterest.com/pocketthrift1/',
        ],
    ];

    $website = [
        '@context' => 'https://schema.org/',
        '@type' => 'WebSite',
        'name' => 'PocketThrift',
        'url' => $siteUrl . '/',
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => $siteUrl . '/search?q={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ];

    $faqSchema = null;
    $faqsArr = $meta['faqs'] ?? ($faqs ?? []);
    if (!empty($faqsArr) && is_array($faqsArr)) {
        $entities = [];
        foreach ($faqsArr as $f) {
            $q = $f['question'] ?? ($f['name'] ?? null);
            $a = $f['answer'] ?? ($f['acceptedAnswer'] ?? null);
            if ($q && $a) {
                $entities[] = [
                    '@type' => 'Question',
                    'name' => $q,
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $a],
                ];
            }
        }
        if (count($entities)) {
            $faqSchema = ['@context' => 'https://schema.org/', '@type' => 'FAQPage', 'mainEntity' => $entities];
        }
    }
@endphp

<script type="application/ld+json">{!! json_encode($org, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>
<script type="application/ld+json">{!! json_encode($website, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>
@if ($faqSchema)
    <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>
@endif
@endpush



@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Hero Section -->
        <div class="col-12 p-0">
            @if(isset($banners) && $banners->count() > 0)
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    @foreach($banners as $key => $banner)
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"
                            aria-current="{{ $key == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $key + 1 }}"></button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach($banners as $key => $banner)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <a href="{{ $banner->url ?? '#' }}">
                                <img src="{{ asset($banner->image) }}" class="d-block w-100 hero-banner-img" alt="{{ $banner->name }}" loading="{{ $key == 0 ? 'eager' : 'lazy' }}" fetchpriority="{{ $key == 0 ? 'high' : 'auto' }}">
                            </a>
                            @if($banner->button_text || $banner->name)
                                <div class="carousel-caption d-none d-md-block">
                                    @if($banner->name) <h2 class="text-white" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">{{ $banner->name }}</h2> @endif
                                    @if($banner->button_text || $banner->url)
                                        <a href="{{ $banner->url ?? '#' }}" target="_blank" class="btn btn-lg mt-3" style="background-color:#cf5103;color:#fff;">{{ $banner->button_text ?: 'View Deal' }}</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            @else
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"
                        aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"
                        aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"
                        aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="d-block w-100 bg-primary text-white text-center py-5">
                            <div class="container">
                                <h1 class="display-4">{{ __('Welcome to') }} {{ config('app.name') }}</h1>
                                <p class="lead">{{ __('Discover amazing products at unbeatable prices') }}</p>
                                <a href="#" class="btn btn-light btn-lg">{{ __('Shop Now') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="d-block w-100 bg-success text-white text-center py-5">
                            <div class="container">
                                <h1 class="display-4">{{ __('Exclusive Deals') }}</h1>
                                <p class="lead">{{ __('Limited time offers on premium products') }}</p>
                                <a href="#" class="btn btn-light btn-lg">{{ __('View Deals') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="d-block w-100 bg-warning text-white text-center py-5">
                            <div class="container">
                                <h1 class="display-4">{{ __('FREE SHIPPING') }}</h1>
                                <p class="lead">{{ __('On all orders over $50') }}</p>
                                <a href="#" class="btn btn-dark btn-lg">{{ __('Start Shopping') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            @endif
        </div>
    </div>



    <!-- Trending Section -->
    <div class="mt-5">
        <div class="container">
            {{-- <h2 class="text-center  mb-5 ">Trending in {{ strtoupper( @if (request()->route('region'))
        {{ \App\Models\Region::where('code', request()->route('region'))->where('active', true)->first()->country ?? strtoupper(request()->route('region')) }}
      @else
        USA
      @endif) }}</h2> --}}

            <!-- Trending Stores Row -->
            <h3 class="mb-4 home-sec-heading">{{ __('Top Picks for You') }} </h3>
            <div class="row g-3 mb-5 box-trending-store">
                @if ($trendingStores && $trendingStores->count() > 0)
                    @foreach ($trendingStores as $store)
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-4">
                            <a href="{{ ($regionCode === 'us' || !$regionCode) ? route('store.detail', ltrim($store->url_slug, '/')) : route('region.store.detail', ['region' => $regionCode, 'store' => ltrim($store->url_slug, '/')]) }}"
                                class="text-decoration-none d-block h-100">
                                <div class="trending-cat-box text-center d-flex flex-column">
                                    <div class="trending-icon d-flex align-items-center justify-content-center mb-2">
                                        @if ($store->logo)
                                            <img src="{{ asset('uploads/' . $store->logo) }}" alt="{{ $store->title }}"
                                                class="img-fluid" style="object-fit: cover;" loading="lazy" width="200" height="100">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light"
                                                style="height: 100px; width: 100%;"><i
                                                    class="fas fa-store text-primary fa-2x"></i></div>
                                        @endif
                                    </div>
                                    <div class="trending-title mt-auto">
                                        <small class="categories-title-text">{{ $store->title }}</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <p class="text-center text-muted home-sec-heading">{{ __('No trending stores available') }}</p>
                    </div>
                @endif
            </div>

            <!-- Trending Categories Row -->
            <h3 class="mb-4 home-sec-heading">{{ __('Top Store Categories') }}
            </h3>
            <div class="row g-3 mb-5 box-trending-store">
                @if ($trendingCategories && $trendingCategories->count() > 0)
                    @foreach ($trendingCategories as $category)
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-4 trend-cat">
                            <a href="{{ ($regionCode === 'us' || !$regionCode) ? route('category.detail', ltrim($category->url_slug, '/')) : route('region.category.detail', ['region' => $regionCode, 'category' => ltrim($category->url_slug, '/')]) }}"
                                class="text-decoration-none d-block object-fit-cover">
                                <div class="trending-cat-box text-center d-flex flex-column">
                                    <div class="trending-icon d-flex align-items-center justify-content-center mb-2">
                                        @if ($category->logo)
                                            <img src="{{ asset('uploads/' . $category->logo) }}"
                                                alt="{{ $category->title }}" class="img-fluid"
                                                style="object-fit: cover;" loading="lazy" width="150" height="100">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light"
                                                style="height: 100px; width: 100%;"><i
                                                    class="fas fa-tags text-primary fa-2x"></i></div>
                                        @endif
                                    </div>
                                    <div class="trending-title mt-auto">
                                        <small class="categories-title-text">{{ $category->title }}</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <p class="text-center text-muted">{{ __('No trending categories available') }}</p>
                    </div>
                @endif
            </div>

            <!-- Top Banner Section -->
            @if(isset($topBanner) && $topBanner)
            <div class="row mb-5">
                <div class="col-12 p-0">
                    <div class="banner-wrapper">
                        <a href="{{ $topBanner->url ?? '#' }}" class="d-block" target="_blank" aria-label="Top Banner">
                            <img src="{{ asset($topBanner->image) }}" alt="{{ $topBanner->name }}" class="img-fluid w-100" style="border-radius: 10px; max-height: 400px; object-fit: cover;" width="1200" height="400" loading="lazy">
                        </a>
                        @if($topBanner->button_text || $topBanner->name)
                            <div class="banner-overlay">
                                @if($topBanner->name) 
                                    <h3 class="text-white mb-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">{{ $topBanner->name }}</h3> 
                                @endif
                                @if($topBanner->button_text)
                                    <a href="{{ $topBanner->url ?? '#' }}" class="btn btn-primary btn-lg" target="_blank" style="background-color:#cf5103;color:#fff;border:none;">{{ $topBanner->button_text }}</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Trending Offers Carousel - Row 1 -->
            <h3 class="mb-4 home-sec-heading">{{ __('Trending Coupons & Deals') }}</h3>
            @if ($trendingOffersRow1 && $trendingOffersRow1->count() > 0)
                <div class="trending-offers-carousel mb-5">
                    @foreach ($trendingOffersRow1 as $index => $offer)
                        @php
                            $store = $offer->store;
                            $category = optional($store)->category;
                        @endphp
                        <div class="carousel-item-wrapper">
                            <div class="offer-grid-card h-100">
                                <div class="card-banner position-relative overflow-hidden">
                                    <img src="{{ $category && $category->logo ? asset('uploads/' . $offer->logo) : asset('images/default-banner.png') }}" 
                                         alt="{{ $offer->title }}" 
                                         class="w-100 h-100 object-fit-cover position-absolute top-0 start-0"
                                         loading="lazy" width="300" height="150">
                                </div>
                                <div class="store-logo-container">
                                    @if ($store && $store->logo)
                                        <img src="{{ asset('uploads/' . $store->logo) }}"
                                            alt="{{ $store->title }}">
                                    @else
                                        <img src="{{ asset('images/default-logo.png') }}" alt="Default Logo">
                                    @endif
                                </div>
                                <div class="card-content">
                                    <div>
                                        <h5 class="offer-title">{{ $offer->title }}</h5>
                                        @if ($offer->discount)
                                            <p class="fs-5 fw-bold" style="color:#cf5103";>
                                                {!! str_ireplace('Get ', 'Get<br>', $offer->discount) !!}
                                            </p>
                                        @elseif(in_array(trim(strtolower($offer->type ?? '')), ['sale', 'offer']))
                                            <p class="fs-5 fw-bold" style="color:#cf5103";>
                                                {{ (isset($offer->type) && trim(strtolower($offer->type)) == 'sale') ? __('SALE') : __('OFFER') }}
                                            </p>
                                        @endif
                                    </div>
                                    <a href="{{ ($regionCode === 'us' || !$regionCode) ? route('store.detail', ltrim(optional($offer->store)->url_slug, '/')) : route('region.store.detail', ['region' => $regionCode, 'store' => ltrim(optional($offer->store)->url_slug, '/')]) }}"
                                        class="btn get-code-btn mt-auto">
                                        {{ $offer->type === 'Code' ? __('REVEAL CODE') : __('GET DEAL') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mb-5">
                    <p class="text-center text-muted">{{ __('No trending offers available') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Second Trending Offers Carousel - Row 2 -->
    <div class="mt-5">
        <div class="container">
                 <h3 class="mb-4 home-sec-heading">{{ __('Featured Coupons & Deals') }}</h3>
            <h3 class="mb-4">{{ __('') }}</h3>
            @if ($trendingOffersRow2 && $trendingOffersRow2->count() > 0)
                <div class="trending-offers-carousel-2 mb-5">
                    @foreach ($trendingOffersRow2 as $index => $offer)
                        @php
                            $store = $offer->store;
                            $category = optional($store)->category;
                        @endphp
                        <div class="carousel-item-wrapper">
                            <div class="offer-grid-card h-100">
                                <div class="card-banner position-relative overflow-hidden">
                                    <img src="{{ $category && $category->logo ? asset('uploads/' . $offer->logo) : asset('images/default-banner.png') }}" 
                                         alt="{{ $offer->title }}" 
                                         class="w-100 h-100 object-fit-cover position-absolute top-0 start-0"
                                         loading="lazy" width="300" height="150">
                                </div>
                                <div class="store-logo-container">
                                    @if ($store && $store->logo)
                                        <img src="{{ asset('uploads/' . $store->logo) }}"
                                            alt="{{ $store->title }}">
                                    @else
                                        <img src="{{ asset('images/default-logo.png') }}" alt="Default Logo">
                                    @endif
                                </div>
                                <div class="card-content">
                                    <div>
                                        <h5 class="offer-title">{{ $offer->title }}</h5>
                                        @if ($offer->discount)
                                            <p class="fs-5 fw-bold" style="color:#cf5103";>
                                                {!! str_ireplace('Get ', 'Get<br>', $offer->discount) !!}
                                            </p>
                                        @elseif(in_array(trim(strtolower($offer->type ?? '')), ['sale', 'offer']))
                                            <p class="fs-5 fw-bold" style="color:#cf5103";>
                                                {{ (isset($offer->type) && trim(strtolower($offer->type)) == 'sale') ? __('SALE') : __('OFFER') }}
                                            </p>
                                        @endif
                                    </div>
                                    <a href="{{ ($regionCode === 'us' || !$regionCode) ? route('store.detail', ltrim(optional($offer->store)->url_slug, '/')) : route('region.store.detail', ['region' => $regionCode, 'store' => ltrim(optional($offer->store)->url_slug, '/')]) }}"
                                        class="btn get-code-btn mt-auto">
                                        {{ $offer->type === 'Code' ? __('REVEAL CODE') : __('GET DEAL') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mb-5">
                    <p class="text-center text-muted">{{ __('No trending offers available') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Third Trending Offers Carousel - Row 3 -->
    <div class="mt-5">
        <div class="container">
                 <h3 class="mb-4 home-sec-heading">{{ __('Popular Coupons & Deals') }}</h3>

            <h3 class="mb-4">{{ __('') }}</h3>
            @if ($trendingOffersRow3 && $trendingOffersRow3->count() > 0)
                <div class="trending-offers-carousel-3 mb-5">
                    @foreach ($trendingOffersRow3 as $index => $offer)
                        @php
                            $store = $offer->store;
                            $category = optional($store)->category;
                        @endphp
                        <div class="carousel-item-wrapper">
                            <div class="offer-grid-card h-100">
                                <div class="card-banner position-relative overflow-hidden">
                                    <img src="{{ $category && $category->logo ? asset('uploads/' . $offer->logo) : asset('images/default-banner.png') }}" 
                                         alt="{{ $offer->title }}" 
                                         class="w-100 h-100 object-fit-cover position-absolute top-0 start-0"
                                         loading="lazy" width="300" height="150">
                                </div>
                                <div class="store-logo-container">
                                    @if ($store && $store->logo)
                                        <img src="{{ asset('uploads/' . $store->logo) }}"
                                            alt="{{ $store->title }}">
                                    @else
                                        <img src="{{ asset('images/default-logo.png') }}" alt="Default Logo">
                                    @endif
                                </div>
                                <div class="card-content">
                                    <div>
                                        <h5 class="offer-title">{{ $offer->title }}</h5>
                                        @if ($offer->discount)
                                            <p class="fs-5 fw-bold" style="color:#cf5103";>
                                                {!! str_ireplace('Get ', 'Get<br>', $offer->discount) !!}
                                            </p>
                                        @elseif(in_array(trim(strtolower($offer->type ?? '')), ['sale', 'offer']))
                                            <p class="fs-5 fw-bold" style="color:#cf5103";>
                                                {{ (isset($offer->type) && trim(strtolower($offer->type)) == 'sale') ? __('SALE') : __('OFFER') }}
                                            </p>
                                        @endif
                                    </div>
                                    <a href="{{ ($regionCode === 'us' || !$regionCode) ? route('store.detail', ltrim(optional($offer->store)->url_slug, '/')) : route('region.store.detail', ['region' => $regionCode, 'store' => ltrim(optional($offer->store)->url_slug, '/')]) }}"
                                        class="btn get-code-btn mt-auto">
                                        {{ $offer->type === 'Code' ? __('REVEAL CODE') : __('GET DEAL') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mb-5">
                    <p class="text-center text-muted">{{ __('No trending offers available') }}</p>
                </div>
            @endif
        </div>
    </div>


<!-- Banner Section -->
@if(isset($bottomBanner) && $bottomBanner)
<section class="section mt-5 mb-5">
    <div class="container">
        <div class="banner-wrapper">
            <a href="{{ $bottomBanner->url ?? '#' }}" class="d-block" target="_blank" aria-label="Bottom Banner">
                <img src="{{ asset($bottomBanner->image) }}" alt="{{ $bottomBanner->name }}" class="img-fluid w-100" style="border-radius: 10px; max-height: 400px; object-fit: cover;" width="1200" height="400" loading="lazy">
            </a>
            @if($bottomBanner->button_text || $bottomBanner->name)
                <div class="banner-overlay">
                    @if($bottomBanner->name) 
                        <h3 class="text-white mb-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">{{ $bottomBanner->name }}</h3> 
                    @endif
                    @if($bottomBanner->button_text)
                        <a href="{{ $bottomBanner->url ?? '#' }}" class="btn btn-primary btn-lg" target="_blank" style="background-color:#cf5103;color:#fff;border:none;">{{ $bottomBanner->button_text }}</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Trending Blogs Section -->
<section class="section mt-5 mb-5">
    <div class="container"> 
        <div class="section-header mb-4">
            <h2 class="section-title home-sec-heading">{{ __('Trending Blogs') }}</h2>
        </div>

        <div class="row">
            @if(isset($trendingBlogs) && $trendingBlogs->count() > 0)
                @foreach($trendingBlogs as $blog)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm blog-card">
                            <div class="position-relative">
                                <a href="{{ ($regionCode === 'us' || !$regionCode) ? route('blog.detail', ltrim($blog->url_slug, '/')) : route('region.blog.detail', ['region' => $regionCode, 'slug' => ltrim($blog->url_slug, '/')]) }}" class="d-block overflow-hidden rounded-top">
                                    @if($blog->logo)
                                        <img src="{{ asset('uploads/' . $blog->logo) }}" class="card-img-top transition-transform duration-300" alt="{{ $blog->image_alt ?: $blog->title }}" style="height: 200px; object-fit: cover; width: 100%;" loading="lazy" width="400" height="200">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px; width: 100%;">
                                            <i class="fas fa-blog fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                </a>
                                @if($blog->category)
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge blog">{{ $blog->category->title }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title h6 mb-2">
                                    <a href="{{ ($regionCode === 'us' || !$regionCode) ? route('blog.detail', ltrim($blog->url_slug, '/')) : route('region.blog.detail', ['region' => $regionCode, 'slug' => ltrim($blog->url_slug, '/')]) }}" class="text-dark text-decoration-none fw-bold">
                                        {{ $blog->title }}
                                    </a>
                                </h5>
                                <p class="card-text">{{ Str::limit($blog->short_description, 100) }}</p>
                            </div>
                            <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                                <a href="{{ ($regionCode === 'us' || !$regionCode) ? route('blog.detail', ltrim($blog->url_slug, '/')) : route('region.blog.detail', ['region' => $regionCode, 'slug' => ltrim($blog->url_slug, '/')]) }}" class="small fw-bold text-decoration-none">
                                    {{ __('Read More') }} <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center">
                    <p class="text-muted">No trending blogs available at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<style>
    /* Trending Offers Carousel Styles */
    .object-fit-cover {
        object-fit: cover;
    }
    .home-sec-heading {
    font-size: 30px;
    font-weight: 500;
    color: #000;
    text-transform: uppercase;
}
    .card-banner {
        height: 150px; /* Or whatever the height was before, relying on CSS file probably but enforcing it here to be safe given inline styles were removed */
        background-color: #f0f0f0; /* Fallback */
    }
    .trending-offers-carousel {
        margin: 0 -15px;
    }

    .trending-offers-carousel .carousel-item-wrapper {
        padding: 0 15px;
    }

    .trending-offers-carousel .slick-slide {
        margin: 0 10px;
    }

    .trending-offers-carousel .slick-list {
        margin: 0 -10px;
    }

    .trending-offers-carousel .slick-prev,
    .trending-offers-carousel .slick-next {
        width: 40px;
        height: 40px;
        background-color: #cf5103;
        border-radius: 50%;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .trending-offers-carousel .slick-prev:hover,
    .trending-offers-carousel .slick-next:hover {
        background-color: #cf5103;
    }

    .trending-offers-carousel .slick-prev {
        left: -20px;
    }

    .trending-offers-carousel .slick-next {
        right: -20px;
    }

    .trending-offers-carousel .slick-prev:before,
    .trending-offers-carousel .slick-next:before {
        display: none;
    }

    .trending-offers-carousel .slick-prev i,
    .trending-offers-carousel .slick-next i {
        color: #fff;
        font-size: 16px;
    }

    .trending-offers-carousel .slick-dots {
        bottom: -40px;
    }

    .trending-offers-carousel .slick-dots li button:before {
        font-size: 12px;
        color: #cf5103;
    }

    .trending-offers-carousel .slick-dots li.slick-active button:before {
        color: #cf5103;
    }

    @media (max-width: 768px) {
        .trending-offers-carousel {
            margin: 0;
        }

        .trending-offers-carousel .carousel-item-wrapper {
            padding: 0 5px;
        }

        .trending-offers-carousel .slick-slide {
            margin: 0 5px;
        }

        .trending-offers-carousel .slick-list {
            margin: 0 -5px;
        }
    }

    /* Second Trending Offers Carousel Styles */
    .trending-offers-carousel-2 {
        margin: 0 -15px;
    }

    .trending-offers-carousel-2 .carousel-item-wrapper {
        padding: 0 15px;
    }

    .trending-offers-carousel-2 .slick-slide {
        margin: 0 10px;
    }

    .trending-offers-carousel-2 .slick-list {
        margin: 0 -10px;
    }

    .trending-offers-carousel-2 .slick-prev,
    .trending-offers-carousel-2 .slick-next {
        width: 40px;
        height: 40px;
        background-color: #cf5103;
        border-radius: 50%;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .trending-offers-carousel-2 .slick-prev:hover,
    .trending-offers-carousel-2 .slick-next:hover {
        background-color: #cf5103;
    }

    .trending-offers-carousel-2 .slick-prev {
        left: -20px;
    }

    .trending-offers-carousel-2 .slick-next {
        right: -20px;
    }

    .trending-offers-carousel-2 .slick-prev:before,
    .trending-offers-carousel-2 .slick-next:before {
        display: none;
    }

    .trending-offers-carousel-2 .slick-prev i,
    .trending-offers-carousel-2 .slick-next i {
        color: #fff;
        font-size: 16px;
    }

    .trending-offers-carousel-2 .slick-dots {
        bottom: -40px;
    }

    .trending-offers-carousel-2 .slick-dots li button:before {
        font-size: 12px;
        color: #cf5103;
    }

    .trending-offers-carousel-2 .slick-dots li.slick-active button:before {
        color: #cf5103;
    }

    @media (max-width: 768px) {
        .trending-offers-carousel-2 {
            margin: 0;
        }

        .trending-offers-carousel-2 .carousel-item-wrapper {
            padding: 0 5px;
        }

        .trending-offers-carousel-2 .slick-slide {
            margin: 0 5px;
        }

        .trending-offers-carousel-2 .slick-list {
            margin: 0 -5px;
        }
    }

    /* Third Trending Offers Carousel Styles */
    .trending-offers-carousel-3 {
        margin: 0 -15px;
    }

    .trending-offers-carousel-3 .carousel-item-wrapper {
        padding: 0 15px;
    }

    .trending-offers-carousel-3 .slick-slide {
        margin: 0 10px;
    }

    .trending-offers-carousel-3 .slick-list {
        margin: 0 -10px;
    }

    .trending-offers-carousel-3 .slick-prev,
    .trending-offers-carousel-3 .slick-next {
        width: 40px;
        height: 40px;
        background-color: #cf5103;
        border-radius: 50%;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .trending-offers-carousel-3 .slick-prev:hover,
    .trending-offers-carousel-3 .slick-next:hover {
        background-color: #cf5103;
    }

    .trending-offers-carousel-3 .slick-prev {
        left: -20px;
    }

    .trending-offers-carousel-3 .slick-next {
        right: -20px;
    }

    .trending-offers-carousel-3 .slick-prev:before,
    .trending-offers-carousel-3 .slick-next:before {
        display: none;
    }

    .trending-offers-carousel-3 .slick-prev i,
    .trending-offers-carousel-3 .slick-next i {
        color: #fff;
        font-size: 16px;
    }

    .trending-offers-carousel-3 .slick-dots {
        bottom: -40px;
    }

    .trending-offers-carousel-3 .slick-dots li button:before {
        font-size: 12px;
        color: #cf5103;
    }

    .trending-offers-carousel-3 .slick-dots li.slick-active button:before {
        color: #cf5103;
    }

    @media (max-width: 768px) {
        .trending-offers-carousel-3 {
            margin: 0;
        }

        .trending-offers-carousel-3 .carousel-item-wrapper {
            padding: 0 5px;
        }

        .trending-offers-carousel-3 .slick-slide {
            margin: 0 5px;
        }

        .trending-offers-carousel-3 .slick-list {
            margin: 0 -5px;
        }
    }

    /* Trending Offers Grid Styles */

    .trend-cat img {
    width: -webkit-fill-available;
    border-radius: 7px 7px 0px 0px;
    height: 160px;
    padding: 0px;

    }

    span.badge.blog {
    width: -webkit-fill-available;
}
    .offer-grid-card {
            border: 3px dashed #ff4900;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    background: #fff1e8;
    text-align: center;
    }

    .offer-grid-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .offer-grid-card .card-banner {
        height: 170px;
        background-size: cover;
        background-position: 100% 100%;
        background-repeat: no-repeat;
    }

    .offer-grid-card .store-logo-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: -30px;
        margin-bottom: 15px;
        position: relative;
        z-index: 1;
    }

    .offer-grid-card .store-logo-container img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 3px solid #fff;
        background: #fff;
        object-fit: contain;
        padding: 5px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .offer-grid-card .card-content {
        padding: 0 20px 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .offer-grid-card .offer-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #000;
        min-height: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .offer-grid-card .get-code-btn {
        background-color: #cf5103;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: 600;
        text-transform: uppercase;
        transition: background-color 0.3s ease;
        text-align: center;
        text-decoration: none;
        display: block;
    }

    .offer-grid-card .get-code-btn:hover {
        background-color: #cf5103;
        color: #fff;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .offer-grid-card .offer-title {
            font-size: 0.9rem;
            min-height: 40px;
        }
        
        .offer-grid-card .card-content {
            padding: 0 15px 15px;
        }
    }

    /* Trending Blogs Custom Styles */
    .blog-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        border: 2px dashed #002955;
    }
    
    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        
    }

    .transition-transform {
        transition: transform 0.3s ease;
    }

    .duration-300 {
        transition-duration: 0.3s;
    }

    .blog-card:hover .card-img-top {
        transform: scale(1.05);
    }

    /* Banner Section Styles */
    .banner-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
    }


    .banner-wrapper img {
        transition: transform 0.3s ease;
    }

    .banner-wrapper:hover img {
        transform: scale(1.02);
    }

    .banner-overlay {
        position: absolute;
        bottom: 30px;
        left: 30px;
        z-index: 2;
    }

    @media (max-width: 768px) {
        .banner-overlay {
            bottom: 15px;
            left: 15px;
        }
        
        .banner-overlay h3 {
            font-size: 1.2rem;
        }
    }

    /* Hero Banner Responsive Styles */
    .hero-banner-img {
        width: 100%;
        height: 500px;
        object-fit: cover;
    }

    @media (max-width: 991px) {
        .hero-banner-img {
            height: 260px;
        }
    }

    @media (max-width: 576px) {
        .hero-banner-img {
            height: 140px; /* Reduced height for mobile */
        }
    }
</style>

<div class="container mt-5">
    <div class="faq-header home-sec-heading">
        {{ __('FREQUENTLY ASKED QUESTIONS (FAQS)') }}
    </div>

    @php
        $regionModel = request()->route('region') ? \App\Models\Region::where('code', request()->route('region'))->where('active', true)->first() : null;
        $regionName = request()->route('region') 
            ? (optional($regionModel)->country ?? strtoupper(request()->route('region'))) 
            : 'USA';
    @endphp

    <div class="faq-container">
        <!-- Question 1 - Expanded by default -->
        <div class="faq-item">
            <div class="faq-question expanded" onclick="toggleFAQ(this)">
                <i class="fas fa-minus"></i>
                {{ __('faq_promo_code_what_is_title') }}
            </div>
            <div class="faq-content show">
                {!! __('faq_promo_code_what_is_content', ['region' => $regionName]) !!}
            </div>
        </div>

        <!-- Question 2 -->
        <div class="faq-item">
            <div class="faq-question collapsed" onclick="toggleFAQ(this)">
                <i class="fas fa-plus"></i>
                {{ __('faq_promo_codes_work_title') }}
            </div>
            <div class="faq-content">
                {!! __('faq_promo_codes_work_content') !!}
            </div>
        </div>

        <!-- Question 3 -->
        <div class="faq-item">
            <div class="faq-question collapsed" onclick="toggleFAQ(this)">
                <i class="fas fa-plus"></i>
                {{ __('faq_deal_vs_promotion_title') }}
            </div>
            <div class="faq-content">
                {!! __('faq_deal_vs_promotion_content') !!}
            </div>
        </div>

        <!-- Question 4 -->
        <div class="faq-item">
            <div class="faq-question collapsed" onclick="toggleFAQ(this)">
                <i class="fas fa-plus"></i>
                {{ __('faq_update_frequency_title', ['region' => $regionName]) }}
            </div>
            <div class="faq-content">
                {!! __('faq_update_frequency_content', ['region' => $regionName]) !!}
            </div>
        </div>

        <!-- Question 5 -->
        <div class="faq-item">
            <div class="faq-question collapsed" onclick="toggleFAQ(this)">
                <i class="fas fa-plus"></i>
                {{ __('faq_how_save_money_title', ['region' => $regionName]) }}
            </div>
            <div class="faq-content">
                {!! __('faq_how_save_money_content', ['region' => $regionName]) !!}
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFAQ(element) {
        const question = element;
        const content = question.nextElementSibling;
        const icon = question.querySelector('i');

        // Close all other FAQ items
        document.querySelectorAll('.faq-question').forEach(q => {
            if (q !== question) {
                q.classList.remove('expanded');
                q.classList.add('collapsed');
                q.querySelector('i').classList.replace('fa-minus', 'fa-plus');
                q.nextElementSibling.classList.remove('show');
            }
        });

        // Toggle current FAQ
        if (question.classList.contains('collapsed')) {
            question.classList.remove('collapsed');
            question.classList.add('expanded');
            icon.classList.replace('fa-plus', 'fa-minus');
            content.classList.add('show');
        } else {
            question.classList.remove('expanded');
            question.classList.add('collapsed');
            icon.classList.replace('fa-minus', 'fa-plus');
            content.classList.remove('show');
        }
    }
</script>

</div>

@push('js')
<script>
    // Initialize Trending Offers Carousel
    $(document).ready(function(){
        $('.trending-offers-carousel').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true,
            dots: true,
            prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false
                    }
                }
            ]
        });

        // Initialize Second Trending Offers Carousel
        $('.trending-offers-carousel-2').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true,
            dots: true,
            prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false
                    }
                }
            ]
        });

        // Initialize Third Trending Offers Carousel
        $('.trending-offers-carousel-3').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true,
            dots: true,
            prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false
                    }
                }
            ]
        });
    });
</script>
@endpush

@endsection
