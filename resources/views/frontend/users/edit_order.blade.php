@extends('frontend.layouts.main')

@section('page_heading', 'Sửa đơn hàng ' . $order->number)

@section('section')

    @include('frontend.layouts.partials.menu')

    <main>

        @include('frontend.users.partials.navigation')

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="title_sub">SỬA ĐƠN HÀNG {{ $order->number }}</h2>
                        <div class="row">
                            <div class="col-lg-12">
                                <form class="frm_donDH" action="{{ action('Frontend\UserController@editOrder', ['id' => $order->id]) }}" method="POST" role="form">

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <p><b>Thông tin lấy hàng</b></p>
                                            <div class="form-group">
                                                <label>Chọn địa chỉ đã có</label>
                                                <select id="RegisterUserAddress" class="form-control">
                                                    <option value=""></option>

                                                    @foreach($order->user->userAddresses as $userAddress)
                                                        <option data-name="{{ $userAddress->name }}" data-phone="{{ $userAddress->phone }}" data-address="{{ $userAddress->address }}" data-ward-id="{{ $userAddress->ward_id }}" data-district-id="{{ $userAddress->district_id }}" data-province-id="{{ $userAddress->province_id }}"
                                                                value="{{ $userAddress->id }}">{{ $userAddress->name . ', ' . $userAddress->phone . ', ' . $userAddress->address . ', ' . $userAddress->ward . ', ' . $userAddress->district . ', ' . $userAddress->province }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Họ tên (*)</label>
                                                <input type="text" class="form-control" name="register_name" id="RegisterName" value="{{ old('register_name', $order->senderAddress->name) }}" required="required" />
                                                @if($errors->has('register_name'))
                                                    <span class="has-error">
                                                        <span class="help-block">* {{ $errors->first('register_name') }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>Số điện thoại (*)</label>
                                                <input type="text" class="form-control" name="register_phone" id="RegisterPhone" value="{{ old('register_phone', $order->senderAddress->phone) }}" required="required" />
                                                @if($errors->has('register_phone'))
                                                    <span class="has-error">
                                                        <span class="help-block">* {{ $errors->first('register_phone') }}</span>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label>Địa chỉ lấy hàng (*)</label>
                                                <input type="text" class="form-control" name="register_address" id="RegisterAddress" value="{{ old('register_address', $order->senderAddress->address) }}" required="required" />
                                                @if($errors->has('register_address'))
                                                    <span class="has-error">
                                                        <span class="help-block">* {{ $errors->first('register_address') }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>Tỉnh / thành phố (*)</label>
                                                <select name="register_province" class="form-control" id="RegisterProvince" required="required">
                                                    <?php
                                                    $province = old('register_province', $order->senderAddress->province_id);
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
                                                <select name="register_district" class="form-control" id="RegisterDistrict" required="required">
                                                    <?php
                                                    $district = old('register_district', $order->senderAddress->district_id);
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
                                                <select name="register_ward" class="form-control" id="RegisterWard" required="required">
                                                    <?php
                                                    $ward = old('register_ward', $order->senderAddress->ward_id);
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
                                            <div class="form-group">
                                                <label>Trọng lượng gói hàng (kg)</label>
                                                <input type="text" class="form-control" id="OrderWeightInput" name="weight" value="{{ old('weight', $order->weight) }}" />
                                                @if($errors->has('weight'))
                                                    <span class="has-error">
                                                        <span class="help-block">* {{ $errors->first('weight') }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>Kích thước gói hàng (cm)</label>
                                                <input type="text" class="form-control" id="OrderDimensionInput" name="dimension" value="{{ old('dimension', $order->dimension) }}" placeholder="Dài x Rộng x Cao" />
                                                @if($errors->has('dimension'))
                                                    <span class="has-error">
                                                        <span class="help-block">* {{ $errors->first('dimension') }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>Tiền thu hộ</label>
                                                <input type="text" class="form-control InputForNumber" id="OrderCodPriceInput" name="cod_price" value="{{ old('cod_price', \App\Libraries\Helpers\Utility::formatNumber($order->cod_price)) }}" />
                                                @if($errors->has('cod_price'))
                                                    <span class="has-error">
                                                        <span class="help-block">* {{ $errors->first('cod_price') }}</span>
                                                    </span>
                                                @endif
                                            </div>

                                            @if(!empty($order->discount))
                                                <div class="form-group">
                                                    <label>Mã giảm giá</label>
                                                    <input type="text" class="form-control" id="OrderDiscountCodeInput" value="{{ $order->discount->code }}" readonly="readonly" />
                                                </div>
                                            @else
                                                <div class="form-group">
                                                    <label>Mã giảm giá</label>
                                                    <input type="text" class="form-control" id="OrderDiscountCodeInput" name="discount_code" value="{{ old('discount_code') }}" />
                                                </div>
                                                @if($errors->has('discount_code'))
                                                    <span class="has-error">
                                                        <span class="help-block">* {{ $errors->first('discount_code') }}</span>
                                                    </span>
                                                @endif
                                            @endif

                                            <div class="form-group">
                                                <label>Được giảm giá</label>
                                                <input type="text" class="form-control" id="OrderDiscountShippingPriceInput" name="discount_shipping_price" value="{{ old('discount_shipping_price', \App\Libraries\Helpers\Utility::formatNumber($order->discount_shipping_price)) }}" readonly="readonly" />
                                            </div>
                                            <div class="form-group">
                                                <label>Phí ship (tạm tính)</label>
                                                <input type="text" class="form-control" id="OrderShippingPriceInput" name="shipping_price" value="{{ old('shipping_price', \App\Libraries\Helpers\Utility::formatNumber($order->shipping_price)) }}" readonly="readonly" />
                                            </div>
                                            <div class="form-group">
                                                <?php
                                                $shippingPayment = old('shipping_payment', $order->shipping_payment);
                                                ?>

                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="shipping_payment" value="{{ \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB }}"<?php echo ($shippingPayment == \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB ? ' checked="checked"' : ''); ?> />
                                                        Shop trả
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="shipping_payment" value="{{ \App\Models\Order::SHIPPING_PAYMENT_RECEIVER_DB }}"<?php echo ($shippingPayment == \App\Models\Order::SHIPPING_PAYMENT_RECEIVER_DB ? ' checked="checked"' : ''); ?> />
                                                        Khách trả
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Tổng tiền thu hộ</label>
                                                <input type="text" class="form-control" id="OrderTotalCodPriceInput" name="total_cod_price" value="{{ old('total_cod_price', \App\Libraries\Helpers\Utility::formatNumber($order->total_cod_price)) }}" readonly="readonly" />
                                            </div>

                                            @if(auth()->user()->prepay == \App\Libraries\Helpers\Utility::ACTIVE_DB)
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="prepay" <?php echo (old('prepay', $order->prepay) ? ' checked="checked"' : ''); ?> />
                                                            Ứng Trước Tiền Thu Hộ Bằng Chuyển Khoản
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="form-group">
                                                <label>Ghi chú</label>
                                                <textarea name="note" class="form-control">{{ old('note', $order->note) }}</textarea>
                                                @if($errors->has('note'))
                                                    <span class="has-error">
                                                        <span class="help-block">* {{ $errors->first('note') }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <p><b>Thông tin người nhận hàng</b></p>
                                            <div class="form-group">
                                                <label>Tên người nhận (*)</label>
                                                <input type="text" class="form-control" name="receiver_name" value="{{ old('receiver_name', $order->receiverAddress->name) }}" required="required" />
                                                @if($errors->has('receiver_name'))
                                                    <span class="has-error">
                                                        <span class="help-block">* {{ $errors->first('receiver_name') }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>Số điện thoại (*)</label>
                                                <input type="text" class="form-control" name="receiver_phone" value="{{ old('receiver_phone', $order->receiverAddress->phone) }}" required="required" />
                                                @if($errors->has('receiver_phone'))
                                                    <span class="has-error">
                                                        <span class="help-block">* {{ $errors->first('receiver_phone') }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>Địa chỉ giao hàng (*)</label>
                                                <input type="text" class="form-control" name="receiver_address" value="{{ old('receiver_address', $order->receiverAddress->address) }}" required="required" />
                                                @if($errors->has('receiver_address'))
                                                    <span class="has-error">
                                                        <span class="help-block">* {{ $errors->first('receiver_address') }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>Thành phố (*)</label>
                                                <select name="receiver_province" class="form-control" id="ReceiverProvince" required="required">
                                                    <?php
                                                    $province = old('receiver_province', $order->receiverAddress->province_id);
                                                    ?>

                                                    <option value=""></option>

                                                    @foreach(\App\Models\Area::getProvinces(true) as $area)
                                                        @if($province == $area->id)
                                                            <option selected="selected" value="{{ $area->id }}">{{ $area->name }}</option>
                                                        @else
                                                            <option value="{{ $area->id }}">{{ $area->name }}</option>
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
                                                <select name="receiver_district" class="form-control" id="ReceiverDistrict" required="required">
                                                    <?php
                                                    $district = old('receiver_district', $order->receiverAddress->district_id);
                                                    ?>

                                                    <option value=""></option>

                                                    @if($province)
                                                        @foreach(\App\Models\Area::getDistricts($province, true) as $area)
                                                            @if($district && $district == $area->id)
                                                                <option selected="selected" value="{{ $area->id }}">{{ $area->name }}</option>
                                                            @else
                                                                <option value="{{ $area->id }}">{{ $area->name }}</option>
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
                                                <select name="receiver_ward" class="form-control" id="ReceiverWard" required="required">
                                                    <?php
                                                    $ward = old('receiver_ward', $order->receiverAddress->ward_id);
                                                    ?>

                                                    <option value=""></option>

                                                    @if($district)
                                                        @foreach(\App\Models\Area::getWards($district, true) as $area)
                                                            @if($ward && $ward == $area->id)
                                                                <option selected="selected" value="{{ $area->id }}">{{ $area->name }}</option>
                                                            @else
                                                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @if($errors->has('receiver_ward'))
                                                    <span class="has-error">
                                                        <span class="help-block">* {{ $errors->first('receiver_ward') }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btnDangDH"><i class="fa fa-upload fa-lg" aria-hidden="true"></i> CẬP NHẬT THÔNG TIN</button>
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

@push('scripts')
    <script type="text/javascript">
        $('#RegisterUserAddress').change(function() {
            var optElem = $(this).find(':selected');

            $('#RegisterName').val(optElem.data('name'));
            $('#RegisterPhone').val(optElem.data('phone'));
            $('#RegisterAddress').val(optElem.data('address'));
            $('#RegisterProvince').val(optElem.data('province-id'));

            $('#RegisterDistrict').html('' +
                '<option value=""></option>' +
            '');

            $.ajax({
                url: '{{ action('Frontend\OrderController@getListArea') }}',
                type: 'get',
                data: 'parent_id=' + $('#RegisterProvince').val() + '&type={{ \App\Models\Area::TYPE_DISTRICT_DB }}',
                success: function(result) {
                    if(result)
                    {
                        result = JSON.parse(result);

                        var i;

                        for(i = 0;i < result.length;i ++)
                        {
                            if(result[i].id == optElem.data('district-id'))
                            {
                                $('#RegisterDistrict').append('' +
                                    '<option selected="selected" value="' + result[i].id + '">' + result[i].name + '</option>' +
                                '');
                            }
                            else
                            {
                                $('#RegisterDistrict').append('' +
                                    '<option value="' + result[i].id + '">' + result[i].name + '</option>' +
                                '');
                            }
                        }

                        $('#RegisterWard').html('' +
                            '<option value=""></option>' +
                        '');

                        $.ajax({
                            url: '{{ action('Frontend\OrderController@getListArea') }}',
                            type: 'get',
                            data: 'parent_id=' + $('#RegisterDistrict').val() + '&type={{ \App\Models\Area::TYPE_WARD_DB }}',
                            success: function(result) {
                                if(result)
                                {
                                    result = JSON.parse(result);

                                    var i;

                                    for(i = 0;i < result.length;i ++)
                                    {
                                        if(result[i].id == optElem.data('ward-id'))
                                        {
                                            $('#RegisterWard').append('' +
                                                '<option selected="selected" value="' + result[i].id + '">' + result[i].name + '</option>' +
                                            '');
                                        }
                                        else
                                        {
                                            $('#RegisterWard').append('' +
                                                '<option value="' + result[i].id + '">' + result[i].name + '</option>' +
                                            '');
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            });

            $(this).val('');
        });

        $('#RegisterProvince').change(function() {
            changeArea($(this), $('#RegisterDistrict'), '{{ \App\Models\Area::TYPE_DISTRICT_DB }}', false);
            $('#RegisterWard').html('<option value=""></option>');
        });

        $('#RegisterDistrict').change(function() {
            changeArea($(this), $('#RegisterWard'), '{{ \App\Models\Area::TYPE_WARD_DB }}', false);
        });

        $('#ReceiverProvince').change(function() {
            changeArea($(this), $('#ReceiverDistrict'), '{{ \App\Models\Area::TYPE_DISTRICT_DB }}', true);
            $('#ReceiverWard').html('<option value=""></option>');

            calculateShippingPrice($('#ReceiverDistrict'), $('#OrderWeightInput'), $('#OrderDimensionInput'), $('#OrderShippingPriceInput'), $('#OrderDiscountCodeInput'), $('#OrderDiscountShippingPriceInput'));
        });

        $('#ReceiverDistrict').change(function() {
            changeArea($(this), $('#ReceiverWard'), '{{ \App\Models\Area::TYPE_WARD_DB }}', true);

            calculateShippingPrice($('#ReceiverDistrict'), $('#OrderWeightInput'), $('#OrderDimensionInput'), $('#OrderShippingPriceInput'), $('#OrderDiscountCodeInput'), $('#OrderDiscountShippingPriceInput'));
        });

        $('#OrderWeightInput').change(function() {
            calculateShippingPrice($('#ReceiverDistrict'), $('#OrderWeightInput'), $('#OrderDimensionInput'), $('#OrderShippingPriceInput'), $('#OrderDiscountCodeInput'), $('#OrderDiscountShippingPriceInput'));
        });

        $('#OrderDimensionInput').change(function() {
            calculateShippingPrice($('#ReceiverDistrict'), $('#OrderWeightInput'), $('#OrderDimensionInput'), $('#OrderShippingPriceInput'), $('#OrderDiscountCodeInput'), $('#OrderDiscountShippingPriceInput'));
        });

        $('#OrderCodPriceInput').change(function() {
            calculateShippingPrice($('#ReceiverDistrict'), $('#OrderWeightInput'), $('#OrderDimensionInput'), $('#OrderShippingPriceInput'), $('#OrderDiscountCodeInput'), $('#OrderDiscountShippingPriceInput'));
        });

        $('input[type="radio"][name="shipping_payment"]').change(function() {
            calculateShippingPrice($('#ReceiverDistrict'), $('#OrderWeightInput'), $('#OrderDimensionInput'), $('#OrderShippingPriceInput'), $('#OrderDiscountCodeInput'), $('#OrderDiscountShippingPriceInput'));
        });

        $('#OrderDiscountCodeInput').change(function() {
            calculateDiscountShippingPrice($(this), $('#OrderDiscountShippingPriceInput'), $('#OrderShippingPriceInput'));
        });

        function changeArea(elem, updateElem, type, receiver)
        {
            updateElem.html('' +
                '<option value=""></option>' +
            '');

            if(elem.val() != '')
            {
                $.ajax({
                    url: '{{ action('Frontend\OrderController@getListArea') }}',
                    type: 'get',
                    data: 'parent_id=' + elem.val() + '&type=' + type + (receiver ? '&receiver=1' : ''),
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

        function calculateShippingPrice(districtElem, weightElem, dimensionElem, shippingPriceElem, discountCodeElem, discountShippingPriceElem)
        {
            if(districtElem.val() != '')
            {
                $.ajax({
                    url: '{{ action('Frontend\OrderController@calculateShippingPrice') }}',
                    type: 'get',
                    data: 'register_district=' + districtElem.val() + '&weight=' + weightElem.val() + '&dimension=' + dimensionElem.val(),
                    success: function(result) {
                        if(result)
                        {
                            if(discountCodeElem)
                            {
                                if(discountCodeElem.val() != '')
                                {
                                    var shippingPrice = result;

                                    $.ajax({
                                        url: '{{ action('Frontend\OrderController@calculateDiscountShippingPrice') }}',
                                        type: 'get',
                                        data: 'discount_code=' + discountCodeElem.val() + '&shipping_price=' + shippingPrice + '<?php echo !empty($order->discount) ? '&edit=1': '' ;?>',
                                        success: function(result) {
                                            if(result)
                                            {
                                                result = JSON.parse(result);

                                                if(result['status'] != 'error')
                                                {
                                                    if(result['discount'] > 0)
                                                    {
                                                        discountShippingPriceElem.val(formatNumber(result['discount'].toString(), '.'));
                                                        shippingPriceElem.val(formatNumber((parseInt(shippingPrice) - parseInt(result['discount'])).toString(), '.'));
                                                    }
                                                    else
                                                    {
                                                        discountShippingPriceElem.val('');
                                                        shippingPriceElem.val(formatNumber(shippingPrice, '.'));
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                discountShippingPriceElem.val('');
                                                shippingPriceElem.val(formatNumber(shippingPrice, '.'));
                                            }

                                            calculateTotalCodPrice($('#OrderCodPriceInput'), $('#OrderTotalCodPriceInput'), $('#OrderShippingPriceInput'), $('input[type="radio"][name="shipping_payment"]:checked').val());
                                        }
                                    });
                                }
                                else
                                {
                                    shippingPriceElem.val(formatNumber(result, '.'));

                                    calculateTotalCodPrice($('#OrderCodPriceInput'), $('#OrderTotalCodPriceInput'), $('#OrderShippingPriceInput'), $('input[type="radio"][name="shipping_payment"]:checked').val());
                                }
                            }
                            else
                            {
                                if(discountShippingPriceElem.val() != '')
                                    shippingPriceElem.val(formatNumber((parseInt(result) - parseInt(discountShippingPriceElem.val().split('.').join(''))).toString(), '.'));
                                else
                                    shippingPriceElem.val(formatNumber(result, '.'));

                                calculateTotalCodPrice($('#OrderCodPriceInput'), $('#OrderTotalCodPriceInput'), $('#OrderShippingPriceInput'), $('input[type="radio"][name="shipping_payment"]:checked').val());
                            }
                        }
                        else
                        {
                            shippingPriceElem.val('');

                            calculateTotalCodPrice($('#OrderCodPriceInput'), $('#OrderTotalCodPriceInput'), $('#OrderShippingPriceInput'), $('input[type="radio"][name="shipping_payment"]:checked').val());
                        }
                    }
                });
            }
            else
            {
                shippingPriceElem.val('');

                calculateTotalCodPrice($('#OrderCodPriceInput'), $('#OrderTotalCodPriceInput'), $('#OrderShippingPriceInput'), $('input[type="radio"][name="shipping_payment"]:checked').val());
            }
        }

        function calculateTotalCodPrice(codPriceElem, totalCodPriceElem, shippingPriceElem, shippingPaymentVal)
        {
            if(shippingPaymentVal == '{{ \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB }}')
                totalCodPriceElem.val(codPriceElem.val());
            else
            {
                if(codPriceElem.val() != '' && shippingPriceElem.val() != '')
                    totalCodPriceElem.val(formatNumber((parseInt(codPriceElem.val().split('.').join('')) + parseInt(shippingPriceElem.val().split('.').join(''))).toString(), '.'));
                else if(shippingPriceElem.val() != '')
                    totalCodPriceElem.val(shippingPriceElem.val());
                else
                    totalCodPriceElem.val(codPriceElem.val());
            }
        }

        function calculateDiscountShippingPrice(discountCodeElem, discountShippingPriceElem, shippingPriceElem)
        {
            if(discountCodeElem.val() != '' && shippingPriceElem.val() != '')
            {
                var shippingPrice;

                if(discountShippingPriceElem.val() != '')
                    shippingPrice = parseInt(shippingPriceElem.val().split('.').join('')) + parseInt(discountShippingPriceElem.val().split('.').join(''));
                else
                    shippingPrice = shippingPriceElem.val();

                $.ajax({
                    url: '{{ action('Frontend\OrderController@calculateDiscountShippingPrice') }}',
                    type: 'get',
                    data: 'discount_code=' + discountCodeElem.val() + '&shipping_price=' + shippingPrice + '<?php echo !empty($order->discount) ? '&edit=1': '' ;?>',
                    success: function(result) {
                        result = JSON.parse(result);

                        if(result['status'] == 'error')
                        {
                            swal({
                                title: result['message'],
                                type: 'error',
                                confirmButtonClass: 'btn-success'
                            });
                        }
                        else
                        {
                            if(result['discount'] > 0)
                                discountShippingPriceElem.val(formatNumber(result['discount'].toString(), '.'));
                            else
                                discountShippingPriceElem.val('');

                            calculateShippingPrice($('#ReceiverDistrict'), $('#OrderWeightInput'), $('#OrderDimensionInput'), $('#OrderShippingPriceInput'), null, discountShippingPriceElem);
                        }
                    }
                });
            }
            else
            {
                discountShippingPriceElem.val('');

                calculateShippingPrice($('#ReceiverDistrict'), $('#OrderWeightInput'), $('#OrderDimensionInput'), $('#OrderShippingPriceInput'), null, discountShippingPriceElem);
            }
        }
    </script>
@endpush