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

                                    <div id="ListOrder">
                                        <?php
                                        $oldReceiverName = old('receiver_name');

                                        if(!empty($oldReceiverName))
                                            $countOrder = count($oldReceiverName);
                                        else
                                            $countOrder = 1;
                                        ?>

                                        @for($i = 0;$i < $countOrder;$i ++)
                                            <div class="OrderFormDiv">
                                                <p>
                                                    <b>Thông tin lấy hàng</b>
                                                    @if($i > 0)
                                                        <button type="button" class="btn btnThem pull-right RemoveOrderButton">Xóa</button>
                                                    @endif
                                                </p>

                                                @if(count($userAddresses) > 0)

                                                    <div class="form-group">
                                                        <label>Địa chỉ (*)</label>
                                                        <select name="user_address[]" class="form-control" required="required">
                                                            <?php
                                                            $userAddressId = old('user_address.' . $i);
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
                                                        <input type="text" class="form-control" name="register_name[]" value="{{ old('register_name.' . $i) }}" required="required" />
                                                        @if($errors->has('register_name.' . $i))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('register_name.' . $i) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Số điện thoại (*)</label>
                                                        <input type="text" class="form-control" name="register_phone[]" value="{{ old('register_phone.' . $i) }}" required="required" />
                                                        @if($errors->has('register_phone.' . $i))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('register_phone.' . $i) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>

                                                    @if(auth()->guest() && $i == 0)
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
                                                        <input type="text" class="form-control" name="register_address[]" value="{{ old('register_address.' . $i) }}" required="required" />
                                                        @if($errors->has('register_address.' . $i))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('register_address.' . $i) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tỉnh / thành phố (*)</label>
                                                        <select name="register_province[]" class="form-control RegisterProvince" required="required">
                                                            <?php
                                                            $province = old('register_province.' . $i);
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
                                                        @if($errors->has('register_province.' . $i))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('register_province.' . $i) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Quận / huyện (*)</label>
                                                        <select name="register_district[]" class="form-control RegisterDistrict" required="required">
                                                            <?php
                                                            $district = old('register_district.' . $i);
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
                                                        @if($errors->has('register_district.' . $i))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('register_district.' . $i) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Phường / xã (*)</label>
                                                        <select name="register_ward[]" class="form-control RegisterWard" required="required">
                                                            <?php
                                                            $ward = old('register_ward.' . $i);
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
                                                        @if($errors->has('register_ward.' . $i))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('register_ward.' . $i) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>

                                                    @if(auth()->guest() && $i == 0)
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
                                                <p><b>Thông tin người nhận hàng</b></p>
                                                <div class="form-group">
                                                    <label>Tên người nhận (*)</label>
                                                    <input type="text" class="form-control" name="receiver_name[]" value="{{ old('receiver_name.' . $i) }}" required="required" />
                                                    @if($errors->has('receiver_name.' . $i))
                                                        <span class="has-error">
                                                            <span class="help-block">* {{ $errors->first('receiver_name.' . $i) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label>Số điện thoại (*)</label>
                                                    <input type="text" class="form-control" name="receiver_phone[]" value="{{ old('receiver_phone.' . $i) }}" required="required" />
                                                    @if($errors->has('receiver_phone.' . $i))
                                                        <span class="has-error">
                                                            <span class="help-block">* {{ $errors->first('receiver_phone.' . $i) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label>Địa chỉ giao hàng (*)</label>
                                                    <input type="text" class="form-control" name="receiver_address[]" value="{{ old('receiver_address.' . $i) }}" required="required" />
                                                    @if($errors->has('receiver_address.' . $i))
                                                        <span class="has-error">
                                                            <span class="help-block">* {{ $errors->first('receiver_address.' . $i) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label>Thành phố (*)</label>
                                                    <select name="receiver_province[]" class="form-control ReceiverProvince" required="required">
                                                        <?php
                                                        $province = old('receiver_province.' . $i);
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
                                                    @if($errors->has('receiver_province.' . $i))
                                                        <span class="has-error">
                                                            <span class="help-block">* {{ $errors->first('receiver_province.' . $i) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label>Quận / huyện (*)</label>
                                                    <select name="receiver_district[]" class="form-control ReceiverDistrict" required="required">
                                                        <?php
                                                        $district = old('receiver_district.' . $i);
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
                                                    @if($errors->has('receiver_district.' . $i))
                                                        <span class="has-error">
                                                            <span class="help-block">* {{ $errors->first('receiver_district.' . $i) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label>Phường / xã (*)</label>
                                                    <select name="receiver_ward[]" class="form-control ReceiverWard" required="required">
                                                        <?php
                                                        $ward = old('receiver_ward.' . $i);
                                                        ?>

                                                        <option value=""></option>

                                                        @if($district)
                                                            @foreach(\App\Models\Area::getDistricts($province) as $area)
                                                                @if($ward && $ward == $area->id)
                                                                    <option selected="selected" value="{{ $area->id }}">{{ $area->name }}</option>
                                                                @else
                                                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @if($errors->has('receiver_ward.' . $i))
                                                        <span class="has-error">
                                                            <span class="help-block">* {{ $errors->first('receiver_ward.' . $i) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label>Trọng lượng gói hàng (kg)</label>
                                                    <input type="text" class="form-control OrderWeightInput" name="weight[]" value="{{ old('weight.' . $i) }}" />
                                                    @if($errors->has('weight.' . $i))
                                                        <span class="has-error">
                                                            <span class="help-block">* {{ $errors->first('weight.' . $i) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label>Kích thước gói hàng (cm)</label>
                                                    <input type="text" class="form-control OrderDimensionInput" name="dimension[]" value="{{ old('dimension.' . $i) }}" placeholder="Dài x Rộng x Cao" />
                                                    @if($errors->has('dimension.' . $i))
                                                        <span class="has-error">
                                                            <span class="help-block">* {{ $errors->first('dimension.' . $i) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label>Tiền thu hộ</label>
                                                    <input type="text" class="form-control InputForNumber OrderCodPriceInput" name="cod_price[]" value="{{ old('cod_price.' . $i) }}" />
                                                    @if($errors->has('cod_price.' . $i))
                                                        <span class="has-error">
                                                            <span class="help-block">* {{ $errors->first('cod_price.' . $i) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label>Phí ship (tạm tính)</label>
                                                    <input type="text" class="form-control OrderShippingPriceInput" name="shipping_price[]" value="{{ old('shipping_price.' . $i) }}" readonly="readonly" />
                                                </div>
                                                <div class="form-group">
                                                    <?php
                                                    $shippingPayment = old('shipping_payment.' . $i);
                                                    ?>

                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="shipping_payment[]" value="{{ \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB }}"<?php echo ($shippingPayment == \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB ? ' checked="checked"' : ''); ?> />
                                                            Shop trả
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="shipping_payment[]" value="{{ \App\Models\Order::SHIPPING_PAYMENT_RECEIVER_DB }}"<?php echo ($shippingPayment == \App\Models\Order::SHIPPING_PAYMENT_RECEIVER_DB ? ' checked="checked"' : ''); ?> />
                                                            Khách trả
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tổng tiền thu hộ</label>
                                                    <input type="text" class="form-control OrderTotalCodPriceInput" name="total_cod_price[]" value="{{ old('total_cod_price.' . $i) }}" readonly="readonly" />
                                                </div>
                                                <div class="form-group">
                                                    <label>Ghi chú</label>
                                                    <textarea name="note[]" class="form-control">{{ old('note.' . $i) }}</textarea>
                                                    @if($errors->has('note.' . $i))
                                                        <span class="has-error">
                                                            <span class="help-block">* {{ $errors->first('note.' . $i) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <hr />
                                            </div>

                                            <?php
                                            $i ++;
                                            ?>
                                        @endfor
                                    </div>

                                    <button type="submit" class="btn btnDangDH"><i class="fa fa-upload fa-lg" aria-hidden="true"></i> ĐĂNG ĐƠN HÀNG</button>
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <hr>
                                <a href="javascript:void(0)" id="AddMoreOrderButton" class="btn btnThemDH"><i class="fa fa-plus fa-lg" aria-hidden="true"></i> THÊM ĐƠN HÀNG</a>
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

        $('#AddMoreOrderButton').click(function() {
            $.ajax({
                url: '{{ action('Frontend\OrderController@getOrderForm') }}',
                type: 'get',
                success: function(result) {
                    if(result)
                        $('#ListOrder').append(result);
                }
            });
        });

        $('#ListOrder').on('click', 'button', function() {
            if($(this).hasClass('RemoveOrderButton'))
                $(this).closest('.OrderFormDiv').remove();
        }).on('change', 'select', function() {
            var containerElem;

            if($(this).hasClass('RegisterProvince'))
            {
                containerElem = $(this).closest('.OrderFormDiv');

                changeArea($(this), containerElem.find('.RegisterDistrict').first(), '{{ \App\Models\Area::TYPE_DISTRICT_DB }}');
                containerElem.find('.RegisterWard').first().html('<option value=""></option>');
            }
            else if($(this).hasClass('RegisterDistrict'))
            {
                containerElem = $(this).closest('.OrderFormDiv');

                changeArea($(this), containerElem.find('.RegisterWard').first(), '{{ \App\Models\Area::TYPE_WARD_DB }}');
            }
            else if($(this).hasClass('ReceiverProvince'))
            {
                containerElem = $(this).closest('.OrderFormDiv');

                changeArea($(this), containerElem.find('.ReceiverDistrict').first(), '{{ \App\Models\Area::TYPE_DISTRICT_DB }}');
                containerElem.find('.ReceiverWard').first().html('<option value=""></option>');
                containerElem.find('.OrderShippingPriceInput').first();

                calculateShippingPrice(containerElem.find('.ReceiverDistrict').first(), containerElem.find('.OrderWeightInput').first(), containerElem.find('.OrderDimensionInput').first(), containerElem.find('.OrderShippingPriceInput').first(), containerElem);
            }
            else if($(this).hasClass('ReceiverDistrict'))
            {
                containerElem = $(this).closest('.OrderFormDiv');

                changeArea($(this), containerElem.find('.ReceiverWard').first(), '{{ \App\Models\Area::TYPE_WARD_DB }}');

                calculateShippingPrice(containerElem.find('.ReceiverDistrict').first(), containerElem.find('.OrderWeightInput').first(), containerElem.find('.OrderDimensionInput').first(), containerElem.find('.OrderShippingPriceInput').first(), containerElem);
            }
        }).on('change', 'input', function() {
            var containerElem;

            if($(this).hasClass('OrderWeightInput'))
            {
                containerElem = $(this).closest('.OrderFormDiv');

                calculateShippingPrice(containerElem.find('.ReceiverDistrict').first(), containerElem.find('.OrderWeightInput').first(), containerElem.find('.OrderDimensionInput').first(), containerElem.find('.OrderShippingPriceInput').first(), containerElem);
            }
            else if($(this).hasClass('OrderDimensionInput'))
            {
                containerElem = $(this).closest('.OrderFormDiv');

                calculateShippingPrice(containerElem.find('.ReceiverDistrict').first(), containerElem.find('.OrderWeightInput').first(), containerElem.find('.OrderDimensionInput').first(), containerElem.find('.OrderShippingPriceInput').first(), containerElem);
            }
            else if($(this).hasClass('OrderCodPriceInput'))
            {
                containerElem = $(this).closest('.OrderFormDiv');

                calculateTotalCodPrice(containerElem.find('.OrderCodPriceInput').first(), containerElem.find('.OrderTotalCodPriceInput').first(), containerElem.find('.OrderShippingPriceInput').first(), containerElem.find('input[type="radio"][name="shipping_payment[]"]:checked').first().val());
            }
            else if($(this).prop('type') == 'radio')
            {
                containerElem = $(this).closest('.OrderFormDiv');

                calculateTotalCodPrice(containerElem.find('.OrderCodPriceInput').first(), containerElem.find('.OrderTotalCodPriceInput').first(), containerElem.find('.OrderShippingPriceInput').first(), containerElem.find('input[type="radio"][name="shipping_payment[]"]:checked').first().val());
            }
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

        function calculateShippingPrice(districtElem, weightElem, dimensionElem, shippingPriceElem, containerElem)
        {
            if(districtElem.val() != '')
            {
                $.ajax({
                    url: '{{ action('Frontend\OrderController@calculateShippingPrice') }}',
                    type: 'get',
                    data: 'register_district=' + districtElem.val() + '&weight=' + weightElem.val() + '&dimension=' + dimensionElem.val(),
                    success: function(result) {
                        if(result)
                            shippingPriceElem.val(formatNumber(result, '.'));
                        else
                            shippingPriceElem.val('');

                        calculateTotalCodPrice(containerElem.find('.OrderCodPriceInput').first(), containerElem.find('.OrderTotalCodPriceInput').first(), containerElem.find('.OrderShippingPriceInput').first(), containerElem.find('input[type="radio"][name="shipping_payment[]"]:checked').first().val());
                    }
                });
            }
            else
                shippingPriceElem.val('');
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
    </script>
@endpush

