<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Libraries\Helpers\Html;
use App\Libraries\Widgets\GridView;
use App\Models\ShippingPriceRule;
use App\Models\ShippingPriceRuleUser;
use App\Models\ShippingPriceRuleArea;
use App\Models\Area;
use App\Models\User;

class ShippingPriceRuleController extends Controller
{
    public function adminShippingPriceRule(Request $request)
    {
        $dataProvider = ShippingPriceRule::with(['shippingPriceRuleUsers.user' => function($query) {
            $query->select('id', 'username');
        }])->select('shipping_price_rule.*');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('shipping_price_rule.name', 'like', '%' . $inputs['name'] . '%');

            if(!empty($inputs['username']))
            {
                $dataProvider->join('shipping_price_rule_user', 'shipping_price_rule.id', '=', 'shipping_price_rule_user.shipping_price_rule_id')
                    ->join('user', 'shipping_price_rule_user.user_id', '=', 'user.id')
                    ->where('user.username', 'like', '%' . $inputs['username'] . '%');
            }
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\ShippingPriceRuleController@editShippingPriceRule', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Rule',
                'data' => function($row) {
                    $details = json_decode($row->rule, true);

                    $lastWeight = 0;

                    foreach($details as $detail)
                    {
                        if(!empty($detail['weight']))
                        {
                            echo '<b>' . $lastWeight . ' kg ' . ' - ' . $detail['weight'] . ' kg:</b> ' . Utility::formatNumber($detail['price']) . '<br />';
                            $lastWeight = $detail['weight'];
                        }
                        else
                            echo '<b>Mỗi 0.5 kg tiếp theo:</b> ' . Utility::formatNumber($detail['price']) . '<br />';
                    }
                },
            ],
            [
                'title' => 'Áp Dụng Cho Khách Hàng',
                'data' => function($row) {
                    foreach($row->shippingPriceRuleUsers as $shippingPriceRuleUser)
                        echo $shippingPriceRuleUser->user->username . '<br />';
                },
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setCheckbox();
        $gridView->setFilters([
            [
                'title' => 'Tên',
                'name' => 'name',
                'type' => 'input',
            ],
            [
                'title' => 'Khách Hàng',
                'name' => 'username',
                'type' => 'input',
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.shipping_price_rules.admin_shipping_price_rule', [
            'gridView' => $gridView,
        ]);
    }

    public function createShippingPriceRule(Request $request)
    {
        Utility::setBackUrlCookie($request, '/admin/shippingPriceRule?');

        $rule = new ShippingPriceRule();

        return $this->saveShippingPriceRule($request, $rule);
    }

    public function editShippingPriceRule(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/shippingPriceRule?');

        $rule = ShippingPriceRule::with(['shippingPriceRuleUsers.user' => function($query) {
            $query->select('id', 'username');
        }, 'shippingPriceRuleAreas'])->find($id);

        if(empty($rule))
            return view('backend.errors.404');

        return $this->saveShippingPriceRule($request, $rule, false);
    }

    protected function saveShippingPriceRule($request, $rule, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required|unique:shipping_price_rule,name' . ($create == true ? '' : (',' . $rule->id)),
                'rule' => 'required|array',
            ]);

            $validator->after(function($validator) use(&$inputs) {
                if(isset($inputs['rule']['weight']) && is_array($inputs['rule']['weight']))
                {
                    $details = array();

                    $countWeight = count($inputs['rule']['weight']);
                    $lastWeight = 0;
                    $lastPrice = 0;

                    foreach($inputs['rule']['weight'] as $key => $weight)
                    {
                        if($key != $countWeight - 1)
                        {
                            if(empty($weight) || !is_numeric($weight) || $weight < 0.05)
                                $validator->errors()->add('rule', trans('validation.in', ['attribute' => 'rule']));
                            else
                            {
                                if($weight <= $lastWeight)
                                    $validator->errors()->add('rule', trans('validation.in', ['attribute' => 'rule']));
                                else
                                    $lastWeight = $weight;

                                if(isset($inputs['rule']['price'][$key]))
                                {
                                    $price = implode('', explode('.', $inputs['rule']['price'][$key]));

                                    if(empty($price) || !is_numeric($price) || $price < 1)
                                        $validator->errors()->add('rule', trans('validation.in', ['attribute' => 'rule']));
                                    else
                                    {
                                        if($price <= $lastPrice)
                                            $validator->errors()->add('rule', trans('validation.in', ['attribute' => 'rule']));
                                        else
                                        {
                                            $lastPrice = $price;

                                            $details[] = [
                                                'weight' => $weight,
                                                'price' => $price
                                            ];
                                        }
                                    }
                                }
                                else
                                    $validator->errors()->add('rule', trans('validation.in', ['attribute' => 'rule']));
                            }
                        }
                        else
                        {
                            if(!empty($weight))
                                $validator->errors()->add('rule', trans('validation.in', ['attribute' => 'rule']));
                            else
                            {
                                if(isset($inputs['rule']['price'][$key]))
                                {
                                    $price = implode('', explode('.', $inputs['rule']['price'][$key]));

                                    if(empty($price) || !is_numeric($price) || $price < 1)
                                        $validator->errors()->add('rule', trans('validation.in', ['attribute' => 'rule']));
                                    else
                                    {
                                        $details[] = [
                                            'weight' => $weight,
                                            'price' => $price
                                        ];
                                    }
                                }
                                else
                                    $validator->errors()->add('rule', trans('validation.in', ['attribute' => 'rule']));
                            }
                        }
                    }

                    $inputs['details'] = json_encode($details);
                }

                if(!empty($inputs['usernames']))
                {
                    $usernames = explode(';', $inputs['usernames']);

                    foreach($usernames as $username)
                    {
                        $user = User::select('id')->where('username', $username)->first();

                        if(empty($user))
                            $validator->errors()->add('rule', 'Khách hàng ' . $username . ' không tồn tại');
                        else
                        {
                            if(!isset($inputs['user_ids']) || !in_array($user->id, $inputs['user_ids']))
                                $inputs['user_ids'][] = $user->id;
                        }
                    }
                }
            });

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    $rule->name = $inputs['name'];
                    $rule->rule = $inputs['details'];
                    $rule->save();

