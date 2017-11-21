<?php

namespace App\Libraries\Helpers;

class OrderExcel
{
    const IMPORT_USER_DO_COLUMN_LABEL = 'USER_DO';
    const IMPORT_SENDER_NAME_COLUMN_LABEL = 'SENDER_NAME';
    const IMPORT_SENDER_PHONE_COLUMN_LABEL = 'SENDER_PHONE';
    const IMPORT_SENDER_EMAIL_COLUMN_LABEL = 'SENDER_EMAIL';
    const IMPORT_SENDER_ADDRESS_COLUMN_LABEL = 'SENDER_ADDRESS';
    const IMPORT_SENDER_PROVINCE_COLUMN_LABEL = 'SENDER_PROVINCE';
    const IMPORT_SENDER_DISTRICT_COLUMN_LABEL = 'SENDER_DISTRICT';
    const IMPORT_SENDER_WARD_COLUMN_LABEL = 'SENDER_WARD';
    const IMPORT_RECEIVER_NAME_COLUMN_LABEL = 'RECEIVER_NAME';
    const IMPORT_RECEIVER_PHONE_COLUMN_LABEL = 'RECEIVER_PHONE';
    const IMPORT_RECEIVER_ADDRESS_COLUMN_LABEL = 'RECEIVER_ADDRESS';
    const IMPORT_RECEIVER_PROVINCE_COLUMN_LABEL = 'RECEIVER_PROVINCE';
    const IMPORT_RECEIVER_DISTRICT_COLUMN_LABEL = 'RECEIVER_DISTRICT';
    const IMPORT_RECEIVER_WARD_COLUMN_LABEL = 'RECEIVER_WARD';
    const IMPORT_WEIGHT_COLUMN_LABEL = 'WEIGHT';
    const IMPORT_DIMENSION_COLUMN_LABEL = 'DIMENSION';
    const IMPORT_COD_MONEY_COLUMN_LABEL = 'COD_MONEY';
    const IMPORT_DISCOUNT_CODE_COLUMN_LABEL = 'DISCOUNT_CODE';
    const IMPORT_PAY_SHIPPING_COLUMN_LABEL = 'PAY_SHIPPING';
    const IMPORT_NOTE_COLUMN_LABEL = 'NOTE';
    const IMPORT_BANK_HOLDER_COLUMN_LABEL = 'BANK_HOLDER';
    const IMPORT_BANK_NUMBER_COLUMN_LABEL = 'BANK_NUMBER';
    const IMPORT_BANK_NAME_COLUMN_LABEL = 'BANK_NAME';
    const IMPORT_BANK_BRANCH_COLUMN_LABEL = 'BANK_BRANCH';
    const IMPORT_PREPAY_COLUMN_LABEL = 'PREPAY';
    const IMPORT_BOXES_COLUMN_LABEL = 'BOXES';
    const IMPORT_ASSIGN_TO_COLUMN_LABEL = 'ASSIGN_TO';

    public static function getImportColumnLabel()
    {
        return [
            self::IMPORT_USER_DO_COLUMN_LABEL,
            self::IMPORT_SENDER_NAME_COLUMN_LABEL,
            self::IMPORT_SENDER_PHONE_COLUMN_LABEL,
            self::IMPORT_SENDER_EMAIL_COLUMN_LABEL,
            self::IMPORT_SENDER_ADDRESS_COLUMN_LABEL,
            self::IMPORT_SENDER_PROVINCE_COLUMN_LABEL,
            self::IMPORT_SENDER_DISTRICT_COLUMN_LABEL,
            self::IMPORT_SENDER_WARD_COLUMN_LABEL,
            self::IMPORT_RECEIVER_NAME_COLUMN_LABEL,
            self::IMPORT_RECEIVER_PHONE_COLUMN_LABEL,
            self::IMPORT_RECEIVER_ADDRESS_COLUMN_LABEL,
            self::IMPORT_RECEIVER_PROVINCE_COLUMN_LABEL,
            self::IMPORT_RECEIVER_DISTRICT_COLUMN_LABEL,
            self::IMPORT_RECEIVER_WARD_COLUMN_LABEL,
            self::IMPORT_WEIGHT_COLUMN_LABEL,
            self::IMPORT_DIMENSION_COLUMN_LABEL,
            self::IMPORT_COD_MONEY_COLUMN_LABEL,
            self::IMPORT_DISCOUNT_CODE_COLUMN_LABEL,
            self::IMPORT_PAY_SHIPPING_COLUMN_LABEL,
            self::IMPORT_NOTE_COLUMN_LABEL,
            self::IMPORT_BANK_HOLDER_COLUMN_LABEL,
            self::IMPORT_BANK_NUMBER_COLUMN_LABEL,
            self::IMPORT_BANK_NAME_COLUMN_LABEL,
            self::IMPORT_BANK_BRANCH_COLUMN_LABEL,
            self::IMPORT_PREPAY_COLUMN_LABEL,
            self::IMPORT_BOXES_COLUMN_LABEL,
            self::IMPORT_ASSIGN_TO_COLUMN_LABEL,
        ];
    }

    public static function getImportColumnDescription()
    {
        return [
            'Mã đơn hàng',
            'Tên người gửi',
            'Số điện thoại người gửi',
            'Email (để trống nếu đã đăng nhập tài khoản)',
            'Địa chỉ người gửi',
            'Tỉnh / thành phố',
            'Quận / huyện',
            'Phường / xã',
            'Tên người nhận',
            'Số điện thoại người nhận',
            'Địa chỉ người nhận',
            'Tỉnh / thành phố',
            'Quận / huyện',
            'Phường / xã',
            'Trọng lượng gói hàng (kg)',
            'Kích thước gói hàng (Dài x Rộng x Cao) (cm)',
            'Tiền thu hộ',
            'Mã giảm giá',
            'Người trả phí ship (để trống là người gửi trả, nhập 1 là người nhận trả)',
            'Ghi chú',
            'Chủ tài khoản ngân hàng',
            'Số tài khoản ngân hàng',
            'Tên ngân hàng',
            'Chi nhánh ngân hàng',
            'Ứng trước tiền thu hộ (để trống là không sử dụng, nhập 1 là sử dụng dịch vụ)',
            'Boxes',
            'Assign To',
        ];
    }

    public static function validateImportData($excelData)
    {
        if(count($excelData) > 2)
        {
            $columnLabels = self::getImportColumnLabel();

            foreach($columnLabels as $key => $label)
            {
                if(!isset($excelData[0][$key]) || Utility::removeWhitespace($excelData[0][$key], '') != $label)
                    return false;
            }

            return true;
        }

        return false;
    }

    public static function getExportColumnLabel()
    {
        return [
            'User DO',
            'D.O.',
            'Attempt',
        	'Date',
            'Start Date',
            'Age',
            'Order',
            'Job type',
            'Address',
            'Zip Code',
            'City',
            'State',
            'Country',
            'Deliver to / Collect from',
            'Phone',
            'Sender Phone',
            'Instructions',
            'Assign to',
            'Notify email',
            'Notify url',
            'Zone',
            'Pay back COD FM',
            'Pay back COD client',
            'Payment mode',
            'Payment amt (VND)',
            'Group',
            'Weight (kg)',
            'CBM',
            'Boxes',
            'Cartons',
            'Job title',
            'Envelopes',
            'Job Fee',
            'Detrack',
            'Status',
            'Time',
            'Reason',
            'Last Reason',
            'Received by',
            'Note',
            'POD lat',
            'POD lng',
            'POD address',
            'Address tracked at',
            'Arrived lat',
            'Arrived lng',
            'Arrived address',
            'Arrived at',
            'Texted at',
            'Called at',
            'Serial',
            'Signed at',
            'Photo 1 at',
            'Photo 2 at',
            'Photo 3 at',
            'Photo 4 at',
            'Photo 5 at',
            'Actual Weight',
            'Temperature',
            'Hold Time',
            'Payment Collected',
            'Actual Crates',
            'Actual Pallets',
            'Actual Utilization',
            'Goods Service Rating',
            'Driver Rating',
            'Feedback Remarks',
            'Pick up from',
            'Pick up address',
            'Pick up city',
            'Pick up state',
            'Pick up country',
            'Pick up zip code',
            'Pick up zone',
            'POD at',
            'Return Fee',
        ];
    }
}