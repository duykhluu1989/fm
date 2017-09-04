@extends('backend.layouts.main')

@section('page_heading', 'Khu Vực Giao Hàng')

@section('section')

    <?php

    $gridView->setTools([
        function() {
            echo \App\Libraries\Helpers\Html::a(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-download fa-fw']), [
                'href' => action('Backend\AreaController@exportArea'),
                'class' => 'btn btn-primary',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Xuất Excel',
                'target' => '_blank',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop

@push('scripts')
    <script type="text/javascript">
        $('select[name="province"]').change(function() {
            $('select[name="district"]').html('' +
                '<option value=""></option>' +
            '');

            $.ajax({
                url: '{{ action('Backend\UserController@getListArea') }}',
                type: 'get',
                data: 'parent_id=' + $('select[name="province"]').val() + '&type={{ \App\Models\Area::TYPE_DISTRICT_DB }}',
                success: function(result) {
                    if(result)
                    {
                        result = JSON.parse(result);

                        var i;

                        for(i = 0;i < result.length;i ++)
                        {
                            $('select[name="district"]').append('' +
                                '<option value="' + result[i].id + '">' + result[i].name + '</option>' +
                            '');
                        }
                    }
                }
            });
        });
    </script>
@endpush