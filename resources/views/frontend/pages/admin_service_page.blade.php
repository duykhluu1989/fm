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

                            @foreach($pages as $page)
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 thongtin_items">
                                    <figure>
                                        <a class="btnThongtin" href="{{ action('Frontend\PageController@detailPage', ['id' => $page->id, 'slug' => $page->slug]) }}">
                                            <img src="{{ $page->image }}" alt="{{ $page->name }}" class="img-responsive">
                                        </a>
                                    </figure>
                                    <h3><a href="{{ action('Frontend\PageController@detailPage', ['id' => $page->id, 'slug' => $page->slug]) }}">{{ $page->name }}</a></h3>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop
