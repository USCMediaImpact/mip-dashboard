<!DOCTYPE html>
<html lang="en">
<head>
    <title>Media Impact Project</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- build:css css/style.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/foundation.min.css') }}">
    <!-- endbuild -->
    <script src="{{ asset('scripts/vendor/amcharts/amcharts.js') }}"></script>
    <script src="{{ asset('scripts/vendor/amcharts/serial.js') }}"></script>
    <script src="{{ asset('scripts/vendor/amcharts/themes/light.js') }}"></script>
    <script>
        @yield('script')
    </script>
</head>
<body>
    @include('layouts.topheader')
    @yield('content')
    @include('layouts.footer')
    
    <!-- build:js scripts/vendor.js -->
    <script src="{{ asset('scripts/vendor/jquery.min.js') }}"></script>
    <script src="{{ asset('scripts/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('scripts/vendor/sprintf.min.js') }}"></script>
    <script src="{{ asset('scripts/vendor/what-input.min.js') }}"></script>
    <script src="{{ asset('scripts/vendor/foundation.min.js') }}"></script>
    <!-- endbuild -->
    <!-- build:js scripts/site.js -->
    <script src="{{ asset('scripts/login.js') }}"></script>
    <!-- endbuild -->
</body>
</html>