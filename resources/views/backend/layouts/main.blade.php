<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | @yield('page_heading')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skins/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert.css') }}">
    @stack('stylesheets')
</head>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
<div class="wrapper">
    <header class="main-header">
        <a href="{{ action('Backend\HomeController@home') }}" class="logo">
            <span class="logo-mini"></span>
            <span class="logo-lg"></span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown notifications-menu">
                        <?php
                        $countUserAttachment = \App\Models\User::where('attachment', \App\Libraries\Helpers\Utility::ACTIVE_DB)->count('id');
                        $totalNotify = $countUserAttachment;
                        ?>

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-bell-o"></i>
                            @if($totalNotify > 0)
                                <span class="label label-warning">{{ $totalNotify }}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <ul class="menu">
                                    @if($countUserAttachment > 0)
                                        <li>
                                            <a href="{{ action('Backend\UserController@adminUserCustomer') }}">
                                                <i class="fa fa-file-excel-o text-yellow"></i> {{ $countUserAttachment . ' khách hàng đang có attachment file' }}
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user fa-fw"></i>
                            <span class="hidden-xs">{{ auth()->user()->username }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ action('Backend\UserController@editAccount') }}" class="btn btn-default btn-flat">Tài Khoản</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ action('Backend\UserController@logout') }}" class="btn btn-default btn-flat">Đăng Xuất</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    @include('backend.layouts.partials.navigation')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @yield('page_heading')
            </h1>
        </section>
        <section class="content">
            @yield('section')
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2017 <a href="{{ action('Backend\HomeController@home') }}">{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::WEB_TITLE) }}</a>.</strong> All rights reserved.
    </footer>
</div>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/js/backend.js') }}"></script>
@stack('scripts')
@if(session('messageSuccess'))
    <script type="text/javascript">
        $(document).ready(function() {
            swal({
                title: '{{ session('messageSuccess') }}',
                type: 'success',
                confirmButtonClass: 'btn-success',
                allowOutsideClick: true
            });
        });
    </script>
@elseif(session('messageError'))
    <script type="text/javascript">
        $(document).ready(function() {
            swal({
                title: '{{ session('messageError') }}',
                type: 'error',
                confirmButtonClass: 'btn-danger',
                allowOutsideClick: true
            });
        });
    </script>
@endif
<script type="text/javascript">
    if($('input[name="_token"]').length > 0)
    {
        setInterval(function() {
            $.ajax({
                url: '{{ action('Frontend\HomeController@refreshCsrfToken') }}',
                type: 'post',
                data: '_token=' + $('input[name="_token"]').first().val(),
                success: function(result) {
                    if(result)
                    {
                        $('input[name="_token"]').each(function() {
                            $(this).val(result);
                        });
                    }
                }
            });
        }, 600000);
    }
</script>
</body>
</html>
