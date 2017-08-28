<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers\Utility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Detrack\Detrack;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\Customer;

class DeliveryController extends Controller
{
    protected function getParams($request)
    {
        if($request->has('key') && $request->has('json'))
            $params = ['key' => $request->input('key'), 'json' => $request->input('json')];
        else if($request->hasHeader('Content-Type') && strtolower($request->header('Content-Type')) == 'application/json' && $request->hasHeader('X-API-KEY'))
        {
            $json = file_get_contents('php://input');

            $params = ['key' => $request->header('X-API-KEY'), 'json' => $json];
        }
        else
            return $this->apiInvalid();

        $user = User::with(['userAddresses' => function($query) {
            $query->where('default', Utility::ACTIVE_DB);
        }, 'customerInformation'])->where('api_key', $params['key'])->where('status', Utility::ACTIVE_DB)->first();

        if(empty($user))
            return $this->apiInvalid();

        $params['user'] = $user;

        return $params;
    }

    protected function apiInvalid()
    {
        return json_encode([
            'info' => [
                'status' => 'failed',
                'error' => [
                    'code' => '1001',
                    'message' => 'API key is invalid',
                ],
            ],
        ]);
    }

    protected function apiInvalidArgument()
    {
        return json_encode([
            'info' => [
                'status' => 'failed',
                'error' => [
                    'code' => '1000',
                    'message' => 'Invalid argument from request',
                ],
            ],
        ]);
    }

    public function addDelivery(Request $request)
    {
        $params = $this->getParams($request);

        if(!is_array($params))
            return $params;

        $user = $params['user'];

        $deliveryData = json_decode($params['json'], true);

        $response = [
            'info' => [
                'status' => 'ok',
                'failed' => 0,
            ],
            'result' => array(),
        ];

        if(!is_array($deliveryData))
            return $this->apiInvalidArgument();

        $placedOrders = array();

        $i = 0;
        foreach($deliveryData as $data)
        {
            if(!empty($data['do']) && !empty($data['date']) && !empty($data['address']) && strtotime($data['date']) !== false && strtotime($data['date']) >= strtotime(date('Y-m-d')))
            {
                $validOrder = Order::select('id', 'created_at')->where('user_id', $user->id)->where('user_do', $data['do'])->first();

                if(empty($validOrder))
                {
                    try
                    {
                        DB::beginTransaction();

                        $order = new Order();
                        $order->user_id = $user->id;
                        $order->created_at = date('Y-m-d H:i:s');
                        $order->cod_price = (!empty($data['pay_amt']) && is_numeric($data['pay_amt']) && $data['pay_amt'] > 0) ? $data['pay_amt'] : 0;
                        $order->shipping_price = 0;
                        $order->shipping_payment = Order::SHIPPING_PAYMENT_SENDER_DB;
                        $order->total_cod_price = $order->cod_price;
                        $order->weight = (!empty($data['wt']) && is_numeric($data['wt']) && $data['wt'] > 0) ? $data['wt'] : 0;
                        $order->note = !empty($data['instructions']) ? $data['instructions'] : '';
                        $order->status = Order::STATUS_INFO_RECEIVED_DB;
                        $order->collection_status = Order::STATUS_INFO_RECEIVED_DB;
                        $order->user_do = strtoupper($data['do']);
                        $order->user_notify_url = !empty($data['notify_url']) ? $data['notify_url'] : '';

                        $order->generateDo(null);

                        $order->date = date('Y-m-d', strtotime($data['date']));
                        $order->save();

                        $order->setRelation('user', $user);

                        if(empty($user->customerInformation))
                        {
                            $customer = new Customer();
                            $customer->user_id = $user->id;
                            $customer->order_count = 1;
                            $customer->save();

                            $user->setRelation('customerInformation', $customer);
                        }
                        else
                        {
                            $user->customerInformation->order_count += 1;
                            $user->customerInformation->save();
                        }

                        $senderAddress = new OrderAddress();
                        $senderAddress->order_id = $order->id;
                        $senderAddress->name = $user->userAddresses[0]->name;
                        $senderAddress->phone = $user->userAddresses[0]->phone;
                        $senderAddress->address = $user->userAddresses[0]->address;
                        $senderAddress->province = $user->userAddresses[0]->province;
                        $senderAddress->district = $user->userAddresses[0]->district;
                        $senderAddress->ward = $user->userAddresses[0]->ward;
                        $senderAddress->province_id = $user->userAddresses[0]->province_id;
                        $senderAddress->district_id = $user->userAddresses[0]->district_id;
                        $senderAddress->ward_id = $user->userAddresses[0]->ward_id;
                        $senderAddress->type = OrderAddress::TYPE_SENDER_DB;
                        $senderAddress->save();

                        $order->setRelation('senderAddress', $senderAddress);

                        $receiverAddress = new OrderAddress();
                        $receiverAddress->order_id = $order->id;
                        $receiverAddress->name = !empty($data['deliver_to']) ? $data['deliver_to'] : '';
                        $receiverAddress->phone = !empty($data['phone']) ? $data['phone'] : '';
                        $receiverAddress->address = $data['address'];
                        $receiverAddress->province = !empty($data['country']) ? $data['country'] : '';
                        $receiverAddress->district = !empty($data['city']) ? $data['city'] : '';
                        $receiverAddress->type = OrderAddress::TYPE_RECEIVER_DB;
                        $receiverAddress->save();

                        $order->setRelation('receiverAddress', $receiverAddress);

                        $placedOrders[] = $order;

                        DB::commit();

                        $response['result'][] = [
                            'date' => $data['date'],
                            'do' => $data['do'],
                            'status' => 'ok',
                        ];
                    }
                    catch(\Exception $e)
                    {
                        DB::rollBack();
                    }
                }
                else
                {
                    $response['info']['failed'] ++;
                    $response['result'][] = [
                        'date' => $data['date'],
                        'do' => $data['do'],
                        'status' => 'failed',
                        'errors' => [
                            [
                                'code' => '1002',
                                'message' => 'Delivery with D.O. # ' . $data['do'] . ' already exists on ' . explode(' ', $validOrder->created_at)[0],
                            ]
                        ]
                    ];
                }
            }
            else
            {
                $response['info']['failed'] ++;
                $response['result'][] = [
                    'status' => 'failed',
                    'errors' => [
                        [
                            'code' => '1000',
                            'message' => 'Invalid argument from request'
                        ]
                    ]
                ];
            }

            $i ++;

            if($i == 100)
                break;
        }

        if(count($placedOrders) > 0)
        {
            $detrack = Detrack::make();
            $successDos = $detrack->addCollections($placedOrders);

            $countSuccessDo = count($successDos);
            if($countSuccessDo > 0)
            {
                foreach($placedOrders as $placedOrder)
                {
                    $key = array_search($placedOrder->do, $successDos);

                    if($key !== false)
                    {
                        $placedOrder->collection_call_api = Utility::ACTIVE_DB;
                        $placedOrder->save();

                        unset($successDos[$key]);

                        $countSuccessDo -= 1;

                        if($countSuccessDo == 0)
                            break;
                    }
                }
            }
        }

        return json_encode($response);
    }

    public function viewDelivery(Request $request)
    {
        $params = $this->getParams($request);

        if($params === null)
            return $this->apiInvalid();
    }

    public function editDelivery(Request $request)
    {
        $params = $this->getParams($request);

        if(!is_array($params))
            return $params;

        $user = $params['user'];

        $deliveryData = json_decode($params['json'], true);

        $response = [
            'info' => [
                'status' => 'ok',
                'failed' => 0,
            ],
            'result' => array(),
        ];

        if(!is_array($deliveryData))
            return $this->apiInvalidArgument();

        $editedOrders = array();
        $placedOrders = array();

        $i = 0;
        foreach($deliveryData as $data)
        {
            if(!empty($data['do']) && !empty($data['date']) && strtotime($data['date']) !== false)
            {
                $order = Order::with('senderAddress', 'receiverAddress')->where('user_id', $user->id)->where('user_do', $data['do'])->first();

                if(!empty($order))
                {
                    if(Order::getOrderStatusOrder($order->status) <= Order::getOrderStatusOrder(Order::STATUS_INFO_RECEIVED_DB))
                    {
                        try
                        {
                            DB::beginTransaction();

                            $order->cod_price = (!empty($data['pay_amt']) && is_numeric($data['pay_amt']) && $data['pay_amt'] > 0) ? $data['pay_amt'] : $order->cod_price;
                            $order->shipping_price = 0;
                            $order->shipping_payment = Order::SHIPPING_PAYMENT_SENDER_DB;
                            $order->total_cod_price = $order->cod_price;
                            $order->weight = (!empty($data['wt']) && is_numeric($data['wt']) && $data['wt'] > 0) ? $data['wt'] : $order->weight;
                            $order->note = !empty($data['instructions']) ? $data['instructions'] : $order->note;
                            $order->status = Order::STATUS_INFO_RECEIVED_DB;
                            $order->collection_status = Order::STATUS_INFO_RECEIVED_DB;
                            $order->user_notify_url = !empty($data['notify_url']) ? $data['notify_url'] : $order->user_notify_url;
                            $order->save();

                            $order->receiverAddress->name = !empty($data['deliver_to']) ? $data['deliver_to'] : $order->receiverAddress->name;
                            $order->receiverAddress->phone = !empty($data['phone']) ? $data['phone'] : $order->receiverAddress->phone;
                            $order->receiverAddress->address = !empty($data['address']) ? $data['address'] : $order->receiverAddress->address;
                            $order->receiverAddress->province = !empty($data['country']) ? $data['country'] : $order->receiverAddress->province;
                            $order->receiverAddress->district = !empty($data['city']) ? $data['city'] : $order->receiverAddress->district;
                            $order->receiverAddress->save();

                            if($order->collection_call_api == Utility::ACTIVE_DB)
                                $editedOrders[] = $order;
                            else
                                $placedOrders[] = $order;

                            DB::commit();

                            $response['result'][] = [
                                'date' => $data['date'],
                                'do' => $data['do'],
                                'status' => 'ok',
                            ];
                        }
                        catch(\Exception $e)
                        {
                            DB::rollBack();
                        }
                    }
                    else
                    {
                        $response['info']['failed'] ++;
                        $response['result'][] = [
                            'date' => $data['date'],
                            'do' => $data['do'],
                            'status' => 'failed',
                            'errors' => [
                                [
                                    'code' => '1004',
                                    'message' => 'Delivery with D.O. # ' . $data['do'] . ' not editable'
                                ]
                            ]
                        ];
                    }
                }
                else
                {
                    $response['info']['failed'] ++;
                    $response['result'][] = [
                        'date' => $data['date'],
                        'do' => $data['do'],
                        'status' => 'failed',
                        'errors' => [
                            [
                                'code' => '1003',
                                'message' => 'Delivery with D.O. # ' . $data['do'] . ' not found on ' . $data['date']
                            ]
                        ]
                    ];
                }
            }
            else
            {
                $response['info']['failed'] ++;
                $response['result'][] = [
                    'status' => 'failed',
                    'errors' => [
                        [
                            'code' => '1000',
                            'message' => 'Invalid argument from request'
                        ]
                    ]
                ];
            }

            $i ++;

            if($i == 100)
                break;
        }

        if(count($editedOrders) > 0)
        {
            $detrack = Detrack::make();
            $detrack->editCollections($editedOrders);
        }

        if(count($placedOrders) > 0)
        {
            $detrack = Detrack::make();
            $successDos = $detrack->addCollections($placedOrders);

            $countSuccessDo = count($successDos);
            if($countSuccessDo > 0)
            {
                foreach($placedOrders as $placedOrder)
                {
                    $key = array_search($placedOrder->do, $successDos);

                    if($key !== false)
                    {
                        $placedOrder->collection_call_api = Utility::ACTIVE_DB;
                        $placedOrder->save();

                        unset($successDos[$key]);

                        $countSuccessDo -= 1;

                        if($countSuccessDo == 0)
                            break;
                    }
                }
            }
        }

        return json_encode($response);
    }

