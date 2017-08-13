<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Models\Widget;

class WidgetController extends Controller
{
    public function adminWidget(Request $request)
    {
        $dataProvider = Widget::select('id', 'name', 'status');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('name', 'like', '%' . $inputs['name'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('status', $inputs['status']);
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\WidgetController@editWidget', ['id' => $row->id]),
                    ]);
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
                'title' => 'Trạng Thái',
                'name' => 'status',
                'type' => 'select',
                'options' => Utility::getTrueFalse(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.widgets.admin_widget', [
            'gridView' => $gridView,
        ]);
    }

    public function editWidget(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/widget?');

        $widget = Widget::find($id);

        if(empty($widget))
            return view('backend.errors.404');

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $details = array();

            if(isset($inputs['custom_detail']))
                $details['custom_detail'] = $inputs['custom_detail'];

            if(isset($inputs['detail']))
            {
                foreach($inputs['detail'] as $attribute => $attributeItems)
                {
                    foreach($attributeItems as $key => $item)
                    {
                        if(!empty($item))
                            $details[$key][$attribute] = $item;
                    }
                }
            }

            $widget->detail = json_encode($details);
            $widget->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;

            $widget->save();

            return redirect()->action('Backend\WidgetController@editWidget', ['id' => $widget->id])->with('messageSuccess', 'Thành Công');
        }

        return view('backend.widgets.edit_widget', [
            'widget' => $widget,
        ]);
    }
}