@extends('backend.layouts.main')

@section('page_heading', 'Chuyên Gia')

@section('section')

    <?php

    $gridView->setTools([
        function() {
            echo \App\Libraries\Helpers\Html::a(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-plus fa-fw']), [
                'href' => action('Backend\UserController@createUser'),
                'class' => 'btn btn-primary',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Thành Viên Mới',
            ]);
        },
        function() {
            echo \App\Libraries\Helpers\Html::button(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-eye fa-fw']), [
                'class' => 'btn btn-primary GridViewCheckBoxControl Confirmation',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => \App\Models\Expert::ONLINE_LABEL,
                'value' => action('Backend\ExpertController@controlChangeOnlineExpert', ['online' => \App\Libraries\Helpers\Utility::ACTIVE_DB]),
                'style' => 'display: none',
            ]);
        },
        function() {
            echo \App\Libraries\Helpers\Html::button(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-eye-slash fa-fw']), [
                'class' => 'btn btn-primary GridViewCheckBoxControl Confirmation',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => \App\Models\Expert::OFFLINE_LABEL,
                'value' => action('Backend\ExpertController@controlChangeOnlineExpert', ['status' => \App\Libraries\Helpers\Utility::INACTIVE_DB]),
                'style' => 'display: none',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop