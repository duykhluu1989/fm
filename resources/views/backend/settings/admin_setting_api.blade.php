@extends('backend.layouts.main')

@section('page_heading', 'Api')

@section('section')

    <form action="{{ action('Backend\SettingController@adminSettingApi') }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{ $settings[\App\Models\Setting::DETRACK_API_KEY]->name }}</label>
                            <input type="password" class="form-control" name="{{ \App\Models\Setting::DETRACK_API_KEY }}" value="{{ old(\App\Models\Setting::DETRACK_API_KEY, $settings[\App\Models\Setting::DETRACK_API_KEY]->value) }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{ $settings[\App\Models\Setting::DETRACK_WEB_HOOK_KEY]->name }}</label>
                            <input type="password" class="form-control" name="{{ \App\Models\Setting::DETRACK_WEB_HOOK_KEY }}" value="{{ old(\App\Models\Setting::DETRACK_WEB_HOOK_KEY, $settings[\App\Models\Setting::DETRACK_WEB_HOOK_KEY]->value) }}" />
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