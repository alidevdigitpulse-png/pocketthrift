@extends('layouts.app')
@section('title', $store->seo_title . ', ' . __(date('F')) . ' ' . date('Y'))
@section('meta_description', $store->meta_description)
@push('schemas')
    <meta name="robots" content="{{ $store->meta_robots }}">

    {{-- Open Graph Meta Tags --}}
    <meta property="og:title" content="{{ $store->seo_title . ', ' . __(date('F')) . ' ' . date('Y') }}">
    <meta property="og:description" content="{{ $store->meta_description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    @if ($store->logo)
        <meta property="og:image" content="{{ asset('uploads/' . $store->logo) }}">
    @else
        <meta property="og:image" content="https://pocketthrift.com/images/og-image.webp">
    @endif
    <meta property="og:image:alt" content="{{ $store->title }}">
    <meta property="og:site_name" content="PocketThrift">

    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $store->twitter_title . ', ' . __(date('F')) . ' ' . date('Y') }}">
    <meta name="twitter:description" content="{{ $store->meta_description }}">
    @if ($store->logo)
        <meta name="twitter:image" content="{{ asset('uploads/' . $store->logo) }}">
    @else
        <meta name="twitter:image" content="https://pocketthrift.com/images/og-image.webp">
    @endif
    <meta name="twitter:image:alt" content="{{ $store->title }}">

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
            'at' => 'Austria',
        ];

        $regionName = $regions[$regionCode] ?? strtoupper($regionCode);
        $baseUrl = rtrim(config('app.url', 'https://pocketthrift.com'), '/');

        // SITE URL ALWAYS REGIONIZED
        $siteUrl = $regionCode === 'us' ? $baseUrl : $baseUrl . '/' . $regionCode;

        // Get store name from page or slug
        $storeName = $store->name ?? ($page->name ?? '');
        $storeName = preg_replace('/-' . $regionCode . '$/i', '', $storeName);
        $storeName = trim($storeName);

        // Build title and description
        $title = $meta['title'] ?? ($page->meta_title ?? $storeName);
        $description = $meta['description'] ?? ($page->meta_description ?? null);

        // Replace region placeholders
        $title = str_replace(
            ['$regionName', '$regionCode', '$storeName'],
            [$regionName, $regionCode, $storeName],
            $title,
        );
        $description = str_replace(
            ['$regionName', '$regionCode', '$storeName'],
            [$regionName, $regionCode, $storeName],
            $description,
        );

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
            ['name' => __('Home'), 'url' => $siteUrl],
            ['name' => __('Stores'), 'url' => $storesUrl],
            ['name' => $storeName, 'url' => $fullUrl],
        ];

        // Force correct URLs
        $breadcrumbs[0]['url'] = $siteUrl;
        $breadcrumbs[1]['url'] = $storesUrl;
    @endphp

    @php
        // WebPage Schema - Use the same values as the meta tags for consistency
        // Use the exact same values as @section('title') and @section('meta_description')
        $webPageTitle =
            $store->seo_title ?? (!empty($store->title) ? $store->title . ' - Coupons & Promo Codes' : 'Store Details');
        $webPageDescription =
            $store->meta_description ??
            (!empty($store->description)
                ? $store->description
                : (!empty($store->title)
                    ? 'Find the latest ' .
                        $store->title .
                        ' coupons, promo codes, and deals. Save money with our verified offers.'
                    : 'Store details and offers'));

        $webpage = [
            '@context' => 'https://schema.org/',
            '@type' => 'WebPage',
            'name' => $webPageTitle . ', ' . date('F Y'),
            'description' => $webPageDescription,
            'url' => $fullUrl,
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'PocketThrift',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $baseUrl . '/images/og-image.webp',
                ],
            ],
        ];

        // Breadcrumb Schema - Ensure proper names and URLs for each breadcrumb item
        $crumbItems = [];
        $pos = 1;

        foreach ($breadcrumbs as $b) {
            // Get the name and URL from the breadcrumb
            $name = isset($b['name']) ? $b['name'] : '';
            $url = isset($b['url']) ? $b['url'] : '';

            // Special handling for each position
            if ($pos === 1) {
                // First breadcrumb (Home) - ensure name and URL are not empty
                $name = !empty($name) && trim($name) !== '' ? $name : 'Home';
                // Get the route to the appropriate home page based on region
                $homeUrl = $regionCode === 'us' ? route('home') : route('region.home', ['region' => $regionCode]);
                // Ensure the URL is always set to the home URL, with fallback to app URL
                $url = !empty($homeUrl) ? $homeUrl : rtrim(config('app.url', 'https://pocketthrift.com'), '/');
            } elseif ($pos === 2) {
                // Second breadcrumb (Stores) - ensure name and URL are not empty
                $name = !empty($name) && trim($name) !== '' ? $name : 'Stores';
                // Ensure the URL is always set to the stores URL for the Stores breadcrumb
                $url = !empty($storesUrl)
                    ? $storesUrl
                    : rtrim(config('app.url', 'https://pocketthrift.com'), '/') . '/stores/';
            } elseif ($pos === 3) {
                // Third breadcrumb (Store Name) - special handling
                if ((empty($name) || trim($name) === '') && !empty($store->title)) {
                    $name = $store->title;
                } elseif (empty($name) || trim($name) === '') {
                    $name = 'Store';
                }
                $url = !empty($url) && trim($url) !== '' ? $url : $fullUrl;
            } else {
                // Any other positions
                if (empty($name) || trim($name) === '') {
                    $name = 'Untitled';
                }
                if (empty($url) || trim($url) === '') {
                    $url = '#';
                }
            }

            $crumbItems[] = [
                '@type' => 'ListItem',
                'position' => $pos++,
                'name' => $name,
                'item' => $url,
            ];
        }

        $breadcrumbSchema = [
            '@context' => 'https://schema.org/',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $crumbItems,
        ];

        // FAQ Schema (if FAQs exist)
        $faqSchema = null;
        if (isset($faqs) && $faqs->isNotEmpty()) {
            $faqItems = [];

            foreach ($faqs as $faq) {
                $faqItems[] = [
                    '@type' => 'Question',
                    'name' => $faq->question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq->answer,
                    ],
                ];
            }

            if (!empty($faqItems)) {
                $faqSchema = [
                    '@context' => 'https://schema.org/',
                    '@type' => 'FAQPage',
                    'mainEntity' => $faqItems,
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

    @if ($faqSchema)
        <script type="application/ld+json">
{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>
    @endif
@endpush

@section('content')
    <style>
        @media (min-width: 992px) {
            .modal-content {
                min-width: 600px;
            }
        }

        p {
            font-size: 14px;
        }

        span.offers-text {
            text-align: center;
        }

        .offer-action p {
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
            width: 100%;
        }

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
            border: 1px dashed #cf5103;
            border-radius: .25rem;
            padding: .5rem;
        }

        /* Make both buttons have the same width */
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

        .coupon-card .store-logo img {
            max-width: 100%;
            max-height: 100%;
        }

        .coupon-card .offer-details h3 {
            font-size: 16px;
            font-weight: 600;
            margin: 5px 0;
        }

        .container>.row>.col-4 img {
            height: 140px !important;
        }

        @media (max-width: 767.98px) {
            .coupon-card .offer-details h3 {
                font-size: 14px;
            }
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

        .deal-button:hover,
        .reveal-code-button:hover {
            background-color: #1a2043;
            transition: all .5s ease-in-out;
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

        .con-det h3 {
            font-size: 14px;
            font-weight: 400;
            line-height: 21px;
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
            background-color: #cf5103;
            color: white;
        }

        .coupon-modal .modal-content {
            border-radius: 0.5rem;
            border: 2px dashed #cf5103;
            overflow: hidden;
        }

        .coupon-modal .btn-close {
            background-color: #cf5103 !important;
            border-radius: 50% !important;
            opacity: 1 !important;
            color: white !important;
            /* Set the icon color to white */
            padding: 5px !important;
            box-shadow: none !important;
            --bs-btn-close-bg: none;
            --bs-btn-close-color: #fff;
            /* Ensure the SVG icon color is white */
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

        .detail-section.mt-3 {
            display: none !important;
        }

        .coupon-modal .detail-section ul {
            padding-left: 1.4rem;
            margin-bottom: 0;
            list-style-type: disc;
        }

        a.follow-icons {
            font-size: 30px;
        }

        @media (max-width: 767.98px) {
            .container>.row>.col-4 img {
                height: 80px !important;
            }

            /* Reduce card padding and margins */
            .coupon-card {
                padding: 10px 8px;
                margin-bottom: 8px;
            }

            .coupon-card .row>[class*="col-"] {
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
            .coupon-card .row>div:nth-child(1) {
                flex: 0 0 30% !important;
                max-width: 30% !important;
                padding-right: 5px;
            }

            /* Title column - center (2nd col) */
            .coupon-card .row>div:nth-child(2) {
                flex: 0 0 70% !important;
                max-width: 70% !important;
                padding-left: 5px;
                padding-right: 5px;
            }

            /* Button column - bottom (3rd col) */
            .coupon-card .row>div:nth-child(3) {
                flex: 0 0 100% !important;
                max-width: 100% !important;
                margin-top: 10px;
                padding-left: 0;
                padding-right: 0;
                text-align: center;
            }

            /* Adjust discount text size and spacing */
            .coupon-card .offer-action .offers-text {
                font-size: 15px !important;
                /* Set to 15px */
                line-height: 1.1 !important;

            }

            .coupon-card .offer-action p {
                font-size: 15px !important;
                /* Set to 15px */
                margin-bottom: 0 !important;
                line-height: 1.2 !important;
                width: 100% !important;
                max-width: 100% !important;
                margin-left: 4px !important;
                margin-right: 0 !important;
                padding-left: 2px !important;
                padding-right: 2px !important;
            }

            /* Fix icon display in discount box on mobile */
            .coupon-card .offer-action p i {
                font-size: 18px !important;
                margin-bottom: 2px;
            }



            /* Adjust title size and spacing */
            .coupon-card .offer-details h3,
            .coupon-card h3 {
                font-size: 14px !important;
                /* Increased from 11px to 14px */
                margin-top: 5px !important;
                margin-bottom: 3px !important;
                line-height: 1.3 !important;
                margin-right: 25px;
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
                /* Reduced padding to fit */
                font-size: 13px;
                /* Slightly smaller text */
                white-space: nowrap;
            }

            .d-flex.gap-3.align-items-center.mb-4 {
                flex-wrap: nowrap !important;
                /* Force single line */
                gap: 5px !important;
                /* Reduce gap */
                overflow-x: auto;
                /* Allow scrolling if needed, though flex-grow should handle it */
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

            .discount-banner h3 {
                font-size: 1.3rem !important;
            }

            .discount-banner p {
                font-size: 0.9rem !important;
            }

            .coupon-modal .coupon-code-container .d-flex {
                flex-direction: column;
            }

            .coupon-modal .copy-code-btn {
                width: 100%;
                margin-top: 0.5rem;
                margin-left: 0 !important;
            }

            .modal-body-content {
                padding: 0 1rem 1rem 1rem !important;
            }

            .coupon-modal .go-to-website a {
                padding: 0.75rem !important;
                font-size: 0.9rem;
            }
        }

        .coupon-modal .coupon-code {
            font-size: 1.4rem;
            letter-spacing: 1px;
        }

        @media (max-width: 767.98px) {

            /* Button styling for mobile */
            .coupon-card .reveal-code-button,
            .coupon-card .deal-button {
                margin-right: 4px !important;
                margin-left: 10px !important;
                font-size: 14px;
                width: calc(100% - 20px) !important;
                /* specific width to account for margins */
                min-width: unset !important;
                max-width: unset !important;
                display: block;
                padding: 10px 4px;
                line-height: 1.2;
                white-space: normal;
                word-wrap: break-word;
                border-radius: 5px;
                /* Slight radius */
            }

            /* Show line breaks on mobile - text in two lines */
            .coupon-card .reveal-code-button br,
            .coupon-card .deal-button br {
                display: none !important;
                /* Changed to none for single line */
            }

            /* Offer meta text size */
            .coupon-card .offer-meta span {
                font-size: 12px !important;
                /* Increased by 4px (from 8px) */
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
                font-size: 12px !important;
                /* Increased by ~4px */
            }

            /* Hide separator pipes on mobile to save space */
            .coupon-card .offer-meta .mx-2 {
                display: none !important;
            }

            /* Sidebar Reordering for Mobile */
            .mobile-sidebar-flex {
                display: flex;
                flex-direction: column;
            }

            .mobile-order-first {
                order: -1;
            }

        }



        /* About Store Content Styling (match Blog) */
        .about-store-content {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #2d3748;
            background-color: #fff;
        }

        .about-store-content p {
            font-size: 1.125rem !important;
            /* 18px */
            line-height: 1.9 !important;
            color: #4a5568;
            margin-bottom: 1.75rem;
        }

        .about-store-content h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            margin-top: 2rem;
            margin-bottom: 1.25rem;
            border-left: 5px solid #cf5103;
            padding-left: 1rem;
        }

        .about-store-content h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3748;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }

        .about-store-content ul {
            margin-bottom: 1.75rem;
            padding-left: 2rem;
            list-style-type: disc;
        }

        .about-store-content li {
            font-size: 1.125rem;
            color: #4a5568;
            margin-bottom: 0.75rem;
            line-height: 1.7;
        }

        .about-store-content table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
        }

        .about-store-content table th,
        .about-store-content table td {
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
    </style>

    <div class="container py-3 px-md-2 px-3">
        <div class="row">
            <div class="col-4 col-lg-3 col-md-4 my-auto">
                <div class="d-block cursor-pointer">
                    @if ($store->logo)
                        <img src="{{ asset('uploads/' . $store->logo) }}"
                            style="object-fit: contain; border:1px dashed #cf5103; width:100%; border-radius:10px;"
                            alt="{{ $store->title }} Logo">
                    @else
                        <div class="mx-auto border border-secondary border-dashed rounded-md w-100 d-flex align-items-center justify-content-center"
                            style="object-fit: contain; border:1px dashed #cf5103; width:100%; border-radius:10px;">
                            <span class="text-muted">{{ $store->title }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-8 col-lg-9 col-md-8 my-auto text-center text-md-start">
                <div class="mt-3 mt-md-0">
                    <div class="">
                        <h1 class="h2 mb-1 resp-h1" style="font-size:24px;font-weight:600;">{{ $store->title_h1 }},
                            {{ date('Y') }}</h1>
                        <h2 class="mb-2 resp-h2" style="font-size:16px;font-weight:500;color:#cf5103;">
                            {{ $store->offers->count() }} {!! $store->subtitle_h2 !!} - {{ __(date('F')) }},
                            {{ date('Y') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-3 px-md-2 px-3">
        <div class="row">
            <div class="col-lg-3 col-md-4 sidebar order-2 order-md-1 mobile-sidebar-flex">
                <div class="widget">
                    <div class="text-center mb-4">
                        <p> {{ __('We may earn a commission if you make a purchase through our links') }}</p>
                        <a href="{{ $regionCode === 'us' || !$regionCode ? route('home') : route('region.home', ['region' => $regionCode]) }}"
                            class="text-decoration-none">
                            <div class="comission-btn">
                                {{ __('We May Earn a Commission') }}
                            </div>
                        </a>
                    </div>
                </div>

                <div class="widget">
                    <h3 class="widget-title">{{ __('Why Trust Us') }}</h3>
                    <p>
                        {{ __('Pocketthrift has a merchandising team sourcing and verifying the best :store_title coupons, promo codes, and deals so you can save money and time while shopping. Our deal hunters are constantly researching the market in real time to bring you the latest savings insights, the best stores to shop, and top product recommendations. No matter where you shop, you can trust Pocketthrift to deliver reliable, vetted coupons, promo codes, and exclusive sales. Our team last verified offers for :store_title on :date', ['store_title' => $store->title, 'date' => __(date('F')) . ', ' . date('Y')]) }}
                    </p>
                </div>

                <div class="widget widget-coupon">
                    <h3 class="widget-title ">{{ __('Coupon Info') }}</h3>
                    <ul>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Number of Deal:') }}
                            <span class="badge">{{ $offers->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Coupons:') }}
                            <span class="badge">{{ $offers->where('type', 'Code')->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Offers:') }}
                            <span class="badge">{{ $offers->where('type', '!=', 'Code')->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Verified:') }}
                            <span class="badge">{{ $offers->where('verified', '!=', 'active')->count() }}</span>
                        </li>
                    </ul>
                </div>

                <div class="widget">
                    <h3 class="widget-title">{{ __('Last Updated') }}</h3>
                    <div class="mt-3">
                        <p class="text-sm border rounded-md bg-light p-2">
                            <span class="fw-medium text-secondary">{{ $store->title }}</span>
                            {{ __('Coupons and Promo Codes last updated on') }} <span>{{ __(date('F')) }},
                                {{ date('Y') }}</span>
                        </p>
                    </div>
                </div>

                <!-- <div class="widget widget-content">
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
                    </div> -->

                <div class="widget more-stores-widget">
                    <h3 class="widget-title">{{ __('More Stores') }}</h3>
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

                        @foreach ($otherStores as $otherStore)
                            <li>
                                <a href="{{ $regionCode === 'us' || !$regionCode ? route('store.detail', ltrim($otherStore->url_slug, '/')) : route('region.store.detail', ['region' => $regionCode, 'store' => ltrim($otherStore->url_slug, '/')]) }}"
                                    class="text-sm text-decoration-none fw-medium">
                                    {{ $otherStore->title }}
                                    <span class="text-xs">({{ $otherStore->offers()->count() }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="widget">
                    <h3 class="widget-title">{{ __('Trending Stores') }}</h3>
                    @php
                        // Fetch trending stores from the current region, ordered by the number of active offers
                        $trendingStores = \App\Models\Store::where('active', true)
                            ->byRegionCodes([$currentRegion]) // Filter by current region
                            ->withCount('offers') // Count related offers
                            ->orderByDesc('offers_count') // Order by offer count
                            ->take(10) // Limit to top 10
                            ->get(); // Execute the query
                    @endphp
                    @foreach ($trendingStores as $trendingStore)
                        <div class="mb-3">
                            <a href="{{ $regionCode === 'us' || !$regionCode ? route('store.detail', ltrim($trendingStore->url_slug, '/')) : route('region.store.detail', ['region' => $regionCode, 'store' => ltrim($trendingStore->url_slug, '/')]) }}"
                                class="text-sm text-decoration-none">
                                <div class="d-flex gap-3 align-items-center mb-3">
                                    <div
                                        class="rounded bg-white shadow p-2 w-44 h-44 d-flex align-items-center justify-content-center">
                                        @if ($trendingStore->logo)
                                            <img src="{{ asset('uploads/' . $trendingStore->logo) }}" class="w-100"
                                                alt="{{ $trendingStore->title }} Logo" style="max-height: 44px;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center"
                                                style="height: 44px;">
                                                <span class="text-muted">{{ substr($trendingStore->title, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="">
                                        <div class="trending-store-title">{{ $trendingStore->title }}
                                            {{ __('Coupon Code') }}</div>
                                        <div class="trending-store-data">
                                            {{ $trendingStore->offers->where('type', 'Code')->count() }}
                                            {{ __('Coupons') }} &
                                            {{ $trendingStore->offers->where('type', '!=', 'Code')->count() }}
                                            {{ __('Offers') }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>



                @if ($store->contact_details && trim(strip_tags($store->contact_details)) !== '')
                    <div id="" class="mt-4 mobile-order-first">
                        <h3 class="widget-title">{{ __('Contact Details') }}</h3>
                        <div class="con-det">
                            {!! $store->contact_details !!}
                        </div>
                    </div>
                @endif





                @if (
                    ($store->play_store && trim($store->play_store) !== '') ||
                        ($store->app_store && trim($store->app_store) !== '') ||
                        ($store->socialLinks && $store->socialLinks->count() > 0))
                    <div class="mt-4 mobile-order-first">
                        <h3 class="widget-title">{{ __('Follow Us') }}</h3>
                        <div class="border-end border-start border-bottom rounded-md p-3">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                @if ($store->play_store && trim($store->play_store) !== '')
                                    <a href="{{ $store->play_store }}" target="_blank"
                                        class="follow-icons text-decoration-none">
                                        <i class="fa-brands fa-google-play"></i>
                                    </a>
                                @endif
                                @if ($store->app_store && trim($store->app_store) !== '')
                                    <a href="{{ $store->app_store }}" target="_blank"
                                        class="follow-icons text-decoration-none">
                                        <i class="fa-brands fa-app-store-ios"></i>
                                    </a>
                                @endif

                                @if ($store->socialLinks && $store->socialLinks->count() > 0)
                                    @foreach ($store->socialLinks as $socialLink)
                                        @if ($socialLink->socialApp && $socialLink->link)
                                            <a href="{{ $socialLink->link }}" target="_blank"
                                                class="follow-icons text-decoration-none"
                                                title="{{ $socialLink->socialApp->title }}">
                                                @if ($socialLink->socialApp->logo)
                                                    <img src="{{ asset('uploads/' . $socialLink->socialApp->logo) }}"
                                                        alt="{{ $socialLink->socialApp->title }}"
                                                        style="width: 30px; height: 30px; object-fit: contain;">
                                                @else
                                                    <i class="fa-solid fa-link"></i>
                                                @endif
                                            </a>
                                        @endif
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>
                @endif


            </div>



            <div class="col-lg-9 col-md-8 order-1 order-md-2">
                <div class="d-flex gap-3 align-items-center mb-4">
                    <div class="buttons-tab active-tab-style" data-filter="all">
                        {{ __('All') }} ({{ $offers->count() }})
                    </div>
                    <div class="buttons-tab " data-filter="codes">
                        {{ __('Codes') }} ({{ $offers->where('type', 'Code')->count() }})
                    </div>
                    <div class="buttons-tab" data-filter="deals">
                        {{ __('Deals') }} ({{ $offers->where('type', '!=', 'Code')->count() }})
                    </div>
                </div>

                @foreach ($offers as $offer)
                    <div class="coupon-card offer-item" data-type="{{ $offer->type }}">
                        <div class="row align-items-center">
                            {{-- Remove the store logo --}}
                            <div class="col-2">
                                <div class="offer-action">
                                    @php
                                        $isFreeShipping = in_array(trim(strtolower($offer->free_delivery)), [
                                            '1',
                                            'true',
                                            'active',
                                        ]);
                                        $isSpecialType = in_array(trim(strtolower($offer->type)), ['sale', 'offer']);
                                    @endphp

                                    @if ($isFreeShipping)
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
                                                {{ isset($offer->type) && trim(strtolower($offer->type)) == 'sale' ? __('SALE') : __('OFFER') }}
                                            </span>
                                        </p>
                                    @endif


                                </div>
                                {{-- <div class="store-logo mx-auto">
                        @if ($store->logo)
                        <img src="{{ asset('uploads/' . $store->logo) }}" alt="{{ $store->title }} Logo">
                        @else
                        <span class="text-muted">{{ substr($store->title, 0, 1) }}</span>
                        @endif
                    </div> --}}
                            </div>
                            <div class="col-8">
                                <div class="offer-meta">
                                    <span
                                        style="font-size:12px;font-weight: 600; color:#000;">{{ __($offer->type) }}</span>
                                    @if (in_array(trim(strtolower($offer->verified)), ['active', '1', 'true']))
                                        <span class="mx-2">|</span> <span
                                            style="font-weight: 600; font-size:12px; padding: 2px 6px;color: #28a745; border:1px dashed #28a745"><i
                                                class="fa-solid fa-check"></i><span class="verified-text">
                                                {{ __('Verified') }}</span></span>
                                    @endif

                                </div>
                                <div>
                                    <h3 style="font-size: 16px; font-weight: 600; margin-top: 10px;">{{ $offer->title }}
                                    </h3>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="offer-details">


                                    <!-- <div style="font-size:12px; font-weight: 600; padding: 2px 6px;color: #003874; border:1px dashed #003874; width: max-content; float: right;">Recently Updated</div> -->

                                </div>
                                <div class="button-deck" style="float: right; margin-top: 10px;">
                                    @if ($offer->type == 'Code')
                                        <button class="reveal-code-button" data-bs-toggle="modal"
                                            data-bs-target="#offerModal" data-offer-id="{{ $offer->id }}"
                                            data-offer-code="{{ $offer->button_text ? $offer->button_text : $offer->code }}">{{ __('REVEAL') }}
                                            <br>{{ __('CODE') }}</button>
                                    @else
                                        <button class="deal-button" data-bs-toggle="modal" data-bs-target="#offerModal"
                                            data-offer-id="{{ $offer->id }}"
                                            data-offer-type="deal">{{ __('GET') }} <br>{{ __('DEAL') }}</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if ($store->content_body && trim(strip_tags($store->content_body)) !== '')
                    <div id="reason-not-working" class="mt-4">
                        <h2 class="h5 fw-semibold text-white bg-primary rounded-md py-2 px-3"
                            style="background-color: #cf5103 !important;">
                            <span class="fw-semibold">{{ __('About') }} {{ $store->title }} {{ __('Store') }}</span>
                        </h2>
                        <div class="border-end border-start border-bottom rounded-md p-3 about-store-content">
                            {!! $store->content_body !!}
                        </div>
                    </div>
                @endif

                @if ($faqs->isNotEmpty())
                    <div id="faqs" class="mt-4">
                        <style>
                            .faq-header {
                                font-size: 24px;
                                font-weight: 700;
                                color: #cf5103;
                                margin-bottom: 20px;
                                text-transform: uppercase;
                            }

                            .faq-container {
                                display: flex;
                                flex-direction: column;
                                gap: 15px;
                            }

                            .faq-item {
                                border: 1px solid #e0e0e0;
                                border-radius: 8px;
                                overflow: hidden;
                                background: #fff;
                            }

                            .faq-question {
                                padding: 15px 20px;
                                font-size: 16px;
                                font-weight: 600;
                                color: #333;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                gap: 10px;
                                background: #f8f9fa;
                                transition: background 0.3s ease;
                            }

                            .faq-question i {
                                font-size: 14px;
                                color: #cf5103;
                                transition: transform 0.3s ease;
                            }

                            .faq-question.expanded {
                                background: #cf5103;
                                color: #fff;
                            }

                            .faq-question.expanded i {
                                color: #fff;
                            }

                            .faq-content {
                                max-height: 0;
                                overflow: hidden;
                                transition: max-height 0.3s ease-out, padding 0.3s ease;
                                background: #fff;
                            }

                            .faq-content.show {
                                max-height: 500px;
                                /* Approximate max height */
                                padding: 20px;
                                border-top: 1px solid #e0e0e0;
                            }

                            .faq-content p {
                                margin: 0;
                                color: #555;
                                line-height: 1.6;
                            }

                            .faq-question.collapsed {
                                color: #fff;
                            }
                        </style>

                        <div class="faq-header">
                            FAQs
                        </div>

                        <div class="faq-container">
                            @foreach ($faqs as $faq)
                                <div class="faq-item">
                                    <div class="faq-question collapsed" onclick="toggleFAQ(this)">
                                        <i class="fas fa-plus"></i>
                                        {{ $faq->question }}
                                    </div>
                                    <div class="faq-content">
                                        <div class="text-muted">
                                            {!! $faq->answer !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
                @endif




            </div>
        </div>
    </div>

    <nav aria-label="Breadcrumb">
        <div class="border-top bg-white px-md-2 px-3 mt-4">
            <div class="container py-3">
                <ul class="list-unstyled d-flex align-items-center gap-2 mb-0">
                    <li class="d-flex align-items-center">
                        <a href="{{ $regionCode === 'us' || !$regionCode ? route('home') : route('region.home', ['region' => $regionCode]) }}"
                            class="text-decoration-none">{{ __('Home') }}</a>
                        <svg fill="rgba(0,0,0,1)" height="18" viewBox="0 0 24 24" width="18"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z">
                            </path>
                        </svg>
                    </li>
                    <li class="d-flex align-items-center">
                        <a href="{{ $currentRegion === 'us' || !$currentRegion ? route('stores') : route('region.stores', ['region' => $currentRegion]) }}"
                            class="text-decoration-none">{{ __('Store') }}</a>
                        <svg fill="rgba(0,0,0,1)" height="18" viewBox="0 0 24 24" width="18"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z">
                            </path>
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
    <div class="modal fade coupon-modal" id="offerModal" tabindex="-1" aria-labelledby="offerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="position: absolute; top: 10px; right: 10px; background-color: #cf5103; border-radius: 50%; opacity: 1;"></button>
                    <div class="text-center">
                        <h3 id="discountValue" class="mb-0 fw-bold" style="font-size: 2rem; color: ##cf5103!important;">
                        </h3>
                        <p id="discountText" class="mb-2" style="font-size: 1rem;"></p>
                        <div class="store-logo-modal mb-3">
                            <img id="modalStoreLogo" src="" alt="Store Logo"
                                style="max-height: 120px; max-width: 120px; border: 1px dashed #cf5103; padding: 5px;">
                        </div>
                        <div class="coupon-code-container mb-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <span id="couponCode"
                                    class="coupon-code flex-grow-1 text-center p-2 bg-light border border-secondary rounded fw-bold">{{ __('Loading') }}...</span>
                                <button id="copyCodeBtn"
                                    class="ms-2 copy-code-btn btn btn-primary">{{ __('Copy Code') }}</button>
                            </div>
                        </div>
                        <div class="go-to-website mb-3">
                            <a id="affiliateLink" href="#" target="_blank" class="fw-bold">
                                {{ __('Go to :store_title Website', ['store_title' => $store->title]) }} &rarr;
                            </a>
                        </div>
                        <div class="verification-status mb-3">
                            <span id="verifiedStatus" class="d-block text-center"></span>
                        </div>
                        <div class="detail-section mt-3">
                            <h5 class="fw-bold">{{ __('Details') }}</h5>
                            <span id="offerDescription" class="list-unstyled text-center"></span>
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
                    document.querySelectorAll('[data-filter]').forEach(btn => btn.classList.remove(
                        'active-tab-style'));
                    this.classList.add('active-tab-style');
                    const filter = this.getAttribute('data-filter');
                    document.querySelectorAll('.offer-item').forEach(item => {
                        if (filter === 'all' || (filter === 'codes' && item.getAttribute(
                                'data-type') === 'Code') || (filter === 'deals' && item
                                .getAttribute('data-type') !== 'Code')) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });

            // Modal logic
            var offerModal = document.getElementById('offerModal');
            offerModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var offerId = button.getAttribute('data-offer-id');
                var offerType = button.getAttribute(
                    'data-offer-type'); // 'deal' for Get Deal, undefined for Reveal Code
                var offerCode = button.getAttribute('data-offer-code');

                // Auto-copy code if it's a "Reveal Code" button and code exists
                if (offerType !== 'deal' && offerCode) {
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(offerCode).then(function() {
                            console.log('Code auto-copied to clipboard');
                        }).catch(function(err) {
                            console.error('Could not auto-copy text: ', err);
                        });
                    } else {
                        // Fallback for non-secure contexts (e.g. local HTTP)
                        let textArea = document.createElement("textarea");
                        textArea.value = offerCode;
                        textArea.style.position = "fixed";
                        textArea.style.left = "-9999px";
                        textArea.style.top = "0";
                        document.body.appendChild(textArea);
                        textArea.focus();
                        textArea.select();
                        try {
                            document.execCommand('copy');
                            console.log('Code auto-copied to clipboard (fallback)');
                        } catch (err) {
                            console.error('Fallback verify copy failed', err);
                        }
                        document.body.removeChild(textArea);
                    }
                }

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
                            } else if (discountLower.includes('free shipping') || discountLower
                                .includes('kostenloser ver') || discountLower.includes(
                                    'spedizione grat')) {
                                discountElement.textContent = 'FREE';
                                discountTextElement.textContent = 'SHIPPING';
                            } else if (discountLower.includes('free delivery')) {
                                discountElement.textContent = 'FREE';
                                discountTextElement.textContent = 'DELIVERY';
                            } else if (discountLower === 'sign up' || discountLower.includes(
                                    'sign up') || discountLower.includes('meldung') || discountLower
                                .includes('iscrizione')) {
                                discountElement.textContent = 'SIGN';
                                discountTextElement.textContent = 'UP';
                            } else if (discountLower.includes('up to') || discountLower.includes(
                                    'bis zu') || discountLower.includes('fino al')) {
                                discountElement.textContent = offer.discount.toUpperCase();
                                discountTextElement.textContent = '';
                            } else {
                                discountElement.textContent = offer.discount.toUpperCase();
                                discountTextElement.textContent = '';
                            }
                        } else {
                            discountElement.textContent = 'SPECIAL';
                            discountTextElement.textContent = (offer.type && offer.type.toLowerCase()
                                .trim() === 'sale') ? 'SALE' : 'OFFER';
                        }

                        // Set offer title
                        offerModal.querySelector('#discountText').textContent = offer.title;

                        // Set store logo
                        var logoPath = store.logo || store.store_icon || '';
                        if (logoPath) {
                            offerModal.querySelector('#modalStoreLogo').src =
                                '{{ asset('uploads/') }}/' + logoPath;
                        } else {
                            // Show first letter of store name if no logo
                            offerModal.querySelector('#modalStoreLogo').src =
                                'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="60" viewBox="0 0 100 60"><rect width="100" height="60" fill="%23002b61"/><text x="50" y="35" font-family="Arial" font-size="20" fill="white" text-anchor="middle">' +
                                (store.title ? store.title.charAt(0) : 'S') + '</text></svg>';
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
                        if (offer.verified === 'active') {
                            verifiedStatus.innerHTML =
                                "{{ __('Verified') }} <i class=\"fas fa-check-circle text-success\"></i> | {{ __('Valid Till') }}: N/A";
                        } else {

                        }

                        // Set offer description
                        var offerDescription = offerModal.querySelector('#offerDescription');
                        offerDescription.innerHTML = ''; // Clear previous details
                        if (offer.description) {
                            // Assuming description is a simple list separated by newlines
                            var descriptionItems = offer.description.split('\n').filter(item => item
                                .trim() !== '');
                            descriptionItems.forEach(function(item) {
                                if (item.trim()) {
                                    var li = document.createElement('li');
                                    li.textContent = item.trim();
                                    offerDescription.appendChild(li);
                                }
                            });
                        } else if (offer.terms_and_conditions) {
                            // Fallback to terms and conditions if no description
                            var termsItems = offer.terms_and_conditions.split('\n').filter(item => item
                                .trim() !== '');
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
                                defaultItems.push(
                                    `Get ${Math.abs(parseFloat(offer.discount))}% off on your order.`
                                );
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
                            if (affiliateLink && affiliateLink.href && affiliateLink.href !==
                                '#' && !affiliateLink.href.includes('LINK NOT AVAILABLE')) {
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
                        offerModal.querySelector('#verifiedStatus').innerHTML =
                            'Error loading offer details';
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

            // Wrap tables in the about-store-content section for mobile responsiveness
            const storeTables = document.querySelectorAll('.about-store-content table');
            storeTables.forEach(table => {
                const wrapper = document.createElement('div');
                wrapper.className = 'table-responsive-wrapper';
                wrapper.style.overflowX = 'auto';
                wrapper.style.webkitOverflowScrolling = 'touch';
                wrapper.style.marginBottom = '1.5rem';

                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            });
        });
    </script>

@endsection
