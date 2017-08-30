@extends('backend.layouts.main')

@section('page_heading', 'Dashboard')

@section('section')

    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-inbox"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Tổng đơn hàng</span>
                    <span class="info-box-number">{{ \App\Libraries\Helpers\Utility::formatNumber($orders->total) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Hoàn thành</span>
                    <span class="info-box-number">{{ \App\Libraries\Helpers\Utility::formatNumber($orders->complete) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-times"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Không giao được</span>
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

@stop