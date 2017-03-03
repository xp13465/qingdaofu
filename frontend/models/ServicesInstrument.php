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
			[['province_id','city_id','district_id',], 'required',"message"=>"请选择所在城市"],
            [['create_user', 'create_time', 'modify_user', 'modify_time'], 'integer'],
            [['type' , 'address', 'desc', 'plaintiff', 'defendant', 'contacts', 'tel'], 'required'],
            [['type', 'status'], 'string'],
			// [['address'],'match','pattern'=>'/^(\w*[^\x00-\xff]+\w*)*$/','message'=>"请输入正确的地址！"],
            [['address'], 'string', 'max' => 100],
            [['desc'], 'string', 'min' => 5 ,'max' => 2000],
            [['plaintiff', 'defendant'], 'string', 'max' => 30],
			[['tel'],'match','pattern'=>'/^1[0-9]{10}$/','message'=>"请输入正确的手机号码！"],
            [['contacts'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'province_id' => '省份ID',
            'city_id' => '城市ID',
            'district_id' => '区域ID',
            'address' => '详细地址',
            'type' => '案件类型',// ,1离婚,2继承,3房产,4拆迁,5合同债务,6劳务工伤,7破产清算,8理财投资,9交通事故,10留学,11移民,12税务,
            'desc' => '案情描述',
            'plaintiff' => '原告',
            'defendant' => '被告',
            'contacts' => '联系人',
            'tel' => '联系方式',
            'status' => '状态 10提交中  20已处理  30忽略',
            'create_user' => 'Create User',
            'create_time' => 'Create Time',
            'modify_user' => 'Modify User',
            'modify_time' => 'Modify Time',
        ];
    }

    /**
     * @inheritdoc
     * @return ServicesInstrumentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ServicesInstrumentQuery(get_called_class());
    }
	
	
	public function change($data=[],$action_by = ''){
		$action_by=$action_by?:Yii::$app->user->getId();
		$this->setAttributes($data); 
		if($this->isNewRecord){
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
