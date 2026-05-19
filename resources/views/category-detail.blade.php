@extends('layouts.app')
@section('content')
@section('title', $categoryRecord->seo_title . ', ' . __(date('F')) . ' ' . date('Y'))
@section('meta_description', $categoryRecord->meta_description)

@push('schemas')
    {{-- Open Graph Meta Tags --}}
    <meta property="og:title" content="{{ $categoryRecord->seo_title . ', ' . __(date('F')) . ' ' . date('Y') }}">
    <meta property="og:description" content="{{ $categoryRecord->meta_description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    @if($categoryRecord->logo)
        <meta property="og:image" content="{{ asset('uploads/' . $categoryRecord->logo) }}">
    @else
        <meta property="og:image" content="https://pocketthrift.com/images/og-image.webp">
    @endif
    <meta property="og:image:alt" content="{{ $categoryRecord->title }}">
    <meta property="og:site_name" content="PocketThrift">
    
    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $categoryRecord->seo_title . ', ' . __(date('F')) . ' ' . date('Y') }}">
    <meta name="twitter:description" content="{{ $categoryRecord->meta_description }}">
    @if($categoryRecord->logo)
        <meta name="twitter:image" content="{{ asset('uploads/' . $categoryRecord->logo) }}">
    @else
        <meta name="twitter:image" content="https://pocketthrift.com/images/og-image.webp">
    @endif
    <meta name="twitter:image:alt" content="{{ $categoryRecord->title }}">
@endpush

<style>
    .deal-button:hover, .reveal-code-button:hover{
    background-color: #1a2043;
    transition: all .5s ease-in-out;
}
    .offer-action p{
        color: #cf5103;
    font-size: 16px;
    line-height: 1.2;
    border: 1px dashed;
    width: 100%;
    padding: 5px 6px;
    display: flex;
    border-radius: 5px;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    margin-left: 17px;
    max-width: 120px;
    text-align: center;
}
    .offer-action .offers-text {
        display: block !important;
    }
    .coupon-card {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: .25rem;
        padding: 14px;
        margin-bottom: 5px;
    }

    .detail-section.mt-3{
        display: none !important;
    }
    .coupon-card .store-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 80px;
        width: 80px;
        border: 1px dashed #cf5103;
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

    .container > .row > .col-4 img {
        height: 140px !important;
    }
   
    .coupon-card .offer-details {
        font-size: .875rem;
        color: #6c757d;
    }
    button.coupon-card {
    border: 2px dashed #cf5103;
    border-radius: 0px;
    padding: 7px 20px;
    background-color: #cf5103;
    color: #fff;
    font-weight: 600;
    position: relative;
    clip-path: polygon(0 0, 100% 0, 91% 100%, 0 100%);
}
  button.deal-button {
    border: 2px dashed #fff;
    border-radius: 0px;
    padding: 7px 20px;
    background-color: #cf5103;
    color: #fff;
    font-weight: 600;
    position: relative;
}


button.reveal-code-button {
    border: 2px dashed #cf5103;
    border-radius: 0px;
    padding: 7px 20px;
    background-color: #cf5103;
    color: #fff;
    font-weight: 600;
    position: relative;
    clip-path: polygon(0 0, 100% 0, 91% 100%, 0 100%);
}
    .coupon-card .offer-action .deal-button {
      border: 2px dashed #cf5103;
    border-radius: 0px;
    padding: 7px 20px;
    background-color: #cf5103;
    color: #fff;
    font-weight: 600;
    position: relative;
        font-weight: 600;
    }
    .coupon-card .offer-action .deal-button:hover,
    .coupon-card .offer-action .reveal-code-button:hover {
        background: #ee7b42;
        color: #000;
        border: 2px dashed #cf5103;
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
        padding: 7px 15px;
        border-radius: 5px;
        background-color: #f1f1f1;
        border: 1px solid #ddd;
    }
    .active-tab-style {
        background-color: #cf5103;
        color: white;
    }
    .coupon-modal .modal-content {
        border-radius: 0.5rem;
        border: 2px dashed #cf5103;
        overflow: hidden;
    }
    .coupon-modal .btn-close {
        background-color: #cf5103!important;
        border-radius: 50% !important;
        opacity: 1 !important;
        color: white !important; /* Set the icon color to white */
        padding: 5px !important;
        box-shadow: none !important;
        --bs-btn-close-bg: none;
        --bs-btn-close-color: #fff; /* Ensure the SVG icon color is white */
    }
    .coupon-modal .store-logo-modal img {
        max-height: 50px;
        max-width: 80px;
        border: 1px dashed #cf5103;
        padding: 5px;
    }
    .offer-meta {
    display: flex;
    align-items: center;
}
    .coupon-modal .coupon-code {
        font-size: 1.5rem;
        font-weight: bold;
        color: #cf5103;
        word-break: break-all;
    }
    .coupon-modal .copy-code-btn {
        background-color: #cf5103;
        color: #fff;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.25rem;
        cursor: pointer;
    }
    .coupon-modal .go-to-website a {
        color: #cf5103;
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
        padding-left: 1.4rem;
        margin-bottom: 0;
        list-style-type: disc;
    }
    a.follow-icons {
    font-size: 30px;
}

    p{
        font-size: 14px;
    }

   

    .coupon-card {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: .25rem;
        padding: 14px;
        margin-bottom: 1.5rem;
    }
    .coupon-card .store-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 80px;
        width: 80px;
        border: 1px dashed #cf5103;
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
    
    .coupon-card .offer-meta {
        font-size: 12px;
        font-weight: 600;
    }
     .coupon-card .offer-action .reveal-code-button {
    border: 2px dashed #cf5103;
    border-radius: 0px;
    padding: 7px 20px;
    background-color: #cf5103;
    color: #fff;
    font-weight: 600;
    position: relative;
    clip-path: polygon(0 0, 100% 0, 91% 100%, 0 100%);
}
    .coupon-card .offer-action .deal-button {
      border: 2px dashed #cf5103;
    border-radius: 0px;
    padding: 7px 20px;
    background-color: #cf5103;
    color: #fff;
    font-weight: 600;
    position: relative;
        font-weight: 600;
    }
    
    /* Make both buttons have the same width on desktop */
    .reveal-code-button,
    .deal-button {
        min-width: 150px;
        width: 150px;
    }
    
    /* Hide line breaks on desktop - keep text in one line */
    .reveal-code-button br,
    .deal-button br {
        display: none;
    }
  
    .col-md-3.button-for-deal {
        display: flex;
        align-content: center;
        align-items: center;
        justify-content: flex-end;
    }

    .sidebar .widget {
        margin-bottom: 2rem;
    }
    .sidebar .widget-title {
        font-size: 1.125rem;
        font-weight: 600;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: .5rem;
        margin-bottom: 1rem;
    }
    .sidebar .list-group-item {
        border: none;
        padding: .5rem 0;
    }
    .buttons-tab {
        cursor: pointer;
        padding: 10px 20px;
        border-radius: 5px;
        background-color: #f1f1f1;
        border: 1px solid #ddd;
    }
    .active-tab-style {
        background-color: #cf5103;
        color: white;
    }

.coupon-modal .modal-content {
    border-radius: 0.5rem;
    border: 2px dashed #cf5103;
    overflow: hidden;
}
.coupon-modal .btn-close {
    background-color: #cf5103!important;
    border-radius: 50% !important;
    opacity: 1 !important;
    color: white !important; /* Set the icon color to white */
    padding: 5px !important;
    box-shadow: none !important;
    --bs-btn-close-bg: none;
    --bs-btn-close-color: #fff; /* Ensure the SVG icon color is white */
}
.coupon-modal .store-logo-modal img {
    max-height: 50px;
    max-width: 80px;
    border: 1px dashed #cf5103;
    padding: 5px;
}
.coupon-modal .coupon-code {
    font-size: 1.5rem;
    font-weight: bold;
    color: #cf5103;
    word-break: break-all;
}
.coupon-modal .copy-code-btn {
    background-color: #cf5103;
    color: #fff;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 0.25rem;
    cursor: pointer;
}
.coupon-modal .go-to-website a {
    color: #cf5103;
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
    list-style-type: disc;
}

.offer-store-viewer {
    text-align: center;
    margin-bottom: 0;
    margin-top: 15px;
    border-width: 1px 0px 0px 0px;
    border-style: dashed;
    border-color: #ff7634;
    color: #000;
}
.offer-store-viewer p {
    margin: 0;
    padding-top: 10px;
    font-weight: 500;
}   


@media (max-width: 767.98px) {

    .coupon-card .offer-details h3 {
         font-size: 14px;
     }
     .offer-store-viewer p{
        font-size: 13px;
    font-weight: 500;
     }

     .container > .row > .col-4 img {
        height: 100px !important;
    }
    .coupon-card .offer-details h3 {
    font-size: 14px; 
    }
    .coupon-card .offer-details {
    text-align: center; 
    }

    /* Reduce card padding and margins */
    .coupon-card {
        padding: 10px 8px;
        margin-bottom: 8px;
    }
    
    .coupon-card .row > [class*="col-"] {
        margin-bottom: 0px;
        padding-left: 5px;
        padding-right: 5px;
    }
    
    .resp-h1 {
        font-size: 17px !important;
        text-align: left;
    }

    .resp-h2 {
        font-size: 15px !important;
        text-align: left;
    }

    .coupon-card .offer-details {
        font-size: 10px;
    }
    .coupon-card .store-logo {
        margin: 0 auto;
    }
    
    /* Mobile layout for coupon cards */
    .coupon-card .row {
        display: flex;
        flex-wrap: wrap !important;
        align-items: center;
    }
    
    /* Discount column - left side (1st col) */
    .coupon-card .col-2:first-child, /* specific fix if col-2 is used in HTML */
    .coupon-card .row > div:nth-child(1) {
        flex: 0 0 30% !important;
        max-width: 30% !important;
        padding-right: 5px;
    }
    
    /* Title column - center (2nd col) */
    .coupon-card .col-8, /* specific fix if col-8 is used in HTML */
    .coupon-card .row > div:nth-child(2) {
        flex: 0 0 70% !important;
        max-width: 70% !important;
        padding-left: 5px;
        padding-right: 5px;
    }
    
    /* Button column - bottom (3rd col) */
    .coupon-card .col-2:last-child, /* specific fix if col-2 is used in HTML */
    .coupon-card .row > div:nth-child(3) {
        flex: 0 0 100% !important;
        max-width: 100% !important;
        margin-top: 10px;
        padding-left: 0;
        padding-right: 0;
        text-align: center;
    }
    
    
    .coupon-card .offer-action p {
        font-size: 0.7rem !important;
        margin-bottom: 0 !important;
        line-height: 1.2 !important;
        margin-left: 7px;
        width: -webkit-fill-available;
        height: 60px;
    }
    
    /* Fix icon display in discount box on mobile */
    .coupon-card .offer-action p i {
        font-size: 18px !important;
        margin-bottom: 2px;
    }
    
    .coupon-card .offer-action .offers-text {
        display: inline !important;
        font-size: 15px !important; /* Increased to 15px */
        line-height: 1.1 !important;
    }
    
    .coupon-card .offer-action p {
        font-size: 15px !important;
    }
    
    /* Adjust title size and spacing */
    .coupon-card .offer-details h3,
    .coupon-card h3 {
        font-size: 11px !important;
        margin-top: 5px !important;
        margin-bottom: 3px !important;
        line-height: 1.3 !important;
        margin-right: 28px;
        overflow-wrap: break-word;
    }
    
    /* Reduce meta spacing */
    .coupon-card .offer-meta {
        margin-top: 3px;
    }
    
    /* Position button center bottom */
    .coupon-card .button-deck {
        float: none !important;
        margin-top: 0 !important;
        text-align: center;
        width: 100%;
        display: block;
    }
    
    .coupon-card .offer-action {
        text-align: center !important;
        margin-top: 0;
    }
    
    .col-md-3.button-for-deal {
        justify-content: center;
    }
    
    .buttons-tab {
        flex-grow: 1;
        text-align: center;
        padding: 7px 5px;
        font-size: 13px;
        white-space: nowrap;
    }
    .d-flex.gap-3.align-items-center.mb-4 { 
        flex-wrap: nowrap !important;
        gap: 5px !important;
        overflow-x: auto;
    }
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
    .coupon-modal .store-logo-modal { order: 1; }
    .coupon-modal .btn-close { order: 3; position: absolute; top: 10px; right: 10px; }
    .coupon-modal .modal-dialog { width: 70%; margin: 1.75rem auto; }
    .discount-banner h3 { font-size: 1.3rem !important; }
    .discount-banner p { font-size: 0.9rem !important; }
    .coupon-modal .coupon-code-container .d-flex { flex-direction: column; }
    .coupon-modal .copy-code-btn { width: 100%; margin-top: 0.5rem; margin-left: 0 !important; }
    .modal-body-content { padding: 0 1rem 1rem 1rem !important; }
    .coupon-modal .go-to-website a { padding: 0.75rem !important; font-size: 0.9rem; }
     .coupon-card .reveal-code-button,
    .coupon-card .deal-button {
        margin-right: 4px !important;
        margin-left: 10px !important;
        font-size: 14px;
        width: calc(100% - 20px) !important;
        min-width: unset !important;
        max-width: unset !important;
        display: block;
        padding: 10px 4px;
        line-height: 1.2;
        white-space: normal;
        word-wrap: break-word;
        border-radius: 5px;
    }

    /* Hide line breaks on mobile */
    .coupon-card .reveal-code-button br,
    .coupon-card .deal-button br {
        display: none !important;
    }
    
    /* Offer meta text size */
    .coupon-card .offer-meta span {
        font-size: 12px !important; /* Increased to 12px */
    }
    
    /* Make verified and shipping text fit in one line on mobile */
    .coupon-card .offer-meta {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        gap: 2px;
    }
    
    .coupon-card .offer-meta .mx-2 {
        margin-left: 2px !important;
        margin-right: 2px !important;
    }
    
    .coupon-card .verified-text,
    .coupon-card .shipping-text {
        font-size: 12px !important; /* Increased to 12px */
    }
    
    /* Hide separator pipes on mobile to save space */
    .coupon-card .offer-meta .mx-2 {
        display: none !important;
    }
}

