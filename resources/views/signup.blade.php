
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ env('SYSTEM_DESCRIPTION') }}">
    <meta name="author" content="{{ env('SYSTEM_AUTHOR') }}">
    <link rel="icon" href="../../images/favicon.ico">

    <title>{{ config('app.name') }}</title>

    <!-- Bootstrap 4.0-->
    <link rel="stylesheet" href="{{ asset('/assets/vendor_components/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- theme style -->
    <link rel="stylesheet" href="{{ asset('/assets/css/style.css') }}">

    <!-- Admin skins -->
    <link rel="stylesheet" href="{{ asset('/assets/css/skin_color.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="hold-transition theme-yellow bg-img" style="background-image: url({{ asset('/image/LoginBackground.jpg') }})" data-overlay="3">

<div class="auth-2-outer row align-items-center h-p100 m-0">
    <div class="auth-2">
        <div class="auth-logo font-size-30">
            <a href="/" class="text-dark"><b>PING</b> Admin</a>
        </div>
        <!-- /.login-logo -->
        <div class="auth-body">
            <p class="auth-msg text-black-50">Register a new Membership</p>

            <form action="/signup" method="post" class="form-element">
                {{ csrf_field() }}
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Full name" id="fullName">
                    <span class="ion ion-person form-control-feedback text-dark"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Email" id="email">
                    <span class="ion ion-email form-control-feedback text-dark"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" id="password01">
                    <span class="ion ion-locked form-control-feedback text-dark"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Retype password" id="password02">
                    <span class="ion ion-log-in form-control-feedback text-dark"></span>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="checkbox">
                            <input type="checkbox" id="basic_checkbox_1" id="agreeTerm">
                            <label for="basic_checkbox_1" class="text-dark">I agree to the <a href="#" class="text-danger"><b>Terms</b></a></label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-rounded my-20 btn-success" id="btnSignUp">SIGN UP</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <div class="text-center text-dark">
                <p class="mt-50">- Sign With -</p>
                <p class="gap-items-2 mb-20">
                    <a class="btn btn-social-icon btn-round btn-facebook" href="#"><i class="fa fa-facebook"></i></a>
                    <a class="btn btn-social-icon btn-round btn-twitter" href="#"><i class="fa fa-twitter"></i></a>
                    <a class="btn btn-social-icon btn-round btn-google" href="#"><i class="fa fa-google"></i></a>
                    <a class="btn btn-social-icon btn-round btn-instagram" href="#"><i class="fa fa-instagram"></i></a>
                </p>
            </div>
            <!-- /.social-auth-links -->

            <div class="margin-top-30 text-center text-dark">
                <p>Already have an account? <a href="auth_login.html" class="text-info m-l-5">Sign In</a></p>
            </div>

        </div>
    </div>

</div>


<!-- jQuery 3 -->
<script src="{{ asset('/assets/vendor_components/jquery-3.3.1/jquery-3.3.1.js') }}"></script>

<!-- fullscreen -->
<script src="{{ asset('/assets/vendor_components/screenfull/screenfull.js') }}"></script>

<!-- popper -->
<script src="{{ asset('/assets/vendor_components/popper/dist/popper.min.js') }}"></script>

<!-- Bootstrap 4.0-->
<script src="{{ asset('/assets/vendor_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

</body>
</html>
