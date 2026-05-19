@extends('admin.layouts.app')

@section('title', 'View Store')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="card-title">Store Details: {{ $store->title }}</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.stores.index') }}" class="btn btn-secondary">Back to Stores</a>
                            <a href="{{ route('admin.stores.edit', $store->id) }}" class="btn btn-primary">Edit Store</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>ID:</th>
                                    <td>{{ $store->id }}</td>
                                </tr>
                                <tr>
                                    <th>Title:</th>
                                    <td>{{ $store->title }}</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td>{{ $store->category ? $store->category->title : 'None' }}</td>
                                </tr>
                                <tr>
                                    <th>URL Slug:</th>
                                    <td>{{ $store->url_slug }}</td>
                                </tr>
                                <tr>
                                    <th>SEO Title:</th>
                                    <td>{{ $store->seo_title ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>SEO Meta Keywords:</th>
                                    <td>{{ $store->seo_meta_keyword ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Meta Description:</th>
                                    <td>{{ $store->meta_description ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>H1 Title:</th>
                                    <td>{{ $store->title_h1 ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>H2 Subtitle:</th>
                                    <td>{{ $store->subtitle_h2 ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Logo:</th>
                                    <td><img src="{{ asset( substr($store->logo, 0, 8) === 'uploads/' ? $store->logo : 'uploads/' . $store->logo ) }}" alt="{{ $store->title }}"></td>
                                </tr>
                                <tr>
                                    <th>Image Alt Text:</th>
                                    <td>{{ $store->image_alt ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Image Title:</th>
                                    <td>{{ $store->image_title ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Meta Robots:</th>
                                    <td>{{ $store->meta_robots ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge {{ $store->active ? 'badge-success' : 'badge-danger' }}">
                                            {{ $store->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Sort Order:</th>
                                    <td>{{ $store->sort }}</td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ $store->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At:</th>
                                    <td>{{ $store->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Created By:</th>
                                    <td>{{ $store->creator ? $store->creator->name : 'System' }}</td>
                                </tr>
                                <tr>
                                    <th>Updated By:</th>
                                    <td>{{ $store->updater ? $store->updater->name : 'System' }}</td>
                                </tr>
                                <tr>
                                    <th>Play Store:</th>
                                    <td>{{ $store->play_store ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>App Store:</th>
                                    <td>{{ $store->app_store ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Content Body</h5>
                            <div class="border p-3">
                                {{ $store->content_body ?? 'No content provided' }}
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Affiliate Links</h5>
                            <div class="border p-3">
                                {{ $store->affiliate_links ?? 'No affiliate links provided' }}
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Contact Details</h5>
                            <div class="border p-3">
                                {{ $store->contact_details ?? 'No contact details provided' }}
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Regions</h5>
                            <div class="border p-3">
                                @if($store->country_codes_collection->count() > 0)
                                    <ul class="list-unstyled">
                                        @foreach($store->country_codes_collection as $region)
                                            <li>{{ $region->country }} ({{ $region->code }})</li>
                                        @endforeach
                                    </ul>
                                @else
                                    No regions specified
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQs Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0 text-white">
                        <i class="fas fa-question-circle me-2"></i>Frequently Asked Questions
                    </h4>
                </div>
                <div class="card-body">
                    @if($store->faqs->count() > 0)
                        <div class="row">
                            @foreach($store->faqs as $index => $faq)
                                <div class="col-12 mb-3">
                                    <div class="card border-start border-primary border-4 shadow-sm">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0 d-flex align-items-center">
                                                <span class="badge bg-primary me-2">Q{{ $index + 1 }}</span>
                                                <strong>{{ $faq->question }}</strong>
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <p class="mb-0"><strong class="text-success">Answer:</strong></p>
                                                <div class="mt-2 p-3 bg-light rounded">
                                                    {{ $faq->answer }}
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center border-top pt-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-sort me-1"></i>Sort Order: <span class="badge bg-secondary">{{ $faq->sort }}</span>
                                                </small>
                                                <small class="text-muted">
                                                    <i class="far fa-clock me-1"></i>Created: {{ $faq->created_at->format('M d, Y H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>No FAQs available for this store.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Social Links Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Social Links</h4>
                </div>
                <div class="card-body">
                    @if($store->socialLinks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Social App</th>
                                        <th>Link</th>
                                        <th>Sort Order</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($store->socialLinks as $link)
                                        <tr>
                                            <td>
                                                @if($link->socialApp && $link->socialApp->logo)
                                                    <img src="{{ asset('uploads/' . $link->socialApp->logo) }}" alt="{{ $link->socialApp->title }}" width="30" class="me-2">
                                                @endif
                                                {{ $link->socialApp->title ?? 'N/A' }}
                                            </td>
                                            <td><a href="{{ $link->link }}" target="_blank">{{ $link->link }}</a></td>
                                            <td>{{ $link->sort }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>No Social Links available for this store.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection