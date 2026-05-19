@extends('admin.layouts.app')

@section('title', 'View Offer')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="card-title">Offer Details: {{ $offer->title }}</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.offer.index') }}" class="btn btn-secondary">Back to Offers</a>
                            <a href="{{ route('admin.offer.edit', $offer->id) }}" class="btn btn-primary">Edit Offer</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>ID:</th>
                                    <td>{{ $offer->id }}</td>
                                </tr>
                                <tr>
                                    <th>Title:</th>
                                    <td>{{ $offer->title }}</td>
                                </tr>
                                <tr>
                                    <th>Store:</th>
                                    <td>{{ $offer->store ? $offer->store->title : 'None' }}</td>
                                </tr>
                                <tr>
                                    <th>Seasonal Category:</th>
                                    <td>{{ $offer->seasonal ? $offer->seasonal->title : 'None' }}</td>
                                </tr>
                                <tr>
                                    <th>Code:</th>
                                    <td>{{ $offer->code ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Discount:</th>
                                    <td>{{ $offer->discount ? $offer->discount . '%' : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>
                                        <span class="badge 
                                            @if($offer->type == 'Deal') badge-info
                                            @elseif($offer->type == 'Offer') badge-success
                                            @elseif($offer->type == 'Code') badge-warning
                                            @else badge-danger
                                            @endif">
                                            {{ $offer->type }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Free Delivery:</th>
                                    <td>
                                        @if($offer->free_delivery == 1)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-danger">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Verified:</th>
                                    <td>
                                        @if($offer->verified == 'active')
                                            <span class="badge badge-success">Verified</span>
                                        @else
                                            <span class="badge badge-danger">Unverified</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge {{ $offer->active ? 'badge-success' : 'badge-danger' }}">
                                            {{ $offer->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>New/Recently Updated:</th>
                                    <td>
                                        <span class="badge {{ $offer->new_recently_updated ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $offer->new_recently_updated ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Sort Order:</th>
                                    <td>{{ $offer->sort }}</td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ $offer->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At:</th>
                                    <td>{{ $offer->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Created By:</th>
                                    <td>{{ $offer->creator ? $offer->creator->name : 'System' }}</td>
                                </tr>
                                <tr>
                                    <th>Updated By:</th>
                                    <td>{{ $offer->updater ? $offer->updater->name : 'System' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Affiliate Links</h5>
                            <div class="border p-3">
                                {{ $offer->affiliate_links ?? 'No affiliate links provided' }}
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Terms and Conditions</h5>
                            <div class="border p-3">
                                {{ $offer->terms_and_conditions ?? 'No terms and conditions provided' }}
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Regions</h5>
                            <div class="border p-3">
                                @if($offer->country_codes_collection->count() > 0)
                                    <ul class="list-unstyled">
                                        @foreach($offer->country_codes_collection as $region)
                                            <li>{{ $region->country }} ({{ $region->code }})</li>
                                        @endforeach
                                    </ul>
                                @else
                                    No regions specified
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($offer->logo)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Logo</h5>
                            <div class="border p-3">
                                <img src="{{ asset( substr($offer->logo, 0, 8) === 'uploads/' ? $offer->logo : 'uploads/' . $offer->logo ) }}" alt="{{ $offer->title }}">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection