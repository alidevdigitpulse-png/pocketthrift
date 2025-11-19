@extends('layouts.app')
@push('schemas')
@php
    // Get region code from multiple sources
    $regionCode = $region->code ?? ($regionCode ?? null);
    
    // If still null, try to extract from current URL path
    if (!$regionCode) {
        $path = request()->path();
        $segments = explode('/', $path);
        // Check if first segment is a valid region code (2 letters)
        if (isset($segments[0]) && strlen($segments[0]) === 2 && ctype_alpha($segments[0])) {
            $regionCode = strtolower($segments[0]);
        } else {
            $regionCode = 'us';
        }
    }
    
    // Define region names mapping
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
    
    // SITE URL ALWAYS REGIONIZED
    $siteUrl = ($regionCode === 'us') ? $baseUrl : $baseUrl . '/' . $regionCode;
    
    // Get store name from page or slug
    $storeName = $store->name ?? ($page->name ?? '');
    $storeName = preg_replace('/-' . $regionCode . '$/i', '', $storeName);
    $storeName = trim($storeName);
    
    // Build title and description
    $title = $meta['title'] ?? ($page->meta_title ?? $storeName);
    $description = $meta['description'] ?? ($page->meta_description ?? null);
    
    // Replace region placeholders
    $title = str_replace(['$regionName', '$regionCode', '$storeName'], [$regionName, $regionCode, $storeName], $title);
    $description = str_replace(['$regionName', '$regionCode', '$storeName'], [$regionName, $regionCode, $storeName], $description);
    
    $path = $meta['path'] ?? request()->getPathInfo();
    
    // Remove region code from path if it exists to avoid duplication
    $pathWithoutRegion = $path;
    if ($regionCode !== 'us' && strpos($path, '/' . $regionCode . '/') === 0) {
        $pathWithoutRegion = substr($path, strlen('/' . $regionCode));
    }
    
    // Clean the path
    $cleanPath = trim($pathWithoutRegion, '/');
    
    // FULL URL: Build properly formatted URL
    if (!empty($cleanPath)) {
        $fullUrl = rtrim($siteUrl, '/') . '/' . $cleanPath . '/';
    } else {
        $fullUrl = rtrim($siteUrl, '/') . '/';
    }
    
    // Build stores page URL
    $storesPath = 'stores';
    $storesUrl = rtrim($siteUrl, '/') . '/' . $storesPath . '/';
    
    // INITIALIZE BREADCRUMBS (3 levels: Home > Stores > Store Name)
    $breadcrumbs = $meta['breadcrumbs'] ?? [
        ['name'=>'Home', 'url'=>$siteUrl],
        ['name'=>'Stores', 'url'=>$storesUrl],
        ['name'=>$storeName, 'url'=>$fullUrl]
    ];
    
    // Force correct URLs
    $breadcrumbs[0]['url'] = $siteUrl;
    $breadcrumbs[1]['url'] = $storesUrl;
@endphp

@php
    // WebPage Schema
    $webpage = [
        "@context"=>"https://schema.org/",
        "@type"=>"WebPage",
        "name"=>$title,
        "description"=>$description,
        "url"=>$fullUrl,
        "publisher"=>[
            "@type"=>"Organization",
            "name"=>"PocketThrift",
            "logo"=>[
                "@type"=>"ImageObject",
                "url"=>$baseUrl . '/images/og-image.webp'
            ]
        ]
    ];
    
    // Breadcrumb Schema
    $crumbItems = [];
    $pos = 1;
    
    foreach($breadcrumbs as $b){
        $url = $b['url'];
        
        $crumbItems[] = [
            "@type"=>"ListItem",
            "position"=>$pos++,
            "name"=>$b['name'],
            "item"=>$url
        ];
    }
    
    $breadcrumbSchema = [
        "@context"=>"https://schema.org/",
        "@type"=>"BreadcrumbList",
        "itemListElement"=>$crumbItems
    ];
    
    // FAQ Schema (if FAQs exist)
    $faqSchema = null;
    if (!empty($faqs) && is_array($faqs)) {
        $faqItems = [];
        
        foreach($faqs as $faq) {
            $faqItems[] = [
                "@type"=>"Question",
                "name"=>$faq['question'] ?? $faq['name'] ?? '',
                "acceptedAnswer"=>[
                    "@type"=>"Answer",
                    "text"=>$faq['answer'] ?? $faq['text'] ?? ''
                ]
            ];
        }
        
        if (!empty($faqItems)) {
            $faqSchema = [
                "@context"=>"https://schema.org/",
                "@type"=>"FAQPage",
                "mainEntity"=>$faqItems
            ];
        }
    }
@endphp

<script type="application/ld+json">
{!! json_encode($webpage, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>

<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>

@if($faqSchema)
<script type="application/ld+json">
{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>
@endif
@endpush

@section('content')
<style>
    .coupon-card {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: .25rem;
        padding: 14px;
        margin-bottom: 5px;
    }
    .coupon-card .store-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 80px;
        width: 80px;
        border: 1px dashed #ff4700;
        border-radius: .25rem;
        padding: .5rem;
    }
    .coupon-card .store-logo img {
        max-width: 100%;
        max-height: 100%;
    }
    .coupon-card .offer-details h3 {
        font-size: 16px;
        font-weight: 600;
        margin: 5px 0;
    }
    @media (max-width: 767.98px) {
        .coupon-card .offer-details h3 { font-size: 14px; }
        .coupon-card .offer-details { text-align: center; }
    }
    .coupon-card .offer-details .offer-meta {
        font-size: .875rem;
        color: #6c757d;
    }
    .coupon-card .offer-action .reveal-code-button {
    border: 2px dashed #002b61;
    border-radius: 0px;
    padding: 7px 20px;
    background-color: #002b61;
    color: #fff;
    font-weight: 600;
    position: relative;
    clip-path: polygon(0 0, 100% 0, 91% 100%, 0 100%);
}
    .coupon-card .offer-action .deal-button {
      border: 2px dashed #002b61;
    border-radius: 0px;
    padding: 7px 20px;
    background-color: #002b61;
    color: #fff;
    font-weight: 600;
    position: relative;
        font-weight: 600;
    }
    .coupon-card .offer-action .deal-button:hover,
    .coupon-card .offer-action .reveal-code-button:hover {
        background: #ee7b42;
        color: #000;
        border: 2px dashed #002b61;
    }
    .sidebar .widget {
        margin-bottom: 2rem;
    }
    .sidebar .widget-title {
        font-size: 1.125rem;
        font-weight: 600;
        border-bottom: 1px solid #F44336;
        padding-bottom: .5rem;
        margin-bottom: 1rem;
    }

    .col-md-3.button-for-deal {
    display: flex;
    align-content: center;
    align-items: center;
    justify-content: flex-end;
}
    .sidebar .list-group-item {
        border: none;
    }
    .buttons-tab {
        cursor: pointer;
        padding: 7px 20px;
        border-radius: 5px;
        background-color: #f1f1f1;
        border: 1px solid #ddd;
    }
    .active-tab-style {
        background-color: #002b61;
        color: white;
    }
    .coupon-modal .modal-content {
        border-radius: 0.5rem;
        border: 2px dashed #ff4700;
        overflow: hidden;
    }
    .discount-banner {
        background: linear-gradient(135deg, #002b61, #004e92);
        position: relative;
        padding-top: 1rem;
    }
    .discount-banner h3 {
        color: #ff4700;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .coupon-modal .modal-header {
        border-bottom: none;
        padding: 0.5rem 3.5rem 0.5rem 1rem; /* Extra padding on right to accommodate close button */
        position: relative;
    }
    .coupon-modal .store-logo-modal {
        /*
        position: absolute;
        top: 10px; /* Adjusted positioning */
        right: 60px; /* Positioned to the left of the close button */
        display: flex;
        align-items: center;
        justify-content: center;
        height: 60px;
        width: 100px;
        border: 1px dashed #ff4700;
        border-radius: .25rem;
        padding: .5rem;
        background-color: white;
        z-index: 10; /* Ensure logo appears above other elements */
        box-sizing: border-box; /* Include padding in width calculation */
        */
    }
    .coupon-modal .store-logo-modal img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain; /* Ensure image fits properly without distortion */
    }
    .modal-body-content {
        padding-top: 0.5rem;
    }
    .coupon-modal .coupon-code-container {
        border: 2px dashed #ccc;
        border-radius: 0.25rem;
        margin-bottom: 1rem;
    }
    .coupon-modal .coupon-code {
        font-size: 1.5rem;
        font-weight: bold;
        color: #002b61;
        word-break: break-all; /* Handle long codes nicely */
    }
    .coupon-modal .copy-code-btn {
        background-color: #002b61;
        color: #fff;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.25rem;
        cursor: pointer;
        flex-shrink: 0; /* Prevent button from shrinking */
        height: fit-content;
        align-self: center; /* Align button vertically with code */
    }
    .coupon-modal .go-to-website {
        text-align: center;
        margin: 1rem 0;
    }
    .coupon-modal .go-to-website a {
        color: #ff4700;
        text-decoration: none;
        font-weight: 600;
    }
    .coupon-modal .verification-status {
        text-align: center;
        font-size: 0.875rem;
        color: #28a745;
        margin-bottom: 1rem;
    }
    .coupon-modal .detail-section {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.25rem;
        margin-top: 1rem;
    }
    .coupon-modal .detail-section h5 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .coupon-modal .detail-section ul {
        padding-left: 1.2rem;
        margin-bottom: 0;
        list-style-type: disc; /* Ensure list bullets are visible */
    }

    @media (max-width: 767.98px) {
        .coupon-card .row > [class*="col-"] {
            margin-bottom: 1rem;
        }
        .coupon-card .store-logo {
            margin: 0 auto;
        }
        .col-md-3.button-for-deal {
            justify-content: center;
        }
        .coupon-card .offer-action {
            text-align: center;
        }
        .buttons-tab {
            flex-grow: 1;
            text-align: center;
        }
        .d-flex.gap-3.align-items-center.mb-4 { flex-wrap: wrap; }
        .modal-header { 
            flex-wrap: wrap;
            padding: 0.5rem 1rem;
            justify-content: center !important;
            text-align: center;
        }
        .coupon-modal .modal-title {
            order: 2;
            width: 100%;
            font-size: 1rem;
            margin-top: 0.5rem;
        }
        .coupon-modal .store-logo-modal {
            order: 1;
        }
        .coupon-modal .btn-close {
            order: 3;
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .coupon-modal .modal-dialog {
            width: 90%;
            margin: 1.75rem auto;
        }
        .discount-banner h3 { font-size: 1.3rem !important; }
        .discount-banner p { font-size: 0.9rem !important; }
        .coupon-modal .coupon-code-container .d-flex { flex-direction: column; }
        .coupon-modal .copy-code-btn { width: 100%; margin-top: 0.5rem; margin-left: 0 !important; }
        .modal-body-content { padding: 0 1rem 1rem 1rem !important; }
        .coupon-modal .go-to-website a { padding: 0.75rem !important; font-size: 0.9rem; }
    }
    .coupon-modal .coupon-code {
        font-size: 1.2rem;
        letter-spacing: 1px;
    }
</style>

<div class="container py-3 px-md-2 px-3">
    <div class="row">
        <div class="col-lg-3 col-md-4 my-auto">
            <div class="d-block cursor-pointer">
                @if($store->logo)
                    <img src="{{ asset('uploads/' . $store->logo) }}" 
                         
                         style="height: 160px; object-fit: contain; border:1px dashed #ff4700; width:100%; border-radius:10px;" 
                         alt="{{ $store->title }} Logo">
                @else
                    <div class="mx-auto border border-secondary border-dashed rounded-md w-100 d-flex align-items-center justify-content-center" 
                         style="height: 160px; object-fit: contain; border:1px dashed #ff4700; width:100%; border-radius:10px;">
                        <span class="text-muted">{{ $store->title }}</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-9 col-md-8 my-auto text-center text-md-start">
            <div class="mt-3 mt-md-0">
                <div class="">
                    <h1 class="h2 mb-1" style="font-size:24px;font-weight:600;">{{ $store->title_h1 }} {{ date('Y') }}</h1>
                    <h2 class="mb-2" style="font-size:16px;font-weight:500;color:#ff4700;">
                        Best <span class="fw-semibold">{{ $offers->count() }}</span> {{ $store->title }} Offers & Promo Code last validated on <span>{{ date('F, Y') }}</span>
                    </h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-3 px-md-2 px-3">
    <div class="row">
        <div class="col-lg-3 col-md-4 sidebar">
            <div class="widget">
                <div class="text-center mb-4">
                       <p> We may earn a commission if you make a purchase through our links</p>
                    <a href="/terms-of-use/" class="text-decoration-none">
                        <div class="comission-btn" >
                            We May Earn a Commission
                        </div>
                    </a>
                </div>
            </div>

            <div class="widget">
                <h3 class="widget-title">Why Trust Us</h3>
                <p>
                    {{ config('app.name', 'PocketThrift') }} has a merchandising team sourcing and verifying the best {{ $store->title }} coupons, promo codes, and deals so you can save money and time while shopping. Our deal hunters are constantly researching the market in real time to bring you the latest savings insights, the best stores to shop, and top product recommendations. No matter where you shop, you can trust {{ config('app.name', 'PocketThrift') }} to deliver reliable, vetted coupons, promo codes, and exclusive sales. Our team last verified offers for {{ $store->title }} on {{ date('F, Y') }}
                </p>
                <div class="comission-btn">
                    Learn How We Verify Deals
                </div>
            </div>

            <div class="widget widget-coupon">
                <h3 class="widget-title ">Coupon Info</h3>
                <ul>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Number of Deal:
                        <span class="badge">{{ $offers->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Coupons:
                        <span class="badge">{{ $offers->where('type', 'Code')->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Offers:
                        <span class="badge">{{ $offers->where('type', '!=', 'Code')->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Verified:
                        <span class="badge">{{ $offers->where('verified', '!=', 'active')->count() }}</span>
                    </li>
                </ul>
            </div>

            <div class="widget">
                <h3 class="widget-title">Last Updated</h3>
                <div class="mt-3">
                    <p class="text-sm border rounded-md bg-light p-2">
                        <span class="fw-medium text-secondary">{{ $store->title }}</span> Coupons and Promo Codes last updated on <span>{{ date('F, Y') }}</span>
                    </p>
                </div>
            </div>

            <div class="widget widget-content">
                <h3 class="widget-title">Table of Content</h3>
                <ul>
                    <li>
                        <a href="#how-to-use">
                            How to Use {{ $store->title }} Promo Code
                        </a>
                    </li>
                    <li>
                        <a href="#reason-not-working">
                            Why is my {{ $store->title }} Promo Code Not Working?
                        </a>
                    </li>
                </ul>
            </div>

            <div class="widget more-stores-widget">
                <h3 class="widget-title">More Stores</h3>
                <ul>
                    @php
                    // Fetch trending stores from the current region, ordered by the number of active offers
                    $otherStores = \App\Models\Store::where('active', true)
                        ->byRegionCodes([$currentRegion]) // Filter by current region
                        ->withCount('offers') // Count related offers
                        ->orderByDesc('offers_count') // Order by offer count
                        ->take(10) // Limit to top 10
                        ->get(); // Execute the query
                @endphp
                    
                    @foreach($otherStores as $otherStore)
                        <li >
                            <a href="{{ route('store.detail', ltrim($otherStore->url_slug, '/')) }}" 
                               class="text-sm text-decoration-none fw-medium">
                               {{ $otherStore->title }} 
                               <span class="text-xs">({{ $otherStore->offers()->count() }})</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

           <div class="widget">
                <h3 class="widget-title">Trending Stores</h3>
                @php
                    // Fetch trending stores from the current region, ordered by the number of active offers
                    $trendingStores = \App\Models\Store::where('active', true)
                        ->byRegionCodes([$currentRegion]) // Filter by current region
                        ->withCount('offers') // Count related offers
                        ->orderByDesc('offers_count') // Order by offer count
                        ->take(10) // Limit to top 10
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

        <div class="col-lg-9 col-md-8 order-md-1 order-2">
            <div class="d-flex gap-3 align-items-center mb-4">
                <div class="buttons-tab active-tab-style"  data-filter="all">
                    All ({{ $offers->count() }})
                </div>
                <div class="buttons-tab " data-filter="codes">
                    Codes ({{ $offers->where('type', 'Code')->count() }})
                </div>
                <div class="buttons-tab" data-filter="deals">
                    Deals ({{ $offers->where('type', '!=', 'Code')->count() }})
                </div>
            </div>

            @foreach($offers as $offer)
            <div class="coupon-card offer-item" data-type="{{ $offer->type }}">
                <div class="row">
                    <div class="col-md-2">
                        <div class="store-logo mx-auto mx-md-0">
                            @if($store->logo)
                                <img src="{{ asset('uploads/' . $store->logo) }}" alt="{{ $store->title }} Logo">
                            @else
                                <span class="text-muted">{{ substr($store->title, 0, 1) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="offer-details">
                            <div class="offer-meta d-flex align-items-center justify-content-center justify-content-md-start">
                                <span style="font-weight: 600; color:#000;">{{ $offer->type }}</span>                                
                                @if(in_array(trim(strtolower($offer->verified)), ['active', '1', 'true']))
                                    <span class="mx-2">|</span> <span style="font-weight: 600; padding: 2px 6px;color: #28a745; border:1px dashed #28a745">Verified</span>
                                @endif
                                @if(in_array(trim(strtolower($offer->free_delivery)), ['1', 'true', 'active']))
                                    <span class="mx-2">|</span> <span style="font-weight: 600; padding: 2px 6px;color: #007bff; border:1px dashed #007bff">Free Delivery</span>
                                @endif
                            </div>
                            <h3>{{ $offer->title }}</h3>
                            <span style="font-size:12px;">Recently Updated</span>
                        </div>
                    </div>
                    <div class="col-md-3 button-for-deal">
                        <div class="offer-action text-center text-md-end">
                            @if($offer->discount && trim($offer->discount) !== '')
                                @if (is_numeric($offer->discount))
                                    <p class="mb-2 fw-bold" style="color: #ee7b42; font-size: 1.2rem;">
                                        @if($offer->type != 'Code') Up To @else Get @endif
                                        {{ rtrim(rtrim(number_format(abs($offer->discount), 2), '0'), '.') }}%
                                    </p>
                                @elseif(stripos(strtolower($offer->discount), 'free shipping') !== false || stripos(strtolower($offer->discount), 'kostenloser ver') !== false || stripos(strtolower($offer->discount), 'spedizione grat') !== false)
                                    <p class="mb-2 fw-bold" style="color: #ee7b42; font-size: 1.2rem;">Free Shipping</p>
                                @elseif(stripos(strtolower($offer->discount), 'free delivery') !== false)
                                    <p class="mb-2 fw-bold" style="color: #ee7b42; font-size: 1.2rem;">Free Delivery</p>
                                @elseif(strtolower(trim($offer->discount)) == 'sign up')
                                    <p class="mb-2 fw-bold" style="color: #ee7b42; font-size: 1.2rem;">Sign Up</p>
                                @elseif(stripos(strtolower($offer->discount), 'sign up') !== false || stripos(strtolower($offer->discount), 'meldung') !== false || stripos(strtolower($offer->discount), 'iscrizione') !== false || stripos(strtolower($offer->discount), 'melden sie sich') !== false)
                                    <p class="mb-2 fw-bold" style="color: #ee7b42; font-size: 1.2rem;">Sign Up</p>
                                @elseif(stripos(strtolower($offer->discount), 'up to') !== false || preg_match('/^(-?)\d+%/', $offer->discount) || stripos($offer->discount, 'Bis zu') !== false || stripos($offer->discount, 'Fino al') !== false)
                                    <p class="mb-2 fw-bold" style="color: #ee7b42; font-size: 1.2rem;">{{ $offer->discount }}</p>
                                @else
                                    <p class="mb-2 fw-bold" style="color: #ee7b42; font-size: 1.2rem;">{{ $offer->discount }}</p>
                                @endif
                            @else
                                <p class="mb-2 fw-bold" style="color: #ee7b42; font-size: 1.2rem;">Special Offer</p>
                            @endif
                            @if($offer->type == 'Code')
                                <button class="reveal-code-button" data-bs-toggle="modal" data-bs-target="#offerModal" data-offer-id="{{ $offer->id }}" data-offer-code="{{ $offer->button_text }}">REVEAL CODE</button>
                            @else
                                <button class="deal-button" data-bs-toggle="modal" data-bs-target="#offerModal" data-offer-id="{{ $offer->id }}" data-offer-type="deal">GET DEAL</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            
            <div id="how-to-use" class="mt-4">
                <h2 class="h5 fw-semibold text-white bg-primary rounded-md py-2 px-3" style="background-color: #002b61 !important;">
                    How to Use {{ $store->title }} Promo Code
                </h2>
                <div class="border-end border-start border-bottom rounded-md p-3">
                    <p class="mb-3">Follow the instructions below and get the best {{ $store->title }} Promo Code</p>
                    <div class="mb-5">
                        <div class="h6 fw-semibold mb-2">Step 1: Select and Reveal Your Coupon Code</div>
                        <p class="text-base">Choose one of the available codes from the list above. To redeem it, click on 'Reveal Code' after selecting your preferred {{ $store->title }} Coupon Code</p>
                        <img alt="Step 1" class="img-fluid" src="{{ asset('uploads/store-step-1.jpg') }}" title="Step 1">
                    </div>
                    <div class="border-bottom mb-4"></div>
                    <div class="mb-5">
                        <div class="h6 fw-semibold mb-2">Step 2: Copy Your Promo Code</div>
                        <p class="text-base">Once you have clicked 'Reveal Code', copy the promo code that appears. This code is necessary for reducing part of the cost of your total order.</p>
                        <img alt="Step 1" class="img-fluid" src="{{ asset('uploads/store-step-2.jpg') }}" title="Step 1">
                    </div>
                    <div class="mb-5">
                        <div class="h6 fw-semibold mb-2">Step 3: Add Items to Your Basket</div>
                        <p class="text-base">You will be redirected to the {{ $store->title }} website. Add the items you wish to purchase to your shopping basket and proceed to complete your order.</p>
                    </div>
                </div>
            </div>

            <div id="reason-not-working" class="mt-4">
                <h2 class="h5 fw-semibold text-white bg-primary rounded-md py-2 px-3" style="background-color: #002b61 !important;">
                    Why is my <span>{{ $store->title }} Promo Code</span> Not Working?
                </h2>
                <div class="border-end border-start border-bottom rounded-md p-3">
                    <p class="text-base">A {{ $store->title }} Coupon Code may not work for several reasons:</p>
                    <ul class="list-unstyled ps-3 my-3">
                        <li class="mb-1">The code could have expired</li>
                        <li>There are some items in your cart for which the code does not apply, such as sale items.</li>
                        <li>Perhaps you have not bought enough to hit the limit necessary for its activation.</li>
                        <li>If you experience any difficulties with your {{ $store->title }} Promo Code On {{ config('app.name', 'PocketThrift') }}, promptly get in touch, and we will solve this problem.</li>
                    </ul>
                </div>
            </div>

            <div class="bg-cover bg-no-repeat py-5 mt-4 rounded-lg" style="background-image: url('{{ asset('images/banner_02.jpg') }}');">
                <div class="text-center relative z-10">
                    <div class="h4 fw-bold text-white px-3">22 Jewelry and Watches Coupons & Promo Codes</div>
                    <a href="/coupons/apps-and-software/" target="_blank" class="text-decoration-none">
                        <div class="px-3 py-2 rounded-sm border border-white bg-transparent text-white text-uppercase text-sm mt-3 d-inline-block">
                            View All Offers
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

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
                    <a href="{{ route('stores') }}" class="text-decoration-none">Store</a>
                    <svg fill="rgba(0,0,0,1)" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z"></path>
                    </svg>
                </li>
                <li class="d-flex align-items-center">
                    <span class="fw-semibold">{{ $store->title }}</span>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Modal for Reveal Code -->
<div class="modal fade coupon-modal" id="offerModal" tabindex="-1" aria-labelledby="offerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="position-relative">
                    <!-- Discount banner at the top -->
                    <div class="discount-banner bg-primary text-white text-center py-3">
                        <h3 id="discountValue" class="mb-0 fw-bold" style="font-size: 2rem; color: #ee7b42 !important;">15%</h3>
                        <p id="discountText" class="mb-0" style="font-size: 1rem;">OFF</p>
                    </div>
                    
                    <!-- Modal header with store logo on right -->
                    <div class="modal-header border-bottom-0 pb-1 justify-content-between">
                        <h5 class="modal-title fw-bold" id="offerModalLabel" style="font-size: 1.2rem;"></h5>
                        <button type="button" class="btn-close m-2" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="store-logo-modal ms-auto">
                            <img id="modalStoreLogo" src="" alt="Store Logo" style="max-height: 50px; max-width: 80px;">
                        </div>
                    </div>
                    
                    <!-- Offer details and code section -->
                    <div class="modal-body-content px-4 pb-4">
                        <div class="coupon-code-container mb-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <span id="couponCode" class="coupon-code flex-grow-1 text-center p-3 bg-light border border-secondary rounded fw-bold">LOADING...</span>
                                <button id="copyCodeBtn" class="ms-2 copy-code-btn btn btn-primary">COPY</button>
                            </div>
                        </div>
                        
                        <!-- Go to store button -->
                        <div class="go-to-website mb-3">
                            <a id="affiliateLink" href="#" target="_blank" class="btn btn-warning w-100 py-3 fw-bold text-dark">
                                GO TO {{ $store->title }} STORE &rarr;
                            </a>
                        </div>
                        
                        <div class="verification-status mb-3">
                            <span id="verifiedStatus" class="d-block text-center"></span>
                        </div>
                        
                        <div class="detail-section mt-3">
                            <h5 class="fw-bold">Details</h5>

                            <ul id="offerDescription"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter logic
    const filterButtons = document.querySelectorAll('[data-filter]');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('[data-filter]').forEach(btn => btn.classList.remove('active-tab-style'));
            this.classList.add('active-tab-style');
            const filter = this.getAttribute('data-filter');
            document.querySelectorAll('.offer-item').forEach(item => {
                if (filter === 'all' || (filter === 'codes' && item.getAttribute('data-type') === 'Code') || (filter === 'deals' && item.getAttribute('data-type') !== 'Code')) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Modal logic
    var offerModal = document.getElementById('offerModal');
    offerModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var offerId = button.getAttribute('data-offer-id');
        var offerType = button.getAttribute('data-offer-type'); // 'deal' for Get Deal, undefined for Reveal Code

        // Fetch offer details via AJAX
        fetch(`/api/offer/${offerId}`)
            .then(response => response.json())
            .then(data => {
                var offer = data.offer;
                var store = data.store;

                // Set discount value at the top
                var discountElement = offerModal.querySelector('#discountValue');
                var discountTextElement = offerModal.querySelector('#discountText');
                
                if (offer.discount && offer.discount.trim() !== '') {
                    var discountLower = offer.discount.toLowerCase().trim();
                    
                    if (isNumeric(offer.discount)) {
                        var discountValue = parseFloat(offer.discount);
                        discountElement.textContent = Math.abs(discountValue).toString();
                        discountTextElement.textContent = '% OFF';
                    } else if (discountLower.includes('free shipping') || discountLower.includes('kostenloser ver') || discountLower.includes('spedizione grat')) {
                        discountElement.textContent = 'FREE';
                        discountTextElement.textContent = 'SHIPPING';
                    } else if (discountLower.includes('free delivery')) {
                        discountElement.textContent = 'FREE';
                        discountTextElement.textContent = 'DELIVERY';
                    } else if (discountLower === 'sign up' || discountLower.includes('sign up') || discountLower.includes('meldung') || discountLower.includes('iscrizione')) {
                        discountElement.textContent = 'SIGN';
                        discountTextElement.textContent = 'UP';
                    } else if (discountLower.includes('up to') || discountLower.includes('bis zu') || discountLower.includes('fino al')) {
                        discountElement.textContent = offer.discount.toUpperCase();
                        discountTextElement.textContent = '';
                    } else {
                        discountElement.textContent = offer.discount.toUpperCase();
                        discountTextElement.textContent = '';
                    }
                } else {
                    discountElement.textContent = 'SPECIAL';
                    discountTextElement.textContent = 'OFFER';
                }

                // Set offer title
                offerModal.querySelector('#offerModalLabel').textContent = offer.title;
                
                // Set store logo
                var logoPath = store.logo || store.store_icon || '';
                if (logoPath) {
                    offerModal.querySelector('#modalStoreLogo').src = '{{ asset('uploads/') }}/' + logoPath;
                } else {
                    // Show first letter of store name if no logo
                    offerModal.querySelector('#modalStoreLogo').src = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="60" viewBox="0 0 100 60"><rect width="100" height="60" fill="%23002b61"/><text x="50" y="35" font-family="Arial" font-size="20" fill="white" text-anchor="middle">' + (store.title ? store.title.charAt(0) : 'S') + '</text></svg>';
                }

                // Set coupon code based on offer type
                var couponCodeElement = offerModal.querySelector('#couponCode');
                var couponCodeContainer = offerModal.querySelector('.coupon-code-container');
                
                if (offerType === 'deal') {
                    // For "Get Deal" offers, hide the coupon code section
                    couponCodeContainer.style.display = 'none';
                } else {
                    // For "Reveal Code" offers, show the coupon code section
                    couponCodeContainer.style.display = 'block';
                    
                    if (offer.button_text) {
                        couponCodeElement.textContent = offer.button_text;
                    } else if (offer.code) {
                        couponCodeElement.textContent = offer.code;
                    } else {
                        couponCodeElement.textContent = 'NO CODE NEEDED';
                    }
                }

                // Set affiliate link - prioritize affiliate_links from store if available
                var affiliateLinkElement = offerModal.querySelector('#affiliateLink');
                if (store.affiliate_links && store.affiliate_links.trim() !== '') {
                    affiliateLinkElement.href = store.affiliate_links;
                } else if (offer.button_link) {
                    affiliateLinkElement.href = offer.button_link;
                } else {
                    affiliateLinkElement.href = '#';
                    affiliateLinkElement.textContent = 'LINK NOT AVAILABLE';
                }

                // Set verification status
                var verifiedStatus = offerModal.querySelector('#verifiedStatus');
                if (offer.verified === 'active') {
                    verifiedStatus.innerHTML = 'Verified <i class="fas fa-check-circle text-success"></i> | Valid Till: N/A';
                } else {
                    verifiedStatus.innerHTML = 'Not Verified | Valid Till: N/A';
                }

                // Set offer description
                var offerDescription = offerModal.querySelector('#offerDescription');
                offerDescription.innerHTML = ''; // Clear previous details
                if (offer.description) {
                    // Assuming description is a simple list separated by newlines
                    var descriptionItems = offer.description.split('\n').filter(item => item.trim() !== '');
                    descriptionItems.forEach(function(item) {
                        if (item.trim()) {
                            var li = document.createElement('li');
                            li.textContent = item.trim();
                            offerDescription.appendChild(li);
                        }
                    });
                } else if (offer.terms_and_conditions) {
                    // Fallback to terms and conditions if no description
                    var termsItems = offer.terms_and_conditions.split('\n').filter(item => item.trim() !== '');
                    termsItems.forEach(function(item) {
                        if (item.trim()) {
                            var li = document.createElement('li');
                            li.textContent = item.trim();
                            offerDescription.appendChild(li);
                        }
                    });
                } else {
                    // Create a default message if no description or terms are available
                    var defaultItems = [];
                    if (isNumeric(offer.discount)) {
                        defaultItems.push(`Get ${Math.abs(parseFloat(offer.discount))}% off on your order.`);
                    }
                    defaultItems.push('Avail offer by using the given code.');
                    defaultItems.forEach(function(item) {
                        var li = document.createElement('li');
                        li.textContent = item;
                        offerDescription.appendChild(li);
                    });
                }

                // Set up copy button functionality (only show for Reveal Code offers)
                var copyBtn = offerModal.querySelector('#copyCodeBtn');
                if (offerType === 'deal') {
                    copyBtn.style.display = 'none';
                } else {
                    copyBtn.style.display = 'block';
                    copyBtn.onclick = function() {
                        var codeToCopy = couponCodeElement.textContent;
                        if (codeToCopy !== 'NO CODE NEEDED') {
                            navigator.clipboard.writeText(codeToCopy).then(function() {
                                // Change button text temporarily
                                var originalText = copyBtn.textContent;
                                copyBtn.textContent = 'COPIED!';
                                setTimeout(function() {
                                    copyBtn.textContent = originalText;
                                }, 2000);
                            }).catch(function(err) {
                                console.error('Could not copy text: ', err);
                            });
                        } else {
                            console.log('No code to copy - deal applied automatically');
                        }
                    }
                }

                // Auto-open affiliate link in new tab with a 500ms delay for both offer types
                setTimeout(function() {
                    var affiliateLink = offerModal.querySelector('#affiliateLink');
                    if (affiliateLink && affiliateLink.href && affiliateLink.href !== '#' && !affiliateLink.href.includes('LINK NOT AVAILABLE')) {
                        // Auto-open for both "Get Deal" and "Reveal Code" offers
                        window.open(affiliateLink.href, '_blank');
                    }
                }, 500);

            })
            .catch(error => {
                console.error('Error fetching offer data:', error);
                // Set default values if fetch fails
                offerModal.querySelector('#discountValue').textContent = 'SPECIAL';
                offerModal.querySelector('#discountText').textContent = 'DEAL';
                offerModal.querySelector('#couponCode').textContent = 'ERROR LOADING';
                offerModal.querySelector('#verifiedStatus').innerHTML = 'Error loading offer details';
            });
    });
    
    // Helper function to check if value is numeric
    function isNumeric(value) {
        return !isNaN(parseFloat(value)) && isFinite(value);
    }
    
    // Helper function to convert to lowercase safely
    function toLowerCase(value) {
        if (typeof value === 'string' || value instanceof String) {
            return value.toLowerCase();
        }
        return '';
    }
});
</script>

@endsection
