@extends('layouts.app')
@section('title', $page->seo_title)
@section('meta_description', $page->meta_description)
@php
    $regionService = app(\App\Services\RegionService::class);
    $currentRegion = $regionService->getCurrentRegion();

    // Default values
    $country = $currentRegion->country ?? 'USA';
    $regionPrefix = $currentRegion->code ?? 'us';

    $baseUrl = url($regionPrefix . '/contact-us');
@endphp
@push('schemas')
<meta name="robots" content="index, follow">

{{-- OG TAGS --}}
<meta property="og:title" content="{{ $page->seo_title }}">
<meta property="og:description"
      content="{{ $page->meta_description }}">
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
<meta name="twitter:description"
      content="{{ $page->meta_description }}">
<meta name="twitter:image" content="https://pocketthrift.com/images/og-image.webp">
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

    // SITE URL ALWAYS REGIONIZED: e.g., https://pocketthrift.com/mx
    // This is the correct regionized home URL
    $siteUrl = ($regionCode === 'us') ? $baseUrl : $baseUrl . '/' . $regionCode;

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
        ['name'=>$page->title, 'url'=>$pathWithoutRegion]
    ];

    // 🔥 FIX: FORCE HOME URL TO INCLUDE REGION
    // The previous logic for $siteUrl already covers this, we just ensure it's applied
    if(!empty($breadcrumbs)) {
        // Ensure the home breadcrumb uses the regionized $siteUrl
        $breadcrumbs[0]['url'] = $siteUrl;
    } else {
        $breadcrumbs = [
            ['name'=>'Home','url'=>$siteUrl],
            ['name'=>$page->title,'url'=>$pathWithoutRegion]
        ];
    }
@endphp

@php
    $webpage = [
        "@context"=>"https://schema.org/",
        "@type"=>"WebPage",
        "name"=>"Contact Us",
        "description"=>$page->meta_description,
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

<script type="application/ld+json">
{!! json_encode($webpage, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>

<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4" style="background-color: #cf5103 !important;">
                    <h1 class="mb-0">{{ __('Contact Us') }}</h1>
                    <p class="mb-0">{{ __("We'd love to hear from you! Get in touch with us.") }}</p>
                </div>
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h4 class="mb-4">{{ __('Get In Touch') }}</h4>
                            <p class="text-muted mb-4">{{ __('Fill out the form to send us a message. Our team will get back to you within 24 hours.') }}</p>
                            
                            <div class="contact-info">
                                <div class="d-flex mb-3">
                                    <div class="contact-icon me-3">
                                        <i class="fas fa-map-marker-alt text-primary" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ __('Address') }}</h6>
                                        <p class="mb-0 text-muted">U10, 14-16 Weigand Avenue, Bankstown, New South Wales 2200  Sydney, Australia</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex mb-3">
                                    <div class="contact-icon me-3">
                                        <i class="fas fa-phone text-primary" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ __('Phone') }}</h6>
                                        <p class="mb-0 text-muted "><a href="tel:+61414573000" class="text-decoration-none">+61 414 573 000</a></p>
                                    </div>
                                </div>
                                
                                <div class="d-flex mb-3">
                                    <div class="contact-icon me-3">
                                        <i class="fas fa-envelope text-primary" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ __('Email') }}</h6>
                                        <p class="mb-0 text-muted"><a href="mailto:contact@pocketthrift.com" class="text-decoration-none">contact@pocketthrift.com</a></p>
                                        <p class="mb-0 text-muted"><a href="mailto:info@pocketthrift.com" class="text-decoration-none">info@pocketthrift.com</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
<form id="contactForm" action="{{ route('contactUs.store') }}" method="POST">
@csrf
<div class="mb-4">
<label for="name" class="form-label fw-bold">{{ __('Full Name') }}</label>
<input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Enter your name') }}" required value="{{ old('name') }}">
@error('name')
<div class="text-danger mt-1">{{ $message }}</div>
@enderror
</div>

<div class="mb-4">
<label for="email" class="form-label fw-bold">{{ __('Email Address') }}</label>
<input type="email" class="form-control" id="email" name="email" placeholder="{{ __('Enter your email') }}" required value="{{ old('email') }}">
@error('email')
<div class="text-danger mt-1">{{ $message }}</div>
@enderror
</div>

<div class="mb-4">
<label for="subject" class="form-label fw-bold">{{ __('Subject') }}</label>
<input type="text" class="form-control" id="subject" name="subject" placeholder="{{ __('Enter subject') }}" required value="{{ old('subject') }}">
@error('subject')
<div class="text-danger mt-1">{{ $message }}</div>
@enderror
</div>

<div class="mb-4">
<label for="message" class="form-label fw-bold">{{ __('Message') }}</label>
<textarea class="form-control" id="message" name="message" rows="5" placeholder="{{ __('Enter your message') }}" required>{{ old('message') }}</textarea>
@error('message')
<div class="text-danger mt-1">{{ $message }}</div>
@enderror
</div>

<button type="submit" class="btn btn-primary w-100" style="background-color: #cf5103; border-color: #cf5103;">{{ __('Send Message') }}</button>
</form>
                        </div>
                    </div>
                </div>
            </div>
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
                    <span class="fw-semibold">{{ __('Contact Us') }}</span>
                </li>
            </ul>
        </div>
    </div>
</nav>
@endpush
</div>
@endsection

@push('css')
<style>
    .contact-icon {
        min-width: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .map-container {
        height: 400px;
        overflow: hidden;
    }
    
    .map-container iframe {
        height: 100%;
        border: 0;
    }
    
    .card {
        border-radius: 10px;
    }
    
    .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 12px 15px;
    }
    
    .form-control:focus {
        border-color: #cf5103;
        box-shadow: 0 0 0 0.2rem rgba(0, 44, 97, 0.25);
    }
    
    .btn-primary {
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 44, 97, 0.3);
    }
</style>
@endpush

@push('js')
<script>
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> ' + "{{ __('Sending...') }}";
        submitBtn.disabled = true;
        
        // Create form data object
        const formData = new FormData(this);
        
        // Send AJAX request
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            if(data.success) {
                // Show success message
                alert(data.message);
                this.reset();
            } else {
                // Handle errors
                alert("{{ __('There was an error sending your message. Please try again.') }}");
            }
        })
        .catch(error => {
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            console.error('Error:', error);
            alert("{{ __('There was an error sending your message. Please try again.') }}");
        });
    });
</script>

@endpush