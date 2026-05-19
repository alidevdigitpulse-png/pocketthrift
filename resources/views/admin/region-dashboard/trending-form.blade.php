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
                            <p class="text-muted">Select up to 12 stores, 6 categories, 9 offers, and 8 blogs to display as trending on the homepage</p>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.region.dashboard.trending.save') }}">
                        @csrf
                        <input type="hidden" name="region_id" value="{{ $regionId }}">

                        
                        {{-- Tab Navigation --}}
                        <ul class="nav nav-tabs mb-3" id="trendingTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="stores-tab" data-toggle="tab" href="#stores" role="tab" aria-controls="stores" aria-selected="true">
                                    <i class="fa fa-store me-2"></i>Stores
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="categories-tab" data-toggle="tab" href="#categories" role="tab" aria-controls="categories" aria-selected="false">
                                    <i class="fa fa-list me-2"></i>Categories
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="offers-tab" data-toggle="tab" href="#offers" role="tab" aria-controls="offers" aria-selected="false">
                                    <i class="fa fa-tags me-2"></i>Offers
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="blogs-tab" data-toggle="tab" href="#blogs" role="tab" aria-controls="blogs" aria-selected="false">
                                    <i class="fa fa-blog me-2"></i>Blogs
                                </a>
                            </li>
                        </ul>

                        {{-- Tab Content --}}
                        <div class="tab-content" id="trendingTabsContent">
                            {{-- Trending Stores Tab --}}
                            <div class="tab-pane fade show active" id="stores" role="tabpanel" aria-labelledby="stores-tab">
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5>Trending Stores <span class="badge bg-primary" id="storeSelectionCount">{{ count($currentTrendingStores) }}/12</span></h5>
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
                                    
                                    {{-- Selected Stores Tags --}}
                                    <div class="mb-3">
                                        <h6 class="mb-2">Selected Stores:</h6>
                                        <div id="selectedStoresTags" class="d-flex flex-wrap gap-2">
                                            {{-- Tags will be added here dynamically --}}
                                        </div>
                                    </div>
                                    
                                    <p class="text-muted">Select up to 12 stores that should appear as trending</p>
                                    
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
                                                               {{ count($currentTrendingStores) >= 12 && !in_array($store->id, $currentTrendingStores) ? 'disabled' : '' }}>
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
                                            No stores available for your region.
                                        </div>
                                    @endif
                                    
                                    @if($errors->has('trending_stores'))
                                        <div class="text-danger mt-1">{{ $errors->first('trending_stores') }}</div>
                                    @endif
                                </div>
                            </div>

                            {{-- Trending Categories Tab --}}
                            <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5>Trending Categories <span class="badge bg-primary" id="categorySelectionCount">{{ count($currentTrendingCategories) }}/6</span></h5>
                                        <div class="input-group" style="max-width: 300px;">
                                            <input type="text" class="form-control" id="categorySearch" placeholder="{{ __('Search categories...') }}">
                                            <button class="btn btn-outline-secondary" type="button" onclick="clearSearch('categorySearch')">Clear</button>
                                        </div>
                                    </div>
                                    
                                    {{-- Selected Categories Tags --}}
                                    <div class="mb-3">
                                        <h6 class="mb-2">Selected Categories:</h6>
                                        <div id="selectedCategoriesTags" class="d-flex flex-wrap gap-2">
                                            {{-- Tags will be added here dynamically --}}
                                        </div>
                                    </div>
                                    
                                    <p class="text-muted">Select up to 6 categories that should appear as trending</p>
                                    
                                    @if($allCategories->count() > 0)
                                        @if(!empty($categoriesFallback) && $categoriesFallback)
                                            <div class="alert alert-warning">
                                                No region-specific categories were found. Showing global active categories as a fallback.
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
                                                               {{ count($currentTrendingCategories) >= 6 && !in_array($category->id, $currentTrendingCategories) ? 'disabled' : '' }}>
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
                                            No categories available for your region.
                                        </div>
                                    @endif
                                    
                                    @if($errors->has('trending_categories'))
                                        <div class="text-danger mt-1">{{ $errors->first('trending_categories') }}</div>
                                    @endif
                                </div>
                            </div>

                            {{-- Trending Offers Tab --}}
                            <div class="tab-pane fade" id="offers" role="tabpanel" aria-labelledby="offers-tab">
                                <div class="mb-4">
                                    <h5>Trending Offers - Row Selection</h5>
                                    <p class="text-muted">Select up to 9 offers for each carousel row on the homepage</p>
                                    
                                    {{-- Row Tabs --}}
                                    <ul class="nav nav-pills mb-3" id="offerRowTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active" id="row1-tab" data-toggle="tab" href="#row1" role="tab" aria-controls="row1" aria-selected="true">
                                                Row 1
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="row2-tab" data-toggle="tab" href="#row2" role="tab" aria-controls="row2" aria-selected="false">
                                                Row 2
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="row3-tab" data-toggle="tab" href="#row3" role="tab" aria-controls="row3" aria-selected="false">
                                                Row 3
                                            </a>
                                        </li>
                                    </ul>

                                    {{-- Row Tab Content --}}
                                    <div class="tab-content" id="offerRowTabsContent">
                                        {{-- Row 1 --}}
                                        <div class="tab-pane fade show active" id="row1" role="tabpanel" aria-labelledby="row1-tab">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6>Row 1 Offers <span class="badge bg-primary" id="row1SelectionCount">{{ count($currentTrendingOffersRow1) }}/9</span></h6>
                                            </div>

                                            {{-- Selected Offers Tags --}}
                                            <div class="mb-4" id="row1SelectedOffersContainer">
                                                <h6 class="mb-2">Selected Offers:</h6>
                                                <div id="row1SelectedOffersTags" class="d-flex flex-wrap gap-2">
                                                    {{-- Tags will be added here dynamically --}}
                                                </div>
                                            </div>

                                            {{-- Store Selection Grid --}}
                                            <div class="mb-3">
                                                <h6 class="mb-2">Select a Store to View Offers:</h6>
                                                <div class="input-group mb-3" style="max-width: 300px;">
                                                    <input type="text" class="form-control" id="storeSearchRow1" placeholder="Search stores...">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="clearSearch('storeSearchRow1')">Clear</button>
                                                </div>
                                                
                                                @if($availableOfferStores->count() > 0)
                                                    <div class="row g-2" id="storesGridRow1">
                                                        @foreach($availableOfferStores as $store)
                                                            <div class="col-md-3 col-sm-4 col-6 store-item-row1" data-title="{{ strtolower($store->title) }}">
                                                                <div class="card h-100 store-card" data-store-id="{{ $store->id }}" style="cursor: pointer;">
                                                                    <div class="card-body text-center p-2">
                                                                        @if($store->logo)
                                                                            <img src="{{ asset('uploads/' . $store->logo) }}" alt="{{ $store->title }}" class="img-fluid mb-2" style="max-height: 60px; object-fit: contain;">
                                                                        @else
                                                                            <i class="fas fa-store fa-2x mb-2 text-muted"></i>
                                                                        @endif
                                                                        <p class="mb-0 small fw-bold">{{ $store->title }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="alert alert-info">No stores available for your region.</div>
                                                @endif
                                            </div>

                                            {{-- Offers Selection Area (Initially Hidden) --}}
                                            <div id="row1OffersContainer" style="display: none;">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0">Offers from <span id="row1CurrentStoreName"></span></h6>
                                                    <button type="button" class="btn btn-sm btn-secondary" onclick="closeOffersRow1()">Back to Stores</button>
                                                </div>
                                                <div class="row g-2 mt-2" id="row1OffersList">
                                                    {{-- Offers will be loaded here via AJAX --}}
                                                </div>
                                            </div>

                                            {{-- Hidden inputs for form submission --}}
                                            <div id="row1HiddenInputs">
                                                @foreach($currentTrendingOffersRow1 as $offerId)
                                                    <input type="hidden" name="trending_offers_row1[]" value="{{ $offerId }}">
                                                @endforeach
                                            </div>
                                        </div>

                                        {{-- Row 2 --}}
                                        <div class="tab-pane fade" id="row2" role="tabpanel" aria-labelledby="row2-tab">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6>Row 2 Offers <span class="badge bg-primary" id="row2SelectionCount">{{ count($currentTrendingOffersRow2) }}/9</span></h6>
                                            </div>

                                            {{-- Selected Offers Tags --}}
                                            <div class="mb-4" id="row2SelectedOffersContainer">
                                                <h6 class="mb-2">Selected Offers:</h6>
                                                <div id="row2SelectedOffersTags" class="d-flex flex-wrap gap-2">
                                                    {{-- Tags will be added here dynamically --}}
                                                </div>
                                            </div>

                                            {{-- Store Selection Grid --}}
                                            <div class="mb-3">
                                                <h6 class="mb-2">Select a Store to View Offers:</h6>
                                                <div class="input-group mb-3" style="max-width: 300px;">
                                                    <input type="text" class="form-control" id="storeSearchRow2" placeholder="Search stores...">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="clearSearch('storeSearchRow2')">Clear</button>
                                                </div>
                                                
                                                @if($availableOfferStores->count() > 0)
                                                    <div class="row g-2" id="storesGridRow2">
                                                        @foreach($availableOfferStores as $store)
                                                            <div class="col-md-3 col-sm-4 col-6 store-item-row2" data-title="{{ strtolower($store->title) }}">
                                                                <div class="card h-100 store-card" data-store-id="{{ $store->id }}" style="cursor: pointer;">
                                                                    <div class="card-body text-center p-2">
                                                                        @if($store->logo)
                                                                            <img src="{{ asset('uploads/' . $store->logo) }}" alt="{{ $store->title }}" class="img-fluid mb-2" style="max-height: 60px; object-fit: contain;">
                                                                        @else
                                                                            <i class="fas fa-store fa-2x mb-2 text-muted"></i>
                                                                        @endif
                                                                        <p class="mb-0 small fw-bold">{{ $store->title }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="alert alert-info">No stores available for your region.</div>
                                                @endif
                                            </div>

                                            {{-- Offers Selection Area (Initially Hidden) --}}
                                            <div id="row2OffersContainer" style="display: none;">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0">Offers from <span id="row2CurrentStoreName"></span></h6>
                                                    <button type="button" class="btn btn-sm btn-secondary" onclick="closeOffersRow2()">Back to Stores</button>
                                                </div>
                                                <div class="row g-2 mt-2" id="row2OffersList">
                                                    {{-- Offers will be loaded here via AJAX --}}
                                                </div>
                                            </div>

                                            {{-- Hidden inputs for form submission --}}
                                            <div id="row2HiddenInputs">
                                                @foreach($currentTrendingOffersRow2 as $offerId)
                                                    <input type="hidden" name="trending_offers_row2[]" value="{{ $offerId }}">
                                                @endforeach
                                            </div>
                                        </div>

                                        {{-- Row 3 --}}
                                        <div class="tab-pane fade" id="row3" role="tabpanel" aria-labelledby="row3-tab">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6>Row 3 Offers <span class="badge bg-primary" id="row3SelectionCount">{{ count($currentTrendingOffersRow3) }}/9</span></h6>
                                            </div>

                                            {{-- Selected Offers Tags --}}
                                            <div class="mb-4" id="row3SelectedOffersContainer">
                                                <h6 class="mb-2">Selected Offers:</h6>
                                                <div id="row3SelectedOffersTags" class="d-flex flex-wrap gap-2">
                                                    {{-- Tags will be added here dynamically --}}
                                                </div>
                                            </div>

                                            {{-- Store Selection Grid --}}
                                            <div class="mb-3">
                                                <h6 class="mb-2">Select a Store to View Offers:</h6>
                                                <div class="input-group mb-3" style="max-width: 300px;">
                                                    <input type="text" class="form-control" id="storeSearchRow3" placeholder="Search stores...">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="clearSearch('storeSearchRow3')">Clear</button>
                                                </div>
                                                
                                                @if($availableOfferStores->count() > 0)
                                                    <div class="row g-2" id="storesGridRow3">
                                                        @foreach($availableOfferStores as $store)
                                                            <div class="col-md-3 col-sm-4 col-6 store-item-row3" data-title="{{ strtolower($store->title) }}">
                                                                <div class="card h-100 store-card" data-store-id="{{ $store->id }}" style="cursor: pointer;">
                                                                    <div class="card-body text-center p-2">
                                                                        @if($store->logo)
                                                                            <img src="{{ asset('uploads/' . $store->logo) }}" alt="{{ $store->title }}" class="img-fluid mb-2" style="max-height: 60px; object-fit: contain;">
                                                                        @else
                                                                            <i class="fas fa-store fa-2x mb-2 text-muted"></i>
                                                                        @endif
                                                                        <p class="mb-0 small fw-bold">{{ $store->title }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="alert alert-info">No stores available for your region.</div>
                                                @endif
                                            </div>

                                            {{-- Offers Selection Area (Initially Hidden) --}}
                                            <div id="row3OffersContainer" style="display: none;">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0">Offers from <span id="row3CurrentStoreName"></span></h6>
                                                    <button type="button" class="btn btn-sm btn-secondary" onclick="closeOffersRow3()">Back to Stores</button>
                                                </div>
                                                <div class="row g-2 mt-2" id="row3OffersList">
                                                    {{-- Offers will be loaded here via AJAX --}}
                                                </div>
                                            </div>

                                            {{-- Hidden inputs for form submission --}}
                                            <div id="row3HiddenInputs">
                                                @foreach($currentTrendingOffersRow3 as $offerId)
                                                    <input type="hidden" name="trending_offers_row3[]" value="{{ $offerId }}">
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Trending Blogs Tab --}}
                            <div class="tab-pane fade" id="blogs" role="tabpanel" aria-labelledby="blogs-tab">
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5>Trending Blogs <span class="badge bg-primary" id="blogSelectionCount">{{ count($currentTrendingBlogs) }}/8</span></h5>
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
                                    
                                    {{-- Selected Blogs Tags --}}
                                    <div class="mb-3">
                                        <h6 class="mb-2">Selected Blogs:</h6>
                                        <div id="selectedBlogsTags" class="d-flex flex-wrap gap-2">
                                            {{-- Tags will be added here dynamically --}}
                                        </div>
                                    </div>
                                    
                                    <p class="text-muted">Select up to 8 blogs that should appear as trending</p>
                                    
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
                                                               {{ count($currentTrendingBlogs) >= 8 && !in_array($blog->id, $currentTrendingBlogs) ? 'disabled' : '' }}>
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
                                            No blogs available for your region.
                                        </div>
                                    @endif
                                    
                                    @if($errors->has('trending_blogs'))
                                        <div class="text-danger mt-1">{{ $errors->first('trending_blogs') }}</div>
                                    @endif
                                </div>
                            </div>
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
// JavaScript for trending items management with two-step offer selection
document.addEventListener('DOMContentLoaded', function() {
    const regionId = {{ $regionId ?? 'null' }};
    
    // Store selected offers for each row
    const selectedOffersRow1 = new Map(); // offerId => {title, type, discount, storeId, storeName}
    const selectedOffersRow2 = new Map();
    const selectedOffersRow3 = new Map();
    
    // Load existing selections from hidden inputs
    @foreach($currentTrendingOffersRow1 as $offerId)
        selectedOffersRow1.set({{ $offerId }}, {id: {{ $offerId }}, title: 'Offer #{{ $offerId }}', type: '', discount: '', storeId: null, storeName: ''});
    @endforeach
    
    @foreach($currentTrendingOffersRow2 as $offerId)
        selectedOffersRow2.set({{ $offerId }}, {id: {{ $offerId }}, title: 'Offer #{{ $offerId }}', type: '', discount: '', storeId: null, storeName: ''});
    @endforeach
    
    @foreach($currentTrendingOffersRow3 as $offerId)
        selectedOffersRow3.set({{ $offerId }}, {id: {{ $offerId }}, title: 'Offer #{{ $offerId }}', type: '', discount: '', storeId: null, storeName: ''});
    @endforeach
    
    // Initialize tags display for existing selections
    updateTagsDisplay('row1');
    updateTagsDisplay('row2');
    updateTagsDisplay('row3');
    
    // Store checkboxes and category checkboxes (unchanged)
    const storeCheckboxes = document.querySelectorAll('input[name="trending_stores[]"]');
    const categoryCheckboxes = document.querySelectorAll('input[name="trending_categories[]"]');
    const blogCheckboxes = document.querySelectorAll('input[name="trending_blogs[]"]');
    
    function updateCheckboxStates(checkboxes, maxCount) {
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        checkboxes.forEach(checkbox => {
            if (!checkbox.checked) {
                checkbox.disabled = checkedCount >= maxCount;
            }
        });
    }
    
    // Add event listeners for stores, categories, and blogs with tag management
    [
        { checkboxes: storeCheckboxes, max: 12, type: 'store', badgeId: 'storeSelectionCount', tagsId: 'selectedStoresTags' },
        { checkboxes: categoryCheckboxes, max: 6, type: 'category', badgeId: 'categorySelectionCount', tagsId: 'selectedCategoriesTags' },
        { checkboxes: blogCheckboxes, max: 8, type: 'blog', badgeId: 'blogSelectionCount', tagsId: 'selectedBlogsTags' }
    ].forEach(({ checkboxes, max, type, badgeId, tagsId }) => {
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateCheckboxStates(checkboxes, max);
                updateSelectionBadge(checkboxes, badgeId, max);
                updateItemTags(type, tagsId);
            });
        });
        updateCheckboxStates(checkboxes, max);
        updateSelectionBadge(checkboxes, badgeId, max);
        updateItemTags(type, tagsId);
    });
    
    // Update selection count badge
    function updateSelectionBadge(checkboxes, badgeId, maxCount) {
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        const badge = document.getElementById(badgeId);
        if (badge) {
            badge.textContent = `${checkedCount}/${maxCount}`;
        }
    }
    
    // Update tags display for stores, categories, and blogs
    function updateItemTags(type, tagsId) {
        const tagsContainer = document.getElementById(tagsId);
        if (!tagsContainer) return;
        
        const checkboxes = document.querySelectorAll(`input[name="trending_${type}s[]"]:checked`);
        
        if (checkboxes.length === 0) {
            tagsContainer.innerHTML = '<span class="text-muted">No items selected yet</span>';
            return;
        }
        
        let html = '';
        checkboxes.forEach(checkbox => {
            const label = document.querySelector(`label[for="${checkbox.id}"]`);
            const title = label ? label.textContent.trim().split('\n')[0].trim() : `${type} #${checkbox.value}`;
            
            html += `
                <span class="badge bg-primary d-flex align-items-center gap-1" style="font-size: 0.9rem; padding: 0.5rem; margin: 3px;">
                    ${title}
                    <button type="button" class="btn-close btn-close-white" style="font-size: 0.6rem;" onclick="removeItem('${type}', ${checkbox.value})" aria-label="Remove"></button>
                </span>
            `;
        });
        
        tagsContainer.innerHTML = html;
    }
    
    // Remove item (called from tag close button)
    window.removeItem = function(type, itemId) {
        const checkbox = document.getElementById(`${type}_${itemId}`);
        if (checkbox) {
            checkbox.checked = false;
            checkbox.dispatchEvent(new Event('change'));
        }
    };
    
    
    // Store card click handlers for all rows
    document.querySelectorAll('#storesGridRow1 .store-card').forEach(card => {
        card.addEventListener('click', function() {
            const storeId = this.dataset.storeId;
            const storeName = this.querySelector('p').textContent;
            loadStoreOffers(storeId, storeName, 'row1');
        });
    });
    
    document.querySelectorAll('#storesGridRow2 .store-card').forEach(card => {
        card.addEventListener('click', function() {
            const storeId = this.dataset.storeId;
            const storeName = this.querySelector('p').textContent;
            loadStoreOffers(storeId, storeName, 'row2');
        });
    });
    
    document.querySelectorAll('#storesGridRow3 .store-card').forEach(card => {
        card.addEventListener('click', function() {
            const storeId = this.dataset.storeId;
            const storeName = this.querySelector('p').textContent;
            loadStoreOffers(storeId, storeName, 'row3');
        });
    });
    
    // Load offers for a specific store via AJAX
    function loadStoreOffers(storeId, storeName, row) {
        const offersContainer = document.getElementById(`${row}OffersContainer`);
        const offersList = document.getElementById(`${row}OffersList`);
        const storeNameSpan = document.getElementById(`${row}CurrentStoreName`);
        const storesGrid = document.getElementById(`storesGrid${row.charAt(0).toUpperCase() + row.slice(1)}`);
        
        // Show loading state
        offersList.innerHTML = '<div class="col-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        offersContainer.style.display = 'block';
        storesGrid.parentElement.style.display = 'none';
        storeNameSpan.textContent = storeName;
        
        // Fetch offers via AJAX
        fetch(`{{ route('admin.region.dashboard.trending.store-offers') }}?store_id=${storeId}&region_id=${regionId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.offers) {
                    displayOffers(data.offers, storeId, storeName, row);
                } else {
                    offersList.innerHTML = '<div class="col-12"><div class="alert alert-warning">No offers found for this store.</div></div>';
                }
            })
            .catch(error => {
                console.error('Error loading offers:', error);
                offersList.innerHTML = '<div class="col-12"><div class="alert alert-danger">Error loading offers. Please try again.</div></div>';
            });
    }
    
    // Display offers in the selection area
    function displayOffers(offers, storeId, storeName, row) {
        const offersList = document.getElementById(`${row}OffersList`);
        const selectedMap = getSelectedMap(row);
        
        if (offers.length === 0) {
            offersList.innerHTML = '<div class="col-12"><div class="alert alert-info">No offers available for this store.</div></div>';
            return;
        }
        
        let html = '';
        offers.forEach(offer => {
            const isSelected = selectedMap.has(offer.id);
            const isDisabled = selectedMap.size >= 9 && !isSelected;
            
            html += `
                <div class="col-md-6 col-lg-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input offer-checkbox-${row}" 
                               type="checkbox" 
                               value="${offer.id}" 
                               id="offer_${row}_${offer.id}"
                               data-offer-title="${offer.title}"
                               data-offer-type="${offer.type || ''}"
                               data-offer-discount="${offer.discount || ''}"
                               data-store-id="${storeId}"
                               data-store-name="${storeName}"
                               ${isSelected ? 'checked' : ''}
                               ${isDisabled ? 'disabled' : ''}>
                        <label class="form-check-label" for="offer_${row}_${offer.id}">
                            ${offer.title}
                            ${offer.type ? `<span class="badge bg-secondary ms-1">${offer.type}</span>` : ''}
                            ${offer.discount ? `<small class="text-muted d-block">${offer.discount}</small>` : ''}
                        </label>
                    </div>
                </div>
            `;
        });
        
        offersList.innerHTML = html;
        
        // Add event listeners to checkboxes
        document.querySelectorAll(`.offer-checkbox-${row}`).forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                handleOfferSelection(this, row);
            });
        });
    }
    
    // Handle offer selection/deselection
    function handleOfferSelection(checkbox, row) {
        const selectedMap = getSelectedMap(row);
        const offerId = parseInt(checkbox.value);
        
        if (checkbox.checked) {
            if (selectedMap.size < 9) {
                selectedMap.set(offerId, {
                    id: offerId,
                    title: checkbox.dataset.offerTitle,
                    type: checkbox.dataset.offerType,
                    discount: checkbox.dataset.offerDiscount,
                    storeId: checkbox.dataset.storeId,
                    storeName: checkbox.dataset.storeName
                });
                updateTagsDisplay(row);
                updateHiddenInputs(row);
                updateSelectionCount(row);
            } else {
                checkbox.checked = false;
                alert('Maximum 9 offers can be selected for this row.');
            }
        } else {
            selectedMap.delete(offerId);
            updateTagsDisplay(row);
            updateHiddenInputs(row);
            updateSelectionCount(row);
        }
        
        // Update disabled state of other checkboxes
        document.querySelectorAll(`.offer-checkbox-${row}`).forEach(cb => {
            if (!cb.checked) {
                cb.disabled = selectedMap.size >= 9;
            }
        });
    }
    
    // Update tags display
    function updateTagsDisplay(row) {
        const selectedMap = getSelectedMap(row);
        const tagsContainer = document.getElementById(`${row}SelectedOffersTags`);
        
        if (selectedMap.size === 0) {
            tagsContainer.innerHTML = '<span class="text-muted">No offers selected yet</span>';
            return;
        }
        
        let html = '';
        selectedMap.forEach((offer, offerId) => {
            html += `
                <span class="badge bg-primary d-flex align-items-center gap-1" style="font-size: 0.9rem; padding: 0.5rem; margin: 3px;">
                    ${offer.title}
                    <button type="button" class="btn-close btn-close-white" style="font-size: 0.6rem;" onclick="removeOffer(${offerId}, '${row}')" aria-label="Remove"></button>
                </span>
            `;
        });
        
        tagsContainer.innerHTML = html;
    }
    
    // Update hidden inputs for form submission
    function updateHiddenInputs(row) {
        const selectedMap = getSelectedMap(row);
        const hiddenInputsContainer = document.getElementById(`${row}HiddenInputs`);
        
        let html = '';
        selectedMap.forEach((offer, offerId) => {
            html += `<input type="hidden" name="trending_offers_${row}[]" value="${offerId}">`;
        });
        
        hiddenInputsContainer.innerHTML = html;
    }
    
    // Update selection count badge
    function updateSelectionCount(row) {
        const selectedMap = getSelectedMap(row);
        const badge = document.getElementById(`${row}SelectionCount`);
        if (badge) {
            badge.textContent = `${selectedMap.size}/9`;
        }
    }
    
    // Get the appropriate selected map for a row
    function getSelectedMap(row) {
        if (row === 'row1') return selectedOffersRow1;
        if (row === 'row2') return selectedOffersRow2;
        if (row === 'row3') return selectedOffersRow3;
    }
    
    // Remove offer (called from tag close button)
    window.removeOffer = function(offerId, row) {
        const selectedMap = getSelectedMap(row);
        selectedMap.delete(offerId);
        updateTagsDisplay(row);
        updateHiddenInputs(row);
        updateSelectionCount(row);
        
        // Update checkbox if it's visible
        const checkbox = document.getElementById(`offer_${row}_${offerId}`);
        if (checkbox) {
            checkbox.checked = false;
        }
        
        // Update disabled state of other checkboxes
        document.querySelectorAll(`.offer-checkbox-${row}`).forEach(cb => {
            if (!cb.checked) {
                cb.disabled = selectedMap.size >= 9;
            }
        });
    };
    
    // Close offers view and return to stores
    window.closeOffersRow1 = function() {
        document.getElementById('row1OffersContainer').style.display = 'none';
        document.getElementById('storesGridRow1').parentElement.style.display = 'block';
    };
    
    window.closeOffersRow2 = function() {
        document.getElementById('row2OffersContainer').style.display = 'none';
        document.getElementById('storesGridRow2').parentElement.style.display = 'block';
    };
    
    window.closeOffersRow3 = function() {
        document.getElementById('row3OffersContainer').style.display = 'none';
        document.getElementById('storesGridRow3').parentElement.style.display = 'block';
    };
    
    // Store search functionality
    function setupStoreSearch(searchInputId, gridId, itemClass) {
        const searchInput = document.getElementById(searchInputId);
        const grid = document.getElementById(gridId);
        
        if (!searchInput || !grid) return;
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const items = grid.querySelectorAll(`.${itemClass}`);
            
            items.forEach(item => {
                const title = item.dataset.title || '';
                item.style.display = title.includes(searchTerm) ? '' : 'none';
            });
        });
    }
    
    setupStoreSearch('storeSearchRow1', 'storesGridRow1', 'store-item-row1');
    setupStoreSearch('storeSearchRow2', 'storesGridRow2', 'store-item-row2');
    setupStoreSearch('storeSearchRow3', 'storesGridRow3', 'store-item-row3');
    
    // Search and filter functionality for stores, categories, and blogs (unchanged)
    function filterItems(searchInputId, containerId, itemClass, filterDropdownId = null) {
        const searchInput = document.getElementById(searchInputId);
        const container = document.getElementById(containerId);
        if (!searchInput || !container) return;
        
        const items = container.querySelectorAll(`.${itemClass}`);
        
        function applyFilters() {
            const searchTerm = searchInput.value.toLowerCase();
            let filterValue = '';
            
            if (filterDropdownId) {
                const filterDropdown = document.getElementById(filterDropdownId);
                if (filterDropdown) filterValue = filterDropdown.value;
            }

            items.forEach(item => {
                const title = item.getAttribute('data-title') || '';
                const type = item.getAttribute('data-type') || '';
                const matchesSearch = title.includes(searchTerm) || type.includes(searchTerm);
                
                let itemFilterValue = 'none';
                if (containerId.includes('stores')) {
                    itemFilterValue = item.getAttribute('data-category') || 'none';
                } else if (containerId.includes('blogs')) {
                    itemFilterValue = item.getAttribute('data-category') || 'none';
                }

                const matchesFilter = filterValue === '' || itemFilterValue === filterValue;
                item.style.display = (matchesSearch && matchesFilter) ? 'block' : 'none';
            });
        }

        searchInput.addEventListener('input', applyFilters);
        
        if (filterDropdownId) {
            const filterDropdown = document.getElementById(filterDropdownId);
            if (filterDropdown) filterDropdown.addEventListener('change', applyFilters);
        }
    }

    filterItems('storeSearch', 'storesContainer', 'store-item', 'storeCategoryFilter');
    filterItems('categorySearch', 'categoriesContainer', 'category-item');
    filterItems('blogSearch', 'blogsContainer', 'blog-item', 'blogCategoryFilter');

    // Function to clear search
    window.clearSearch = function(inputId) {
        const searchInput = document.getElementById(inputId);
        if (!searchInput) return;
        searchInput.value = '';
        const event = new Event('input');
        searchInput.dispatchEvent(event);
    };
});
</script>
@endsection