@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Thành Viên - ' . $user->username)

@section('section')

    <form action="{{ action('Backend\UserController@editUser', ['id' => $user->id]) }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\UserController@adminUser')) }}" class="btn btn-default">Quay Lại</a>

                <button type="button" class="btn btn-primary" id="AttachmentFileListButton">Attachment File</button>

                <button type="button" class="btn btn-primary" id="UploadPlaceOrderButton">Tạo Đơn Hàng Bằng Excel</button>

                <a class="btn btn-primary pull-right" href="{{ action('Frontend\OrderController@importExcelPlaceOrderTemplate') }}" target="_blank">Tải File Excel Mẫu</a>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><b>Thông Tin Tài Khoản</b></a></li>
                        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><b>Danh Sách Địa Chỉ</b></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group{{ $errors->has('username') ? ' has-error': '' }}">
                                        <label>Tên Tài Khoản <i>(bắt buộc)</i></label>
                                        <input type="text" class="form-control" name="username" required="required" value="{{ old('username', $user->username) }}" />
                                        @if($errors->has('username'))
                                            <span class="help-block">{{ $errors->first('username') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                                        <label>Tên <i>(bắt buộc)</i></label>
                                        <input type="text" class="form-control" name="name" required="required" value="{{ old('name', $user->name) }}" />
                                        @if($errors->has('name'))
                                            <span class="help-block">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group{{ $errors->has('phone') ? ' has-error': '' }}">
                                        <label>Số Điện Thoại</label>
                                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}" />
                                        @if($errors->has('phone'))
                                            <span class="help-block">{{ $errors->first('phone') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group{{ $errors->has('email') ? ' has-error': '' }}">
                                        <label>Email <i>(bắt buộc)</i></label>
                                        <input type="email" class="form-control" name="email" required="required" value="{{ old('email', $user->email) }}" />
                                        @if($errors->has('email'))
                                            <span class="help-block">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Thời Gian Khởi Tạo</label>
                                        <span class="form-control no-border">{{ $user->created_at }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Tổng Số Đơn Hàng</label>
                                        <span class="form-control no-border">{{ !empty($user->customerInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->customerInformation->order_count) : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Đơn Hàng Hoàn Thành</label>
                                        <span class="form-control no-border">{{ !empty($user->customerInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->customerInformation->complete_order_count) : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Đơn Hàng Không Giao Được</label>
                                        <span class="form-control no-border">{{ !empty($user->customerInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->customerInformation->fail_order_count) : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Đơn Hàng Hủy</label>
                                        <span class="form-control no-border">{{ !empty($user->customerInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->customerInformation->cancel_order_count) : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Tổng Khối Lượng</label>
                                        <span class="form-control no-border">{{ !empty($user->customerInformation) ? $user->customerInformation->total_weight : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Tổng Thu Hộ</label>
                                        <span class="form-control no-border">{{ !empty($user->customerInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->customerInformation->total_cod_price) : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Tổng Phí Ship</label>
                                        <span class="form-control no-border">{{ !empty($user->customerInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->customerInformation->shipping_price) : '' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Ngân hàng</label>
                                        <input type="text" class="form-control" name="bank" value="{{ old('bank', $user->bank) }}" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Chủ Tài Khoản</label>
                                        <input type="text" class="form-control" name="bank_holder" value="{{ old('bank_holder', $user->bank_holder) }}" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group{{ $errors->has('bank_number') ? ' has-error': '' }}">
                                        <label>Số Tài Khoản</label>
                                        <input type="text" class="form-control" name="bank_number" value="{{ old('bank_number', $user->bank_number) }}" />
                                        @if($errors->has('bank_number'))
                                            <span class="help-block">{{ $errors->first('bank_number') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Chi Nhánh</label>
                                        <input type="text" class="form-control" name="bank_branch" value="{{ old('bank_branch', $user->bank_branch) }}" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group{{ $errors->has('api_key') ? ' has-error': '' }}">
                                        <label>Api Key</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="ApiKeyInput" name="api_key" value="{{ old('api_key', $user->api_key) }}" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary" id="GenerateApiKeyButton">Tạo Api Key</button>
                                            </span>
                                        </div>
                                        @if($errors->has('api_key'))
                                            <span class="help-block">{{ $errors->first('api_key') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Group</label>
                                        <input type="text" class="form-control" name="group" value="{{ old('group', $user->group) }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Trạng Thái</label>
                                        <?php
                                        $status = old('status', $user->status);
                                        ?>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="status" value="{{ \App\Libraries\Helpers\Utility::ACTIVE_DB }}"<?php echo ($status == \App\Libraries\Helpers\Utility::ACTIVE_DB ? ' checked="checked"' : ''); ?> data-toggle="toggle" data-on="{{ \App\Libraries\Helpers\Utility::TRUE_LABEL }}" data-off="{{ \App\Libraries\Helpers\Utility::FALSE_LABEL }}" data-onstyle="success" data-offstyle="danger" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Quản Trị Viên</label>
                                        <?php
                                        $admin = old('admin', $user->admin);
                                        ?>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="admin" value="{{ \App\Libraries\Helpers\Utility::ACTIVE_DB }}"<?php echo ($admin == \App\Libraries\Helpers\Utility::ACTIVE_DB ? ' checked="checked"' : ''); ?> data-toggle="toggle" data-on="{{ \App\Libraries\Helpers\Utility::TRUE_LABEL }}" data-off="{{ \App\Libraries\Helpers\Utility::FALSE_LABEL }}" data-onstyle="success" data-offstyle="danger" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group{{ $errors->has('password') ? ' has-error': '' }}">
                                        <label>Mật Khẩu Mới</label>
                                        <input type="password" class="form-control" name="password" value="{{ old('password') }}" />
                                        @if($errors->has('password'))
                                            <span class="help-block">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group{{ $errors->has('re_password') ? ' has-error': '' }}">
                                        <label>Xác Nhận Mật Khẩu Mới</label>
                                        <input type="password" class="form-control" name="re_password" value="{{ old('re_password') }}" />
                                        @if($errors->has('re_password'))
                                            <span class="help-block">{{ $errors->first('re_password') }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($user->admin == \App\Libraries\Helpers\Utility::ACTIVE_DB)
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Vai Trò</label>
                                            <?php
                                            $assignedRoles = array();
                                            foreach($user->userRoles as $userRole)
                                                $assignedRoles[] = $userRole->role_id;
                                            $assignedRoles = old('roles', $assignedRoles);
                                            ?>
                                            <div class="row">
                                                @foreach($roles as $id => $name)
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="roles[]" type="checkbox" value="{{ $id }}"<?php echo (in_array($id, $assignedRoles) ? ' checked="checked"' : ''); ?> />{{ $name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_2">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="no-padding">
                                            <table class="table table-bordered table-striped table-hover table-condensed">
                                                <thead>
                                                <tr>
                                                    <th>Tên</th>
                                                    <th>Số Điện Thoại</th>
                                                    <th>Địa Chỉ</th>
                                                    <th>Tỉnh / Thành Phố</th>
                                                    <th>Quận / Huyện</th>
                                                    <th>Phường / Xã</th>
                                                    <th class="col-sm-1 text-center">
                                                        <button type="button" class="btn btn-primary" id="NewUserAddressButton" data-container="body" data-toggle="popover" data-placement="top" data-content="Thêm Mới"><i class="fa fa-plus fa-fw"></i></button>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody id="ListUserAddress">
                                                <?php
                                                $i = 0;
                                                ?>
                                                @foreach($user->userAddresses as $userAddress)
                                                    <tr class="UserAddressRow">
                                                        <td>
                                                            <input type="text" class="form-control" name="user_address_name[{{ $userAddress->id }}]" value="{{ old('user_address_name.' . $userAddress->id, $userAddress->name) }}" required="required" />
                                                            @if($errors->has('user_address_name.' . $userAddress->id))
                                                                <span class="has-error">
                                                                    <span class="help-block">* {{ $errors->first('user_address_name.' . $userAddress->id) }}</span>
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="user_address_phone[{{ $userAddress->id }}]" value="{{ old('user_address_phone.' . $userAddress->id, $userAddress->phone) }}" required="required" />
                                                            @if($errors->has('user_address_phone.' . $userAddress->id))
                                                                <span class="has-error">
                                                                    <span class="help-block">* {{ $errors->first('user_address_phone.' . $userAddress->id) }}</span>
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="user_address_address[{{ $userAddress->id }}]" value="{{ old('user_address_address.' . $userAddress->id, $userAddress->address) }}" required="required" />
                                                            @if($errors->has('user_address_address.' . $userAddress->id))
                                                                <span class="has-error">
                                                                    <span class="help-block">* {{ $errors->first('user_address_address.' . $userAddress->id) }}</span>
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
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
                                                        </td>
                                                        <td>
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
                                                        </td>
                                                        <td>
                                                            <select name="user_address_ward[{{ $userAddress->id }}]" class="form-control UserAddressWard">
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
                                                        </td>
                                                        <td class="text-center">
                                                            @if($i > 0)
                                                                <button type="button" class="btn btn-default RemoveUserAddressButton"><i class="fa fa-trash-o fa-fw"></i></button>
                                                            @endif
                                                        </td>
                                                    </tr>

                                                    <?php
                                                    $i ++;
                                                    ?>
                                                @endforeach

                                                <?php
                                                $newUserAddressName = old('new_user_address_name');
                                                ?>
                                                @if(is_array($newUserAddressName) && !empty($newUserAddressName))
                                                    @foreach($newUserAddressName as $k => $v)
                                                        <tr class="UserAddressRow">
                                                            <td>
                                                                <input type="text" class="form-control" name="new_user_address_name[{{ $k }}]" value="{{ old('new_user_address_name.' . $k) }}" required="required" />
                                                                @if($errors->has('new_user_address_name.' . $k))
                                                                    <span class="has-error">
                                                                        <span class="help-block">* {{ $errors->first('new_user_address_name.' . $k) }}</span>
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="new_user_address_phone[{{ $k }}]" value="{{ old('new_user_address_phone.' . $k) }}" required="required" />
                                                                @if($errors->has('new_user_address_phone.' . $k))
                                                                    <span class="has-error">
                                                                        <span class="help-block">* {{ $errors->first('new_user_address_phone.' . $k) }}</span>
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="new_user_address_address[{{ $k }}]" value="{{ old('new_user_address_address.' . $k) }}" required="required" />
                                                                @if($errors->has('new_user_address_address.' . $k))
                                                                    <span class="has-error">
                                                                        <span class="help-block">* {{ $errors->first('new_user_address_address.' . $k) }}</span>
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
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
                                                            </td>
                                                            <td>
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
                                                            </td>
                                                            <td>
                                                                <select name="new_user_address_ward[{{ $k }}]" class="form-control UserAddressWard">
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
                                                            </td>
                                                            <td class="text-center">
                                                                @if($i > 0)
                                                                    <button type="button" class="btn btn-default RemoveUserAddressButton"><i class="fa fa-trash-o fa-fw"></i></button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <?php
                                                    $k = 0;
                                                    ?>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\UserController@adminUser')) }}" class="btn btn-default">Quay Lại</a>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

    <div class="modal fade" tabindex="-1" role="dialog" id="UploadPlaceOrderModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content box">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Tạo Đơn Hàng Bằng Excel</h4>
                </div>
                <form action="{{ action('Backend\UserController@importExcelPlaceOrder', ['id' => $user->id]) }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>File Excel <i>(bắt buộc)</i></label>
                            <input type="file" name="file" required="required" accept="{{ implode(', ', \App\Libraries\Helpers\Utility::getValidExcelExt(true)) }}" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Xác Nhận</button>
                    </div>
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>

@stop

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-toggle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/colorbox.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.colorbox-min.js') }}"></script>
    <script type="text/javascript">
        $('#GenerateApiKeyButton').click(function() {
            $.ajax({
                url: '{{ action('Backend\UserController@generateApiKey') }}',
                type: 'get',
                success: function(result) {
                    if(result)
                        $('#ApiKeyInput').val(result);
                    else
                        alert('Tạo Api Key Thất Bại');
                }
            });
        });

        $('#UploadPlaceOrderButton').click(function() {
            $('#UploadPlaceOrderModal').modal('show');
        });

        $('#AttachmentFileListButton').click(function() {
            $.colorbox({
                href: '{{ action('Backend\ElFinderController@popupUserAttachment', ['id' => $user->id]) }}',
                iframe: true,
                width: '1200',
                height: '600',
                closeButton: false
            });
        });

        var countUserAddress = {{ $k + 1 }};

        $('#NewUserAddressButton').click(function() {
            $.ajax({
                url: '{{ action('Backend\UserController@getUserAddressForm') }}',
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
                $(this).closest('.UserAddressRow').remove();
        }).on('change', 'select', function() {
            var containerElem;

            if($(this).hasClass('UserAddressProvince'))
            {
                containerElem = $(this).closest('.UserAddressRow');

                changeArea($(this), containerElem.find('.UserAddressDistrict').first(), '{{ \App\Models\Area::TYPE_DISTRICT_DB }}');
                containerElem.find('.UserAddressWard').first().html('<option value=""></option>');
            }
            else if($(this).hasClass('UserAddressDistrict'))
            {
                containerElem = $(this).closest('.UserAddressRow');

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
                    url: '{{ action('Backend\UserController@getListArea') }}',
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
    </script>
@endpush