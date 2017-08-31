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