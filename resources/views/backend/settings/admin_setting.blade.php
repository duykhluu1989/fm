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
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::HOT_LINE) ? ' has-error': '' }}">
                            <label>{{ $settings[\App\Models\Setting::HOT_LINE]->name }}</label>
                            <input type="text" class="form-control" name="{{ \App\Models\Setting::HOT_LINE }}" value="{{ old(\App\Models\Setting::HOT_LINE, $settings[\App\Models\Setting::HOT_LINE]->value) }}" />
                            @if($errors->has(\App\Models\Setting::HOT_LINE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::HOT_LINE) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::CONTACT_EMAIL) ? ' has-error': '' }}">
                            <label>{{ $settings[\App\Models\Setting::CONTACT_EMAIL]->name }}</label>
                            <input type="text" class="form-control" name="{{ \App\Models\Setting::CONTACT_EMAIL }}" value="{{ old(\App\Models\Setting::CONTACT_EMAIL, $settings[\App\Models\Setting::CONTACT_EMAIL]->value) }}" />
                            @if($errors->has(\App\Models\Setting::CONTACT_EMAIL))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::CONTACT_EMAIL) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{ $settings[\App\Models\Setting::WORKING_TIME]->name }}</label>
                            <input type="text" class="form-control" name="{{ \App\Models\Setting::WORKING_TIME }}" value="{{ old(\App\Models\Setting::WORKING_TIME, $settings[\App\Models\Setting::WORKING_TIME]->value) }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{ $settings[\App\Models\Setting::ABOUT_US]->name }}</label>
                            <input type="text" class="form-control" name="{{ \App\Models\Setting::ABOUT_US }}" value="{{ old(\App\Models\Setting::ABOUT_US, $settings[\App\Models\Setting::ABOUT_US]->value) }}" />
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