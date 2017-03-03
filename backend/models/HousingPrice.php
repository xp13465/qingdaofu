<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%housing_price}}".
 *
 * @property integer $id
 * @property string $serviceCode
 * @property string $city
 * @property string $district
 * @property string $address
 * @property string $buildingNumber
 * @property string $unitNumber
 * @property string $size
 * @property string $floor
 * @property string $maxFloor
 * @property integer $create_time
 * @property string $ip
 * @property string $code
 * @property string $msg
 * @property integer $totalPrice
 * @property integer $userid
 */
class HousingPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%housing_price}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['size'], 'number'],
            [['create_time', 'totalPrice', 'userid'], 'integer'],
            [['serviceCode', 'city', 'district', 'address', 'buildingNumber', 'unitNumber', 'floor', 'maxFloor'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 16],
            [['code', 'msg'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serviceCode' => '服务编码：S001',
            'city' => '城市',
            'district' => '区域',
            'address' => '小区名称或地址',
            'buildingNumber' => '楼号',
            'unitNumber' => '室号',
            'size' => '面积',
            'floor' => '所在楼层',
            'maxFloor' => '总楼层',
            'create_time' => '评估时间',
			'userid' => '用户',
            'ip' => '请求IP',
            'code' => '返回错误码',
            'msg' => '错误信息',
            'totalPrice' => '总价',
            
        ];
    }
	
	public function getuserinfo(){
		return $this->hasOne(\common\models\User::className(), ['id'=>'userid']);
	}
}
