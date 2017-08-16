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
                        <p class="quitrinh">Tiếp nhận đơn hàng<span>(0)</span> → Shipper đang giao <span>(0)</span> → Đơn hàng thành công hoặc đơn hàng thất bại <span>(0)</span> → Đơn hàng đang giữ tại kho <span>(0)</span> → Đơn hàng hoàn trả <span>(0)</span> </p>
                        <h4 class="title_user line-on-right">Tìm kiếm đơn hàng</h4>
                        <form class="frm_timkiemdonhang" action="{{ action('Frontend\UserController@adminOrder') }}" method="GET" role="form">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Trạng thái đơn hàng</label>
                                        <select name="status" class="form-control">
                                            <option value="">Chọn trạng thái đơn hàng</option>
                                            @foreach(\App\Models\Order::getOrderStatus() as $value)
                                                @if(request()->get('status') !== null && request()->get('status') == $value)
                                                    <option selected="selected" value="{{ $value }}">{{ $value }}</option>
                                                @else
                                                    <option value="{{ $value }}">{{ $value }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
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
                                                <input type="text" name="created_at_from" class="form-control datetime" value="{{ request()->get('created_at_from') }}" placeholder="Từ" />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label>&nbsp;</label>
                                                <input type="text" name="created_at_to" class="form-control datetime" value="{{ request()->get('created_at_to') }}" placeholder="Đến" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="">Thời gian đối soát:</label>
                                                <input type="text" name="" id="" class="form-control datetime" value="" title="" placeholder="Từ">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="">&nbsp;</label>
                                                <input type="text" name="" id="" class="form-control datetime" value="" title="" placeholder="Đến">
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
                                                <input type="radio" name="shipping_payment" value=""<?php echo $shippingPayment === '' ? ' checked="checked"' : ''; ?> />
                                                Tất cả
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="shipping_payment" value="{{ \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB }}"<?php echo ($shippingPayment !== '' && $shippingPayment == \App\Models\Order::SHIPPING_PAYMENT_SENDER_DB) ? ' checked="checked"' : ''; ?> />
                                                Shop trả
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="shipping_payment" value="{{ \App\Models\Order::SHIPPING_PAYMENT_RECEIVER_DB }}"<?php echo ($shippingPayment !== '' && $shippingPayment == \App\Models\Order::SHIPPING_PAYMENT_RECEIVER_DB) ? ' checked="checked"' : ''; ?> />
                                                Khách trả
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="">
                                                Đơn hàng đã huỷ
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="">
                                                Đã trả hàng
                                            </label>
                                        </div>
                                    </div>
                                    <hr>
                                    <button type="submit" class="btn btnTimDH2"><i class="fa fa-search fa-lg" aria-hidden="true"></i> TÌM ĐƠN HÀNG</button>
                                    <a href="javascript:void(0)" class="btn btninDH"><i class="fa fa-print fa-lg" aria-hidden="true"></i> IN ĐƠN HÀNG ĐÃ CHỌN</a>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <table class="table table-bordered table-hover table_QLDH">
                            <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Khách hàng</th>
                                <th>Tiền thu hộ</th>
                                <th>Phí ship</th>
                                <th>Shipper</th>
                                <th>Trạng thái</th>
                                <th>Đặt đơn hàng lúc</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <a class="label label-danger" href="{{ action('Frontend\UserController@detailOrder', ['id' => $order->id]) }}">{{ $order->number }}</a>
                                    </td>
                                    <td>{{ $order->receiverAddress->name }}</td>
                                    <td>{{ $order->cod_price }}</td>
                                    <td>{{ $order->shipping_price }}</td>
                                    <td>{{ $order->shipper }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td>{{ $order->created_at }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <a href="javascript:void(0)" class="btn btninDH"><i class="fa fa-print fa-lg" aria-hidden="true"></i> IN ĐƠN HÀNG</a>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop
