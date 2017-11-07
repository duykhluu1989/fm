<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($zone->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\ZoneController@adminZone')) }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($zone->id))
            <a href="{{ action('Backend\ZoneController@deleteZone', ['id' => $zone->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                    <label>Tên <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="name" required="required" value="{{ old('name', $zone->name) }}" />
                    @if($errors->has('name'))
                        <span class="help-block">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Mô Tả</label>
                    <textarea class="form-control" name="description">{{ old('description', $zone->description) }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{ empty($zone->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\ZoneController@adminZone')) }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($zone->id))
            <a href="{{ action('Backend\ZoneController@deleteZone', ['id' => $zone->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
</div>
{{ csrf_field() }}