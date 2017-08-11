@extends('frontend.layouts.main')

@section('page_heading', 'Đơn đặt hàng')

@section('section')

    @include('frontend.layouts.partials.menu')

    @include('frontend.layouts.partials.headline')

    <main>

        @include('frontend.users.partials.navigation')

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="title_sub">ĐƠN ĐẶT HÀNG</h2>
                        <div class="row">
                            <div class="col-lg-6 col-lg-offset-3">
                                <form class="frm_donDH" action="" method="POST" role="form">
                                    <p><b>Thông tin lấy hàng</b></p>
                                    <div class="form-group">
                                        <label for="">Địa chỉ: (*)</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">Lorem ipsum dolor sit amet, consectetur adipiscing elit</option>
                                            <option value="">Lorem ipsum dolor sit amet, consectetur adipiscing elit</option>
                                            <option value="">Lorem ipsum dolor sit amet, consectetur adipiscing elit</option>
                                        </select>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label for="">Kích cỡ sản phẩm: (*)</label>
                                                <input type="text" class="form-control" id="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label class="hidden-xs" for="">&nbsp;</label>
                                                <input type="number" class="form-control" id="" placeholder="2">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label class="hidden-xs" style="display: block;" for="">&nbsp;</label>
                                                <a href="#" class="btn btnThem"><i class="fa fa-plus" aria-hidden="true"></i> Thêm</a>
                                                <a href="#" class="btn btnThem"><i class="fa fa-times" aria-hidden="true"></i> Xoá</a>
                                            </div>
                                        </div>
                                    </div>

                                    <p><b>Thông tin người nhận hàng</b></p>
                                    <div class="form-group">
                                        <label for="">Nhập số điện thoại: (*)</label>
                                        <input type="text" class="form-control" id="" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Tên khách hàng: (*)</label>
                                        <input type="text" class="form-control" id="" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Quận/ huyện: (*)</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">...</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Phường/ xã: (*)</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">...</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Thành phố: (*)</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">...</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Thu tiền hộ: (*)</label>
                                        <input type="text" class="form-control" id="" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Phí ship: (*)</label>
                                        <input type="text" class="form-control" id="" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="1" id="" value="" checked="checked">
                                                Shop trả
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="1" id="" value="">
                                                Khách trả
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Ghi chú:  (*)</label>
                                        <textarea name="" id="" class="form-control" rows="8"></textarea>
                                    </div>
                                    <button type="submit" class="btn btnDangDH"><i class="fa fa-upload fa-lg" aria-hidden="true"></i> ĐĂNG ĐƠN HÀNG</button>
                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <hr>
                                <a href="#" class="btn btnThemDH"><i class="fa fa-plus fa-lg" aria-hidden="true"></i> THÊM ĐƠN HÀNG</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop
