@extends('admin.layouts.app')
@section('title', 'Add Gallery ')
@section('content')
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="page-title">Inquiry
                        {{ $data == null ? '' : '#' . $data->id }}</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#"><i class="mdi mdi-home-outline">Inquiry Details</i></a>
                                </li>

                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="row">
                <div class="col-lg-12 col-12">


                    <!-- /.box-header -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">{{ ucwords($data['type']) }} Details</h4>

                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tbody>
                                        @foreach (json_decode($data->data) as $key => $item)
                                            <tr>
                                                <td><a href="javascript:void(0)">{{ ucwords(str_replace(' ', '_', $key)) }}</a></td>
                                                <td>{{ $item }}</td>

                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>

            </div>
        </section>
    </div>

@endsection
