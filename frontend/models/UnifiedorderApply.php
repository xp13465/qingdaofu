<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%unifiedorder_apply}}".
 *
 * @property integer $id
 * @property integer $uoid
 * @property string $return_code
 * @property string $return_msg
 * @property string $appid
 * @property string $mch_id
 * @property string $device_info
 * @property string $nonce_str
 * @property string $result_code
 * @property string $err_code
 * @property string $err_code_des
 * @property string $trade_type
 * @property string $prepay_id
 * @property string $actiontime
 */
class UnifiedorderApply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%unifiedorder_apply}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uoid'], 'integer'],
            [['actiontime'], 'safe'],
            [['return_code', 'result_code', 'trade_type'], 'string', 'max' => 16],
            [['return_msg', 'err_code_des'], 'string', 'max' => 128],
            [['appid', 'mch_id', 'device_info', 'nonce_str', 'err_code'], 'string', 'max' => 32],
            [['prepay_id'], 'string', 'max' => 64],
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
            'return_code' => 'Return Code',
            'return_msg' => 'Return Msg',
            'appid' => 'Appid',
            'mch_id' => 'Mch ID',
            'device_info' => 'Device Info',
            'nonce_str' => 'Nonce Str',
            'result_code' => 'Result Code',
            'err_code' => 'Err Code',
            'err_code_des' => 'Err Code Des',
            'trade_type' => 'Trade Type',
            'prepay_id' => 'Prepay ID',
            'actiontime' => 'Actiontime',
        ];
    }
}
