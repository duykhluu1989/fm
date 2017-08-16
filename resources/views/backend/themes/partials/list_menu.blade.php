<ul id="{{ $listId }}">
    @foreach($listMenus as $listMenu)
        <li>
            <div class="input-group">
                <span class="form-control">{{ $listMenu->getMenuTitle() }}</span>
                <span class="input-group-addon">{{ \App\Models\Menu::getMenuType($listMenu->type) }}</span>
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default EditMenuButton" id="EditMenuButton_{{ $listMenu->id }}" value="{{ action('Backend\ThemeController@editMenu', ['id' => $listMenu->id]) }}"><i class="fa fa-pencil fa-fw"></i></button>
                </span>
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default DeleteMenuButton Confirmation" id="DeleteMenuButton_{{ $listMenu->id }}" value="{{ action('Backend\ThemeController@deleteMenu', ['id' => $listMenu->id]) }}"><i class="fa fa-trash fa-fw"></i></button>
                </span>
                <input type="hidden" name="parent_id[{{ $listMenu->id }}]" value="{{ $listMenu->parent_id }}" />
            </div>

            @if(count($listMenu->childrenMenus) > 0)

                @include('backend.themes.partials.list_menu', ['listMenus' => $listMenu->childrenMenus, 'listId' => ''])

            @endif

        </li>
    @endforeach
</ul>