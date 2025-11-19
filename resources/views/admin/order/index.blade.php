@extends('admin.layouts.app')
@section('title', 'Orders List')
@section('content')
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="page-title">Order List</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#"><i class="mdi mdi-home-outline">Order Management</i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Order List</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <div id="productorder_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">

                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="productorder"
                                        class="table table-hover no-wrap product-order dataTable no-footer"
                                        data-page-size="10" role="grid" aria-describedby="productorder_info">
                                        <thead>
                                            <tr role="row">
                                                <th>S.No</th>
                                                <th>Customer</th>
                                                <th>Email</th>
                                                <th>Amount</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        @foreach ($data as $key => $item)
                                            <tbody>
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->email }}</td>
                                                    <td>${{ $item->amount }}</td>
                                                    <td>
                                                        <a href="{{ route('order_detail', $item->id) }}"
                                                            class="waves-effect waves-light btn btn-xs btn-primary mb-5"
                                                            data-toggle="tooltip" data-original-title="View">
                                                            <i class="fa fa-eye"></i>
                                                            View
                                                        </a>
                                                        <form action="{{ route('order.destroy', $item->id) }}"
                                                            method="POST">
                                                            <input type="hidden" name="id"
                                                                value="{{ $item->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"><i class="ti-trash"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        @endforeach
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            {{-- <div id="pagination">
            {{ $data->links('pagination::custom-pagination') }}
            <div id="pagination"> --}}
        </section>
    </div>
@endsection

@push('css')
    <style>
        .error {
            margin: 0 auto;
        }

        ul.pagination {
            position: relative;
        }

        li.page-item {
            display: inline;
            text-align: center;
        }

        ul.pagination {
            /* background: #666; */
            /* display: inline-block; */
        }

        ul.pagination li {
            background: #fff;
            /* display: flex; */
            color: black !important;
        }

        ul.pagination span {
            background: transparent !important;
            color: #737373 !important;
        }

        ul.pagination {
            display: flex;
            justify-content: center;
        }

        li.page-item.active {
            background: #fd683e;
            border-color: coral !important;
        }

        .page-item.active .page-link {
            border-color: #fd683e;
            color: white !important;
        }

        tbody form {
            display: contents;
        }

        button i.ti-trash {
            color: #5555dd;
        }

        form button {
            background: white;
            border: none;
        }
    </style>
@endpush

@push('js')
@endpush