.coupon-modal .coupon-code {
    font-size: 1.4rem;
    letter-spacing: 1px;
}
</style>

<div class="container py-3 px-md-2 px-3">
    <div class="row">
        <div class="col-lg-12 col-md-8 my-auto">
            <div class="">
                <div class="">
                    <h1 class="h2 mb-1" style="font-size:24px;font-weight:600;">{{ $categoryRecord->title }} {{ __('Coupons & Promo Codes') }}, {{ date('Y') }}</h1>
                    <h2 class="mb-2" style="font-size:16px;font-weight:500;color:#cf5103;">
                     <span class="fw-semibold">{{ $categoryRecord->stores->count() }}</span> {{ __('Best Coupons & Offers last validated on') }} <span>- {{ __(date('F')) }}, {{ date('Y') }}</span>
                    </h2>
                    {!! $categoryRecord->content_body !!}

                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-3 px-md-2 px-3">
    <div class="row">
        <div class="col-lg-3 col-md-4 sidebar order-2 order-md-1">
            

            <div class="widget widget-coupon">
                <h3 class="widget-title ">{{ __('Coupon Info') }}</h3>
                <ul>
                    @php
                        $allOffers = $categoryRecord->stores->flatMap(function($store) { return $store->offers; });
                    @endphp
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ __('Number of Deal:') }}
                        <span class="badge">{{ $allOffers->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ __('Coupons:') }}
                        <span class="badge">{{ $allOffers->where('type', 'Code')->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ __('Offers:') }}
                        <span class="badge">{{ $allOffers->where('type', '!=', 'Code')->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ __('Verified:') }}
                        <span class="badge">{{ $allOffers->where('verified', 'active')->count() }}</span>
                    </li>
                </ul>
            </div>


            <div class="widget">
                <h3 class="widget-title">{{ __('Last Updated') }}</h3>
                <div class="mt-3">
                    <p class="text-sm border rounded-md bg-light p-2">
                        <span class="fw-medium text-secondary">{{ $categoryRecord->title }}</span> {{ __('Coupons and Promo Codes last updated on') }} <span>{{ __(date('F')) }}, {{ date('Y') }}</span>
                    </p>
                </div>
            </div>

        

            <div class="widget">
                <h3 class="widget-title">{{ __('Offers From Top Trending Stores') }}</h3>
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
                    <div class="mb-3 trending-store-widget">
                        <a href="{{ ($currentRegion === 'us' || !$currentRegion) ? route('store.detail', ltrim($trendingStore->url_slug, '/')) : route('region.store.detail', ['region' => $currentRegion, 'store' => ltrim($trendingStore->url_slug, '/')]) }}" 
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
                                    <div class="trending-store-title">{{ $trendingStore->title }} {{ __('Coupon Code') }}</div>
                                    <div class="trending-store-data">
                                        {{ $trendingStore->offers->where('type', 'Code')->count() }} 
                                        {{ __('Coupons') }} & {{ $trendingStore->offers->where('type', '!=', 'Code')->count() }} {{ __('Offers') }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-9 col-md-8 order-1 order-md-2">
            <div class="md-2">
            <div class="d-flex gap-3 align-items-center mb-4">
                <div class="buttons-tab active-tab-style"  data-filter="all">
                    {{ __('All') }} ({{ $offers->count() }})
                </div>
                <div class="buttons-tab " data-filter="codes">
                    {{ __('Codes') }} ({{ $offers->where('type', 'Code')->count() }})
                </div>
                <div class="buttons-tab" data-filter="deals">
                    {{ __('Deals') }} ({{ $offers->where('type', '!=', 'Code')->count() }})
                </div>
            </div>

            @forelse($offers as $offer)
            <div class="coupon-card offer-item" data-type="{{ $offer->type }}">
            <div class="row align-items-center">
                {{-- Remove the store logo --}}
                <div class="col-2">
                    <div class="offer-action">
                            @php
                                $isFreeShipping = in_array(trim(strtolower($offer->free_delivery)), ['1', 'true', 'active']);
                                $isSpecialType = in_array(trim(strtolower($offer->type)), ['sale', 'offer']);
                            @endphp

                            @if($isFreeShipping)
                                <p class="mb-2 fw-bold value">
                                    <i class="fa-solid fa-truck" style="font-size: 24px; margin-bottom: 2px;"></i>
                                    <span class="offers-text">
                                        {{ __('FREE SHIPPING') }}
                                    </span>
                                </p>
                            @elseif(!empty($offer->discount))
                                <p class="mb-2 fw-bold value">
                                    <span class="offers-text">
                                        {!! str_ireplace('Get ', 'Get<br>', $offer->discount) !!}
                                    </span>
                                </p>
                            @elseif($isSpecialType)
                                <p class="mb-2 fw-bold value">
                                    <i class="fa-solid fa-tag" style="font-size: 24px; margin-bottom: 2px;"></i>
                                    <span class="offers-text">
                                        {{ (isset($offer->type) && trim(strtolower($offer->type)) == 'sale') ? __('SALE') : __('OFFER') }}
                                    </span>
                                </p>
                            @endif
                    </div>
                    {{-- <div class="store-logo mx-auto">
                        @if($store->logo)
                        <img src="{{ asset('uploads/' . $store->logo) }}" alt="{{ $store->title }} Logo">
                        @else
                        <span class="text-muted">{{ substr($store->title, 0, 1) }}</span>
                        @endif
                    </div> --}}
                </div>
                <div class="col-8">
                    <div class="offer-meta">
                                <span style="font-size:12px;font-weight: 600; color:#000;">{{ __($offer->type) }}</span>
                                @if(in_array(trim(strtolower($offer->verified)), ['active', '1', 'true']))
                                    <span class="mx-2">|</span> <span style="font-weight: 600; font-size:12px; padding: 2px 6px;color: #28a745; border:1px dashed #28a745"><i class="fa-solid fa-check"></i><span class="verified-text"> {{ __('Verified') }}</span></span>
                                @endif

                            </div>
                            <div>
                            <h3 style="font-size: 16px; font-weight: 600; margin-top: 10px;">{{ $offer->store->title }} - {{ $offer->title }}</h3>
                            </div>
                </div>
                <div class="col-2">
                    <div class="offer-details">
                    </div>
                    <div class="button-deck" style="float: right; margin-top: 10px;">
                        @if($offer->type == 'Code')
                            <button class="reveal-code-button" data-bs-toggle="modal" data-bs-target="#offerModal" data-offer-id="{{ $offer->id }}" data-offer-code="{{ $offer->button_text }}">{{ __('REVEAL') }} <br>{{ __('CODE') }}</button>
                        @else
                            <button class="deal-button" data-bs-toggle="modal" data-bs-target="#offerModal" data-offer-id="{{ $offer->id }}" data-offer-type="deal">{{ __('GET') }} <br>{{ __('DEAL') }}</button>
                        @endif
                    </div>
                </div>
                </div>
            <div class="offer-store-viewer">
            <p>{{ __('View all offers of :store_title', ['store_title' => $offer->store->title]) }} 
                <a href="{{ ($currentRegion === 'us' || !$currentRegion) ? route('store.detail', ltrim($offer->store->url_slug, '/')) : route('region.store.detail', ['region' => $currentRegion, 'store' => ltrim($offer->store->url_slug, '/')]) }}" class="text-decoration-none">{{ __('here') }}</a></p>    
            </div>
            </div>
            
                
            @empty
                <p>{{ __('No offers available in this category yet.') }}</p>
            @endforelse
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
                        <a href="{{ ($currentRegion === 'us' || !$currentRegion) ? route('home') : route('region.home', ['region' => $currentRegion]) }}" class="text-decoration-none">{{ __('Home') }}</a>
                        <svg fill="rgba(0,0,0,1)" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z"></path>
                        </svg>
                    </li>
                    <li class="d-flex align-items-center">
                        <a href="{{ ($currentRegion === 'us' || !$currentRegion) ? route('categories') : route('region.categories', ['region' => $currentRegion]) }}" class="text-decoration-none">{{ __('Category') }}</a>
                        <svg fill="rgba(0,0,0,1)" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z"></path>
                        </svg>
                    </li>
                    <li class="d-flex align-items-center">
                        <span class="fw-semibold">{{ $categoryRecord->title }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endpush

<!-- Modal for Reveal Code -->
<div class="modal fade coupon-modal" id="offerModal" tabindex="-1" aria-labelledby="offerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-4">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; top: 10px; right: 10px; background-color: #cf5103; border-radius: 50%; opacity: 1;"></button>
                <div class="text-center">
                    <h3 id="discountValue" class="mb-0 fw-bold" style="font-size: 2rem; color: ##cf5103!important;"></h3>
                    <p id="discountText" class="mb-2" style="font-size: 1rem;"></p>
                    <div class="store-logo-modal mb-3">
                        <img id="modalStoreLogo" src="" alt="Store Logo" style="max-height: 120px; max-width: 120px; border: 1px dashed #cf5103; padding: 5px;">
                    </div>
                    <div class="coupon-code-container mb-3">
                        <div class="d-flex align-items-center justify-content-center">
                            <span id="couponCode" class="coupon-code flex-grow-1 text-center p-3 bg-light border border-secondary rounded fw-bold">LOADING...</span>
                            <button id="copyCodeBtn" class="ms-2 copy-code-btn btn btn-primary">{{ __('Copy Code') }}</button>
                        </div>
                    </div>
                    <div class="go-to-website mb-3">
                        <a id="affiliateLink" href="#" target="_blank" class="fw-bold">
                            Go to Store Website &rarr;
                        </a>
                    </div>
                    <div class="verification-status mb-3">
                        <span id="verifiedStatus" class="d-block text-center"></span>
                    </div>
                    <div class="detail-section mt-3">
                        <h5 class="fw-bold">Detail</h5>
                        <span id="offerDescription" class="list-unstyled text-center"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
                    discountTextElement.textContent = (offer.type && offer.type.toLowerCase().trim() === 'sale') ? 'SALE' : 'OFFER';
                }

                // Set offer title
                offerModal.querySelector('#discountText').textContent = offer.title;
                
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
                        couponCodeElement.textContent = "{{ __('NO CODE NEEDED') }}";
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
                    affiliateLinkElement.textContent = "{{ __('LINK NOT AVAILABLE') }}";
                }

                // Set verification status
                var verifiedStatus = offerModal.querySelector('#verifiedStatus');
                var validTillText = 'N/A';
                
                // Format the end date if it exists
                if (offer.end_date) {
                    var endDate = new Date(offer.end_date);
                    validTillText = endDate.toLocaleDateString();
                }
                
                var isVerified = offer.verified === 'active';
                if (isVerified) {
                    verifiedStatus.innerHTML = "{{ __('Verified') }} <i class=\"fas fa-check-circle text-success\"></i> | {{ __('Valid Till') }}: " + validTillText;
                } else {
                    verifiedStatus.innerHTML = "{{ __('Not Verified') }} | {{ __('Valid Till') }}: " + validTillText;
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
                        defaultItems.push(`{{ __('Get') }} ${Math.abs(parseFloat(offer.discount))}% {{ __('off on your order.') }}`);
                    }
                    defaultItems.push("{{ __('Avail offer by using the given code.') }}");
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
                                copyBtn.textContent = "{{ __('COPIED!') }}";
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

    // Filter functionality for tabs
    document.querySelectorAll('.buttons-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');

            // Remove active class from all tabs
            document.querySelectorAll('.buttons-tab').forEach(t => {
                t.classList.remove('active-tab-style');
            });

            // Add active class to clicked tab
            this.classList.add('active-tab-style');

            // Filter offers based on selected tab
            const offerItems = document.querySelectorAll('.offer-item');
            offerItems.forEach(item => {
                const type = item.getAttribute('data-type');

                if (filter === 'all') {
                    item.style.display = 'block';
                } else if (filter === 'codes') {
                    if (type === 'Code') {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                } else if (filter === 'deals') {
                    if (type !== 'Code') {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                }
            });
        });
    });
});
</script>

@endsection