<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%contacts}}".
 *
 * @property integer $contactsid
 * @property integer $userid
 * @property string $mobile
 * @property string $name
 * @property string $status
 * @property string $validflag
 * @property integer $create_at
 * @property integer $create_by
 * @property integer $modify_at
 * @property integer $modify_by
 */
class Contacts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contacts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['status'], 'default', 'value' => '0'],
			[['create_at'], 'default', 'value' => time()],
            [['create_by'], 'default', 'value' => Yii::$app->user->getId() ],
			[['validflag'], 'default', 'value' => '1' ],
            [['userid', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['status'], 'required'],
            [['status', 'validflag'], 'string'],
            [['mobile', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contactsid' => 'Contactsid',
            'userid' => '好友ID',
            'mobile' => '电话号码',
            'name' => '姓名',
            'status' => '状态类型（0为请求中 1为同意申请，2不同意）',
            'validflag' => '回收状态',
            'create_at' => '创建时间',
            'create_by' => '创建人',
            'modify_at' => '修改时间',
            'modify_by' => '修改人',
        ];
    }

    /**
     * @inheritdoc
     * @return ContactsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ContactsQuery(get_called_class());
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getUserinfo()
    {
        return $this->hasOne(User::className(), ['id' => 'userid'])->alias("userinfo")
		->select(['userinfo.id',"userinfo.username","userinfo.mobile","userinfo.realname","userinfo.picture"])
		->joinWith("headimg");
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersOperator()
    {
        return $this->hasOne(ProductOrdersOperator::className(), ['operatorid' => 'userid'])->alias("ordersOperator")->onCondition(["ordersOperator.validflag"=>"1"])
		->select(['ordersOperator.id',"ordersOperator.level","ordersOperator.ordersid","ordersOperator.productid","ordersOperator.operatorid","ordersOperator.create_by","ordersOperator.validflag"]);
    }
	
	/**
	*	添加联系人
	*	
	*/
	public function apply($params=[] , $create_by = ''){
		$create_by=$create_by?:Yii::$app->user->getId();
		$this->setAttributes($params);
		if($create_by)$this->create_by = $create_by;
		if($this->userid==$create_by){
			$this->addError("userid","不可添加自己到通讯录");
			return MODELDATACHECK;
		}
		
		if(!$this->validate())return MODELDATACHECK;
		$count = self::find()->where(['validflag'=>'1','create_by'=>$this->create_by,"mobile"=>$this->mobile])->count();
		if($count>0){
			$this->addError("userid","该用户已在通讯录列表！");
			return MODELDATACHECK;
		}
		if($this->save()){
			// $ProductOrdersLog = new ProductOrdersLog;
			// $beforeStatus = $Orders->status;
			// $afterStatus = $Orders->status;
			// $ProductOrdersLog->create($this->ordersid,$this->closedid,'申请结案',$beforeStatus,$afterStatus,3,2);
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
	
	/**
	 * 删除联系人
	 *
	 *
	 */
	public function recy($contactsid='',$modify_by = ''){
		$modify_by = $modify_by?:Yii::$app->user->getId();
		$Contacts = self::find()->where(['contactsid'=>$contactsid,'validflag'=>1])->one(); 
		if(!$Contacts)return PARAMSCHECK;
		$return = $this->updateAll(["validflag"=>"0","modify_at"=>time(),"modify_by"=>$modify_by],["validflag"=>"1","contactsid"=>$contactsid]);
		if($return){
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
	
}
