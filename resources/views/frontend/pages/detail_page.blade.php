@extends('frontend.layouts.main')

@section('og_description', $page->short_description)

@section('og_image', $page->image)

@section('page_heading', $page->name)

@section('section')

    @include('frontend.layouts.partials.menu')

    @include('frontend.layouts.partials.headline', ['bannerImage' => $page->image])

    <main>

        @include('frontend.layouts.partials.breadcrumb', ['breadcrumbTitle' => $page->name])

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="title_sub">{{ $page->name }}</h2>

                        <?php
                        echo $page->content;
                        ?>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop
