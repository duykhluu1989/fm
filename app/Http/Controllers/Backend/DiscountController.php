<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Models\Discount;
use App\Models\User;

class DiscountController extends Controller
{
    public function adminDiscount(Request $request)
    {
        $dataProvider = Discount::select('id', 'code', 'start_time', 'end_time', 'status', 'type', 'value', 'value_limit', 'code', 'used_count', 'campaign_code', 'minimum_order_amount', 'usage_limit')
            ->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['code']))
                $dataProvider->where('code', 'like', '%' . $inputs['code'] . '%');

            if(isset($inputs['type']) && $inputs['type'] !== '')
                $dataProvider->where('type', $inputs['type']);

            if(!empty($inputs['campaign_code']))
                $dataProvider->where('campaign_code', 'like', '%' . $inputs['campaign_code'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('status', $inputs['status']);
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Mã',
                'data' => function($row) {
                    echo Html::a($row->code, [
                        'href' => action('Backend\DiscountController@editDiscount', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Giá Trị Đơn Hàng Tối Thiểu',
                'data' => function($row) {
                    echo Utility::formatNumber($row->minimum_order_amount) . ' VND';
                },
            ],
            [
                'title' => 'Thời Gian Bắt Đầu',
                'data' => 'start_time',
            ],
            [
                'title' => 'Thời Gian Kết Thúc',
                'data' => 'end_time',
            ],
            [
                'title' => 'Loại Giảm Giá',
                'data' => function($row) {
                    echo Discount::getDiscountType($row->type);
                },
            ],
            [
                'title' => 'Giá Trị Giảm Giá',
                'data' => function($row) {
                    if($row->type == Discount::TYPE_FIX_AMOUNT_DB)
                        echo Utility::formatNumber($row->value) . ' VND';
                    else
                    {
                        $value = $row->value . ' %';
                        if(!empty($row->value_limit))
                            $value .= ' (Tối Đa: ' . Utility::formatNumber($row->value_limit) . ' VND)';
                        echo $value;
                    }
                },
            ],
            [
                'title' => 'Mã Chương Trình',
                'data' => 'campaign_code',
            ],
            [
                'title' => 'Số Lần Sử Dụng Tổng',
                'data' => function($row) {
                    if(empty($row->usage_limit))
                        echo 'Không Giới Hạn';
                    else
                        echo Utility::formatNumber($row->usage_limit);
                },
            ],
            [
                'title' => 'Đã Sử Dụng',
                'data' => function($row) {
                    echo Utility::formatNumber($row->used_count);
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
        $gridView->setCheckbox();
        $gridView->setFilters([
            [
                'title' => 'Mã',
                'name' => 'code',
                'type' => 'input',
            ],
            [
                'title' => 'Loại Giảm Giá',
                'name' => 'type',
                'type' => 'select',
                'options' => Discount::getDiscountType(),
            ],
            [
                'title' => 'Mã Chương Trình',
                'name' => 'campaign_code',
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

        return view('backend.discounts.admin_discount', [
            'gridView' => $gridView,
        ]);
    }

    public function createDiscount(Request $request)
    {
        Utility::setBackUrlCookie($request, '/admin/discount?');

        $discount = new Discount();
        $discount->status = Utility::ACTIVE_DB;
        $discount->type = Discount::TYPE_FIX_AMOUNT_DB;
        $discount->value = 1;

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $inputs['value'] = implode('', explode('.', $inputs['value']));

            if(!empty($inputs['minimum_order_amount']))
                $inputs['minimum_order_amount'] = implode('', explode('.', $inputs['minimum_order_amount']));

            if(!empty($inputs['value_limit']))
                $inputs['value_limit'] = implode('', explode('.', $inputs['value_limit']));

            $validator = Validator::make($inputs, [
                'code' => 'required_without_all:create_multi_character_number,create_multi_number|nullable|alpha_num|unique:discount,code',
                'create_multi_character_number' => 'required_without:code|nullable|integer|min:1',
                'create_multi_number' => 'required_without:code|nullable|integer|min:1',
                'minimum_order_amount' => 'nullable|integer|min:1',
                'start_time' => 'nullable|date',
                'end_time' => 'nullable|date',
                'value' => 'required|integer|min:1',
                'value_limit' => 'nullable|integer|min:1',
                'usage_limit' => 'nullable|integer|min:1',
                'usage_unique' => 'nullable|integer|min:1',
                'campaign_code' => 'nullable|alpha_num|unique:discount,campaign_code',
            ]);

            $validator->after(function($validator) use(&$inputs) {
                if(!empty($inputs['user_name']))
                {
                    $userNameParts = explode(' - ', $inputs['user_name']);

                    if(count($userNameParts) == 2)
                    {
                        $user = User::select('id')
                            ->where('email', $userNameParts[1])
                            ->where('name', $userNameParts[0])
                            ->first();

                        if(!empty($user))
                            $inputs['user_id'] = $user->id;
                    }

                    if(!isset($inputs['user_id']))
                        $validator->errors()->add('user_name', 'Thành Viên Không Tồn Tại');
                }

                if($inputs['type'] == Discount::TYPE_PERCENTAGE_DB && $inputs['value'] > 99)
                    $validator->errors()->add('value', 'Phần Trăm Giảm Giá Không Được Lớn Hơn 99');
            });

            if($validator->passes())
            {
                if(!empty($inputs['user_id']))
                    $discount->user_id = $inputs['user_id'];

                $discount->minimum_order_amount = $inputs['minimum_order_amount'];
                $discount->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                $discount->start_time = $inputs['start_time'];
                $discount->end_time = $inputs['end_time'];
                $discount->type = $inputs['type'];
                $discount->value = $inputs['value'];
                $discount->value_limit = $inputs['value_limit'];
                $discount->usage_limit = $inputs['usage_limit'];
                $discount->usage_unique = $inputs['usage_unique'];
                $discount->description = $inputs['description'];
                $discount->campaign_code = strtoupper($inputs['campaign_code']);
                $discount->created_at = date('Y-m-d H:i:s');

                if(empty($inputs['create_multi_character_number']) && empty($inputs['create_multi_number']))
                {
                    $discount->code = strtoupper($inputs['code']);
                    $discount->save();

                    return redirect()->action('Backend\DiscountController@editDiscount', ['id' => $discount->id])->with('messageSuccess', 'Thành Công');
                }
                else
                {
                    try
                    {
                        DB::beginTransaction();

                        for($i = 1;$i <= $inputs['create_multi_number'];$i ++)
                        {
                            $newDiscount = clone $discount;
                            $newDiscount->code = Discount::generateCodeByNumberCharacter($inputs['create_multi_character_number']);
                            if(!empty($newDiscount->code))
                                $newDiscount->save();
                        }

                        DB::commit();

                        return redirect()->action('Backend\DiscountController@adminDiscount')->with('messageSuccess', 'Thành Công');
                    }
                    catch(\Exception $e)
                    {
                        DB::rollBack();

                        return redirect()->action('Backend\DiscountController@createDiscount')->withInput()->with('messageError', $e->getMessage());
                    }
                }
            }
            else
                return redirect()->action('Backend\DiscountController@createDiscount')->withErrors($validator)->withInput();
        }

        return view('backend.discounts.create_discount', [
            'discount' => $discount,
        ]);
    }

    public function editDiscount(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/discount?');

        $discount = Discount::with(['user' => function($query) {
            $query->select('id', 'name', 'email');
        }])->find($id);

        if(empty($discount))
            return view('backend.errors.404');

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'start_time' => 'nullable|date',
                'end_time' => 'nullable|date',
            ]);

            if($validator->passes())
            {
                $discount->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                $discount->start_time = $inputs['start_time'];
                $discount->end_time = $inputs['end_time'];
                $discount->save();

                return redirect()->action('Backend\DiscountController@editDiscount', ['id' => $discount->id])->with('messageSuccess', 'Thành Công');
            }
            else
                return redirect()->action('Backend\DiscountController@editDiscount', ['id' => $discount->id])->withErrors($validator)->withInput();
        }

        return view('backend.discounts.edit_discount', [
            'discount' => $discount,
        ]);
    }

    public function deleteDiscount($id)
    {
        $discount = Discount::with('discountApplies')->find($id);

        if(empty($discount) || $discount->isDeletable() == false)
            return view('backend.errors.404');

        $discount->delete();

        return redirect(Utility::getBackUrlCookie(action('Backend\DiscountController@adminDiscount')))->with('messageSuccess', 'Thành Công');
    }

    public function controlDeleteDiscount(Request $request)
    {
        $ids = $request->input('ids');

        $discounts = Discount::with('discountApplies')->whereIn('id', explode(';', $ids))->get();

        foreach($discounts as $discount)
        {
            if($discount->isDeletable() == true)
                $discount->delete();
        }

        if($request->headers->has('referer'))
            return redirect($request->headers->get('referer'))->with('messageSuccess', 'Thành Công');
        else
            return redirect()->action('Backend\DiscountController@adminDiscount')->with('messageSuccess', 'Thành Công');
    }

    public function generateDiscountCode(Request $request)
    {
        $number = $request->input('number');

        return Discount::generateCodeByNumberCharacter($number);
    }
}