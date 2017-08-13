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
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><b>Thông Tin Tài Khoản</b></a></li>
                        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><b>Thông Tin Thành Viên</b></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group{{ $errors->has('avatar') ? ' has-error': '' }}">
                                        <label>Ảnh Đại Diện <i>(200 x 200)</i></label>
                                        <input type="file" class="form-control" name="avatar" accept="{{ implode(', ', \App\Libraries\Helpers\Utility::getValidImageExt(true)) }}" />
                                        @if($errors->has('avatar'))
                                            <span class="help-block">{{ $errors->first('avatar') }}</span>
                                        @endif
                                        @if(!empty($user->avatar))
                                            <img src="{{ $user->avatar }}" width="150px" alt="User Avatar" />
                                        @endif
                                    </div>
                                </div>
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
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Số Lượng Khóa Học</label>
                                        <span class="form-control no-border">{{ !empty($user->studentInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->studentInformation->course_count) : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Số Lượng Khóa Học Đã Hoàn Thành</label>
                                        <span class="form-control no-border">{{ !empty($user->studentInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->studentInformation->finish_course_count) : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Tổng Chi Tiêu</label>
                                        <span class="form-control no-border">{{ !empty($user->studentInformation) ? (\App\Libraries\Helpers\Utility::formatNumber($user->studentInformation->total_spent) . ' VND') : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Điểm Hiện Tại</label>
                                        <span class="form-control no-border">{{ !empty($user->studentInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->studentInformation->current_point) : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Tổng Điểm</label>
                                        <span class="form-control no-border">{{ !empty($user->studentInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->studentInformation->total_point) : '' }}</span>
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
                                                <input type="checkbox" name="status" value="{{ \App\Libraries\Helpers\Utility::ACTIVE_DB }}"<?php echo ($status == \App\Libraries\Helpers\Utility::ACTIVE_DB ? ' checked="checked"' : ''); ?> data-toggle="toggle" data-on="{{ \App\Models\User::STATUS_ACTIVE_LABEL }}" data-off="{{ \App\Models\User::STATUS_INACTIVE_LABEL }}" data-onstyle="success" data-offstyle="danger" />
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
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Cộng Tác Viên</label>
                                        <?php
                                        $collaborator = old('collaborator', $user->collaborator);
                                        ?>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="collaborator" value="{{ \App\Libraries\Helpers\Utility::ACTIVE_DB }}"<?php echo ($collaborator == \App\Libraries\Helpers\Utility::ACTIVE_DB ? ' checked="checked"' : ''); ?> data-toggle="toggle" data-on="{{ \App\Libraries\Helpers\Utility::TRUE_LABEL }}" data-off="{{ \App\Libraries\Helpers\Utility::FALSE_LABEL }}" data-onstyle="success" data-offstyle="danger" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Giảng Viên</label>
                                        <?php
                                        $teacher = old('teacher', $user->teacher);
                                        ?>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="teacher" value="{{ \App\Libraries\Helpers\Utility::ACTIVE_DB }}"<?php echo ($teacher == \App\Libraries\Helpers\Utility::ACTIVE_DB ? ' checked="checked"' : ''); ?> data-toggle="toggle" data-on="{{ \App\Libraries\Helpers\Utility::TRUE_LABEL }}" data-off="{{ \App\Libraries\Helpers\Utility::FALSE_LABEL }}" data-onstyle="success" data-offstyle="danger" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Chuyên Gia</label>
                                        <?php
                                        $expert = old('expert', $user->expert);
                                        ?>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="expert" value="{{ \App\Libraries\Helpers\Utility::ACTIVE_DB }}"<?php echo ($expert == \App\Libraries\Helpers\Utility::ACTIVE_DB ? ' checked="checked"' : ''); ?> data-toggle="toggle" data-on="{{ \App\Libraries\Helpers\Utility::TRUE_LABEL }}" data-off="{{ \App\Libraries\Helpers\Utility::FALSE_LABEL }}" data-onstyle="success" data-offstyle="danger" />
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
                                    <div class="form-group{{ $errors->has('first_name') ? ' has-error': '' }}">
                                        <label>Tên <i>(bắt buộc)</i></label>
                                        <input type="text" class="form-control" name="first_name" required="required" value="{{ old('first_name', $user->profile->first_name) }}" />
                                        @if($errors->has('first_name'))
                                            <span class="help-block">{{ $errors->first('first_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group{{ $errors->has('last_name') ? ' has-error': '' }}">
                                        <label>Họ</label>
                                        <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $user->profile->last_name) }}" />
                                        @if($errors->has('last_name'))
                                            <span class="help-block">{{ $errors->first('last_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Chức Danh</label>
                                        <input type="text" class="form-control" name="title" value="{{ old('title', $user->profile->title) }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Giới Tính</label>
                                        <?php
                                        $gender = old('gender', $user->profile->gender);
                                        ?>
                                        <div>
                                            @foreach(\App\Models\Profile::getProfileGender() as $value => $label)
                                                @if($gender == $value)
                                                    <label class="radio-inline">
                                                        <input type="radio" name="gender" checked="checked" value="{{ $value }}">{{ $label }}
                                                    </label>
                                                @else
                                                    <label class="radio-inline">
                                                        <input type="radio" name="gender" value="{{ $value }}">{{ $label }}
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group{{ $errors->has('phone') ? ' has-error': '' }}">
                                        <label>Số Điện Thoại</label>
                                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->profile->phone) }}" />
                                        @if($errors->has('phone'))
                                            <span class="help-block">{{ $errors->first('phone') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group{{ $errors->has('birthday') ? ' has-error': '' }}">
                                        <label>Ngày Sinh</label>
                                        <input type="text" class="form-control DatePicker" name="birthday" value="{{ old('birthday', $user->profile->birthday) }}" />
                                        @if($errors->has('birthday'))
                                            <span class="help-block">{{ $errors->first('birthday') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Địa Chỉ</label>
                                        <input type="text" class="form-control" name="address" value="{{ old('address', $user->profile->address) }}" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Tỉnh / Thành Phố</label>
                                        <select id="ProfileProvince" name="province" class="form-control">
                                            <?php
                                            $province = old('province', \App\Libraries\Helpers\Area::getCodeFromName($user->profile->province));
                                            ?>
                                            <option value=""></option>
                                            @foreach(\App\Libraries\Helpers\Area::$provinces as $code => $data)
                                                @if($province == $code)
                                                    <option selected="selected" value="{{ $code }}">{{ $data['name'] }}</option>
                                                @else
                                                    <option value="{{ $code }}">{{ $data['name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Quận / Huyện</label>
                                        <select id="ProfileDistrict" name="district" class="form-control">
                                            <?php
                                            $district = old('district', \App\Libraries\Helpers\Area::getCodeFromName($user->profile->district, 'district'));
                                            ?>
                                            <option value=""></option>
                                            @if($district && isset(\App\Libraries\Helpers\Area::$provinces[$province]['cities']))
                                                @foreach(\App\Libraries\Helpers\Area::$provinces[$province]['cities'] as $code => $data)
                                                    @if($district == $code)
                                                        <option selected="selected" value="{{ $code }}">{{ $data }}</option>
                                                    @else
                                                        <option value="{{ $code }}">{{ $data }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Mô Tả</label>
                                        <textarea class="form-control" name="description">{{ old('description', $user->profile->description) }}</textarea>
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

@stop

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-toggle.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/bootstrap-toggle.min.js') }}"></script>
    <script type="text/javascript">
        $('#ProfileProvince').change(function() {
            var districtElem = $('#ProfileDistrict');

            districtElem.html('' +
                '<option value=""></option>' +
            '');

            if($(this).val() != '')
            {
                $.ajax({
                    url: '{{ action('Backend\UserController@getListDistrict') }}',
                    type: 'get',
                    data: 'province_code=' + $(this).val(),
                    success: function(result) {
                        if(result)
                        {
                            result = JSON.parse(result);

                            for(var code in result)
                            {
                                if(result.hasOwnProperty(code))
                                {
                                    districtElem.append('' +
                                        '<option value="' + code + '">' + result[code] + '</option>' +
                                    '');
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush