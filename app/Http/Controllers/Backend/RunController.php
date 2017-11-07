<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Models\Area;
use App\Models\Run;
use App\Models\RunArea;
use App\Models\Zone;

class RunController extends Controller
{
    public function adminRun(Request $request)
    {
        $dataProvider = Run::with('zone')->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('name', 'like', '%' . $inputs['name'] . '%');

            if(!empty($inputs['zone_id']))
                $dataProvider->where('zone_id', $inputs['zone_id']);
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\RunController@editRun', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Zone',
                'data' => function($row) {
                    echo $row->zone->name;
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
            [
                'title' => 'Zone',
                'name' => 'zone_id',
                'type' => 'select',
                'options' => Zone::pluck('name', 'id'),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.runs.admin_run', [
            'gridView' => $gridView,
        ]);
    }

    public function createRun(Request $request)
    {
        Utility::setBackUrlCookie($request, '/admin/run?');

        $run = new Run();

        return $this->saveRun($request, $run);
    }

    public function editRun(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/run?');

        $run = Run::with('runAreas')->find($id);

        if(empty($run))
            return view('backend.errors.404');

        return $this->saveRun($request, $run, false);
    }

    protected function saveRun($request, $run, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required|unique:run,name' . ($create == true ? '' : (',' . $run->id)),
                'zone_id' => 'required|integer|min:1',
            ]);

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    $run->name = $inputs['name'];
                    $run->zone_id = $inputs['zone_id'];
                    $run->description = $inputs['description'];
                    $run->save();

                    if(isset($inputs['districts']))
                    {
                        foreach($run->runAreas as $runArea)
                        {
                            $key = array_search($runArea->area_id, $inputs['districts']);

                            if($key !== false)
                                unset($inputs['districts'][$key]);
                            else
                                $runArea->delete();
                        }

                        foreach($inputs['districts'] as $districtId)
                        {
                            $runArea = new RunArea();
                            $runArea->run_id = $run->id;
                            $runArea->area_id = $districtId;
                            $runArea->save();
                        }
                    }
                    else
                    {
                        foreach($run->runAreas as $runArea)
                            $runArea->delete();
                    }

                    DB::commit();

                    return redirect()->action('Backend\RunController@editRun', ['id' => $run->id])->with('messageSuccess', 'Thành Công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    if($create == true)
                        return redirect()->action('Backend\RunController@createRun')->withInput()->with('messageError', $e->getMessage());
                    else
                        return redirect()->action('Backend\RunController@editRun', ['id' => $run->id])->withInput()->with('messageError', $e->getMessage());
                }
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\RunController@createRun')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\RunController@editRun', ['id' => $run->id])->withErrors($validator)->withInput();
            }
        }

        $provinces = Area::with(['childrenAreas' => function($query) {
            $query->select('id', 'parent_id', 'name');
        }])->select('id', 'name')
            ->where('type', Area::TYPE_PROVINCE_DB)
            ->get();

        if($create == true)
        {
            return view('backend.runs.create_run', [
                'run' => $run,
                'provinces' => $provinces,
            ]);
        }
        else
        {
            return view('backend.runs.edit_run', [
                'run' => $run,
                'provinces' => $provinces,
            ]);
        }
    }

    public function deleteRun($id)
    {
        $run = Run::with('runAreas')->find($id);

        if(empty($run))
            return view('backend.errors.404');

        try
        {
            DB::beginTransaction();

            $run->doDelete();

            DB::commit();

            return redirect(Utility::getBackUrlCookie(action('Backend\RunController@adminRun')))->with('messageSuccess', 'Thành Công');
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            return redirect()->action('Backend\RunController@editRun', ['id' => $id])->with('messageError', $e->getMessage());
        }
    }

    public function controlDeleteRun(Request $request)
    {
        $ids = $request->input('ids');

        $runs = Run::with('runAreas')->whereIn('id', explode(';', $ids))->get();

        foreach($runs as $run)
        {
            try
            {
                DB::beginTransaction();

                $run->doDelete();

                DB::commit();
            }
            catch(\Exception $e)
            {
                DB::rollBack();

                return redirect()->action('Backend\RunController@adminRun')->with('messageError', $e->getMessage());
            }
        }

        if($request->headers->has('referer'))
            return redirect($request->headers->get('referer'))->with('messageSuccess', 'Thành Công');
        else
            return redirect()->action('Backend\RunController@adminRun')->with('messageSuccess', 'Thành Công');
    }
}