@extends('layouts.app')
@php
    $regionService = app(\App\Services\RegionService::class);
    $currentRegion = $regionService->getCurrentRegion();

    $regionCode = strtolower($currentRegion->code ?? 'us');
    $regionName = $currentRegion->country ?? 'USA';

    // DYNAMIC META CONTENT FOR STORES PAGE
    $storeTitle = "Explore All Stores & Top Brands in $regionName";

    $storeDesc = "Explore amazing deals and promo codes from top $regionName brands! Shop fashion, electronics, home essentials, beauty, and more, and save big on every purchase!";
@endphp

@push('schemas')
<title>{{ $storeTitle }}</title>

<meta name="description" content="{{ $storeDesc }}">
<meta name="keywords" content="All Stores & Top Brands">
<meta name="robots" content="index, follow">

<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $storeTitle }}">
<meta property="og:description" content="{{ $storeDesc }}">
<meta property="og:image" content="https://pocketthrift.com/images/og-image.webp">

<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:domain" content="pocketthrift.com">
<meta property="twitter:url" content="{{ url()->current() }}">
<meta property="twitter:title" content="{{ $storeTitle }}">
<meta property="twitter:description" content="{{ $storeDesc }}">
<meta property="twitter:image" content="https://pocketthrift.com/images/og-image.webp">

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
    
    // Clean up page name/title - remove region code suffix
    $pageName = $page->name ?? ($pageTitle ?? '');
    $pageName = preg_replace('/-' . $regionCode . '$/i', '', $pageName);
    $pageName = trim($pageName);
    
    $title = $meta['title'] ?? ($page->meta_title ?? $pageName);
    
    // If title is still empty, use default with region name
    if (empty($title)) {
        $title = "Explore All Stores & Top Brands in the $regionName";
    }
    
    // Replace region placeholders in title
    $title = str_replace(['$regionName', '$regionCode'], [$regionName, $regionCode], $title);
    $title = str_replace(['in the $regionName', 'in $regionName'], ['in the ' . $regionName, 'in ' . $regionName], $title);
    
    $description = $meta['description'] ?? ($page->meta_description ?? null);
    
    // If description is empty, use default with region name
    if (empty($description)) {
        $description = "Explore amazing deals and promo codes from top $regionName brands! Shop fashion, electronics, home essentials, beauty, and more, and save big on every purchase!";
    }
    
    // Replace region placeholders in description
    $description = str_replace(['$regionName', '$regionCode'], [$regionName, $regionCode], $description);
    
    $path = $meta['path'] ?? request()->getPathInfo();
    
    // Remove region code from path if it exists to avoid duplication
    $pathWithoutRegion = $path;
    if ($regionCode !== 'us' && strpos($path, '/' . $regionCode . '/') === 0) {
        $pathWithoutRegion = substr($path, strlen('/' . $regionCode));
    }
    
    // FULL URL: Ensure the full URL uses the regionized site URL base
    $fullUrl = rtrim($siteUrl, '/') . rtrim($pathWithoutRegion, '/');
    
    // Ensure URL ends with trailing slash for consistency
    if (substr($fullUrl, -1) !== '/') {
        $fullUrl .= '/';
    }
    
    // INITIALIZE BREADCRUMBS
    $breadcrumbs = $meta['breadcrumbs'] ?? [
        ['name'=>'Home', 'url'=>$siteUrl],
        ['name'=>'Stores', 'url'=>$pathWithoutRegion]
    ];
    
    // 🔥 FIX: FORCE HOME URL TO INCLUDE REGION
    if(!empty($breadcrumbs)) {
        $breadcrumbs[0]['url'] = $siteUrl;
    } else {
        $breadcrumbs = [
            ['name'=>'Home','url'=>$siteUrl],
            ['name'=>'Stores','url'=>$pathWithoutRegion]
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
                "url"=>$baseUrl . '/images/og-image.webp'
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


<style>
.category-cards {
  background: #f7f7f7;
  padding: 20px 0;
}

.alphabet-filter {
  overflow-x: auto;
  white-space: nowrap;
  padding-bottom: 4px;
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

/* Store Card Design */
.store-card {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  transition: transform 0.2s ease;
  height: 100%;
}
.store-card:hover {
  transform: translateY(-4px);
}

.store-logo {
  width: 80px;
  height: 80px;
  object-fit: contain;
  border-radius: 6px;
}

.store-name {
  font-weight: 600;
  font-size: 1rem;
  color: #222;
}

.store-meta {
  font-size: 0.9rem;
  color: #6c757d;
}

/* For 5 cards per row on large screens */
@media (min-width: 992px) {
  .col-lg-2-4 {
    flex: 0 0 auto;
    width: 20%;
  }
}

@media (max-width: 767.98px) {
    .top-categories-widget h1, .category-content h1 { font-size: 1.8rem; }
    .top-categories-widget h2 { font-size: 1.5rem; }
    .search-bar {
        width: 100%;
        margin-left: 0 !important;
    }
    .alphabet-filter {
        padding-bottom: 10px;
    }
}
</style>

<div class="category-content pb-2 pt-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4 text-dark">All Stores</h1>
                <div class="secondary-heading">Verified On: October, 2025</div>
                <p>Welcome to your ultimate savings hub! Here, you’ll find a fantastic multiple brands from across the USA, all ready to help you score amazing deals. Whether you’re hunting for stylish fashion, cutting-edge electronics, home essentials, or beauty must-haves, we’ve gathered the best promo codes, deals, and coupons just for you. Our mission is to make your shopping experience not only enjoyable but also budget-friendly.
                </p>
                
            </div>
        </div>
    </div>
</div>

<div class="category-cards">
  <div class="container">
    <div class="top-categories-widget pb-5">
      <h2 class="mt-5 pb-3">Top Brands for Coupons and Deals in the 
      @if(request()->route('region'))
        {{ \App\Models\Region::where('code', request()->route('region'))->where('active', true)->first()->country ?? strtoupper(request()->route('region')) }}
      @else
        USA
      @endif
      </h2>

      <!-- Alphabet Filter & Search -->
      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
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
          <div class="input-group input-group-sm">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by Stores name">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
          </div>
        </div>
      </div>

      <!-- Store Cards -->
      <div class="row g-3" id="storeContainer">
        @forelse($stores as $store)
        <div class="col-6 col-md-4 col-lg-2-4 store-item" data-name="{{ $store->title }}">
            <a href="{{ route('store.detail', ltrim($store->url_slug, '/')) }}" class="text-decoration-none">
            <div class="store-card text-left p-3">
              <img src="{{ $store->logo ? asset('uploads/' . $store->logo) : asset('uploads/default-store.png') }}" alt="{{ $store->title }}" class="store-logo mb-2" onerror="this.onerror=null; this.src='{{ asset('uploads/default-store.png') }}';">
              <h6 class="store-name">{{ $store->title }}</h6>
              <hr class="my-2">
              <p class="store-meta mb-0">
                @php
                  $affiliateLinks = $store->affiliate_links ? json_decode($store->affiliate_links, true) : null;
                  $couponCount = is_array($affiliateLinks) ? count($affiliateLinks) : 0;
                @endphp
                {{ $couponCount }} Coupons <span class="mx-2">|</span> {{ rand(1, 15) }} Offers
              </p>
            </div>
          </a>
        </div>
        @empty
        <div class="col-12">
            <p class="text-center">No stores available at the moment.</p>
        </div>
        @endforelse
      </div>
    </div>
  </div>
</div>

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

<!-- Add Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

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
                        <span class="fw-semibold">Stores</span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endpush
@endsection