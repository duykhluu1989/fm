@extends('backend.layouts.main')

@section('page_heading', 'Chi Tiết Đơn Hàng - ' . $order->do)

@section('section')

    <div class="box{{ empty($order->cancelled_at) ? ' box-primary' : ' box-danger' }}">
        <div class="box-header with-border">
            @if($order->status == \App\Models\Order::STATUS_COMPLETED_DB)
                @if($order->payment == \App\Models\Order::NOT_PAYMENT_DB)
                    <a href="{{ action('Backend\OrderController@paymentOrder', ['id' => $order->id]) }}" class="btn btn-primary Confirmation">Tiến Hành Đối Soát</a>
                @elseif($order->payment == \App\Models\Order::PROCESSING_PAYMENT_DB)
                    <a href="{{ action('Backend\OrderController@completePaymentOrder', ['id' => $order->id]) }}" class="btn btn-primary Confirmation">Xác Nhận Đối Soát</a>
                @endif
            @elseif($order->status == \App\Models\Order::STATUS_RETURN_DB)
                <button class="btn btn-primary" id="OpenReturnPriceModalButton">Phí Return</button>
            @elseif(\App\Models\Order::getOrderStatusOrder($order->status) <= \App\Models\Order::getOrderStatusOrder(\App\Models\Order::STATUS_INFO_RECEIVED_DB))
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
                    <div class="row">
                        <div class="col-sm-3"><b>Group</b></div>
                        <div class="col-sm-9">{{ $order->user->group }}</div>
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
                        <div class="col-sm-3"><b>DO</b></div>
                        <div class="col-sm-9">{{ $order->do }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>User DO</b></div>
                        <div class="col-sm-9">{{ $order->user_do }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Trạng Thái</b></div>
                        <div class="col-sm-9"><span class="label label-{{ \App\Models\Order::getOrderStatusLabel($order->status ) }}">{{ \App\Models\Order::getOrderStatus($order->status) }}</span></div>
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
                        <div class="col-sm-3"><b>Tổng Tiền</b></div>
                        <div class="col-sm-9">{{ \App\Libraries\Helpers\Utility::formatNumber($order->total_cod_price) }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Phí Return</b></div>
                        <div class="col-sm-9">{{ !empty($order->return_price) ? \App\Libraries\Helpers\Utility::formatNumber($order->return_price) : '' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Shipper Lấy Hàng</b></div>
                        <div class="col-sm-9">{{ $order->collection_shipper }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Shipper Giao Hàng</b></div>
                        <div class="col-sm-9">{{ $order->shipper }}</div>
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
                        <div class="col-sm-9">{{ \App\Models\Order::getOrderPayment($order->payment) }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Thời Gian Đối Soát</b></div>
                        <div class="col-sm-9">{{ $order->payment_completed_at }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Transaction Code</b></div>
                        <div class="col-sm-9">{{ $order->payment_code }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Transaction Note</b></div>
                        <div class="col-sm-9">{{ $order->payment_note }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><b>Job Type</b></div>
                        <div class="col-sm-9">{{ $order->getJobType() }}</div>
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
                            <h3>Delivery</h3>
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
            <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\OrderController@adminOrder')) }}" class="btn btn-default">Quay Lại</a>
        </div>
    </div>

    @if($order->status == \App\Models\Order::STATUS_RETURN_DB)
        <div class="modal fade" tabindex="-1" role="dialog" id="ReturnPriceModal">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content box">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Phí Return</h4>
                    </div>
                    <form action="{{ action('Backend\OrderController@returnPriceOrder', ['id' => $order->id]) }}" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Phí <i>(bắt buộc)</i></label>
                                <input type="text" class="form-control InputForNumber" name="return_price" value="{{ !empty($order->return_price) ? \App\Libraries\Helpers\Utility::formatNumber($order->return_price) : '' }}" required="required" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Xác Nhận</button>
                        </div>
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    @endif

@stop

@if($order->status == \App\Models\Order::STATUS_RETURN_DB)
    @push('scripts')
        <script type="text/javascript">
            $('#OpenReturnPriceModalButton').click(function() {
                $('#ReturnPriceModal').modal('show');
            });
        </script>
    @endpush
@endif
