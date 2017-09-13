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
                        <form class="frm_locthoigian" action="{{ action('Frontend\UserController@general') }}" role="form">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Từ ngày</label>
                                        <input name="created_at_from" class="i_calendar form-control DatePicker" value="{{ request()->input('created_at_from') }}" required="required" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Đến ngày</label>
                                        <input name="created_at_to" class="i_calendar form-control DatePicker" value="{{ request()->input('created_at_to') }}" required="required" />
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btnXemBC"><i class="fa fa-newspaper-o fa-lg" aria-hidden="true"></i> XEM BÁO CÁO</button>
                        </form>

                        <h4 class="title_user line-on-right">Báo cáo hiệu quả</h4>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <h3 class="text-center">Tất cả</h3>
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
                                        <td>{{ !empty($user->customerInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->customerInformation->order_count) : 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Hoàn thành</td>
                                        <td>{{ !empty($user->customerInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->customerInformation->complete_order_count) : 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Không giao được</td>
                                        <td>{{ !empty($user->customerInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->customerInformation->fail_order_count) : 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Đơn hàng hủy</td>
                                        <td>{{ !empty($user->customerInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->customerInformation->cancel_order_count) : 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Đang giao hàng</td>
                                        <td>{{ !empty($user->customerInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->customerInformation->order_count - $user->customerInformation->complete_order_count - $user->customerInformation->fail_order_count - $user->customerInformation->cancel_order_count) : 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tỉ lệ hoàn thành</td>
                                        <td>{{ (!empty($user->customerInformation) && !empty($user->customerInformation->order_count)) ? round($user->customerInformation->complete_order_count * 100 / $user->customerInformation->order_count, 2) : 0 }} %</td>
                                    </tr>
                                    <tr>
                                        <td>Tỉ lệ không giao được</td>
                                        <td>{{ (!empty($user->customerInformation) && !empty($user->customerInformation->order_count)) ? round($user->customerInformation->fail_order_count * 100 / $user->customerInformation->order_count, 2) : 0 }} %</td>
                                    </tr>
                                    <tr>
                                        <td>Tỉ lệ hủy</td>
                                        <td>{{ (!empty($user->customerInformation) && !empty($user->customerInformation->order_count)) ? round($user->customerInformation->cancel_order_count * 100 / $user->customerInformation->order_count, 2) : 0 }} %</td>
                                    </tr>
                                    <tr>
                                        <td>Tổng khối lượng</td>
                                        <td>{{ !empty($user->customerInformation) ? $user->customerInformation->total_weight : 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tổng thu hộ</td>
                                        <td>{{ !empty($user->customerInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->customerInformation->total_cod_price) : 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tổng phí ship</td>
                                        <td>{{ !empty($user->customerInformation) ? \App\Libraries\Helpers\Utility::formatNumber($user->customerInformation->total_shipping_price) : 0 }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                @if(!empty(request()->input('created_at_from')) && request()->input('created_at_to'))
                                    <h3 class="text-center">{{ request()->input('created_at_from') . ' - ' . request()->input('created_at_to') }}</h3>
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
                                            <td>{{ \App\Libraries\Helpers\Utility::formatNumber($orders->total) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Hoàn thành</td>
                                            <td>{{ \App\Libraries\Helpers\Utility::formatNumber($orders->complete) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Không giao được</td>
                                            <td>{{ \App\Libraries\Helpers\Utility::formatNumber($orders->fail) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Đơn hàng hủy</td>
                                            <td>{{ \App\Libraries\Helpers\Utility::formatNumber($orders->cancel) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Đang giao hàng</td>
                                            <td>{{ \App\Libraries\Helpers\Utility::formatNumber($orders->total - $orders->complete - $orders->fail - $orders->cancel) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tỉ lệ hoàn thành</td>
                                            <td>{{ !empty($orders->total) ? round($orders->complete * 100 / $orders->total, 2) : 0 }} %</td>
                                        </tr>
                                        <tr>
                                            <td>Tỉ lệ không giao được</td>
                                            <td>{{ !empty($orders->total) ? round($orders->fail * 100 / $orders->total, 2) : 0 }} %</td>
                                        </tr>
                                        <tr>
                                            <td>Tỉ lệ hủy</td>
                                            <td>{{ !empty($orders->total) ? round($orders->cancel * 100 / $orders->total, 2) : 0 }} %</td>
                                        </tr>
                                        <tr>
                                            <td>Tổng khối lượng</td>
                                            <td>{{ $orders->weight }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tổng thu hộ</td>
                                            <td>{{ \App\Libraries\Helpers\Utility::formatNumber($orders->cod_price) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tổng phí ship</td>
                                            <td>{{ \App\Libraries\Helpers\Utility::formatNumber($orders->shipping_price) }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop
