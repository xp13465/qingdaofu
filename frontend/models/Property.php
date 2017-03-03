<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%property}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $province
 * @property string $city
 * @property string $address
 * @property integer $cid
 * @property string $phone
 * @property string $money
 * @property integer $time
 * @property integer $uptime
 * @property integer $status
 * @property string $orderId
 */
class Property extends \frontend\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%property}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['type'], 'default', 'value' => 1],
            [['uid','money','city','address','phone'], 'required'],
            [['uid', 'cid', 'time', 'uptime', 'status', 'type'], 'integer'],
			[['type'], 'in', 'range' => [1,2]],
            [['money'], 'number'],
            [['province', 'city'], 'string', 'max' => 4],
            [['address'], 'string', 'max' => 225],
            [['name'], 'string', 'max' => 100],
			[['phone'],'match','pattern'=>'/^1[0-9]{10}$/','message'=>"请输入正确的手机号码！"],
            [['orderId'], 'string', 'max' => 32],
            [['orderId'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'province' => 'Province',
            'city' => '区域',
            'address' => '详细地址',
            'cid' => 'Cid',
            'phone' => '电话号码',
            'money' => 'Money',
            'time' => 'Time',
            'uptime' => 'Uptime',
            'status' => 'Status',
            'orderId' => 'Order ID',
        ];
    }
}
