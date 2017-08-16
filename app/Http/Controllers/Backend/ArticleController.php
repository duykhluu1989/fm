<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Models\Article;

class ArticleController extends Controller
{
    public function adminArticle(Request $request)
    {
        $dataProvider = Article::select('id', 'name', 'status', 'view_count', 'order', 'group')
            ->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('name', 'like', '%' . $inputs['name'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('status', $inputs['status']);

            if(isset($inputs['group']) && $inputs['group'] !== '')
                $dataProvider->where('group', $inputs['group']);
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\ArticleController@editArticle', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Thứ Tự',
                'data' => 'order',
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = Article::getArticleStatus($row->status);
                    if($row->status == Article::STATUS_PUBLISH_DB)
                        echo Html::span($status, ['class' => 'label label-success']);
                    else if($row->status == Article::STATUS_FINISH_DB)
                        echo Html::span($status, ['class' => 'label label-primary']);
                    else
                        echo Html::span($status, ['class' => 'label label-danger']);
                },
            ],
            [
                'title' => 'Nhóm Trang',
                'data' => function($row) {
                    if($row->group !== null)
                        echo Article::getArticleGroup($row->group);
                },
            ],
            [
                'title' => 'Lượt Xem',
                'data' => function($row) {
                    echo Utility::formatNumber($row->view_count);
                },
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setCheckbox();
        $gridView->setFilters([
            [
                'title' => 'Tên',
                'name' => 'name',
                'type' => 'input',
            ],
            [
                'title' => 'Trạng Thái',
                'name' => 'status',
                'type' => 'select',
                'options' => Article::getArticleStatus(),
            ],
            [
                'title' => 'Nhóm Trang',
                'name' => 'group',
                'type' => 'select',
                'options' => Article::getArticleGroup(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.articles.admin_article', [
            'gridView' => $gridView,
        ]);
    }

    public function createArticle(Request $request)
    {
        Utility::setBackUrlCookie($request, '/admin/article?');

        $article = new Article();
        $article->status = Article::STATUS_DRAFT_DB;
        $article->group = null;
        $article->order = 1;

        return $this->saveArticle($request, $article);
    }

    public function editArticle(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/article?');

        $article = Article::find($id);

        if(empty($article))
            return view('backend.errors.404');

        return $this->saveArticle($request, $article, false);
    }

    protected function saveArticle($request, $article, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required|unique:article,name' . ($create == true ? '' : (',' . $article->id)),
                'content' => 'required',
                'slug' => 'nullable|unique:article,slug' . ($create == true ? '' : (',' . $article->id)),
                'order' => 'required|integer|min:1',
            ]);

            if($validator->passes())
            {
                $article->image = $inputs['image'];
                $article->name = $inputs['name'];
                $article->status = $inputs['status'];
                $article->short_description = $inputs['short_description'];
                $article->order = $inputs['order'];

                if(isset($inputs['group']) && $inputs['group'] !== '')
                    $article->group = $inputs['group'];
                else
                    $article->group = null;

                if(empty($inputs['slug']))
                    $article->slug = str_slug($article->name);
                else
                    $article->slug = str_slug($inputs['slug']);

                if($create == true)
                    $article->created_at = date('Y-m-d H:i:s');
                
                $article->content = $inputs['content'];
                $article->save();

                return redirect()->action('Backend\ArticleController@editArticle', ['id' => $article->id])->with('messageSuccess', 'Thành Công');
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\ArticleController@createArticle')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\ArticleController@editArticle', ['id' => $article->id])->withErrors($validator)->withInput();
            }
        }

        if($create == true)
        {
            return view('backend.articles.create_article', [
                'article' => $article,
            ]);
        }
        else
        {
            return view('backend.articles.edit_article', [
                'article' => $article,
            ]);
        }
    }

    public function autoCompleteArticle(Request $request)
    {
        $term = $request->input('term');

        $builder = Article::select('id', 'name')->where('name', 'like', '%' . $term . '%')->limit(Utility::AUTO_COMPLETE_LIMIT);

        $articles = $builder->get()->toJson();

        return $articles;
    }

    public function deleteArticle($id)
    {
        $article = Article::find($id);

        if(empty($article))
            return view('backend.errors.404');

        $article->delete();

        return redirect(Utility::getBackUrlCookie(action('Backend\ArticleController@adminArticle')))->with('messageSuccess', 'Thành Công');
    }

    public function controlDeleteArticle(Request $request)
    {
        $ids = $request->input('ids');

        $articles = Article::whereIn('id', explode(';', $ids))->get();

        foreach($articles as $article)
            $article->delete();

        if($request->headers->has('referer'))
            return redirect($request->headers->get('referer'))->with('messageSuccess', 'Thành Công');
        else
            return redirect()->action('Backend\ArticleController@adminArticle')->with('messageSuccess', 'Thành Công');
    }
}