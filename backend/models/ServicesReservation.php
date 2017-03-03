<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%services_reservation}}".
 *
 * @property integer $id
 * @property string $contacts
 * @property string $tel
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $district_id
 * @property string $desc
 * @property string $status
 * @property integer $create_user
 * @property integer $create_time
 * @property integer $modify_user
 * @property integer $modify_time
 */
class ServicesReservation extends \yii\db\ActiveRecord
{
	public static $status = [
		"10"=>"待处理",
		"20"=>"已处理",
		"30"=>"忽略",
	];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%services_reservation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_id', 'city_id', 'district_id', 'create_user', 'create_time', 'modify_user', 'modify_time'], 'integer'],
            [['status'], 'string'],
            [['contacts', 'tel'], 'string', 'max' => 20],
            [['desc'], 'string', 'max' => 2000],
        ];
    }

     /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contacts' => '联系人',
            'tel' => '联系方式',
            'province_id' => '省',
            'city_id' => '市',
            'district_id' => '区',
            'desc' => '问题描述',
			'auditmemo' => '处理备注',
            'status' => '状态',// 10提交中  20已处理  30忽略
            'validflag' => '回收标志',
            'create_user' => '创建用户',
            'create_time' => '创建时间',
            'modify_user' => '修改用户',
            'modify_time' => '修改时间',
        ];
    }
	public function getCreateuser(){
		return $this->hasOne(\common\models\User::className(), ['id'=>'create_user']);
	}
	public function getModifyuser(){
		return $this->hasOne(\common\models\User::className(), ['id'=>'modify_user']);
	}
	public function getProvincename(){
		return $this->hasOne(\common\models\Province::className(), ['provinceID'=>'province_id'])->select('province,provinceID');
	}
	public function getCityname(){
		return $this->hasOne(\common\models\City::className(), ['cityID'=>'city_id'])->select('city,cityID');
	}
	public function getAreaname(){
		return $this->hasOne(\common\models\Area::className(), ['areaID'=>'district_id'])->select('area,areaID');
	}
}
