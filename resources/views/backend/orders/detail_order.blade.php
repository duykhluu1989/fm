@extends('backend.layouts.main')

@section('page_heading', 'Chi Tiết Đơn Hàng - ' . $order->number)

@section('section')

    <div class="box{{ empty($order->cancelled_at) ? ' box-primary' : ' box-danger' }}">
        <div class="box-header with-border">
            @if($order->payment == \App\Libraries\Helpers\Utility::INACTIVE_DB && $order->status == \App\Models\Order::STATUS_COMPLETED_DB)
                <a href="{{ action('Backend\OrderController@paymentOrder', ['id' => $order->id]) }}" class="btn btn-primary Confirmation">Xác Nhận Đối Soát</a>
            @endif

            @if(\App\Models\Order::getOrderStatusOrder($order->status) <= \App\Models\Order::getOrderStatusOrder(\App\Models\Order::STATUS_INFO_RECEIVED_DB))
                <a href="{{ action('Backend\OrderController@editOrder', ['id' => $order->id]) }}" class="btn btn-primary">Sửa Đơn Hàng</a>

                <a href="{{ action('Backend\OrderController@cancelOrder', ['id' => $order->id]) }}" class="btn btn-primary pull-right Confirmation">Hủy</a>
            @endif

            <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\OrderController@adminOrder')) }}" class="btn btn-default">Quay Lại</a>

            @if(!empty($order->cancelled_at))
                <span class="box-title text-danger pull-right">Đơn Hàng Đã Hủy Vào Lúc {{ $order->cancelled_at }}</span>
            @endif
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Thông Tin Khách Hàng</h3>
                    <div class="row">
                        <div class="col-sm-3"><b>Tên</b></div>
                        <div class="col-sm-9">{{ $order->user->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Số Điện Thoại</b></div>
                        <div class="col-sm-9">{{ $order->user->phone }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Email</b></div>
                        <div class="col-sm-9">{{ $order->user->email }}</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <h3>Địa Chỉ Lấy Hàng</h3>
                    <div class="row">
                        <div class="col-sm-4"><b>Tên</b></div>
                        <div class="col-sm-8">{{ $order->senderAddress->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><b>Điện Thoại</b></div>
                        <div class="col-sm-8">{{ $order->senderAddress->phone }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><b>Địa Chỉ</b></div>
                        <div class="col-sm-8">{{ $order->senderAddress->address }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><b>Thành Phố</b></div>
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
                    <h3>Địa Chỉ Giao Hàng</h3>
                    <div class="row">
                        <div class="col-sm-4"><b>Tên</b></div>
                        <div class="col-sm-8">{{ $order->receiverAddress->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><b>Điện Thoại</b></div>
                        <div class="col-sm-8">{{ $order->receiverAddress->phone }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><b>Địa Chỉ</b></div>
                        <div class="col-sm-8">{{ $order->receiverAddress->address }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><b>Thành Phố</b></div>
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
                    <h3>Thông Tin Đơn Hàng</h3>
                    <div class="row">
                        <div class="col-sm-3"><b>Đặt Đơn Hàng Lúc</b></div>
                        <div class="col-sm-9">{{ $order->created_at }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Nguồn</b></div>
                        <div class="col-sm-9">{{ \App\Models\Order::getOrderSource($order->source) }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Mã Đơn Hàng</b></div>
                        <div class="col-sm-9">{{ $order->number }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>DO</b></div>
                        <div class="col-sm-9">{{ $order->do }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Trạng Thái</b></div>
                        <div class="col-sm-9"><span class="label label-{{ \App\Models\Order::getOrderStatusLabel($order->status ) }}">{{ $order->status }}</span></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Trọng Lượng (kg)</b></div>
                        <div class="col-sm-9">{{ $order->weight }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Kích Thước (cm)</b></div>
                        <div class="col-sm-9">{{ $order->dimension }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Người Trả Phí Ship</b></div>
                        <div class="col-sm-9">{{ ($order->shipping_payment == \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB ? 'Người Gửi Hàng' : 'Người Nhận Hàng') }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Tiền Thu Hộ</b></div>
                        <div class="col-sm-9">{{ \App\Libraries\Helpers\Utility::formatNumber($order->cod_price) }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Mã Giảm Giá</b></div>
                        <div class="col-sm-9">{{ !empty($order->discount) ? $order->discount->code : '' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Giảm Giá</b></div>
                        <div class="col-sm-9">{{ \App\Libraries\Helpers\Utility::formatNumber($order->discount_shipping_price) }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Phí Ship</b></div>
                        <div class="col-sm-9">{{ \App\Libraries\Helpers\Utility::formatNumber($order->shipping_price) }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Tổng Tiền Thu Hộ</b></div>
                        <div class="col-sm-9">{{ \App\Libraries\Helpers\Utility::formatNumber($order->total_cod_price) }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Shipper Lấy Hàng</b></div>
                        <div class="col-sm-9">{{ $order->shipper }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Shipper Giao Hàng</b></div>
                        <div class="col-sm-9">{{ $order->collection_shipper }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Ghi Chú</b></div>
                        <div class="col-sm-9">{{ $order->note }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Dịch Vụ Ứng Trước Tiền Thu Hộ</b></div>
                        <div class="col-sm-9">{{ \App\Models\Order::getOrderPrepay($order->prepay) }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Đối Soát</b></div>
                        <div class="col-sm-9">{{ ($order->payment == \App\Libraries\Helpers\Utility::ACTIVE_DB ? 'Đã Đối Soát' : 'Chưa Đối Soát') }}</div>
                    </div>
                </div>
            </div>

            @if(!empty($order->collection_tracking_detail))
                <?php
                $collectionTrackingDetail = json_decode($order->collection_tracking_detail, true);
                ?>
                @if(isset($collectionTrackingDetail['milestones']))
                    <div class="row">
                        <div class="col-sm-12">
                            <h3>Collection</h3>
                            <div class="table-responsive no-padding">
                                <table class="table table-striped table-hover table-condensed">
                                    <thead>
                                    <tr>

                                        @foreach($collectionTrackingDetail['milestones'][0] as $label => $value)
                                            <th>{{ $label }}</th>
                                        @endforeach

                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($collectionTrackingDetail['milestones'] as $milestones)
                                        <tr>
                                            @foreach($milestones as $value)
                                                <td>{{ $value }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if(!empty($order->tracking_detail))
                <?php
                $deliveryTrackingDetail = json_decode($order->tracking_detail, true);
                ?>
                @if(isset($deliveryTrackingDetail['milestones']))
                    <div class="row">
                        <div class="col-sm-12">
                            <h3>Collection</h3>
                            <div class="table-responsive no-padding">
                                <table class="table table-striped table-hover table-condensed">
                                    <thead>
                                    <tr>

                                        @foreach($deliveryTrackingDetail['milestones'][0] as $label => $value)
                                            <th>{{ $label }}</th>
                                        @endforeach

                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($deliveryTrackingDetail['milestones'] as $milestones)
                                        <tr>
                                            @foreach($milestones as $value)
                                                <td>{{ $value }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
        <div class="box-footer">
            @if($order->payment == \App\Libraries\Helpers\Utility::INACTIVE_DB && $order->status == \App\Models\Order::STATUS_COMPLETED_DB)
                <a href="{{ action('Backend\OrderController@paymentOrder', ['id' => $order->id]) }}" class="btn btn-primary Confirmation">Xác Nhận Đối Soát</a>
            @endif

            @if(\App\Models\Order::getOrderStatusOrder($order->status) <= \App\Models\Order::getOrderStatusOrder(\App\Models\Order::STATUS_INFO_RECEIVED_DB))
                <a href="{{ action('Backend\OrderController@editOrder', ['id' => $order->id]) }}" class="btn btn-primary">Sửa Đơn Hàng</a>

                <a href="{{ action('Backend\OrderController@cancelOrder', ['id' => $order->id]) }}" class="btn btn-primary pull-right Confirmation">Hủy</a>
            @endif

            <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\OrderController@adminOrder')) }}" class="btn btn-default">Quay Lại</a>
        </div>
    </div>

@stop
