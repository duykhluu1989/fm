<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Models\Zone;

class ZoneController extends Controller
{
    public function adminZone(Request $request)
    {
        $dataProvider = Zone::orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('name', 'like', '%' . $inputs['name'] . '%');
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\ZoneController@editZone', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Mô Tả',
                'data' => 'description',
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
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.zones.admin_zone', [
            'gridView' => $gridView,
        ]);
    }

    public function createZone(Request $request)
    {
        Utility::setBackUrlCookie($request, '/admin/zone?');

        $zone = new Zone();

        return $this->saveZone($request, $zone);
    }

    public function editZone(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/zone?');

        $zone = Zone::find($id);

        if(empty($zone))
            return view('backend.errors.404');

        return $this->saveZone($request, $zone, false);
    }

    protected function saveZone($request, $zone, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required|unique:zone,name' . ($create == true ? '' : (',' . $zone->id)),
            ]);

            if($validator->passes())
            {
                $zone->name = $inputs['name'];
                $zone->description = $inputs['description'];
                $zone->save();

                return redirect()->action('Backend\ZoneController@editZone', ['id' => $zone->id])->with('messageSuccess', 'Thành Công');
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\ZoneController@createZone')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\ZoneController@editZone', ['id' => $zone->id])->withErrors($validator)->withInput();
            }
        }

        if($create == true)
        {
            return view('backend.zones.create_zone', [
                'zone' => $zone,
            ]);
        }
        else
        {
            return view('backend.zones.edit_zone', [
                'zone' => $zone,
            ]);
        }
    }

    public function deleteZone($id)
    {
        $zone = Zone::with('runs')->find($id);

        if(empty($zone))
            return view('backend.errors.404');

        try
        {
            DB::beginTransaction();

            $zone->doDelete();

            DB::commit();

            return redirect(Utility::getBackUrlCookie(action('Backend\ZoneController@adminZone')))->with('messageSuccess', 'Thành Công');
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            return redirect()->action('Backend\ZoneController@editZone', ['id' => $id])->with('messageError', $e->getMessage());
        }
    }

    public function controlDeleteZone(Request $request)
    {
        $ids = $request->input('ids');

        $zones = Zone::with('runs')->whereIn('id', explode(';', $ids))->get();

        foreach($zones as $zone)
        {
            try
            {
                DB::beginTransaction();

                $zone->doDelete();

                DB::commit();
            }
            catch(\Exception $e)
            {
                DB::rollBack();

                return redirect()->action('Backend\ZoneController@adminZone')->with('messageError', $e->getMessage());
            }
        }

        if($request->headers->has('referer'))
            return redirect($request->headers->get('referer'))->with('messageSuccess', 'Thành Công');
        else
            return redirect()->action('Backend\ZoneController@adminZone')->with('messageSuccess', 'Thành Công');
    }
}