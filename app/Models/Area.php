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
    const TYPE_PROVINCE_LABEL = 'Tỉnh / Thành Phố';
    const TYPE_DISTRICT_LABEL = 'Quận / Huyện';
    const TYPE_WARD_LABEL = 'Phường / Xã';

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

    public static function getAreaType($value = null)
    {
        $type = [
            self::TYPE_PROVINCE_DB => self::TYPE_PROVINCE_LABEL,
            self::TYPE_DISTRICT_DB => self::TYPE_DISTRICT_LABEL,
            self::TYPE_WARD_DB => self::TYPE_WARD_LABEL,
        ];

        if($value !== null && isset($type[$value]))
            return $type[$value];

        return $type;
    }

    public static function initCoreAreas()
    {
        $sql = include(base_path() . '/resources/sqls/area.php');

        DB::statement($sql);
    }

    public static function getProvinces($receiver = false)
    {
        $builder = Area::select('id', 'name')
            ->where('type', self::TYPE_PROVINCE_DB);

        if($receiver == true)
            $builder->where('status', Utility::ACTIVE_DB);

        $provinces = $builder->get();

        return $provinces;
    }

    public static function getDistricts($provinceId, $receiver = false)
    {
        $builder = Area::select('id', 'name')
            ->where('parent_id', $provinceId)
            ->where('type', self::TYPE_DISTRICT_DB);

        if($receiver == true)
            $builder->where('status', Utility::ACTIVE_DB);

        $districts = $builder->get();

        return $districts;
    }

    public static function getWards($districtId, $receiver = false)
    {
        $builder = Area::select('id', 'name')
            ->where('parent_id', $districtId)
            ->where('type', self::TYPE_WARD_DB);

        if($receiver == true)
            $builder->where('status', Utility::ACTIVE_DB);

        $wards = $builder->get();

        return $wards;
    }
}