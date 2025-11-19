@extends('layouts.app')
@section('content')
<style>
    .coupon-card {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: .25rem;
        padding: 14px;
        margin-bottom: 1.5rem;
    }
    .coupon-card .store-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 80px;
        width: 80px;
        border: 1px dashed #ff4700;
        border-radius: .25rem;
        padding: .5rem;
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
    @media (max-width: 767.98px) {
        .coupon-card .offer-details h3 { font-size: 14px; }
        .coupon-card .offer-details { text-align: center; }
    }
    .coupon-card .offer-details .offer-meta {
        font-size: .875rem;
        color: #6c757d;
    }
     .coupon-card .offer-action .reveal-code-button {
    border: 2px dashed #002b61;
    border-radius: 0px;
    padding: 7px 20px;
    background-color: #002b61;
    color: #fff;
    font-weight: 600;
    position: relative;
    clip-path: polygon(0 0, 100% 0, 91% 100%, 0 100%);
}
    .coupon-card .offer-action .deal-button {
      border: 2px dashed #002b61;
    border-radius: 0px;
    padding: 7px 20px;
    background-color: #002b61;
    color: #fff;
    font-weight: 600;
    position: relative;
        font-weight: 600;
    }
    .coupon-card .offer-action .deal-button:hover,
    .coupon-card .offer-action .reveal-code-button:hover {
        background: #ee7b42;
        color: #000;
        border: 2px dashed #002b61;
    }

    .col-md-3.button-for-deal {
        display: flex;
        align-content: center;
        align-items: center;
        justify-content: flex-end;
    }

    .sidebar .widget {
        margin-bottom: 2rem;
    }
    .sidebar .widget-title {
        font-size: 1.125rem;
        font-weight: 600;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: .5rem;
        margin-bottom: 1rem;
    }
    .sidebar .list-group-item {
        border: none;
        padding: .5rem 0;
    }
    .buttons-tab {
        cursor: pointer;
        padding: 10px 20px;
        border-radius: 5px;
        background-color: #f1f1f1;
        border: 1px solid #ddd;
    }
    .active-tab-style {
        background-color: #002b61;
        color: white;
    }
