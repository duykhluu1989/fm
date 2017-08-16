<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Article;

class PageController extends Controller
{
    public function gioithieu()
    {
        return view('frontend.pages.gioithieu');
    }

    public function banggia()
    {
        return view('frontend.pages.banggia');
    }

    public function chinhsach()
    {
        return view('frontend.pages.chinhsach');
    }

    public function dichvu()
    {
        return view('frontend.pages.dichvu');
    }

    public function tuyendung()
    {
        return view('frontend.pages.tuyendung');
    }

    public function tuyenchungchitiet()
    {
        return view('frontend.pages.tuyendungchitiet');
    }

    public function detailPage($id, $slug)
    {
        $page = Article::select('id', 'name', 'content', 'short_description', 'image', 'group', 'view_count')
            ->where('status', Article::STATUS_PUBLISH_DB)
            ->where('id', $id)
            ->where('slug', $slug)
            ->first();

        if(empty($page))
            return view('frontend.errors.404');

        Utility::viewCount($page, 'view_count', Utility::VIEW_ARTICLE_COOKIE_NAME);

        if($page->group !== null)
        {
            switch($page->group)
            {
                case Article::ARTICLE_GROUP_POLICY_DB:

                    return $this->detailPolicyPage($page);

                    break;
            }
        }

        return $this->page($page);
    }

    public function detailPolicyPage($page)
    {
        return view('frontend.pages.detail_policy_page', [
            'page' => $page,
        ]);
    }
}