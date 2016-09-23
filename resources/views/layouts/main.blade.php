<!DOCTYPE html>
<html lang="en">
<head>
    <title>Media Impact Project</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/6.2.3/foundation.min.css">
    <!-- build:css css/style.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.structure.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dataTables.foundation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.comiseo.daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/noty.animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    <!-- endbuild -->
</head>
<body>
    <div class="off-canvas-wrapper" data-offcanvas>
        <div class="off-canvas-wrapper-inner" data-off-canvas-wrapper>
            @if(isset($user))
            <div class="off-canvas position-left reveal-for-medium side-menu" id="offCanvas" data-off-canvas>
                @include('layouts.offCanvas')
            </div>
            @endif
            <div class="off-canvas-content" data-off-canvas-content>
                
                {{-- @include('layouts.header') --}}
                @include('layouts.menu')
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
    <script src="//cdn.polyfill.io/v2/polyfill.min.js?features=Intl.~locale.en"></script>
    <script src="//code.jquery.com/jquery-2.2.4.min.js"
            integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
            crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/foundation/6.2.3/foundation.min.js"></script>
    <!-- build:js scripts/vendor.js -->
    <script src="{{ asset('scripts/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('scripts/vendor/sprintf.min.js') }}"></script>
    <script src="{{ asset('scripts/vendor/cookie.js') }}"></script>
    <script src="{{ asset('scripts/vendor/what-input.min.js') }}"></script>
    <script src="{{ asset('scripts/vendor/jquery-ui.js') }}"></script>
    <script src="{{ asset('scripts/vendor/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('scripts/vendor/dataTables.foundation.js') }}"></script>
    <script src="{{ asset('scripts/vendor/jquery.noty.packaged.js') }}"></script>
    <script src="{{ asset('scripts/vendor/noty.theme.foundation.js') }}"></script>
    <script src="{{ asset('scripts/vendor/select2.full.min.js') }}"></script>
    <script src="{{ asset('scripts/vendor/jquery.comiseo.daterangepicker.js') }}"></script>
    <script src="{{ asset('scripts/vendor/amcharts/amcharts.js') }}"></script>
    <script src="{{ asset('scripts/vendor/amcharts/pie.js') }}"></script>
    <script src="{{ asset('scripts/vendor/amcharts/serial.js') }}"></script>
    <script src="{{ asset('scripts/vendor/amcharts/themes/light.js') }}"></script>
    <!-- endbuild -->
    @yield('prepare_script')
    <!-- build:js scripts/site.js -->
    <script src="{{ asset('scripts/main.js') }}"></script>
    <!-- endbuild -->
    @yield('script')
    
</body>
</html>