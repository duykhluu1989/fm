@extends('backend.layouts.main')

@section('page_heading', 'New Zone')

@section('section')

    <form action="{{ action('Backend\ZoneController@createZone') }}" method="post">

        @include('backend.zones.partials.zone_form')

    </form>

@stop