@extends('layouts.app')
@section('content')
    <section class="banner">
        <div class="container">
            <div class="row">
                <div class="inner-banner">
                    <h1 class="banner-title-head">Order History</h1>
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
                                                    <h2>Order History</h2>

                                                    <table class="table table-bordered">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Customer Name</th>
                                                                <th>Invoice Number</th>
                                                                <th>Total</th>
                                                                <th>View Invoice</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($order as $key => $items)
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td>{{ $items->name }}</td>
                                                                    <td>{{ $items->invoice }}</td>
                                                                    <td>${{ $items->amount }}</td>
                                                                    <td><a href="{{ route('user.generateinvoice', $items->id) }}"
                                                                            class="btn view-invoice" data-toggle="tooltip"
                                                                            data-original-title="Invoice">View</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <div class="d-flex justify-content-center mt-3">
                                                        {{ $order->links('pagination::bootstrap-5') }}
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
        </div>
    </section>
@endsection

@push('css')
    <style>
        a.page-link {
            color: #cf5103 !important;
        }

        .active>.page-link,
        .page-link.active {
            background-color: #cf5103 !important;
            border-color: #cf5103 !important;
        }

        .page-link:hover {
            color: #cf5103 !important;
        }
    </style>
@endpush
