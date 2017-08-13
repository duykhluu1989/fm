@extends('frontend.layouts.main')

@section('page_heading', 'Trang chủ')

@section('section')

    @include('frontend.layouts.partials.menu')

    <section class="banner">
        <div class="owl_banner owl-carousel owl-theme">
            <div class="item">
                <img src="{{ asset('themes/images/banner01.jpg') }}" alt="" class="img-responsive">
            </div>
            <div class="item">
                <img src="{{ asset('themes/images/banner01.jpg') }}" alt="" class="img-responsive">
            </div>
        </div>
    </section>

    <section class="search_DH">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="hotline">
                        <p class="numberPhone"><i class="fa fa-phone" aria-hidden="true"></i> HOTLINE: <span>090 999 999</span></p>
                        <p class="email"><b><i class="fa fa-envelope" aria-hidden="true"></i>
                                Email:</b> <a href="mailto:parcelpost.vn">info@parcelpost.vn</a></p>
                        <p class="timeOP"><b><i class="fa fa-clock-o" aria-hidden="true"></i> Thời gian làm việc:</b>  8h - 20h (T2 - T7)</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <form class="frm_searchDH" action="" method="POST" role="form">
                        <div class="form-group">
                            <input type="text" class="form-control" id="" placeholder="">
                            <button type="submit" class="btn btnTimDH"><i class="fa fa-search fa-lg" aria-hidden="true"></i> TRA CỨU ĐƠN HÀNG</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <main>
        <section class="thongtin">
            <div class="container">
                <div class="row text-center">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 thongtin_items">
                        <figure>
                            <a class="btnThongtin" href="{{ (auth()->guest() ? action('Frontend\UserController@login') : action('Frontend\UserController@editAccount')) }}">
                                <img src="{{ asset('themes/images/img01.jpg') }}" alt="" class="img-responsive">
                            </a>
                        </figure>
                        <h3><a href="{{ action('Frontend\UserController@login') }}">{{ (auth()->guest() ? 'ĐĂNG KÝ - ĐĂNG NHẬP' : 'TÀI KHOẢN') }}</a></h3>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 thongtin_items">
                        <figure>
                            <a class="btnThongtin" href="{{ (auth()->guest() ? action('Frontend\UserController@login') : action('Frontend\UserController@adminOrder')) }}">
                                <img src="{{ asset('themes/images/img02.jpg') }}" alt="" class="img-responsive">
                            </a>
                        </figure>
                        <h3><a href="">TRA CỨU ĐƠN HÀNG</a></h3>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 thongtin_items">
                        <figure>
                            <a class="btnThongtin" href="{{ action('Frontend\OrderController@placeOrder') }}">
                                <img src="{{ asset('themes/images/img03.jpg') }}" alt="" class="img-responsive">
                            </a>
                        </figure>
                        <h3><a href="{{ action('Frontend\OrderController@placeOrder') }}">ĐĂNG ĐƠN HÀNG</a></h3>
                    </div>
                </div>
            </div>
        </section>

        <section class="dichvu">
            <div class="container">
                <h2 class="title">DỊCH VỤ</h2>
                <div class="row text-center">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <a href="">
                            <img src="{{ asset('themes/images/img04.jpg') }}" alt="" class="img-reponsive">
                            <h3>GIAO HÀNG TIẾT KIỆM</h3>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <a href="">
                            <img src="{{ asset('themes/images/img05.jpg') }}" alt="" class="img-reponsive">
                            <h3>CHUYỂN PHÁT NHANH</h3>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <a href="">
                            <img src="{{ asset('themes/images/img06.jpg') }}" alt="" class="img-reponsive">
                            <h3>CHUYỂN PHÁT HOẢ TỐC</h3>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <a href="">
                            <img src="{{ asset('themes/images/img07.jpg') }}" alt="" class="img-reponsive">
                            <h3>GIAO HÀNG TIẾT KIỆM</h3>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="tienloi parallax-one">
            <div class="container text-center">
                <h2 class="title">Bạn có biết?</h2>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <h3>Nam eu lectus a ante finibus ullamcorper.</h3>
                        <a href="#" class="btn btnRed"><i class="fa fa-sign-in fa-lg" aria-hidden="true"></i> ĐĂNG KÝ NGAY</a>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <ul class="list_tienloi">
                            <li>
                                <i class="fa fa-list-alt fa-3x" aria-hidden="true"></i>
                                <h4>Lorem ipsum dolor</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, sequi.</p>
                            </li>
                            <li>
                                <i class="fa fa-handshake-o fa-3x" aria-hidden="true"></i>
                                <h4>Lorem ipsum dolor</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, sequi.</p>
                            </li>
                            <li>
                                <i class="fa fa-fighter-jet fa-3x" aria-hidden="true"></i>
                                <h4>Lorem ipsum dolor</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, sequi.</p>
                            </li>
                            <li>
                                <i class="fa fa-clock-o fa-3x" aria-hidden="true"></i>
                                <h4>Lorem ipsum dolor</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, sequi.</p>
                            </li>
                            <li>
                                <i class="fa fa-credit-card-alt fa-3x" aria-hidden="true"></i>
                                <h4>Lorem ipsum dolor</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, sequi.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="phuongthuc">
            <div class="container">
                <h2 class="title">Phương thức hoạt động</h2>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <ul class="list_phuongthuc">
                            <li>
                                <i class="fa fa-desktop fa-3x" aria-hidden="true"></i>
                                <h4>Tiếp nhận đơn hàng</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, sequi.</p>
                            </li>
                            <li>
                                <i class="fa fa-handshake-o fa-3x" aria-hidden="true"></i>
                                <h4>Lấy hàng</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, sequi.</p>
                            </li>
                            <li>
                                <i class="fa fa-truck fa-3x" aria-hidden="true"></i>
                                <h4>Giao hàng</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, sequi.</p>
                            </li>
                            <li>
                                <i class="fa fa-usd fa-3x" aria-hidden="true"></i>
                                <h4>Đối soát</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, sequi.</p>
                            </li>
                            <li>
                                <i class="fa fa-check-circle fa-3x" aria-hidden="true"></i>
                                <h4>Kết thúc</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, sequi.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop

@push('scripts')
    <script src="{{ asset('themes/js/jquery.samesizr-min.js') }}"></script>
    <script type="text/javascript">
        $(window).on("load resize", function() {
            $('.example').samesizr({
                mobile: 0
            });
        });

        $(document).ready(function() {
            $('.btn-animation').on('click', function() {
                var st = $(this).parents('.section').next().offset().top;
                $('html, body').animate({
                    scrollTop: st
                }, 300);
            });
            Tu.t_scroll({
                't_element': '.zoomOut'
            })
            Tu.t_scroll({
                't_element': '.news .slideDown',
                't_duration': 0.1,
                't_delay': 0.8
            })
        });
    </script>
@endpush