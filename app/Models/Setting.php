<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    const WEB_TITLE = 'web_title';
    const WEB_DESCRIPTION = 'web_description';
    const WEB_KEYWORD = 'web_keyword';
    const HOT_LINE = 'hot_line';
    const CONTACT_EMAIL = 'contact_email';
    const WORKING_TIME = 'working_time';

    const TYPE_STRING_DB = 0;
    const TYPE_INT_DB = 1;
    const TYPE_JSON_DB = 2;

    const CATEGORY_GENERAL_DB = 0;

    protected static $settings;

    protected $table = 'setting';

    public $timestamps = false;

    public static function initCoreSettings()
    {
        $coreSettings = [
            [self::WEB_TITLE, 'Tiêu Đề Website', self::TYPE_STRING_DB, 'parcelpost', self::CATEGORY_GENERAL_DB],
            [self::WEB_DESCRIPTION, 'Mô Tả Website', self::TYPE_STRING_DB, 'parcelpost', self::CATEGORY_GENERAL_DB],
            [self::WEB_KEYWORD, 'Từ Khóa', self::TYPE_STRING_DB, 'parcelpost', self::CATEGORY_GENERAL_DB],
            [self::HOT_LINE, 'Hot Line', self::TYPE_STRING_DB, '', self::CATEGORY_GENERAL_DB],
            [self::CONTACT_EMAIL, 'Email Liên Hệ', self::TYPE_STRING_DB, '', self::CATEGORY_GENERAL_DB],
            [self::WORKING_TIME, 'Thời Gian Làm Việc', self::TYPE_STRING_DB, '', self::CATEGORY_GENERAL_DB],
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