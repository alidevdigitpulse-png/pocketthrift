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

            <img src="{{ asset('images/sizzlingo/hero-meal.webp') }}" alt="Chef-prepared halal ready meal"
                class="sz-hero-background" width="1920" height="1000" loading="eager" fetchpriority="high">

            <div class="sz-hero-overlay"></div>

            <div class="sz-container sz-hero-inner">

                <div class="sz-partner-pill">
                    <i class="fas fa-handshake" aria-hidden="true"></i>
                    PocketThrift Featured Partner · Sizzzlingo
                </div>

                <h1>
                    Healthy Halal Meals,
                    <span>Delivered</span>
                    Across Australia
                </h1>

                <p class="sz-hero-description">
                    Chef-prepared, ready-to-eat halal meals from Sizzzlingo
                    Express — recommended by PocketThrift and delivered chilled
                    to your door.
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
                        <i class="fas fa-certificate"></i>
                        100% Halal Certified
                    </span>

                    <span>
                        <i class="fas fa-clock"></i>
                        Ready in 3 Minutes
                    </span>

                    <span>
                        <i class="fas fa-truck"></i>
                        Australia-wide Delivery
                    </span>
                </div>

                <div class="sz-halal-badge">

                    <img src="{{ asset('uploads/page/sizzlingo/sizzzlingo-logo-BM0Sz1x2.png') }}" alt="Halal certification"
                        width="42" height="42" loading="eager">

                    <span>
                        <small>Featured Partner</small>
                        <strong>Sizzzlingo Express</strong>
                    </span>
                </div>

            </div>
        </section>

        {{-- ===============================================================
         Trust strip
    ================================================================ --}}

        <section class="sz-trust-strip" aria-label="Sizzzlingo benefits">
            <div class="sz-container sz-trust-grid">

                <div class="sz-trust-item">
                    <i class="fas fa-certificate"></i>
                    <span>Halal Certified</span>
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
                    <span>Family Meals</span>
                </div>

                <div class="sz-trust-item">
                    <i class="fas fa-truck"></i>
                    <span>Australia Delivery</span>
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

                    <h2>Why Choose Sizzzlingo Express?</h2>

                    <p>
                        A partner brand we trust to deliver on taste,
                        convenience and value for money.
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

                        <h3>Authentic Halal Meals</h3>

                        <p>
                            Every dish is prepared to strict halal standards
                            using certified ingredients.
                        </p>
                    </article>

                    <article class="sz-reason-card">
                        <span class="sz-icon-box">
                            <i class="fas fa-utensils"></i>
                        </span>

                        <h3>Ready-to-Eat Convenience</h3>

                        <p>
                            Skip the preparation. Heat, plate and enjoy
                            chef-quality meals in minutes.
                        </p>
                    </article>

                    <article class="sz-reason-card">
                        <span class="sz-icon-box">
                            <i class="fas fa-leaf"></i>
                        </span>

                        <h3>Fresh Ingredients</h3>

                        <p>
                            Real produce, real proteins and full flavour,
                            without over-processing.
                        </p>
                    </article>

                    <article class="sz-reason-card">
                        <span class="sz-icon-box">
                            <i class="fas fa-wallet"></i>
                        </span>

                        <h3>Budget Friendly</h3>

                        <p>
                            Meal plans priced to beat takeaway, with
                            PocketThrift savings on top.
                        </p>
                    </article>

                    <article class="sz-reason-card">
                        <span class="sz-icon-box">
                            <i class="fas fa-users"></i>
                        </span>

                        <h3>Family Meals</h3>

                        <p>
                            Generous family packs designed to keep the whole
                            table happy.
                        </p>
                    </article>

                    <article class="sz-reason-card">
                        <span class="sz-icon-box">
                            <i class="fas fa-truck"></i>
                        </span>

                        <h3>Nationwide Delivery</h3>

                        <p>
                            Chilled shipping to supported suburbs, cities,
                            states and territories.
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
                    <p>A simple, no-fuss experience designed for busy Australians.</p>
                </div>

                <div class="sz-steps-grid">

                    <article class="sz-step-card">
                        <span class="sz-step-label">Step 1</span>

                        <span class="sz-step-icon">
                            <i class="fas fa-search"></i>
                        </span>

                        <h3>Browse Meals</h3>
                        <p>Explore chef-designed halal meals.</p>
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
                        <p>Receive chilled meals at your door.</p>
                    </article>

                    <article class="sz-step-card">
                        <span class="sz-step-label">Step 4</span>

                        <span class="sz-step-icon">
                            <i class="fas fa-fire"></i>
                        </span>

                        <h3>Heat & Enjoy</h3>
                        <p>Your meal is ready in about three minutes.</p>
                    </article>

                </div>
            </div>
        </section>

        {{-- ===============================================================
         Dynamic collections
    ================================================================ --}}

        <section id="sz-collections" class="sz-section sz-collections-section">
            <div class="sz-container">

                <div class="sz-section-heading">
                    <span class="sz-eyebrow">Featured categories</span>
                    <h2>Something for every appetite</h2>

                    <p>
                        From high-protein lunches to family-size dinners —
                        all halal and ready in minutes.
                    </p>
                </div>
                @if ($collections->isNotEmpty())

                    <div class="sz-collections-grid">

                        @foreach ($collections as $collection)
                            <a href="{{ $collection['url'] }}" target="_blank" rel="nofollow sponsored noopener"
                                class="sz-collection-card">
                                <div class="sz-collection-image">
                                    <img src="{{ $collection['image'] ?: asset('images/sizzlingo/collection-placeholder.webp') }}"
                                        alt="{{ $collection['image_alt'] }}" width="700" height="500"
                                        loading="lazy">
                                </div>

                                <div class="sz-collection-content">
                                    <h3>{{ $collection['title'] }}</h3>

                                    <p>{{ $collection['description'] }}</p>

                                    <span class="sz-collection-arrow">
                                        <i class="fas fa-arrow-right"></i>
                                    </span>
                                </div>
                            </a>
                        @endforeach

                    </div>
                @else
                    <div class="sz-api-message">
                        <h3>Collections are temporarily unavailable</h3>

                        <p>
                            Sizzzlingo collections could not be loaded right now.
                        </p>

                        @if (!empty($collectionsError))
                            <pre style="white-space: pre-wrap;">
                {{ $collectionsError }}
            </pre>
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
                    <img src="{{ asset('/uploads/page/sizzlingo/benefits-meal-FbrF5BY_.jpg') }}" alt="Preparing a fresh halal ready meal"
                        width="900" height="900" loading="lazy">

                    <div class="sz-image-badge">
                        <i class="fas fa-certificate"></i>
                        Chef-prepared. Never frozen.
                    </div>

                </div>

                <div class="sz-benefits-content">

                    <span class="sz-eyebrow">The benefits</span>

                    <h2>Real food. Real time saved.</h2>

                    <p class="sz-benefits-intro">
                        Sizzzlingo Express fits mealtimes back into your day
                        without compromising on taste, nutrition or your values.
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

                    <h2>Why PocketThrift Recommends Sizzzlingo Express</h2>

                    <p>A quick look at how it compares with cooking yourself.</p>
                </div>

                <div class="sz-table-wrapper">

                    <table class="sz-comparison-table">
                        <thead>
                            <tr>
                                <th>Compare</th>
                                <th>Cooking Yourself</th>
                                <th class="sz-highlight-column">
                                    <img src="{{ asset('images/sizzlingo/halal-logo.webp') }}" alt=""
                                        width="38" height="38">
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
                                <td>Nationwide Delivery</td>
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
                            “Weeknights used to be chaos. Now the kids eat halal
                            home-quality meals in minutes.”
                        </p>

                        <div class="sz-customer">
                            <span>A</span>
                            <div>
                                <strong>Aisha R.</strong>
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
                            “The portions are generous and the meals taste
                            genuinely fresh. Great after a long workday.”
                        </p>

                        <div class="sz-customer">
                            <span>M</span>
                            <div>
                                <strong>Mohammed K.</strong>
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
                            “A convenient option for busy weeks. Delivery was
                            chilled and everything was easy to prepare.”
                        </p>

                        <div class="sz-customer">
                            <span>S</span>
                            <div>
                                <strong>Sarah L.</strong>
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
                        Common questions about ordering from Sizzzlingo Express
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
                                Meals are chef-prepared, chilled and packaged
                                for convenient reheating. Storage instructions
                                are supplied with each order.
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
                                Sizzzlingo Express states that its meals are
                                prepared using halal-certified ingredients and
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
                                are displayed directly on the Sizzzlingo
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
                                Pricing depends on the selected meals, packs
                                and delivery location. Visit the collection
                                cards above for current pricing.
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

                    <img src="{{ asset('images/sizzlingo/halal-logo.webp') }}" alt="" width="74"
                        height="74" loading="lazy">

                    <h2>Ready to Skip Meal Prep?</h2>

                    <p>
                        Discover delicious halal ready meals while saving with
                        PocketThrift.
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
                        PocketThrift may earn a commission when you purchase
                        through affiliate links.
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
    </script>
@endpush
