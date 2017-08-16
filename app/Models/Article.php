<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    const ARTICLE_GROUP_POLICY_DB = 0;
    const ARTICLE_GROUP_SERVICE_DB = 1;
    const ARTICLE_GROUP_RECRUITMENT_DB = 2;
    const ARTICLE_GROUP_PREPAY_DB = 3;
    const ARTICLE_GROUP_POLICY_LABEL = 'Chính Sách';
    const ARTICLE_GROUP_SERVICE_LABEL = 'Dịch Vụ';
    const ARTICLE_GROUP_RECRUITMENT_LABEL = 'Tuyển Dụng';
    const ARTICLE_GROUP_PREPAY_LABEL = 'Ứng Tiền Trước';

    const STATUS_PUBLISH_DB = 2;
    const STATUS_FINISH_DB = 1;
    const STATUS_DRAFT_DB = 0;
    const STATUS_PUBLISH_LABEL = 'Xuất Bản';
    const STATUS_FINISH_LABEL = 'Hoàn Thành';
    const STATUS_DRAFT_LABEL = 'Nháp';

    protected $table = 'article';

    public $timestamps = false;

    public static function getArticleGroup($value = null)
    {
        $group = [
            self::ARTICLE_GROUP_POLICY_DB => self::ARTICLE_GROUP_POLICY_LABEL,
            self::ARTICLE_GROUP_SERVICE_DB => self::ARTICLE_GROUP_SERVICE_LABEL,
            self::ARTICLE_GROUP_RECRUITMENT_DB => self::ARTICLE_GROUP_RECRUITMENT_LABEL,
            self::ARTICLE_GROUP_PREPAY_DB => self::ARTICLE_GROUP_PREPAY_LABEL,
        ];

        if($value !== null && isset($group[$value]))
            return $group[$value];

        return $group;
    }

    public static function getArticleStatus($value = null)
    {
        $status = [
            self::STATUS_DRAFT_DB => self::STATUS_DRAFT_LABEL,
            self::STATUS_FINISH_DB => self::STATUS_FINISH_LABEL,
            self::STATUS_PUBLISH_DB => self::STATUS_PUBLISH_LABEL,
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }
}