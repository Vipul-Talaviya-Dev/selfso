<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>Admin Login Selfso</title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{asset('assets_admin/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets_admin/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets_admin/vendors/themify-icons/css/themify-icons.css')}}" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    
    <!-- THEME STYLES-->
    <link href="{{asset('assets_admin/css/main.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets_admin/css/pages/auth-light.css')}}" rel="stylesheet" />
    <style type="text/css">
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
</head>

<body class="fixed-navbar">
    <div class="content">
        <div class="brand">
            <a class="link" href="/">AdminCAST</a>
        </div>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="login-form" action="{{ route('admin.login') }}" method="post">
            @csrf
            <h2 class="login-title">Log in</h2>
            <div class="form-group">
                <div class="input-group-icon right">
                    <div class="input-icon"><i class="fa fa-envelope"></i></div>
                    <input class="form-control" type="email" name="email" placeholder="Email" autocomplete="off" value="{{ old('email') }}">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group-icon right">
                    <div class="input-icon"><i class="fa fa-lock font-16"></i></div>
                    <input class="form-control" type="password" name="password" placeholder="Password">
                </div>
            </div>
            @if(false)
                <div class="form-group d-flex justify-content-between">
                    <label class="ui-checkbox ui-checkbox-info">
                        <input type="checkbox">
                        <span class="input-span"></span>Remember me</label>
                    <a href="forgot_password.html">Forgot password?</a>
                </div>
            @endif
            <div class="form-group">
                <button class="btn btn-info btn-block cursor-pointer" type="submit">Login</button>
            </div>
        </form>
    </div>
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
    <script src="{{asset('assets_admin/vendors/jquery-validation/dist/jquery.validate.min.js')}}" type="text/javascript"></script>
    <!-- PAGE LEVEL PLUGINS-->   
    <!-- CORE SCRIPTS-->
    <script src="{{asset('assets_admin/js/app.min.js')}}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#login-form').validate({
                errorClass: "help-block",
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    }
                },
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error")
                },
                unhighlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-error")
                },
            });
        });
        $(document).ready(function() {
            @if(Session::has('success'))
            swal("Success...", "{{ Session::get('success') }}", "success");
            @endif
            @if(Session::has('warning'))
            swal("Yikes...", "{{ Session::get('warning') }}", "warning");
            @endif
            @if(Session::has('error'))
            swal("Oops...", "{{ Session::get('error') }}", "error");
            @endif
        });
    </script>
</body>
</html>