    public function deleteDelivery(Request $request)
    {
        $params = $this->getParams($request);

        if(!is_array($params))
            return $params;

        $user = $params['user'];

        $deliveryData = json_decode($params['json'], true);

        $response = [
            'info' => [
                'status' => 'ok',
                'failed' => 0,
            ],
            'result' => array(),
        ];

        if(!is_array($deliveryData))
            return $this->apiInvalidArgument();

        $deletedOrders = array();

        $i = 0;
        foreach($deliveryData as $data)
        {
            if(!empty($data['do']) && !empty($data['date']) && strtotime($data['date']) !== false)
            {
                $order = Order::where('user_id', $user->id)->where('user_do', $data['do'])->first();

                if(!empty($order))
                {
                    if(Order::getOrderStatusOrder($order->status) <= Order::getOrderStatusOrder(Order::STATUS_INFO_RECEIVED_DB))
                    {
                        try
                        {
                            DB::beginTransaction();

                            $order->cancelOrder();

                            if($order->collection_call_api == Utility::ACTIVE_DB)
                                $deletedOrders[] = $order;

                            DB::commit();

                            $response['result'][] = [
                                'date' => $data['date'],
                                'do' => $data['do'],
                                'status' => 'ok',
                            ];
                        }
                        catch(\Exception $e)
                        {
                            DB::rollBack();
                        }
                    }
                    else
                    {
                        $response['info']['failed'] ++;
                        $response['result'][] = [
                            'date' => $data['date'],
                            'do' => $data['do'],
                            'status' => 'failed',
                            'errors' => [
                                [
                                    'code' => '1005',
                                    'message' => 'Delivery with D.O. # ' . $data['do'] . ' not deletable'
                                ]
                            ]
                        ];
                    }
                }
                else
                {
                    $response['info']['failed'] ++;
                    $response['result'][] = [
                        'date' => $data['date'],
                        'do' => $data['do'],
                        'status' => 'failed',
                        'errors' => [
                            [
                                'code' => '1003',
                                'message' => 'Delivery with D.O. # ' . $data['do'] . ' not found on ' . $data['date']
                            ]
                        ]
                    ];
                }
            }
            else
            {
                $response['info']['failed'] ++;
                $response['result'][] = [
                    'status' => 'failed',
                    'errors' => [
                        [
                            'code' => '1000',
                            'message' => 'Invalid argument from request'
                        ]
                    ]
                ];
            }

            $i ++;

            if($i == 100)
                break;
        }

        if(count($deletedOrders) > 0)
        {
            $detrack = Detrack::make();
            $detrack->deleteCollections($deletedOrders);
        }

        return json_encode($response);
    }

    public function handleDeliveryNotification(Request $request)
    {
        $inputs = $request->all();

        $detrack = Detrack::make();
        $detrack->handleDeliveryNotification($inputs);
    }
}