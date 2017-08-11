<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::WEB_TITLE) }} | @yield('page_heading')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::WEB_DESCRIPTION) }}" />
    <meta name="keywords" content="{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::WEB_KEYWORD) }}" />
    <meta property="og:url" content="@yield('og_url', request()->url())" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::WEB_TITLE) }} | @yield('page_heading')" />
    <meta property="og:description" content="@yield('og_description', \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::WEB_DESCRIPTION))" />
    <meta property="og:image" content="@yield('og_image', asset('themes/favicons/favicon-32x32.png'))" />
    <meta name="theme-color" content="#ffffff">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('themes/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('themes/favicons/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('themes/favicons/favicon-16x16.png') }}" sizes="16x16">
    <link rel="manifest" href="{{ asset('themes/favicons/manifest.json') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,700,900">
    <link rel="stylesheet" href="{{ asset('themes/owlcarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/owlcarousel/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/css/jquery-ui-1.10.3.custom.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/css/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/css/t-scroll.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/css/animsition.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/css/component.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert.css') }}">
    @stack('stylesheets')
</head>
<body>
<div id="page" class="animsition">

    @yield('section')

    @include('frontend.layouts.partials.footer')

</div>
<script src="{{ asset('themes/js/jquery-1.9.1.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('themes/js/jquery-ui-1.10.3.custom.min.js') }}"></script>
<script src="{{ asset('themes/js/animsition.min.js') }}"></script>
<script src="{{ asset('themes/js/jquery.easing.1.3.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('themes/owlcarousel/owl.carousel.js') }}"></script>
<script src="{{ asset('themes/js/jquery.matchHeight-min.js') }}"></script>
<script src="{{ asset('themes/js/t-scroll.min.js') }}"></script>
<script src="{{ asset('themes/js/swiper.jquery.min.js') }}"></script>
<script src="{{ asset('themes/js/functions.js') }}"></script>
<script src="{{ asset('themes/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<script src="{{ asset('themes/js/modernizr.custom.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
@stack('scripts')
</body>
</html>