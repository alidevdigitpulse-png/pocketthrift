@extends('admin.layouts.app')

@section('title', 'Manage Trending Items')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="card-title">Manage Trending Items</h4>
                            <p class="text-muted">Select up to 5 stores, categories, and offers to display as trending on the homepage</p>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.region.dashboard.trending.save') }}">
                        @csrf
                        <input type="hidden" name="region_id" value="{{ $regionId }}">

                        <!-- Trending Stores -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5>Trending Stores</h5>
                                <div class="d-flex gap-2">
                                    <div class="input-group" style="max-width: 200px;">
                                        <select class="form-select" id="storeCategoryFilter">
                                            <option value="">All Categories</option>
                                            @foreach($availableStoreCategories as $category)
                                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-group" style="max-width: 200px;">
                                        <input type="text" class="form-control" id="storeSearch" placeholder="Search stores...">
                                        <button class="btn btn-outline-secondary" type="button" onclick="clearSearch('storeSearch')">Clear</button>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted">Select up to 5 stores that should appear as trending</p>
                            
                            @if($allStores->count() > 0)
                                <div class="row" id="storesContainer">
                                    @foreach($allStores as $store)
                                        <div class="col-md-6 col-lg-4 mb-2 store-item" data-title="{{ strtolower($store->title) }}" data-category="{{ $store->category_id ?? 'none' }}">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="trending_stores[]" 
                                                       value="{{ $store->id }}" 
                                                       id="store_{{ $store->id }}"
                                                       {{ in_array($store->id, $currentTrendingStores) ? 'checked' : '' }}
                                                       {{ count($currentTrendingStores) >= 5 && !in_array($store->id, $currentTrendingStores) ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="store_{{ $store->id }}">
                                                    {{ $store->title }}
                                                    @if($store->category)
                                                        <small class="text-muted d-block">({{ $store->category->title }})</small>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-muted mt-2">
                                    Showing <span id="storeCount">{{ $allStores->count() }}</span> of {{ $allStores->count() }} stores
                                </div>
                            @else
                                <div class="alert alert-info">
                                    No stores available for your region. This might be because:
                                    <ul>
                                        <li>No stores exist in the system</li>
                                        <li>Stores exist but don't belong to your region</li>
                                        <li>Stores haven't been configured with region settings</li>
                                    </ul>
                                </div>
                            @endif
                            
                            @if($errors->has('trending_stores'))
                                <div class="text-danger mt-1">{{ $errors->first('trending_stores') }}</div>
                            @endif
                        </div>

                        <!-- Trending Categories -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5>Trending Categories</h5>
                                <div class="input-group" style="max-width: 300px;">
                                    <input type="text" class="form-control" id="categorySearch" placeholder="Search categories...">
                                    <button class="btn btn-outline-secondary" type="button" onclick="clearSearch('categorySearch')">Clear</button>
                                </div>
                            </div>
                            <p class="text-muted">Select up to 5 categories that should appear as trending</p>
                            
                            @if($allCategories->count() > 0)
                                @if(!empty($categoriesFallback) && $categoriesFallback)
                                    <div class="alert alert-warning">
                                        No region-specific categories were found. Showing global active categories as a fallback.
                                        <br>
                                        <small class="text-muted">These categories are not tied to the selected region. Selected items will still be saved for the region, but please consider assigning categories to regions for strict filtering.</small>
                                    </div>
                                @endif
                                <div class="row" id="categoriesContainer">
                                    @foreach($allCategories as $category)
                                        <div class="col-md-6 col-lg-4 mb-2 category-item" data-title="{{ strtolower($category->title) }}">
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="trending_categories[]" 
                                                       value="{{ $category->id }}" 
                                                       id="category_{{ $category->id }}"
                                                       {{ in_array($category->id, $currentTrendingCategories) ? 'checked' : '' }}
                                                       {{ count($currentTrendingCategories) >= 5 && !in_array($category->id, $currentTrendingCategories) ? 'disabled' : '' }}>
                                                <label class="form-check-label ms-2" for="category_{{ $category->id }}">
                                                    {{ $category->title }}
                                                </label>
                                                @if(!empty($categoriesFallback) && $categoriesFallback)
                                                    <span class="badge bg-secondary ms-2">Global</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-muted mt-2">
                                    Showing {{ $allCategories->count() }} categories
                                </div>
                            @else
                                <div class="alert alert-info">
                                    No categories available for your region. This might be because:
                                    <ul>
                                        <li>No categories exist in the system</li>
                                        <li>Categories exist but don't belong to your region</li>
                                        <li>Categories haven't been configured with region settings</li>
                                    </ul>
                                </div>
                            @endif
                            
                            @if($errors->has('trending_categories'))
                                <div class="text-danger mt-1">{{ $errors->first('trending_categories') }}</div>
                            @endif
                        </div>

                        <!-- Trending Offers -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5>Trending Offers</h5>
                                <div class="d-flex gap-2">
                                    <div class="input-group" style="max-width: 200px;">
                                        <select class="form-select" id="offerStoreFilter">
                                            <option value="">All Stores</option>
                                            @foreach($availableOfferStores as $store)
                                                <option value="{{ $store->id }}">{{ $store->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-group" style="max-width: 200px;">
                                        <input type="text" class="form-control" id="offerSearch" placeholder="Search offers...">
                                        <button class="btn btn-outline-secondary" type="button" onclick="clearSearch('offerSearch')">Clear</button>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted">Select up to 5 offers that should appear as trending</p>
                            
                            @if($allOffers->count() > 0)
                                <div class="row" id="offersContainer">
                                    @foreach($allOffers as $offer)
                                        <div class="col-md-6 col-lg-4 mb-2 offer-item" data-title="{{ strtolower($offer->title) }}" data-type="{{ strtolower($offer->type) }}" data-store="{{ $offer->store_id ?? 'none' }}">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="trending_offers[]" 
                                                       value="{{ $offer->id }}" 
                                                       id="offer_{{ $offer->id }}"
                                                       {{ in_array($offer->id, $currentTrendingOffers) ? 'checked' : '' }}
                                                       {{ count($currentTrendingOffers) >= 5 && !in_array($offer->id, $currentTrendingOffers) ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="offer_{{ $offer->id }}">
                                                    {{ $offer->title }}
                                                    @if($offer->store)
                                                        <small class="text-muted d-block">({{ $offer->store->title }})</small>
                                                    @endif
                                                    @if($offer->type)
                                                        <span class="badge bg-secondary ms-1">{{ $offer->type }}</span>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-muted mt-2">
                                    Showing <span id="offerCount">{{ $allOffers->count() }}</span> of {{ $allOffers->count() }} offers
                                </div>
                            @else
                                <div class="alert alert-info">
                                    No offers available for your region. This might be because:
                                    <ul>
                                        <li>No offers exist in the system</li>
                                        <li>Offers exist but don't belong to your region</li>
                                        <li>Offers haven't been configured with region settings</li>
                                    </ul>
                                </div>
                            @endif
                            
                            @if($errors->has('trending_offers'))
                                <div class="text-danger mt-1">{{ $errors->first('trending_offers') }}</div>
                            @endif
                        </div>
                        
                        <!-- Trending Blogs -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5>Trending Blogs</h5>
                                <div class="d-flex gap-2">
                                    <div class="input-group" style="max-width: 200px;">
                                        <select class="form-select" id="blogCategoryFilter">
                                            <option value="">All Categories</option>
                                            @foreach($availableBlogCategories as $category)
                                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-group" style="max-width: 200px;">
                                        <input type="text" class="form-control" id="blogSearch" placeholder="Search blogs...">
                                        <button class="btn btn-outline-secondary" type="button" onclick="clearSearch('blogSearch')">Clear</button>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted">Select up to 5 blogs that should appear as trending</p>
                            
                            @if($allBlogs->count() > 0)
                                <div class="row" id="blogsContainer">
                                    @foreach($allBlogs as $blog)
                                        <div class="col-md-6 col-lg-4 mb-2 blog-item" data-title="{{ strtolower($blog->title) }}" data-category="{{ $blog->category_id ?? 'none' }}">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="trending_blogs[]" 
                                                       value="{{ $blog->id }}" 
                                                       id="blog_{{ $blog->id }}"
                                                       {{ in_array($blog->id, $currentTrendingBlogs) ? 'checked' : '' }}
                                                       {{ count($currentTrendingBlogs) >= 5 && !in_array($blog->id, $currentTrendingBlogs) ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="blog_{{ $blog->id }}">
                                                    {{ $blog->title }}
                                                    @if($blog->category)
                                                        <small class="text-muted d-block">({{ $blog->category->title }})</small>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-muted mt-2">
                                    Showing <span id="blogCount">{{ $allBlogs->count() }}</span> of {{ $allBlogs->count() }} blogs
                                </div>
                            @else
                                <div class="alert alert-info">
                                    No blogs available for your region. This might be because:
                                    <ul>
                                        <li>No blogs exist in the system</li>
                                        <li>Blogs exist but don't belong to your region</li>
                                        <li>Blogs haven't been configured with region settings</li>
                                    </ul>
                                </div>
                            @endif
                            
                            @if($errors->has('trending_blogs'))
                                <div class="text-danger mt-1">{{ $errors->first('trending_blogs') }}</div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Save Trending Items</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// JavaScript to limit selections to 5 per section
document.addEventListener('DOMContentLoaded', function() {
    // Store max selections
    const storeCheckboxes = document.querySelectorAll('input[name="trending_stores[]"]');
    const categoryCheckboxes = document.querySelectorAll('input[name="trending_categories[]"]');
    const offerCheckboxes = document.querySelectorAll('input[name="trending_offers[]"]');
    const blogCheckboxes = document.querySelectorAll('input[name="trending_blogs[]"]');
    
    function updateCheckboxStates(checkboxes) {
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        
        checkboxes.forEach(checkbox => {
            if (!checkbox.checked) {
                checkbox.disabled = checkedCount >= 5; // Fixed to 5 instead of 10
            }
        });
    }
    
    // Add event listeners to all checkboxes
    [storeCheckboxes, categoryCheckboxes, offerCheckboxes, blogCheckboxes].forEach(checkboxes => {
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                updateCheckboxStates(checkboxes);
            });
        });
        
        // Initial call to update states
        updateCheckboxStates(checkboxes);
    });

    // Search and filter functionality
    function filterItems(searchInputId, containerId, itemClass) {
        const searchInput = document.getElementById(searchInputId);
        const container = document.getElementById(containerId);
        if (!searchInput || !container) return; // Guard clause if elements don't exist
        
        const items = container.querySelectorAll(`.${itemClass}`);
        
        // Store original state for filters
        const originalItems = Array.from(items);

        // Combined filter function
        function applyFilters() {
            const searchTerm = searchInput.value.toLowerCase();
            let filterValue = '';
            
            // Get the corresponding filter dropdown value
            if (containerId === 'storesContainer') {
                const categoryFilter = document.getElementById('storeCategoryFilter');
                if (categoryFilter) filterValue = categoryFilter.value;
            } else if (containerId === 'offersContainer') {
                const storeFilter = document.getElementById('offerStoreFilter');
                if (storeFilter) filterValue = storeFilter.value;
            }

            items.forEach(item => {
                const title = item.getAttribute('data-title') || '';
                const type = item.getAttribute('data-type') || '';
                const categoryOrStore = containerId === 'storesContainer' ? 
                    (item.getAttribute('data-category') || 'none') : 
                    (item.getAttribute('data-store') || 'none');

                // Check if item matches search
                const matchesSearch = title.includes(searchTerm) || type.includes(searchTerm);
                
                // Determine the attribute to check for filtering (category for stores/blogs, store for offers)
                let itemFilterValue = 'none';
                if (containerId === 'storesContainer' || containerId === 'blogsContainer') {
                    itemFilterValue = item.getAttribute('data-category') || 'none';
                } else if (containerId === 'offersContainer') {
                    itemFilterValue = item.getAttribute('data-store') || 'none';
                }

                // Check if item matches category/store filter (if filter is set)
                const matchesFilter = filterValue === '' || itemFilterValue === filterValue;
                
                // Show item only if it matches both conditions
                item.style.display = (matchesSearch && matchesFilter) ? 'block' : 'none';
            });
            
            updateCountDisplay(containerId);
        }

        // Apply filters when search input changes
        searchInput.addEventListener('input', applyFilters);
        
        // Also apply filters when a filter dropdown changes
        if (containerId === 'storesContainer') {
            const categoryFilter = document.getElementById('storeCategoryFilter');
            if (categoryFilter) categoryFilter.addEventListener('change', applyFilters);
        } else if (containerId === 'offersContainer') {
            const storeFilter = document.getElementById('offerStoreFilter');
            if (storeFilter) storeFilter.addEventListener('change', applyFilters);
        } else if (containerId === 'blogsContainer') {
            const blogCategoryFilter = document.getElementById('blogCategoryFilter');
            if (blogCategoryFilter) blogCategoryFilter.addEventListener('change', applyFilters);
        }
        
        // Initial count display
        updateCountDisplay(containerId);
    }
    
    // Update count display
    function updateCountDisplay(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        const visibleItems = container.querySelectorAll(`.${containerId.replace('Container', '-item')}:not([style*="display: none"])`);
        const countElement = document.getElementById(containerId.replace('Container', 'Count'));
        
        if (countElement) {
            countElement.textContent = visibleItems.length;
        }
    }

    // Initialize search and filter for each section
    filterItems('storeSearch', 'storesContainer', 'store-item');
    filterItems('categorySearch', 'categoriesContainer', 'category-item');
    filterItems('offerSearch', 'offersContainer', 'offer-item');
    filterItems('blogSearch', 'blogsContainer', 'blog-item');

    // Function to clear search
    window.clearSearch = function(inputId) {
        const searchInput = document.getElementById(inputId);
        if (!searchInput) return;
        
        searchInput.value = '';
        
        // Also reset the corresponding filter dropdown if exists
        if (inputId === 'storeSearch') {
            const filterSelect = document.getElementById('storeCategoryFilter');
            if (filterSelect) filterSelect.value = '';
        } else if (inputId === 'offerSearch') {
            const filterSelect = document.getElementById('offerStoreFilter');
            if (filterSelect) filterSelect.value = '';
        } else if (inputId === 'blogSearch') {
            const filterSelect = document.getElementById('blogCategoryFilter');
            if (filterSelect) filterSelect.value = '';
        }
        
        // Trigger input event to refresh the display
        const event = new Event('input');
        searchInput.dispatchEvent(event);
    }
});
</script>
@endsection