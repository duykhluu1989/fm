@extends('frontend.layouts.main')

@section('page_heading', 'Tổng quan chung')

@section('section')

    @include('frontend.layouts.partials.menu')

    <main>

        @include('frontend.users.partials.navigation')

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="title_user line-on-right">Lọc theo thời gian</h4>
                        <form class="frm_locthoigian" action="" method="POST" role="form">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <select name="" id="" class="i_calendar form-control" required="required">
                                            <option value="">Từ 2017-08-01 đến 2017-08-02</option>
                                            <option value="">Từ 2017-08-01 đến 2017-08-02</option>
                                            <option value="">Từ 2017-08-01 đến 2017-08-02</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6"></div>
                            </div>
                            <button type="submit" class="btn btnXemBC"><i class="fa fa-newspaper-o fa-lg" aria-hidden="true"></i> XEM BÁO CÁO</button>
                        </form>

                        <h4 class="title_user line-on-right">Báo cáo hiệu quả</h4>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <table class="table table-bordered table-hover table_tongquanchung">
                                    <thead>
                                    <tr>
                                        <th>Trạng thái</th>
                                        <th>Số đơn hàng</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Số đơn hàng</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <td>Hoàn thành</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <td>Không giao được</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <td>Đơn hàng hủy</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <td>Đang giao hàng</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <td>Tỉ lệ hoàn thành</td>
                                        <td>0%</td>
                                    </tr>
                                    <tr>
                                        <td>Tỉ lệ không giao được</td>
                                        <td>0%</td>
                                    </tr>
                                    <tr>
                                        <td>Tỉ lệ hủy</td>
                                        <td>0%</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <table class="table table-bordered table-hover table_tongquanchung">
                                    <thead>
                                    <tr>
                                        <th>Trạng thái</th>
                                        <th>Số đơn hàng</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Số đơn hàng</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <td>Hoàn thành</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <td>Không giao được</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <td>Đơn hàng hủy</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <td>Đang giao hàng</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <td>Tỉ lệ hoàn thành</td>
                                        <td>0%</td>
                                    </tr>
                                    <tr>
                                        <td>Tỉ lệ không giao được</td>
                                        <td>0%</td>
                                    </tr>
                                    <tr>
                                        <td>Tỉ lệ hủy</td>
                                        <td>0%</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop
