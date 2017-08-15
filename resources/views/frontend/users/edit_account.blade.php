@extends('frontend.layouts.main')

@section('page_heading', 'Cập nhật thông tin tài khoản')

@section('section')

    @include('frontend.layouts.partials.menu')

    <main>

        @include('frontend.users.partials.navigation')

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="title_sub">CẬP NHẬT THÔNG TIN TÀI KHOẢN</h2>
                        <h4 class="title_user line-on-right">Thông tin cơ bản</h4>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <form action="" method="POST" role="form">
                                    <div class="form-group">
                                        <label>Tên (*)</label>
                                        <input type="text" class="form-control" name="register_name" value="{{ old('register_name', $user->name) }}" required="required" />
                                        @if($errors->has('register_name'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('register_name') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Điện thoại (*)</label>
                                        <input type="text" class="form-control" name="register_phone" value="{{ old('register_phone', $user->phone) }}" required="required" />
                                        @if($errors->has('register_phone'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('register_phone') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Email (*)</label>
                                        <input type="text" class="form-control" name="register_email" value="{{ old('register_email', $user->email) }}" required="required" />
                                        @if($errors->has('register_email'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('register_email') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Mật khẩu mới</label>
                                        <input type="password" class="form-control" name="register_password" />
                                        @if($errors->has('register_password'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('register_password') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Xác nhận mật khẩu mới</label>
                                        <input type="password" class="form-control" name="confirm_password" />
                                    </div>
                                    <div class="form-group">
                                        <label>Chủ tài khoản ngân hàng</label>
                                        <input type="text" class="form-control" name="register_bank_holder" value="{{ old('register_bank_holder', $user->bank_holder) }}" />
                                        @if($errors->has('register_bank_holder'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('register_bank_holder') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Số tài khoản ngân hàng</label>
                                        <input type="text" class="form-control" name="register_bank_number" value="{{ old('register_bank_number', $user->bank_number) }}" />
                                        @if($errors->has('register_bank_number'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('register_bank_number') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Tên ngân hàng</label>
                                        <input type="text" class="form-control" name="register_bank" value="{{ old('register_bank', $user->bank) }}" />
                                        @if($errors->has('register_bank'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('register_bank') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Chi nhánh ngân hàng</label>
                                        <input type="text" class="form-control" name="register_bank_branch" value="{{ old('register_bank_branch', $user->bank_branch) }}" />
                                        @if($errors->has('register_bank_branch'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('register_bank_branch') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <button type="submit" class="btn btnLuuTT"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i> LƯU THÔNG TIN</button>
                                </form>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                        </div>

                        <h4 class="title_user line-on-right">Địa chỉ lấy hàng - kho hàng 1 - mã kho 137707</h4>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <form action="" method="POST" role="form">
                                    <div class="form-group">
                                        <label for="">Tên người liên hệ:</label>
                                        <input type="text" class="form-control" id="" placeholder="Ninishop">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Số điện thoại:</label>
                                        <input type="text" class="form-control" id="" placeholder="0908911493">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Thành phố:</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">TP. Hồ Chí Minh</option>
                                            <option value="">...</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Địa chỉ lấy hàng:</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">51D phú mỹ </option>
                                            <option value="">51D phú mỹ </option>
                                            <option value="">51D phú mỹ </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Quận huyện:</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">Quận 1</option>
                                            <option value="">Quận 2</option>
                                            <option value="">Quận ...</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Phường/xã:</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">Phường 1</option>
                                            <option value="">Phường 2</option>
                                            <option value="">Phường ...</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                        </div>

                        <h4 class="title_user line-on-right">Địa chỉ lấy hàng - kho hàng 2 - mã kho 137708</h4>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <form action="" method="POST" role="form">
                                    <div class="form-group">
                                        <label for="">Tên người liên hệ:</label>
                                        <input type="text" class="form-control" id="" placeholder="Betty">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Số điện thoại:</label>
                                        <input type="text" class="form-control" id="" placeholder="0908911493">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Thành phố:</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">TP. Hồ Chí Minh</option>
                                            <option value="">...</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Địa chỉ lấy hàng:</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">248A Nơ trang long</option>
                                            <option value="">248A Nơ trang long</option>
                                            <option value="">248A Nơ trang long</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Quận huyện:</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">Quận 1</option>
                                            <option value="">Quận 2</option>
                                            <option value="">Quận ...</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Phường/xã:</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">Phường 1</option>
                                            <option value="">Phường 2</option>
                                            <option value="">Phường ...</option>
                                        </select>
                                    </div>
                                    <a href="#" class="btn btnThemDD"><i class="fa fa-plus" aria-hidden="true"></i> THÊM ĐỊA ĐIỂM LẤY HÀNG - KHO HÀNG</a>
                                    <a href="#" class="btn btnLuuTT"><i class="fa fa-floppy-o" aria-hidden="true"></i> LƯU ĐỊA CHỈ</a>
                                </form>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop
