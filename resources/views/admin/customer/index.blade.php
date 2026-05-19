@extends('admin.layouts.app')
@section('title', 'Customer List')
@section('content')
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="page-title">Customer List</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#"><i class="mdi mdi-home-outline">Customer Management</i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Customer List</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="card">
                {{-- <div class="card-header">
                    <div class="col-md-6 pl-0">
                        <h4 class="card-title">Customer Lists</h4>
                    </div>
                    <div class="col-md-6">
                        <form method="get" action="{{ route('customer.index') }}">
                            <div class="input-group">
                                <input type="search" class="form-control" placeholder="Search" aria-label="Search"
                                    aria-describedby="button-addon2" name="search" value="{{ Request::get('search') }}">
                                <div class="input-group-append">
                                    <button class="btn" type="submit" id="button-addon3"><i
                                            class="ti-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> --}}
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Customer List</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">
                                <div id="example5_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="example5" class="table table-bordered table-striped dataTable"
                                                style="width: 100%;" role="grid" aria-describedby="example5_info">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting_asc" tabindex="0" aria-controls="example5"
                                                            rowspan="1" colspan="1" aria-sort="ascending"
                                                            aria-label="Name: activate to sort column descending"
                                                            style="width: 141px;">ID</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example5"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Position: activate to sort column ascending"
                                                            style="width: 155px;">Name</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example5"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Office: activate to sort column ascending"
                                                            style="width: 141px;">Email</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example5"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Office: activate to sort column ascending"
                                                            style="width: 141px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($data as $key => $items)
                                                        <tr role="row" class="{{ $key % 2 == 0 ? 'odd' : 'even' }}">
                                                            <td>{{ $items->id }}</td>
                                                            <td>{{ $items->name }}</td>
                                                            <td>{{ $items->email }}</td>
                                                            <td><a href="{{ route('customer.edit', $items->id) }}"
                                                                    class="btn btn-sm btn-primary">Edit</a>
                                                                <button class="btn btn-sm btn-danger"
                                                                    type="button">Delete</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th rowspan="1" colspan="1"><input type="text"
                                                                placeholder="Search ID"></th>
                                                        <th rowspan="1" colspan="1"><input type="text"
                                                                placeholder="Search Name"></th>
                                                        <th rowspan="1" colspan="1"><input type="text"
                                                                placeholder="Search Email"></th>
                                                        <th rowspan="1" colspan="1"><input type="text"
                                                                placeholder="Search Action"></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
@endsection

@push('css')
    <style></style>
@endpush

@push('js')
    <script></script>
@endpush
