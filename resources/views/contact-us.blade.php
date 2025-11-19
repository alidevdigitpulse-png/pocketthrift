@extends('layouts.app')
@php
    $regionService = app(\App\Services\RegionService::class);
    $currentRegion = $regionService->getCurrentRegion();

    // Default values
    $country = $currentRegion->country ?? 'USA';
    $regionPrefix = $currentRegion->code ?? 'us';

    $baseUrl = url($regionPrefix . '/contact-us');
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

    $title = $meta['title'] ?? $page->name ?? 'Contact Us';
    $description = $meta['description'] ?? $page->meta_description ?? 'Get in touch with Pocketthrift through our Contact Us page. Find our contact details, submit inquiries, and connect with our team for assistance.';
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
    // The previous logic for $siteUrl already covers this, we just ensure it's applied
    if(!empty($breadcrumbs)) {
        // Ensure the home breadcrumb uses the regionized $siteUrl
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
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4" style="background-color: #002c61 !important;">
                    <h2 class="mb-0">Contact Us</h2>
                    <p class="mb-0">We'd love to hear from you! Get in touch with us.</p>
                </div>
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h4 class="mb-4">Get In Touch</h4>
                            <p class="text-muted mb-4">Fill out the form to send us a message. Our team will get back to you within 24 hours.</p>
                            
                            <div class="contact-info">
                                <div class="d-flex mb-3">
                                    <div class="contact-icon me-3">
                                        <i class="fas fa-map-marker-alt text-primary" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Address</h6>
                                        <p class="mb-0 text-muted">123 Business Street, City, Country</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex mb-3">
                                    <div class="contact-icon me-3">
                                        <i class="fas fa-phone text-primary" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Phone</h6>
                                        <p class="mb-0 text-muted">+1 (123) 456-7890</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex mb-3">
                                    <div class="contact-icon me-3">
                                        <i class="fas fa-envelope text-primary" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Email</h6>
                                        <p class="mb-0 text-muted">info@pocketthrift.com</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex">
                                    <div class="contact-icon me-3">
                                        <i class="fas fa-clock text-primary" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Working Hours</h6>
                                        <p class="mb-0 text-muted">Mon-Fri: 9AM - 6PM</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <form id="contactForm" action="{{ route('contactUs.store') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-bold">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required value="{{ old('name') }}">
                                    @error('name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-bold">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required value="{{ old('email') }}">
                                    @error('email')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="subject" class="form-label fw-bold">Subject</label>
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject" required value="{{ old('subject') }}">
                                    @error('subject')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="message" class="form-label fw-bold">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" placeholder="Enter your message" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100" style="background-color: #002c61; border-color: #002c61;">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Google Maps Section -->
<div class="container-fluid px-0">
    <div class="map-container">
        <!-- Google Maps Embed (Placeholder) -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.1234567890123!2d-74.0059413!3d40.7127753!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDQyJzQ2LjAiTiA3NMKwMDAnMjEuNCJX!5e0!3m2!1sen!2sus!4v1234567890123" 
                width="100%" 
                height="400" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
        </iframe>
    </div>
</div>

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
                    <span class="fw-semibold">Contact Us</span>
                </li>
            </ul>
        </div>
    </div>
</nav>
@endpush
</div>
@endsection

@section('styles')
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
        border-color: #002c61;
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
@endsection

@section('scripts')
<script>
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Sending...';
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
                alert('There was an error sending your message. Please try again.');
            }
        })
        .catch(error => {
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            console.error('Error:', error);
            alert('There was an error sending your message. Please try again.');
        });
    });
</script>

@endsection