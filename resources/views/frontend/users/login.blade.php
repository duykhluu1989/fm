@extends('frontend.layouts.main')

@section('page_heading', 'Đăng nhập - Đăng ký')

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
                            <p><a class="btn btn-link" href="{{ action('Frontend\UserController@forgetPassword') }}">Quên mật khẩu?</a></p>
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
                                <input type="password" class="form-control" name="register_password" value="{{ old('register_password') }}" required="required" />
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
                                <select id="RegisterProvince" name="register_province" class="form-control" required="required">
                                    <?php
                                    $province = old('register_province');
                                    ?>

                                    <option value=""></option>

                                    @foreach(\App\Models\Area::getProvinces() as $area)
                                        @if($province == $area->id)
                                            <option selected="selected" value="{{ $area->id }}">{{ $area->name }}</option>
                                        @else
                                            <option value="{{ $area->id }}">{{ $area->name }}</option>
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

                                    @if($province)
                                        @foreach(\App\Models\Area::getDistricts($province) as $area)
                                            @if($district && $district == $area->id)
                                                <option selected="selected" value="{{ $area->id }}">{{ $area->name }}</option>
                                            @else
                                                <option value="{{ $area->id }}">{{ $area->name }}</option>
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
                                <select id="RegisterWard" name="register_ward" class="form-control" required="required">
                                    <?php
                                    $ward = old('register_ward');
                                    ?>

                                    <option value=""></option>

                                    @if($district)
                                        @foreach(\App\Models\Area::getWards($district) as $area)
                                            @if($ward && $ward == $area->id)
                                                <option selected="selected" value="{{ $area->id }}">{{ $area->name }}</option>
                                            @else
                                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
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
                                <label>Chủ tài khoản ngân hàng</label>
                                <input type="text" class="form-control" name="register_bank_holder" value="{{ old('register_bank_holder') }}" />
                                @if($errors->has('register_bank_holder'))
                                    <span class="has-error">
                                        <span class="help-block">* {{ $errors->first('register_bank_holder') }}</span>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Số tài khoản ngân hàng</label>
                                <input type="text" class="form-control" name="register_bank_number" value="{{ old('register_bank_number') }}"  />
                                @if($errors->has('register_bank_number'))
                                    <span class="has-error">
                                        <span class="help-block">* {{ $errors->first('register_bank_number') }}</span>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Tên ngân hàng</label>
                                <input type="text" class="form-control" name="register_bank" value="{{ old('register_bank') }}" />
                                @if($errors->has('register_bank'))
                                    <span class="has-error">
                                        <span class="help-block">* {{ $errors->first('register_bank') }}</span>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Chi nhánh ngân hàng</label>
                                <input type="text" class="form-control" name="register_bank_branch" value="{{ old('register_bank_branch') }}" />
                                @if($errors->has('register_bank_branch'))
                                    <span class="has-error">
                                        <span class="help-block">* {{ $errors->first('register_bank_branch') }}</span>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="register_accept_policy"<?php echo (old('register_accept_policy') ? ' checked="checked"' : ''); ?> required="required" />
                                        Tôi đồng ý với <a href="{{ !empty($policyPage) ? action('Frontend\PageController@detailPage', ['id' => $policyPage->id, 'slug' => $policyPage->slug]) : 'javascript:void:(0)' }}" target="_blank"><span class="red">chính sách dịch vụ</span></a> của ParcelPost
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="register_prepay_service"<?php echo (old('register_prepay_service') ? ' checked="checked"' : ''); ?> />
                                        Dịch Vụ Ứng Trước Tiền Thu Hộ Bằng Chuyển Khoản
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <button type="submit" class="btn btnDangky"><i class="fa fa-user fa-lg" aria-hidden="true"></i> ĐĂNG KÝ</button>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop

@push('scripts')
    <script type="text/javascript">
        $('#RegisterProvince').change(function() {

            changeArea($(this), $('#RegisterDistrict'), '{{ \App\Models\Area::TYPE_DISTRICT_DB }}');
            $('#RegisterWard').html('<option value=""></option>');

        });

        $('#RegisterDistrict').change(function() {

            changeArea($(this), $('#RegisterWard'), '{{ \App\Models\Area::TYPE_WARD_DB }}');

        });

        function changeArea(elem, updateElem, type)
        {
            updateElem.html('' +
                '<option value=""></option>' +
            '');

            if(elem != '')
            {
                $.ajax({
                    url: '{{ action('Frontend\OrderController@getListArea') }}',
                    type: 'get',
                    data: 'parent_id=' + elem.val() + '&type=' + type,
                    success: function(result) {
                        if(result)
                        {
                            result = JSON.parse(result);

                            var i;

                            for(i = 0;i < result.length;i ++)
                            {
                                updateElem.append('' +
                                    '<option value="' + result[i].id + '">' + result[i].name + '</option>' +
                                '');
                            }
                        }
                    }
                });
            }
        }
    </script>
@endpush
