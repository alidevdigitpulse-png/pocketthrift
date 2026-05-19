@extends('layouts.app')
@section('title', "All Regions")
@section('meta_description', "All Regions")
@php
    $regionService = app(\App\Services\RegionService::class);
    $currentRegion = $regionService->getCurrentRegion();

    $regionPrefix = $currentRegion->code ?? 'us';
    $baseUrl = rtrim(config('app.url','https://pocketthrift.com'), '/');
    $pageUrl = ($regionPrefix === 'us') ? $baseUrl . '/all-regions/' : $baseUrl . '/' . $regionPrefix . '/all-regions/';
@endphp


@push('schemas')

<meta name="robots" content="index, follow">

{{-- OG TAGS --}}
<meta property="og:url" content="{{ $pageUrl }}">
<meta property="og:title" content="All Regions">
<meta property="og:description" content="All Regions">
<meta property="og:image" content="https://pocketthrift.com/images/og-image.webp">
<meta property="og:site_name" content="PocketThrift">

{{-- TWITTER TAGS --}}
<meta name="twitter:title" content="All Regions">
<meta name="twitter:description" content="All Regions">
<meta name="twitter:image" content="https://pocketthrift.com/images/og-image.webp">
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

@section('styles')
<link rel="stylesheet" href="{{ asset('front/css/all-regions.css') }}">
@endsection

@section('content')
<div class=" all-regions-container">
    <div class="all-regions-header pt-4 container">
        <h1>{{ __('All Regions') }}</h1>
        <p>{{ __('Join the PocketThrift experience and access special offers in every region that fits you. If you would like to shop through the website in a language you prefer, then you can do that too as you will find all the local savings offers around. Click here to change language and do smarter shopping now!') }}</p>
    </div>



    <div class="regions-grid pt-4 pb-4" style="background: #f6f6f7;">
        <div class="container">
            <div class="row pt-4">
                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/au.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'au']) }}" class="text-decoration-none">{{__('Australia')}}</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/at.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'at']) }}" class="text-decoration-none">{{__('Austria')}}</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/ca.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'ca']) }}" class="text-decoration-none">{{__('Canada')}}</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/fi.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'fi']) }}" class="text-decoration-none">{{__('Finland')}}</a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row pt-4">

                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/fr.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'fr']) }}" class="text-decoration-none">{{__('France')}}</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/de.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'de']) }}" class="text-decoration-none">{{__('Germany')}}</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/it.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'it']) }}" class="text-decoration-none">{{__('Italy')}}</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/lu.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'lu']) }}" class="text-decoration-none">{{__('Luxembourg')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-4">
                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/mx.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'mx']) }}" class="text-decoration-none">{{__('Mexico')}}</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/nl.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'nl']) }}" class="text-decoration-none">{{__('Netherlands')}}</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/nz.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'nz']) }}" class="text-decoration-none">{{__('New Zealand')}}</a>
                        </div>
                    </div>
                </div>


                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/no.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'no']) }}" class="text-decoration-none">{{__('Norway')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-4">
                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/pl.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'pl']) }}" class="text-decoration-none">{{__('Poland')}}</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/sg.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'sg']) }}" class="text-decoration-none">{{__('Singapore')}}</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/es.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'es']) }}" class="text-decoration-none">{{__('Spain')}}</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/ch.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'ch']) }}" class="text-decoration-none">{{__('Switzerland')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-4">
                <div class="col-sm-3">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/uk.svg">
                        <div class="region-name">
                            <a href="{{ route('region.home', ['region' => 'uk']) }}" class="text-decoration-none">{{__('United Kingdom')}}</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3 ">
                    <div class="region-card">
                        <img src="/uploads/regions-flags/us.svg">
                        <div class="region-name">
                            <a href="{{ route('change.region.us') }}" class="text-decoration-none">{{__('United States')}}</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    
    <div class="container">
        <div class="deals-section pt-5">
            <h2>{{ __('Find Great Deals from the Top Retailers') }}</h2>
            <p>{{ __('With the help of PocketThrift, one can save remarkably owing to the discounts provided by different retailers. If you are on a buying spree for your usual favorite brands or want to try out other stores that you have not visited before, you will appreciate our special collection that helps you shop better. Find the best Flash Sales with PocketThrift, the online shopping app built for you.') }}</p>
        </div>
            <div class="deals-columns row g-3">
                <div class="deal-column col-12 col-md-6 text-center">
                    <img style="width:70%;" class="img-fluid" src="{{ asset('/uploads/all-region-img-1.jpg') }}" alt="Discount Image" >
                    <h3>{{ __('Stop Losing Time Looking for Discounts') }}</h3>
                    <p>{{ __('PocketThrift takes the backache away from you and helps you enjoy your shopping. Instead, we make it simple. Instead of making you click dozens of times to find the best price, PocketThrift shows new and exciting ways to save on purchases every day. This makes looking for great deals even more fun than it used to be.') }}</p>
                </div>
                <div class="deal-column col-12 col-md-6 text-center">
                    <img style="width:70%;" class="img-fluid" src="{{ asset('/uploads/all-region-img-2.jpg') }}" alt="Discount Image" >
                    <h3>{{ __('Benefit from Targeted Discounts with Daily Updates') }}</h3>
                    <p>{{ __('For continued savings from PocketThrift, check for daily discount updates as they come in. The goal remains the same, there will be refreshing of the deals every day so that customers don\'t run out of even the last of offers provided by super stores. If you intend to purchase something, be sure to check PocketThrift first as the rates offered there will be cheaper. Smart shopping starts here with PocketThrift!') }}</p>
                </div>
            </div>
    </div>
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
                    <span class="fw-semibold">{{ __('All Regions') }}</span>
                </li>
            </ul>
        </div>
    </div>
</nav>
@endpush
    </div>
@endsection