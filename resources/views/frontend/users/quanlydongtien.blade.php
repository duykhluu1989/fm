@extends('frontend.layouts.main')

@section('page_heading', 'Quản lý dòng tiền')

@section('section')

    @include('frontend.layouts.partials.menu')

    <main>

        @include('frontend.users.partials.navigation')

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="title_user line-on-right">Lượt đối soát sắp tới</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table_QLDH">
                                <thead>
                                <tr>
                                    <th>Loại</th>
                                    <th>Tiền thu hộ</th>
                                    <th>Phí return</th>
                                    <th>Tiền đối soát</th>
                                    <th>Số ĐH</th>
                                    <th>Xem đơn hàng</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Tiền đã thu hộ</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td><a class="btn btn-link" href="#">Xem chi tiết →</a></td>
                                </tr>
                                <tr>
                                    <td>Đơn hàng hoàn trả</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td><a class="btn btn-link" href="#">Xem chi tiết →</a></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <p>Tổng số tiền shop sẽ nhận được trong lần đối soát tới là: <span>0</span> </p>
                        <p>Tổng số hàng shop sẽ được trả lại trong lần đối soát tới là: <span>0</span> đơn hàng </p>
                        <p><b>Tiền đối soát =  Tiền thu hộ + Phí Return (nếu có)</b></p>
                        <h4 class="title_user line-on-right">Lịch sử các lượt đối soát</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table_QLDH">
                                <thead>
                                <tr>
                                    <th>Loại</th>
                                    <th>Tiền thu hộ</th>
                                    <th>Phí return</th>
                                    <th>Tiền đối soát</th>
                                    <th>Số ĐH</th>
                                    <th>Xem đơn hàng</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Tiền đã thu hộ</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td><a class="btn btn-link" href="#">Xem chi tiết →</a></td>
                                </tr>
                                <tr>
                                    <td>Đơn hàng hoàn trả</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td><a class="btn btn-link" href="#">Xem chi tiết →</a></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop

