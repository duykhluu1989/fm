@extends('frontend.layouts.main')

@section('page_heading', 'Cập nhật thông tin tài khoản')

@section('section')

    @include('frontend.layouts.partials.menu')

    <main>

        @include('frontend.users.partials.navigation')

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="title_sub">CẬP NHẬT THÔNG TIN TÀI KHOẢN</h2>
                        <form action="{{ action('Frontend\UserController@editAccount') }}" method="POST" role="form">
                            <h4 class="title_user line-on-right">Thông tin cơ bản</h4>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Tên (*)</label>
                                        <input type="text" class="form-control" name="register_name" value="{{ old('register_name', $user->name) }}" required="required" />
                                        @if($errors->has('register_name'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('register_name') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Điện thoại (*)</label>
                                        <input type="text" class="form-control" name="register_phone" value="{{ old('register_phone', $user->phone) }}" required="required" />
                                        @if($errors->has('register_phone'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('register_phone') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Email (*)</label>
                                        <input type="text" class="form-control" name="register_email" value="{{ old('register_email', $user->email) }}" required="required" />
                                        @if($errors->has('register_email'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('register_email') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Mật khẩu mới</label>
                                        <input type="password" class="form-control" name="register_password" />
                                        @if($errors->has('register_password'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('register_password') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Xác nhận mật khẩu mới</label>
                                        <input type="password" class="form-control" name="confirm_password" />
                                    </div>
                                    <div class="form-group">
                                        <label>Chủ tài khoản ngân hàng</label>
                                        <input type="text" class="form-control" name="register_bank_holder" value="{{ old('register_bank_holder', $user->bank_holder) }}" />
                                        @if($errors->has('register_bank_holder'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('register_bank_holder') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Số tài khoản ngân hàng</label>
                                        <input type="text" class="form-control" name="register_bank_number" value="{{ old('register_bank_number', $user->bank_number) }}" />
                                        @if($errors->has('register_bank_number'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('register_bank_number') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Tên ngân hàng</label>
                                        <input type="text" class="form-control" name="register_bank" value="{{ old('register_bank', $user->bank) }}" />
                                        @if($errors->has('register_bank'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('register_bank') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Chi nhánh ngân hàng</label>
                                        <input type="text" class="form-control" name="register_bank_branch" value="{{ old('register_bank_branch', $user->bank_branch) }}" />
                                        @if($errors->has('register_bank_branch'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('register_bank_branch') }}</span>
                                            </span>
                                        @endif
                                    </div>

                                    @if($user->prepay == \App\Libraries\Helpers\Utility::INACTIVE_DB)
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="RegisterPrepayServiceCheckbox" name="register_prepay_service"<?php echo (old('register_prepay_service') ? ' checked="checked"' : ''); ?> />
                                                    Dịch Vụ Ứng Trước Tiền Thu Hộ Bằng Chuyển Khoản
                                                </label>
                                            </div>
                                            @if($errors->has('register_prepay_service'))
                                                <span class="has-error">
                                                    <span class="help-block">* {{ $errors->first('register_prepay_service') }}</span>
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    <button type="submit" class="btn btnLuuTT"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i> LƯU THÔNG TIN</button>
                                    {{ csrf_field() }}

                                    @if(!empty($prepayPage))
                                        <div class="modal fade" tabindex="-1" role="dialog" id="RegisterPrepayServiceModal">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content box">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title">{{ $prepayPage->name }}</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php
                                                        $prepayPageContent = str_replace('{input}', '<input type="text" name="register_prepay_contract[]" />', $prepayPage->content);
                                                        echo $prepayPageContent;
                                                        ?>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Hoàn thành</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                            </div>

                            <div id="ListUserAddress">

                                <?php
                                $i = 0;
                                ?>
                                @foreach($user->userAddresses as $userAddress)
                                    <div class="UserAddressDiv">
                                        <h4 class="title_user line-on-right">Địa chỉ lấy hàng - {{ old('user_address_address.' . $userAddress->id, $userAddress->address) }}</h4>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                                                @if($i > 0)
                                                    <div class="form-group">
                                                        <button type="button" class="btn btnThem pull-right RemoveUserAddressButton">Xóa</button>
                                                    </div>
                                                @endif

                                                <div class="form-group">
                                                    <label>Tên người liên hệ (*)</label>
                                                    <input type="text" class="form-control" name="user_address_name[{{ $userAddress->id }}]" value="{{ old('user_address_name.' . $userAddress->id, $userAddress->name) }}" required="required" />
                                                    @if($errors->has('user_address_name.' . $userAddress->id))
                                                        <span class="has-error">
                                                            <span class="help-block">* {{ $errors->first('user_address_name.' . $userAddress->id) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label>Số điện thoại (*)</label>
                                                    <input type="text" class="form-control" name="user_address_phone[{{ $userAddress->id }}]" value="{{ old('user_address_phone.' . $userAddress->id, $userAddress->phone) }}" required="required" />
                                                    @if($errors->has('user_address_phone.' . $userAddress->id))
                                                        <span class="has-error">
                                                            <span class="help-block">* {{ $errors->first('user_address_phone.' . $userAddress->id) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label>Địa chỉ lấy hàng (*)</label>
                                                    <input type="text" class="form-control" name="user_address_address[{{ $userAddress->id }}]" value="{{ old('user_address_address.' . $userAddress->id, $userAddress->address) }}" required="required" />
                                                    @if($errors->has('user_address_address.' . $userAddress->id))
                                                        <span class="has-error">
                                                            <span class="help-block">* {{ $errors->first('user_address_address.' . $userAddress->id) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label>Thành phố (*)</label>
                                                    <select name="user_address_province[{{ $userAddress->id }}]" class="form-control UserAddressProvince" required="required">
                                                        <?php
                                                        $province = old('user_address_province.' . $userAddress->id, $userAddress->province_id);
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
                                                    @if($errors->has('user_address_province.' . $userAddress->id))
                                                        <span class="has-error">
                                                            <span class="help-block">* {{ $errors->first('user_address_province.' . $userAddress->id) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label>Quận huyện (*)</label>
                                                    <select name="user_address_district[{{ $userAddress->id }}]" class="form-control UserAddressDistrict" required="required">
                                                        <?php
                                                        $district = old('user_address_district.' . $userAddress->id, $userAddress->district_id);
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
                                                    @if($errors->has('user_address_district.' . $userAddress->id))
                                                        <span class="has-error">
                                                            <span class="help-block">* {{ $errors->first('user_address_district.' . $userAddress->id) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label>Phường / xã (*)</label>
                                                    <select name="user_address_ward[{{ $userAddress->id }}]" class="form-control UserAddressWard" required="required">
                                                        <?php
                                                        $ward = old('user_address_ward.' . $userAddress->id, $userAddress->ward_id);
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
                                                    @if($errors->has('user_address_ward.' . $userAddress->id))
                                                        <span class="has-error">
                                                            <span class="help-block">* {{ $errors->first('user_address_ward.' . $userAddress->id) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                                        </div>
                                    </div>

                                    <?php
                                    $i ++;
                                    ?>
                                @endforeach

                                <?php
                                $newUserAddressName = old('new_user_address_name');
                                ?>
                                @if(is_array($newUserAddressName) && !empty($newUserAddressName))
                                    @foreach($newUserAddressName as $k => $v)
                                        <div class="UserAddressDiv">
                                            <h4 class="title_user line-on-right">Địa chỉ lấy hàng - {{ old('new_user_address_address.' . $k) }}</h4>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                                                    @if($i > 0)
                                                        <div class="form-group">
                                                            <button type="button" class="btn btnThem pull-right RemoveUserAddressButton">Xóa</button>
                                                        </div>
                                                    @endif

                                                    <div class="form-group">
                                                        <label>Tên người liên hệ (*)</label>
                                                        <input type="text" class="form-control" name="new_user_address_name[{{ $k }}]" value="{{ old('new_user_address_name.' . $k) }}" required="required" />
                                                        @if($errors->has('new_user_address_name.' . $k))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('new_user_address_name.' . $k) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Số điện thoại (*)</label>
                                                        <input type="text" class="form-control" name="new_user_address_phone[{{ $k }}]" value="{{ old('new_user_address_phone.' . $k) }}" required="required" />
                                                        @if($errors->has('new_user_address_phone.' . $k))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('new_user_address_phone.' . $k) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Địa chỉ lấy hàng (*)</label>
                                                        <input type="text" class="form-control" name="new_user_address_address[{{ $k }}]" value="{{ old('new_user_address_address.' . $k) }}" required="required" />
                                                        @if($errors->has('new_user_address_address.' . $k))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('new_user_address_address.' . $k) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Thành phố (*)</label>
                                                        <select name="new_user_address_province[{{ $k }}]" class="form-control UserAddressProvince" required="required">
                                                            <?php
                                                            $province = old('new_user_address_province.' . $k);
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
                                                        @if($errors->has('new_user_address_province.' . $k))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('new_user_address_province.' . $k) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Quận huyện (*)</label>
                                                        <select name="new_user_address_district[{{ $k }}]" class="form-control UserAddressDistrict" required="required">
                                                            <?php
                                                            $district = old('new_user_address_district.' . $k);
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
                                                        @if($errors->has('new_user_address_district.' . $k))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('new_user_address_district.' . $k) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Phường / xã (*)</label>
                                                        <select name="new_user_address_ward[{{ $k }}]" class="form-control UserAddressWard" required="required">
                                                            <?php
                                                            $ward = old('new_user_address_ward.' . $k);
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
                                                        @if($errors->has('new_user_address_ward.' . $k))
                                                            <span class="has-error">
                                                                <span class="help-block">* {{ $errors->first('new_user_address_ward.' . $k) }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                                            </div>
                                        </div>

                                        <?php
                                        $i ++;
                                        ?>
                                    @endforeach
                                @endif

                            </div>

                            <a href="javascript:void(0)" id="AddMoreUserAddressButton" class="btn btnThemDD"><i class="fa fa-plus" aria-hidden="true"></i> THÊM ĐỊA ĐIỂM LẤY HÀNG - KHO HÀNG</a>
                            <button type="submit" class="btn btnLuuTT"><i class="fa fa-floppy-o" aria-hidden="true"></i> LƯU ĐỊA CHỈ</button>
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
        var countUserAddress = 1;

        $('#AddMoreUserAddressButton').click(function() {
            $.ajax({
                url: '{{ action('Frontend\UserController@getUserAddressForm') }}',
                type: 'get',
                data: 'count_user_address=' + countUserAddress,
                success: function(result) {
                    if(result)
                    {
                        $('#ListUserAddress').append(result);

                        countUserAddress ++;
                    }
                }
            });
        });

        $('#ListUserAddress').on('click', 'button', function() {
            if($(this).hasClass('RemoveUserAddressButton'))
                $(this).closest('.UserAddressDiv').remove();
        }).on('change', 'select', function() {
            var containerElem;

            if($(this).hasClass('UserAddressProvince'))
            {
                containerElem = $(this).closest('.UserAddressDiv');

                changeArea($(this), containerElem.find('.UserAddressDistrict').first(), '{{ \App\Models\Area::TYPE_DISTRICT_DB }}');
                containerElem.find('.UserAddressWard').first().html('<option value=""></option>');
            }
            else if($(this).hasClass('UserAddressDistrict'))
            {
                containerElem = $(this).closest('.UserAddressDiv');

                changeArea($(this), containerElem.find('.UserAddressWard').first(), '{{ \App\Models\Area::TYPE_WARD_DB }}');
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

        $('#RegisterPrepayServiceCheckbox').click(function() {
            if($(this).prop('checked'))
            {
                $('input[name="register_prepay_contract[]"]').each(function() {
                    $(this).prop('required', 'required');
                });

                $('#RegisterPrepayServiceModal').modal({
                    backdrop: 'static',
                    show: true
                });
            }
            else
            {
                $('input[name="register_prepay_contract[]"]').each(function() {
                    $(this).removeAttr('required');
                });
            }
        });
    </script>
@endpush
