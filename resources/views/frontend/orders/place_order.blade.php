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
                                            <label>Địa chỉ (*)</label>
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

                                        @if(auth()->guest())
                                            <div class="form-group">
                                                <label>Email (*)</label>
                                                <input type="text" class="form-control" name="register_email" id="RegisterEmailInput" value="{{ old('register_email') }}" required="required" />
                                                @if($errors->has('register_email'))
                                                    <span class="has-error">
                                                        <span class="help-block">* {{ $errors->first('register_email') }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                        @endif

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
                                                            <option selected="selected" value="{{ $code }}">{{ (is_array($data) ? $data['name'] : $data) }}</option>
                                                        @else
                                                            <option value="{{ $code }}">{{ (is_array($data) ? $data['name'] : $data) }}</option>
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

                                        @if(auth()->guest())
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
                                        @endif

                                    @endif

                                    <hr>
                                    <p><b>Thông tin sản phẩm</b></p>
                                    <div id="ListOrderItemDiv">
                                        <?php
                                        $itemNames = old('item.name');
                                        ?>

                                        @if(is_array($itemNames) && count($itemNames) > 0)
                                            <?php
                                            $i = 1;
                                            ?>
                                            @foreach($itemNames as $key => $itemName)
                                                @if($i > 1)
                                                    <div class="row">
                                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                            <div class="form-group">
                                                                <label>Tên sản phẩm (*)</label>
                                                                <input type="text" class="form-control" name="item[name][]" value="{{ $itemName }}" required="required" />
                                                                @if($errors->has('item.name.' . $key))
                                                                    <span class="has-error">
                                                                        <span class="help-block">* {{ $errors->first('item.name.' . $key) }}</span>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                            <div class="form-group">
                                                                <label>Số lượng (*)</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" name="item[quantity][]" value="{{ old('item.quantity.' . $key) }}" required="required" />
                                                                    <span class="input-group-btn">
                                                                        <a href="javascript:void(0)" class="btn btnThem"><i class="fa fa-times" aria-hidden="true"></i> Xoá</a>
                                                                    </span>
                                                                </div>
                                                                @if($errors->has('item.quantity.' . $key))
                                                                    <span class="has-error">
                                                                        <span class="help-block">* {{ $errors->first('item.quantity.' . $key) }}</span>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="row">
                                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                            <div class="form-group">
                                                                <label>Tên sản phẩm (*)</label>
                                                                <input type="text" class="form-control" name="item[name][]" value="{{ $itemName }}" required="required" />
                                                                @if($errors->has('item.name.' . $key))
                                                                    <span class="has-error">
                                                                        <span class="help-block">* {{ $errors->first('item.name.' . $key) }}</span>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                            <div class="form-group">
                                                                <label>Số lượng (*)</label>
                                                                <input type="text" class="form-control" name="item[quantity][]" value="{{ old('item.quantity.' . $key) }}" required="required" />
                                                                @if($errors->has('item.quantity.' . $key))
                                                                    <span class="has-error">
                                                                        <span class="help-block">* {{ $errors->first('item.quantity.' . $key) }}</span>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="row">
                                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Tên sản phẩm (*)</label>
                                                        <input type="text" class="form-control" name="item[name][]" required="required" />
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Số lượng (*)</label>
                                                        <input type="text" class="form-control" name="item[quantity][]" required="required" />
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <a href="javascript:void(0)" id="AddMoreOrderItemButton" class="btn btnThem"><i class="fa fa-plus" aria-hidden="true"></i> Thêm</a>
                                        </div>
                                    </div>
                                    <hr>
                                    <p><b>Thông tin người nhận hàng</b></p>
                                    <div class="form-group">
                                        <label>Tên người nhận (*)</label>
                                        <input type="text" class="form-control" name="receiver_name" value="{{ old('receiver_name') }}" required="required" />
                                        @if($errors->has('receiver_name'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('receiver_name') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Số điện thoại (*)</label>
                                        <input type="text" class="form-control" name="receiver_phone" value="{{ old('receiver_phone') }}" required="required" />
                                        @if($errors->has('receiver_phone'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('receiver_phone') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Địa chỉ giao hàng (*)</label>
                                        <input type="text" class="form-control" name="receiver_address" value="{{ old('receiver_address') }}" required="required" />
                                        @if($errors->has('receiver_address'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('receiver_address') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Thành phố (*)</label>
                                        <select name="receiver_province" id="ReceiverProvince" class="form-control" required="required">
                                            <?php
                                            $province = old('receiver_province');
                                            ?>

                                            <option value=""></option>

                                            @foreach($receiverAreas as $id => $area)
                                                @if($province == $id)
                                                    <option selected="selected" value="{{ $id }}">{{ $area['name'] }}</option>
                                                @else
                                                    <option value="{{ $id }}">{{ $area['name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if($errors->has('receiver_province'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('receiver_province') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Quận / huyện (*)</label>
                                        <select name="receiver_district" id="ReceiverDistrict" class="form-control" required="required">
                                            <?php
                                            $district = old('receiver_district');
                                            ?>

                                            <option value=""></option>

                                            @if($district && isset($receiverAreas[$province]['children_areas']))
                                                @foreach($receiverAreas[$province]['children_areas'] as $area)
                                                    @if($district == $area['id'])
                                                        <option selected="selected" data-shipping-price="{{ $area['shipping_price'] }}" value="{{ $area['id'] }}">{{ $area['name'] }}</option>
                                                    @else
                                                        <option data-shipping-price="{{ $area['shipping_price'] }}" value="{{ $area['id'] }}">{{ $area['name'] }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                        @if($errors->has('receiver_district'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('receiver_district') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Phường / xã (*)</label>
                                        <input name="receiver_ward" class="form-control" value="{{ old('receiver_ward') }}" required="required" />
                                        @if($errors->has('receiver_ward'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('receiver_ward') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Trọng lượng gói hàng (gr)</label>
                                        <input type="text" class="form-control" id="OrderWeightInput" name="weight" value="{{ old('weight') }}" />
                                        @if($errors->has('weight'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('weight') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Kích thước gói hàng (mm)</label>
                                        <input type="text" class="form-control" id="OrderDimensionInput" name="dimension" value="{{ old('dimension') }}" placeholder="Dài x Rộng x Cao" />
                                        @if($errors->has('dimension'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('dimension') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Tiền thu hộ</label>
                                        <input type="text" class="form-control InputForNumber" name="cod_price" id="OrderCodPriceInput" value="{{ old('cod_price') }}" />
                                        @if($errors->has('cod_price'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('cod_price') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Phí ship (tạm tính)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" readonly="readonly" id="OrderShippingPriceInput" />
                                            <span class="input-group-btn">
                                                <a href="javascript:void(0)" id="CalculateOrderShippingPriceButton" class="btn btnThem"><i class="fa fa-truck" aria-hidden="true"></i> Tính phí ship</a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="shipping_payment" value="{{ \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB }}" checked="checked" />
                                                Shop trả
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="shipping_payment" value="{{ \App\Models\Order::SHIPPING_PAYMENT_RECEIVER_DB }}" />
                                                Khách trả
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Tổng tiền thu hộ</label>
                                        <input type="text" class="form-control" readonly="readonly" id="OrderTotalCodPriceInput" />
                                    </div>
                                    <div class="form-group">
                                        <label>Ghi chú</label>
                                        <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                                        @if($errors->has('note'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('note') }}</span>
                                            </span>
                                        @endif
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


@push('scripts')
    <script type="text/javascript">
        $('#RegisterEmailInput').change(function() {
            if($(this).val() != '')
            {
                $.ajax({
                    url: '{{ action('Frontend\UserController@checkRegisterEmail') }}',
                    type: 'get',
                    data: 'email=' + $(this).val(),
                    success: function(result) {
                        if(result)
                        {
                            swal({
                                title: 'Email này đã đăng ký tài khoản vào ngày ' + result + ', bạn vui lòng đăng nhập tài khoản',
                                type: 'error',
                                confirmButtonClass: 'btn-danger',
                                allowOutsideClick: true
                            });
                        }
                    }
                });
            }
        });

        $('#RegisterProvince').change(function() {
            var districtElem = $('#RegisterDistrict');

            districtElem.html('' +
                '<option value=""></option>' +
            '');

            if($(this).val() != '')
            {
                $.ajax({
                    url: '{{ action('Frontend\OrderController@getListDistrict') }}',
                    type: 'get',
                    data: 'province_code=' + $(this).val(),
                    success: function(result) {
                        if(result)
                        {
                            result = JSON.parse(result);

                            for(var code in result)
                            {
                                if(result.hasOwnProperty(code))
                                {
                                    districtElem.append('' +
                                        '<option value="' + code + '">' + ((typeof result[code]) == 'string' ? result[code] : result[code].name) + '</option>' +
                                    '');
                                }
                            }
                        }
                    }
                });
            }
        });

        $('#ReceiverProvince').change(function() {
            var districtElem = $('#ReceiverDistrict');

            districtElem.html('' +
                '<option value=""></option>' +
            '');

            if($(this).val() != '')
            {
                $.ajax({
                    url: '{{ action('Frontend\OrderController@getListDistrict') }}',
                    type: 'get',
                    data: 'province_code=' + $(this).val() + '&receiver=1',
                    success: function(result) {
                        if(result)
                        {
                            result = JSON.parse(result);

                            var i;

                            for(i = 0;i < result.length;i ++)
                            {
                                districtElem.append('' +
                                    '<option data-shipping-price="' + result[i].shipping_price + '" value="' + result[i].id + '">' + result[i].name + '</option>' +
                                '');
                            }
                        }
                    }
                });
            }
        });

        $('#AddMoreOrderItemButton').click(function() {
            $('#ListOrderItemDiv').append('' +
                '<div class="row">' +
                '<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">' +
                '<div class="form-group">' +
                '<label>Tên sản phẩm (*)</label>' +
                '<input type="text" class="form-control" name="item[name][]" required="required" />' +
                '</div>' +
                '</div>' +
                '<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">' +
                '<div class="form-group">' +
                '<label>Số lượng (*)</label>' +
                '<div class="input-group">' +
                '<input type="text" class="form-control" name="item[quantity][]" required="required" />' +
                '<span class="input-group-btn">' +
                '<a href="javascript:void(0)" class="btn btnThem RemoveOrderItemButton"><i class="fa fa-times" aria-hidden="true"></i> Xoá</a>' +
                '</span>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
            '');
        });

        $('#ListOrderItemDiv').on('click', 'a', function() {
            if($(this).hasClass('RemoveOrderItemButton'))
                $(this).parent().parent().parent().parent().parent().remove();
        });

        $('#OrderCodPriceInput').change(function() {
            calculateTotalCodPrice();
        });

        function calculateTotalCodPrice()
        {
            var codElem = $('#OrderCodPriceInput');
            var shippingElem = $('#OrderShippingPriceInput');

            if($('input[type="radio"][name="shipping_payment"]:checked').val() == '{{ \App\Models\Order::SHIPPING_PAYMENT_RECEIVER_DB }}' && shippingElem.val() != '')
            {
                if(codElem.val() != '')
                    $('#OrderTotalCodPriceInput').val(formatNumber((parseInt(codElem.val().split('.').join('')) + parseInt(shippingElem.val().split('.').join(''))).toString(), '.'));
                else
                    $('#OrderTotalCodPriceInput').val(shippingElem.val());
            }
            else
                $('#OrderTotalCodPriceInput').val(codElem.val());
        }

        $('input[type="radio"][name="shipping_payment"]').change(function() {
            calculateTotalCodPrice();
        });

        $('#CalculateOrderShippingPriceButton').click(function() {
            $.ajax({
                url: '{{ action('Frontend\OrderController@calculateShippingPrice') }}',
                type: 'get',
                data: 'province_code=' + $('#ReceiverProvince').val() + '&district_code=' + $('#ReceiverDistrict').val() + '&weight=' + $('#OrderWeightInput').val() + '&dimension=' + $('#OrderDimensionInput').val(),
                success: function(result) {
                    if(result)
                    {
                        if(!isNaN(result))
                        {
                            $('#OrderShippingPriceInput').val(formatNumber(result, '.'));

                            calculateTotalCodPrice();
                        }
                        else
                        {
                            result = JSON.parse(result);

                            swal({
                                title: result[0],
                                type: 'error',
                                confirmButtonClass: 'btn-danger',
                                allowOutsideClick: true
                            });
                        }
                    }
                }
            });
        });
    </script>
@endpush