.coupon-modal .modal-content {
    border-radius: 0.5rem;
    border: 2px dashed #ff4700;
    overflow: hidden;
}
.discount-banner {
    background: linear-gradient(135deg, #002b61, #004e92);
    position: relative;
    padding-top: 1rem;
}
.discount-banner h3 {
    color: #ff4700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.coupon-modal .modal-header {
    border-bottom: none;
    padding: 0.5rem 3.5rem 0.5rem 1rem; /* Extra padding on right to accommodate close button */
    position: relative;
}
.coupon-modal .store-logo-modal {
    /*
    position: absolute;
    top: 10px; /* Adjusted positioning */
    right: 60px; /* Positioned to the left of the close button */
    display: flex;
    align-items: center;
    justify-content: center;
    height: 60px;
    width: 100px;
    border: 1px dashed #ff4700;
    border-radius: .25rem;
    padding: .5rem;
    background-color: white;
    z-index: 10; /* Ensure logo appears above other elements */
    box-sizing: border-box; /* Include padding in width calculation */
    */
}
.coupon-modal .store-logo-modal img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain; /* Ensure image fits properly without distortion */
}
.modal-body-content {
    padding-top: 0.5rem;
}
.coupon-modal .coupon-code-container {
    border: 2px dashed #ccc;
    border-radius: 0.25rem;
    margin-bottom: 1rem;
}
.coupon-modal .coupon-code {
    font-size: 1.5rem;
    font-weight: bold;
    color: #002b61;
    word-break: break-all; /* Handle long codes nicely */
}
.coupon-modal .copy-code-btn {
    background-color: #002b61;
    color: #fff;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 0.25rem;
    cursor: pointer;
    flex-shrink: 0; /* Prevent button from shrinking */
    height: fit-content;
    align-self: center; /* Align button vertically with code */
}
.coupon-modal .go-to-website {
    text-align: center;
    margin: 1rem 0;
}
.coupon-modal .go-to-website a {
    color: #ff4700;
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
.coupon-modal .detail-section ul {
    padding-left: 1.2rem;
    margin-bottom: 0;
    list-style-type: disc; /* Ensure list bullets are visible */
}

@media (max-width: 767.98px) {
    h1.h2 { font-size: 20px !important; }
    h2.mb-2 { font-size: 14px !important; }
    .coupon-card .row > [class*="col-"] {
        margin-bottom: 1rem;
    }
    .coupon-card .store-logo {
        margin: 0 auto;
    }
    .col-md-3.button-for-deal {
        justify-content: center;
    }
    .coupon-card .offer-action {
        text-align: center;
    }
    .trending-store-widget .d-flex {
        flex-direction: column;
        align-items: center !important;
        text-align: center;
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
    .coupon-modal .store-logo-modal { order: 1; }
    .coupon-modal .btn-close { order: 3; position: absolute; top: 10px; right: 10px; }
    .coupon-modal .modal-dialog { width: 90%; margin: 1.75rem auto; }
    .discount-banner h3 { font-size: 1.3rem !important; }
    .discount-banner p { font-size: 0.9rem !important; }
    .coupon-modal .coupon-code-container .d-flex { flex-direction: column; }
    .coupon-modal .copy-code-btn { width: 100%; margin-top: 0.5rem; margin-left: 0 !important; }
    .modal-body-content { padding: 0 1rem 1rem 1rem !important; }
    .coupon-modal .go-to-website a { padding: 0.75rem !important; font-size: 0.9rem; }
}
</style>

<div class="container py-3 px-md-2 px-3">
    <div class="row">
        <div class="col-lg-12 col-md-8 my-auto">
            <div class="">
                <div class="">
                    <h1 class="h2 mb-1" style="font-size:24px;font-weight:600;">{{ $categoryRecord->title }} Coupons & Promo Codes</h1>
                    <h2 class="mb-2" style="font-size:16px;font-weight:500;color:#ff4700;">
                        Best <span class="fw-semibold">{{ $categoryRecord->stores->count() }}</span> {{ $categoryRecord->title }} Offers & Promo Code last validated on <span>{{ date('F, Y') }}</span>
                    </h2>
                    <p>They offer a diverse range of products designed to make your travels seamless and enjoyable. From durable luggage to essential travel accessories, their collection ensures you have everything you need for a smooth journey, whether you're exploring new destinations or heading out on a business trip.
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-3 px-md-2 px-3">
    <div class="row">
        <div class="col-lg-3 col-md-4 sidebar">
            

            <div class="widget widget-coupon">
                <h3 class="widget-title ">Coupon Info</h3>
                <ul>
                    @php
                        $allOffers = $categoryRecord->stores->flatMap(function($store) { return $store->offers; });
                    @endphp
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Number of Deal:
                        <span class="badge">{{ $allOffers->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Coupons:
                        <span class="badge">{{ $allOffers->where('type', 'Code')->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Offers:
                        <span class="badge">{{ $allOffers->where('type', '!=', 'Code')->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Verified:
                        <span class="badge">{{ $allOffers->where('verified', '!=', 'active')->count() }}</span>
                    </li>
                </ul>
            </div>


            <div class="widget">
                <h3 class="widget-title">Last Updated</h3>
                <div class="mt-3">
                    <p class="text-sm border rounded-md bg-light p-2">
                        <span class="fw-medium text-secondary">{{ $categoryRecord->title }}</span> Coupons and Promo Codes last updated on <span>{{ date('F, Y') }}</span>
                    </p>
                </div>
            </div>

        

            <div class="widget">
                <h3 class="widget-title">Offers From Top Trending Stores</h3>
                @php
                    // Fetch trending stores from the current region, ordered by the number of active offers
                    $trendingStores = \App\Models\Store::where('active', true)
                        ->byRegionCodes([$currentRegion]) // Filter by current region
                        ->withCount('offers') // Count related offers
                        ->orderByDesc('offers_count') // Order by offer count
                        ->take(10) // Limit to top 10
                        ->get(); // Execute the query
                @endphp
                @foreach($trendingStores as $trendingStore)
                    <div class="mb-3 trending-store-widget">
                        <a href="{{ route('store.detail', ltrim($trendingStore->url_slug, '/')) }}" 
                           class="text-sm text-decoration-none">
                            <div class="d-flex gap-3 align-items-center mb-3">
                                <div class="rounded bg-white shadow p-2 w-44 h-44 d-flex align-items-center justify-content-center">
                                    @if($trendingStore->logo)
                                        <img src="{{ asset('uploads/' . $trendingStore->logo) }}" 
                                             class="w-100" 
                                             alt="{{ $trendingStore->title }} Logo"
                                             style="max-height: 44px;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center" style="height: 44px;">
                                            <span class="text-muted">{{ substr($trendingStore->title, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="">
                                    <div class="trending-store-title">{{ $trendingStore->title }} Coupon Code</div>
                                    <div class="trending-store-data">
                                        {{ $trendingStore->offers->where('type', 'Code')->count() }} 
                                        Coupons & {{ $trendingStore->offers->where('type', '!=', 'Code')->count() }} Offers
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-9 col-md-8 order-md-1 order-2">


            @php
                $userRegion = session('user_region');
                
                // Get all offers and group them by store_id
                $offersByStore = $categoryRecord->stores->flatMap(function ($store) {
                    return $store->offers;
                })->groupBy('store_id');

                $regionalOffers = collect();
                $otherOffers = collect();

                // Separate stores by region and get the first offer from each
                foreach ($offersByStore as $storeId => $storeOffers) {
                    $firstOffer = $storeOffers->first();
                    if ($firstOffer && $firstOffer->store && $firstOffer->store->country_code == $userRegion) {
                        $regionalOffers->push($firstOffer);
                        // Add remaining offers to the other offers collection
                        $otherOffers = $otherOffers->merge($storeOffers->slice(1));
                    } else {
                        $otherOffers = $otherOffers->merge($storeOffers);
                    }
                }

                $offers = $regionalOffers->merge($otherOffers);
            @endphp

            @forelse($offers as $offer)
                <div class="coupon-card offer-item" data-type="{{ $offer->type }}">
                    <div class="row">
                        <div class="col-md-2 ">
                            <div class="store-logo">
                                @if($offer->store->logo)
                                    <img src="{{ asset('uploads/' . $offer->store->logo) }}" alt="{{ $offer->store->title }} Logo">
                                @else
                                    <span class="text-muted">{{ substr($offer->store->title, 0, 1) }}</span>
                                @endif
                            </div>
                        </div>
                       <div class="col-md-7">
                        <div class="offer-details">
                            <div class="offer-meta d-flex align-items-center justify-content-center justify-content-md-start">
                                <span style="font-weight: 600; color:#000;">{{ $offer->type }}</span>                                
                                @if(in_array(trim(strtolower($offer->verified)), ['active', '1', 'true']))
                                    <span class="mx-2">|</span> <span style="font-weight: 600; padding: 2px 6px;color: #28a745; border:1px dashed #28a745">Verified</span>
                                @endif
                                @if(in_array(trim(strtolower($offer->free_delivery)), ['1', 'true', 'active']))
                                    <span class="mx-2">|</span> <span style="font-weight: 600; padding: 2px 6px;color: #007bff; border:1px dashed #007bff">Free Delivery</span>
                                @endif
                            </div>
                            <h3>{{ $offer->title }}</h3>
                            <span style="font-size:12px;">Recently Updated</span>
                        </div>
                    </div>
                        <div class="col-md-3 button-for-deal">
                            <div class="offer-action">
                                @if($offer->discount)
                                    @if (is_numeric($offer->discount))
                                        <p class="mb-2 fw-bold" style="color: #ee7b42; font-size: 1.2rem;">
                                            @if($offer->type != 'Code') Up To @else Get @endif
                                            {{ rtrim(rtrim(number_format(abs($offer->discount), 2), '0'), '.') }}%
                                        </p>
                                    @elseif(strtolower($offer->discount) == 'free shipping')
                                        <p class="mb-2 fw-bold" style="color: #ee7b42; font-size: 1.2rem;">Free Shipping</p>
                                    @endif
                                @endif
                                @if($offer->type == 'Code')
                                    <button class="reveal-code-button" data-bs-toggle="modal" data-bs-target="#offerModal" data-offer-id="{{ $offer->id }}" data-offer-code="{{ $offer->button_text }}">REVEAL CODE</button>
                                @else
                                    <button class="deal-button" data-bs-toggle="modal" data-bs-target="#offerModal" data-offer-id="{{ $offer->id }}" data-offer-type="deal">GET DEAL</button>
                                @endif
                            </div>
                        </div>
                    </div> 
                    <div class="all-store-data text-center">
                        <hr class="mt-1 mb-1">
                        <a href="{{ route('store.detail', ltrim($offer->store->url_slug, '/')) }}" class="pt-0 pb-0 btn deal-button">View All {{ $offer->store->title }}</a>
                    </div>        
                </div>
                
            @empty
                <p>No offers available in this category yet.</p>
            @endforelse
            
           

        
        </div>
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
                        <a href="{{ route('categories') }}" class="text-decoration-none">Category</a>
                        <svg fill="rgba(0,0,0,1)" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z"></path>
                        </svg>
                    </li>
                    <li class="d-flex align-items-center">
                        <span class="fw-semibold">{{ $categoryRecord->title }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endpush

<!-- Modal for Reveal Code -->
<div class="modal fade coupon-modal" id="offerModal" tabindex="-1" aria-labelledby="offerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="position-relative">
                    <!-- Discount banner at the top -->
                    <div class="discount-banner bg-primary text-white text-center py-3">
                        <h3 id="discountValue" class="mb-0 fw-bold" style="font-size: 2rem; color: #ee7b42 !important;">15%</h3>
                        <p id="discountText" class="mb-0" style="font-size: 1rem;">OFF</p>
                    </div>
                    
                    <!-- Modal header with store logo on right -->
                    <div class="modal-header border-bottom-0 pb-1 justify-content-between">
                        <h5 class="modal-title fw-bold" id="offerModalLabel" style="font-size: 1.2rem;"></h5>
                        <button type="button" class="btn-close m-2" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="store-logo-modal ms-auto">
                            <img id="modalStoreLogo" src="" alt="Store Logo" style="max-height: 50px; max-width: 80px;">
                        </div>
                    </div>
                    
                    <!-- Offer details and code section -->
                    <div class="modal-body-content px-4 pb-4">
                        <div class="coupon-code-container mb-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <span id="couponCode" class="coupon-code flex-grow-1 text-center p-3 bg-light border border-secondary rounded fw-bold" style="font-size: 1.2rem; letter-spacing: 1px;">
                                    LOADING...
                                </span>
                                <button id="copyCodeBtn" class="ms-2 copy-code-btn btn btn-primary">COPY</button>
                            </div>
                        </div>
                        
                        <!-- Go to store button -->
                        <div class="go-to-website mb-3">
                            <a id="affiliateLink" href="#" target="_blank" rel="noopener noreferrer" class="btn btn-warning w-100 py-3 fw-bold text-dark">
                                GO TO STORE &rarr;
                            </a>
                        </div>
                        
                        <div class="verification-status mb-3">
                            <span id="verifiedStatus" class="d-block text-center"></span>
                        </div>
                        
                        <div class="detail-section mt-3">
                            <h5 class="fw-bold">Details</h5>
                            <ul id="offerDescription">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var offerModal = document.getElementById('offerModal');
    offerModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var offerId = button.getAttribute('data-offer-id');
        var offerType = button.getAttribute('data-offer-type'); // 'deal' for Get Deal, undefined for Reveal Code

        // Fetch offer details via AJAX
        fetch(`/api/offer/${offerId}`)
            .then(response => response.json())
            .then(data => {
                var offer = data.offer;
                var store = data.store;

                // Set discount value at the top
                var discountElement = offerModal.querySelector('#discountValue');
                var discountTextElement = offerModal.querySelector('#discountText');
                
                if (offer.discount) {
                    if (isNumeric(offer.discount)) {
                        var discountValue = parseFloat(offer.discount);
                        discountElement.textContent = Math.abs(discountValue).toString();
                        discountTextElement.textContent = '% OFF';
                    } else if (toLowerCase(offer.discount) === 'free shipping') {
                        discountElement.textContent = 'FREE';
                        discountTextElement.textContent = 'SHIPPING';
                    } else {
                        discountElement.textContent = 'SPECIAL';
                        discountTextElement.textContent = 'DEAL';
                    }
                } else {
                    discountElement.textContent = 'UP TO';
                    discountTextElement.textContent = 'SAVE';
                }

                // Set offer title
                offerModal.querySelector('#offerModalLabel').textContent = offer.title;
                
                // Set store logo
                var logoPath = store.logo || store.store_icon || '';
                if (logoPath) {
                    offerModal.querySelector('#modalStoreLogo').src = '{{ asset('uploads/') }}/' + logoPath;
                } else {
                    // Show first letter of store name if no logo
                    offerModal.querySelector('#modalStoreLogo').src = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="60" viewBox="0 0 100 60"><rect width="100" height="60" fill="%23002b61"/><text x="50" y="35" font-family="Arial" font-size="20" fill="white" text-anchor="middle">' + (store.title ? store.title.charAt(0) : 'S') + '</text></svg>';
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
                        couponCodeElement.textContent = 'NO CODE NEEDED';
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
                    affiliateLinkElement.textContent = 'LINK NOT AVAILABLE';
                }

                // Set affiliate link - prioritize affiliate_links from store if available
                var affiliateLinkElement = offerModal.querySelector('#affiliateLink');
                if (store.affiliate_links && store.affiliate_links.trim() !== '') {
                    affiliateLinkElement.href = store.affiliate_links;
                } else if (offer.button_link) {
                    affiliateLinkElement.href = offer.button_link;
                } else {
                    affiliateLinkElement.href = '#';
                    affiliateLinkElement.textContent = 'LINK NOT AVAILABLE';
                }


                // Set verification status
                var verifiedStatus = offerModal.querySelector('#verifiedStatus');
                var validTillText = 'N/A';
                
                // Format the end date if it exists
                if (offer.end_date) {
                    var endDate = new Date(offer.end_date);
                    validTillText = endDate.toLocaleDateString();
                }
                
                if (offer.verified === 'active') {
                    verifiedStatus.innerHTML = 'Verified <i class="fas fa-check-circle text-success"></i> | Valid Till: ' + validTillText;
                } else {
                    verifiedStatus.innerHTML = 'Not Verified | Valid Till: ' + validTillText;
                }

                // Set offer description
                var offerDescription = offerModal.querySelector('#offerDescription');
                offerDescription.innerHTML = ''; // Clear previous details
                if (offer.description) {
                    // Assuming description is a simple list separated by newlines
                    var descriptionItems = offer.description.split('\n').filter(item => item.trim() !== '');
                    descriptionItems.forEach(function(item) {
                        if (item.trim()) {
                            var li = document.createElement('li');
                            li.textContent = item.trim();
                            offerDescription.appendChild(li);
                        }
                    });
                } else if (offer.terms_and_conditions) {
                    // Fallback to terms and conditions if no description
                    var termsItems = offer.terms_and_conditions.split('\n').filter(item => item.trim() !== '');
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
                        defaultItems.push(`Get ${Math.abs(parseFloat(offer.discount))}% off on your order.`);
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
                    if (affiliateLink && affiliateLink.href && affiliateLink.href !== '#' && !affiliateLink.href.includes('LINK NOT AVAILABLE')) {
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
                offerModal.querySelector('#verifiedStatus').innerHTML = 'Error loading offer details';
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
});
</script>

@endsection