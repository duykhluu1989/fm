<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Detrack\Detrack;

class DeliveryController extends Controller
{
    public function addDelivery()
    {

    }

    public function viewDelivery()
    {

    }

    public function editDelivery()
    {

    }

    public function deleteDelivery()
    {

    }

    public function handleDeliveryNotification(Request $request)
    {
        $inputs = $request->all();

        $detrack = Detrack::make();
        $detrack->handleDeliveryNotification($inputs);
    }
}