@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xxxl-12 col-12">
                <div class="box">
                    <div class="box-header no-border">
                        <h4 class="box-title">
                            Dashboard
                        </h4>
                    </div>
                </div>
            </div>


            <div class="col-xxxl-4 col-lg-4 col-md-6 col-12">
                <div class="box">
                    <div class="box-body">
                        <div class="d-flex align-items-center">
                            <div class="box-icon">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            <div>
                                <h2 class="my-0 font-weight-700">{{ count($banner) }}</h2>
                                <p class="text-fade mb-0">Banners</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-4 col-lg-4 col-md-6 col-12">
                <div class="box">
                    <div class="box-body">
                        <div class="d-flex align-items-center">
                            <div class="box-icon">
                                <i class="fa-solid fa-sheet-plastic"></i>
                            </div>
                            <div>
                                <h2 class="my-0 font-weight-700">{{ count($page) }}</h2>
                                <p class="text-fade mb-0">Pages</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-4 col-lg-4 col-md-6 col-12">
                <div class="box">
                    <div class="box-body">
                        <div class="d-flex align-items-center">
                            <div class="box-icon">
                                <i class="fa-solid fa-address-book"></i>
                            </div>
                            <div>
                                <h2 class="my-0 font-weight-700">{{ count($inquiry) }}</h2>
                                <p class="text-fade mb-0">Contact Inquiries</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ecommerce Info --}}


        </div>
    </section>
@endsection




@push('css')
    <style>
        p {
            color: whitesmoke;
        }
    </style>
@endpush
