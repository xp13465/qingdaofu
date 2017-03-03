<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product_orders_closed}}".
 *
 * @property integer $closedid
 * @property integer $productid
 * @property integer $ordersid
 * @property string $price
 * @property string $price2
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
class ProductOrdersClosed extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_orders_closed}}';
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
            [['price', 'price2'], 'required'],
            [['price', 'price2'], 'number'],
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
            'closedid' => '结案id',
            'productid' => '产品ID',
            'ordersid' => '接单ID',
            'price' => '实际结案金额',
            'price2' => '实际结案佣金',
            'applymemo' => '申请原因',
            'resultmemo' => '结果原因',
            'status' => '状态（0申请结案中,10结案失败，20结案成功）',
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
	
	 public function getProduct()
    {
        return $this->hasOne(Product::className(), ['productid' => 'productid']);
    }
	
	/**
	*	申请结案数据操作
	*	
	*/
	public function apply($params=[] , $create_by = ''){
		$Orders = ProductOrders::find()->where(['ordersid'=>$params['ordersid'],'validflag'=>1])->one(); 
		if(!$Orders)return PARAMSCHECK;
		if(!$Orders->accessClosed('APPLY',$create_by)){
			return USERAUTH;
		}
		$statusLabel = $Orders->checkStatus();
		if($statusLabel!='ORDERSPROCESS'){
			return $statusLabel;
		}
		if($Orders->productOrdersClosedsApplyCount>0)return 'EXISTED';
		
		$this->setAttributes($params);
		$this->applymemo = htmlspecialchars($this->applymemo);
		$this->productid = $Orders->productid;
		$this->status = '0';
		if($create_by)$this->create_by = $create_by;
		if(!$this->validate())return MODELDATACHECK;
		$this->price = $this->price*10000;
		$this->price2 = $this->price2*10000;
		if($this->save()){
			$ProductOrdersLog = new ProductOrdersLog;
			$beforeStatus = $Orders->status;
			$afterStatus = $Orders->status;
			$ProductOrdersLog->create($this->ordersid,$this->closedid,$this->applymemo,$beforeStatus,$afterStatus,40,2);
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
	/**
	 * 同意结案数据操作
	 *
	 *
	 */
	public function agree($resultmemo='',$modify_by = ''){
		$resultmemo = htmlspecialchars($resultmemo);
		$modify_by = $modify_by?$modify_by:Yii::$app->user->getId();
		$status = $this->updateAll(['modify_by'=>$modify_by,'resultmemo'=>$resultmemo,'modify_at'=>time(),'status'=>'20'],["closedid"=>$this->closedid,'status'=>'0']);
		if($status){
			$ProductOrdersLog = new ProductOrdersLog;
			$beforeStatus = $this->status;
			$afterStatus = $this->status;
			$ProductOrdersLog->create($this->ordersid,$this->closedid,$resultmemo,20,40,41,2);
			$ProductOrdersLog->updateAll(["relatrigger"=>"1"],["action"=>"40","ordersid"=>$this->ordersid,"relaid"=>$this->closedid]);
			return OK;
		}else{
			return PAGETIMEOUT;
		}
		
	}
	/**
	 * 否决结案数据操作
	 * @param resultmemo 否决的原因
	 * @param modify_by 修改人的ID
	 */
	public function veto($resultmemo='',$modify_by = ''){
		$resultmemo = htmlspecialchars($resultmemo);
		$modify_by = $modify_by?$modify_by:Yii::$app->user->getId();
		$status = $this->updateAll(['modify_by'=>$modify_by,'resultmemo'=>$resultmemo,'modify_at'=>time(),'status'=>'10'],["closedid"=>$this->closedid,'status'=>'0']);
		if($status){
			$ProductOrdersLog = new ProductOrdersLog;
			$beforeStatus = $this->status;
			$afterStatus = $this->status;
			$ProductOrdersLog->create($this->ordersid,$this->closedid,$resultmemo,20,20,42,2);
			$ProductOrdersLog->updateAll(["relatrigger"=>"1"],["action"=>"40","ordersid"=>$this->ordersid,"relaid"=>$this->closedid]);
			return OK;
		}else{
			return PAGETIMEOUT;
		}
		
	}
	
	/**
	 * 返回结案所处阶段
	 *
	 */
	public function checkStatus(){
		switch($this->status){
			case 20:
			return 'CLOSEDAGREE';
			break;
			case 10:
			return 'CLOSEDVETO';
			break;
		}
		return 'CLOSEDAPPLY';
	}
}
