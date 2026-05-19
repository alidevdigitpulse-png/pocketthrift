@extends('layouts.app')
@section('title', $blog->seo_title)
@section('meta_description', $blog->meta_description)
@push('schemas')
    {{-- Open Graph Meta Tags --}}
    <meta property="og:title" content="{{ $blog->seo_title }}">
    <meta property="og:description" content="{{ $blog->meta_description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="article">
    @if($blog->logo)
        <meta property="og:image" content="{{ asset('uploads/' . $blog->logo) }}">
    @else
        <meta property="og:image" content="https://pocketthrift.com/images/og-image.webp">
    @endif
    <meta property="og:image:alt" content="{{ $blog->title }}">
    <meta property="og:site_name" content="PocketThrift">
    
    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $blog->seo_title }}">
    <meta name="twitter:description" content="{{ $blog->meta_description }}">
    @if($blog->logo)
        <meta name="twitter:image" content="{{ asset('uploads/' . $blog->logo) }}">
    @else
        <meta name="twitter:image" content="https://pocketthrift.com/images/og-image.webp">
    @endif
    <meta name="twitter:image:alt" content="{{ $blog->title }}">
    
    @php
        $regionCode = $region->code ?? ($regionCode ?? 'us');
        $baseUrl = rtrim(config('app.url', 'https://pocketthrift.com'), '/');
        $siteUrl = ($regionCode === 'us') ? $baseUrl : $baseUrl . '/' . $regionCode;

        $title = $meta['title'] ?? ($post->title ?? 'Blog');
        $description = $meta['description'] ?? ($post->excerpt ?? null);
        $path = $meta['path'] ?? request()->getPathInfo();
        $fullUrl = rtrim($siteUrl, '/') . '/' . ltrim($path, '/');

        $breadcrumbs = $meta['breadcrumbs'] ?? [
            ['name' => __('Home'), 'url' => '/'],
            ['name' => __('Blogs'), 'url' => '/blogs/'],
            ['name' => $title, 'url' => $path]
        ];

        $webpage = ["@context" => "https://schema.org/", "@type" => "WebPage", "name" => $title, "description" => $description, "url" => $fullUrl, "publisher" => ["@type" => "Organization", "name" => "PocketThrift", "logo" => ["@type" => "ImageObject", "url" => $siteUrl . '/images/og-image.webp']]];

        $items = [];
        $pos = 1;
        foreach ($breadcrumbs as $b) {
            $u = (strpos($b['url'], 'http') === 0) ? $b['url'] : rtrim($siteUrl, '/') . '/' . ltrim($b['url'], '/');
            $items[] = ["@type" => "ListItem", "position" => $pos++, "name" => $b['name'], "item" => $u];
        }
        $breadcrumbSchema = ["@context" => "https://schema.org/", "@type" => "BreadcrumbList", "itemListElement" => $items];

        $faqsArr = $meta['faqs'] ?? ($faqs ?? []);
        $faqSchema = null;

        // If $faqs is a collection (from the database), convert it to the expected array format
        if ($faqsArr && is_a($faqsArr, 'Illuminate\Database\Eloquent\Collection')) {
            // Convert Eloquent collection to array format for schema
            $ents = [];
            foreach ($faqsArr as $f) {
                $q = $f->question ?? null;
                $a = $f->answer ?? null;
                if ($q && $a)
                    $ents[] = ["@type" => "Question", "name" => $q, "acceptedAnswer" => ["@type" => "Answer", "text" => $a]];
            }
            if (count($ents))
                $faqSchema = ["@context" => "https://schema.org/", "@type" => "FAQPage", "mainEntity" => $ents];
        } elseif (!empty($faqsArr) && is_array($faqsArr)) {
            // Handle if it's already an array format (fallback)
            $ents = [];
            foreach ($faqsArr as $f) {
                $q = $f['question'] ?? $f['name'] ?? null;
                $a = $f['answer'] ?? $f['acceptedAnswer'] ?? null;
                if ($q && $a)
                    $ents[] = ["@type" => "Question", "name" => $q, "acceptedAnswer" => ["@type" => "Answer", "text" => $a]];
            }
            if (count($ents))
                $faqSchema = ["@context" => "https://schema.org/", "@type" => "FAQPage", "mainEntity" => $ents];
        }
    @endphp

    <script
        type="application/ld+json">{!! json_encode($webpage, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
    <script
        type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
    @if($faqSchema)
        <script
            type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
    @endif
@endpush

@section('title', $blog->title ?? 'Blog Detail')
@section('robots', 'index, follow')
@section('content')

    <style>
        /* Custom Blog UI Styles */
        .blog-wrapper {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #2d3748;
            background-color: #f8fafc;
            padding-bottom: 3rem;
        }

        .blog-header-section {
            background: #fff;
            padding: 2rem 0;
            border-bottom: 1px solid #edf2f7;
            margin-bottom: 2rem;
        }

        .blog-main-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: #1a202c;
            line-height: 1.25;
            margin-bottom: 1rem;
        }

        .blog-meta-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            background-color: #fff5f0;
            color: #c05621;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-right: 1rem;
        }

        .blog-meta-info {
            color: #718096;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 0.75rem;
        }

        .blog-featured-image {
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            margin-bottom: 2.5rem;
            overflow: hidden;
            width: 100%;
        }

        .blog-content {
            background: white;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .blog-content p {
            font-size: 1.125rem;
            /* 18px */
            line-height: 1.9;
            color: #4a5568;
            margin-bottom: 1.75rem;
        }

        .blog-content h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            margin-top: 3rem;
            margin-bottom: 1.25rem;
            border-left: 5px solid #cf5103;
            padding-left: 1rem;
        }

        .blog-content h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3748;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
        }

        .blog-content ul {
            margin-bottom: 1.75rem;
            padding-left: 2rem;
        }

        .blog-content li {
            font-size: 1.125rem;
            color: #4a5568;
            margin-bottom: 0.75rem;
            line-height: 1.7;
        }

        .blog-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.75rem;
            display: block;
            margin: 2rem auto;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .blog-content table {
            width: 100%;
            margin-bottom: 0; 
            border-collapse: collapse;
        }

        .blog-content table th,
        .blog-content table td {
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }


        /* Clean Sidebar */
        .sidebar-wrapper {
            position: sticky;
            top: 2rem;
        }

        .sidebar-widget {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
            transition: transform 0.2s;
        }

        .sidebar-widget:hover {
            transform: translateY(-2px);
        }

        .widget-heading {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #edf2f7;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .related-card {
            display: flex;
            gap: 1rem;
            align-items: center;
            text-decoration: none;
            padding: 0.75rem;
            border-radius: 0.5rem;
            transition: background 0.15s;
        }

        .related-card:hover {
            background: #f7fafc;
        }

        .related-card-img {
            width: 60px;
            height: 60px;
            flex-shrink: 0;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
        }

        .related-card-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2d3748;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* FAQ Accordion Modern */
        .faq-modern-item {
            border-bottom: 1px solid #edf2f7;
        }

        .faq-modern-item:last-child {
            border-bottom: none;
        }

        .faq-modern-btn {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 1.25rem 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3748;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        .faq-modern-btn:hover {
            color: #cf5103;
        }

        .faq-modern-content {
            display: none;
            padding-bottom: 1.25rem;
            color: #4a5568;
            font-size: 1rem;
            line-height: 1.7;
        }

        .faq-modern-content.active {
            display: block;
            animation: fadeIn 0.3s;
        }

        .faq-icon {
            transition: transform 0.3s;
            font-size: 0.875rem;
            color: #a0aec0;
        }

        .faq-modern-btn[aria-expanded="true"] .faq-icon {
            transform: rotate(180deg);
            color: #cf5103;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .blog-main-title {
                font-size: 1.75rem;
            }

            .blog-content {
                padding: 1.5rem;
            }
        }

        /* Breadcrumb styling override */
        .breadcrumb-nav {
            background: white;
            border-top: 1px solid #edf2f7;
        }
    </style>

    <div class="blog-wrapper">
        <!-- Header Section -->
        <div class="blog-header-section">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-10 col-xl-9">
                        <span class="blog-meta-badge">
                            {{ $blog->category ? $blog->category->title : 'Blog' }}
                        </span>
                        <h1 class="blog-main-title">{{ $blog->title }}</h1>
                        <div class="blog-meta-info">
                            <span><i
                                    class="far fa-calendar-alt me-2"></i>{{ $blog->created_at ? __($blog->created_at->format('F')) . ' ' . $blog->created_at->format('j, Y') : '' }}</span>
                            @if($blog->est_read_time)
                                <span><span class="mx-2">•</span><i class="far fa-clock me-2"></i>{{ $blog->est_read_time }} {{ __('min read') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-center">

                <!-- Main Content Area -->
                <div class="col-lg-7 col-xl-6">

                    <!-- Main Blog Card -->
                    <article>
                        <!-- Featured Image -->
                        <div class="blog-featured-image">
                            @if($blog->logo)
                                <img src="{{ asset('uploads/' . $blog->logo) }}" alt="{{ $blog->image_alt ?: $blog->title }}"
                                    class="w-100" style="object-fit: cover; min-height: 300px;">
                            @else
                                <img src="{{ asset('image.png') }}" alt="{{ $blog->title }}" class="w-100"
                                    style="object-fit: cover; min-height: 300px;">
                            @endif
                        </div>

                        <!-- Content Body -->
                        <div class="blog-content">
                            {!! html_entity_decode($blog->content_body) !!}

                            <!-- FAQ Section inside Main Content -->
                            @if(isset($faqs) && $faqs->isNotEmpty())
                                <div class="mt-5 pt-4 border-top">
                                    <h3 class="mb-4" style="font-size: 1.5rem; color: #1a202c;">{{ __('Frequently Asked Questions') }}</h3>
                                    <div class="faq-accordion">
                                        @foreach($faqs as $index => $faq)
                                            <div class="faq-modern-item">
                                                <button class="faq-modern-btn" onclick="toggleModernFAQ(this)"
                                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                                    <span class="me-3">{{ $faq->question }}</span>
                                                    <i class="fas fa-chevron-down faq-icon"></i>
                                                </button>
                                                <div class="faq-modern-content {{ $index === 0 ? 'active' : '' }}">
                                                    {!! html_entity_decode($faq->answer) !!}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </article>

                    <!-- Back to Blogs -->
                    <div class="text-center mt-5 mb-5">
                        @if(request()->route('region'))
                            <a href="/{{ request()->route('region') }}/blogs"
                                class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold">
                                <i class="fas fa-arrow-left me-2"></i>{{ __('Back to All Blogs') }}
                            </a>
                        @else
                            <a href="/blogs" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold">
                                <i class="fas fa-arrow-left me-2"></i>{{ __('Back to All Blogs') }}
                            </a>
                        @endif
                    </div>

                </div>

                <!-- Sidebar Area -->
                <div class="col-lg-4 col-xl-3">
                    <div class="sidebar-wrapper">

                        <!-- Related Blogs Widget -->
                        <div class="sidebar-widget">
                            <h3 class="widget-heading">{{ __('Related Blogs') }}</h3>
                            @php
                                // First try to get blogs from the same category
                                $relatedBlogs = \App\Models\Blog::where('category_id', $blog->category_id)
                                    ->where('id', '!=', $blog->id)
                                    ->where('active', true)
                                    ->byRegionCodes([$currentRegion])
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();

                                // If we don't have enough blogs from the same category, get more from other categories
                                if ($relatedBlogs->count() < 5) {
                                    $additionalBlogs = \App\Models\Blog::where('id', '!=', $blog->id)
                                        ->where('active', true)
                                        ->byRegionCodes([$currentRegion])
                                        ->whereNotIn('id', $relatedBlogs->pluck('id'))
                                        ->orderBy('created_at', 'desc')
                                        ->take(5 - $relatedBlogs->count())
                                        ->get();

                                    $relatedBlogs = $relatedBlogs->merge($additionalBlogs);
                                }
                            @endphp

                            @foreach($relatedBlogs as $relatedBlog)
                                <a href="@if(request()->route('region'))/{{ request()->route('region') }}/blogs/{{ ltrim($relatedBlog->url_slug, '/') }}@else/blogs/{{ ltrim($relatedBlog->url_slug, '/') }}@endif"
                                    class="related-card">
                                    <div class="related-card-img">
                                        @if($relatedBlog->logo)
                                            <img src="{{ asset('uploads/' . $relatedBlog->logo) }}" alt="{{ $relatedBlog->title }}"
                                                style="width: 100%; height: 100%; object-fit: cover; border-radius:5px;">
                                        @else
                                            <span class="text-muted fw-bold">{{ substr($relatedBlog->title, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div class="related-card-title">
                                        {{ Str::limit($relatedBlog->title, 55) }}
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- Trending Stores Widget -->
                        <div class="sidebar-widget">
                            <h3 class="widget-heading">{{ __('Trending Stores') }}</h3>
                            @php
                                $trendingStores = \App\Models\Store::where('active', true)
                                    ->byRegionCodes([$currentRegion])
                                    ->withCount('offers')
                                    ->orderByDesc('offers_count')
                                    ->take(5)
                                    ->get();
                            @endphp

                            @foreach($trendingStores as $store)
                                <a href="{{ ($currentRegion === 'us' || !$currentRegion) ? route('store.detail', ltrim($store->url_slug, '/')) : route('region.store.detail', ['region' => $currentRegion, 'store' => ltrim($store->url_slug, '/')]) }}"
                                    class="related-card">
                                    <div class="related-card-img">
                                        @if($store->logo)
                                            <img src="{{ asset('uploads/' . $store->logo) }}" alt="{{ $store->title }}"
                                                style="width: 100%; height: 100%; object-fit: contain;">
                                        @else
                                            <span class="text-muted fw-bold">{{ substr($store->title, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="related-card-title mb-1">{{ $store->title }}</div>
                                        <span class="coupon-count-badge">
                                            {{ $store->offers->where('type', 'Code')->count() }} {{ __('Codes') }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Improved Breadcrumb -->
        @push('breadcrumb')
            <nav aria-label="Breadcrumb" class="breadcrumb-nav">
                <div class="container py-3">
                    <ul class="list-unstyled d-flex align-items-center gap-2 mb-0 flex-wrap" style="font-size: 0.9rem;">
                        <li class="d-flex align-items-center">
                            <a href="{{ ($currentRegion === 'us' || !$currentRegion) ? route('home') : route('region.home', ['region' => $currentRegion]) }}"
                                class="text-decoration-none text-muted">{{ __('Home') }}</a>
                            <i class="fas fa-chevron-right mx-2 text-muted" style="font-size: 0.75rem;"></i>
                        </li>
                        <li class="d-flex align-items-center">
                            <a href="{{ ($currentRegion === 'us' || !$currentRegion) ? route('blogs') : route('region.blogs', ['region' => $currentRegion]) }}"
                                class="text-decoration-none text-muted">{{ __('Blogs') }}</a>
                            <i class="fas fa-chevron-right mx-2 text-muted" style="font-size: 0.75rem;"></i>
                        </li>
                        <li class="d-flex align-items-center">
                            <span class="fw-semibold text-dark text-truncate"
                                style="max-width: 250px;">{{ $blog->title }}</span>
                        </li>
                    </ul>
                </div>
            </nav>
        @endpush
    </div>
@endsection

@push('js')
    <script>
        function toggleModernFAQ(button) {
            const content = button.nextElementSibling;
            const expanded = button.getAttribute('aria-expanded') === 'true';

            button.setAttribute('aria-expanded', !expanded);
            content.classList.toggle('active');
        }

        // Wrap tables throughout the blog content for responsibility
        document.addEventListener("DOMContentLoaded", function() {
            const tables = document.querySelectorAll('.blog-content table');
            tables.forEach(table => {
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
@endpush