@extends('frontend.layouts.main')

@section('page_heading', 'Chi tiết đơn hàng ' . $order->number)

@section('section')

    @include('frontend.layouts.partials.menu')

    <main>

        @include('frontend.users.partials.navigation')

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="title_user line-on-right">Chi tiết đơn hàng {{ $order->number }}</h4>
                        <div class="row">
                            <div class="col-sm-6">
                                <h3>Địa chỉ lấy hàng</h3>
                                <div class="row">
                                    <div class="col-sm-4"><b>Tên</b></div>
                                    <div class="col-sm-8">{{ $order->senderAddress->name }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4"><b>Điện thoại</b></div>
                                    <div class="col-sm-8">{{ $order->senderAddress->phone }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4"><b>Địa chỉ</b></div>
                                    <div class="col-sm-8">{{ $order->senderAddress->address }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4"><b>Thành phố</b></div>
                                    <div class="col-sm-8">{{ $order->senderAddress->province }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4"><b>Quận</b></div>
                                    <div class="col-sm-8">{{ $order->senderAddress->district }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4"><b>Phường</b></div>
                                    <div class="col-sm-8">{{ $order->senderAddress->ward }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <h3>Địa chỉ giao hàng</h3>
                                <div class="row">
                                    <div class="col-sm-4"><b>Tên</b></div>
                                    <div class="col-sm-8">{{ $order->receiverAddress->name }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4"><b>Điện thoại</b></div>
                                    <div class="col-sm-8">{{ $order->receiverAddress->phone }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4"><b>Địa chỉ</b></div>
                                    <div class="col-sm-8">{{ $order->receiverAddress->address }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4"><b>Thành phố</b></div>
                                    <div class="col-sm-8">{{ $order->receiverAddress->province }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4"><b>Quận</b></div>
                                    <div class="col-sm-8">{{ $order->receiverAddress->district }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4"><b>Phường</b></div>
                                    <div class="col-sm-8">{{ $order->receiverAddress->ward }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h3>Thông tin đơn hàng</h3>
                                <div class="row">
                                    <div class="col-sm-3"><b>Đặt đơn hàng lúc</b></div>
                                    <div class="col-sm-9">{{ $order->created_at }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"><b>Mã đơn hàng</b></div>
                                    <div class="col-sm-9">{{ $order->number }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"><b>Trạng thái</b></div>
                                    <div class="col-sm-9"><span class="label label-{{ \App\Models\Order::getOrderStatusLabel($order->status ) }}">{{ $order->status }}</span></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"><b>Trọng lượng (kg)</b></div>
                                    <div class="col-sm-9">{{ $order->weight }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"><b>Kích thước (cm)</b></div>
                                    <div class="col-sm-9">{{ $order->dimension }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"><b>Người trả phí ship</b></div>
                                    <div class="col-sm-9">{{ ($order->shipping_payment == \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB ? 'Người gửi hàng' : 'Người nhận hàng') }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"><b>Tiền thu hộ</b></div>
                                    <div class="col-sm-9">{{ \App\Libraries\Helpers\Utility::formatNumber($order->cod_price) }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"><b>Phí ship</b></div>
                                    <div class="col-sm-9">{{ \App\Libraries\Helpers\Utility::formatNumber($order->shipping_price) }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"><b>Tổng tiền thu hộ</b></div>
                                    <div class="col-sm-9">{{ \App\Libraries\Helpers\Utility::formatNumber($order->total_cod_price) }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"><b>Shipper</b></div>
                                    <div class="col-sm-9">{{ $order->shipper }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"><b>Ghi chú</b></div>
                                    <div class="col-sm-9">{{ $order->note }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"><b>Dịch vụ ứng trước tiền thu hộ</b></div>
                                    <div class="col-sm-9">{{ ($order->prepay == \App\Libraries\Helpers\Utility::ACTIVE_DB ? 'Có sử dụng' : 'Không sử dụng') }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"><b>Đối soát</b></div>
                                    <div class="col-sm-9">{{ ($order->payment == \App\Libraries\Helpers\Utility::ACTIVE_DB ? 'Đã đối soát' : 'Chưa đối soát') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop
