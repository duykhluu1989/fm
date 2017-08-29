<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    const WEB_TITLE = 'web_title';
    const WEB_DESCRIPTION = 'web_description';
    const WEB_KEYWORD = 'web_keyword';
    const WEB_LOGO = 'web_logo';
    const WEB_BACKGROUND = 'web_background';
    const HOT_LINE = 'hot_line';
    const CONTACT_EMAIL = 'contact_email';
    const WORKING_TIME = 'working_time';
    const ABOUT_US = 'about_us';

    const DETRACK_API_KEY = 'detrack_api_key';
    const DETRACK_WEB_HOOK_KEY = 'detrack_web_hook_key';

    const FACEBOOK_APP_ID = 'facebook_app_id';
    const FACEBOOK_APP_SECRET = 'facebook_app_secret';
    const FACEBOOK_GRAPH_VERSION = 'facebook_graph_version';
    const FACEBOOK_PAGE_URL = 'facebook_page_url';

    const TYPE_STRING_DB = 0;
    const TYPE_INT_DB = 1;
    const TYPE_JSON_DB = 2;
    const TYPE_IMAGE_DB = 3;

    const CATEGORY_GENERAL_DB = 0;
    const CATEGORY_API_DB = 1;
    const CATEGORY_SOCIAL_DB = 2;

    protected static $settings;

    protected $table = 'setting';

    public $timestamps = false;

    public static function initCoreSettings()
    {
        $coreSettings = [
            [self::WEB_TITLE, 'Tiêu Đề Website', self::TYPE_STRING_DB, 'parcelpost', self::CATEGORY_GENERAL_DB],
            [self::WEB_DESCRIPTION, 'Mô Tả Website', self::TYPE_STRING_DB, 'parcelpost', self::CATEGORY_GENERAL_DB],
            [self::WEB_KEYWORD, 'Từ Khóa', self::TYPE_STRING_DB, 'parcelpost', self::CATEGORY_GENERAL_DB],
            [self::WEB_LOGO, 'Logo', self::TYPE_IMAGE_DB, '', self::CATEGORY_GENERAL_DB],
            [self::WEB_BACKGROUND, 'Background', self::TYPE_IMAGE_DB, '', self::CATEGORY_GENERAL_DB],
            [self::HOT_LINE, 'Hot Line', self::TYPE_STRING_DB, '', self::CATEGORY_GENERAL_DB],
            [self::CONTACT_EMAIL, 'Email Liên Hệ', self::TYPE_STRING_DB, 'info@parcelpost.vn', self::CATEGORY_GENERAL_DB],
            [self::WORKING_TIME, 'Thời Gian Làm Việc', self::TYPE_STRING_DB, '', self::CATEGORY_GENERAL_DB],
            [self::ABOUT_US, 'Về Chúng Tôi', self::TYPE_STRING_DB, '', self::CATEGORY_GENERAL_DB],

            [self::DETRACK_API_KEY, 'Detrack Api Key', self::TYPE_STRING_DB, '', self::CATEGORY_API_DB],
            [self::DETRACK_WEB_HOOK_KEY, 'Detrack Web Hook Key', self::TYPE_STRING_DB, '', self::CATEGORY_API_DB],

            [self::FACEBOOK_APP_ID, 'Facebook App Id', self::TYPE_STRING_DB, '', self::CATEGORY_SOCIAL_DB],
            [self::FACEBOOK_APP_SECRET, 'Facebook App Secret', self::TYPE_STRING_DB, '', self::CATEGORY_SOCIAL_DB],
            [self::FACEBOOK_GRAPH_VERSION, 'Facebook Graph Version', self::TYPE_STRING_DB, 'v2.9', self::CATEGORY_SOCIAL_DB],
            [self::FACEBOOK_PAGE_URL, 'Facebook Page', self::TYPE_STRING_DB, '', self::CATEGORY_SOCIAL_DB],
        ];

        foreach($coreSettings as $coreSetting)
        {
            $setting = new Setting();
            $setting->code = $coreSetting[0];
            $setting->name = $coreSetting[1];
            $setting->type = $coreSetting[2];
            $setting->value = $coreSetting[3];
            $setting->category = $coreSetting[4];
            $setting->save();
        }
    }

    public static function getSettings($category = self::CATEGORY_GENERAL_DB, $code = null)
    {
        if(empty(self::$settings) || !isset(self::$settings[$category]))
        {
            $settings = Setting::where('category', $category)->get();

            foreach($settings as $setting)
                self::$settings[$category][$setting->code] = $setting;
        }

        if($code != null)
        {
            if(isset(self::$settings[$category][$code]))
            {
                if(self::$settings[$category][$code]->type == self::TYPE_JSON_DB)
                    return json_decode(self::$settings[$category][$code]->value, true);
                else
                    return self::$settings[$category][$code]->value;
            }
        }

        return self::$settings[$category];
    }
}