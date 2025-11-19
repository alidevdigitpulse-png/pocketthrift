@extends('layouts.app')


@push('schemas')
@php
    $regionService = app(\App\Services\RegionService::class);
    $currentRegion = $regionService->getCurrentRegion();

    $regionCode = strtolower($currentRegion->code ?? 'us');
    $regionName = $currentRegion->country ?? 'USA';

    // Dynamic Meta Title & Description
    $dynamicTitle = "PocketThrift $regionName: Guides, Reviews, Coupons & Insights";
    $dynamicDesc = "Save money with PocketThrift $regionName! Discover the latest guides, product reviews, and exclusive coupons for valuable insights and smart shopping decisions!";
@endphp


<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@pocketthrift">

<meta name="description" content="{{ $dynamicDesc }}">
<meta name="robots" content="index, follow">

<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="https://pocketthrift.com/images/og-image.webp">
<meta property="og:image:type" content="image/webp">
<meta property="og:image:alt" content="PocketThrift">
<meta property="og:site_name" content="PocketThrift">
<meta property="og:title" content="{{ $dynamicTitle }}">
<meta property="og:description" content="{{ $dynamicDesc }}">

<meta property="twitter:title" content="{{ $dynamicTitle }}">
<meta property="twitter:description" content="{{ $dynamicDesc }}">
<meta property="twitter:image" content="https://pocketthrift.com/images/og-image.webp">
<meta property="twitter:image:alt" content="Pocketthrift">

@php
// Determine region code (from $region object or URL segment)
$regionCode = $region->code ?? request()->segment(1) ?? 'us';

// Map region codes to full region names
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

// Get the region name, default to uppercase region code if not found
$regionName = $regions[strtolower($regionCode)] ?? strtoupper($regionCode);

$regionCode = $region->code ?? ($regionCode ?? 'us');
$baseUrl = rtrim(config('app.url','https://pocketthrift.com'), '/');
$siteUrl = ($regionCode === 'us') ? $baseUrl : $baseUrl . '/' . $regionCode;

// FIXED: Always use mapped country name
$regionName = $regions[strtolower($regionCode)] ?? strtoupper($regionCode);
@endphp

@php

$faqEntities = [
  [
    "question" => "What is a Promo Code and how do I use it?",
    "answer" => "Find the Promo Code: Locate a valid promo code on PocketThrift $regionName. We offer a variety of codes for different stores and products. Copy the Code: Click on the promo code to copy it to your clipboard. Shop Online: Go to the online store where you want to make a purchase and add items to your cart. Apply the Code: During checkout, look for a field labeled “Promo Code,” “Coupon Code,” or “Discount Code.” Paste the code into this field and apply it. Enjoy Your Savings: The discount will be applied to your total, and you’ll see the updated amount before you complete your purchase."
  ],

  [
    "question" => "Do Promo Codes really work?",
    "answer" => "Yes, Promo Codes often work, but only last for a very short period of time. Retailers frequently run time-limited campaigns and use common coupon phrases throughout the year $regionName."
  ],

  [
    "question" => "What are the differences between a Deal, Promotion and a Discount code?",
    "answer" => "A deal is when a product or service is available at a reduced price compared to its regular cost. Promotions are special offers from retailers, like limited-time sales or free items, that provide extra value. A discount code, or promotional code, is a specific code you enter at checkout to receive a percentage or fixed amount off your total purchase.
"
  ],

  [
    "question" => "How frequently are new Deals and Coupon Codes updated on PocketThrift $regionName?",
    "answer" => "PocketThrift $regionName regularly updates new deals and coupon codes to provide you with the latest offers. We refresh our selection several times a week, so be sure to visit often for the newest savings opportunities."
  ],

  [
    "question" => "How can PocketThrift $regionName help me save money while shopping online?",
    "answer" => "PocketThrift $regionName helps you save money online by providing a variety of coupons and deals from numerous brands and categories. Our platform offers exclusive promo codes, curated deals, and regularly updated promotions, all easily accessible through our user-friendly interface. Use PocketThrift to find the best offers and maximize your savings on your favorite products and brands."
  ]
];

$faqMain = [];
foreach($faqEntities as $f){
    $faqMain[] = [
        "@type" => "Question",
        "name" => $f['question'],
        "acceptedAnswer" => [
            "@type" => "Answer",
            "text" => $f['answer']
        ]
    ];
}

$faqSchema = [
    "@context" => "https://schema.org/",
    "@type" => "FAQPage",
    "mainEntity" => $faqMain
];
@endphp

<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>
@endpush



