@extends('frontend.layouts.main')

@section('page_heading', 'Quản lý đơn hàng')

@section('section')

    @include('frontend.layouts.partials.menu')

    <main>

        @include('frontend.users.partials.navigation')

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="title_user line-on-right">Danh sách đơn hàng</h4>
                        <p class="quitrinh">Tiếp nhận đơn hàng <span>({{ \App\Libraries\Helpers\Utility::formatNumber($countReceiveOrder) }})</span> → Shipper đang giao <span>({{ \App\Libraries\Helpers\Utility::formatNumber($countShippingOrder) }})</span> → Đơn hàng thành công hoặc đơn hàng thất bại <span>({{ \App\Libraries\Helpers\Utility::formatNumber($countCompleteOrFailOrder) }})</span> → Đơn hàng đang giữ tại kho <span>({{ \App\Libraries\Helpers\Utility::formatNumber($countHoldOrder) }})</span> → Đơn hàng hoàn trả <span>({{ \App\Libraries\Helpers\Utility::formatNumber($countReturnOrder) }})</span> </p>
                        <h4 class="title_user line-on-right">Tìm kiếm đơn hàng</h4>
                        <form class="frm_timkiemdonhang" action="{{ action('Frontend\UserController@adminOrder') }}" method="GET" role="form">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Mã đơn hàng</label>
                                        <input type="text" name="number" class="form-control" value="{{ request()->get('number') }}" placeholder="Nếu nhiều mã đơn hàng cách nhau bởi dấu phẩy" />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Điện thoại khách hàng</label>
                                        <input type="text" name="phone" class="form-control" value="{{ request()->get('phone') }}" placeholder="Nếu nhiều số điện thoại khách hàng cách nhau bởi dấu phẩy" />
                                    </div>
                                    <div class="form-group">
                                        <label>Họ tên khách hàng</label>
                                        <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}" placeholder="Nếu nhiều họ tên khách hàng cách nhau bởi dấu phẩy" />
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label>Thời gian tạo đơn hàng</label>
                                                <input type="text" name="created_at_from" class="i_calendar form-control DatePicker" value="{{ request()->get('created_at_from') }}" placeholder="Từ" />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label>&nbsp;</label>
                                                <input type="text" name="created_at_to" class="i_calendar form-control DatePicker" value="{{ request()->get('created_at_to') }}" placeholder="Đến" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb0">
                                        <label style="display: block">Trả ship</label>
                                        <?php
                                        $shippingPayment = request()->get('shipping_payment');
                                        ?>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="shipping_payment" value=""<?php echo ($shippingPayment === null ? ' checked="checked"' : ''); ?> />
                                                Tất cả
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="shipping_payment" value="{{ \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB }}"<?php echo ($shippingPayment !== null && $shippingPayment == \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB) ? ' checked="checked"' : ''; ?> />
                                                Shop trả
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="shipping_payment" value="{{ \App\Models\Order::SHIPPING_PAYMENT_RECEIVER_DB }}"<?php echo ($shippingPayment !== null && $shippingPayment == \App\Models\Order::SHIPPING_PAYMENT_RECEIVER_DB) ? ' checked="checked"' : ''; ?> />
                                                Khách trả
                                            </label>
                                        </div>
                                    </div>
                                    <hr>
                                    <button type="submit" class="btn btnTimDH2"><i class="fa fa-search fa-lg" aria-hidden="true"></i> TÌM ĐƠN HÀNG</button>
                                    <a href="javascript:void(0)" id="ExportOrderButton" class="btn btninDH"><i class="fa fa-print fa-lg" aria-hidden="true"></i> IN ĐƠN HÀNG ĐÃ CHỌN</a>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <table class="table table-bordered table-hover table_QLDH">
                            <thead>
                            <tr>
                                <th><input type="checkbox" class="GridViewCheckBoxAll" /></th>
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
                                    <td><input type="checkbox" class="GridViewCheckBox" value="{{ $order->id }}" /></td>
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

@push('scripts')
    <script type="text/javascript">
        $('.GridViewCheckBoxAll').click(function() {
            $('.GridViewCheckBox').prop('checked', $(this).prop('checked'));
        });

        $('.GridViewCheckBox').click(function() {
            if($(this).prop('checked'))
            {
                var allChecked = true;

                $('.GridViewCheckBox').each(function() {
                    if(!$(this).prop('checked'))
                    {
                        allChecked = false;
                        return false;
                    }
                });

                if(allChecked)
                    $('.GridViewCheckBoxAll').first().prop('checked', $(this).prop('checked'));
            }
            else
            {
                var noneChecked = true;

                $('.GridViewCheckBox').each(function() {
                    if($(this).prop('checked'))
                    {
                        noneChecked = false;
                        return false;
                    }
                });

                $('.GridViewCheckBoxAll').first().prop('checked', $(this).prop('checked'));
            }
        });

        $('#ExportOrderButton').click(function() {
            var ids = '';

            $('.GridViewCheckBox:checked').each(function() {
                if($(this).val() != '')
                {
                    if(ids != '')
                        ids += ';' + $(this).val();
                    else
                        ids = $(this).val();
                }
            });

            if(ids != '')
                window.location = '{{ action('Frontend\UserController@exportOrder') }}?ids=' + ids;
        });
    </script>
@endpush
