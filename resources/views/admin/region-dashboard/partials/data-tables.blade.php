<div class="row">
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
                                            <a href="{{ route('admin.store.show', $store->id) }}" class="text-body fw-bold">{{ Str::limit($store->title, 20) }}</a>
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