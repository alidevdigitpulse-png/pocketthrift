@extends('layouts.app')
@section('content')
    <section class="banner">
        <div class="container">
            <div class="row">
                <div class="inner-banner">
                    <h1 class="banner-title-head">Update Profile</h1>
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
                                        <div class="tab-pane fade show active" id="dashboad">
                                            <div class="myaccount-content">
                                                <div class="section-heading">
                                                    <h2>Update Profile</h2>
                                                </div>
                                                <form action="{{ route('user.update') }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <h5>Personal Information</h5>

                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="name">Name</label>
                                                                <input type="text" name="name" class="form-control"
                                                                    value="{{ Auth::user()->name }}" disabled>
                                                                @error('name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="name">Email</label>
                                                                <input type="email" name="email" class="form-control"
                                                                    value="{{ Auth::user()->email }}" disabled>
                                                                @error('email')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror


                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <br>
                                                            <h5>Change Password</h5>

                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="name">Password</label>
                                                                <input type="password" name="password" class="form-control">
                                                                @error('password')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="password_confirmation"> Confirm Password</label>
                                                                <input type="password" name="password_confirmation"
                                                                    class="form-control">
                                                                @error('password_confirmation')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <h5>Change Your Profile</h5>

                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="profile-img-sec">
                                                                <div class="circle">
                                                                    <img class="profile-pic"
                                                                        src="{{ Auth::user()->image ? asset(Auth::user()->image) : asset('front/images/avatar-1.png') }}">
                                                                    <div class="p-image">
                                                                        <input class="file-upload dropify" name="image"
                                                                            type="file" accept="image/*" />
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group text-center">
                                                                <button type="submit" class="btn custom-btn mt-4"
                                                                    id="update-btn">Save
                                                                    Settings</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });
    </script>
@endpush
