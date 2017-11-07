<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RunArea extends Model
{
    protected $table = 'run_area';

    public $timestamps = false;

    public function run()
    {
        return $this->belongsTo('App\Models\Run', 'run_id');
    }

    public function area()
    {
        return $this->belongsTo('App\Models\Area', 'area_id');
    }
}