<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Libraries\Helpers\Utility;

class Area extends Model
{
    const TYPE_PROVINCE_DB = 0;
    const TYPE_DISTRICT_DB = 1;
    const TYPE_WARD_DB = 2;

    protected static $provinces;

    protected $table = 'area';

    public $timestamps = false;

    public function parentArea()
    {
        return $this->belongsTo('App\Models\Area', 'parent_id');
    }

    public function childrenAreas()
    {
        return $this->hasMany('App\Models\Area', 'parent_id');
    }

    public static function initCoreAreas()
    {
        $sql = require(base_path() . '/resources/sqls/area.php');

        DB::statement($sql);
    }

    public static function getProvinces()
    {
        $provinces = Area::select('id', 'name')->where('type', self::TYPE_PROVINCE_DB)->where('status', Utility::ACTIVE_DB)->get();

        return $provinces;
    }

    public static function getDistricts($provinceId)
    {
        $districts = Area::select('id', 'name')->where('parent_id', $provinceId)->where('status', Utility::ACTIVE_DB)->get();

        return $districts;
    }

    public static function getWards($districtId)
    {
        $wards = Area::select('id', 'name')->where('parent_id', $districtId)->where('status', Utility::ACTIVE_DB)->get();

        return $wards;
    }
}