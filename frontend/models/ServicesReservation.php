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
			[['province_id','city_id','district_id',], 'required',"message"=>"请选择所在城市"],
			[['contacts', 'tel', 'desc'], 'required'],
            [['province_id', 'city_id', 'district_id', 'create_user', 'create_time', 'modify_user', 'modify_time'], 'integer'],
            [['status'], 'string'],
			[['tel'],'match','pattern'=>'/^1[0-9]{10}$/','message'=>"请输入正确的手机号码！"],
            [['contacts'], 'string', 'max' => 20],
            [['desc'], 'string', 'min' => 5 ,'max' => 2000],
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
            'province_id' => '省份ID',
            'city_id' => '城市ID',
            'district_id' => '区域ID',
            'desc' => '问题描述',
            'status' => '状态',// 10提交中  20已处理  30忽略
            'create_user' => 'Create User',
            'create_time' => 'Create Time',
            'modify_user' => 'Modify User',
            'modify_time' => 'Modify Time',
        ];
    }

    /**
     * @inheritdoc
     * @return ServicesReservationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ServicesReservationQuery(get_called_class());
    }
	
	
	public function change($data=[],$action_by = ''){
		$action_by=$action_by?:Yii::$app->user->getId();
		$this->setAttributes($data); 
		if($this->isNewRecord){
			$this->status = "10";
			$this->create_user = $action_by;
			$this->create_time = time();
		}else{
			$this->modify_user = $action_by;
			$this->modify_time = time();
		}
		if(!$this->validate())return MODELDATACHECK;
		if($this->save()){
			return OK;
		}else{
			return MODELDATASAVE;
		}
		
	}
}
