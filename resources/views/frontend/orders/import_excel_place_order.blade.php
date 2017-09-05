@extends('frontend.layouts.main')

@section('page_heading', 'Đăng đơn hàng bằng excel')

@section('section')

    @include('frontend.layouts.partials.menu')

    @include('frontend.layouts.partials.headline')

    <main>

        @include('frontend.users.partials.navigation')

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="title_sub">ĐĂNG ĐƠN HÀNG BẰNG EXCEL</h2>
                        <div class="row">
                            <div class="col-lg-12">
                                <form class="frm_donDH" action="{{ action('Frontend\OrderController@importExcelPlaceOrder') }}" method="POST" enctype="multipart/form-data" role="form">
                                    <div class="form-group">
                                        <label>Chọn file excel (*)</label>
                                        <input type="file" name="file" required="required" accept="{{ implode(', ', \App\Libraries\Helpers\Utility::getValidExcelExt(true)) }}" />
                                        @if($errors->has('file'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('file') }}</span>
                                            </span>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btnDangDH"><i class="fa fa-upload fa-lg" aria-hidden="true"></i> ĐĂNG ĐƠN HÀNG</button>
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop