@extends('backend.layouts.main')

@section('page_heading', 'New Shipping Price Rule')

@section('section')

    <form action="{{ action('Backend\ShippingPriceRuleController@createShippingPriceRule') }}" method="post">

        @include('backend.shipping_price_rules.partials.shipping_price_form')

    </form>

@stop