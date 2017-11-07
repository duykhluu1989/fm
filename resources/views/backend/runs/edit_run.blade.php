@extends('backend.layouts.main')

@section('page_heading', 'Edit Run - ' . $run->name)

@section('section')

    <form action="{{ action('Backend\RunController@editRun', ['id' => $run->id]) }}" method="post">

        @include('backend.runs.partials.run_form')

    </form>

@stop