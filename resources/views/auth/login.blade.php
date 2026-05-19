<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset($favicon) }}">
    <title>{{ config('app.name') }} - Login</title>
    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('admin/css/vendors_css.css') }}">
      
    <!-- Style-->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/skin_color.css') }}">
    <style>
        .theme-primary .text-primary {
            color: #b10c74 !important;
        }
        .theme-primary .btn-danger {
            background-color: #b00971;
            border-color: #b00971;
        }
    </style>
</head>
    
<body class="hold-transition theme-primary bg-img" style="background-image: url({{ asset('admin/image/login-bg.jpg')  }})">
    
    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">   
            
            <div class="col-12">
                <div class="row justify-content-center no-gutters">
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="login-panel rounded30 shadow-lg">
                            <div class="content-top-agile p-20 pb-0">
                                @if($errors->any())
                                <div class="alert alert-danger">
                                    {{ $errors->first() }}
                                </div>
                                @endif
                                <img src="{{ asset('uploads/logo.png') }}" alt="" style="width: 200px;margin-bottom: 4px;">
                                <h2 class="text-primary">Let's Get Started</h2>
                                <p class="mb-0">Sign in to continue to {{ config('app.name') }}.</p>
                            </div>
                            <div class="p-40">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent"><i class="fa-solid fa-user"></i></span>
                                            </div>
                                            <input type="email" class="form-control pl-15 bg-transparent @error('email') is-invalid @enderror" placeholder="Email Address" name="email" autocomplete="email" required autofocus value="{{ old('email') }}">
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
                                                <span class="input-group-text  bg-transparent"><i class="fa-solid fa-lock"></i></span>
                                            </div>
                                            <input type="password" class="form-control pl-15 bg-transparent @error('password') is-invalid @enderror" placeholder="Password" id="password" name="password" required autocomplete="current-password">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="basic_checkbox_1" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                    <label for="basic_checkbox_1">Remember Me</label>
                                                </div>
                                            </div>
                                        <!-- /.col -->
                                      
                                        <div class="col-12 text-center">
                                          <button type="submit" class="btn btn-danger mt-10">SIGN IN</button>
                                        </div>
                                        <!-- /.col -->
                                      </div>
                                </form>
                                <div class="text-center">
									<!--<p class="mt-15 mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-warning ml-5">Sign Up</a></p>-->
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
