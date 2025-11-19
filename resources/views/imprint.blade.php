@extends('layouts.app')
@php
    $regionService = app(\App\Services\RegionService::class);
    $currentRegion = $regionService->getCurrentRegion();

    // Safe defaults
    $country = $currentRegion->country ?? 'USA';
    $regionPrefix = $currentRegion->code ?? 'us';

    $baseUrl = url($regionPrefix . '/imprint');
@endphp
@section('title', 'Imprint')

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
    
    $regionName = $region->name ?? strtoupper($regionCode);
    $baseUrl = rtrim(config('app.url','https://pocketthrift.com'), '/');
    
    // SITE URL ALWAYS REGIONIZED
    $siteUrl = ($regionCode === 'us') ? $baseUrl : $baseUrl . '/' . $regionCode;
    
    // Clean up page name/title - remove region code suffix
    $pageName = $page->name ?? ($pageTitle ?? '');
    $pageName = preg_replace('/-' . $regionCode . '$/i', '', $pageName);
    $pageName = trim($pageName);
    
    $title = $meta['title'] ?? $pageName;
    $description = $meta['description'] ?? ($page->meta_description ?? null);
    $path = $meta['path'] ?? request()->getPathInfo();
    
    // Remove region code from path if it exists to avoid duplication
    $pathWithoutRegion = $path;
    if ($regionCode !== 'us' && strpos($path, '/' . $regionCode . '/') === 0) {
        $pathWithoutRegion = substr($path, strlen('/' . $regionCode));
    }
    
    // FULL URL: Ensure the full URL uses the regionized site URL base
    $fullUrl = rtrim($siteUrl, '/') . '/' . ltrim($pathWithoutRegion, '/');
    
    // INITIALIZE BREADCRUMBS
    $breadcrumbs = $meta['breadcrumbs'] ?? [
        ['name'=>'Home', 'url'=>$siteUrl],
        ['name'=>$title, 'url'=>$pathWithoutRegion]
    ];
    
    // 🔥 FIX: FORCE HOME URL TO INCLUDE REGION
    if(!empty($breadcrumbs)) {
        $breadcrumbs[0]['url'] = $siteUrl;
    } else {
        $breadcrumbs = [
            ['name'=>'Home','url'=>$siteUrl],
            ['name'=>$title,'url'=>$pathWithoutRegion]
        ];
    }
@endphp

@php
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
    
    $crumbItems = [];
    $pos = 1;
    
    foreach($breadcrumbs as $b){
        // Special handling for Home breadcrumb - always use $siteUrl
        if($pos === 1 || strtolower($b['name']) === 'home' || strtolower($b['name']) === 'inicio'){
            $url = $siteUrl;
        }
        // If the URL is already absolute (starts with http)
        elseif(strpos($b['url'],'http') === 0){
            $url = $b['url'];
        }
        // If the URL is relative, prepend the regionalized site URL
        else {
            $url = rtrim($siteUrl,'/') . '/' . ltrim($b['url'],'/');
        }
        
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

@section('content')
<div class="container">
    <!--<h1 class="page-titles">Imprint</h1>-->

    @if($page)
        @php
            // Look for most specific section names first for imprint content
            $sectionNames = [
                'imprint-content-' . $currentRegion,
                'imprint-' . $currentRegion,
                'content-' . $currentRegion . '-imprint',
                'content-' . $currentRegion,
                $currentRegion . '-imprint-content',
                $currentRegion . '-content',
                'imprint-content',
                'content'
            ];
            
            $content = null;
            
            // First try exact matches from the predefined list
            foreach($sectionNames as $sectionName) {
                $section = $page->findSection($sectionName);
                if ($section) {
                    $content = $section;
                    break;
                }
            }
            
            // If no exact match found, try partial matches containing the region code
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
            <p>Imprint content for this region does not have content yet.</p>
        @endif
    @else
        <p>Imprint content not available for this region.</p>
    @endif
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
                    <span class="fw-semibold">Imprint</span>
                </li>
            </ul>
        </div>
    </div>
</nav>
@endpush
</div>
@endsection