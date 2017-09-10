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
        $dataProvider = ShippingPriceRule::select('shipping_price_rule.name', 'shipping_price_rule.rule');

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

            ]);

            if($validator->passes())
            {


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