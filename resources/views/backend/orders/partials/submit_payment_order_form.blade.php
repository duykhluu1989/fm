<div class="row">
    <div class="col-sm-12">
        <div class="form-group{{ $errors->has('amount') ? ' has-error': '' }}">
            <label>Số Tiền</label>
            <span class="form-control no-border">{{ \App\Libraries\Helpers\Utility::formatNumber($order->total_price) . ' VND' }}</span>
            @if($errors->has('amount'))
                <span class="help-block">{{ $errors->first('amount') }}</span>
            @endif
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label>Ghi Chú</label>
            <input type="text" class="form-control" name="note" maxlength="255" value="{{ request()->input('note') }}" />
        </div>
    </div>
</div>