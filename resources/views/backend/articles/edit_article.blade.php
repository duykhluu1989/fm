@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Trang - ' . $article->name)

@section('section')

    <form action="{{ action('Backend\ArticleController@editArticle', ['id' => $article->id]) }}" method="post">

        @include('backend.articles.partials.article_form')

    </form>

@stop