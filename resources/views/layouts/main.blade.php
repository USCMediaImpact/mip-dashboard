<!DOCTYPE html>
<html lang="en">
<head>
    <title>Media Impact</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- build:css css/style.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/foundation.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dataTables.foundation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/noty.animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    <!-- endbuild -->
</head>
<body>
    <div class="off-canvas-wrapper" data-offcanvas>
        <div class="off-canvas-wrapper-inner" data-off-canvas-wrapper>
            <div class="off-canvas position-left" id="offCanvas" data-off-canvas>
                @include('layouts.offCanvas')
            </div>
            <div class="off-canvas-content" data-off-canvas-content>
                @include('layouts.header')
                <div class="main-content">
                    @yield('content')    
                </div>
                @include('layouts.footer')
            </div>
        </div>
    </div>
    <div class="hide">
        @yield('reveal')
    </div>
    
    <!-- build:js scripts/vendor.js -->
    <script src="{{ asset('scripts/vendor/jquery.min.js') }}"></script>
    <script src="{{ asset('scripts/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('scripts/vendor/sprintf.min.js') }}"></script>
    <script src="{{ asset('scripts/vendor/what-input.min.js') }}"></script>
    <script src="{{ asset('scripts/vendor/foundation.min.js') }}"></script>
    <script src="{{ asset('scripts/vendor/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('scripts/vendor/dataTables.foundation.js') }}"></script>
    <script src="{{ asset('scripts/vendor/jquery.noty.packaged.js') }}"></script>
    <script src="{{ asset('scripts/vendor/noty.theme.foundation.js') }}"></script>
    <script src="{{ asset('scripts/vendor/amcharts/amcharts.js') }}"></script>
    <script src="{{ asset('scripts/vendor/amcharts/serial.js') }}"></script>
    <script src="{{ asset('scripts/vendor/amcharts/themes/light.js') }}"></script>
    <!-- endbuild -->
    <!-- build:js scripts/site.js -->
    <script src="{{ asset('scripts/main.js') }}"></script>
    <!-- endbuild -->
    
    @yield('script')
    
</body>
</html>