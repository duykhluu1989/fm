@extends('frontend.layouts.main')

@section('page_heading', 'Quên mật khẩu')

@section('section')

    @include('frontend.layouts.partials.menu')

    <main>
        <section class="content mt106">
            <div class="container">
                <div class="row">
                    <h2 class="title_sub">QUÊN MẬT KHẨU </h2>
                    <div class="col-lg-6 col-lg-offset-3">
                        <form class="mb200" action="#" method="POST" role="form">
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="text" class="form-control" id="" placeholder="info@gmail.com">
                            </div>
                            <hr>
                            <a href="#" class="btn btnDangnhap"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> SEND</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop
