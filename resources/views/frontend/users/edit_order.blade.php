@extends('frontend.layouts.main')

@section('page_heading', 'Sửa đơn hàng ' . $order->number)

@section('section')

    @include('frontend.layouts.partials.menu')

    <main>

        @include('frontend.users.partials.navigation')

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="title_user line-on-right">Sửa đơn hàng {{ $order->number }}</h4>
                        <form action="{{ action('Frontend\UserController@editOrder', ['id' => $order->id]) }}" method="post">

                        </form>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop
