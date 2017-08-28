<?php
$type = request()->input('type', $menu->type);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}" id="NameFormGroup">
            <label>Tên<?php echo ($type == \App\Models\Menu::TYPE_STATIC_LINK_DB ? ' <i>(bắt buộc)</i>' : ''); ?></label>
            <input type="text" class="form-control" name="name" value="{{ request()->input('name', $menu->name) }}"<?php echo ($type == \App\Models\Menu::TYPE_STATIC_LINK_DB ? ' required="required"' : ''); ?> />
            @if($errors->has('name'))
                <span class="help-block">{{ $errors->first('name') }}</span>
            @endif
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label>Loại</label>
            <div>
                @foreach(\App\Models\Menu::getMenuType() as $value => $label)
                    @if($type == $value)
                        <label class="radio-inline">
                            <input type="radio" name="type" checked="checked" value="{{ $value }}">{{ $label }}
                        </label>
                    @else
                        <label class="radio-inline">
                            <input type="radio" name="type" value="{{ $value }}">{{ $label }}
                        </label>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-sm-12" id="UrlFormGroup" style="{{ $type != \App\Models\Menu::TYPE_STATIC_LINK_DB ? 'display: none' : '' }}">
        <div class="form-group{{ $errors->has('url') ? ' has-error': '' }}">
            <label>Đường Dẫn Tĩnh <i>(bắt buộc)</i></label>
            <input type="text" class="form-control" name="url" value="{{ request()->input('url', $menu->url) }}"<?php echo ($type == \App\Models\Menu::TYPE_STATIC_LINK_DB ? ' required="required"' : ''); ?> />
            @if($errors->has('url'))
                <span class="help-block">{{ $errors->first('url') }}</span>
            @endif
        </div>
    </div>
    <div class="col-sm-12" id="TargetNameFormGroup" style="{{ $type == \App\Models\Menu::TYPE_STATIC_LINK_DB ? 'display: none' : '' }}">
        <div class="form-group{{ $errors->has('target_name') ? ' has-error': '' }}">
            <label>Trang Tĩnh <i>(bắt buộc)</i></label>
            <input type="text" class="form-control{{ $type == \App\Models\Menu::TYPE_ARTICLE_DB ? ' ArticleNameInput' : '' }}" name="target_name" value="{{ request()->input('target_name', (!empty($menu->targetInformation) ? $menu->targetInformation->name : '')) }}"<?php echo ($type == \App\Models\Menu::TYPE_ARTICLE_DB ? ' required="required"' : ''); ?> />
            @if($errors->has('target_name'))
                <span class="help-block">{{ $errors->first('target_name') }}</span>
            @endif
        </div>
    </div>
</div>

<input type="hidden" name="theme_position" value="{{ request()->input('theme_position', $menu->theme_position) }}" />