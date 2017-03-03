<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product_orders_termination}}".
 *
 * @property integer $terminationid
 * @property integer $productid
 * @property integer $ordersid
 * @property string $applymemo
 * @property string $resultmemo
 * @property string $status
 * @property string $validflag
 * @property integer $create_at
 * @property integer $create_by
 * @property integer $modify_at
 * @property integer $modify_by
 *
 * @property ProductOrders $orders
 */
class ProductOrdersTermination extends \yii\db\ActiveRecord
{
	public static function getDb()  
    {  
        return \Yii::$app->qdfdb;  
    } 
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_product_orders_termination';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['create_at'], 'default', 'value' => time()],
            [['create_by'], 'default', 'value' => Yii::$app->user->getId() ],
            [['validflag'], 'default', 'value' => '1' ],
            [['status'], 'default', 'value' => '0' ],
            [['productid', 'ordersid', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['status'], 'required'],
            [['status', 'validflag'], 'string'],
            [['applymemo', 'resultmemo'], 'string', 'max' => 2000],
            [['ordersid'], 'exist', 'skipOnError' => true, 'targetClass' => ProductOrders::className(), 'targetAttribute' => ['ordersid' => 'ordersid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'terminationid' => '接单id',
            'productid' => '产品ID',
            'ordersid' => '接单ID',
            'applymemo' => '申请原因',
            'resultmemo' => '结果原因',
            'status' => '状态（0申请终止中,10终止失败，20终止成功）',
            'validflag' => '回收状态',
            'create_at' => '创建时间',
            'create_by' => '创建人',
            'modify_at' => '修改时间',
            'modify_by' => '修改人',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasOne(ProductOrders::className(), ['ordersid' => 'ordersid']);
    }
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateuser()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by'])->alias("createuser")->joinWith(["headimg"])->select(['createuser.id','createuser.picture',"createuser.realname","createuser.username","createuser.mobile"]);
    }
	
	public function getFilesImg()
    {
		if($this->files){
			return \app\models\QdfFiles::getFiles(explode(",",$this->files));
		}else{
			return [];
		}
    }
	
	/**
	*	申请终止数据操作
	*	
	*/
	public function apply($params=[] , $create_by = ''){
		$Orders = ProductOrders::find()->where(['ordersid'=>$params['ordersid'],'validflag'=>1])->one(); 
		if(!$Orders)return 'ParamsCheck';
		$statusLabel = $Orders->checkStatus();
		if($statusLabel!='ORDERSPROCESS'){
			return $statusLabel;
		}
		if($Orders->productOrdersTerminationsApplyCount>0)return 'EXISTED';
		
		$this->setAttributes($params);
		$this->applymemo = htmlspecialchars($this->applymemo);
		$this->productid = $Orders->productid;
		$this->status = '0';
		$this->validate();
		if($create_by)$this->create_by = $create_by;
		if(!$this->validate())return MODELDATACHECK;
		if($this->save()){
			$ProductOrdersLog = new ProductOrdersLog;
			$beforeStatus = $Orders->status;
			$afterStatus = $Orders->status;
			$ProductOrdersLog->create($this->ordersid,$this->terminationid,$this->applymemo,$beforeStatus,$afterStatus,50,2);
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
	/**
	 * 同意终止数据操作
	 *
	 *
	 */
	public function agree($resultmemo='',$modify_by = ''){
		$resultmemo = htmlspecialchars($resultmemo);
		$modify_by = $modify_by?$modify_by:Yii::$app->user->getId();
		$status = $this->updateAll(['modify_by'=>$modify_by,'resultmemo'=>$resultmemo,'modify_at'=>time(),'status'=>'20'],["terminationid"=>$this->terminationid,'status'=>'0']);
		if($status){
			$ProductOrdersLog = new ProductOrdersLog;
			$beforeStatus = $this->status;
			$afterStatus = $this->status;
			$ProductOrdersLog->create($this->ordersid,$this->terminationid,$resultmemo,20,40,51,2);
			$ProductOrdersLog->updateAll(["relatrigger"=>"1"],["action"=>"50","ordersid"=>$this->ordersid,"relaid"=>$this->terminationid]);
			return OK;
		}else{
			return PAGETIMEOUT;
		}
		
	}
	/**
	 * 否决终止数据操作
	 *
	 *
	 */
	public function veto($resultmemo='',$modify_by = ''){
		$resultmemo = htmlspecialchars($resultmemo);
		$modify_by = $modify_by?$modify_by:Yii::$app->user->getId();
		$status = $this->updateAll(['modify_by'=>$modify_by,'resultmemo'=>$resultmemo,'modify_at'=>time(),'status'=>'10'],["terminationid"=>$this->terminationid,'status'=>'0']);
		if($status){
			$ProductOrdersLog = new ProductOrdersLog;
			$beforeStatus = $this->status;
			$afterStatus = $this->status;
			$ProductOrdersLog->create($this->ordersid,$this->terminationid,$resultmemo,20,20,52,2);
			$ProductOrdersLog->updateAll(["relatrigger"=>"1"],["action"=>"50","ordersid"=>$this->ordersid,"relaid"=>$this->terminationid]);
			return OK;
		}else{
			return PAGETIMEOUT;
		}
		
	}
	
	
	/**
	 * 返回终止所处阶段
	 *
	 */
	public function checkStatus(){
		switch($this->status){
			case 20:
			return 'TERMINATIONAGREE';
			break;
			case 10:
			return 'TERMINATIONVETO';
			break;
		}
		return 'TERMINATIONAPPLY';
	}
}
