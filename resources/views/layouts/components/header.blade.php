@php
    $regionService = app(\App\Services\RegionService::class);
    $currentRegionCode = $regionService->getCurrentRegionCode();
    $isUsRegion = $currentRegionCode === 'us';
@endphp

<!-- Top marquee bar -->
<div class="top-marquee py-2 text-center" style="background-color: #cf5103;color:#fff;">
    <div class="container-fluid">
                    <p style="margin:0;">{{ __('We may earn a commission if you make a purchase through our links') }}</p>
          </div>
</div>

<!-- Main navigation -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1c2043;" id="header">
    <div class="container-fluid">
        <!-- Center: Logo -->
        <a class="navbar-brand text-white" href="{{ $isUsRegion ? route('home') : route('region.home', ['region' => $currentRegionCode]) }}">
            <img src="{{ asset('uploads/logo.png') }}" alt="{{ config('app.name') }}" class="img-fluid" style="max-height: 50px; max-width: 180px; object-fit: contain;" width="180" height="50">
        </a>

        <!-- Mobile menu toggle -->
        <!-- Changed data-bs-toggle="collapse" to "offcanvas" and target to #mainNavbar -->
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Changed class "collapse navbar-collapse" to "offcanvas offcanvas-start" for side menu -->
        <!-- Added tabindex="-1" and styles for offcanvas behavior -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="mainNavbar" aria-labelledby="mainNavbarLabel" style="background-color: #1c2043;">
            <div class="offcanvas-header justify-content-between">
                <!-- Mobile Menu Logo -->
                <a class="navbar-brand text-white" href="{{ $isUsRegion ? route('home') : route('region.home', ['region' => $currentRegionCode]) }}" id="mainNavbarLabel">
                    <img src="{{ asset('uploads/logo.png') }}" alt="{{ config('app.name') }}" class="img-fluid" style="max-height: 40px; object-fit: contain;" width="180" height="40">
                </a>
                <!-- Close Button -->
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            
            <div class="offcanvas-body">
                <div class="w-100 d-flex flex-column flex-lg-row justify-content-between">
                    <!-- Left side: Navigation menu -->
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') || request()->routeIs('region.home') ? 'active' : '' }}" aria-current="page" href="{{ $isUsRegion ? route('home') : route('region.home', ['region' => $currentRegionCode]) }}">{{ __('Home') }}</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('categories') || request()->routeIs('region.categories') ? 'active' : '' }}" href="{{ $isUsRegion ? route('categories') : route('region.categories', ['region' => $currentRegionCode]) }}">{{ __('Categories') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('stores') || request()->routeIs('region.stores') ? 'active' : '' }}" href="{{ $isUsRegion ? route('stores') : route('region.stores', ['region' => $currentRegionCode]) }}">{{ __('Stores') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('blogs') || request()->routeIs('region.blogs') ? 'active' : '' }}" href="{{ $isUsRegion ? route('blogs') : route('region.blogs', ['region' => $currentRegionCode]) }}">{{ __('Blogs') }}</a>
                        </li>
                    </ul>
        
                    <!-- Right side: Search and Region dropdown -->
                    <div class="d-flex align-items-center justify-content-center mt-3 mt-lg-0">
                        <!-- Search bar -->
                        <div class="position-relative">
                            <form class="d-flex me-2" role="search" onsubmit="return false;" id="headerSearchForm">
                                <div class="input-group input-group-sm" style="width: 180px;">
                                    <input class="form-control" type="search" name="search" id="headerSearchInput" placeholder="{{ __('Search...') }}" aria-label="Search" autocomplete="off">
                                    <button class="btn btn-outline-light" type="button"><i class="fas fa-search"></i></button>
                                </div>
                            </form>
                            <div id="searchSuggestions" class="list-group position-absolute shadow" style="display:none; width: 260px; right: 0px; z-index: 10000; top: 100%;max-height: 300px; overflow-y: auto; background-color: white; border: 1px solid #ddd;">
                                <!-- Suggestions will be appended here -->    
    
                            </div>
                        </div>
        
    
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>


</nav>

@push('js')
<script>
    $(document).ready(function() {
        var searchInput = $('#headerSearchInput');
        var suggestionsBox = $('#searchSuggestions');
        var searchTimeout;

        // Determine the correct route based on region availability
        var searchUrl = "{{ $isUsRegion ? route('search.suggestions') : route('region.search.suggestions', ['region' => $currentRegionCode]) }}";

        searchInput.on('keyup', function() {
            var term = $(this).val();

            clearTimeout(searchTimeout);

            if (term.length < 1) {
                suggestionsBox.hide();
                return;
            }

            searchTimeout = setTimeout(function() {
                console.log('Searching for:', term);
                console.log('URL:', searchUrl);
                $.ajax({
                    url: searchUrl,
                    method: 'GET',
                    data: { term: term },
                    success: function(data) {
                        console.log('Data received:', data);
                        suggestionsBox.empty();

                        if (data.length > 0) {
                            var currentCategory = '';
                            
                            $.each(data, function(index, item) {
                                if (item.category !== currentCategory) {
                                    suggestionsBox.append('<div class="list-group-item list-group-item-light fw-bold text-uppercase" style="font-size: 0.8rem; background-color: #f8f9fa; color: #333;">' + item.category + '</div>');
                                    currentCategory = item.category;
                                }
                                
                                var suggestionItem = $('<a href="' + item.url + '" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" style="background-color: white; color: #333; cursor: pointer;"><span>' + item.label + '</span><small class="text-muted" style="font-size: 0.7rem;">' + item.category + '</small></a>');
                                suggestionsBox.append(suggestionItem);
                            });
                            
                            suggestionsBox.show();
                        } else {
                            console.log('No results found');
                            suggestionsBox.hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching suggestions:', error);
                        console.log('Response:', xhr.responseText);
                    }
                });
            }, 300); // 300ms debounce
        });

        // Hide suggestions when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.position-relative').length) {
                suggestionsBox.hide();
            }
        });
    });
</script>
@endpush
