
@extends('layouts.app')
@php
    $regionService = app(\App\Services\RegionService::class);
    $currentRegion = $regionService->getCurrentRegion();

    // Defaults if nothing found
    $country = $currentRegion->country ?? 'USA';
    $regionPrefix = $currentRegion->code ?? 'us';

    $baseUrl = url($regionPrefix . '/about-us');
@endphp
@push('schemas')
<title>PocketThrift {{ $country }}: Guides, Reviews, Coupons & Insights</title>

<meta name="description"
      content="Save money with PocketThrift {{ $country }}! Discover the latest guides, product reviews, and exclusive coupons for valuable insights and smart shopping decisions!">

<meta name="robots" content="index, follow">

{{-- OG TAGS --}}
<meta property="og:title" content="PocketThrift {{ $country }}: Guides, Reviews, Coupons & Insights">
<meta property="og:description"
      content="Save money with PocketThrift {{ $country }}! Discover the latest guides, product reviews, and exclusive coupons for valuable insights and smart shopping decisions!">
<meta property="og:url" content="{{ $baseUrl }}">
<meta property="og:type" content="website">
<meta property="og:image" content="https://pocketthrift.com/images/og-image.webp">
<meta property="og:image:type" content="image/webp">
<meta property="og:image:alt" content="PocketThrift {{ $country }}">
<meta property="og:site_name" content="PocketThrift">

{{-- TWITTER TAGS --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@pocketthrift">
<meta name="twitter:title" content="PocketThrift {{ $country }}: Guides, Reviews, Coupons & Insights">
<meta name="twitter:description"
      content="Save money with PocketThrift {{ $country }}! Discover the latest guides, product reviews, and exclusive coupons for valuable insights and smart shopping decisions!">
<meta name="twitter:image" content="https://pocketthrift.com/images/og-image.webp">

{{-- CANONICAL --}}
<link rel="canonical" href="{{ $baseUrl }}">
@php
    // Determine region code & name
    $regionCode = $region->code ?? ($regionCode ?? 'us');
    $regions = [
        'us'=>'USA','uk'=>'United Kingdom','au'=>'Australia','ca'=>'Canada',
        'fr'=>'France','de'=>'Germany','it'=>'Italy','nl'=>'Netherlands',
        'pl'=>'Poland','es'=>'Spain','mx'=>'Mexico','ch'=>'Switzerland',
        'lu'=>'Luxembourg','fi'=>'Finland','no'=>'Norway','nz'=>'New Zealand',
        'sg'=>'Singapore','at'=>'Austria'
    ];
    $regionName = $regions[strtolower($regionCode)] ?? strtoupper($regionCode);

    // Base URL & site URL
    $baseUrl = rtrim(config('app.url','https://pocketthrift.com'), '/');
    $siteUrl = (strtolower($regionCode) === 'us') ? $baseUrl : $baseUrl . '/' . strtolower($regionCode);

    // Page title (remove any -region suffix)
    $title = $page->meta_title 
        ?? preg_replace('/-(us|uk|fi|au|ca|fr|de|it|nl|pl|es|mx|ch|lu|no|nz|sg|at)$/i','', $page->name ?? '') 
        ?? 'About Us';

    // Page description
    $description = $page->meta_description 
        ?? 'Learn about PocketThrift’s mission to provide stylish, budget-friendly solutions and make thrift shopping a rewarding experience.';

    // Full URL
    $path = $meta['path'] ?? request()->getPathInfo();
    $path = ($path === '/' || $path === '') ? '/' : rtrim($path, '/') . '/';
    $fullUrl = rtrim($siteUrl, '/') . $path;

    // Breadcrumbs
    $breadcrumbs = $meta['breadcrumbs'] ?? [
        ['name'=>'Home','url'=>'/'],
        ['name'=>$title,'url'=>$path]
    ];

    // Prepare WebPage schema
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
                "url"=>$siteUrl . '/images/og-image.webp'
            ]
        ]
    ];

    // Prepare BreadcrumbList schema
    $crumbItems = [];
    $pos = 1;
    foreach($breadcrumbs as $b){
        $url = (strpos($b['url'],'http') === 0) ? $b['url'] : rtrim($siteUrl,'/').'/'.ltrim($b['url'],'/');
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
@endphp

<script type="application/ld+json">
{!! json_encode($webpage, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>
@endpush


@section('title', 'About Us')


@section('content')
<div class="about-sec">
    <div class="container">

        @if($page)
            @php
                $sectionNames = [
                    'about-content-' . $currentRegion,
                    'about-' . $currentRegion,
                    'content-' . $currentRegion . '-about',
                    'content-' . $currentRegion,
                    $currentRegion . '-about-content',
                    $currentRegion . '-content',
                    'about-content',
                    'content'
                ];
                
                $content = null;

                foreach($sectionNames as $sectionName) {
                    $section = $page->findSection($sectionName);
                    if ($section) {
                        $content = $section;
                        break;
                    }
                }
                
                if (!$content) {
                    foreach($page->sections as $section) {
                        if (str_contains($section->slug, $currentRegion)) {
                            $content = $section->value;
                            break;
                        }
                    }
                }
            @endphp

            @if($content)
                {!! $content !!}
            @else
                <p>About Us content for this region does not have content yet.</p>
            @endif
        @else
            <p>About Us content not available for this region.</p>
        @endif
    </div>

    @push('breadcrumb')
    <nav aria-label="Breadcrumb">
        <div class="border-top bg-white px-md-2 px-3 mt-4">
            <div class="container py-3">
                <ul class="list-unstyled d-flex align-items-center gap-2 mb-0">
                    <li class="d-flex align-items-center">
                        <a href="{{ route('home') }}" class="text-decoration-none">Home</a>
                        <svg fill="rgba(0,0,0,1)" height="18" viewBox="0 0 24 24" width="18">
                            <path d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z"></path>
                        </svg>
                    </li>
                    <li class="d-flex align-items-center">
                        <span class="fw-semibold">About Us</span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endpush

</div>
@endsection
