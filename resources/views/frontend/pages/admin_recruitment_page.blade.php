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

                                @foreach($pages as $page)
                                    <article class="tuyendung_item">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <a href="{{ action('Frontend\PageController@detailPage', ['id' => $page->id, 'slug' => $page->slug]) }}"><img src="{{ $page->image }}" alt="{{ $page->name }}" class="img-responsive"></a>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                <h5><a href="{{ action('Frontend\PageController@detailPage', ['id' => $page->id, 'slug' => $page->slug]) }}">{{ $page->name }}</a></h5>
                                                <p>{{ $page->short_description }}</p>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <ul class="pagination">
                                    @if($pages->lastPage() > 1)
                                        @if($pages->currentPage() > 1)
                                            <li><a href="{{ $pages->previousPageUrl() }}">&laquo;</a></li>
                                            <li><a href="{{ $pages->url(1) }}">1</a></li>
                                        @endif

                                        @for($i = 2;$i >= 1;$i --)
                                            @if($pages->currentPage() - $i > 1)
                                                @if($pages->currentPage() - $i > 2 && $i == 2)
                                                    <li>...</li>
                                                    <li><a href="{{ $pages->url($pages->currentPage() - $i) }}">{{ $pages->currentPage() - $i }}</a></li>
                                                @else
                                                    <li><a href="{{ $pages->url($pages->currentPage() - $i) }}">{{ $pages->currentPage() - $i }}</a></li>
                                                @endif
                                            @endif
                                        @endfor

                                        <li class="active"><a href="javascript:void(0)">{{ $pages->currentPage() }}</a></li>

                                        @for($i = 1;$i <= 2;$i ++)
                                            @if($pages->currentPage() + $i < $pages->lastPage())
                                                @if($pages->currentPage() + $i < $pages->lastPage() - 1 && $i == 2)
                                                    <li><a href="{{ $pages->url($pages->currentPage() + $i) }}">{{ $pages->currentPage() + $i }}</a></li>
                                                    <li>...</li>
                                                @else
                                                    <li><a href="{{ $pages->url($pages->currentPage() + $i) }}">{{ $pages->currentPage() + $i }}</a></li>
                                                @endif
                                            @endif
                                        @endfor

                                        @if($pages->currentPage() < $pages->lastPage())
                                            <li><a href="{{ $pages->url($pages->lastPage()) }}">{{ $pages->lastPage() }}</a></li>
                                            <li><a href="{{ $pages->nextPageUrl() }}">&raquo;</a></li>
                                        @endif
                                    @endif
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