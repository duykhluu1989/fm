<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Article;

class PageController extends Controller
{
    public function detailPage($id, $slug)
    {
        $page = Article::select('id', 'name', 'content', 'short_description', 'image', 'group', 'view_count')
            ->where('status', Article::STATUS_PUBLISH_DB)
            ->where('id', $id)
            ->where('slug', $slug)
            ->first();

        if(empty($page) || $page->group == Article::ARTICLE_GROUP_PREPAY_DB)
            return view('frontend.errors.404');

        Utility::viewCount($page, 'view_count', Utility::VIEW_ARTICLE_COOKIE_NAME);

        return $this->page($page);
    }

    public function page($page)
    {
        return view('frontend.pages.detail_page', [
            'page' => $page,
        ]);
    }

    public function adminServicePage()
    {
        $pages = Article::select('id', 'name', 'slug', 'image')
            ->where('status', Article::STATUS_PUBLISH_DB)
            ->where('group', Article::ARTICLE_GROUP_SERVICE_DB)
            ->orderBy('order', 'desc')
            ->get();

        return view('frontend.pages.admin_service_page', [
            'pages' => $pages
        ]);
    }

    public function adminRecruitmentPage()
    {
        $pages = Article::select('id', 'name', 'slug', 'short_description', 'image')
            ->where('status', Article::STATUS_PUBLISH_DB)
            ->where('group', Article::ARTICLE_GROUP_RECRUITMENT_DB)
            ->orderBy('order', 'desc')
            ->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.pages.admin_recruitment_page', [
            'pages' => $pages
        ]);
    }
}