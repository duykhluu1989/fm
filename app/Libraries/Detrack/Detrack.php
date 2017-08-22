<?php

namespace App\Libraries\Detrack;

use App\Libraries\Helpers\Utility;
use App\Models\Setting;

class Detrack
{
    const API_ROOT_URL = 'https://app.detrack.com/api/v1';

    protected static $detrack;

    protected $api_key;

    protected function __construct()
    {
        $this->api_key = Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::DETRACK_API_KEY);
    }

    public static function make()
    {
        if(empty(self::$detrack))
            self::$detrack = new Detrack();

        return self::$detrack;
    }

    public function addDeliveries($orders)
    {
        $params = array();

        foreach($orders as $order)
        {
            $params[] = [
                'do' => $order->do,
                'address' => $order->receiverAddress->address . ' ' . $order->receiverAddress->ward . ' ' . $order->receiverAddress->district . ' ' . $order->receiverAddress->province,
                'date' => date('Y-m-d'),
                'deliver_to' => $order->receiverAddress->name,
                'phone' => $order->receiverAddress->phone,
                'notify_email' => $order->user->email,
                'notify_url' => '',
                'instructions' => !empty($order->note) ? $order->note : '',
                'pay_amt' => $order->total_cod_price,
            ];
        }

        $params = json_encode($params);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::API_ROOT_URL . '/deliveries/create.json');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-API-Key: ' . $this->api_key, 'Content-Type: application/json', 'Content-Length: ' . strlen($params)]);

        if(app()->environment() != 'production')
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);
        curl_close($curl);

        if(!empty($result))
        {
            $result = json_decode($result, true);

            if(is_array($result) && isset($result['info']['status']) && strtoupper($result['info']['status']) == 'OK' && isset($result['results']) && is_array($result['results']))
            {
                $keyedOrders = array();

                foreach($orders as $order)
                    $keyedOrders[$order->do] = $order;

                foreach($result['results'] as $orderResult)
                {
                    if(is_array($orderResult) && isset($orderResult['status']) && strtoupper($orderResult['status']) == 'OK' && isset($orderResult['do']) && isset($keyedOrders[$orderResult['do']]))
                    {
                        $keyedOrders[$orderResult['do']]->call_api = Utility::ACTIVE_DB;
                        $keyedOrders[$orderResult['do']]->save();
                    }
                }
            }
        }
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