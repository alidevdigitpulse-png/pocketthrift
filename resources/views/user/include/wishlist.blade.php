@extends('layouts.app')
@section('content')
    <section class="banner">
        <div class="container">
            <div class="row">
                <div class="inner-banner">
                    <h1 class="banner-title-head">My Wishlist</h1>
                </div>
            </div>
    </section>
    <section class="dashboardSection">
        <div class="my-account-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="myaccount-page-wrapper">
                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    <div class="myaccount-tab-menu nav" role="tablist">
                                        @include('user.include.sidebar')
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-8">
                                    <div class="tab-content" id="myaccountContent">
                                        <!-- Single Tab Content Start -->
                                        <div class="tab-pane fade show active" id="dashboad">
                                            <div class="myaccount-content">
                                                <div class="section-heading">
                                                    <h2>My Wishlist</h2>
                                                </div>
                                                <div class="row">
                                                    @foreach ($wishlist as $items)
                                                        <div class="col-lg-6">
                                                            <div class="innerproducts">
                                                                <a href="{{ route('product.detail', $items->slug) }}">
                                                                    <figure>
                                                                        <img src="{{ asset($items->image) }}"
                                                                            class="img-fluid" alt="">

                                                                    </figure>
                                                                    <div class="product-txt">
                                                                        <span>{{ $items->category->name }}</span>
                                                                        <h5>{{ $items->name }}</h5>
                                                                        <strong>$ {{ $items->price }}</strong>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('css')
@endpush

@push('js')
@endpush
