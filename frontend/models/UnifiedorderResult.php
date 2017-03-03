<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%unifiedorder_result}}".
 *
 * @property integer $id
 * @property integer $uoid
 * @property string $appid
 * @property string $mch_id
 * @property string $device_info
 * @property string $nonce_str
 * @property string $sign
 * @property string $return_code
 * @property string $err_code
 * @property string $err_code_des
 * @property string $openid
 * @property string $is_subscribe
 * @property string $trade_type
 * @property string $bank_type
 * @property string $total_fee
 * @property string $fee_type
 * @property string $cash_fee
 * @property string $cash_fee_type
 * @property string $coupon_fee
 * @property integer $coupon_count
 * @property string $coupon_id_
 * @property string $coupon_fee_
 * @property string $transaction_id
 * @property string $out_trade_no
 * @property string $attach
 * @property string $time_end
 * @property string $actiontime
 * @property string $code
 * @property string $requesturl
 */
class UnifiedorderResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%unifiedorder_result}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uoid', 'coupon_count'], 'integer'],
            [['trade_type'], 'required'],
            [['total_fee', 'cash_fee', 'coupon_fee', 'coupon_fee_'], 'number'],
            [['actiontime'], 'safe'],
            [['appid', 'mch_id', 'device_info', 'nonce_str', 'sign', 'err_code', 'transaction_id', 'out_trade_no', 'code'], 'string', 'max' => 32],
            [['return_code', 'trade_type', 'bank_type', 'cash_fee_type'], 'string', 'max' => 16],
            [['err_code_des', 'openid', 'attach'], 'string', 'max' => 128],
            [['is_subscribe'], 'string', 'max' => 1],
            [['fee_type'], 'string', 'max' => 8],
            [['coupon_id_'], 'string', 'max' => 20],
            [['time_end'], 'string', 'max' => 14],
            [['requesturl'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uoid' => 'Uoid',
            'appid' => 'Appid',
            'mch_id' => 'Mch ID',
            'device_info' => 'Device Info',
            'nonce_str' => 'Nonce Str',
            'sign' => 'Sign',
            'return_code' => 'Return Code',
            'err_code' => 'Err Code',
            'err_code_des' => 'Err Code Des',
            'openid' => 'Openid',
            'is_subscribe' => 'Is Subscribe',
            'trade_type' => 'Trade Type',
            'bank_type' => 'Bank Type',
            'total_fee' => 'Total Fee',
            'fee_type' => 'Fee Type',
            'cash_fee' => 'Cash Fee',
            'cash_fee_type' => 'Cash Fee Type',
            'coupon_fee' => 'Coupon Fee',
            'coupon_count' => 'Coupon Count',
            'coupon_id_' => 'Coupon ID',
            'coupon_fee_' => 'Coupon Fee',
            'transaction_id' => 'Transaction ID',
            'out_trade_no' => 'Out Trade No',
            'attach' => 'Attach',
            'time_end' => 'Time End',
            'actiontime' => 'Actiontime',
            'code' => 'Code',
            'requesturl' => 'Requesturl',
        ];
    }
}
