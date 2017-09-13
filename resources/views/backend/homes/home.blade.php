@extends('backend.layouts.main')

@section('page_heading', 'Dashboard')

@section('section')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Lọc Thống Kê</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus fa-fw"></i></button>
            </div>
        </div>
        <form method="get" action="{{ action('Backend\HomeController@home') }}">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Từ Ngày</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control DatePicker" name="created_at_from" value="{{ request('created_at_from') }}" required="required" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Tới Ngày</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control DatePicker" name="created_at_to" value="{{ request('created_at_to') }}" required="required" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer text-center">
                <button type="submit" class="btn btn-primary">Lọc Thống Kê</button>
                <a href="{{ action('Backend\HomeController@home') }}" class="btn btn-default">Hủy Lọc Thống Kê</a>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-inbox"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Tổng Đơn Hàng</span>
                    <span class="info-box-number">{{ \App\Libraries\Helpers\Utility::formatNumber($orders->total) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Hoàn Thành</span>
                    <span class="info-box-number">{{ \App\Libraries\Helpers\Utility::formatNumber($orders->complete) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-times"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Không Giao Được</span>
                    <span class="info-box-number">{{ \App\Libraries\Helpers\Utility::formatNumber($orders->fail) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-trash"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Hủy</span>
                    <span class="info-box-number">{{ \App\Libraries\Helpers\Utility::formatNumber($orders->cancel) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-4 col-xs-6">
                    <div class="description-block">
                        <span class="description-text text-green">Tổng Khối Lượng</span>
                        <h5 class="description-header">{{ $orders->weight }}</h5>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-6">
                    <div class="description-block">
                        <span class="description-text text-green">Tổng Thu Hộ</span>
                        <h5 class="description-header">{{ \App\Libraries\Helpers\Utility::formatNumber($orders->cod_price) }}</h5>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-6">
                    <div class="description-block">
                        <span class="description-text text-green">Tổng Phí Ship</span>
                        <h5 class="description-header">{{ \App\Libraries\Helpers\Utility::formatNumber($orders->shipping_price) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Location</h3>
        </div>
        <div class="box-body">
            <div class="row">

                @foreach($orderByDistricts as $orderByDistrict)
                    <div class="col-sm-3 col-xs-6">
                        <div class="description-block">
                            <span class="description-text text-green">{{ $orderByDistrict->district }}</span>
                        </div>
                        <h4>Tổng Đơn Hàng: <b>{{ \App\Libraries\Helpers\Utility::formatNumber($orderByDistrict->total) }}</b></h4>
                        <h4>Hoàn Thành: <b>{{ \App\Libraries\Helpers\Utility::formatNumber($orderByDistrict->complete) }}</b></h4>
                        <h4>Không Giao Được: <b>{{ \App\Libraries\Helpers\Utility::formatNumber($orderByDistrict->fail) }}</b></h4>
                        <h4>Hủy: <b>{{ \App\Libraries\Helpers\Utility::formatNumber($orderByDistrict->cancel) }}</b></h4>
                        <h4>Tổng Khối Lượng: <b>{{ $orderByDistrict->weight }}</b></h4>
                        <h4>Tổng Thu Hộ: <b>{{ \App\Libraries\Helpers\Utility::formatNumber($orderByDistrict->cod_price) }}</b></h4>
                        <h4>Tổng Phí Ship: <b>{{ \App\Libraries\Helpers\Utility::formatNumber($orderByDistrict->shipping_price) }}</b></h4>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Shipper</h3>
        </div>
        <div class="box-body">
            <div class="row">

                @foreach($orderByShippers as $orderByShipper)
                    @if(!empty($orderByShipper->shipper))
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block">
                                <span class="description-text text-green">{{ $orderByShipper->shipper }}</span>
                            </div>
                            <h4>Tổng Đơn Hàng: <b>{{ \App\Libraries\Helpers\Utility::formatNumber($orderByShipper->total) }}</b></h4>
                            <h4>Hoàn Thành: <b>{{ \App\Libraries\Helpers\Utility::formatNumber($orderByShipper->complete) }}</b></h4>
                            <h4>Không Giao Được: <b>{{ \App\Libraries\Helpers\Utility::formatNumber($orderByShipper->fail) }}</b></h4>
                            <h4>Hủy: <b>{{ \App\Libraries\Helpers\Utility::formatNumber($orderByShipper->cancel) }}</b></h4>
                            <h4>Tổng Khối Lượng: <b>{{ $orderByShipper->weight }}</b></h4>
                            <h4>Tổng Thu Hộ: <b>{{ \App\Libraries\Helpers\Utility::formatNumber($orderByShipper->cod_price) }}</b></h4>
                            <h4>Tổng Phí Ship: <b>{{ \App\Libraries\Helpers\Utility::formatNumber($orderByShipper->shipping_price) }}</b></h4>
                        </div>
                    @endif
                @endforeach

            </div>
        </div>
    </div>

@stop