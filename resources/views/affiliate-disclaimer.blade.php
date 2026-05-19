@extends('layouts.app')
@section('title', $page->seo_title)
@section('meta_description', $page->meta_description)
@php
    $regionService = app(\App\Services\RegionService::class);
    $currentRegion = $regionService->getCurrentRegion();

    // Safe defaults
    $country = $currentRegion->country ?? 'USA';
    $regionPrefix = $currentRegion->code ?? 'us';

    $baseUrl = url($regionPrefix . '/affiliate-disclaimer');
@endphp
    @php
        // Determine region code & name
        $regionCode = $region->code ?? ($regionCode ?? 'us');
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
            'at' => 'Austria',
        ];
        $regionName = $regions[strtolower($regionCode)] ?? strtoupper($regionCode);

        // Base URL & site URL
        $baseUrl = rtrim(config('app.url', 'https://pocketthrift.com'), '/');
        $siteUrl = strtolower($regionCode) === 'us' ? $baseUrl : $baseUrl . '/' . strtolower($regionCode);

        // Full URL
        $path = $meta['path'] ?? request()->getPathInfo();
        $path = $path === '/' || $path === '' ? '/' : rtrim($path, '/') . '/';
        $fullUrl = rtrim($siteUrl, '/') . $path;

        // Dynamic Content Replacement
        $pageContent = $page->content_body;

        // Replace region placeholders
        $replacements = [
            '$regionName' => $regionName,
            '$regionCode' => $regionCode,
            'in the $regionName' => 'in the ' . $regionName,
            'in $regionName' => 'in ' . $regionName,
        ];

        foreach ($replacements as $key => $value) {
            $pageContent = str_replace($key, $value, $pageContent);
        }

        // Breadcrumbs
        $breadcrumbs = $meta['breadcrumbs'] ?? [['name' => 'Home', 'url' => '/'], ['name' => $page->title, 'url' => $path]];

        // Prepare WebPage schema
        $webpage = [
            '@context' => 'https://schema.org/',
            '@type' => 'WebPage',
            'name' => $page->seo_title,
            'description' => $page->meta_description,
            'url' => $fullUrl,
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'PocketThrift',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $siteUrl . '/images/og-image.webp',
                ],
            ],
        ];

        // Prepare BreadcrumbList schema
        $crumbItems = [];
        $pos = 1;
        foreach ($breadcrumbs as $b) {
            $url = strpos($b['url'], 'http') === 0 ? $b['url'] : rtrim($siteUrl, '/') . '/' . ltrim($b['url'], '/');
            $crumbItems[] = [
                '@type' => 'ListItem',
                'position' => $pos++,
                'name' => $b['name'],
                'item' => $url,
            ];
        }
        $breadcrumbSchema = [
            '@context' => 'https://schema.org/',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $crumbItems,
        ];
    @endphp

    @push('schemas')
    <meta name="robots" content="index, follow">
    
    {{-- OG TAGS --}}
    <meta property="og:title" content="{{ $page->seo_title }}">
    <meta property="og:description" content="{{ $page->meta_description }}">
    <meta property="og:url" content="{{ $baseUrl }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="https://pocketthrift.com/images/og-image.webp">
    <meta property="og:image:type" content="image/webp">
    <meta property="og:image:alt" content="PocketThrift {{ $country }}">
    <meta property="og:site_name" content="PocketThrift">

    {{-- TWITTER TAGS --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@pocketthrift">
    <meta name="twitter:title" content="{{ $page->seo_title }}">
    <meta name="twitter:description" content="{{ $page->meta_description }}">
    <meta name="twitter:image" content="https://pocketthrift.com/images/og-image.webp">

    <script type="application/ld+json">
{!! json_encode($webpage, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>
    <script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('title', 'Affiliate Disclaimer')

@section('content')
    <div class="container">
        <div class="policy-sec">
            <!--<h1 class="page-titles">Affiliate Disclaimer</h1>-->

            {!! $pageContent !!}
        </div>
        @push('breadcrumb')
            <nav aria-label="Breadcrumb">
                <div class="border-top bg-white px-md-2 px-3 mt-4">
                    <div class="container py-3">
                        <ul class="list-unstyled d-flex align-items-center gap-2 mb-0">
                            <li class="d-flex align-items-center">
                                <a href="{{ ($regionCode === 'us' || !$regionCode) ? route('home') : route('region.home', ['region' => $regionCode]) }}" class="text-decoration-none">{{ __('Home') }}</a>
                                <svg fill="rgba(0,0,0,1)" height="18" viewBox="0 0 24 24" width="18"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z">
                                    </path>
                                </svg>
                            </li>
                            <li class="d-flex align-items-center">
                                <span class="fw-semibold">{{ __('Affiliate Disclaimer') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        @endpush
    </div>
@endsection
