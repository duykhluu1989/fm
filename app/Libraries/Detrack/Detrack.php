<?php

namespace App\Libraries\Detrack;

class Detrack
{
    protected static $detrack;

    protected function __construct()
    {

    }

    public static function make()
    {
        if(empty(self::$detrack))
            self::$detrack = new Detrack();

        return self::$detrack;
    }

    public function addDeliveries()
    {

    }

    public function viewDeliveries()
    {

    }

    public function editDeliveries()
    {

    }

    public function deleteDeliveries()
    {

    }

    public function deliveryPushNotification()
    {

    }
}