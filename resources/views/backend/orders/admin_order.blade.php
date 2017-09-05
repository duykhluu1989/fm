@extends('backend.layouts.main')

@section('page_heading', 'Đơn Hàng')

@section('section')

    <?php

    $gridView->setTools([
        function() {
            echo \App\Libraries\Helpers\Html::button(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-check fa-fw']), [
                'id' => 'UploadCompletePaymentOrderButton',
                'class' => 'btn btn-primary',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Xác Nhận Đối Soát',
            ]);
        },
        function() {
            echo \App\Libraries\Helpers\Html::button(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-money fa-fw']), [
                'class' => 'btn btn-primary GridViewCheckBoxControl Confirmation',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Tiến Hành Đối Soát',
                'value' => action('Backend\OrderController@controlPaymentOrder'),
                'style' => 'display: none',
            ]);
        },
        function() {
            echo \App\Libraries\Helpers\Html::button(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-trash fa-fw']), [
                'class' => 'btn btn-primary GridViewCheckBoxControl Confirmation',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Hủy',
                'value' => action('Backend\OrderController@controlCancelOrder'),
                'style' => 'display: none',
            ]);
        },
        function() {
            echo \App\Libraries\Helpers\Html::button(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-download fa-fw']), [
                'class' => 'btn btn-primary GridViewCheckBoxControl',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Xuất Excel',
                'value' => action('Backend\OrderController@controlExportOrder'),
                'style' => 'display: none',
            ]);
        },
    ]);

    $gridView->render();

    ?>

    <div class="modal fade" tabindex="-1" role="dialog" id="UploadCompletePaymentOrderModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content box">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Xác Nhận Đối Soát</h4>
                </div>
                <form action="{{ action('Backend\OrderController@uploadCompletePaymentOrder') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>File Excel <i>(bắt buộc)</i></label>
                            <input type="file" name="file" required="required" accept="{{ implode(', ', \App\Libraries\Helpers\Utility::getValidExcelExt(true)) }}" />
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

@stop

@push('scripts')
    <script type="text/javascript">
        $('#UploadCompletePaymentOrderButton').click(function() {
            $('#UploadCompletePaymentOrderModal').modal('show');
        });
    </script>
@endpush