@extends('frontend.layouts.main')

@section('page_heading', 'Tuyển dụng')

@section('section')

    @include('frontend.layouts.partials.menu')

    @include('frontend.layouts.partials.headline')

    <main>

        @include('frontend.layouts.partials.breadcrumb', ['breadcrumbTitle' => 'Tuyển dụng'])

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="title_sub">TUYỂN DỤNG</h2>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <article class="tuyendung_item">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <a href="{{ action('PageController@tuyendungchitiet') }} "><img src="{{ asset('themes/images/img10.jpg') }}" alt="" class="img-responsive"></a>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            <h5><a href="{{ action('PageController@tuyendungchitiet') }} ">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</a></h5>
                                            <p>sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, cons ectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet</p>
                                        </div>
                                    </div>
                                </article>
                                <article class="tuyendung_item">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <a href="{{ action('PageController@tuyendungchitiet') }} "><img src="{{ asset('themes/images/img10.jpg') }}" alt="" class="img-responsive"></a>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            <h5><a href="{{ action('PageController@tuyendungchitiet') }} ">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</a></h5>
                                            <p>sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, cons ectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet</p>
                                        </div>
                                    </div>
                                </article>
                                <article class="tuyendung_item">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <a href="{{ action('PageController@tuyendungchitiet') }} "><img src="{{ asset('themes/images/img10.jpg') }}" alt="" class="img-responsive"></a>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            <h5><a href="{{ action('PageController@tuyendungchitiet') }} ">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</a></h5>
                                            <p>sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, cons ectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet</p>
                                        </div>
                                    </div>
                                </article>
                                <article class="tuyendung_item">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <a href="{{ action('PageController@tuyendungchitiet') }} "><img src="{{ asset('themes/images/img10.jpg') }}" alt="" class="img-responsive"></a>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            <h5><a href="{{ action('PageController@tuyendungchitiet') }} ">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</a></h5>
                                            <p>sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, cons ectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet</p>
                                        </div>
                                    </div>
                                </article>
                                <article class="tuyendung_item">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <a href="{{ action('PageController@tuyendungchitiet') }} "><img src="{{ asset('themes/images/img10.jpg') }}" alt="" class="img-responsive"></a>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            <h5><a href="{{ action('PageController@tuyendungchitiet') }} ">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</a></h5>
                                            <p>sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, cons ectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet</p>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <ul class="pagination">
                                    <li><a href="#">&laquo;</a></li>
                                    <li><a href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#">5</a></li>
                                    <li><a href="#">&raquo;</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop
