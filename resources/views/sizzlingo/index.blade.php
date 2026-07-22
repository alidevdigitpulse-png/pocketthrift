@extends('layouts.app')

@section('title', 'Sizzzlingo Express Halal Ready Meals | PocketThrift Australia')

@section('meta_description',
    'Discover chef-prepared halal ready meals from Sizzzlingo Express, recommended by
    PocketThrift Australia.')

    @push('css')
        <link rel="stylesheet" href="{{ asset('front/css/sizzlingo-landing.css') }}?v={{ time() }}">
    @endpush

@section('content')

    <main class="sz-page">

        {{-- ===============================================================
         Hero
    ================================================================ --}}

        <section class="sz-hero">

            {{-- Background video --}}
            <video class="sz-hero-background" autoplay muted loop playsinline preload="metadata"
                poster="{{ asset('images/sizzlingo/hero-meal.webp') }}" aria-hidden="true">
                {{-- Apni actual video filename/path yahan set karo --}}
                <source src="{{ asset('uploads/page/sizzlingo/hero-video-Bi7D3R_Z.mp4') }}" type="video/mp4">
            </video>

            {{-- Dark overlay --}}
            <div class="sz-hero-overlay"></div>

            {{-- Centered hero content --}}
            <div class="sz-container sz-hero-inner">

                <div class="sz-partner-pill">
                    <i class="fas fa-handshake" aria-hidden="true"></i>
                    PocketThrift Featured Partner · Sizzzlingo Express
                </div>

                <h1>
                    Healthy Halal Meals,<br>
                    <span>Delivered</span> Across Sydney
                </h1>

                <p class="sz-hero-description">
                    Chef-prepared, ready-to-eat halal food from SizZzlingo Express — recommended by PocketThrift and
                    delivered fresh to your door.
                </p>

                <div class="sz-hero-actions">

                    <a href="#sz-collections" class="sz-button sz-button-primary">
                        Explore Meals
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>

                    <a href="#sz-comparison" class="sz-button sz-button-outline">
                        Why PocketThrift Recommends
                    </a>

                </div>

                <div class="sz-hero-points">

                    <span>
                        <i class="fas fa-certificate" aria-hidden="true"></i>
                        100% Halal Certified
                    </span>

                    <span>
                        <i class="fas fa-stopwatch" aria-hidden="true"></i>
                        Ready in Minutes
                    </span>

                    <span>
                        <i class="fas fa-truck" aria-hidden="true"></i>
                        Sydney-wide Delivery
                    </span>

                </div>

            </div>

            {{-- Badge bottom-left --}}
            <div class="sz-halal-badge">

                <img src="{{ asset('uploads/page/sizzlingo/sizzzlingo-logo-BM0Sz1x2.png') }}" alt="Sizzzlingo Express"
                    width="42" height="42" loading="eager">

                <span>
                    <small>Featured Partner</small>
                    <strong>Sizzzlingo Express</strong>
                </span>

            </div>

        </section>

        {{-- ===============================================================
         Trust strip
    ================================================================ --}}

        <section class="sz-trust-strip" aria-label="Sizzzlingo benefits">
            <div class="sz-container sz-trust-grid">

                <div class="sz-trust-item">
                    <i class="fas fa-certificate"></i>
                    <span>Certified Halal Meal Pans</span>
                </div>

                <div class="sz-trust-item">
                    <i class="fas fa-clock"></i>
                    <span>Ready in Minutes</span>
                </div>

                <div class="sz-trust-item">
                    <i class="fas fa-leaf"></i>
                    <span>Fresh Ingredients</span>
                </div>

                <div class="sz-trust-item">
                    <i class="fas fa-wallet"></i>
                    <span>Affordable Plans</span>
                </div>

                <div class="sz-trust-item">
                    <i class="fas fa-users"></i>
                    <span>Nutritionally Balanced Meals</span>
                </div>



            </div>
        </section>

        {{-- ===============================================================
         Why choose
    ================================================================ --}}

        <section class="sz-section sz-why-section">

            <div class="sz-container">

                <div class="sz-section-heading">
                    <span class="sz-eyebrow">Why we recommend</span>

                    <h2>Why Choose SizZzlingo Express?</h2>

                    <p>
                        A partner brand we trust to deliver on taste, convenience, and value for money.

                    </p>
                </div>

                <div class="sz-featured-partner">
                    <img src="{{ asset('uploads/page/sizzlingo/sizzzlingo-logo-BM0Sz1x2.png') }}" alt=""
                        width="36" height="36" loading="lazy">

                    <span>Featured Partner · Sizzzlingo Express</span>
                </div>

                <div class="sz-reasons-grid">

                    <article class="sz-reason-card">
                        <span class="sz-icon-box">
                            <i class="fas fa-certificate"></i>
                        </span>

                        <h3>Authentic Halal Prepared Meals</h3>

                        <p>
                            Every dish is prepared to strict halal standards using certified ingredients.

                        </p>
                    </article>

                    <article class="sz-reason-card">
                        <span class="sz-icon-box">
                            <i class="fas fa-utensils"></i>
                        </span>

                        <h3>Ready-to-Eat Convenience</h3>

                        <p>
                            Skip the preparation. Heat, plate, and enjoy chef-quality meals in minutes.

                        </p>
                    </article>

                    <article class="sz-reason-card">
                        <span class="sz-icon-box">
                            <i class="fas fa-leaf"></i>
                        </span>

                        <h3>Fresh Ingredients</h3>

                        <p>
                            Real produce, real proteins and full flavour, without over-processing.

                        </p>
                    </article>

                    <article class="sz-reason-card">
                        <span class="sz-icon-box">
                            <i class="fas fa-wallet"></i>
                        </span>

                        <h3>Budget Friendly</h3>

                        <p>
                            Meal plans priced to beat takeaway, with PocketThrift savings on top.

                        </p>
                    </article>

                    <article class="sz-reason-card">
                        <span class="sz-icon-box">
                            <i class="fas fa-users"></i>
                        </span>

                        <h3>Family Meals</h3>

                        <p>
                            Generous family packs designed to keep the whole table happy.

                        </p>
                    </article>

                    <article class="sz-reason-card">
                        <span class="sz-icon-box">
                            <i class="fas fa-truck"></i>
                        </span>

                        <h3>Sydney-Wide Delivery</h3>

                        <p>
                            Shipping to supported suburbs, cities, states, and territories.

                        </p>
                    </article>

                </div>
            </div>
        </section>



        {{-- ===============================================================
         How it works
    ================================================================ --}}

        <section class="sz-section sz-steps-section">

            <div class="sz-container">

                <div class="sz-section-heading">
                    <span class="sz-eyebrow">How it works</span>
                    <h2>From menu to table in four steps</h2>
                    <p>A simple, no-fuss halal meal prep Sydney experience designed for busy Australians.
                    </p>
                </div>

                <div class="sz-steps-grid">

                    <article class="sz-step-card">
                        <span class="sz-step-label">Step 1</span>

                        <span class="sz-step-icon">
                            <i class="fas fa-search"></i>
                        </span>

                        <h3>Browse Meals</h3>
                        <p>Explore chef-designed best halal foods online.</p>
                    </article>

                    <article class="sz-step-card">
                        <span class="sz-step-label">Step 2</span>

                        <span class="sz-step-icon">
                            <i class="fas fa-box-open"></i>
                        </span>

                        <h3>Choose Your Plan</h3>
                        <p>Pick single meals, weekly plans or family packs.</p>
                    </article>

                    <article class="sz-step-card">
                        <span class="sz-step-label">Step 3</span>

                        <span class="sz-step-icon">
                            <i class="fas fa-truck"></i>
                        </span>

                        <h3>Meals Delivered</h3>
                        <p>Receive fresh meals at your door.</p>
                    </article>

                    <article class="sz-step-card">
                        <span class="sz-step-label">Step 4</span>

                        <span class="sz-step-icon">
                            <i class="fas fa-fire"></i>
                        </span>

                        <h3>Heat & Enjoy</h3>
                        <p>Your meal is ready in about three minutes..</p>
                    </article>

                </div>
            </div>
        </section>


        {{-- ===============================================================
        Digit Pulse Banner
    ================================================================ --}}

        <section class="sz-campaign-section">
            <div class="container">

                <div class="sz-campaign-banner">

                    {{-- Decorative Background --}}
                    <span class="sz-campaign-circle sz-circle-one"></span>
                    <span class="sz-campaign-circle sz-circle-two"></span>
                    <span class="sz-campaign-circle sz-circle-three"></span>

                    <div class="sz-campaign-glow"></div>

                    {{-- Left Content --}}
                    <div class="sz-campaign-content">

                        <div class="sz-campaign-eyebrow">
                            <span class="sz-eyebrow-icon">
                                <i class="fas fa-location-dot"></i>
                            </span>

                            Premium Meal Delivery Across Sydney
                        </div>

                        <h2>
                            Fresh Halal Meals,
                            <span>Ready When You Are.</span>
                        </h2>

                        <p>
                            Get fresh, halal, ready-to-eat meals delivered directly to your door, now at a reduced price.
                            You just have to use the code “DIGITPULSEPTYLT” when you check out to get a discount on your
                            initial purchase.
                        </p>

                        <div class="sz-campaign-highlights">

                            <div class="sz-campaign-highlight">
                                <i class="fas fa-circle-check"></i>

                                <span>
                                    <strong>Premium Halal</strong>
                                    <small>Quality ingredients</small>
                                </span>
                            </div>

                            <div class="sz-campaign-highlight">
                                <i class="fas fa-clock"></i>

                                <span>
                                    <strong>Ready-to-Eat</strong>
                                    <small>Quick and convenient</small>
                                </span>
                            </div>

                            <div class="sz-campaign-highlight">
                                <i class="fas fa-truck-fast"></i>

                                <span>
                                    <strong>Sydney Delivery</strong>
                                    <small>Delivered to your door</small>
                                </span>
                            </div>

                        </div>

                        <a href="https://www.sizzzlingoexpress.com.au/" class="sz-campaign-cta" target="_blank"
                            rel="nofollow sponsored noopener">

                            Explore Sizzlingo Meals

                            <span>
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </a>

                    </div>

                    {{-- Right Coupon Area --}}
                    <div class="sz-campaign-offer">

                        <div class="sz-offer-floating-label">
                            <i class="fas fa-sparkles"></i>
                            PocketThrift Exclusive
                        </div>

                        <div class="sz-ticket-card">

                            <span class="sz-ticket-cut sz-ticket-cut-top"></span>
                            <span class="sz-ticket-cut sz-ticket-cut-bottom"></span>

                            <div class="sz-ticket-top">

                                <div class="sz-ticket-percent">
                                    <span>10</span>
                                    <small>%</small>
                                </div>

                                <div class="sz-ticket-heading">
                                    <span>Special Sydney Offer</span>
                                    <strong>Get 10% OFF</strong>
                                    <p>Your next Sizzlingo meal order</p>
                                </div>

                            </div>

                            <div class="sz-ticket-divider">
                                <span></span>
                            </div>

                            <div class="sz-ticket-code-area">

                                <span class="sz-ticket-code-label">
                                    Use discount code
                                </span>

                                <button type="button" class="sz-ticket-code" data-sz-copy-code="DIGITPULSEPTYLT">

                                    <strong>DIGITPULSEPTYLT</strong>

                                    <span class="sz-ticket-copy-icon">
                                        <i class="far fa-copy"></i>
                                    </span>

                                </button>

                                <small class="sz-ticket-copy-text">
                                    Click the code to copy
                                </small>

                            </div>

                        </div>

                        <div class="sz-offer-mini-card sz-mini-card-one">
                            <span>
                                <i class="fas fa-leaf"></i>
                            </span>

                            <div>
                                <small>Fresh &amp; Healthy</small>
                                <strong>Made with Care</strong>
                            </div>
                        </div>

                        <div class="sz-offer-mini-card sz-mini-card-two">
                            <span>
                                <i class="fas fa-bowl-food"></i>
                            </span>

                            <div>
                                <small>Easy Meals</small>
                                <strong>Heat &amp; Enjoy</strong>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </section>


        {{-- ===============================================================
       Store Offers
    ================================================================ --}}
        @if (isset($exclusiveOffers) &&
                $exclusiveOffers instanceof \Illuminate\Support\Collection &&
                $exclusiveOffers->isNotEmpty())

            @php
                $allSizzlingoOffersUrl = url('/au/stores/sizzzlingo-express');
            @endphp

            <section class="szx-exclusive-offers" id="exclusive-sizzlingo-offers">
                <div class="sz-container">

                    <div class="szx-exclusive-offers__heading">

                        <span class="szx-exclusive-offers__eyebrow">
                            Save on your next order
                        </span>

                        <h2>Exclusive SizzlinGo Offers</h2>

                        <p>
                            Discover the latest SizzlinGo discount codes and
                            special deals, automatically updated whenever new
                            promotions are added.
                        </p>

                    </div>

                    <div class="szx-offers-grid">

                        @foreach ($exclusiveOffers as $offer)
                            @php
                                $offerType = strtolower(trim((string) ($offer->type ?? 'deal')));

                                $isCodeOffer = $offerType === 'code';

                                $offerCode = trim((string) ($offer->code ?? ''));

                                $offerDiscount = trim((string) ($offer->discount ?? ''));

                                $offerTitle = trim((string) ($offer->title ?? 'SizzlinGo Offer'));

                                $isVerified = (int) ($offer->verified ?? 0) === 1;

                                $hasCouponCode = $isCodeOffer && $offerCode !== '';
                            @endphp

                            <article class="szx-offer-card">

                                <div class="szx-offer-card__header">

                                    <div class="szx-offer-card__icon {{ $hasCouponCode ? 'is-code' : 'is-deal' }}">
                                        <i class="fas {{ $hasCouponCode ? 'fa-ticket-alt' : 'fa-bolt' }}"
                                            aria-hidden="true"></i>
                                    </div>

                                    <span class="szx-offer-card__type {{ $hasCouponCode ? 'is-code' : 'is-deal' }}">
                                        {{ $hasCouponCode ? 'Promo Code' : 'Deal' }}
                                    </span>

                                </div>

                                <div class="szx-offer-card__body">

                                    @if ($offerDiscount !== '')
                                        <div class="szx-offer-card__discount">
                                            {{ $offerDiscount }}
                                        </div>
                                    @endif

                                    <h3>
                                        {{ $offerTitle }}
                                    </h3>

                                    <div class="szx-offer-card__meta">

                                        @if ($isVerified)
                                            <span class="is-verified">
                                                <i class="fas fa-check-circle" aria-hidden="true"></i>

                                                Verified offer
                                            </span>
                                        @endif

                                        @if (!empty($offer->end_date))
                                            <span>
                                                <i class="far fa-clock" aria-hidden="true"></i>

                                                Ends
                                                {{ \Carbon\Carbon::parse($offer->end_date)->format('d M Y') }}
                                            </span>
                                        @else
                                            <span>
                                                <i class="far fa-clock" aria-hidden="true"></i>

                                                Limited-time offer
                                            </span>
                                        @endif

                                    </div>

                                    <div class="szx-offer-card__action">

                                        <div class="szx-offer-card__action-note">

                                            @if ($hasCouponCode)
                                                <i class="fas fa-ticket-alt" aria-hidden="true"></i>

                                                <span>
                                                    Coupon code available
                                                </span>
                                            @else
                                                <i class="fas fa-bolt" aria-hidden="true"></i>

                                                <span>
                                                    No coupon code required
                                                </span>
                                            @endif

                                        </div>

                                        @if ($hasCouponCode)
                                            <button type="button" class="szx-offer-card__button js-szx-copy-offer"
                                                data-code="{{ $offerCode }}" data-url="{{ $allSizzlingoOffersUrl }}">
                                                <i class="far fa-copy" aria-hidden="true"></i>

                                                <span class="js-szx-button-text">
                                                    Copy Code &amp; Shop
                                                </span>
                                            </button>
                                        @else
                                            <a href="{{ $allSizzlingoOffersUrl }}" class="szx-offer-card__button">
                                                <span>Shop This Deal</span>

                                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                            </a>
                                        @endif

                                    </div>

                                </div>

                            </article>
                        @endforeach

                    </div>

                    <div class="szx-exclusive-offers__footer">

                        <a href="{{ $allSizzlingoOffersUrl }}" class="szx-view-all-offers">
                            <span>View All SizzlinGo Offers</span>

                            <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>

                    </div>

                </div>
            </section>



        @endif


        {{-- ===============================================================
         Dynamic collections
    ================================================================ --}}

        <section id="sz-collections" class="sz-section sz-collections-section">
            <div class="sz-container">

                <div class="sz-section-heading">
                    <span class="sz-eyebrow">
                        Featured categories
                    </span>

                    <h2>
                        Something for every appetite
                    </h2>

                    <p>
                        From high-protein lunches to family-size dinners — all halal and ready in minutes.

                    </p>
                </div>

                @if ($collections->count() > 0)

                    <div class="sz-collections-grid">

                        @foreach ($collections as $collection)
                            <a href="{{ $collection['url'] }}" class="sz-collection-card">
                                <div class="sz-collection-image">

                                    <img src="{{ !empty($collection['image']) ? $collection['image'] : asset('uploads/page/sizzlingo/sizzlingo-logo.jpg') }}"
                                        alt="{{ !empty($collection['image_alt']) ? $collection['image_alt'] : $collection['title'] }}"
                                        width="700" height="500" loading="lazy">
                                </div>

                                <div class="sz-collection-content">

                                    <h3>
                                        {{ $collection['title'] }}
                                    </h3>

                                    <p>
                                        {{ $collection['description'] }}
                                    </p>

                                    <span class="sz-collection-arrow" aria-hidden="true">
                                        <i class="fas fa-arrow-right"></i>
                                    </span>

                                </div>
                            </a>
                        @endforeach

                    </div>

                    {{-- Pagination --}}
                    @if ($collections->hasPages())

                        @php
                            $currentPage = $collections->currentPage();
                            $lastPage = $collections->lastPage();

                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($lastPage, $currentPage + 2);
                        @endphp

                        <nav class="sz-pagination" aria-label="Sizzzlingo collections pagination">

                            {{-- Previous --}}
                            @if ($collections->onFirstPage())
                                <span class="sz-pagination-link is-disabled" aria-disabled="true">
                                    <i class="fas fa-chevron-left"></i>
                                    Previous
                                </span>
                            @else
                                <a href="{{ $collections->previousPageUrl() }}#sz-collections" class="sz-pagination-link"
                                    rel="prev">
                                    <i class="fas fa-chevron-left"></i>
                                    Previous
                                </a>
                            @endif

                            <div class="sz-pagination-pages">

                                {{-- First page --}}
                                @if ($startPage > 1)

                                    <a href="{{ $collections->url(1) }}#sz-collections" class="sz-pagination-number">
                                        1
                                    </a>

                                    @if ($startPage > 2)
                                        <span class="sz-pagination-dots">
                                            &hellip;
                                        </span>
                                    @endif

                                @endif

                                {{-- Nearby pages --}}
                                @for ($page = $startPage; $page <= $endPage; $page++)
                                    @if ($page === $currentPage)
                                        <span class="sz-pagination-number is-active" aria-current="page">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $collections->url($page) }}#sz-collections"
                                            class="sz-pagination-number">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endfor

                                {{-- Last page --}}
                                @if ($endPage < $lastPage)

                                    @if ($endPage < $lastPage - 1)
                                        <span class="sz-pagination-dots">
                                            &hellip;
                                        </span>
                                    @endif

                                    <a href="{{ $collections->url($lastPage) }}#sz-collections"
                                        class="sz-pagination-number">
                                        {{ $lastPage }}
                                    </a>

                                @endif

                            </div>

                            {{-- Next --}}
                            @if ($collections->hasMorePages())
                                <a href="{{ $collections->nextPageUrl() }}#sz-collections" class="sz-pagination-link"
                                    rel="next">
                                    Next
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span class="sz-pagination-link is-disabled" aria-disabled="true">
                                    Next
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            @endif

                        </nav>

                        <p class="sz-pagination-summary">
                            Showing
                            {{ $collections->firstItem() }}
                            to
                            {{ $collections->lastItem() }}
                            of
                            {{ $collections->total() }}
                            collections
                        </p>

                    @endif
                @else
                    <div class="sz-api-message">

                        <h3>
                            Collections are temporarily unavailable
                        </h3>

                        <p>
                            Sizzzlingo collections could not be loaded right now.
                        </p>

                        @if (!empty($collectionsError))
                            <pre style="white-space: pre-wrap;">{{ $collectionsError }}</pre>
                        @endif

                    </div>

                @endif

            </div>
        </section>

        {{-- ===============================================================
         Benefits
    ================================================================ --}}

        <section class="sz-section sz-benefits-section">

            <div class="sz-container sz-benefits-grid">

                <div class="sz-benefits-image">

                    <video class="sz-benefits-video" autoplay muted loop playsinline preload="metadata"
                        poster="{{ asset('uploads/page/sizzlingo/benefits-meal-FbrF5BY_.jpg') }}"
                        aria-label="Preparing a fresh halal ready meal">
                        <source src="https://cdn.shopify.com/videos/c/o/v/a1af882af9364a0485c90254e4e3949d.mp4"
                            type="video/mp4">

                        Your browser does not support the video tag.
                    </video>
                    <div class="sz-image-badge"> <i class="fas fa-certificate"></i> Chef-Prepared. Halal Frozen Meals.
                    </div>


                </div>

                <div class="sz-benefits-content">

                    <span class="sz-eyebrow">The benefits</span>

                    <h2>Real food. Real time saved.</h2>

                    <p class="sz-benefits-intro">
                        SizZzlingo Express fits mealtimes back into your day without compromising on taste, nutrition or
                        your values.
                    </p>

                    <ul class="sz-benefits-list">
                        <li><i class="fas fa-check"></i> Save Time</li>
                        <li><i class="fas fa-check"></i> No Cooking Required</li>
                        <li><i class="fas fa-check"></i> Fresh Halal Meals</li>
                        <li><i class="fas fa-check"></i> Perfect for Busy Professionals</li>
                        <li><i class="fas fa-check"></i> Great for Families</li>
                        <li><i class="fas fa-check"></i> Affordable</li>
                        <li><i class="fas fa-check"></i> Healthy Portion Control</li>
                    </ul>

                    <a href="#sz-collections" class="sz-button sz-button-primary">
                        Explore the Menu
                        <i class="fas fa-arrow-right"></i>
                    </a>

                </div>

            </div>
        </section>

        {{-- ===============================================================
         Comparison
    ================================================================ --}}

        <section id="sz-comparison" class="sz-section sz-comparison-section">
            <div class="sz-container">

                <div class="sz-section-heading">
                    <span class="sz-eyebrow">Comparison</span>

                    <h2>Why PocketThrift Recommends SizZzlingo Express</h2>

                    <p>A quick look at how it compares with cooking yourself.
                    </p>
                </div>

                <div class="sz-table-wrapper">

                    <table class="sz-comparison-table">
                        <thead>
                            <tr>
                                <th>Compare</th>
                                <th>Cooking Yourself</th>
                                <th class="sz-highlight-column">
                                    <img src="{{ asset('uploads/page/sizzlingo/sizzzlingo-logo-BM0Sz1x2.png') }}"
                                        alt="" width="38" height="38">
                                    Sizzzlingo
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>Halal Certified</td>
                                <td>Depends</td>
                                <td class="sz-highlight-column">
                                    <i class="fas fa-check"></i>
                                </td>
                            </tr>

                            <tr>
                                <td>Time Required</td>
                                <td>60+ minutes</td>
                                <td class="sz-highlight-column">3–5 minutes</td>
                            </tr>

                            <tr>
                                <td>Fresh Ingredients</td>
                                <td>
                                    <i class="fas fa-check sz-table-yes"></i>
                                </td>
                                <td class="sz-highlight-column">
                                    <i class="fas fa-check"></i>
                                </td>
                            </tr>

                            <tr>
                                <td>Nutrition Balanced</td>
                                <td>Depends</td>
                                <td class="sz-highlight-column">
                                    <i class="fas fa-check"></i>
                                </td>
                            </tr>

                            <tr>
                                <td>Sydney-wide Delivery</td>
                                <td>
                                    <i class="fas fa-times sz-table-no"></i>
                                </td>
                                <td class="sz-highlight-column">
                                    <i class="fas fa-check"></i>
                                </td>
                            </tr>

                            <tr>
                                <td>Cost per Meal</td>
                                <td>$$</td>
                                <td class="sz-highlight-column">$</td>
                            </tr>

                            <tr>
                                <td>PocketThrift Deals</td>
                                <td>
                                    <i class="fas fa-times sz-table-no"></i>
                                </td>
                                <td class="sz-highlight-column">
                                    <i class="fas fa-check"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </section>

        {{-- ===============================================================
         Testimonials
    ================================================================ --}}

        <section class="sz-section sz-testimonials-section">

            <div class="sz-container">

                <div class="sz-section-heading">
                    <span class="sz-eyebrow">Customer testimonials</span>
                    <h2>Loved across Australia</h2>
                    <p>Feedback from customers who value convenience and taste.</p>
                </div>

                <div class="sz-testimonials-grid">

                    <article class="sz-testimonial-card">
                        <div class="sz-stars" aria-label="5 out of 5 stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>

                        <p>
                            “Congrats on Sizzlingo anniversary 👏 , MashAllah today as well food and ambience was amazing,
                            all the food ordered was mouth-watering from Starter Golgappy to paratha roll, flavourful beef
                            burger with ending up on Kashmiri chai everything tastes great!! . May Allah give barakah.”
                        </p>

                        <div class="sz-customer">
                            <span>T</span>
                            <div>
                                <strong>Taha Ibrahim.</strong>
                                <small>Sydney, NSW</small>
                            </div>
                        </div>
                    </article>

                    <article class="sz-testimonial-card">
                        <div class="sz-stars" aria-label="5 out of 5 stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>

                        <p>
                            “This street food cafeteria is an absolute gem! The burgers are incredibly tasty, with spices
                            that hit the spot perfectly. Special Gola is awesome!! The staff is exceptionally welcoming and
                            approachable, making the whole experience even better. The place is very tidy, and I loved
                            enjoying the soft music in the background. Price is 100% justified.
                            I will definitely back!”
                        </p>

                        <div class="sz-customer">
                            <span>A</span>
                            <div>
                                <strong>Adeel Abbas.</strong>
                                <small>Melbourne, VIC</small>
                            </div>
                        </div>
                    </article>

                    <article class="sz-testimonial-card">
                        <div class="sz-stars" aria-label="5 out of 5 stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>

                        <p>
                            “Best Pakistani style zinger burger in Australia. I have been here a few times and it never
                            disappoints. It’s crunchy, juicy and spicy. Paratha rolls are great too. I love the loaded chips
                            with cheese and hot sauce. Staff is nice and prices are reasonable. It does take some time for
                            the food to be served but it’s been worth the wait. I highly recommend this halal cafe.”
                        </p>

                        <div class="sz-customer">
                            <span>L</span>
                            <div>
                                <strong>LGV.</strong>
                                <small>Brisbane, QLD</small>
                            </div>
                        </div>
                    </article>

                </div>
            </div>
        </section>

        {{-- ===============================================================
         FAQ
    ================================================================ --}}

        <section class="sz-section sz-faq-section">

            <div class="sz-container">

                <div class="sz-section-heading">
                    <span class="sz-eyebrow">FAQ</span>
                    <h2>Everything you need to know</h2>

                    <p>
                        Common questions about ordering from SizZzlingo Express
                        through PocketThrift.
                    </p>
                </div>

                <div class="sz-faq-list">

                    <article class="sz-faq-item">
                        <button type="button" class="sz-faq-button" aria-expanded="true">
                            <span>How fresh are the meals?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>

                        <div class="sz-faq-answer is-open">
                            <p>
                                Meals are prepared fresh by their chefs as convenient halal food prep meals, ready to reheat
                                and enjoy in minutes.
                            </p>
                        </div>
                    </article>

                    <article class="sz-faq-item">
                        <button type="button" class="sz-faq-button" aria-expanded="false">
                            <span>Are the meals genuinely halal certified?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>

                        <div class="sz-faq-answer">
                            <p>
                                SizZzlingo Express states that its meals are prepared using halal-certified ingredients and
                                processes.
                            </p>
                        </div>
                    </article>

                    <article class="sz-faq-item">
                        <button type="button" class="sz-faq-button" aria-expanded="false">
                            <span>How do I heat the meals?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>

                        <div class="sz-faq-answer">
                            <p>
                                Follow the heating instructions provided on the
                                meal packaging. Many meals can be ready within
                                a few minutes.
                            </p>
                        </div>
                    </article>

                    <article class="sz-faq-item">
                        <button type="button" class="sz-faq-button" aria-expanded="false">
                            <span>Can I subscribe or place a one-off order?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>

                        <div class="sz-faq-answer">
                            <p>
                                Available purchasing and subscription options
                                are displayed directly on the SizZzlingo
                                Express website.
                            </p>
                        </div>
                    </article>

                    <article class="sz-faq-item">
                        <button type="button" class="sz-faq-button" aria-expanded="false">
                            <span>How much does it cost?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>

                        <div class="sz-faq-answer">
                            <p>
                                Pricing depends on the selected halal meals delivered, packs, and delivery location. Visit
                                the collection cards above for current pricing.
                            </p>
                        </div>
                    </article>

                    <article class="sz-faq-item">
                        <button type="button" class="sz-faq-button" aria-expanded="false">
                            <span>Does SizZzlingo Express offer halal food catering for events and large gatherings in
                                Sydney?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>

                        <div class="sz-faq-answer">
                            <p>
                                Yes, SizZzlingo Express caters to events, parties, and large gatherings across Sydney,
                                offering dedicated best halal lunch catering and online halal frozen food delivery options
                                alongside its regular halal food catering menus.
                            </p>
                        </div>
                    </article>

                    <article class="sz-faq-item">
                        <button type="button" class="sz-faq-button" aria-expanded="false">
                            <span>Are there halal weight loss meals available for people trying to manage their calorie
                                intake? </span>
                            <i class="fas fa-chevron-down"></i>
                        </button>

                        <div class="sz-faq-answer">
                            <p>
                                Yes, SizZzlingo Express offers a dedicated Weight Loss Meal Pack built around halal diet
                                food principles, featuring halal weight loss meals with controlled portions of lean protein,
                                fresh vegetables, and balanced carbs.
                            </p>
                        </div>
                    </article>

                    <article class="sz-faq-item">
                        <button type="button" class="sz-faq-button" aria-expanded="false">
                            <span>Does SizZzlingo Express offer a meal plan for muscle gain for people looking to build
                                muscle? </span>
                            <i class="fas fa-chevron-down"></i>
                        </button>

                        <div class="sz-faq-answer">
                            <p>
                                Yes, SizZzlingo Express offers a dedicated Muscle Gain Meal Pack designed as a proper meal
                                prep for muscle gain, featuring protein-rich halal chicken portions.
                            </p>
                        </div>
                    </article>

                </div>
            </div>
        </section>

        {{-- ===============================================================
         Final CTA
    ================================================================ --}}

        <section class="sz-section sz-final-cta-section">

            <div class="sz-container">

                <div class="sz-final-cta">

                    <img src="{{ asset('uploads/page/sizzlingo/sizzzlingo-logo-BM0Sz1x2.png') }}" alt=""
                        width="74" height="74" loading="lazy">

                    <h2>Ready to Skip Meal Prep?</h2>

                    <p>
                        Discover delicious halal ready made meals while saving with PocketThrift.
                    </p>

                    <div class="sz-final-actions">

                        <a href="#sz-collections" class="sz-button sz-button-light">
                            Explore Meals
                            <i class="fas fa-arrow-right"></i>
                        </a>

                        <a href="{{ $sizzlingoBaseUrl }}" target="_blank" rel="nofollow sponsored noopener"
                            class="sz-button sz-button-dark-outline">
                            View Latest Deals
                        </a>

                    </div>

                    <small>
                        PocketThrift may earn a commission when you purchase through affiliate links.
                    </small>

                </div>
            </div>
        </section>

        {{-- ===============================================================
         Other brands
    ================================================================ --}}

        <section class="sz-section sz-brands-section">

            <div class="sz-container">

                <div class="sz-section-heading">
                    <span class="sz-eyebrow">Explore more</span>

                    <h2>
                        Discover other trusted food and lifestyle brands on
                        PocketThrift
                    </h2>

                    <p>
                        Handpicked Australian stores, exclusive deals and
                        cashback opportunities — all in one place.
                    </p>
                </div>

                <div class="sz-brand-chips">
                    <span>Hungry Jack's</span>
                    <span>Bonny</span>
                    <span>Life Cykel</span>
                    <span>Laser Clinics</span>
                    <span>Nisbets</span>
                    <span>Puma</span>
                </div>

                <a href="{{ route('region.stores', ['region' => 'au']) }}" class="sz-button sz-button-primary">
                    Back to PocketThrift
                    <i class="fas fa-arrow-right"></i>
                </a>

            </div>
        </section>




        {{-- ===============================================================
         Blogs Section
        ================================================================ --}}
        @if (isset($sizzlingoBlogs) && $sizzlingoBlogs->isNotEmpty())

            <section class="sz-blog-section" id="sizzlingo-blogs">
                <div class="container">

                    <div class="sz-blog-heading">
                        <span>Latest Articles</span>
                        <h2>Insights from SizZzlingo Express</h2>

                        <p>
                            Discover helpful guides, meal recommendations and insights
                            about halal ready-to-eat meals in Sydney.
                        </p>
                    </div>

                    <div class="sz-blog-grid">

                        @foreach ($sizzlingoBlogs as $blog)
                            @php
                                $blogSlug = ltrim($blog->url_slug, '/');

                                $blogUrl = route('region.blog.detail', [
                                    'region' => $regionCode ?? 'au',
                                    'slug' => $blogSlug,
                                ]);

                                $blogImage = !empty($blog->logo)
                                    ? asset('uploads/' . ltrim($blog->logo, '/'))
                                    : asset('uploads/page/sizzlingo/sizzlingo-logo.jpg');

                                $description = $blog->short_description ?: $blog->meta_description;
                            @endphp

                            <article class="sz-blog-card">

                                <a href="{{ $blogUrl }}" class="sz-blog-image" aria-label="{{ $blog->title }}">
                                    <img src="{{ $blogImage }}" alt="{{ $blog->image_alt ?: $blog->title }}"
                                        title="{{ $blog->image_title ?: $blog->title }}" width="640" height="360"
                                        loading="lazy">
                                </a>

                                <div class="sz-blog-category">
                                    {{ optional($blog->category)->title ?: 'Food And Beverage' }}
                                </div>

                                <div class="sz-blog-content">

                                    <h3>
                                        <a href="{{ $blogUrl }}">
                                            {{ $blog->title }}
                                        </a>
                                    </h3>

                                    @if (!empty($description))
                                        <p>
                                            {{ \Illuminate\Support\Str::limit(strip_tags($description), 120) }}
                                        </p>
                                    @endif

                                    <a href="{{ $blogUrl }}" class="sz-blog-read-more">
                                        Read More
                                    </a>

                                </div>

                            </article>
                        @endforeach

                    </div>

                </div>
            </section>

        @endif


    </main>

@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqButtons = document.querySelectorAll('.sz-faq-button');

            faqButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const currentItem = button.closest('.sz-faq-item');
                    const currentAnswer = currentItem.querySelector('.sz-faq-answer');
                    const currentlyExpanded =
                        button.getAttribute('aria-expanded') === 'true';

                    document.querySelectorAll('.sz-faq-item').forEach(function(item) {
                        const itemButton = item.querySelector('.sz-faq-button');
                        const itemAnswer = item.querySelector('.sz-faq-answer');

                        itemButton.setAttribute('aria-expanded', 'false');
                        itemAnswer.classList.remove('is-open');
                    });

                    if (!currentlyExpanded) {
                        button.setAttribute('aria-expanded', 'true');
                        currentAnswer.classList.add('is-open');
                    }
                });
            });
        });

        /* =========================================================
           SIZZLINGO PREMIUM OFFER BANNER
        ========================================================= */
        document.addEventListener('DOMContentLoaded', function() {
            const copyButtons = document.querySelectorAll('[data-sz-copy-code]');

            copyButtons.forEach(function(button) {
                button.addEventListener('click', async function() {
                    const code = button.dataset.szCopyCode;
                    const icon = button.querySelector('i');
                    const helperText = button.parentElement.querySelector(
                        '.sz-ticket-copy-text'
                    );

                    try {
                        await navigator.clipboard.writeText(code);
                    } catch (error) {
                        const input = document.createElement('input');

                        input.value = code;
                        document.body.appendChild(input);
                        input.select();
                        document.execCommand('copy');
                        input.remove();
                    }

                    button.classList.add('is-copied');

                    if (icon) {
                        icon.className = 'fas fa-check';
                    }

                    if (helperText) {
                        helperText.textContent = 'Discount code copied!';
                    }

                    setTimeout(function() {
                        button.classList.remove('is-copied');

                        if (icon) {
                            icon.className = 'far fa-copy';
                        }

                        if (helperText) {
                            helperText.textContent = 'Click the code to copy';
                        }
                    }, 2200);
                });
            });
        });


        /* =========================================================
              Copy Code From Offers
            ========================================================= */
        document.addEventListener('DOMContentLoaded', function() {
            const copyButtons = document.querySelectorAll(
                '.js-szx-copy-offer'
            );

            copyButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const couponCode =
                        button.getAttribute('data-code') || '';

                    const destinationUrl =
                        button.getAttribute('data-url') || '';

                    const textElement = button.querySelector(
                        '.js-szx-button-text'
                    );

                    if (!couponCode) {
                        if (destinationUrl) {
                            window.location.href = destinationUrl;
                        }

                        return;
                    }

                    button.disabled = true;

                    copyCouponCode(couponCode)
                        .then(function() {
                            button.classList.add('is-copied');

                            if (textElement) {
                                textElement.textContent =
                                    'Code Copied!';
                            }

                            /*
                             * Small delay so the visitor can see
                             * that the coupon has been copied.
                             */
                            setTimeout(function() {
                                if (destinationUrl) {
                                    window.location.href =
                                        destinationUrl;
                                }
                            }, 700);
                        })
                        .catch(function() {
                            if (textElement) {
                                textElement.textContent =
                                    'Continue to Offer';
                            }

                            button.disabled = false;

                            if (destinationUrl) {
                                window.location.href =
                                    destinationUrl;
                            }
                        });
                });
            });

            function copyCouponCode(text) {
                if (
                    navigator.clipboard &&
                    window.isSecureContext
                ) {
                    return navigator.clipboard.writeText(text);
                }

                return new Promise(function(resolve, reject) {
                    const textarea = document.createElement(
                        'textarea'
                    );

                    textarea.value = text;
                    textarea.setAttribute('readonly', '');

                    textarea.style.position = 'fixed';
                    textarea.style.top = '-9999px';
                    textarea.style.left = '-9999px';
                    textarea.style.opacity = '0';

                    document.body.appendChild(textarea);

                    textarea.focus();
                    textarea.select();

                    try {
                        const copied = document.execCommand(
                            'copy'
                        );

                        document.body.removeChild(textarea);

                        if (copied) {
                            resolve();
                        } else {
                            reject(
                                new Error(
                                    'Coupon code could not be copied.'
                                )
                            );
                        }
                    } catch (error) {
                        document.body.removeChild(textarea);
                        reject(error);
                    }
                });
            }
        });
    </script>
@endpush
