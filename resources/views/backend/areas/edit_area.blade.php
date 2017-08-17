@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Khu Vực - ' . $area->name)

@section('section')

    <form action="{{ action('Backend\AreaController@editArea', ['id' => $area->id]) }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\AreaController@adminArea')) }}" class="btn btn-default">Quay Lại</a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Tên Khu Vực</label>
                            <span class="form-control no-border">{{ $area->name }}</span>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Thuộc Khu Vực</label>
                            <span class="form-control no-border">
                                @if(!empty($argc->parentArea))
                                    {{ $area->parentArea->name }}
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Loại Khu Vực</label>
                            <span class="form-control no-border">{{ \App\Models\Area::getAreaType($area->type) }}</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Trạng Thái</label>
                            <?php
                            $status = old('status', $area->status);
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
                        <div class="form-group{{ $errors->has('shipping_price') ? ' has-error': '' }}">
                            <label>Phí Ship</label>
                            <input type="text" class="form-control InputForNumber" name="shipping_price" value="{{ old('shipping_price', \App\Libraries\Helpers\Utility::formatNumber($area->shipping_price)) }}" required="required" />
                            @if($errors->has('shipping_price'))
                                <span class="help-block">{{ $errors->first('shipping_price') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\AreaController@adminArea')) }}" class="btn btn-default">Quay Lại</a>
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