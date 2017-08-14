@extends('backend.layouts.main')

@section('page_heading', 'Tài Khoản')

@section('section')

    <form action="{{ action('Backend\UserController@editAccount') }}" method="post" enctype="multipart/form-data">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
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
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

@stop