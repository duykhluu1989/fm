<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Models\Area;

class AreaController extends Controller
{
    public function adminArea(Request $request)
    {
        $dataProvider = Area::with(['parentArea' => function($query) {
            $query->select('id', 'name');
        }])->select('area.id', 'area.parent_id', 'area.name', 'area.status', 'area.shipping_price', 'area.type');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('area.name', 'like', '%' . $inputs['name'] . '%');

            if(!empty($inputs['parent_name']))
            {
                $dataProvider->join('area as pa', function($join) {
                    $join->on('area.parent_id', '=', 'pa.id');
                })->where('pa.name', 'like', '%' . $inputs['parent_name'] . '%');
            }

            if(isset($inputs['type']) && $inputs['type'] !== '')
                $dataProvider->where('area.type', $inputs['type']);

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('area.status', $inputs['status']);
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên Khu Vực',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\AreaController@editArea', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Thuộc Khu Vực',
                'data' => function($row) {
                    if(!empty($row->parentArea))
                        echo $row->parentArea->name;
                },
            ],
            [
                'title' => 'Loại Khu Vực',
                'data' => function($row) {
                    echo Area::getAreaType($row->type);
                },
            ],
            [
                'title' => 'Phí Ship',
                'data' => function($row) {
                    echo Utility::formatNumber($row->shipping_price);
                },
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = Utility::getTrueFalse($row->status);
                    if($row->status == Utility::ACTIVE_DB)
                        echo Html::span($status, ['class' => 'label label-success']);
                    else
                        echo Html::span($status, ['class' => 'label label-danger']);
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
            [
                'title' => 'Thuộc',
                'name' => 'parent_name',
                'type' => 'input',
            ],
            [
                'title' => 'Loại',
                'name' => 'type',
                'type' => 'select',
                'options' => Area::getAreaType(),
            ],
            [
                'title' => 'Trạng Thái',
                'name' => 'status',
                'type' => 'select',
                'options' => Utility::getTrueFalse(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.areas.admin_area', [
            'gridView' => $gridView,
        ]);
    }

    public function editArea(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/area?');

        $area = Area::find($id);

        if(empty($area))
            return view('backend.errors.404');

        return view('backend.areas.edit_area', [
            'area' => $area,
        ]);
    }
}