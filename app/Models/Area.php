<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Helpers\Utility;
use App\Libraries\Helpers\Area as LibraryArea;

class Area extends Model
{
    const TYPE_PROVINCE_DB = 0;
    const TYPE_DISTRICT_DB = 1;

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
        foreach(LibraryArea::$provinces as $province)
        {
            $provinceArea = new Area();
            $provinceArea->name = $province['name'];
            $provinceArea->type = self::TYPE_PROVINCE_DB;
            $provinceArea->status = Utility::ACTIVE_DB;
            $provinceArea->shipping_price = (isset($province['price']) ? $province['price'] : 0);
            $provinceArea->save();

            foreach($province['cities'] as $district)
            {
                $districtArea = new Area();
                $districtArea->parent_id = $provinceArea->id;
                $districtArea->name = (is_array($district) ? $district['name'] : $district);
                $districtArea->type = self::TYPE_DISTRICT_DB;
                $districtArea->status = Utility::ACTIVE_DB;
                $districtArea->shipping_price = (is_array($district) ? $district['price'] : 0);
                $districtArea->save();
            }
        }
    }

    public static function getProvinces($id = null)
    {
        if(empty(self::$provinces))
        {
            $provinces = Area::with(['childrenAreas' => function($query) {
                $query->select('id', 'parent_id', 'name', 'shipping_price')
                    ->where('status', Utility::ACTIVE_DB);
            }])->select('id', 'name', 'shipping_price')
                ->where('type', self::TYPE_PROVINCE_DB)
                ->where('status', Utility::ACTIVE_DB)
                ->get()
                ->keyBy('id')
                ->toArray();

            self::$provinces = $provinces;
        }

        if($id !== null && isset(self::$provinces[$id]))
            return self::$provinces[$id];

        return self::$provinces;
    }

    public static function getDistricts($provinceId, $id = null)
    {
        if(empty(self::$provinces))
            $provinces = self::getProvinces();
        else
            $provinces = self::$provinces;

        if(isset($provinces[$provinceId]))
        {
            if($id !== null)
            {
                foreach($provinces[$provinceId]['children_areas'] as $district)
                {
                    if($district['id'] == $id)
                        return $district;
                }
            }

            return $provinces[$provinceId]['children_areas'];
        }

        return null;
    }
}