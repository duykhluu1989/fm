<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $table = 'zone';

    public $timestamps = false;

    public function runs()
    {
        return $this->hasMany('App\Models\Run', 'zone_id');
    }

    public function doDelete()
    {
        $this->delete();

        foreach($this->runs as $run)
            $run->doDelete();
    }
}