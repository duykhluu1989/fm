@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Mã Giảm Giá - ' . $discount->code)

@section('section')

    <form action="{{ action('Backend\DiscountController@editDiscount', ['id' => $discount->id]) }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\DiscountController@adminDiscount')) }}" class="btn btn-default">Quay Lại</a>

                @if(!empty($discount->id))
                    <?php
                    $isDeletable = $discount->isDeletable();
                    ?>
                    @if($isDeletable == true)
                        <a href="{{ action('Backend\DiscountController@deleteDiscount', ['id' => $discount->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
                    @endif
                @endif
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Mã</label>
                            <span class="form-control no-border">{{ $discount->code }}</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Thời Gian Khởi Tạo</label>
                            <span class="form-control no-border">{{ $discount->created_at }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Trạng Thái</label>
                            <?php
                            $status = old('status', $discount->status);
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
                        <div class="form-group{{ $errors->has('start_time') ? ' has-error': '' }}">
                            <label>Thời Gian Bắt Đầu</label>
                            <input type="text" class="form-control DateTimePicker" name="start_time" value="{{ old('start_time', $discount->start_time) }}" />
                            @if($errors->has('start_time'))
                                <span class="help-block">{{ $errors->first('start_time') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('end_time') ? ' has-error': '' }}">
                            <label>Thời Gian Kết Thúc</label>
                            <input type="text" class="form-control DateTimePicker" name="end_time" value="{{ old('end_time', $discount->end_time) }}" />
                            @if($errors->has('end_time'))
                                <span class="help-block">{{ $errors->first('end_time') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Giá Trị Đơn Hàng Tối Thiểu</label>
                            <span class="form-control no-border">{{ \App\Libraries\Helpers\Utility::formatNumber($discount->minimum_order_amount) . ' VND' }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Loại Giảm Giá</label>
                            <span class="form-control no-border">{{ \App\Models\Discount::getDiscountType($discount->type) }}</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Giá Trị Giảm Giá</label>
                            <span class="form-control no-border">{{ $discount->type == \App\Models\Discount::TYPE_FIX_AMOUNT_DB ? (\App\Libraries\Helpers\Utility::formatNumber($discount->value) . ' VND') : ($discount->value . ' %' . (!empty($discount->value_limit) ? ' (Tối Đa: ' . \App\Libraries\Helpers\Utility::formatNumber($discount->value_limit) . ' VND)' : '')) }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Số Lần Sử Dụng Tổng</label>
                            <span class="form-control no-border">{{ !empty($discount->usage_limit) ? \App\Libraries\Helpers\Utility::formatNumber($discount->usage_limit) : 'Không Giới Hạn' }}</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Đã Sử Dụng</label>
                            <span class="form-control no-border">{{ \App\Libraries\Helpers\Utility::formatNumber($discount->used_count) }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if(!empty($discount->user_id))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Thành Viên Chỉ Định</label>
                                <span class="form-control no-border">{{ !empty($discount->user) ? ($discount->user->name . ' - ' . $discount->user->email) : '' }}</span>
                            </div>
                        </div>
                    @else
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Số Lần Sử Dụng Mỗi Thành Viên</label>
                                <span class="form-control no-border">{{ !empty($discount->usage_unique) ? \App\Libraries\Helpers\Utility::formatNumber($discount->usage_unique) : 'Không Giới Hạn' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>Mã Chương Trình</label>
                                <span class="form-control no-border">{{ $discount->campaign_code }}</span>
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Mô Tả</label>
                            <span class="form-control no-border">{{ $discount->description }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\DiscountController@adminDiscount')) }}" class="btn btn-default">Quay Lại</a>

                @if(!empty($discount->id) && $isDeletable == true)
                    <a href="{{ action('Backend\DiscountController@deleteDiscount', ['id' => $discount->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
                @endif
            </div>
        </div>
        {{ csrf_field() }}

    </form>

@stop

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-toggle.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-toggle.min.js') }}"></script>
    <script type="text/javascript">
        $('.DateTimePicker').datetimepicker({
            format: 'Y-m-d H:i:s'
        });
    </script>
@endpush