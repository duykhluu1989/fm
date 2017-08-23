<?php

namespace App\Libraries\Detrack;

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
                'date' => explode(' ', $order->created_at)[0],
                'city' => $order->receiverAddress->district,
                'country' => $order->receiverAddress->province,
                'wt' => $order->weight,
                'deliver_to' => $order->receiverAddress->name,
                'phone' => $order->receiverAddress->phone,
                'notify_email' => $order->user->email,
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
                'date' => explode(' ', $order->created_at)[0],
                'city' => $order->receiverAddress->district,
                'country' => $order->receiverAddress->province,
                'wt' => $order->weight,
                'deliver_to' => $order->receiverAddress->name,
                'phone' => $order->receiverAddress->phone,
                'notify_email' => $order->user->email,
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

    public function handleDeliveryNotification()
    {

    }

    public function addCollections($orders)
    {
        $params = array();

        foreach($orders as $order)
        {
            $params[] = [
                'do' => $order->do,
                'address' => $order->senderAddress->address . ' ' . $order->senderAddress->ward . ' ' . $order->senderAddress->district . ' ' . $order->senderAddress->province,
                'date' => explode(' ', $order->created_at)[0],
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
                'date' => explode(' ', $order->created_at)[0],
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
            if(!isset($input['json']) || !isset($input['key']))
                return json_encode(['status' => 'param_invalid']);

            $environment = app()->environment();

            if($environment != 'production' && strtoupper($input['key']) != strtoupper(self::$apiNotifyKeyTest))
                return json_encode(['status' => 'key_invalid']);
            else if(strtoupper($input['key']) != strtoupper(self::$apiNotifyKey))
                return json_encode(['status' => 'key_invalid']);

            $webhookData = json_decode($input['json'], true);

            $shipper = Carrier::where('class_handle', __CLASS__)->first();
            $fulfillment = OrderFulfillment::with('order.orderStatus', 'order.orderDiscounts')->where('tracking_number', $webhookData['do'])->where('carrier_id', $shipper->id)->first();
            if(empty($fulfillment))
                return json_encode(['status' => 'id_invalid']);

            if(empty($fulfillment->weight) && !empty($webhookData['wt']))
            {
                $fulfillment->weight = (int)($webhookData['wt'] * 1000);
                $fulfillment->price = self::calculateShippingPrice($fulfillment->weight, $fulfillment->order->orderBillingAddress->district);
            }

            $newLog = true;

            if(empty($fulfillment->description))
                $fulfillment->description = json_encode(self::createLogData($webhookData));
            else
            {
                $descriptions = explode(';', $fulfillment->description);
                if(count($descriptions) > 0)
                {
                    $lastLog = json_decode($descriptions[count($descriptions) - 1], true);
                    if($lastLog['tracking_status'] == $webhookData['tracking_status'])
                    {
                        $newLog = false;

                        $descriptions[count($descriptions) - 1] = json_encode(self::createLogData($webhookData));
                        $fulfillment->description = implode(';', $descriptions);
                    }
                }

                if($newLog)
                    $fulfillment->description .= ';' . json_encode(self::createLogData($webhookData));
            }

            $fulfillment->updated_at = new \DateTime();
            $fulfillment->save();

            if($newLog)
            {
                if($webhookData['tracking_status'] == 'completed')
                {
                    $fulfillment->status = Order::FULFILLMENT_STATUS_FULFILLED;
                    $fulfillment->save();

                    if($fulfillment->type == OrderFulfillment::TYPE_FULFILLMENT_VALUE)
                        self::updateOrderDeliveried($fulfillment->order);
                }
                else if($webhookData['tracking_status'] == 'failed')
                {
                    $fulfillment->status = Order::FULFILLMENT_STATUS_NOTFULFILLED;
                    $fulfillment->save();

                    if($fulfillment->type == OrderFulfillment::TYPE_FULFILLMENT_VALUE)
                        self::updateOrderFailDeliveried($fulfillment->order);
                }
            }

            return json_encode(['status' => 'success']);
        }
        catch(\Exception $e)
        {
            return json_encode(['status' => 'error']);
        }
    }
}