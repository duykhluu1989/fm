@extends('backend.layouts.main')

@section('page_heading', 'Edit Zone - ' . $zone->name)

@section('section')

    <form action="{{ action('Backend\ZoneController@editZone', ['id' => $zone->id]) }}" method="post">

        @include('backend.zones.partials.zone_form')

    </form>

@stop