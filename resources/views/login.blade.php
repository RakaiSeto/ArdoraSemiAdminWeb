
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
            {{-- <a href="/" class="text-dark"><b>BLASTME</b><br/>Web Admin</a> --}}
            <img class="mx-auto d-block" src="{{ asset('/image/Logo.png') }}" alt="{{ env('APP_NAME') }}" width="100">
        </div>
        <!-- /.login-logo -->
        <div class="auth-body">
            <p class="auth-msg text-black-50">Sign in to start your session</p>

            <form action="/dologin" method="post" class="form-element">
                {{ csrf_field() }}
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Email" id="email" name="email">
                    {{-- <input type="email" class="form-control" placeholder="Email" id="email" name="email"> --}}
                    <span class="ion ion-email form-control-feedback text-dark"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                    {{-- <input type="password" class="form-control" placeholder="Password" id="password" name="password"> --}}
                    <span class="ion ion-locked form-control-feedback text-dark"></span>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="checkbox">
                            <input type="checkbox" id="basic_checkbox_1">
                            <label for="basic_checkbox_1" class="text-dark">Remember Me</label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-6">
                    </div>
                    <!-- /.col -->
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-rounded my-20 btn-success">SIGN IN</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            @if (session('message'))
                &nbsp
                <div class="alert alert-danger">
                    <span class="font-size-12 pt-2">{{ session('message') }}</span>
                </div>
            @endif
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
