@extends('admin.layouts.app')
@section('title', 'Orders Detail')
@section('content')
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="page-title">Order Detail</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#"><i class="mdi mdi-home-outline">Order Management</i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Order Detail</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="d-flex justify-content-between">
                                <h4 class="box-title">Order Info</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table product-table">
                                    <thead>
                                        <th>ID</th>
                                        <th>Product</th>
                                        <th></th>
                                        <th>Price</th>


                                    </thead>
                                    <tbody>
                                        @foreach ($data->products as $items)
                                            <tr>
                                                <td>{{ $items->id }}</td>
                                                <td>{{ $items->name }}</td>
                                                <td class="product-image">
                                                    <img src="{{ asset($items->image) }}" class="img-fluid" alt="">
                                                </td>
                                                <td>{{ $items->pivot->price }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="total-price"><b>Total Price:</b>
                                            </td>
                                            <td class="total-price"><b>${{ $data->amount }}</b>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h4 class="box-title mt-40">Shipping Address</h4>
                            <div class="table-responsive">
                                <table class="table shipping-table">
                                    <tbody>
                                        <tr>
                                            <td>Customer Name</td>
                                            <td>{{ ucwords($data->name) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Country</td>
                                            <td>{{ ucwords($data->country) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Phone</td>
                                            <td>{{ $data->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td>{{ $data->address }}</td>
                                        </tr>
                                        <tr>
                                            <td>Notes</td>
                                            <td>{{ $data->notes }}</td>
                                        </tr>
                                        <tr>
                                            <td>Payment Method</td>
                                            <td>{{ $data->payment_method }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('css')
    <style>
        td.product-image {
            width: 100px;
            height: 100px;
        }

        td.product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .total-price {
            position: relative;
            left: 45rem;
        }

        .change-status {
            background: #014ca9;
            color: white;
        }

        .change-status:hover {
            color: white;
        }

        .success-status {
            background-color: #28a745;
            color: white;
        }

        .success-status:hover {
            color: white;
        }
    </style>
@endpush

@push('js')
@endpush
