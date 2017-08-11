@extends('frontend.layouts.main')

@section('page_heading', 'Dịch vụ của chúng tôi')

@section('section')

    @include('frontend.layouts.partials.menu')

    @include('frontend.layouts.partials.headline')

    <main>

        @include('frontend.layouts.partials.breadcrumb', ['breadcrumbTitle' => 'Dịch vụ của chúng tôi'])

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row text-center">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 thongtin_items">
                                <figure>
                                    <a class="btnThongtin" href="{{ action('Frontend\UserController@login') }}">
                                        <img src="{{ asset('themes/images/img12.jpg') }}" alt="" class="img-responsive">
                                    </a>
                                </figure>
                                <h3><a href="{{ action('Frontend\UserController@login') }}">GIAO HÀNG CẤP TỐC</a></h3>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 thongtin_items">
                                <figure>
                                    <a class="btnThongtin" href="">
                                        <img src="{{ asset('themes/images/img13.jpg') }}" alt="" class="img-responsive">
                                    </a>
                                </figure>
                                <h3><a href="">GIAO HÀNG CHO SHOP ONLINE</a></h3>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 thongtin_items">
                                <figure>
                                    <a class="btnThongtin" href="">
                                        <img src="{{ asset('themes/images/img14.jpg') }}" alt="" class="img-responsive">
                                    </a>
                                </figure>
                                <h3><a href="">GIAO HÀNG SIÊU TIẾT KIỆM</a></h3>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 thongtin_items">
                                <figure>
                                    <a class="btnThongtin" href="{{ action('Frontend\UserController@login') }}">
                                        <img src="{{ asset('themes/images/img12.jpg') }}" alt="" class="img-responsive">
                                    </a>
                                </figure>
                                <h3><a href="{{ action('Frontend\UserController@login') }}">GIAO HÀNG CẤP TỐC</a></h3>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 thongtin_items">
                                <figure>
                                    <a class="btnThongtin" href="">
                                        <img src="{{ asset('themes/images/img13.jpg') }}" alt="" class="img-responsive">
                                    </a>
                                </figure>
                                <h3><a href="">GIAO HÀNG CHO SHOP ONLINE</a></h3>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 thongtin_items">
                                <figure>
                                    <a class="btnThongtin" href="">
                                        <img src="{{ asset('themes/images/img14.jpg') }}" alt="" class="img-responsive">
                                    </a>
                                </figure>
                                <h3><a href="">GIAO HÀNG SIÊU TIẾT KIỆM</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        @include('frontend.layouts.partials.process')
        
    </main>

@stop
