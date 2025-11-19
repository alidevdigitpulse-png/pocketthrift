<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../images/favicon.ico">
    <title>{{ config('app.name') }} - Register</title>
    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('admin/css/vendors_css.css') }}">

    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/skin_color.css') }}">

</head>

<style>
.dark-card.rounded30.shadow-lg {
    background: #fff;
}

</style>


<body class="hold-transition theme-primary bg-img"
    style="background-image: url({{ asset('admin/image/login-bg.webp') }})">

    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">

            <div class="col-12">
                <div class="row justify-content-center no-gutters">
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="dark-card rounded30 shadow-lg">
                            <div class="content-top-agile p-20 pb-0">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        {{ $errors->first() }}
                                    </div>
                                @endif
                                <h2 class="text-primary">Let's Get Started</h2>
                                <p class="mb-0">Register yourself to continue to {{ config('app.name') }}.</p>
                            </div>
                            <div class="p-40">
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <div class="form-group">



                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text  bg-transparent"><i
                                                        class="ti-user"></i></span>
                                            </div>
                                            <input id="name" type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="{{ old('name') }}" placeholder="John Farey" required autocomplete="name" autofocus>

                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text  bg-transparent"><i
                                                        class="ti-email"></i></span>
                                            </div>

                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" mailto:placeholder="john@example.com" required autocomplete="email">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror


                                        </div>
                                    </div>



                                    <div class="form-group">

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text  bg-transparent"><i
                                                        class="ti-lock"></i></span>
                                            </div>


                                            <input id="password-confirm" placeholder="Password"
                                                class="form-control pl-15 bg-transparent @error('password') is-invalid @enderror"
                                                type="password" name="password" placeholder="●●●●●●●●" required autocomplete="new-password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text  bg-transparent"><i
                                                        class="ti-lock"></i></span>
                                            </div>


                                            <input id="password-confirm" placeholder="Confirm Password"
                                                class="form-control pl-15 bg-transparent @error('password') is-invalid @enderror"
                                                type="password" placeholder="●●●●●●●●"  name="password_confirmation" required
                                                autocomplete="new-password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="row mb-0">
                                        <div class="col-md-12 offset-md-4">
                                            <button type="submit" class="btn btn-danger">
                                                {{ __('Register') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <div class="text-center">
									<p class="mt-15 mb-0">Already have an account?<a href="{{ route('login') }}" class="text-danger ml-5"> Sign In</a></p>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Vendor JS -->
    <script src="{{ asset('admin/js/vendors.min.js') }}"></script>
</body>

</html>
