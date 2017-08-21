<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Helpers\Utility;

class Discount extends Model
{
    const TYPE_PERCENTAGE_DB = 1;
    const TYPE_FIX_AMOUNT_DB = 0;
    const TYPE_PERCENTAGE_LABEL = 'Phần Trăm';
    const TYPE_FIX_AMOUNT_LABEL = 'Cố Định';

    protected $table = 'discount';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public static function getDiscountType($value = null)
    {
        $type = [
            self::TYPE_FIX_AMOUNT_DB => self::TYPE_FIX_AMOUNT_LABEL,
            self::TYPE_PERCENTAGE_DB => self::TYPE_PERCENTAGE_LABEL,
        ];

        if($value !== null && isset($type[$value]))
            return $type[$value];

        return $type;
    }

    public function isDeletable()
    {
        if($this->used_count > 0)
            return false;

        return true;
    }

    public static function generateCodeByNumberCharacter($number)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $charactersLength = strlen($characters);
        $times = 0;
        $maxTimes = 20;

        do
        {
            $randomString = '';

            for($i = 0; $i < $number; $i++)
                $randomString .= $characters[rand(0, $charactersLength - 1)];

            $discount = Discount::where('code', $randomString)->first();

            $times ++;
        }
        while(!empty($discount) && $times < $maxTimes);

        if(empty($discount))
            return $randomString;

        return null;
    }

    public static function calculateDiscountPrice($code)
    {
        $result = [
            'status' => 'error',
            'message' => 'Mã khuyến mãi không hợp lệ',
            'discount' => '',
            'discountPrice' => 0,
        ];

        $discount = Discount::where('code', $code)->where('status', Utility::ACTIVE_DB)->first();

        if(empty($discount))
            return $result;

        return $result;
    }
}