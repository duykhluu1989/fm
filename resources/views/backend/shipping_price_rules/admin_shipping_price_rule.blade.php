@extends('backend.layouts.main')

@section('page_heading', 'Shipping Price Rule')

@section('section')

    <?php

    $gridView->setTools([
        function() {
            echo \App\Libraries\Helpers\Html::a(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-plus fa-fw']), [
                'href' => action('Backend\ShippingPriceRuleController@createShippingPriceRule'),
                'class' => 'btn btn-primary',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'New Shipping Price Rule',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop