@extends('frontend.layouts.main')

@section('page_heading', 'Tìm đơn hàng theo mã ' . $keyword)

@section('section')

    @include('frontend.layouts.partials.menu')

    <main>

        @include('frontend.users.partials.navigation')

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="title_user line-on-right">Danh sách đơn hàng tìm theo mã {{ $keyword }}</h4>
                        <table class="table table-bordered table-hover table_QLDH">
                            <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>DO</th>
                                <th>Khách hàng</th>
                                <th>Tiền thu hộ</th>
                                <th>Phí ship</th>
                                <th>Shipper</th>
                                <th>Trạng thái</th>
                                <th>Đối soát</th>
                                <th>Đặt đơn hàng lúc</th>
                                <th>Hủy đơn hàng lúc</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <a class="label label-{{ !empty($order->cancelled_at) ? 'danger' : 'success' }}" href="{{ action('Frontend\UserController@detailOrder', ['id' => $order->id]) }}">{{ $order->number }}</a>
                                    </td>
                                    <td>{{ $order->do }}</td>
                                    <td>{{ $order->receiverAddress->name }}</td>
                                    <td>{{ \App\Libraries\Helpers\Utility::formatNumber($order->cod_price) }}</td>
                                    <td>{{ \App\Libraries\Helpers\Utility::formatNumber($order->shipping_price) }}</td>
                                    <td>{{ $order->shipper }}</td>
                                    <td><span class="label label-{{ \App\Models\Order::getOrderStatusLabel($order->status) }}">{{ \App\Models\Order::getOrderStatus($order->status) }}</span></td>
                                    <td>{{ \App\Models\Order::getOrderPayment($order->payment) }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>{{ $order->cancelled_at }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <ul class="pagination">
                                    @if($orders->lastPage() > 1)
                                        @if($orders->currentPage() > 1)
                                            <li><a href="{{ $orders->previousPageUrl() }}">&laquo;</a></li>
                                            <li><a href="{{ $orders->url(1) }}">1</a></li>
                                        @endif

                                        @for($i = 2;$i >= 1;$i --)
                                            @if($orders->currentPage() - $i > 1)
                                                @if($orders->currentPage() - $i > 2 && $i == 2)
                                                    <li>...</li>
                                                    <li><a href="{{ $orders->url($orders->currentPage() - $i) }}">{{ $orders->currentPage() - $i }}</a></li>
                                                @else
                                                    <li><a href="{{ $orders->url($orders->currentPage() - $i) }}">{{ $orders->currentPage() - $i }}</a></li>
                                                @endif
                                            @endif
                                        @endfor

                                        <li class="active"><a href="javascript:void(0)">{{ $orders->currentPage() }}</a></li>

                                        @for($i = 1;$i <= 2;$i ++)
                                            @if($orders->currentPage() + $i < $orders->lastPage())
                                                @if($orders->currentPage() + $i < $orders->lastPage() - 1 && $i == 2)
                                                    <li><a href="{{ $orders->url($orders->currentPage() + $i) }}">{{ $orders->currentPage() + $i }}</a></li>
                                                    <li>...</li>
                                                @else
                                                    <li><a href="{{ $orders->url($orders->currentPage() + $i) }}">{{ $orders->currentPage() + $i }}</a></li>
                                                @endif
                                            @endif
                                        @endfor

                                        @if($orders->currentPage() < $orders->lastPage())
                                            <li><a href="{{ $orders->url($orders->lastPage()) }}">{{ $orders->lastPage() }}</a></li>
                                            <li><a href="{{ $orders->nextPageUrl() }}">&raquo;</a></li>
                                        @endif
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop
