@extends('backend.layouts.main')

@section('page_heading', 'Mã Giảm Giá Mới')

@section('section')

    <form action="{{ action('Backend\DiscountController@createDiscount') }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Tạo Mới</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\DiscountController@adminDiscount')) }}" class="btn btn-default">Quay Lại</a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Tạo Nhiều Mã</label>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="CreateMultiCheckBox"<?php echo (old('create_multi_number') ? ' checked="checked"' : ''); ?> data-toggle="toggle" data-on="{{ \App\Libraries\Helpers\Utility::TRUE_LABEL }}" data-off="{{ \App\Libraries\Helpers\Utility::FALSE_LABEL }}" data-onstyle="success" data-offstyle="danger" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="CreateOneCodeInputs" style="<?php echo (old('create_multi_number') ? 'display: none' : ''); ?>">
                    <div class="col-sm-8">
                        <div class="form-group{{ $errors->has('code') ? ' has-error': '' }}">
                            <label>Mã <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" id="CodeInput" name="code" value="{{ old('code', $discount->code) }}"<?php echo (old('create_multi_number') ? '' : ' required="required"'); ?> />
                            @if($errors->has('code'))
                                <span class="help-block">{{ $errors->first('code') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Tạo Mã Tự Động <i>(nhập số kí tự của mã: 1 - 255)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="GenerateCodeInput"<?php echo (old('create_multi_number') ? '' : ''); ?> />
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary" id="GenerateCodeButton">Tạo Mã</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="CreateMultiCodeInputs" style="<?php echo (old('create_multi_number') ? '' : 'display: none'); ?>">
                    <div class="col-sm-6">
                        <div class="form-group{{ $errors->has('create_multi_character_number') ? ' has-error': '' }}">
                            <label>Số Kí Tự Mã <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" id="CreateMultiCharacterNumberInput" name="create_multi_character_number" value="{{ old('create_multi_character_number') }}"<?php echo (old('create_multi_number') ? ' required="required"' : ''); ?> />
                            @if($errors->has('create_multi_character_number'))
                                <span class="help-block">{{ $errors->first('create_multi_character_number') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group{{ $errors->has('create_multi_number') ? ' has-error': '' }}">
                            <label>Số Lượng Mã <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" id="CreateMultiNumberInput" name="create_multi_number" value="{{ old('create_multi_number') }}"<?php echo (old('create_multi_number') ? ' required="required"' : ''); ?> />
                            @if($errors->has('create_multi_number'))
                                <span class="help-block">{{ $errors->first('create_multi_number') }}</span>
                            @endif
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
                            <label>Thời Gian Bắt Đầu <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control DateTimePicker" name="start_time" value="{{ old('start_time', $discount->start_time) }}" required="required" />
                            @if($errors->has('start_time'))
                                <span class="help-block">{{ $errors->first('start_time') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('end_time') ? ' has-error': '' }}">
                            <label>Thời Gian Kết Thúc <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control DateTimePicker" name="end_time" value="{{ old('end_time', $discount->end_time) }}" required="required" />
                            @if($errors->has('end_time'))
                                <span class="help-block">{{ $errors->first('end_time') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('minimum_order_amount') ? ' has-error': '' }}">
                            <label>Giá Trị Đơn Hàng Tối Thiểu</label>
                            <div class="input-group">
                                <input type="text" class="form-control InputForNumber" name="minimum_order_amount" value="{{ old('minimum_order_amount', (!empty($discount->minimum_order_amount) ? \App\Libraries\Helpers\Utility::formatNumber($discount->minimum_order_amount) : '')) }}" />
                                <span class="input-group-addon">VND</span>
                            </div>
                            @if($errors->has('minimum_order_amount'))
                                <span class="help-block">{{ $errors->first('minimum_order_amount') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Loại Giảm Giá</label>
                            <?php
                            $type = old('type', $discount->type);
                            ?>
                            <div>
                                @foreach(\App\Models\Discount::getDiscountType() as $value => $label)
                                    @if($type == $value)
                                        <label class="radio-inline">
                                            <input type="radio" name="type" checked="checked" value="{{ $value }}">{{ $label }}
                                        </label>
                                    @else
                                        <label class="radio-inline">
                                            <input type="radio" name="type" value="{{ $value }}">{{ $label }}
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('value') ? ' has-error': '' }}">
                            <label>Giá Trị Giảm Giá <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="ValueInput" name="value" required="required" value="{{ old('value', ($type == \App\Models\Discount::TYPE_FIX_AMOUNT_DB ? \App\Libraries\Helpers\Utility::formatNumber($discount->value) : $discount->value)) }}" />
                                <span class="input-group-addon" id="ValueUnit">{{ $type == \App\Models\Discount::TYPE_FIX_AMOUNT_DB ? 'VND' : '%' }}</span>
                            </div>
                            @if($errors->has('value'))
                                <span class="help-block">{{ $errors->first('value') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('value_limit') ? ' has-error': '' }}">
                            <label>Giá Trị Giảm Giá Tối Đa</label>
                            <div class="input-group">
                                <input type="text" class="form-control InputForNumber" id="ValueLimitInput" name="value_limit" value="{{ old('value_limit', (!empty($discount->value_limit) ? \App\Libraries\Helpers\Utility::formatNumber($discount->value_limit) : '')) }}"<?php echo ($type == \App\Models\Discount::TYPE_FIX_AMOUNT_DB ? ' readonly="readonly"' : ''); ?> />
                                <span class="input-group-addon">VND</span>
                            </div>
                            @if($errors->has('value_limit'))
                                <span class="help-block">{{ $errors->first('value_limit') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('usage_limit') ? ' has-error': '' }}">
                            <label>Số Lần Sử Dụng Tổng <i>(để trống là không giới hạn)</i></label>
                            <input type="text" class="form-control InputForNumber" name="usage_limit" value="{{ old('usage_limit', (!empty($discount->usage_limit) ? \App\Libraries\Helpers\Utility::formatNumber($discount->usage_limit) : '')) }}" />
                            @if($errors->has('usage_limit'))
                                <span class="help-block">{{ $errors->first('usage_limit') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Chỉ Cho Phép Thành Viên Chỉ Định Sử Dụng</label>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="FixUsageUserCheckBox"<?php echo (old('user_name', $discount->user_id) ? ' checked="checked"' : ''); ?> data-toggle="toggle" data-on="{{ \App\Libraries\Helpers\Utility::TRUE_LABEL }}" data-off="{{ \App\Libraries\Helpers\Utility::FALSE_LABEL }}" data-onstyle="success" data-offstyle="danger" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="FixUsageUserUsernameInput" style="<?php echo (old('user_name', $discount->user_id) ? '' : 'display: none'); ?>">
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has('user_name') ? ' has-error': '' }}">
                            <label>Thành Viên Chỉ Định <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" id="UserInput" name="user_name" placeholder="name - email" value="{{ old('user_name', (!empty($discount->user_id) ? (!empty($discount->user) ? ($discount->user->name . ' - ' . $discount->user->email) : '') : '')) }}"<?php echo (old('user_name', $discount->user_id) ? ' required="required"' : ''); ?> />
                            @if($errors->has('user_name'))
                                <span class="help-block">{{ $errors->first('user_name') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row" id="NoFixUsageUserInputs" style="<?php echo (old('user_name', $discount->user_id) ? 'display: none' : ''); ?>">
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('usage_unique') ? ' has-error': '' }}">
                            <label>Số Lần Sử Dụng Mỗi Thành Viên <i>(để trống là không giới hạn)</i></label>
                            <input type="text" class="form-control InputForNumber" id="UsageUniqueInput" name="usage_unique" value="{{ old('usage_unique', (!empty($discount->usage_unique) ? \App\Libraries\Helpers\Utility::formatNumber($discount->usage_unique) : '')) }}" />
                            @if($errors->has('usage_unique'))
                                <span class="help-block">{{ $errors->first('usage_unique') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group{{ $errors->has('campaign_code') ? ' has-error': '' }}">
                            <label>Mã Chương Trình <i>(mã giảm giá cùng chương trình thì mỗi thành viên chỉ được sử dụng 1 mã)</i></label>
                            <input type="text" class="form-control" id="CampaignCodeInput" name="campaign_code" value="{{ old('campaign_code', $discount->campaign_code) }}" />
                            @if($errors->has('campaign_code'))
                                <span class="help-block">{{ $errors->first('campaign_code') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Mô Tả</label>
                            <textarea class="form-control" name="description">{{ old('description', $discount->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Tạo Mới</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\DiscountController@adminDiscount')) }}" class="btn btn-default">Quay Lại</a>
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
        $('#GenerateCodeButton').click(function() {
            var elem = $('#GenerateCodeInput');

            if(elem.val() == '')
            {
                elem.focus();
                alert('Vui lòng nhập số kí tự');
            }
            else
            {
                var number = parseInt(elem.val());

                if(number < 1 || number > 255)
                    alert('Số kí tự phải lớn hơn hoặc bằng 1 và nhỏ hơn hoặc bằng 255');
                else
                {
                    $.ajax({
                        url: '{{ action('Backend\DiscountController@generateDiscountCode') }}',
                        type: 'post',
                        data: '_token=' + $('input[name="_token"]').first().val() + '&number=' + number,
                        success: function(result) {
                            if(result)
                            {
                                $('#CodeInput').val(result);
                                elem.val('');
                            }
                            else
                                alert('Tạo mã tự động thất bại');
                        }
                    });
                }
            }
        });

        $('.DateTimePicker').datetimepicker({
            format: 'Y-m-d H:i:s'
        });

        setValueInputFormatNumber(true);

        $('input[type="radio"][name="type"]').change(function() {
            setValueInputFormatNumber(false);
        });

        function setValueInputFormatNumber(init)
        {
            var valueInputElem = $('#ValueInput');

            if($('input[type="radio"][name="type"]:checked').val() == '{{ \App\Models\Discount::TYPE_FIX_AMOUNT_DB }}')
            {
                $('#ValueUnit').html('VND');
                if(init == false)
                    valueInputElem.val(1);
                valueInputElem.on('keyup', function() {
                    $(this).val(formatNumber($(this).val(), '.'));
                });
                $('#ValueLimitInput').val('').prop('readonly', 'readonly');
            }
            else
            {
                $('#ValueUnit').html('%');
                if(init == false)
                    valueInputElem.val(1);
                valueInputElem.off('keyup');
                $('#ValueLimitInput').removeAttr('readonly');
            }
        }

        $('#FixUsageUserCheckBox').change(function() {
            if($(this).prop('checked'))
            {
                $('#UserInput').prop('required', 'required');
                $('#UsageUniqueInput').val('');
                $('#CampaignCodeInput').val('');
                $('#FixUsageUserUsernameInput').show();
                $('#NoFixUsageUserInputs').hide();
            }
            else
            {
                $('#UserInput').val('').removeAttr('required');
                $('#FixUsageUserUsernameInput').hide();
                $('#NoFixUsageUserInputs').show();
            }
        });

        $('#CreateMultiCheckBox').change(function() {
            if($(this).prop('checked'))
            {
                $('#CreateMultiCharacterNumberInput').prop('required', 'required').val(1);
                $('#CreateMultiNumberInput').prop('required', 'required').val(1);
                $('#CodeInput').val('').removeAttr('required');
                $('#GenerateCodeInput').val('');
                $('#CreateOneCodeInputs').hide();
                $('#CreateMultiCodeInputs').show();
            }
            else
            {
                $('#CreateMultiCharacterNumberInput').val('').removeAttr('required');
                $('#CreateMultiNumberInput').val('').removeAttr('required');
                $('#CodeInput').prop('required', 'required');
                $('#CreateOneCodeInputs').show();
                $('#CreateMultiCodeInputs').hide();
            }
        });

        $('#UserInput').autocomplete({
            minLength: 3,
            delay: 1000,
            source: function(request, response) {
                $.ajax({
                    url: '{{ action('Backend\UserController@autoCompleteUser') }}',
                    type: 'post',
                    data: '_token=' + $('input[name="_token"]').first().val() + '&term=' + request.term,
                    success: function(result) {
                        if(result)
                        {
                            result = JSON.parse(result);
                            response(result);
                        }
                    }
                });
            },
            select: function(event, ui) {
                $(this).val(ui.item.name + ' - ' + ui.item.email);
                return false;
            }
        }).autocomplete('instance')._renderItem = function(ul, item) {
            return $('<li>').append('<a>' + item.name + ' - ' + item.email + '</a>').appendTo(ul);
        };
    </script>
@endpush