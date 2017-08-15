<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

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
}