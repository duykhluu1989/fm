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
                        <form class="frm_timkiemdonhang" action="" method="POST" role="form">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="">Trạng thái đơn hàng:</label>
                                        <input type="text" name="" id="" class="form-control" value="" title="" placeholder="Chọn trạng thái đơn hàng">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Mã đơn hàng:</label>
                                        <input type="text" name="" id="" class="form-control" value="" title="" placeholder="Nếu nhiều mã đơn hàng cách nhau bởi dấu phẩy">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="">Điện thoại khách hàng:</label>
                                        <input type="text" name="" id="" class="form-control" value="" title="" placeholder="Nếu nhiều số điện thoại khách hàng cách nhau bởi dấu phẩy">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Họ tên khách hàng:</label>
                                        <input type="text" name="" id="" class="form-control" value="" title="" placeholder="Nếu nhiều họ tên khách hàng cách nhau bởi dấu phẩy">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Email khách hàng:</label>
                                        <input type="text" name="" id="" class="form-control" value="" title="" placeholder="Nếu nhiều email khách hàng cách nhau bởi dấu phẩy">
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="">Thời gian tạo đơn hàng:</label>
                                                <input type="text" name="" id="" class="form-control datetime" value="" title="" placeholder="Từ">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="">&nbsp;</label>
                                                <input type="text" name="" id="" class="form-control datetime" value="" title="" placeholder="Đến">
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
                                        <label style="display: block;" for="">Trả ship:</label>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="1" id="" value="" checked="checked">
                                                Tất cả
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="1" id="" value=""">
                                                Shop trả
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="1" id="" value="">
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
                                    <a href="#" class="btn btnTimDH2"><i class="fa fa-search fa-lg" aria-hidden="true"></i> TÌM ĐƠN HÀNG</a>
                                    <a href="#" class="btn btninDH"><i class="fa fa-print fa-lg" aria-hidden="true"></i> IN ĐƠN HÀNG ĐÃ CHỌN</a>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <table class="table table-bordered table-hover table_QLDH">
                            <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Khách hàng</th>
                                <th>Hàng hoá</th>
                                <th>Ghi chú</th>
                                <th>Tiền thu hộ</th>
                                <th>Phí ship</th>
                                <th>Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <a class="label label-danger" href="{{ action('Frontend\UserController@detailOrder', ['id' => $order->id]) }}">{{ $order->number }}</a>
                                    </td>
                                    <td>
                                        <?php
                                        foreach($order->orderAddresses as $orderAddress)
                                        {
                                            if($orderAddress->type == \App\Models\OrderAddress::TYPE_RECEIVER_DB)
                                            {
                                                echo $orderAddress->name;
                                                break;
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach($order->orderItems as $orderItem)
                                            echo $orderItem->quantity . ' x ' . $orderItem->name . '<br />';
                                        ?>
                                    </td>
                                    <td>{{ $order->note }}</td>
                                    <td>{{ $order->cod_price }}</td>
                                    <td>{{ $order->shipping_price }}</td>
                                    <td>{{ $order->status }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <a href="#" class="btn btninDH"><i class="fa fa-print fa-lg" aria-hidden="true"></i> IN ĐƠN HÀNG</a>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop
