<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    const TYPE_ARTICLE_DB = 0;
    const TYPE_STATIC_LINK_DB = 1;
    const TYPE_ARTICLE_LABEL = 'Trang Tĩnh';
    const TYPE_STATIC_LINK_LABEL = 'Đường Dẫn Tĩnh';

    const TARGET_ARTICLE_DB = 'article';

    const THEME_POSITION_MENU_DB = 0;
    const THEME_POSITION_FOOTER_DB = 1;

    protected $table = 'menu';

    public $timestamps = false;

    public function parentMenu()
    {
        return $this->belongsTo('App\Models\Menu', 'parent_id');
    }

    public function childrenMenus()
    {
        return $this->hasMany('App\Models\Menu', 'parent_id');
    }

    public function targetInformation()
    {
        return $this->belongsTo('App\Models\Article', 'target_id');
    }

    public static function getMenuType($value = null)
    {
        $type = [
            self::TYPE_ARTICLE_DB => self::TYPE_ARTICLE_LABEL,
            self::TYPE_STATIC_LINK_DB => self::TYPE_STATIC_LINK_LABEL,
        ];

        if($value !== null && isset($type[$value]))
            return $type[$value];

        return $type;
    }

    public function doDelete()
    {
        $this->delete();

        foreach($this->childrenMenus as $menu)
            $menu->doDelete();
    }

    public function getMenuTitle($backend = true)
    {
        $title = '';

        if($backend == true)
        {
            if(!empty($this->name))
                $title .= $this->name . ' - ';

            if(!empty($this->url))
                $title .= $this->url;
            else if(!empty($this->targetInformation))
                $title .= $this->targetInformation->name;
        }
        else
        {
            if(!empty($this->name))
                $title .= $this->name;
            else if(!empty($this->targetInformation))
                $title .= $this->targetInformation->name;
        }

        return $title;
    }

    public static function getMenuTree($themePosition)
    {
        $rootMenus = Menu::select('id', 'name', 'url', 'target_id', 'target', 'type')
            ->whereNull('parent_id')
            ->where('theme_position', $themePosition)
            ->orderBy('position')
            ->get();

        foreach($rootMenus as $rootMenu)
            $rootMenu->lazyLoadChildrenMenus();

        return $rootMenus;
    }

    public function lazyLoadChildrenMenus()
    {
        $this->load(['childrenMenus' => function($query) {
            $query->select('id', 'parent_id', 'name', 'url', 'target_id', 'target', 'type')->orderBy('position');
        }, 'targetInformation' => function($query) {
            $query->select('id', 'name', 'slug');
        }]);

        if(count($this->childrenMenus) > 0)
        {
            foreach($this->childrenMenus as $childMenu)
                $childMenu->lazyLoadChildrenMenus();
        }
    }

    public function getMenuUrl()
    {
        if(!empty($this->url))
            return $this->url;
        else if(!empty($this->targetInformation))
        {
            if($this->target == self::TARGET_ARTICLE_DB)
                return action('Frontend\PageController@detailPage', ['id' => $this->target_id, 'slug' => $this->targetInformation->slug]);
        }
        else
            return 'javascript:void(0)';
    }
}