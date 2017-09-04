@extends('backend.layouts.main')

@section('page_heading', 'Trạng Thái Đơn Hàng')

@section('section')

    <form action="{{ action('Backend\SettingController@adminSettingOrderStatus') }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </div>
            <div class="box-body">
                <?php
                $orderStatusList = json_decode($settings[\App\Models\Setting::ORDER_STATUS_LIST]->value, true);
                ?>
                <div class="row">

                    @foreach($orderStatusList as $code => $label)
                        <div class="col-sm-4">
                            <div class="form-group{{ $errors->has($code) ? ' has-error': '' }}">
                                <label>{{ $code }}</label>
                                <input type="text" class="form-control" name="{{ $code }}" value="{{ old($code, $label) }}" required="required" />
                                @if($errors->has($code))
                                    <span class="help-block">{{ $errors->first($code) }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

@stop