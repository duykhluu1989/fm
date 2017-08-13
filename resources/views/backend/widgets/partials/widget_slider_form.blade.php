<?php
$attributes = json_decode($widget->attribute, true);

$details = array();
if(!empty($widget->detail))
    $details = json_decode($widget->detail, true);
?>
<div class="col-sm-12">
    <div class="form-group">
        <label>Chi Tiết <i>(kéo thả để thay đổi thứ tự)</i></label>
        <div class="no-padding">
            <table class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                <tr>
                    @foreach($attributes as $attribute)
                        @if($attribute['type'] == \App\Models\Widget::ATTRIBUTE_TYPE_IMAGE_DB)
                            <th class="col-sm-1">{{ $attribute['title'] }}</th>
                        @else
                            <th>{{ $attribute['title'] }}</th>
                        @endif
                    @endforeach
                    <th class="col-sm-1 text-center">
                        <button type="button" class="btn btn-primary" id="NewSliderItemButton" data-container="body" data-toggle="popover" data-placement="top" data-content="Thêm Mới"><i class="fa fa-plus fa-fw"></i></button>
                    </th>
                </tr>
                </thead>
                <tbody id="ListSliderItem">
                @foreach($details as $detail)
                    <tr>
                        @foreach($attributes as $attribute)
                            @if($attribute['type'] == \App\Models\Widget::ATTRIBUTE_TYPE_IMAGE_DB)
                                <td class="text-center">
                                    <button type="button" class="btn btn-default ElFinderPopupOpen"><i class="fa fa-image fa-fw"></i></button>
                                    <input type="hidden" name="detail[{{ $attribute['name'] }}][]" value="{{ isset($detail[$attribute['name']]) ? $detail[$attribute['name']] : '' }}" />
                                    @if(isset($detail[$attribute['name']]))
                                        <img src="{{ $detail[$attribute['name']] }}" width="100%" alt="Slider Image" />
                                    @endif
                                </td>
                            @else
                                <td>
                                    <input type="text" class="form-control" name="detail[{{ $attribute['name'] }}][]" value="{{ isset($detail[$attribute['name']]) ? $detail[$attribute['name']] : '' }}" />
                                </td>
                            @endif
                        @endforeach
                        <td class="text-center">
                            <button type="button" class="btn btn-default RemoveSliderItemButton"><i class="fa fa-trash-o fa-fw"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/colorbox.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/jquery.colorbox-min.js') }}"></script>
    <script type="text/javascript">
        var elFinderSelectedFile;

        $('#NewSliderItemButton').click(function() {
            $('#ListSliderItem').append('' +
                '<tr>' +
                @foreach($attributes as $attribute)
                    @if($attribute['type'] == \App\Models\Widget::ATTRIBUTE_TYPE_IMAGE_DB)
                        '<td class="text-center">' +
                        '<button type="button" class="btn btn-default ElFinderPopupOpen"><i class="fa fa-image fa-fw"></i></button>' +
                        '<input type="hidden" name="detail[{{ $attribute['name'] }}][]" />' +
                        '</td>' +
                    @else
                        '<td>' +
                        '<input type="text" class="form-control" name="detail[{{ $attribute['name'] }}][]" />' +
                        '</td>' +
                    @endif
                @endforeach
                '<td class="text-center">' +
                '<button type="button" class="btn btn-default RemoveSliderItemButton"><i class="fa fa-trash-o fa-fw"></i></button>' +
                '</td>' +
            '</tr>');
        });

        $('#ListSliderItem').on('click', 'button', function() {
            if($(this).hasClass('ElFinderPopupOpen'))
            {
                elFinderSelectedFile = $(this).parent().find('input').first();

                $.colorbox({
                    href: '{{ action('Backend\ElFinderController@popup') }}',
                    iframe: true,
                    width: '1200',
                    height: '600',
                    closeButton: false
                });
            }
            else if($(this).hasClass('RemoveSliderItemButton'))
                $(this).parent().parent().remove();
        }).sortable({
            revert: true,
            cursor: 'move'
        });

        function elFinderProcessSelectedFile(fileUrl)
        {
            elFinderSelectedFile.val(fileUrl);

            if(elFinderSelectedFile.parent().find('img').length > 0)
                elFinderSelectedFile.parent().find('img').first().prop('src', fileUrl);
            else
            {
                elFinderSelectedFile.parent().append('' +
                    '<img src="' + fileUrl + '" width="100%" alt="Slider Image" />' +
                '');
            }
        }
    </script>
@endpush