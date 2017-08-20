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
                                                <select name="user_address" class="form-control">
                                                    <?php
                                                    $userAddressId = old('user_address');
                                                    ?>

                                                    <option value=""></option>

                                                    @foreach($order->user->userAddresses as $userAddress)
                                                        @if((!empty($userAddressId) && $userAddressId == $userAddress->id) || (empty($userAddressId) && $userAddress->default == \App\Libraries\Helpers\Utility::ACTIVE_DB))
                                                            <option selected="selected" value="{{ $userAddress->id }}">{{ $userAddress->name . ', ' . $userAddress->phone . ', ' . $userAddress->address . ', ' . $userAddress->ward . ', ' . $userAddress->district . ', ' . $userAddress->province }}</option>
                                                        @else
                                                            <option value="{{ $userAddress->id }}">{{ $userAddress->name . ', ' . $userAddress->phone . ', ' . $userAddress->address . ', ' . $userAddress->ward . ', ' . $userAddress->district . ', ' . $userAddress->province }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Trọng lượng gói hàng (kg)</label>
                                                <input type="text" class="form-control OrderWeightInput" name="weight" value="{{ old('weight', $order->weight) }}" />
                                                @if($errors->has('weight'))
                                                    <span class="has-error">
                                                        <span class="help-block">* {{ $errors->first('weight') }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>Kích thước gói hàng (cm)</label>
                                                <input type="text" class="form-control OrderDimensionInput" name="dimension" value="{{ old('dimension', $order->dimension) }}" placeholder="Dài x Rộng x Cao" />
                                                @if($errors->has('dimension'))
                                                    <span class="has-error">
                                                        <span class="help-block">* {{ $errors->first('dimension') }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>Tiền thu hộ</label>
                                                <input type="text" class="form-control InputForNumber OrderCodPriceInput" name="cod_price" value="{{ old('cod_price', \App\Libraries\Helpers\Utility::formatNumber($order->cod_price)) }}" />
                                                @if($errors->has('cod_price'))
                                                    <span class="has-error">
                                                        <span class="help-block">* {{ $errors->first('cod_price') }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>Phí ship (tạm tính)</label>
                                                <input type="text" class="form-control OrderShippingPriceInput" name="shipping_price" value="{{ old('shipping_price', \App\Libraries\Helpers\Utility::formatNumber($order->shipping_price)) }}" readonly="readonly" />
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
                                                <input type="text" class="form-control OrderTotalCodPriceInput" name="total_cod_price" value="{{ old('total_cod_price', $order->total_cod_price) }}" readonly="readonly" />
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
                                                <select name="receiver_province" class="form-control ReceiverProvince" required="required">
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
                                                <select name="receiver_district" class="form-control ReceiverDistrict" required="required">
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
                                                <select name="receiver_ward" class="form-control ReceiverWard" required="required">
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
