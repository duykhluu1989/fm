@extends('frontend.layouts.main')

@section('page_heading', 'Trang chủ')

@section('section')

    @include('frontend.layouts.partials.menu')

    <main>
        <section class="content mt106">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <h2 class="title_sub">ĐĂNG NHẬP</h2>
                        <form action="{{ action('Frontend\UserController@login') }}" method="POST" role="form">
                            <div class="form-group">
                                <label>Email / ID Name (*)</label>
                                <input type="text" class="form-control" name="login_email_username" value="{{ old('login_email_username') }}" required="required" />
                                @if($errors->has('login_email_username'))
                                    <span class="has-error">
                                        <span class="help-block">* {{ $errors->first('login_email_username') }}</span>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Mật khẩu (*)</label>
                                <input type="password" class="form-control" name="login_password" required="required" />
                                @if($errors->has('login_password'))
                                    <span class="has-error">
                                        <span class="help-block">* {{ $errors->first('login_password') }}</span>
                                    </span>
                                @endif
                            </div>
                            <hr>
                            <button type="submit" class="btn btnDangnhap"><i class="fa fa-lock fa-lg" aria-hidden="true"></i> ĐĂNG NHẬP</button>
                            <p><a class="btn btn-link" href="quenmatkhau.php">Quên mật khẩu?</a></p>
                            {{ csrf_field() }}
                        </form>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <h2 class="title_sub">ĐĂNG KÝ</h2>
                        <form action="{{ action('Frontend\UserController@register') }}" method="POST" role="form">
                            <div class="form-group">
                                <label>Họ tên (*)</label>
                                <input type="text" class="form-control" name="register_name" value="{{ old('register_name') }}" required="required" />
                                @if($errors->has('register_name'))
                                    <span class="has-error">
                                        <span class="help-block">* {{ $errors->first('register_name') }}</span>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Số điện thoại (*)</label>
                                <input type="text" class="form-control" name="register_phone" value="{{ old('register_phone') }}" required="required" />
                                @if($errors->has('register_phone'))
                                    <span class="has-error">
                                        <span class="help-block">* {{ $errors->first('register_phone') }}</span>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Email (*)</label>
                                <input type="text" class="form-control" name="register_email" value="{{ old('register_email') }}" required="required" />
                                @if($errors->has('register_email'))
                                    <span class="has-error">
                                        <span class="help-block">* {{ $errors->first('register_email') }}</span>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Mật khẩu (*)</label>
                                <input type="text" class="form-control" name="register_password" value="{{ old('register_password') }}" required="required" />
                                @if($errors->has('register_password'))
                                    <span class="has-error">
                                        <span class="help-block">* {{ $errors->first('register_password') }}</span>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Địa chỉ lấy hàng (*)</label>
                                <input type="text" class="form-control" name="register_address" value="{{ old('register_address') }}" required="required" />
                                @if($errors->has('register_address'))
                                    <span class="has-error">
                                        <span class="help-block">* {{ $errors->first('register_address') }}</span>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Tỉnh / thành phố (*)</label>
                                <select name="register_province" class="form-control" required="required">

                                </select>
                            </div>
                            <div class="form-group">
                                <label>Quận / huyện (*)</label>
                                <select name="register_district" class="form-control" required="required">
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Phường / xã (*)</label>
                                <select name="register_ward" class="form-control" required="required">
                                </select>
                            </div>
                            <hr>
                            <p>Thông tin Ngân hàng sử dụng cho mục đích chuyển trả tiền thu hộ</p>
                            <h2 class="title_sub">THÔNG TIN NGÂN HÀNG</h2>
                            <div class="form-group">
                                <label>Chủ tài khoản ngân hàng:</label>
                                <input type="text" class="form-control" name="register_bank" />
                            </div>
                            <div class="form-group">
                                <label>Số tài khoản ngân hàng:</label>
                                <input type="text" class="form-control" name="register_bank_number" />
                            </div>
                            <div class="form-group">
                                <label for="">Tên ngân hàng:</label>
                                <select name="" id="" class="form-control">
                                    <option value="">DONGA - NGAN HANG TMCP DONG A</option>
                                    <option value="">DONGA - NGAN HANG TMCP DONG A</option>
                                    <option value="">DONGA - NGAN HANG TMCP DONG A</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Chi nhánh ngân hàng:</label>
                                <select name="" id="" class="form-control">
                                    <option value="">DONGA BANK HO CHI MINH (HCM)</option>
                                    <option value="">DONGA BANK HO CHI MINH (HCM)</option>
                                    <option value="">DONGA BANK HO CHI MINH (HCM)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="" checked>
                                        Tôi đồng ý với <a href="chinhsach.php"><span class="red">chính sách dịch vụ</span></a> của ParcelPost
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <button type="submit" class="btn btnDangky"><i class="fa fa-user fa-lg" aria-hidden="true"></i> ĐĂNG KÝ</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.proccess')

    </main>

@stop
