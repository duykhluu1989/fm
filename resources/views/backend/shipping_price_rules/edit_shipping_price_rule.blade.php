@extends('backend.layouts.main')

@section('page_heading', 'Edit Shipping Price Rule - ' . $rule->name)

@section('section')

    <form action="{{ action('Backend\ShippingPriceRuleController@editShippingPriceRule', ['id' => $rule->id]) }}" method="post">

        @include('backend.shipping_price_rules.partials.shipping_price_form')

    </form>

@stop