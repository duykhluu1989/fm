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
                                <form class="frm_donDH" action="{{ action('Frontend\OrderController@placeOrder') }}" method="POST" role="form">
                                    <p><b>Thông tin lấy hàng</b></p>

                                    @if(count($userAddresses) > 0)

                                        <div class="form-group">
                                            <label>Địa chỉ: (*)</label>
                                            <select name="user_address" class="form-control" required="required">
                                                <?php
                                                $userAddressId = old('user_address');
                                                ?>

                                                @foreach($userAddresses as $userAddress)
                                                    @if((!empty($userAddressId) && $userAddressId == $userAddress->id) || (empty($userAddressId) && $userAddress->default == \App\Libraries\Helpers\Utility::ACTIVE_DB))
                                                        <option selected="selected" value="{{ $userAddress->id }}">{{ $userAddress->name . ', ' . $userAddress->phone . ', ' . $userAddress->address . ', ' . $userAddress->ward . ', ' . $userAddress->district . ', ' . $userAddress->province }}</option>
                                                    @else
                                                        <option value="{{ $userAddress->id }}">{{ $userAddress->name . ', ' . $userAddress->phone . ', ' . $userAddress->address . ', ' . $userAddress->ward . ', ' . $userAddress->district . ', ' . $userAddress->province }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                    @else

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
                                            <select id="RegisterProvince" name="register_province" class="form-control" required="required">
                                                <?php
                                                $province = old('register_province');
                                                ?>

                                                <option value=""></option>

                                                @foreach(\App\Libraries\Helpers\Area::$provinces as $code => $data)
                                                    @if($province == $code)
                                                        <option selected="selected" value="{{ $code }}">{{ $data['name'] }}</option>
                                                    @else
                                                        <option value="{{ $code }}">{{ $data['name'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if($errors->has('register_province'))
                                                <span class="has-error">
                                                    <span class="help-block">* {{ $errors->first('register_province') }}</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Quận / huyện (*)</label>
                                            <select id="RegisterDistrict" name="register_district" class="form-control" required="required">
                                                <?php
                                                $district = old('register_district');
                                                ?>

                                                <option value=""></option>

                                                @if($district && isset(\App\Libraries\Helpers\Area::$provinces[$province]['cities']))
                                                    @foreach(\App\Libraries\Helpers\Area::$provinces[$province]['cities'] as $code => $data)
                                                        @if($district == $code)
                                                            <option selected="selected" value="{{ $code }}">{{ $data }}</option>
                                                        @else
                                                            <option value="{{ $code }}">{{ $data }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if($errors->has('register_district'))
                                                <span class="has-error">
                                                    <span class="help-block">* {{ $errors->first('register_district') }}</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Phường / xã (*)</label>
                                            <input type="text" class="form-control" name="register_ward" value="{{ old('register_ward') }}" required="required" />
                                            @if($errors->has('register_ward'))
                                                <span class="has-error">
                                                    <span class="help-block">* {{ $errors->first('register_ward') }}</span>
                                                </span>
                                            @endif
                                        </div>
                                        <hr>
                                        <p>Thông tin Ngân hàng sử dụng cho mục đích chuyển trả tiền thu hộ</p>
                                        <h2 class="title_sub">THÔNG TIN NGÂN HÀNG</h2>
                                        <div class="form-group">
                                            <label>Chủ tài khoản ngân hàng:</label>
                                            <input type="text" class="form-control" name="register_bank_holder" value="{{ old('register_bank_holder') }}" />
                                            @if($errors->has('register_bank_holder'))
                                                <span class="has-error">
                                                    <span class="help-block">* {{ $errors->first('register_bank_holder') }}</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Số tài khoản ngân hàng:</label>
                                            <input type="text" class="form-control" name="register_bank_number" value="{{ old('register_bank_number') }}"  />
                                            @if($errors->has('register_bank_number'))
                                                <span class="has-error">
                                                    <span class="help-block">* {{ $errors->first('register_bank_number') }}</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Tên ngân hàng:</label>
                                            <input type="text" class="form-control" name="register_bank" value="{{ old('register_bank') }}" />
                                            @if($errors->has('register_bank'))
                                                <span class="has-error">
                                                    <span class="help-block">* {{ $errors->first('register_bank') }}</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Chi nhánh ngân hàng:</label>
                                            <input type="text" class="form-control" name="register_bank_branch" value="{{ old('register_bank_branch') }}" />
                                            @if($errors->has('register_bank_branch'))
                                                <span class="has-error">
                                                    <span class="help-block">* {{ $errors->first('register_bank_branch') }}</span>
                                                </span>
                                            @endif
                                        </div>

                                    @endif

                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Kích cỡ sản phẩm: (*)</label>
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
                                    {{ csrf_field() }}
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