@push('schemas')
@php
$regionCode = $region->code ?? ($regionCode ?? 'us');
$regionName = $region->name ?? ($regionName ?? strtoupper($regionCode));
$baseUrl = rtrim(config('app.url','https://pocketthrift.com'), '/');
$siteUrl = ($regionCode === 'us') ? $baseUrl : $baseUrl . '/' . $regionCode;
$faqsArr = $meta['faqs'] ?? ($faqs ?? []);
@endphp

@php
$org = [
  "@context"=>"https://schema.org/",
  "@type"=>"Organization",
  "name"=>"PocketThrift",
  "url"=>$siteUrl . "/",
  "logo"=>$siteUrl . "/images/og-image.webp",
  "contactPoint"=>["@type"=>"ContactPoint","telephone"=>"+61 414 573 000","contactType"=>"customer service","areaServed"=>$regionName,"availableLanguage"=>"en-US"],
  "sameAs"=>[
    "https://www.facebook.com/people/Pocket-Thrift/61562541071877/?mibextid=ZbWKwL",
    "https://x.com/Pocketthrift",
    "https://www.instagram.com/pocketthrift1/",
    "https://www.linkedin.com/in/pocket-thrift-40336131b",
    "https://www.pinterest.com/pocketthrift1/"
  ]
];

$website = [
  "@context"=>"https://schema.org/",
  "@type"=>"WebSite",
  "name"=>"PocketThrift",
  "url"=>$siteUrl . "/",
  "potentialAction"=>["@type"=>"SearchAction","target"=>$siteUrl . "/search?q={search_term_string}","query-input"=>"required name=search_term_string"]
];

$faqSchema = null;
if(!empty($faqsArr) && is_array($faqsArr)){
  $entities = [];
  foreach($faqsArr as $f){
    $q = $f['question'] ?? $f['name'] ?? null;
    $a = $f['answer'] ?? $f['acceptedAnswer'] ?? null;
    if($q && $a) $entities[] = ["@type"=>"Question","name"=>$q,"acceptedAnswer"=>["@type"=>"Answer","text"=>$a]];
  }
  if(count($entities)) $faqSchema = ["@context"=>"https://schema.org/","@type"=>"FAQPage","mainEntity"=>$entities];
}
@endphp

<script type="application/ld+json">{!! json_encode($org, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>
<script type="application/ld+json">{!! json_encode($website, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>
@if($faqSchema)
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>
@endif
@endpush



@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Hero Section -->
            <div class="col-12 p-0">
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
                                    <h1 class="display-4">Welcome to {{ config('app.name') }}</h1>
                                    <p class="lead">Discover amazing products at unbeatable prices</p>
                                    <a href="#" class="btn btn-light btn-lg">Shop Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-block w-100 bg-success text-white text-center py-5">
                                <div class="container">
                                    <h1 class="display-4">Exclusive Deals</h1>
                                    <p class="lead">Limited time offers on premium products</p>
                                    <a href="#" class="btn btn-light btn-lg">View Deals</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-block w-100 bg-warning text-white text-center py-5">
                                <div class="container">
                                    <h1 class="display-4">Free Shipping</h1>
                                    <p class="lead">On all orders over $50</p>
                                    <a href="#" class="btn btn-dark btn-lg">Start Shopping</a>
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
            </div>
        </div>



        <!-- Trending Section -->
        <div class="mt-5">
            <div class="container">
                {{-- <h2 class="text-center mb-5">Trending in {{ strtoupper( @if(request()->route('region'))
        {{ \App\Models\Region::where('code', request()->route('region'))->where('active', true)->first()->country ?? strtoupper(request()->route('region')) }}
      @else
        USA
      @endif) }}</h2> --}}

                <!-- Trending Stores Row -->
                <h3 class="mb-4">Top Picks for You</h3>
                <div class="row g-3 mb-5 box-trending-store">
                    @if ($trendingStores && $trendingStores->count() > 0)
                        @foreach ($trendingStores as $store)
                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                                <a href="{{ route('store.detail', ltrim($store->url_slug, '/')) }}"
                                    class="text-decoration-none d-block h-100">
                                    <div class="trending-box text-center p-1 d-flex flex-column h-100">
                                        <div class="trending-icon d-flex align-items-center justify-content-center mb-2">
                                            @if ($store->logo)
                                                <img src="{{ asset('uploads/' . $store->logo) }}" alt="{{ $store->title }}"
                                                    class="img-fluid" style="object-fit: contain;">
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
                            <p class="text-center text-muted">No trending stores available</p>
                        </div>
                    @endif
                </div>

                <!-- Trending Categories Row -->
                <h3 class="mb-4">Top Store Categories
                </h3>
                <div class="row g-3 mb-5 box-trending-store">
                    @if ($trendingCategories && $trendingCategories->count() > 0)
                        @foreach ($trendingCategories as $category)
                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                                <a href="{{ route('category.detail', ltrim($category->url_slug, '/')) }}"
                                    class="text-decoration-none d-block h-100">
                                    <div class="trending-cat-box text-center p-1 d-flex flex-column h-100">
                                        <div class="trending-icon d-flex align-items-center justify-content-center mb-2">
                                            @if ($category->logo)
                                                <img src="{{ asset('uploads/' . $category->logo) }}"
                                                    alt="{{ $category->title }}" class="img-fluid"
                                                    style="object-fit: contain;">
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
                            <p class="text-center text-muted">No trending categories available</p>
                        </div>
                    @endif
                </div>

                <!-- Trending Offers Row -->
                <h3 class="mb-4">Trending Offers</h3>
                <div class="mb-5">
                    @if ($trendingOffers && $trendingOffers->count() > 0)
                        <div class="trending-offers-container">
                            <div class="trending-offers-slider" id="trendingOffersSlickSlider">
                                @foreach ($trendingOffers as $index => $offer)
                                    @php
                                        $store = $offer->store;
                                        $category = optional($store)->category;
                                    @endphp
                                    <div class="slick-slide-item">
                                        <div class="offer-carousel-card h-100">
                                            <div class="card-banner"
                                                style="background-image: url('{{ $category && $category->logo ? asset('uploads/' . $category->logo) : asset('images/default-banner.png') }}');">
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
                                                        <p class="fs-5 fw-bold text-danger">
                                                            {{ is_numeric($offer->discount) ? $offer->discount . '%' : $offer->discount }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <a href="{{ route('store.detail', ltrim(optional($offer->store)->url_slug, '/')) }}"
                                                    class="btn get-code-btn mt-auto">GET CODE</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>



                </div>
            @else
                <div class="col-12">
                    <p class="text-center text-muted">No trending offers available</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        #trendingOffersCarousel .carousel-control-prev,
        #trendingOffersCarousel .carousel-control-next {
            width: 50px;
            height: 50px;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.8;
            transition: opacity 0.3s;
            z-index: 10;
        }

        #trendingOffersCarousel .carousel-control-prev:hover,
        #trendingOffersCarousel .carousel-control-next:hover {
            opacity: 1;
            background-color: rgba(0, 0, 0, 0.7);
        }

        #trendingOffersCarousel .carousel-control-prev {
            left: 10px;
        }

        #trendingOffersCarousel .carousel-control-next {
            right: 10px;
        }

        #trendingOffersCarousel .carousel-control-prev-icon,
        #trendingOffersCarousel .carousel-control-next-icon {
            width: 20px;
            height: 20px;
        }

        #trendingOffersCarousel .carousel-inner {
            transition: transform 0.6s ease-in-out;
        }

        /* Slick slider custom styles */
        .trending-offers-slider .slick-slide {
            padding: 0 10px;
        }

        .trending-offers-slider .slick-list {
            margin: 0 -10px;
        }

        .trending-offers-slider .slick-dots {
            bottom: -40px;
        }

        .trending-offers-slider .slick-dots li button:before {
            font-size: 12px;
            color: #ff5900;
        }

        .trending-offers-slider .slick-dots li.slick-active button:before {
            color: #ff5900;
            opacity: 1;
        }
    </style>

    <!-- ✅ 1. jQuery (must be first) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- ✅ 2. Slick Slider JS -->
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <!-- ✅ 3. Your custom script -->
    <script>
        $(document).ready(function() {
            // Vanilla JS slider logic
            (function() {
                const sliderInner = document.getElementById('trendingOffersSliderInner');
                const prevBtn = document.getElementById('trendingOffersPrev');
                const nextBtn = document.getElementById('trendingOffersNext');

                if (!sliderInner || !prevBtn || !nextBtn) return;

                const items = sliderInner.querySelectorAll('.carousel-item-wrapper');
                const totalItems = items.length;
                const visibleItems = Math.min(3, totalItems);
                let currentIndex = 0;

                function updateSliderPosition() {
                    const itemWidth = items[0].offsetWidth || (sliderInner.offsetWidth / visibleItems);
                    const translateX = -currentIndex * itemWidth;
                    sliderInner.style.transform = `translateX(${translateX}px)`;
                }

                window.addEventListener('resize', updateSliderPosition);
                updateSliderPosition();

                nextBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentIndex = (currentIndex < totalItems - visibleItems) ? currentIndex + 1 : 0;
                    updateSliderPosition();
                });

                prevBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentIndex = (currentIndex > 0) ? currentIndex - 1 : Math.max(0, totalItems -
                        visibleItems);
                    updateSliderPosition();
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'ArrowLeft') prevBtn.click();
                    else if (e.key === 'ArrowRight') nextBtn.click();
                });
            })();

            // ✅ Initialize Slick only if items exist
            if ($('#trendingOffersSlickSlider .slick-slide-item').length > 0) {
                $('#trendingOffersSlickSlider').slick({
                    dots: true,
                    infinite: true,
                    speed: 300,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    adaptiveHeight: true,
                    autoplay: true,
                    autoplaySpeed: 2000,
                    responsive: [{
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                });
            }
        });
    </script>
    <div class="container mt-5">
        <div class="faq-header">
            FREQUENTLY ASKED QUESTIONS (FAQS)
        </div>

        <div class="faq-container">
            <!-- Question 1 - Expanded by default -->
            <div class="faq-item">
                <div class="faq-question expanded" onclick="toggleFAQ(this)">
                    <i class="fas fa-minus"></i>
                    What is a Promo Code and how do I use it?
                </div>
                <div class="faq-content show">
                    <ul>
                        <li><strong>Find the Promo Code:</strong> Locate a valid promo code on PocketThrift  @if(request()->route('region'))
        {{ \App\Models\Region::where('code', request()->route('region'))->where('active', true)->first()->country ?? strtoupper(request()->route('region')) }}
      @else
        USA
      @endif. We offer a
                            variety of codes for different stores and products.</li>
                        <li><strong>Copy the Code:</strong> Click on the promo code to copy it to your clipboard.</li>
                        <li><strong>Shop Online:</strong> Go to the online store where you want to make a purchase and add
                            items to your cart.</li>
                        <li><strong>Apply the Code:</strong> During checkout, look for a field labeled "Promo Code," "Coupon
                            Code," or "Discount Code." Paste the code into this field and apply it.</li>
                        <li><strong>Enjoy Your Savings:</strong> The discount will be applied to your total, and you'll see
                            the updated amount before you complete your purchase.</li>
                    </ul>
                </div>
            </div>

            <!-- Question 2 -->
            <div class="faq-item">
                <div class="faq-question collapsed" onclick="toggleFAQ(this)">
                    <i class="fas fa-plus"></i>
                    Do Promo Codes really work?
                </div>
                <div class="faq-content">
                    <p>Yes, Promo Codes often work, but only last for a very short period of time. Retailers frequently run
                        time-limited campaigns and use common coupon phrases throughout the year.</p>
                </div>
            </div>

            <!-- Question 3 -->
            <div class="faq-item">
                <div class="faq-question collapsed" onclick="toggleFAQ(this)">
                    <i class="fas fa-plus"></i>
                    What are the differences between a Deal, Promotion and a Discount code?
                </div>
                <div class="faq-content">
                    <p>A deal is when a product or service is available at a reduced price compared to its regular cost.
                        Promotions are special offers from retailers, like limited-time sales or free items, that provide
                        extra value. A discount code, or promotional code, is a specific code you enter at checkout to
                        receive a percentage or fixed amount off your total purchase.</p>
                </div>
            </div>

            <!-- Question 4 -->
            <div class="faq-item">
                <div class="faq-question collapsed" onclick="toggleFAQ(this)">
                    <i class="fas fa-plus"></i>
                    How frequently are new Deals and Coupon Codes updated on PocketThrift  @if(request()->route('region'))
        {{ \App\Models\Region::where('code', request()->route('region'))->where('active', true)->first()->country ?? strtoupper(request()->route('region')) }}
      @else
        USA
      @endif?
                </div>
                <div class="faq-content">
                    <p>PocketThrift  @if(request()->route('region'))
        {{ \App\Models\Region::where('code', request()->route('region'))->where('active', true)->first()->country ?? strtoupper(request()->route('region')) }}
      @else
        USA
      @endif regularly updates new deals and coupon codes to provide you with the latest offers.
                        We refresh our selection several times a week, so be sure to visit often for the newest savings
                        opportunities.</p>
                </div>
            </div>

            <!-- Question 5 -->
            <div class="faq-item">
                <div class="faq-question collapsed" onclick="toggleFAQ(this)">
                    <i class="fas fa-plus"></i>
                    How can PocketThrift 
                     @if(request()->route('region'))
        {{ \App\Models\Region::where('code', request()->route('region'))->where('active', true)->first()->country ?? strtoupper(request()->route('region')) }}
      @else
        USA
      @endif
                    help me save money while shopping online?


                </div>
                <div class="faq-content">
                    <p>PocketThrift  @if(request()->route('region'))
        {{ \App\Models\Region::where('code', request()->route('region'))->where('active', true)->first()->country ?? strtoupper(request()->route('region')) }}
      @else
        USA
      @endif helps you save money online by providing a variety of coupons and deals from
                        numerous brands and categories. Our platform offers exclusive promo codes, curated deals, and
                        regularly updated promotions, all easily accessible through our user-friendly interface. Use
                        PocketThrift to find the best offers and maximize your savings on your favorite products and brands.
                    </p>
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

@endsection
