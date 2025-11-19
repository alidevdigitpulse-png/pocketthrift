@extends('admin.layouts.app')
@section('title', 'Gallery List')
@section('content')
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="page-title">Inquiry List</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#"><i class="mdi mdi-home-outline">Inquiry Management</i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Inquiry List</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Inquiry Management</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="example5" class="table table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>

                                    <th>{{ \Request::query('inquiry') == 'contact' ? 'Name' : 'Email' }}</th>
                                    <th>Inquiry Type</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td> @php $json = json_decode($item->data); @endphp <a href="{{ route('inquiry.detail',$item->id) }}">
                                                {{ \Request::query('inquiry') == 'contact' ? $json->name : $json->email }}
                                            </a></td>

                                        <td>{{ ucwords($item->type) }}</td>

                                    </tr>
                                @endforeach


                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Inquiry Type</th>

                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
    </div>

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
    </style>
@endpush
@push('js')
    <script src="{{ asset('admin/js/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/js/data-table.js') }}"></script>
@endpush

{{-- @push('js')
    <script src="{{ asset('admin/js/search.js') }}"></script>
@endpush --}}
