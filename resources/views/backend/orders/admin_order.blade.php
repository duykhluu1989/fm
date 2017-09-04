@extends('backend.layouts.main')

@section('page_heading', 'Đơn Hàng')

@section('section')

    <?php

    $gridView->setTools([
        function() {
            echo \App\Libraries\Helpers\Html::button(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-money fa-fw']), [
                'class' => 'btn btn-primary GridViewCheckBoxControl Confirmation',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Tiến Hành Đối Soát',
                'value' => action('Backend\OrderController@controlPaymentOrder'),
                'style' => 'display: none',
            ]);
        },
        function() {
            echo \App\Libraries\Helpers\Html::button(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-trash fa-fw']), [
                'class' => 'btn btn-primary GridViewCheckBoxControl Confirmation',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Hủy',
                'value' => action('Backend\OrderController@controlCancelOrder'),
                'style' => 'display: none',
            ]);
        },
        function() {
            echo \App\Libraries\Helpers\Html::button(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-download fa-fw']), [
                'class' => 'btn btn-primary GridViewCheckBoxControl',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Xuất Excel',
                'value' => action('Backend\OrderController@controlExportOrder'),
                'style' => 'display: none',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop