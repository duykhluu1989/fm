<div class="UserAddressDiv">
    <h4 class="title_user line-on-right">Địa chỉ lấy hàng</h4>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <button type="button" class="btn btnThem pull-right RemoveUserAddressButton">Xóa</button>
            </div>
            <div class="form-group">
                <label>Tên người liên hệ (*)</label>
                <input type="text" class="form-control" name="new_user_address_name[{{ $countUserAddress }}]" required="required" />
            </div>
            <div class="form-group">
                <label>Số điện thoại (*)</label>
                <input type="text" class="form-control" name="new_user_address_phone[{{ $countUserAddress }}]" required="required" />
            </div>
            <div class="form-group">
                <label>Địa chỉ lấy hàng (*)</label>
                <input type="text" class="form-control" name="new_user_address_address[{{ $countUserAddress }}]" required="required" />
            </div>
            <div class="form-group">
                <label>Thành phố (*)</label>
                <select name="new_user_address_province[{{ $countUserAddress }}]" class="form-control UserAddressProvince" required="required">
                    <option value=""></option>

                    @foreach(\App\Models\Area::getProvinces() as $area)
                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Quận huyện (*)</label>
                <select name="new_user_address_district[{{ $countUserAddress }}]" class="form-control UserAddressDistrict" required="required">
                    <option value=""></option>
                </select>
            </div>
            <div class="form-group">
                <label>Phường / xã (*)</label>
                <select name="new_user_address_ward[{{ $countUserAddress }}]" class="form-control UserAddressWard" required="required">
                    <option value=""></option>
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
    </div>
</div>