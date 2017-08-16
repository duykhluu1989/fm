@extends('backend.layouts.main')

@section('page_heading', $formTitle)

@section('section')

    <form action="{{ $formAction }}" method="post" id="AdminMenuForm">

        <div class="box box-primary" id="AdminMenuDiv">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>

                <button type="button" class="btn btn-primary pull-right NewMenuButton" data-container="body" data-toggle="popover" data-placement="top" data-content="Menu Mới"><i class="fa fa-plus fa-fw"></i></button>
            </div>
            <div class="box-body">

                @include('backend.themes.partials.list_menu', ['listMenus' => $rootMenus, 'listId' => 'ListMenuItem'])

            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>

                <button type="button" class="btn btn-primary pull-right NewMenuButton" data-container="body" data-toggle="popover" data-placement="top" data-content="Menu Mới"><i class="fa fa-plus fa-fw"></i></button>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

    <div class="modal fade" tabindex="-1" role="dialog" id="MenuModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content box" id="MenuModalContent">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="MenuModalTitle"></h4>
                </div>
                <div class="modal-body">
                    <form id="MenuModalForm">

                        @include('backend.themes.partials.menu_form')

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="MenuModalSubmitButton"></button>
                </div>
            </div>
        </div>
    </div>

@stop

@push('stylesheets')
    <style type="text/css">
        #ListMenuItem, #ListMenuItem ul {
            list-style-type: none;
        }

        .MenuItemPlaceholder {
            outline: 1px dashed #4183C4;
        }

        .mjs-nestedSortable-error {
            background: #fbe3e4;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/jquery.mjs.nestedSortable.js') }}"></script>
    <script type="text/javascript">
        $('#ListMenuItem').nestedSortable({
            maxLevels: {{ $treeMaxLevel }},
            revert: 250,
            forcePlaceholderSize: true,
            handle: 'div',
            helper:	'clone',
            items: 'li',
            placeholder: 'MenuItemPlaceholder',
            tolerance: 'pointer',
            toleranceElement: '> div',
            isTree: true,
            expandOnHover: 700,
            listType: 'ul'
        }).on('click', 'button', function() {
            if($(this).hasClass('EditMenuButton'))
            {
                if($(this).val() != '')
                {
                    var elemIdArr = $(this).prop('id').split('_');

                    $('#AdminMenuDiv').append('' +
                        '<div class="overlay">' +
                        '<i class="fa fa-refresh fa-spin"></i>' +
                        '</div>' +
                    '');

                    $('#MenuModalTitle').html('Chỉnh Sửa Menu');
                    $('#MenuModalSubmitButton').html('Cập Nhật').val('edit_' + elemIdArr[1]);
                    $('#MenuModalForm').prop('action', $(this).val());

                    $.ajax({
                        url: $(this).val(),
                        type: 'get',
                        success: function(result) {
                            if(result)
                            {
                                $('#MenuModalForm').html(result);

                                $('#AdminMenuDiv').find('div[class="overlay"]').first().remove();

                                $('#MenuModal').modal('show');
                            }
                        }
                    });
                }
            }
            else if($(this).hasClass('DeleteMenuButton'))
            {
                if($(this).val() != '')
                {
                    var elem = $(this);

                    $('#AdminMenuDiv').append('' +
                        '<div class="overlay">' +
                        '<i class="fa fa-refresh fa-spin"></i>' +
                        '</div>' +
                    '');

                    $.ajax({
                        url: $(this).val(),
                        type: 'get',
                        success: function(result) {
                            if(result)
                            {
                                elem.parent().parent().parent().remove();

                                $('#AdminMenuDiv').find('div[class="overlay"]').first().remove();
                            }
                        }
                    });
                }
            }
        });

        $('#AdminMenuForm').submit(function(e) {
            $('#ListMenuItem').children().each(function() {
                updateMenuParentIdValue($(this), '');
            });
        });

        function updateMenuParentIdValue(currentElem, parentId)
        {
            currentElem.find('input[type="hidden"]').first().val(parentId);

            var elemIdArr = currentElem.find('button[type="button"]').first().prop('id').split('_');

            currentElem.find('ul').first().children().each(function() {
                updateMenuParentIdValue($(this), elemIdArr[1]);
            });
        }

        $('#MenuModalForm').on('change', 'input[type="radio"][name="type"]', function() {
            var nameFormGroupElem = $('#NameFormGroup');

            if($(this).val() == '{{ \App\Models\Menu::TYPE_STATIC_LINK_DB }}')
            {
                nameFormGroupElem.find('label').first().html('Tên <i>(bắt buộc)</i>');
                nameFormGroupElem.find('input').first().prop('required', 'required');
                $('#UrlFormGroup').show().find('input').first().prop('required', 'required');
                $('#TargetNameFormGroup').hide().find('input').first().removeAttr('required').val('').removeClass('ArticleNameInput');
            }
            else
            {
                nameFormGroupElem.find('label').first().html('Tên');
                nameFormGroupElem.find('input').first().removeAttr('required');
                $('#UrlFormGroup').hide().find('input').first().removeAttr('required').val('');

                var targetNameFormGroupElem = $('#TargetNameFormGroup');
                targetNameFormGroupElem.show();
                targetNameFormGroupElem.find('label').first().html('Trang Tĩnh <i>(bắt buộc)</i>');
                targetNameFormGroupElem.find('input').first().prop('required', 'required').addClass('ArticleNameInput');
            }
        }).on('focusin', 'input', function() {
            if($(this).hasClass('ArticleNameInput'))
            {
                $(this).autocomplete({
                    appendTo: '#MenuModalForm',
                    minLength: 3,
                    delay: 1000,
                    source: function(request, response) {
                        $.ajax({
                            url: '{{ action('Backend\ArticleController@autoCompleteArticle') }}',
                            type: 'post',
                            data: '_token=' + $('input[name="_token"]').first().val() + '&term=' + request.term,
                            success: function(result) {
                                if(result)
                                {
                                    result = JSON.parse(result);
                                    response(result);
                                }
                            }
                        });
                    },
                    select: function(event, ui) {
                        $(this).val(ui.item.name);
                        return false;
                    }
                }).autocomplete('instance')._renderItem = function(ul, item) {
                    return $('<li>').append('<a>' + item.name + '</a>').appendTo(ul);
                };
            }
        });

        $('.NewMenuButton').click(function() {
            $('#MenuModalTitle').html('Menu Mới');
            $('#MenuModalSubmitButton').html('Tạo Mới').val('create');
            $('#MenuModalForm').prop('action', '{{ action('Backend\ThemeController@createMenu') }}');

            clearForm();

            $('#MenuModal').modal('show');
        });

        function clearForm()
        {
            $('#MenuModalForm').find('input[type="text"]').each(function() {
                $(this).val('');
            });
        }

        $('#MenuModalSubmitButton').click(function() {
            var menuModalFormElem = $('#MenuModalForm');

            if(menuModalFormElem[0].checkValidity() == false)
                menuModalFormElem[0].reportValidity();
            else
            {
                $('#MenuModalContent').append('' +
                    '<div class="overlay">' +
                    '<i class="fa fa-refresh fa-spin"></i>' +
                    '</div>' +
                '');

                $.ajax({
                    url: menuModalFormElem.prop('action'),
                    type: 'post',
                    data: '_token=' + $('input[name="_token"]').first().val() + '&' + menuModalFormElem.serialize(),
                    success: function(result) {
                        if(result)
                        {
                            if(result.indexOf('<span class="help-block">') != -1)
                                menuModalFormElem.html(result);
                            else
                            {
                                var menuModalSubmitButton = $('#MenuModalSubmitButton');
                                var menuId = '';
                                if(menuModalSubmitButton.val() != 'create')
                                {
                                    var menuModalSubmitButtonValArr = menuModalSubmitButton.val().split('_');
                                    menuId = menuModalSubmitButtonValArr[1];
                                }

                                if(menuModalSubmitButton.val() == 'create')
                                    $('#ListMenuItem').append(result);
                                else
                                    $('#EditMenuButton_' + menuId).parent().parent().html(result);

                                $('#MenuModal').modal('hide');

                                clearForm();
                            }

                            $('#MenuModalContent').find('div[class="overlay"]').first().remove();
                        }
                    }
                });
            }
        });
    </script>
@endpush