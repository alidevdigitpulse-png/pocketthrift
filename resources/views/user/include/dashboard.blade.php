@extends('layouts.app')
@section('content')
    <section class="banner">
        <div class="container">
            <div class="row">
                <div class="inner-banner">
                    <h1 class="banner-title-head">Dashboard</h1>
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
                                                    <h2>Dashboard</h2>

                                                    <div class="welcome">
                                                        <form class="d-none" method="POST" id="logout"
                                                            action="{{ route('logout') }}">
                                                            @csrf
                                                        </form>
                                                        <p>Hello, <strong>{{ Auth::user()->name }}</strong> (If Not
                                                            <strong>{{ Auth::user()->name }}
                                                                !</strong><a href="#" class="logout"
                                                                onclick="document.getElementById('logout').submit();">
                                                                Logout</a>)
                                                        </p>
                                                    </div>

                                                    <p class="mb-0">From your account dashboard. you can easily check
                                                        &amp;
                                                        view your recent orders, manage your shipping and billing addresses
                                                        and
                                                        edit your password and account details.</p>
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