                    if(isset($inputs['user_ids']))
                    {
                        foreach($rule->shippingPriceRuleUsers as $shippingPriceRuleUser)
                        {
                            $key = array_search($shippingPriceRuleUser->user_id, $inputs['user_ids']);

                            if($key !== false)
                                unset($inputs['user_ids'][$key]);
                            else
                                $shippingPriceRuleUser->delete();
                        }

                        foreach($inputs['user_ids'] as $userId)
                        {
                            $shippingPriceRuleUser = new ShippingPriceRuleUser();
                            $shippingPriceRuleUser->shipping_price_rule_id = $rule->id;
                            $shippingPriceRuleUser->user_id = $userId;
                            $shippingPriceRuleUser->save();
                        }
                    }
                    else
                    {
                        foreach($rule->shippingPriceRuleUsers as $shippingPriceRuleUser)
                            $shippingPriceRuleUser->delete();
                    }

                    if(isset($inputs['districts']))
                    {
                        foreach($rule->shippingPriceRuleAreas as $shippingPriceRuleArea)
                        {
                            $key = array_search($shippingPriceRuleArea->area_id, $inputs['districts']);

                            if($key !== false)
                                unset($inputs['districts'][$key]);
                            else
                                $shippingPriceRuleArea->delete();
                        }

                        foreach($inputs['districts'] as $districtId)
                        {
                            $shippingPriceRuleArea = new ShippingPriceRuleArea();
                            $shippingPriceRuleArea->shipping_price_rule_id = $rule->id;
                            $shippingPriceRuleArea->area_id = $districtId;
                            $shippingPriceRuleArea->save();
                        }
                    }
                    else
                    {
                        foreach($rule->shippingPriceRuleAreas as $shippingPriceRuleArea)
                            $shippingPriceRuleArea->delete();
                    }

                    DB::commit();

                    return redirect()->action('Backend\ShippingPriceRuleController@editShippingPriceRule', ['id' => $rule->id])->with('messageSuccess', 'Thành Công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    if($create == true)
                        return redirect()->action('Backend\ShippingPriceRuleController@createShippingPriceRule')->withInput()->with('messageError', $e->getMessage());
                    else
                        return redirect()->action('Backend\ShippingPriceRuleController@editShippingPriceRule', ['id' => $rule->id])->withInput()->with('messageError', $e->getMessage());
                }
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\ShippingPriceRuleController@createShippingPriceRule')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\ShippingPriceRuleController@editShippingPriceRule', ['id' => $rule->id])->withErrors($validator)->withInput();
            }
        }

        $provinces = Area::with(['childrenAreas' => function($query) {
            $query->select('id', 'parent_id', 'name');
        }])->select('id', 'name')
            ->where('type', Area::TYPE_PROVINCE_DB)
            ->get();

        if($create == true)
        {
            return view('backend.shipping_price_rules.create_shipping_price_rule', [
                'rule' => $rule,
                'provinces' => $provinces,
            ]);
        }
        else
        {
            return view('backend.shipping_price_rules.edit_shipping_price_rule', [
                'rule' => $rule,
                'provinces' => $provinces,
            ]);
        }
    }

    public function deleteShippingPriceRule($id)
    {
        $rule = ShippingPriceRule::with('shippingPriceRuleAreas', 'shippingPriceRuleUsers')->find($id);

        if(empty($rule))
            return view('backend.errors.404');

        try
        {
            DB::beginTransaction();

            $rule->doDelete();

            DB::commit();

            return redirect(Utility::getBackUrlCookie(action('Backend\ShippingPriceRuleController@adminShippingPriceRule')))->with('messageSuccess', 'Thành Công');
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            return redirect()->action('Backend\ShippingPriceRuleController@editShippingPriceRule', ['id' => $id])->with('messageError', $e->getMessage());
        }
    }

    public function controlDeleteShippingPriceRule(Request $request)
    {
        $ids = $request->input('ids');

        $rules = ShippingPriceRule::with('shippingPriceRuleAreas', 'shippingPriceRuleUsers')->whereIn('id', explode(';', $ids))->get();

        foreach($rules as $rule)
        {
            try
            {
                DB::beginTransaction();

                $rule->doDelete();

                DB::commit();
            }
            catch(\Exception $e)
            {
                DB::rollBack();

                return redirect()->action('Backend\ShippingPriceRuleController@adminShippingPriceRule')->with('messageError', $e->getMessage());
            }
        }

        if($request->headers->has('referer'))
            return redirect($request->headers->get('referer'))->with('messageSuccess', 'Thành Công');
        else
            return redirect()->action('Backend\ShippingPriceRuleController@adminShippingPriceRule')->with('messageSuccess', 'Thành Công');
    }
}