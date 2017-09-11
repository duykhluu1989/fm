@extends('backend.layouts.main')

@section('page_heading', 'Sửa Đơn Hàng - ' . $order->do)

@section('section')

    <form action="{{ action('Backend\OrderController@editOrder', ['id' => $order->id]) }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ action('Backend\OrderController@detailOrder', ['id' => $order->id]) }}" class="btn btn-default">Quay Lại</a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>Thông Tin Lấy Hàng</h3>
                        <div class="form-group">
                            <label>Chọn Địa Chỉ Đã Có</label>
                            <select id="RegisterUserAddress" class="form-control">
                                <option value=""></option>

                                @foreach($order->user->userAddresses as $userAddress)
                                    <option data-name="{{ $userAddress->name }}" data-phone="{{ $userAddress->phone }}" data-address="{{ $userAddress->address }}" data-ward-id="{{ $userAddress->ward_id }}" data-district-id="{{ $userAddress->district_id }}" data-province-id="{{ $userAddress->province_id }}"
                                            value="{{ $userAddress->id }}">{{ $userAddress->name . ', ' . $userAddress->phone . ', ' . $userAddress->address . ', ' . $userAddress->ward . ', ' . $userAddress->district . ', ' . $userAddress->province }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group{{ $errors->has('register_name') ? ' has-error': '' }}">
                            <label>Họ Tên <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" name="register_name" id="RegisterName" value="{{ old('register_name', $order->senderAddress->name) }}" required="required" />
                            @if($errors->has('register_name'))
                                <span class="help-block">{{ $errors->first('register_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('register_phone') ? ' has-error': '' }}">
                            <label>Số Điện Thoại <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" name="register_phone" id="RegisterPhone" value="{{ old('register_phone', $order->senderAddress->phone) }}" required="required" />
                            @if($errors->has('register_phone'))
                                <span class="help-block">{{ $errors->first('register_phone') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('register_address') ? ' has-error': '' }}">
                            <label>Địa Chỉ Lấy Hàng <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" name="register_address" id="RegisterAddress" value="{{ old('register_address', $order->senderAddress->address) }}" required="required" />
                            @if($errors->has('register_address'))
                                <span class="help-block">{{ $errors->first('register_address') }}</span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('register_province') ? ' has-error': '' }}">
                            <label>Tỉnh / Thành Phố <i>(bắt buộc)</i></label>
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
                                <span class="help-block">{{ $errors->first('register_province') }}</span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('register_district') ? ' has-error': '' }}">
                            <label>Quận / Huyện <i>(bắt buộc)</i></label>
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
                                <span class="help-block">{{ $errors->first('register_district') }}</span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('register_ward') ? ' has-error': '' }}">
                            <label>Phường / Xã <i>(bắt buộc)</i></label>
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
                                <span class="help-block">{{ $errors->first('register_ward') }}</span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('weight') ? ' has-error': '' }}">
                            <label>Trọng Lượng Gói Hàng (kg)</label>
                            <input type="text" class="form-control" id="OrderWeightInput" name="weight" value="{{ old('weight', $order->weight) }}" />
                            @if($errors->has('weight'))
                                <span class="help-block">{{ $errors->first('weight') }}</span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('dimension') ? ' has-error': '' }}">
                            <label>Kích Thước Gói Hàng (cm)</label>
                            <input type="text" class="form-control" id="OrderDimensionInput" name="dimension" value="{{ old('dimension', $order->dimension) }}" placeholder="Dài x Rộng x Cao" />
                            @if($errors->has('dimension'))
                                <span class="help-block">{{ $errors->first('dimension') }}</span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('cod_price') ? ' has-error': '' }}">
                            <label>Tiền Thu Hộ</label>
                            <input type="text" class="form-control InputForNumber" id="OrderCodPriceInput" name="cod_price" value="{{ old('cod_price', \App\Libraries\Helpers\Utility::formatNumber($order->cod_price)) }}" />
                            @if($errors->has('cod_price'))
                                <span class="help-block">{{ $errors->first('cod_price') }}</span>
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
                            <label>Phí Ship</label>
                            <input type="text" class="form-control" id="OrderShippingPriceInput" name="shipping_price" value="{{ old('shipping_price', \App\Libraries\Helpers\Utility::formatNumber($order->shipping_price)) }}" readonly="readonly" />
                        </div>
                        <div class="form-group">
                            <?php
                            $shippingPayment = old('shipping_payment', $order->shipping_payment);
                            ?>

                            <div>
                                <label class="radio-inline">
                                    <input type="radio" name="shipping_payment" value="{{ \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB }}"<?php echo ($shippingPayment == \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB ? ' checked="checked"' : ''); ?> />
                                    Shop Trả
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="shipping_payment" value="{{ \App\Models\Order::SHIPPING_PAYMENT_RECEIVER_DB }}"<?php echo ($shippingPayment == \App\Models\Order::SHIPPING_PAYMENT_RECEIVER_DB ? ' checked="checked"' : ''); ?> />
                                    Khách Trả
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tổng Tiền Thu Hộ</label>
                            <input type="text" class="form-control" id="OrderTotalCodPriceInput" name="total_cod_price" value="{{ old('total_cod_price', \App\Libraries\Helpers\Utility::formatNumber($order->total_cod_price)) }}" readonly="readonly" />
                        </div>

                        @if($order->user->prepay == \App\Libraries\Helpers\Utility::ACTIVE_DB)
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="prepay" <?php echo (old('prepay', $order->prepay) ? ' checked="checked"' : ''); ?> />
                                        Ứng Trước Tiền Thu Hộ Bằng Chuyển Khoản
                                    </label>
                                </div>
                            </div>
                        @endif

                        <div class="form-group{{ $errors->has('note') ? ' has-error': '' }}">
                            <label>Ghi Chú</label>
                            <textarea name="note" class="form-control">{{ old('note', $order->note) }}</textarea>
                            @if($errors->has('note'))
                                <span class="help-block">{{ $errors->first('note') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <h3>Thông Tin Người Nhận Hàng</h3>
                        <div class="form-group{{ $errors->has('receiver_name') ? ' has-error': '' }}">
                            <label>Tên Người Nhận <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" name="receiver_name" value="{{ old('receiver_name', $order->receiverAddress->name) }}" required="required" />
                            @if($errors->has('receiver_name'))
                                <span class="help-block">{{ $errors->first('receiver_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('receiver_phone') ? ' has-error': '' }}">
                            <label>Số Điện Thoại <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" name="receiver_phone" value="{{ old('receiver_phone', $order->receiverAddress->phone) }}" required="required" />
                            @if($errors->has('receiver_phone'))
                                <span class="help-block">{{ $errors->first('receiver_phone') }}</span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('receiver_address') ? ' has-error': '' }}">
                            <label>Địa Chỉ Giao Hàng <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" name="receiver_address" value="{{ old('receiver_address', $order->receiverAddress->address) }}" required="required" />
                            @if($errors->has('receiver_address'))
                                <span class="help-block">{{ $errors->first('receiver_address') }}</span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('receiver_province') ? ' has-error': '' }}">
                            <label>Thành Phố <i>(bắt buộc)</i></label>
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
                                <span class="help-block">{{ $errors->first('receiver_province') }}</span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('receiver_district') ? ' has-error': '' }}">
                            <label>Quận / Huyện <i>(bắt buộc)</i></label>
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
                                <span class="help-block">{{ $errors->first('receiver_district') }}</span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('receiver_ward') ? ' has-error': '' }}">
                            <label>Phường / Xã <i>(bắt buộc)</i></label>
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
                                <span class="help-block">{{ $errors->first('receiver_ward') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ action('Backend\OrderController@detailOrder', ['id' => $order->id]) }}" class="btn btn-default">Quay Lại</a>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

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
                url: '{{ action('Backend\UserController@getListArea') }}',
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
                            url: '{{ action('Backend\UserController@getListArea') }}',
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
                    url: '{{ action('Backend\UserController@getListArea') }}',
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
                    url: '{{ action('Backend\OrderController@calculateShippingPrice') }}',
                    type: 'get',
                    data: 'register_district=' + districtElem.val() + '&weight=' + weightElem.val() + '&dimension=' + dimensionElem.val() + '&user_id=<?php echo $order->user_id; ?>',
                    success: function(result) {
                        if(result)
                        {
                            if(discountCodeElem)
                            {
                                if(discountCodeElem.val() != '')
                                {
                                    var shippingPrice = result;

                                    $.ajax({
                                        url: '{{ action('Backend\OrderController@calculateDiscountShippingPrice') }}',
                                        type: 'get',
                                        data: 'discount_code=' + discountCodeElem.val() + '&shipping_price=' + shippingPrice + '&user_id=<?php echo $order->user_id; ?>' + '<?php echo !empty($order->discount) ? '&edit=1': '' ;?>',
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
                    url: '{{ action('Backend\OrderController@calculateDiscountShippingPrice') }}',
                    type: 'get',
                    data: 'discount_code=' + discountCodeElem.val() + '&shipping_price=' + shippingPrice + '&user_id=<?php echo $order->user_id; ?>' + '<?php echo !empty($order->discount) ? '&edit=1': '' ;?>',
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
