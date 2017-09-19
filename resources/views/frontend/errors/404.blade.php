@extends('frontend.layouts.blank')

@section('page_heading', 404)

@section('section')

    @include('frontend.layouts.partials.menu')

    @include('frontend.layouts.partials.headline')

    <main>

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="title_sub">404</h2>
                        <p>Xin lỗi, trang bạn đang tìm kiếm không thể tìm thấy</p>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop