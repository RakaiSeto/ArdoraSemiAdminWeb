
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ env('SYSTEM_DESCRIPTION') }}">
    <meta name="author" content="{{ env('SYSTEM_AUTHOR') }}">
    <link rel="icon" href="../images/favicon.ico">

    <title>{{ config('app.name') }}</title>

    <!-- Bootstrap 4.0-->
{{--    <link rel="stylesheet" href="{{ asset('/assets/vendor_components/bootstrap/dist/css/bootstrap.min.css') }}">--}}
    <link rel="stylesheet" href="/assets/vendor_components/bootstrap/dist/css/bootstrap.min.css"/>

    <!-- daterange picker -->
{{--    <link rel="stylesheet" href="{{ asset('/assets/vendor_components/bootstrap-daterangepicker/daterangepicker.css') }}">--}}
    <link rel="stylesheet" href="/assets/vendor_components/bootstrap-daterangepicker/daterangepicker.css"/>

    <!-- Data Table-->
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor_components/datatable/datatables.min.css') }}"/>--}}
    <link rel="stylesheet" type="text/css" href="/assets/vendor_components/datatable/datatables.min.css"/>

    <!-- theme style -->
{{--    <link rel="stylesheet" href="{{ asset('/assets/css/style.css') }}">--}}
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- Crypto Admin skins -->
{{--    <link rel="stylesheet" href="{{ asset('/assets/css/skin_color.css') }}">--}}
    <link rel="stylesheet" href="/assets/css/skin_color.css">

    <!-- Select2 -->
{{--    <link rel="stylesheet" href="{{ asset('/assets/vendor_components/select2/dist/css/select2.min.css') }}">--}}
    <link rel="stylesheet" href="/assets/vendor_components/select2/dist/css/select2.min.css">

    <!-- Animate -->
{{--    <link rel="stylesheet" href="{{ asset('/assets/vendor_components/animate/animate.css') }}">--}}
    <link rel="stylesheet" href="/assets/vendor_components/animate/animate.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body class="hold-transition light-skin dark-sidebar sidebar-mini theme-blue">

<div class="wrapper">
    <!-- Header -->
    @include('layout.header')

    <!-- Left side column -->
    @include('layout.menu')

    <!-- Content Wrapper. Contains page content -->
    @yield('content')
    <!-- /.content-wrapper -->

    <!-- Footer -->
    @include('layout.footer')

</div>
<!-- ./wrapper -->


<!-- jQuery 3 -->
<script src="/assets/vendor_components/jquery-3.3.1/jquery-3.3.1.js"></script>

<!-- fullscreen -->
<script src="/assets/vendor_components/screenfull/screenfull.js"></script>

<!-- jQuery UI 1.11.4 -->
<script src="/assets/vendor_components/jquery-ui/jquery-ui.js"></script>

<!-- popper -->
<script src="/assets/vendor_components/popper/dist/popper.min.js"></script>

<!-- Bootstrap 4.0-->
<script src="/assets/vendor_components/bootstrap/dist/js/bootstrap.js"></script>

<!-- Slimscroll -->
<script src="/assets/vendor_components/jquery-slimscroll/jquery.slimscroll.js"></script>

<!-- FastClick -->
<script src="/assets/vendor_components/fastclick/lib/fastclick.js"></script>

<!-- This is data table -->
<script src="/assets/vendor_components/datatable/datatables.min.js"></script>

<!-- Sparkline -->
<script src="{{ asset('/assets/vendor_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>

<!-- date-range-picker -->
<script src="{{ asset('/assets/vendor_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('/assets/vendor_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Crypto Admin App -->
<script src="/assets/js/template.js"></script>

@yield('jscript')

</body>
</html>
