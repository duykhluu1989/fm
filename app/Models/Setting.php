<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected static $settings;

    protected $table = 'setting';

    public $timestamps = false;
}