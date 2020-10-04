<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>Selfso -  @yield('title')</title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{asset('assets_admin/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets_admin/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets_admin/vendors/themify-icons/css/themify-icons.css')}}" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    
    <!-- THEME STYLES-->
    <link href="{{asset('assets_admin/css/main.min.css')}}" rel="stylesheet" />
    <!-- PAGE LEVEL STYLES-->
    <style type="text/css">
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
    @yield('stylesheets')
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        
        <!-- START HEADER-->
        @include('admin.layout.header')
        <!-- END HEADER-->

        <!-- START SIDEBAR-->
        @include('admin.layout.sidebar')
        <!-- END SIDEBAR-->
        <div class="content-wrapper">
            <!-- START PAGE CONTENT-->
            <div class="page-heading">
                <h1 class="page-title"> @yield('title')</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);"><i class="la la-home font-20"></i></a>
                    </li>
                </ol>
            </div>
            <div class="page-content fade-in-up">
                @yield('content')
            </div>
            <!-- END PAGE CONTENT-->
            @include('admin.layout.footer')
        </div>
    </div>
    <!-- BEGIN THEME CONFIG PANEL-->
    <!-- @include('admin.layout.theme_config') -->
    <!-- END THEME CONFIG PANEL-->
    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>
    <!-- END PAGA BACKDROPS-->
    <!-- CORE PLUGINS-->
    <script src="{{asset('assets_admin/vendors/jquery/dist/jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets_admin/vendors/popper.js/dist/umd/popper.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets_admin/vendors/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets_admin/vendors/metisMenu/dist/metisMenu.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets_admin/vendors/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
    <!-- PAGE LEVEL PLUGINS-->   
    <!-- CORE SCRIPTS-->
    <script src="{{asset('assets_admin/js/app.min.js')}}" type="text/javascript"></script>
    <!-- PAGE LEVEL SCRIPTS-->
    @yield('scripts')
</body>

</html>