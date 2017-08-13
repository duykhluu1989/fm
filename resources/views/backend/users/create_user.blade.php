@extends('backend.layouts.main')

@section('page_heading', 'Thành Viên Mới')

@section('section')

    <form action="{{ action('Backend\UserController@createUser') }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Tạo Mới</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\UserController@adminUser')) }}" class="btn btn-default">Quay Lại</a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has('username') ? ' has-error': '' }}">
                            <label>Tên Tài Khoản <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" name="username" required="required" value="{{ old('username') }}" />
                            @if($errors->has('username'))
                                <span class="help-block">{{ $errors->first('username') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has('first_name') ? ' has-error': '' }}">
                            <label>Tên <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" name="first_name" required="required" value="{{ old('first_name') }}" />
                            @if($errors->has('first_name'))
                                <span class="help-block">{{ $errors->first('first_name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has('last_name') ? ' has-error': '' }}">
                            <label>Họ</label>
                            <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" />
                            @if($errors->has('last_name'))
                                <span class="help-block">{{ $errors->first('last_name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has('email') ? ' has-error': '' }}">
                            <label>Email <i>(bắt buộc)</i></label>
                            <input type="email" class="form-control" name="email" required="required" value="{{ old('email') }}" />
                            @if($errors->has('email'))
                                <span class="help-block">{{ $errors->first('email') }}</span>
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
                            <label>Mật Khẩu <i>(bắt buộc)</i></label>
                            <input type="password" class="form-control" name="password" required="required" value="{{ old('password') }}" />
                            @if($errors->has('password'))
                                <span class="help-block">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has('re_password') ? ' has-error': '' }}">
                            <label>Xác Nhận Mật Khẩu <i>(bắt buộc)</i></label>
                            <input type="password" class="form-control" name="re_password" required="required" value="{{ old('re_password') }}" />
                            @if($errors->has('re_password'))
                                <span class="help-block">{{ $errors->first('re_password') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Tạo Mới</button>
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
@endpush