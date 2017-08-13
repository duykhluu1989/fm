@extends('backend.layouts.main')

@section('page_heading', 'Tổng Quan')

@section('section')

    <form action="{{ action('Backend\SettingController@adminSetting') }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{ $settings[\App\Models\Setting::WEB_TITLE]->name }}</label>
                            <input type="text" class="form-control" name="{{ \App\Models\Setting::WEB_TITLE }}" value="{{ old(\App\Models\Setting::WEB_TITLE, $settings[\App\Models\Setting::WEB_TITLE]->value) }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{ $settings[\App\Models\Setting::WEB_DESCRIPTION]->name }}</label>
                            <input type="text" class="form-control" name="{{ \App\Models\Setting::WEB_DESCRIPTION }}" value="{{ old(\App\Models\Setting::WEB_DESCRIPTION, $settings[\App\Models\Setting::WEB_DESCRIPTION]->value) }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{ $settings[\App\Models\Setting::WEB_KEYWORD]->name }}</label>
                            <input type="text" class="form-control" name="{{ \App\Models\Setting::WEB_KEYWORD }}" value="{{ old(\App\Models\Setting::WEB_KEYWORD, $settings[\App\Models\Setting::WEB_KEYWORD]->value) }}" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::EXCHANGE_USD_RATE) ? ' has-error': '' }}">
                            <label>{{ $settings[\App\Models\Setting::EXCHANGE_USD_RATE]->name }} <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control InputForNumber" name="{{ \App\Models\Setting::EXCHANGE_USD_RATE }}" value="{{ old(\App\Models\Setting::EXCHANGE_USD_RATE, \App\Libraries\Helpers\Utility::formatNumber($settings[\App\Models\Setting::EXCHANGE_USD_RATE]->value)) }}" required="required" />
                                <span class="input-group-addon">VND = 1 USD</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::EXCHANGE_USD_RATE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::EXCHANGE_USD_RATE) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::EXCHANGE_POINT_RATE) ? ' has-error': '' }}">
                            <label>{{ $settings[\App\Models\Setting::EXCHANGE_POINT_RATE]->name }} <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control InputForNumber" name="{{ \App\Models\Setting::EXCHANGE_POINT_RATE }}" value="{{ old(\App\Models\Setting::EXCHANGE_POINT_RATE, \App\Libraries\Helpers\Utility::formatNumber($settings[\App\Models\Setting::EXCHANGE_POINT_RATE]->value)) }}" required="required" />
                                <span class="input-group-addon">VND = 1 Điểm</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::EXCHANGE_POINT_RATE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::EXCHANGE_POINT_RATE) }}</span>
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