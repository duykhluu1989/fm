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
                            <div class="col-lg-12">
                                <form class="frm_donDH" action="{{ action('Frontend\OrderController@placeOrder') }}" method="POST" role="form">

                                    <div id="ListOrder">
                                        <?php
                                        $oldReceiverName = old('receiver_name');

                                        if(!is_array($oldReceiverName) || empty($oldReceiverName))
                                            $oldReceiverName = [0 => ''];

                                        $i = 0;
                                        ?>

                                        @foreach($oldReceiverName as $k => $v)
                                            <div class="row OrderFormDiv">
                                                @if($i > 0)
                                                    <div class="col-lg-12">
                                                        <h2 class="title_sub">ĐƠN HÀNG {{ $i + 1 }}</h2>
                                                    </div>
                                                @endif
                                                <div class="col-lg-6">
                                                    <p>
                                                        <b>Thông tin lấy hàng</b>
                                                        @if($i > 0)
                                                            <button type="button" class="btn btnThem pull-right RemoveOrderButton">Xóa</button>
                                                        @endif
                                                    </p>

                                                    @if(count($userAddresses) > 0)

                                                        <div class="form-group">
                                                            <label>Chọn địa chỉ đã có</label>
                                                            <select name="user_address[{{ $k }}]" class="form-control RegisterUserAddress">
                                                                <?php
                                                                $userAddressId = old('user_address.' . $k);
                                                                ?>

                                                                <option value="">Địa chỉ mới</option>

                                                                @foreach($userAddresses as $userAddress)
                                                                    @if($userAddressId == $userAddress->id)
                                                                        <option selected="selected" value="{{ $userAddress->id }}">{{ $userAddress->name . ', ' . $userAddress->phone . ', ' . $userAddress->address . ', ' . $userAddress->ward . ', ' . $userAddress->district . ', ' . $userAddress->province }}</option>
                                                                    @else
                                                                        <option value="{{ $userAddress->id }}">{{ $userAddress->name . ', ' . $userAddress->phone . ', ' . $userAddress->address . ', ' . $userAddress->ward . ', ' . $userAddress->district . ', ' . $userAddress->province }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="NewUserAddressDiv"<?php echo (!empty($userAddressId) ? ' style="display: none"' : ''); ?>>
                                                            <div class="form-group">
                                                                <label>Họ tên (*)</label>
                                                                <input type="text" class="form-control" name="register_name[{{ $k }}]" value="{{ old('register_name.' . $k) }}"<?php echo (empty($userAddressId) ? ' required="required"' : ''); ?> />
                                                                @if($errors->has('register_name.' . $k))
                                                                    <span class="has-error">
                                                                    <span class="help-block">* {{ $errors->first('register_name.' . $k) }}</span>
                                                                </span>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Số điện thoại (*)</label>
                                                                <input type="text" class="form-control" name="register_phone[{{ $k }}]" value="{{ old('register_phone.' . $k) }}"<?php echo (empty($userAddressId) ? ' required="required"' : ''); ?> />
                                                                @if($errors->has('register_phone.' . $k))
                                                                    <span class="has-error">
                                                                    <span class="help-block">* {{ $errors->first('register_phone.' . $k) }}</span>
                                                                </span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Địa chỉ lấy hàng (*)</label>
                                                                <input type="text" class="form-control" name="register_address[{{ $k }}]" value="{{ old('register_address.' . $k) }}"<?php echo (empty($userAddressId) ? ' required="required"' : ''); ?> />
                                                                @if($errors->has('register_address.' . $k))
                                                                    <span class="has-error">
                                                                    <span class="help-block">* {{ $errors->first('register_address.' . $k) }}</span>
                                                                </span>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Tỉnh / thành phố (*)</label>
                                                                <select name="register_province[{{ $k }}]" class="form-control RegisterProvince"<?php echo (empty($userAddressId) ? ' required="required"' : ''); ?>>
                                                                    <?php
                                                                    $province = old('register_province.' . $k);
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
                                                                @if($errors->has('register_province.' . $k))
                                                                    <span class="has-error">
                                                                    <span class="help-block">* {{ $errors->first('register_province.' . $k) }}</span>
                                                                </span>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Quận / huyện (*)</label>
                                                                <select name="register_district[{{ $k }}]" class="form-control RegisterDistrict"<?php echo (empty($userAddressId) ? ' required="required"' : ''); ?>>
                                                                    <?php
                                                                    $district = old('register_district.' . $k);
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
                                                                @if($errors->has('register_district.' . $k))
                                                                    <span class="has-error">
                                                                    <span class="help-block">* {{ $errors->first('register_district.' . $k) }}</span>
                                                                </span>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Phường / xã (*)</label>
                                                                <select name="register_ward[{{ $k }}]" class="form-control RegisterWard"<?php echo (empty($userAddressId) ? ' required="required"' : ''); ?>>
                                                                    <?php
                                                                    $ward = old('register_ward.' . $k);
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
                                                                @if($errors->has('register_ward.' . $k))
                                                                    <span class="has-error">
                                                                    <span class="help-block">* {{ $errors->first('register_ward.' . $k) }}</span>
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                    @else

                                                        <div class="form-group">
                                                            <label>Họ tên (*)</label>
                                                            <input type="text" class="form-control" name="register_name[{{ $k }}]" value="{{ old('register_name.' . $k) }}" required="required" />
                                                            @if($errors->has('register_name.' . $k))
                                                                <span class="has-error">
                                                                    <span class="help-block">* {{ $errors->first('register_name.' . $k) }}</span>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Số điện thoại (*)</label>
                                                            <input type="text" class="form-control" name="register_phone[{{ $k }}]" value="{{ old('register_phone.' . $k) }}" required="required" />
                                                            @if($errors->has('register_phone.' . $k))
                                                                <span class="has-error">
                                                                    <span class="help-block">* {{ $errors->first('register_phone.' . $k) }}</span>
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
                                                            <input type="text" class="form-control" name="register_address[{{ $k }}]" value="{{ old('register_address.' . $k) }}" required="required" />
                                                            @if($errors->has('register_address.' . $k))
                                                                <span class="has-error">
                                                                    <span class="help-block">* {{ $errors->first('register_address.' . $k) }}</span>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Tỉnh / thành phố (*)</label>
                                                            <select name="register_province[{{ $k }}]" class="form-control RegisterProvince" required="required">
                                                                <?php
                                                                $province = old('register_province.' . $k);
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
                                                            @if($errors->has('register_province.' . $k))
                                                                <span class="has-error">
                                                                    <span class="help-block">* {{ $errors->first('register_province.' . $k) }}</span>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Quận / huyện (*)</label>
                                                            <select name="register_district[{{ $k }}]" class="form-control RegisterDistrict" required="required">
                                                                <?php
                                                                $district = old('register_district.' . $k);
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
                                                            @if($errors->has('register_district.' . $k))
                                                                <span class="has-error">
                                                                    <span class="help-block">* {{ $errors->first('register_district.' . $k) }}</span>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Phường / xã (*)</label>
                                                            <select name="register_ward[{{ $k }}]" class="form-control RegisterWard" required="required">
                                                                <?php
                                                                $ward = old('register_ward.' . $k);
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
                                                            @if($errors->has('register_ward.' . $k))
                                                                <span class="has-error">
                                                                    <span class="help-block">* {{ $errors->first('register_ward.' . $k) }}</span>
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

                                                    <div class="form-group">
                                                        <label>Trọng lượng gói hàng (kg)</label>
                                                        <input type="text" class="form-control OrderWeightInput" name="weight[{{ $k }}]" value="{{ old('weight.' . $k) }}" />
                                                        @if($errors->has('weight.' . $k))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('weight.' . $k) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Kích thước gói hàng (cm)</label>
                                                        <input type="text" class="form-control OrderDimensionInput" name="dimension[{{ $k }}]" value="{{ old('dimension.' . $k) }}" placeholder="Dài x Rộng x Cao" />
                                                        @if($errors->has('dimension.' . $k))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('dimension.' . $k) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tiền thu hộ</label>
                                                        <input type="text" class="form-control InputForNumber OrderCodPriceInput" name="cod_price[{{ $k }}]" value="{{ old('cod_price.' . $k) }}" />
                                                        @if($errors->has('cod_price.' . $k))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('cod_price.' . $k) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Phí ship (tạm tính)</label>
                                                        <input type="text" class="form-control OrderShippingPriceInput" name="shipping_price[{{ $k }}]" value="{{ old('shipping_price.' . $k) }}" readonly="readonly" />
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                        $shippingPayment = old('shipping_payment.' . $k);
                                                        ?>

                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" name="shipping_payment[{{ $k }}]" value="{{ \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB }}"<?php echo ($shippingPayment == \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB ? ' checked="checked"' : ''); ?> />
                                                                Shop trả
                                                            </label>
                                                        </div>
                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" name="shipping_payment[{{ $k }}]" value="{{ \App\Models\Order::SHIPPING_PAYMENT_RECEIVER_DB }}"<?php echo ($shippingPayment == \App\Models\Order::SHIPPING_PAYMENT_RECEIVER_DB ? ' checked="checked"' : ''); ?> />
                                                                Khách trả
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tổng tiền thu hộ</label>
                                                        <input type="text" class="form-control OrderTotalCodPriceInput" name="total_cod_price[{{ $k }}]" value="{{ old('total_cod_price.' . $k) }}" readonly="readonly" />
                                                    </div>

                                                    @if(auth()->user() && auth()->user()->prepay == \App\Libraries\Helpers\Utility::ACTIVE_DB)
                                                        <div class="form-group">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="prepay[{{ $k }}]" <?php echo (old('prepay.' . $k) ? ' checked="checked"' : ''); ?> />
                                                                    Ứng Trước Tiền Thu Hộ Bằng Chuyển Khoản
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="form-group">
                                                        <label>Ghi chú</label>
                                                        <textarea name="note[{{ $k }}]" class="form-control">{{ old('note.' . $k) }}</textarea>
                                                        @if($errors->has('note.' . $k))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('note.' . $k) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <p><b>Thông tin người nhận hàng</b></p>
                                                    <div class="form-group">
                                                        <label>Tên người nhận (*)</label>
                                                        <input type="text" class="form-control" name="receiver_name[{{ $k }}]" value="{{ old('receiver_name.' . $k) }}" required="required" />
                                                        @if($errors->has('receiver_name.' . $k))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('receiver_name.' . $k) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Số điện thoại (*)</label>
                                                        <input type="text" class="form-control" name="receiver_phone[{{ $k }}]" value="{{ old('receiver_phone.' . $k) }}" required="required" />
                                                        @if($errors->has('receiver_phone.' . $k))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('receiver_phone.' . $k) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Địa chỉ giao hàng (*)</label>
                                                        <input type="text" class="form-control" name="receiver_address[{{ $k }}]" value="{{ old('receiver_address.' . $k) }}" required="required" />
                                                        @if($errors->has('receiver_address.' . $k))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('receiver_address.' . $k) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Thành phố (*)</label>
                                                        <select name="receiver_province[{{ $k }}]" class="form-control ReceiverProvince" required="required">
                                                            <?php
                                                            $province = old('receiver_province.' . $k);
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
                                                        @if($errors->has('receiver_province.' . $k))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('receiver_province.' . $k) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Quận / huyện (*)</label>
                                                        <select name="receiver_district[{{ $k }}]" class="form-control ReceiverDistrict" required="required">
                                                            <?php
                                                            $district = old('receiver_district.' . $k);
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
                                                        @if($errors->has('receiver_district.' . $k))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('receiver_district.' . $k) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Phường / xã (*)</label>
                                                        <select name="receiver_ward[{{ $k }}]" class="form-control ReceiverWard" required="required">
                                                            <?php
                                                            $ward = old('receiver_ward.' . $k);
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
                                                        @if($errors->has('receiver_ward.' . $k))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('receiver_ward.' . $k) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <?php
                                            $i ++;
                                            ?>
                                        @endforeach
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

        @if(auth()->guest())
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
        @endif

        var countOrder = {{ $k + 1 }};

        $('#AddMoreOrderButton').click(function() {
            $.ajax({
                url: '{{ action('Frontend\OrderController@getOrderForm') }}',
                type: 'get',
                data: 'count_order=' + countOrder,
                success: function(result) {
                    if(result)
                    {
                        $('#ListOrder').append(result);

                        countOrder ++;
                    }
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

                changeArea($(this), containerElem.find('.RegisterDistrict').first(), '{{ \App\Models\Area::TYPE_DISTRICT_DB }}', false);
                containerElem.find('.RegisterWard').first().html('<option value=""></option>');
            }
            else if($(this).hasClass('RegisterDistrict'))
            {
                containerElem = $(this).closest('.OrderFormDiv');

                changeArea($(this), containerElem.find('.RegisterWard').first(), '{{ \App\Models\Area::TYPE_WARD_DB }}', false);
            }
            else if($(this).hasClass('ReceiverProvince'))
            {
                containerElem = $(this).closest('.OrderFormDiv');

                changeArea($(this), containerElem.find('.ReceiverDistrict').first(), '{{ \App\Models\Area::TYPE_DISTRICT_DB }}', true);
                containerElem.find('.ReceiverWard').first().html('<option value=""></option>');
                containerElem.find('.OrderShippingPriceInput').first();

                calculateShippingPrice(containerElem.find('.ReceiverDistrict').first(), containerElem.find('.OrderWeightInput').first(), containerElem.find('.OrderDimensionInput').first(), containerElem.find('.OrderShippingPriceInput').first(), containerElem);
            }
            else if($(this).hasClass('ReceiverDistrict'))
            {
                containerElem = $(this).closest('.OrderFormDiv');

                changeArea($(this), containerElem.find('.ReceiverWard').first(), '{{ \App\Models\Area::TYPE_WARD_DB }}', true);

                calculateShippingPrice(containerElem.find('.ReceiverDistrict').first(), containerElem.find('.OrderWeightInput').first(), containerElem.find('.OrderDimensionInput').first(), containerElem.find('.OrderShippingPriceInput').first(), containerElem);
            }
            else if($(this).hasClass('RegisterUserAddress'))
            {
                containerElem = $(this).closest('.OrderFormDiv');

                if($(this).val() == '')
                {
                    containerElem.find('.NewUserAddressDiv').first().css('display', 'block');
                    containerElem.find('.NewUserAddressDiv').first().find('input').each(function() {
                        $(this).prop('required', 'required');
                    });
                    containerElem.find('.NewUserAddressDiv').first().find('select').each(function() {
                        $(this).prop('required', 'required');
                    });
                }
                else
                {
                    containerElem.find('.NewUserAddressDiv').first().css('display', 'none');
                    containerElem.find('.NewUserAddressDiv').first().find('input').each(function() {
                        $(this).removeAttr('required').val('');
                    });
                    containerElem.find('.NewUserAddressDiv').first().find('select').each(function() {
                        $(this).removeAttr('required').val('');
                    });
                }
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

                calculateTotalCodPrice(containerElem.find('.OrderCodPriceInput').first(), containerElem.find('.OrderTotalCodPriceInput').first(), containerElem.find('.OrderShippingPriceInput').first(), containerElem.find('input[type="radio"]:checked').first().val());
            }
            else if($(this).prop('type') == 'radio')
            {
                containerElem = $(this).closest('.OrderFormDiv');

                calculateTotalCodPrice(containerElem.find('.OrderCodPriceInput').first(), containerElem.find('.OrderTotalCodPriceInput').first(), containerElem.find('.OrderShippingPriceInput').first(), containerElem.find('input[type="radio"]:checked').first().val());
            }
        }).on('keyup', 'input', function() {
            if($(this).hasClass('OrderCodPriceInput'))
                $(this).val(formatNumber($(this).val(), '.'));
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

                        calculateTotalCodPrice(containerElem.find('.OrderCodPriceInput').first(), containerElem.find('.OrderTotalCodPriceInput').first(), containerElem.find('.OrderShippingPriceInput').first(), containerElem.find('input[type="radio"]:checked').first().val());
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

