<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%express}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $orderId
 * @property integer $time
 * @property integer $uptime
 * @property integer $jid
 */
class Express extends \frontend\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%express}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','phone','address'], 'required'],
            [['time', 'uptime', 'jid'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['phone'], 'string', 'max' => 11],
            [['address'], 'string', 'max' => 100],
            [['orderId'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'phone' => 'Phone',
            'address' => 'Address',
            'orderId' => 'Order ID',
            'time' => 'Time',
            'uptime' => 'Uptime',
            'jid' => 'Jid',
        ];
    }
}
