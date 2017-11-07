@extends('backend.layouts.main')

@section('page_heading', 'New Run')

@section('section')

    <form action="{{ action('Backend\RunController@createRun') }}" method="post">

        @include('backend.runs.partials.run_form')

    </form>

@stop