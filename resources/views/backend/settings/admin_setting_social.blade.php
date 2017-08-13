@extends('backend.layouts.main')

@section('page_heading', 'Mạng Xã Hội')

@section('section')

    <form action="{{ action('Backend\SettingController@adminSettingSocial') }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title">Facebook</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>{{ $settings[\App\Models\Setting::FACEBOOK_APP_ID]->name }}</label>
                            <input type="text" class="form-control" name="{{ \App\Models\Setting::FACEBOOK_APP_ID }}" value="{{ old(\App\Models\Setting::FACEBOOK_APP_ID, $settings[\App\Models\Setting::FACEBOOK_APP_ID]->value) }}" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>{{ $settings[\App\Models\Setting::FACEBOOK_APP_SECRET]->name }}</label>
                            <input type="password" class="form-control" name="{{ \App\Models\Setting::FACEBOOK_APP_SECRET }}" value="{{ old(\App\Models\Setting::FACEBOOK_APP_SECRET, $settings[\App\Models\Setting::FACEBOOK_APP_SECRET]->value) }}" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>{{ $settings[\App\Models\Setting::FACEBOOK_GRAPH_VERSION]->name }}</label>
                            <input type="text" class="form-control" name="{{ \App\Models\Setting::FACEBOOK_GRAPH_VERSION }}" value="{{ old(\App\Models\Setting::FACEBOOK_GRAPH_VERSION, $settings[\App\Models\Setting::FACEBOOK_GRAPH_VERSION]->value) }}" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>{{ $settings[\App\Models\Setting::FACEBOOK_PAGE_URL]->name }}</label>
                            <input type="text" class="form-control" name="{{ \App\Models\Setting::FACEBOOK_PAGE_URL }}" value="{{ old(\App\Models\Setting::FACEBOOK_PAGE_URL, $settings[\App\Models\Setting::FACEBOOK_PAGE_URL]->value) }}" />
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