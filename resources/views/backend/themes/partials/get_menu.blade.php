@if($create)
    <li>
        <div class="input-group">
@endif

            <span class="form-control">{{ $menu->getMenuTitle() }}</span>
            <span class="input-group-addon">{{ \App\Models\Menu::getMenuType($menu->type) }}</span>
            <span class="input-group-btn">
                <button type="button" class="btn btn-default EditMenuButton" id="EditMenuButton_{{ $menu->id }}" value="{{ action('Backend\ThemeController@editMenu', ['id' => $menu->id]) }}"><i class="fa fa-pencil fa-fw"></i></button>
            </span>
            <span class="input-group-btn">
                <button type="button" class="btn btn-default DeleteMenuButton Confirmation" id="DeleteMenuButton_{{ $menu->id }}" value="{{ action('Backend\ThemeController@deleteMenu', ['id' => $menu->id]) }}"><i class="fa fa-trash fa-fw"></i></button>
            </span>
            <input type="hidden" name="parent_id[{{ $menu->id }}]" value="{{ $menu->parent_id }}" />

@if($create)
        </div>
    </li>
@endif