<tr class="UserAddressRow">
    <td>
        <input type="text" class="form-control" name="new_user_address_name[{{ $countUserAddress }}]" required="required" />
    </td>
    <td>
        <input type="text" class="form-control" name="new_user_address_phone[{{ $countUserAddress }}]" required="required" />
    </td>
    <td>
        <input type="text" class="form-control" name="new_user_address_address[{{ $countUserAddress }}]" required="required" />
    </td>
    <td>
        <select name="new_user_address_province[{{ $countUserAddress }}]" class="form-control UserAddressProvince" required="required">
            <option value=""></option>

            @foreach(\App\Models\Area::getProvinces() as $area)
                <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <select name="new_user_address_district[{{ $countUserAddress }}]" class="form-control UserAddressDistrict" required="required">
            <option value=""></option>
        </select>
    </td>
    <td>
        <select name="new_user_address_ward[{{ $countUserAddress }}]" class="form-control UserAddressWard" required="required">
            <option value=""></option>
        </select>
    </td>
    <td class="text-center">
        <button type="button" class="btn btn-default RemoveUserAddressButton"><i class="fa fa-trash-o fa-fw"></i></button>
    </td>
</tr>