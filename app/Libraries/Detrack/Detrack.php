<?php

namespace App\Libraries\Detrack;

use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers\Utility;
use App\Models\Setting;
use App\Models\Order;

class Detrack
{
    const API_ROOT_URL = 'https://app.detrack.com/api/v1';

    protected static $detrack;

    protected $api_key;
    protected $web_hook_key;

    protected function __construct()
    {
        $this->api_key = Setting::getSettings(Setting::CATEGORY_API_DB, Setting::DETRACK_API_KEY);
        $this->web_hook_key = Setting::getSettings(Setting::CATEGORY_API_DB, Setting::DETRACK_WEB_HOOK_KEY);
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
                'date' => $order->date,
                'city' => $order->receiverAddress->district,
                'country' => $order->receiverAddress->province,
                'wt' => $order->weight,
                'deliver_to' => $order->receiverAddress->name,
                'phone' => $order->receiverAddress->phone,
                'notify_url' => action('Api\DeliveryController@handleDeliveryNotification'),
                'instructions' => !empty($order->note) ? $order->note : '',
                'pay_amt' => $order->total_cod_price,
                'order_no' => $order->number,
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

        $successDos = array();

        if(!empty($result))
        {
            $result = json_decode($result, true);

            if(is_array($result) && isset($result['info']['status']) && strtoupper($result['info']['status']) == 'OK' && isset($result['results']) && is_array($result['results']))
            {
                foreach($result['results'] as $orderResult)
                {
                    if(is_array($orderResult) && isset($orderResult['status']) && strtoupper($orderResult['status']) == 'OK' && isset($orderResult['do']))
                        $successDos[] = $orderResult['do'];
                }
            }
        }

        return $successDos;
    }

    public function viewDeliveries($orders, $pass = false)
    {
        $params = array();

        foreach($orders as $order)
        {
            $params[] = [
                'do' => $order->do,
                'date' => explode(' ', $order->created_at)[0],
            ];
        }

        $params = json_encode($params);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::API_ROOT_URL . '/deliveries/view.json');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-API-Key: ' . $this->api_key, 'Content-Type: application/json', 'Content-Length: ' . strlen($params)]);

        if(app()->environment() != 'production')
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);
        curl_close($curl);

        if($pass == true)
            return $result;

        $responseDatas = array();

        if(!empty($result))
        {
            $result = json_decode($result, true);

            if(is_array($result) && isset($result['info']['status']) && strtoupper($result['info']['status']) == 'OK' && isset($result['results']) && is_array($result['results']))
            {
                foreach($result['results'] as $orderResult)
                {
                    if(is_array($orderResult) && isset($orderResult['status']) && strtoupper($orderResult['status']) == 'OK' && isset($orderResult['delivery']))
                        $responseDatas[] = $orderResult['delivery'];
                }
            }
        }

        return $responseDatas;
    }

    public function editDeliveries($orders)
    {
        $params = array();

        foreach($orders as $order)
        {
            $params[] = [
                'do' => $order->do,
                'address' => $order->receiverAddress->address . ' ' . $order->receiverAddress->ward . ' ' . $order->receiverAddress->district . ' ' . $order->receiverAddress->province,
                'date' => $order->date,
                'city' => $order->receiverAddress->district,
                'country' => $order->receiverAddress->province,
                'wt' => $order->weight,
                'deliver_to' => $order->receiverAddress->name,
                'phone' => $order->receiverAddress->phone,
                'notify_url' => action('Api\DeliveryController@handleDeliveryNotification'),
                'instructions' => !empty($order->note) ? $order->note : '',
                'pay_amt' => $order->total_cod_price,
                'order_no' => $order->number,
            ];
        }

        $params = json_encode($params);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::API_ROOT_URL . '/deliveries/update.json');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-API-Key: ' . $this->api_key, 'Content-Type: application/json', 'Content-Length: ' . strlen($params)]);

        if(app()->environment() != 'production')
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);
        curl_close($curl);

        $successDos = array();

        if(!empty($result))
        {
            $result = json_decode($result, true);

            if(is_array($result) && isset($result['info']['status']) && strtoupper($result['info']['status']) == 'OK' && isset($result['results']) && is_array($result['results']))
            {
                foreach($result['results'] as $orderResult)
                {
                    if(is_array($orderResult) && isset($orderResult['status']) && strtoupper($orderResult['status']) == 'OK' && isset($orderResult['do']))
                        $successDos[] = $orderResult['do'];
                }
            }
        }

        return $successDos;
    }

    public function deleteDeliveries($orders)
    {
        $params = array();

        foreach($orders as $order)
        {
            $params[] = [
                'do' => $order->do,
                'date' => explode(' ', $order->created_at)[0],
            ];
        }

        $params = json_encode($params);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::API_ROOT_URL . '/deliveries/delete.json');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-API-Key: ' . $this->api_key, 'Content-Type: application/json', 'Content-Length: ' . strlen($params)]);

        if(app()->environment() != 'production')
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);
        curl_close($curl);

        $successDos = array();

        if(!empty($result))
        {
            $result = json_decode($result, true);

            if(is_array($result) && isset($result['info']['status']) && strtoupper($result['info']['status']) == 'OK' && isset($result['results']) && is_array($result['results']))
            {
                foreach($result['results'] as $orderResult)
                {
                    if(is_array($orderResult) && isset($orderResult['status']) && strtoupper($orderResult['status']) == 'OK' && isset($orderResult['do']))
                        $successDos[] = $orderResult['do'];
                }
            }
        }

        return $successDos;
    }

    public function handleDeliveryNotification($inputs)
    {
        try
        {
            if(isset($inputs['json']) && isset($inputs['key']) && $inputs['key'] == $this->web_hook_key)
            {
                $deliveryTrackingData = json_decode($inputs['json'], true);

                $order = Order::with('user.customerInformation')->where('do', $deliveryTrackingData['do'])->first();

                if(!empty($order))
                {
                    DB::beginTransaction();

                    if($order->delivery_status != strtolower($deliveryTrackingData['tracking_status']))
                        $order->delivery_status = strtolower($deliveryTrackingData['tracking_status']);

                    $order->shipper = $deliveryTrackingData['assign_to'];
                    $order->tracking_detail = $inputs['json'];
                    $order->save();

                    DB::commit();

                    if($order->status != Order::STATUS_INFO_RECEIVED_DB  && !empty($order->user_notify_url) && !empty($order->user->api_key))
                    {
                        $deliveryTrackingData['do'] = $order->user_do;
                        $deliveryTrackingData['notify_url'] = $order->user_notify_url;

                        $params = [
                            'key' => $order->user->api_key,
                            'json' => json_encode($deliveryTrackingData)
                        ];

                        $curl = curl_init();

                        curl_setopt($curl, CURLOPT_URL, $order->user_notify_url);
                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_FAILONERROR, true);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

                        if(app()->environment() != 'production')
                            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                        $result = curl_exec($curl);
                        curl_close($curl);
                    }
                }
            }
        }
        catch(\Exception $e)
        {
            DB::rollBack();
        }
    }

    public function addCollections($orders)
    {
        $params = array();

        foreach($orders as $order)
        {
            $params[] = [
                'do' => $order->do,
                'address' => $order->senderAddress->address . ' ' . $order->senderAddress->ward . ' ' . $order->senderAddress->district . ' ' . $order->senderAddress->province,
                'date' => $order->date,
                'city' => $order->senderAddress->district,
                'country' => $order->senderAddress->province,
                'wt' => $order->weight,
                'collect_from' => $order->senderAddress->name,
                'phone' => $order->senderAddress->phone,
                'notify_url' => action('Api\CollectionController@handleCollectionNotification'),
                'instructions' => !empty($order->note) ? $order->note : '',
                'order_no' => $order->number,
            ];
        }

        $params = json_encode($params);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::API_ROOT_URL . '/collections/create.json');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-API-Key: ' . $this->api_key, 'Content-Type: application/json', 'Content-Length: ' . strlen($params)]);

        if(app()->environment() != 'production')
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);
        curl_close($curl);

        $successDos = array();

        if(!empty($result))
        {
            $result = json_decode($result, true);

            if(is_array($result) && isset($result['info']['status']) && strtoupper($result['info']['status']) == 'OK' && isset($result['results']) && is_array($result['results']))
            {
                foreach($result['results'] as $orderResult)
                {
                    if(is_array($orderResult) && isset($orderResult['status']) && strtoupper($orderResult['status']) == 'OK' && isset($orderResult['do']))
                        $successDos[] = $orderResult['do'];
                }
            }
        }

        return $successDos;
    }

    public function viewCollections($orders, $pass = false)
    {
        $params = array();

        foreach($orders as $order)
        {
            $params[] = [
                'do' => $order->do,
                'date' => explode(' ', $order->created_at)[0],
            ];
        }

        $params = json_encode($params);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::API_ROOT_URL . '/collections/view.json');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-API-Key: ' . $this->api_key, 'Content-Type: application/json', 'Content-Length: ' . strlen($params)]);

        if(app()->environment() != 'production')
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);
        curl_close($curl);

        if($pass == true)
            return $result;

        $responseDatas = array();

        if(!empty($result))
        {
            $result = json_decode($result, true);

            if(is_array($result) && isset($result['info']['status']) && strtoupper($result['info']['status']) == 'OK' && isset($result['results']) && is_array($result['results']))
            {
                foreach($result['results'] as $orderResult)
                {
                    if(is_array($orderResult) && isset($orderResult['status']) && strtoupper($orderResult['status']) == 'OK' && isset($orderResult['delivery']))
                        $responseDatas[] = $orderResult['delivery'];
                }
            }
        }

        return $responseDatas;
    }

    public function editCollections($orders)
    {
        $params = array();

        foreach($orders as $order)
        {
            $params[] = [
                'do' => $order->do,
                'address' => $order->senderAddress->address . ' ' . $order->senderAddress->ward . ' ' . $order->senderAddress->district . ' ' . $order->senderAddress->province,
                'date' => $order->date,
                'city' => $order->senderAddress->district,
                'country' => $order->senderAddress->province,
                'wt' => $order->weight,
                'collect_from' => $order->senderAddress->name,
                'phone' => $order->senderAddress->phone,
                'notify_url' => action('Api\CollectionController@handleCollectionNotification'),
                'instructions' => !empty($order->note) ? $order->note : '',
                'order_no' => $order->number,
            ];
        }

        $params = json_encode($params);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::API_ROOT_URL . '/collections/update.json');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-API-Key: ' . $this->api_key, 'Content-Type: application/json', 'Content-Length: ' . strlen($params)]);

        if(app()->environment() != 'production')
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);
        curl_close($curl);

        $successDos = array();

        if(!empty($result))
        {
            $result = json_decode($result, true);

            if(is_array($result) && isset($result['info']['status']) && strtoupper($result['info']['status']) == 'OK' && isset($result['results']) && is_array($result['results']))
            {
                foreach($result['results'] as $orderResult)
                {
                    if(is_array($orderResult) && isset($orderResult['status']) && strtoupper($orderResult['status']) == 'OK' && isset($orderResult['do']))
                        $successDos[] = $orderResult['do'];
                }
            }
        }

        return $successDos;
    }

    public function deleteCollections($orders)
    {
        $params = array();

        foreach($orders as $order)
        {
            $params[] = [
                'do' => $order->do,
                'date' => explode(' ', $order->created_at)[0],
            ];
        }

        $params = json_encode($params);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::API_ROOT_URL . '/collections/delete.json');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-API-Key: ' . $this->api_key, 'Content-Type: application/json', 'Content-Length: ' . strlen($params)]);

        if(app()->environment() != 'production')
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);
        curl_close($curl);

        $successDos = array();

        if(!empty($result))
        {
            $result = json_decode($result, true);

            if(is_array($result) && isset($result['info']['status']) && strtoupper($result['info']['status']) == 'OK' && isset($result['results']) && is_array($result['results']))
            {
                foreach($result['results'] as $orderResult)
                {
                    if(is_array($orderResult) && isset($orderResult['status']) && strtoupper($orderResult['status']) == 'OK' && isset($orderResult['do']))
                        $successDos[] = $orderResult['do'];
                }
            }
        }

        return $successDos;
    }

    public function handleCollectionNotification($inputs)
    {
        try
        {
            if(isset($inputs['json']) && isset($inputs['key']) && $inputs['key'] == $this->web_hook_key)
            {
                $collectionTrackingData = json_decode($inputs['json'], true);

                $order = Order::where('do', $collectionTrackingData['do'])->first();

                if(!empty($order))
                {
                    DB::beginTransaction();

                    if($order->collection_status != strtolower($collectionTrackingData['tracking_status']))
                        $order->collection_status = strtolower($collectionTrackingData['tracking_status']);

                    $order->collection_shipper = $collectionTrackingData['assign_to'];
                    $order->collection_tracking_detail = $inputs['json'];
                    $order->save();

                    DB::commit();

                    if($order->collection_status == Order::STATUS_COMPLETED_DB)
                    {
                        $successDos = $this->addDeliveries([$order]);

                        $countSuccessDo = count($successDos);
                        if($countSuccessDo > 0)
                        {
                            $order->delivery_status = Order::STATUS_INFO_RECEIVED_DB;
                            $order->call_api = Utility::ACTIVE_DB;
                            $order->save();
                        }
                    }
                }
            }
        }
        catch(\Exception $e)
        {
            DB::rollBack();
        }
    }
}