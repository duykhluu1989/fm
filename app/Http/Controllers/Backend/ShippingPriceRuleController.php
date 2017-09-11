<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Libraries\Helpers\Html;
use App\Libraries\Widgets\GridView;
use App\Models\ShippingPriceRule;

class ShippingPriceRuleController extends Controller
{
    public function adminShippingPriceRule(Request $request)
    {
        $dataProvider = ShippingPriceRule::select('shipping_price_rule.*');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('shipping_price_rule.name', 'like', '%' . $inputs['name'] . '%');
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
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setFilters([
            [
                'title' => 'Tên',
                'name' => 'name',
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

        $rule = ShippingPriceRule::find($id);

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
                            if(empty($weight) || !is_numeric($weight) || $weight < 0.1)
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
            });

            if($validator->passes())
            {
                $rule->name = $inputs['name'];
                $rule->rule = $inputs['details'];
                $rule->save();

                return redirect()->action('Backend\ShippingPriceRuleController@editShippingPriceRule', ['id' => $rule->id])->with('messageSuccess', 'Thành Công');
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\ShippingPriceRuleController@createShippingPriceRule')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\ShippingPriceRuleController@editShippingPriceRule', ['id' => $rule->id])->withErrors($validator)->withInput();
            }
        }

        if($create == true)
        {
            return view('backend.shipping_price_rules.create_shipping_price_rule', [
                'rule' => $rule,
            ]);
        }
        else
        {
            return view('backend.shipping_price_rules.edit_shipping_price_rule', [
                'rule' => $rule,
            ]);
        }
    }
}