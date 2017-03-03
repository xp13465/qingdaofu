<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%unifiedorder}}".
 *
 * @property integer $id
 * @property string $appid
 * @property string $mch_id
 * @property string $device_info
 * @property string $nonce_str
 * @property string $sign
 * @property string $body
 * @property string $detail
 * @property string $attach
 * @property string $out_trade_no
 * @property string $fee_type
 * @property string $total_fee
 * @property string $spbill_create_ip
 * @property string $time_start
 * @property string $time_expire
 * @property string $goods_tag
 * @property string $notify_url
 * @property string $trade_type
 * @property string $limit_pay
 * @property string $actiontime
 */
class Unifiedorder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%unifiedorder}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['total_fee'], 'number'],
            [['trade_type'], 'required'],
            [['actiontime'], 'safe'],
            [['appid', 'mch_id', 'device_info', 'nonce_str', 'sign', 'out_trade_no', 'goods_tag', 'limit_pay'], 'string', 'max' => 32],
            [['body'], 'string', 'max' => 128],
            [['detail'], 'string', 'max' => 8192],
            [['attach'], 'string', 'max' => 127],
            [['fee_type', 'spbill_create_ip', 'trade_type'], 'string', 'max' => 16],
            [['time_start', 'time_expire'], 'string', 'max' => 14],
            [['notify_url'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'appid' => 'Appid',
            'mch_id' => 'Mch ID',
            'device_info' => 'Device Info',
            'nonce_str' => 'Nonce Str',
            'sign' => 'Sign',
            'body' => 'Body',
            'detail' => 'Detail',
            'attach' => 'Attach',
            'out_trade_no' => 'Out Trade No',
            'fee_type' => 'Fee Type',
            'total_fee' => 'Total Fee',
            'spbill_create_ip' => 'Spbill Create Ip',
            'time_start' => 'Time Start',
            'time_expire' => 'Time Expire',
            'goods_tag' => 'Goods Tag',
            'notify_url' => 'Notify Url',
            'trade_type' => 'Trade Type',
            'limit_pay' => 'Limit Pay',
            'actiontime' => 'Actiontime',
        ];
    }
}
