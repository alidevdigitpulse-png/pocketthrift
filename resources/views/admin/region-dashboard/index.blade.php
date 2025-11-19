@extends('admin.layouts.app')

@section('title', 'Region Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="card-title">Region Dashboard</h4>
                            @if($isAdmin)
                                <p class="text-muted">Showing data from all regions</p>
                            @else
                                <p class="text-muted">Showing data from your assigned regions</p>
                            @endif
                        </div>
                        <div class="col-4 text-end">
                            @if($isAdmin)
                            @else
                                <div class="badge bg-info">
                                    Your Regions: 
                                    @foreach($userRegions as $userRegion)
                                        <span class="badge bg-secondary">{{ $userRegion->country }} ({{ $userRegion->code }})</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Stats Cards -->
                    <div class="row">
                        <div class="col-xl-4 col-12">
                            <div class="card bg-info-gradient">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-uppercase text-white mt-0">Stores</h6>
                                            <h2 class="text-white mb-0">{{ $storesCount }}</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fa-solid fa-store text-white" style="font-size: 36px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-12">
                            <div class="card bg-success">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-uppercase text-white mt-0">Categories</h6>
                                            <h2 class="text-white mb-0">{{ $categoriesCount }}</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fa-solid fa-folder text-white" style="font-size: 36px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-12">
                            <div class="card bg-warning">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-uppercase text-white mt-0">Offers</h6>
                                            <h2 class="text-white mb-0">{{ $offersCount }}</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fa-solid fa-percent text-white" style="font-size: 36px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trending Items Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4>Trending Items</h4>
                                <a href="{{ route('admin.region.dashboard.trending.form') }}" class="btn btn-primary btn-sm">Manage Trending Items</a>
                            </div>
                        </div>
                        
                        <!-- Trending Stores -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Trending Stores</h5>
                                </div>
                                <div class="card-body">
                                    @if($trendingStores->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Store</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($trendingStores as $index => $store)
                                                        <tr>
                                                            <td><span class="badge bg-primary">{{ $index + 1 }}</span></td>
                                                            <td>
                                                                <a href="{{ route('admin.stores.show', $store->id) }}" class="text-body fw-bold">{{ Str::limit($store->title, 20) }}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">No trending stores selected</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Trending Categories -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Trending Categories</h5>
                                </div>
                                <div class="card-body">
                                    @if($trendingCategories->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Category</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($trendingCategories as $index => $category)
                                                        <tr>
                                                            <td><span class="badge bg-primary">{{ $index + 1 }}</span></td>
                                                            <td>
                                                                <a href="{{ route('admin.category.show', $category->id) }}" class="text-body fw-bold">{{ Str::limit($category->title, 20) }}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">No trending categories selected</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Trending Offers -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Trending Offers</h5>
                                </div>
                                <div class="card-body">
                                    @if($trendingOffers->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Offer</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($trendingOffers as $index => $offer)
                                                        <tr>
                                                            <td><span class="badge bg-primary">{{ $index + 1 }}</span></td>
                                                            <td>
                                                                <a href="{{ route('admin.offer.show', $offer->id) }}" class="text-body fw-bold">{{ Str::limit($offer->title, 20) }}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">No trending offers selected</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Items Section -->
                    <div class="row mt-4">
                        <!-- Recent Stores -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Recent Stores</h5>
                                </div>
                                <div class="card-body">
                                    @if($recentStores->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentStores as $store)
                                                        <tr>
                                                            <td>
                                                                <a href="{{ route('admin.stores.show', $store->id) }}" class="text-body fw-bold">{{ Str::limit($store->title, 20) }}</a>
                                                            </td>
                                                            <td>
                                                                <span class="badge {{ $store->active ? 'badge-success' : 'badge-danger' }}">
                                                                    {{ $store->active ? 'Active' : 'Inactive' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">No stores found</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Recent Categories -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Recent Categories</h5>
                                </div>
                                <div class="card-body">
                                    @if($recentCategories->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentCategories as $category)
                                                        <tr>
                                                            <td>
                                                                <a href="{{ route('admin.category.show', $category->id) }}" class="text-body fw-bold">{{ Str::limit($category->title, 20) }}</a>
                                                            </td>
                                                            <td>
                                                                <span class="badge {{ $category->active ? 'badge-success' : 'badge-danger' }}">
                                                                    {{ $category->active ? 'Active' : 'Inactive' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">No categories found</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Recent Offers -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Recent Offers</h5>
                                </div>
                                <div class="card-body">
                                    @if($recentOffers->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Type</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentOffers as $offer)
                                                        <tr>
                                                            <td>
                                                                <a href="{{ route('admin.offer.show', $offer->id) }}" class="text-body fw-bold">{{ Str::limit($offer->title, 20) }}</a>
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $badgeClass = '';
                                                                    switch($offer->type) {
                                                                        case 'Deal': $badgeClass = 'badge-info'; break;
                                                                        case 'Offer': $badgeClass = 'badge-success'; break;
                                                                        case 'Code': $badgeClass = 'badge-warning'; break;
                                                                        default: $badgeClass = 'badge-danger'; break;
                                                                    }
                                                                @endphp
                                                                <span class="badge {{ $badgeClass }}">
                                                                    {{ $offer->type }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">No offers found</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection