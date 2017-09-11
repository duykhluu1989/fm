<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($rule->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\ShippingPriceRuleController@adminShippingPriceRule')) }}" class="btn btn-default">Quay Lại</a>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                    <label>Tên <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="name" required="required" value="{{ old('name', $rule->name) }}" />
                    @if($errors->has('name'))
                        <span class="help-block">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('rule') ? ' has-error': '' }}">
                    <label>Rule <i>(bắt buộc)</i></label>
                    @if($errors->has('rule'))
                        <span class="help-block">{{ $errors->first('rule') }}</span>
                    @endif
                    <div class="no-padding">
                        <table class="table table-bordered table-striped table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>Weight</th>
                                <th>Price</th>
                                <th class="col-sm-1 text-center">
                                    <button type="button" class="btn btn-primary" id="NewWeightPriceRuleButton" data-container="body" data-toggle="popover" data-placement="top" data-content="Thêm Mới"><i class="fa fa-plus fa-fw"></i></button>
                                </th>
                            </tr>
                            </thead>
                            <tbody id="ListWeightPriceRule">

                            @if(empty(old('rule')))
                                <?php
                                $details = array();
                                if(!empty($rule->rule))
                                    $details = json_decode($rule->rule, true);
                                ?>
                                @foreach($details as $detail)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" name="rule[weight][]" value="{{ isset($detail['weight']) ? $detail['weight'] : '' }}" />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control InputForNumber" name="rule[price][]" value="{{ isset($detail['price']) ? \App\Libraries\Helpers\Utility::formatNumber($detail['price']) : '' }}" required="required" />
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-default RemoveWeightPriceRuleButton"><i class="fa fa-trash-o fa-fw"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <?php
                                $details = old('rule');
                                ?>
                                @foreach($details['weight'] as $key => $weight)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" name="rule[weight][]" value="{{ $weight }}" />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control InputForNumber" name="rule[price][]" value="{{ isset($details['price'][$key]) ? $details['price'][$key] : '' }}" required="required" />
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-default RemoveWeightPriceRuleButton"><i class="fa fa-trash-o fa-fw"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('usernames') ? ' has-error': '' }}">
                    <label>Áp Dụng Cho Khách Hàng <i>(ngăn cách nhiều khách hàng bằng dấu ; chấm phẩy)</i></label>
                    <?php
                    $usernames = '';
                    foreach($rule->shippingPriceRuleUsers as $shippingPriceRuleUser)
                    {
                        if($usernames == '')
                            $usernames = $shippingPriceRuleUser->user->username;
                        else
                            $usernames .= ';' . $shippingPriceRuleUser->user->username;
                    }
                    ?>
                    <input type="text" class="form-control" id="UsernamesInput" name="usernames" value="{{ old('usernames', $usernames) }}" />
                    @if($errors->has('usernames'))
                        <span class="help-block">{{ $errors->first('usernames') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('usernames') ? ' has-error': '' }}">
                    <label>Áp Dụng Cho Khu Vực</label>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{ empty($rule->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\ShippingPriceRuleController@adminShippingPriceRule')) }}" class="btn btn-default">Quay Lại</a>
    </div>
</div>
{{ csrf_field() }}

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.tag-editor.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/jquery.caret.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.tag-editor.min.js') }}"></script>
    <script type="text/javascript">
        $('#NewWeightPriceRuleButton').click(function() {
            $('#ListWeightPriceRule').append('' +
                '<tr>' +
                '<td>' +
                '<input type="text" class="form-control" name="rule[weight][]" value="" />' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control InputForNumber" name="rule[price][]" value="" required="required" />' +
                '</td>' +
                '<td class="text-center">' +
                '<button type="button" class="btn btn-default RemoveWeightPriceRuleButton"><i class="fa fa-trash-o fa-fw"></i></button>' +
                '</td>' +
                '</tr>' +
            '');
        });

        $('#ListWeightPriceRule').on('click', 'button', function() {
            if($(this).hasClass('RemoveWeightPriceRuleButton'))
                $(this).parent().parent().remove();
        }).on('keyup', 'input', function() {
            if($(this).hasClass('InputForNumber'))
                $(this).val(formatNumber($(this).val(), '.'));
        });

        $('#UsernamesInput').tagEditor({
            delimiter: ';',
            sortable: false,
            autocomplete: {
                minLength: 3,
                delay: 1000,
                source: function(request, response) {
                    $.ajax({
                        url: '{{ action('Backend\UserController@autoCompleteUser') }}',
                        type: 'post',
                        data: '_token=' + $('input[name="_token"]').first().val() + '&term=' + request.term,
                        success: function(result) {
                            if(result)
                            {
                                result = JSON.parse(result);

                                var users = [];

                                result.forEach(function(elem) {
                                    users.push(elem.username);
                                });

                                response(users);
                            }
                        }
                    });
                }
            }
        });
    </script>
@endpush