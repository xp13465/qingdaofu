<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%services_instrument}}".
 *
 * @property integer $id
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $district_id
 * @property string $address
 * @property string $type
 * @property string $desc
 * @property string $plaintiff
 * @property string $defendant
 * @property string $contacts
 * @property string $tel
 * @property string $status
 * @property integer $create_user
 * @property integer $create_time
 * @property integer $modify_user
 * @property integer $modify_time
 */
class ServicesInstrument extends \yii\db\ActiveRecord
{
	public static $type=[
		"1"=>"离婚",
		"2"=>"继承",
		"3"=>"房产",
		"4"=>"拆迁",
		"5"=>"合同债务",
		"6"=>"劳务工伤",
		"7"=>"破产清算",
		"8"=>"理财投资",
		"9"=>"交通事故",
		"10"=>"留学",
		"11"=>"移民",
		"12"=>"税务",
	];
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
        return '{{%services_instrument}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_id', 'city_id', 'district_id', 'create_user', 'create_time', 'modify_user', 'modify_time'], 'integer'],
            [['type'], 'required'],
            [['type', 'status'], 'string'],
            [['address'], 'string', 'max' => 100],
            [['desc'], 'string', 'max' => 2000],
            [['plaintiff', 'defendant'], 'string', 'max' => 30],
            [['contacts', 'tel'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'province_id' => '省',
            'city_id' => '市',
            'district_id' => '区',
            'address' => '详细地址',
            'type' => '案件类型',// ,1离婚,2继承,3房产,4拆迁,5合同债务,6劳务工伤,7破产清算,8理财投资,9交通事故,10留学,11移民,12税务,
            'desc' => '案情描述',
            'plaintiff' => '原告',
            'defendant' => '被告',
            'contacts' => '联系人',
            'tel' => '联系方式',
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
