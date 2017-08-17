@if(!empty($filters))
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Tìm Kiếm</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus fa-fw"></i></button>
            </div>
        </div>
        <form id="FilterForm" method="get" action="{{ url(request()->getPathInfo()) }}">
            <div class="box-body">
                <div class="row">
                    @foreach($filters as $filter)
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-sm-5 control-label">{{ $filter['title'] }}</label>
                                <div class="col-sm-7">
                                    @if($filter['type'] == 'input')
                                        <?php
                                        echo \App\Libraries\Helpers\Html::input((isset($filterValues[$filter['name']]) ? $filterValues[$filter['name']] : ''), [
                                            'type' => 'text',
                                            'class' => 'form-control',
                                            'name' => $filter['name'],
                                        ]);
                                        ?>
                                    @elseif($filter['type'] == 'select')
                                        <?php
                                        echo \App\Libraries\Helpers\Html::select((isset($filterValues[$filter['name']]) ? $filterValues[$filter['name']] : ''), $filter['options'], [
                                            'class' => 'form-control',
                                            'name' => $filter['name'],
                                        ]);
                                        ?>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="box-footer text-center">
                <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                <a href="{{ url(request()->getPathInfo()) }}" class="btn btn-default">Hủy Tìm Kiếm</a>
            </div>
        </form>
    </div>
@endif

<div class="box box-primary">
    <div class="box-header with-border" style="min-height: 54px">
        @if(!empty($tools))
            @foreach($tools as $tool)
                @if(is_callable($tool))
                    <?php
                    call_user_func($tool);
                    ?>
                @endif
            @endforeach
        @endif
        @if($pagination == true)
            <div class="box-tools">

                @include('libraries.widgets.gridViews.pagination')

            </div>
        @endif
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-striped table-hover table-condensed">
            <thead>
            <tr>
                @if($checkbox == true)
                    <th><input type="checkbox" class="GridViewCheckBoxAll" /></th>
                @endif
                @foreach($columns as $column)
                    <th>{{ $column['title'] }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($dataProvider as $row)
                <tr>
                    @if($checkbox == true)
                        <td><input type="checkbox" class="GridViewCheckBox" value="{{ $row->id }}" /></td>
                    @endif
                    @foreach($columns as $column)
                        <td>
                            @if(is_callable($column['data']))
                                <?php
                                call_user_func($column['data'], $row);
                                ?>
                            @else
                                {{ $row->{$column['data']} }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if($pagination == true)
        <div class="box-footer">

            @include('libraries.widgets.gridViews.pagination')

        </div>
    @endif
</div>

@if($checkbox == true)
    @push('scripts')
        <script type="text/javascript">
            $('.GridViewCheckBoxAll').click(function() {
                if($(this).prop('checked'))
                    $('.GridViewCheckBoxControl').show();
                else
                    $('.GridViewCheckBoxControl').hide();

                $('.GridViewCheckBox').prop('checked', $(this).prop('checked'));
            });

            $('.GridViewCheckBox').click(function() {
                if($(this).prop('checked'))
                {
                    $('.GridViewCheckBoxControl').show();

                    var allChecked = true;

                    $('.GridViewCheckBox').each(function() {
                        if(!$(this).prop('checked'))
                        {
                            allChecked = false;
                            return false;
                        }
                    });

                    if(allChecked)
                        $('.GridViewCheckBoxAll').first().prop('checked', $(this).prop('checked'));
                }
                else
                {
                    var noneChecked = true;

                    $('.GridViewCheckBox').each(function() {
                        if($(this).prop('checked'))
                        {
                            noneChecked = false;
                            return false;
                        }
                    });

                    if(noneChecked)
                        $('.GridViewCheckBoxControl').hide();

                    $('.GridViewCheckBoxAll').first().prop('checked', $(this).prop('checked'));
                }
            });

            $('.GridViewCheckBoxControl').click(function() {
                if($(this).val() != '')
                {
                    var ids = '';

                    $('.GridViewCheckBox:checked').each(function() {
                        if($(this).val() != '')
                        {
                            if(ids != '')
                                ids += ';' + $(this).val();
                            else
                                ids = $(this).val();
                        }
                    });

                    if(ids != '')
                        window.location = $(this).val() + '?ids=' + ids;
                }
            });

            $('.GridViewFilterControl').click(function() {
                if($(this).val() != '')
                {
                    var queries = $('#FilterForm').serialize();

                    if(queries != '')
                        window.location = $(this).val() + '?' + queries;
                }
            });
        </script>
    @endpush
@endif