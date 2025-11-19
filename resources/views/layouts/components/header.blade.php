<!-- Top marquee bar -->
<div class="top-marquee py-2" style="background-color: #ff5700;">
    <div class="container-fluid">
        <div class="marquee-text text-white text-center">
            <div class="marquee-content">
                We may earn a commission if you make a purchase through our links
            </div>
        </div>
    </div>
</div>

<!-- Main navigation -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #002c61;">
    <div class="container-fluid">
        <!-- Center: Logo -->
        <a class="navbar-brand text-white" href="{{ route('home') }}">
            <img src="{{ asset('uploads/logo.png') }}" alt="{{ config('app.name') }}" class="img-fluid" style="max-height: 50px; max-width: 180px; object-fit: contain;">
        </a>

        <!-- Mobile menu toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <div class="w-100 d-flex flex-column flex-lg-row justify-content-between">
                <!-- Left side: Navigation menu -->
                <ul class="navbar-nav mb-2 mb-lg-0">
                    @php
                        $regionService = app(\App\Services\RegionService::class);
                        $currentRegionCode = $regionService->getCurrentRegionCode();
                        $isUsRegion = $currentRegionCode === 'us';
                    @endphp
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') || request()->routeIs('region.home') ? 'active' : '' }}" aria-current="page" href="{{ $isUsRegion ? route('home') : route('region.home', ['region' => $currentRegionCode]) }}">Home</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories') || request()->routeIs('region.categories') ? 'active' : '' }}" href="{{ $isUsRegion ? route('categories') : route('region.categories', ['region' => $currentRegionCode]) }}">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('stores') || request()->routeIs('region.stores') ? 'active' : '' }}" href="{{ $isUsRegion ? route('stores') : route('region.stores', ['region' => $currentRegionCode]) }}">Stores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('blogs') || request()->routeIs('region.blogs') ? 'active' : '' }}" href="{{ $isUsRegion ? route('blogs') : route('region.blogs', ['region' => $currentRegionCode]) }}">Blogs</a>
                    </li>
                </ul>
    
                <!-- Right side: Search and Region dropdown -->
                <div class="d-flex align-items-center">
                    <!-- Search bar -->
                    <form class="d-flex me-2" role="search" action="{{ route('stores') }}" method="GET">
                        <div class="input-group input-group-sm" style="width: 140px;">
                            <input class="form-control" type="search" name="search" placeholder="Search..." aria-label="Search">
                            <button class="btn btn-outline-light" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
    
                    @include('layouts.components.region-dropdown',  ['usUrl' => route('home')])
                </div>
            </div>
        </div>
    </div>
</nav>


