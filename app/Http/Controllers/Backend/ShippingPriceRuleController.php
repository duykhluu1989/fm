<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
                'title' => 'Name',
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
                'title' => 'Name',
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

    }

    public function editShippingPriceRule(Request $request, $id)
    {

    }
}