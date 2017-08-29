@extends('frontend.layouts.main')

@section('page_heading', 'Trang chủ')

@section('section')

    @include('frontend.layouts.partials.menu')

    <section class="banner">
        <div class="owl_banner owl-carousel owl-theme">
            <?php
            $sliderItems = array();

            if(isset($widgets[\App\Models\Widget::HOME_SLIDER]))
            {
                if(!empty($widgets[\App\Models\Widget::HOME_SLIDER]->detail))
                    $sliderItems = json_decode($widgets[\App\Models\Widget::HOME_SLIDER]->detail, true);
            }
            ?>

            @foreach($sliderItems as $sliderItem)
                <div class="item">
                    <a href="{{ isset($sliderItem['url']) ? $sliderItem['url'] : 'javascript:void(0)' }}">
                        <img src="{{ isset($sliderItem['image']) ? $sliderItem['image'] : '' }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($sliderItem, 'title') }}" class="img-responsive">
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <section class="search_DH">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="hotline">
                        <p class="numberPhone"><i class="fa fa-phone" aria-hidden="true"></i> HOTLINE: <span>{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::HOT_LINE) }}</span></p>
                        <p class="email"><b><i class="fa fa-envelope" aria-hidden="true"></i> Email:</b> <a href="mailto:{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::CONTACT_EMAIL) }}">{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::CONTACT_EMAIL) }}</a></p>
                        <p class="timeOP"><b><i class="fa fa-clock-o" aria-hidden="true"></i> Thời gian làm việc:</b> {{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::WORKING_TIME) }}</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <form class="frm_searchDH" action="{{ action('Frontend\UserController@searchOrder') }}" role="form">
                        <div class="form-group">
                            <input type="text" class="form-control" name="k" placeholder="Nhập mã đơn hàng" />
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
                    <?php
                    $sliderItems = array();

                    if(isset($widgets[\App\Models\Widget::HOME_BANNER_TOP]))
                    {
                        if(!empty($widgets[\App\Models\Widget::HOME_BANNER_TOP]->detail))
                            $sliderItems = json_decode($widgets[\App\Models\Widget::HOME_BANNER_TOP]->detail, true);
                    }
                    ?>

                    @foreach($sliderItems as $sliderItem)
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 thongtin_items">
                            <figure>
                                <a class="btnThongtin" href="{{ isset($sliderItem['url']) ? $sliderItem['url'] : 'javascript:void(0)' }}">
                                    <img src="{{ isset($sliderItem['image']) ? $sliderItem['image'] : '' }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($sliderItem, 'title') }}" class="img-responsive">
                                </a>
                            </figure>
                            <h3><a href="{{ isset($sliderItem['url']) ? $sliderItem['url'] : 'javascript:void(0)' }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($sliderItem, 'title') }}</a></h3>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="dichvu">
            <div class="container">
                <h2 class="title">DỊCH VỤ</h2>
                <div class="row text-center">
                    <div class="col-lg-12 col-md-12 col-sm-12-col-xs-12">
                        <div class="owl_dichvu owl-carousel owl-theme">
                            <?php
                            $sliderItems = array();

                            if(isset($widgets[\App\Models\Widget::HOME_BANNER_SERVICE]))
                            {
                                if(!empty($widgets[\App\Models\Widget::HOME_BANNER_SERVICE]->detail))
                                    $sliderItems = json_decode($widgets[\App\Models\Widget::HOME_BANNER_SERVICE]->detail, true);
                            }
                            ?>

                            @foreach($sliderItems as $sliderItem)
                                <div class="item">
                                    <a href="{{ isset($sliderItem['url']) ? $sliderItem['url'] : 'javascript:void(0)' }}">
                                        <img src="{{ isset($sliderItem['image']) ? $sliderItem['image'] : '' }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($sliderItem, 'title') }}" class="img-reponsive">
                                        <h3>{{ \App\Libraries\Helpers\Utility::getValueByLocale($sliderItem, 'title') }}</h3>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="tienloi parallax-one">
            <div class="container text-center">
                <h2 class="title">Bạn có biết?</h2>
                <div class="row">
                    <?php
                    $sliderItems = array();

                    if(isset($widgets[\App\Models\Widget::HOME_BANNER_ASK]))
                    {
                        if(!empty($widgets[\App\Models\Widget::HOME_BANNER_ASK]->detail))
                            $sliderItems = json_decode($widgets[\App\Models\Widget::HOME_BANNER_ASK]->detail, true);
                    }
                    ?>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p>{{ isset($details['custom_detail']['title_1']) ? $details['custom_detail']['title_1'] : '' }}</p>
                        <h3>{{ isset($details['custom_detail']['title_2']) ? $details['custom_detail']['title_2'] : '' }}</h3>
                        <a href="{{ action('Frontend\UserController@login') }}" class="btn btnRed"><i class="fa fa-sign-in fa-lg" aria-hidden="true"></i> ĐĂNG KÝ NGAY</a>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <ul class="list_tienloi">
                            @foreach($sliderItems as $sliderItem)
                                <li>
                                    <i class="fa fa-{{ isset($sliderItem['icon']) ? $sliderItem['icon'] : '' }} fa-3x" aria-hidden="true"></i>
                                    <h4>{{ \App\Libraries\Helpers\Utility::getValueByLocale($sliderItem, 'title') }}</h4>
                                    <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($sliderItem, 'description') }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

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
            });
            Tu.t_scroll({
                't_element': '.news .slideDown',
                't_duration': 0.1,
                't_delay': 0.8
            });
        });
    </script>
@endpush