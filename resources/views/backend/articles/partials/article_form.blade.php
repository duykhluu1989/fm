<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($article->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\ArticleController@adminArticle')) }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($article->id))
            <a href="{{ action('Backend\ArticleController@deleteArticle', ['id' => $article->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Ảnh</label>
                    <div>
                        <button type="button" class="btn btn-default" id="ElFinderPopupOpen"><i class="fa fa-image fa-fw"></i></button>
                        <input type="hidden" name="image" value="{{ old('image', $article->image) }}" />
                        @if(old('image', $article->image))
                            <img src="{{ old('image', $article->image) }}" width="100%" alt="Article Image" />
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                    <label>Tên <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="name" required="required" value="{{ old('name', $article->name) }}" />
                    @if($errors->has('name'))
                        <span class="help-block">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Trạng Thái</label>
                    <?php
                    $status = old('status', $article->status);
                    ?>
                    <div>
                        @foreach(\App\Models\Article::getArticleStatus() as $value => $label)
                            <?php
                            if($value == \App\Models\Article::STATUS_PUBLISH_DB)
                                $optionClass = 'label label-success';
                            else if($value == \App\Models\Article::STATUS_FINISH_DB)
                                $optionClass = 'label label-primary';
                            else
                                $optionClass = 'label label-danger';
                            ?>
                            @if($status == $value)
                                <label class="radio-inline">
                                    <input type="radio" name="status" checked="checked" value="{{ $value }}"><span class="{{ $optionClass }}">{{ $label }}</span>
                                </label>
                            @else
                                <label class="radio-inline">
                                    <input type="radio" name="status" value="{{ $value }}"><span class="{{ $optionClass }}">{{ $label }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Nhóm Trang</label>
                    <select class="form-control" name="group">
                        <option value=""></option>
                        <?php
                        $group = old('group', $article->group);
                        ?>
                        @foreach(\App\Models\Article::getArticleGroup() as $value => $label)
                            @if($group === $value)
                                <option value="{{ $value }}" selected="selected">{{ $label }}</option>
                            @else
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group{{ $errors->has('order') ? ' has-error': '' }}">
                    <label>Thứ Tự <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="order" required="required" value="{{ old('order', $article->order) }}" />
                    @if($errors->has('order'))
                        <span class="help-block">{{ $errors->first('order') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Liên Kết Tĩnh</label>
                    <div class="input-group">
                        @if(empty($article->id))
                            <span class="input-group-addon">{{ url('page') }}/</span>
                        @else
                            <span class="input-group-addon">{{ url('page', ['id' => $article->id]) }}/</span>
                        @endif
                        <input type="text" class="form-control" name="slug" value="{{ old('slug', $article->slug) }}" />
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Mô Tả Ngắn</label>
                    <input type="text" class="form-control" name="short_description" value="{{ old('short_description', $article->short_description) }}" />
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('content') ? ' has-error': '' }}">
                    <label>Nội Dung <i>(bắt buộc)</i></label>
                    @if($errors->has('content'))
                        <span class="help-block">{{ $errors->first('content') }}</span>
                    @endif
                    <textarea class="form-control TextEditorInput" name="content">{{ old('content', $article->content) }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{ empty($article->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\ArticleController@adminArticle')) }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($article->id))
            <a href="{{ action('Backend\ArticleController@deleteArticle', ['id' => $article->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
</div>
{{ csrf_field() }}

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/colorbox.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/jquery.colorbox-min.js') }}"></script>
    <script src="{{ asset('packages/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        var elFinderSelectedFile;

        tinymce.init({
            selector: '.TextEditorInput',
            height: 600,
            theme: 'modern',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools'
            ],
            toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor emoticons',
            image_advtab: true,
            nonbreaking_force_tab: true,
            convert_urls: false,
            file_picker_callback : function(callback, value, meta) {
                tinymce.activeEditor.windowManager.open({
                    file: '{{ action('Backend\ElFinderController@tinymce') }}',
                    title: 'Thư Viện',
                    width: 1200,
                    height: 600
                }, {
                    oninsert: function(file, elf) {
                        var url, reg, info;
                        url = file.url;
                        reg = /\/[^/]+?\/\.\.\//;
                        while(url.match(reg))
                            url = url.replace(reg, '/');
                        info = file.name + ' (' + elf.formatSize(file.size) + ')';
                        if(meta.filetype == 'file')
                            callback(url, {text: info, title: info});
                        if(meta.filetype == 'image')
                            callback(url, {alt: info});
                        if(meta.filetype == 'media')
                            callback(url);
                    }
                });
                return false;
            }
        });

        $('#ElFinderPopupOpen').click(function() {
            elFinderSelectedFile = $(this).parent().find('input').first();

            $.colorbox({
                href: '{{ action('Backend\ElFinderController@popup') }}',
                iframe: true,
                width: '1200',
                height: '600',
                closeButton: false
            });
        });

        function elFinderProcessSelectedFile(fileUrl)
        {
            elFinderSelectedFile.val(fileUrl);

            if(elFinderSelectedFile.parent().find('img').length > 0)
                elFinderSelectedFile.parent().find('img').first().prop('src', fileUrl);
            else
            {
                elFinderSelectedFile.parent().append('' +
                    '<img src="' + fileUrl + '" width="100%" alt="Article Image" />' +
                '');
            }
        }
    </script>
@endpush