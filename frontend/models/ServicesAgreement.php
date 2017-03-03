<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%services_agreement}}".
 *
 * @property integer $id
 * @property string $type
 * @property string $contacts
 * @property string $tel
 * @property string $desc
 * @property string $status
 * @property integer $create_user
 * @property integer $create_time
 * @property integer $modify_user
 * @property integer $modify_time
 */
class ServicesAgreement extends \yii\db\ActiveRecord
{
	public static $type=[
		"1"=>"买卖合同",
		"2"=>"赠与合同",
		"3"=>"借款合同",
		"4"=>"租赁合同",
		"5"=>"融资租赁合同",
		"6"=>"承揽合同",
		"7"=>"建设工程合同",
		"8"=>"运输合同",
		"9"=>"技术合同",
		"10"=>"仓储合同",
		"11"=>"委托合同",
		"12"=>"行纪合同",
		"13"=>"居间合同",
	];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%services_agreement}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type','desc','contacts', 'tel'], 'required'],
            [['type', 'status'], 'string'],
            [['type'], 'in', 'range' => array_keys(self::$type)],
            [['create_user', 'create_time', 'modify_user', 'modify_time'], 'integer'],
            [['contacts'], 'string', 'max' => 20],
			[['tel'],'match','pattern'=>'/^1[0-9]{10}$/','message'=>"请输入正确的手机号码！"],
            [['desc'], 'string', 'min' => 5, 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '合同类型',// ,1买卖合同,2赠与合同,3借款合同,4租赁合同,5融资租赁合同,6承揽合同,7建设工程合同,8运输合同,9技术合同,10仓储合同,11委托合同,12行纪合同,13居间合同
            'contacts' => '联系人',
            'tel' => '联系方式',
            'desc' => '合同描述',
            'status' => '状态',// 10提交中  20已处理  30忽略
            'create_user' => 'Create User',
            'create_time' => 'Create Time',
            'modify_user' => 'Modify User',
            'modify_time' => 'Modify Time',
        ];
    }

    /**
     * @inheritdoc
     * @return ServicesAgreementQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ServicesAgreementQuery(get_called_class());
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
