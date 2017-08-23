<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Detrack\Detrack;

class CollectionController extends Controller
{
    public function handleCollectionNotification(Request $request)
    {
        $inputs = $request->all();

        $detrack = Detrack::make();
        $detrack->handleCollectionNotification($inputs);
    }
}