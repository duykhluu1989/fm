<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Run extends Model
{
    protected $table = 'run';

    public $timestamps = false;

    public function zone()
    {
        return $this->belongsTo('App\Models\Zone', 'zone_id');
    }

    public function runAreas()
    {
        return $this->hasMany('App\Models\RunArea', 'run_id');
    }

    public function doDelete()
    {
        $this->delete();

        foreach($this->runAreas as $runArea)
            $runArea->delete();
    }
}