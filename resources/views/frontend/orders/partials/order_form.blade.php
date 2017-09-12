<div class="row OrderFormDiv">
    <div class="col-lg-12">
        <h2 class="title_sub">ĐƠN HÀNG {{ $countOrder + 1 }}</h2>
    </div>
    <div class="col-lg-6">
        <p>
            <b>Thông tin lấy hàng</b>
            <button type="button" class="btn btnThem pull-right RemoveOrderButton">Xóa</button>
        </p>

        @if(count($userAddresses) > 0)

            <div class="form-group">
                <label>Chọn địa chỉ đã có</label>
                <select name="user_address[{{ $countOrder }}]" class="form-control RegisterUserAddress">
                    <option value="">Địa chỉ mới</option>

                    @foreach($userAddresses as $userAddress)
                        <option value="{{ $userAddress->id }}">{{ $userAddress->name . ', ' . $userAddress->phone . ', ' . $userAddress->address . ', ' . $userAddress->ward . ', ' . $userAddress->district . ', ' . $userAddress->province }}</option>
                    @endforeach
                </select>
            </div>

            <div class="NewUserAddressDiv">
                <div class="form-group">
                    <label>Họ tên (*)</label>
                    <input type="text" class="form-control" name="register_name[{{ $countOrder }}]" required="required" />
                </div>
                <div class="form-group">
                    <label>Số điện thoại (*)</label>
                    <input type="text" class="form-control" name="register_phone[{{ $countOrder }}]" required="required" />
                </div>
                <div class="form-group">
                    <label>Địa chỉ lấy hàng (*)</label>
                    <input type="text" class="form-control" name="register_address[{{ $countOrder }}]" required="required" />
                </div>
                <div class="form-group">
                    <label>Tỉnh / thành phố (*)</label>
                    <select name="register_province[{{ $countOrder }}]" class="form-control RegisterProvince" required="required">
                        <option value=""></option>

                        @foreach(\App\Models\Area::getProvinces() as $area)
                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Quận / huyện (*)</label>
                    <select name="register_district[{{ $countOrder }}]" class="form-control RegisterDistrict" required="required">
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Phường / xã (*)</label>
                    <select name="register_ward[{{ $countOrder }}]" class="form-control RegisterWard" required="required">
                        <option value=""></option>
                    </select>
                </div>
            </div>

        @else

            <div class="form-group">
                <label>Họ tên (*)</label>
                <input type="text" class="form-control" name="register_name[{{ $countOrder }}]" required="required" />
            </div>
            <div class="form-group">
                <label>Số điện thoại (*)</label>
                <input type="text" class="form-control" name="register_phone[{{ $countOrder }}]" required="required" />
            </div>
            <div class="form-group">
                <label>Địa chỉ lấy hàng (*)</label>
                <input type="text" class="form-control" name="register_address[{{ $countOrder }}]" required="required" />
            </div>
            <div class="form-group">
                <label>Tỉnh / thành phố (*)</label>
                <select name="register_province[{{ $countOrder }}]" class="form-control RegisterProvince" required="required">
                    <option value=""></option>

                    @foreach(\App\Models\Area::getProvinces() as $area)
                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Quận / huyện (*)</label>
                <select name="register_district[{{ $countOrder }}]" class="form-control RegisterDistrict" required="required">
                    <option value=""></option>
                </select>
            </div>
            <div class="form-group">
                <label>Phường / xã (*)</label>
                <select name="register_ward[{{ $countOrder }}]" class="form-control RegisterWard" required="required">
                    <option value=""></option>
                </select>
            </div>

        @endif

        <div class="form-group">
            <label>Mã đơn hàng</label>
            <input type="text" class="form-control OrderUserDoInput" name="user_do[{{ $countOrder }}]" />
        </div>
        <div class="form-group">
            <label>Trọng lượng gói hàng (kg)</label>
            <input type="text" class="form-control OrderWeightInput" name="weight[{{ $countOrder }}]" />
        </div>
        <div class="form-group">
            <label>Kích thước gói hàng (cm)</label>
            <input type="text" class="form-control OrderDimensionInput" name="dimension[{{ $countOrder }}]" placeholder="Dài x Rộng x Cao" />
        </div>
        <div class="form-group">
            <label>Tiền thu hộ</label>
            <input type="text" class="form-control InputForNumber OrderCodPriceInput" name="cod_price[{{ $countOrder }}]" />
        </div>
        <div class="form-group">
            <label>Mã giảm giá</label>
            <input type="text" class="form-control OrderDiscountCodeInput" name="discount_code[{{ $countOrder }}]" />
        </div>
        <div class="form-group">
            <label>Được giảm giá</label>
            <input type="text" class="form-control OrderDiscountShippingPriceInput" name="discount_shipping_price[{{ $countOrder }}]" readonly="readonly" />
        </div>
        <div class="form-group">
            <label>Phí ship (tạm tính)</label>
            <input type="text" class="form-control OrderShippingPriceInput" name="shipping_price[{{ $countOrder }}]" readonly="readonly" />
        </div>
        <div class="form-group">
            <div class="radio">
                <label>
                    <input type="radio" name="shipping_payment[{{ $countOrder }}]" value="{{ \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB }}" checked="checked" />
                    Shop trả
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="shipping_payment[{{ $countOrder }}]" value="{{ \App\Models\Order::SHIPPING_PAYMENT_RECEIVER_DB }}" />
                    Khách trả
                </label>
            </div>
        </div>
        <div class="form-group">
            <label>Tổng tiền thu hộ</label>
            <input type="text" class="form-control OrderTotalCodPriceInput" name="total_cod_price[{{ $countOrder }}]" readonly="readonly" />
        </div>

        @if(auth()->user() && auth()->user()->prepay == \App\Libraries\Helpers\Utility::ACTIVE_DB)
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="prepay[{{ $countOrder }}]" />
                        Ứng Trước Tiền Thu Hộ Bằng Chuyển Khoản
                    </label>
                </div>
            </div>
        @endif

        <div class="form-group">
            <label>Ghi chú</label>
            <textarea name="note[{{ $countOrder }}]" class="form-control"></textarea>
        </div>
    </div>
    <div class="col-lg-6">
        <p><b>Thông tin người nhận hàng</b></p>
        <div class="form-group">
            <label>Tên người nhận (*)</label>
            <input type="text" class="form-control" name="receiver_name[{{ $countOrder }}]" required="required" />
        </div>
        <div class="form-group">
            <label>Số điện thoại (*)</label>
            <input type="text" class="form-control" name="receiver_phone[{{ $countOrder }}]" required="required" />
        </div>
        <div class="form-group">
            <label>Địa chỉ giao hàng (*)</label>
            <input type="text" class="form-control" name="receiver_address[{{ $countOrder }}]" required="required" />
        </div>
        <div class="form-group">
            <label>Thành phố (*)</label>
            <select name="receiver_province[{{ $countOrder }}]" class="form-control ReceiverProvince" required="required">
                <option value=""></option>

                @foreach(\App\Models\Area::getProvinces(true) as $area)
                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Quận / huyện (*)</label>
            <select name="receiver_district[{{ $countOrder }}]" class="form-control ReceiverDistrict" required="required">
                <option value=""></option>
            </select>
        </div>
        <div class="form-group">
            <label>Phường / xã (*)</label>
            <select name="receiver_ward[{{ $countOrder }}]" class="form-control ReceiverWard" required="required">
                <option value=""></option>
            </select>
        </div>
    </div>
</div>