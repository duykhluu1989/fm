<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($run->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\RunController@adminRun')) }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($run->id))
            <a href="{{ action('Backend\RunController@deleteRun', ['id' => $run->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                    <label>Tên <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="name" required="required" value="{{ old('name', $run->name) }}" />
                    @if($errors->has('name'))
                        <span class="help-block">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Zone <i>(bắt buộc)</i></label>
                    <select class="form-control" name="zone_id">
                        @foreach(\App\Models\Zone::all() as $zone)
                            @if(old('zone_id', $run->zone_id) == $zone->id)
                                <option selected="selected" value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @else
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Mô Tả</label>
                    <textarea class="form-control" name="description">{{ old('description', $run->description) }}</textarea>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Áp Dụng Cho Khu Vực</label>
                    <?php
                    $districts = array();
                    foreach($run->runAreas as $runArea)
                        $districts[] = $runArea->area_id;
                    $districts = old('districts', $districts);
                    ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="no-padding" style="max-height: 500px;overflow: scroll">
                                <table class="table table-bordered table-striped table-hover table-condensed">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" class="ApplyProvinceCheckBoxAll" /></th>
                                        <th>Tỉnh / Thành Phố</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($provinces as $province)
                                        <?php
                                        $fullProvince = true;
                                        foreach($province->childrenAreas as $childArea)
                                        {
                                            if(in_array($childArea->id, $districts) == false)
                                            {
                                                $fullProvince = false;
                                                break;
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td><input type="checkbox" class="ApplyProvinceCheckBox" id="ApplyProvinceCheckBox_{{ $province->id }}"<?php echo ($fullProvince == true ? ' checked="checked"' : ''); ?> data-province-id="{{ $province->id }}" /></td>
                                            <td class="ApplyProvinceItem" data-province-id="{{ $province->id }}">{{ $province->name }}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="no-padding" style="max-height: 500px;overflow: scroll">

                                @foreach($provinces as $province)
                                    <table class="table table-bordered table-striped table-hover table-condensed hidden ApplyDistrictTable" id="ApplyDistrictTable_{{ $province->id }}">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Quận / Huyện</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($province->childrenAreas as $childArea)
                                            <tr>
                                                <td><input type="checkbox" class="ApplyDistrictCheckBox ApplyDistrictInProvinceCheckBox_{{ $province->id }}"<?php echo (in_array($childArea->id, $districts) ? ' checked="checked"' : ''); ?> name="districts[]" value="{{ $childArea->id }}" data-province-id="{{ $province->id }}" /></td>
                                                <td>{{ $childArea->name }}</td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{ empty($run->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\RunController@adminRun')) }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($run->id))
            <a href="{{ action('Backend\RunController@deleteRun', ['id' => $run->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
</div>
{{ csrf_field() }}

@push('scripts')
    <script type="text/javascript">
        $('.ApplyProvinceItem').click(function() {
            if($(this).hasClass('danger') == false)
            {
                var currentProvince = $('.ApplyProvinceItem.danger');

                if(currentProvince.length > 0)
                    currentProvince.removeClass('danger');

                $(this).addClass('danger');

                var provinceId = $(this).data('province-id');

                var currentDistrictTable = $('.CurrentApplyDistrictTable');

                if(currentDistrictTable.length > 0)
                {
                    if(currentDistrictTable.prop('id') != ('ApplyDistrictTable_' + provinceId))
                    {
                        currentDistrictTable.addClass('hidden').removeClass('CurrentApplyDistrictTable');

                        $('#ApplyDistrictTable_' + provinceId).addClass('CurrentApplyDistrictTable').removeClass('hidden');
                    }
                }
                else
                    $('#ApplyDistrictTable_' + provinceId).addClass('CurrentApplyDistrictTable').removeClass('hidden');
            }
        });

        $('.ApplyProvinceCheckBoxAll').click(function() {
            var checked = $(this).prop('checked');

            $('.ApplyProvinceCheckBox').each(function() {
                if($(this).prop('checked') != checked)
                    $(this).trigger('click');
            });
        });

        $('.ApplyProvinceCheckBox').click(function() {
            var provinceId = $(this).data('province-id');

            if($(this).prop('checked'))
            {
                var allChecked = true;

                $('.ApplyProvinceCheckBox').each(function() {
                    if(!$(this).prop('checked'))
                    {
                        allChecked = false;
                        return false;
                    }
                });

                if(allChecked)
                    $('.ApplyProvinceCheckBoxAll').first().prop('checked', $(this).prop('checked'));
            }
            else
                $('.ApplyProvinceCheckBoxAll').first().prop('checked', $(this).prop('checked'));

            $('.ApplyDistrictInProvinceCheckBox_' + provinceId).prop('checked', $(this).prop('checked'));
        });

        $('.ApplyDistrictCheckBox').click(function() {
            var provinceId = $(this).data('province-id');

            if($(this).prop('checked'))
            {
                var allChecked = true;

                $('.ApplyDistrictInProvinceCheckBox_' + provinceId).each(function() {
                    if(!$(this).prop('checked'))
                    {
                        allChecked = false;
                        return false;
                    }
                });

                if(allChecked)
                    $('#ApplyProvinceCheckBox_' + provinceId).prop('checked', $(this).prop('checked'));
            }
            else
                $('#ApplyProvinceCheckBox_' + provinceId).prop('checked', $(this).prop('checked'));
        });
    </script>
@endpush