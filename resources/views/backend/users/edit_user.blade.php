@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Thành Viên - ' . $user->username)

@section('section')

    <form action="{{ action('Backend\UserController@editUser', ['id' => $user->id]) }}" method="post" enctype="multipart/form-data">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\UserController@adminUser')) }}" class="btn btn-default">Quay Lại</a>
            </div>
            <div class="box-body">
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
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\UserController@adminUser')) }}" class="btn btn-default">Quay Lại</a>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

@stop

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-toggle.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/bootstrap-toggle.min.js') }}"></script>
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
    </script>
@endpush