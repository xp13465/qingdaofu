<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zcb_product_apply".
 *
 * @property integer $applyid
 * @property integer $productid
 * @property string $status
 * @property string $validflag
 * @property integer $create_at
 * @property integer $create_by
 * @property integer $modify_at
 * @property integer $modify_by
 *
 * @property Product $product
 * @property ProductApplyLog[] $productApplyLogs
 * @property ProductOrders[] $productOrders
 */
class ProductApply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_product_apply';
    }
    
	//状态（10申请中，20面谈中，30取消面谈，40面谈成功, 50取消申请,60申请失败）
	public static $status = [
		// ''=>'全部',
		'1'=>'申请中',
		'2'=>'面谈中',
		'3'=>'取消面谈',
		'4'=>'处理中',
		'5'=>'取消申请',
		'6'=>'申请失败',
		'7'=>'已终止',
		'8'=>'已结案',
	];
	public static $ApplyStatus = [
		'10'=>'申请中',
		'20'=>'面谈中',
		'30'=>'取消面谈',
		'40'=>'处理中',
		'50'=>'取消申请',
		'60'=>'申请失败',
	];
	//状态
	public static $validflag = [
		// ''=>'全部',
		'1'=>'已回收',
		'2'=>'未回收',
	];
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productid', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['status'], 'required'],
            [['status', 'validflag'], 'string'],
            [['productid'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['productid' => 'productid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applyid' => '申请ID',
            'productid' => '产品ID',
            'status' => '状态（10申请中，20面谈中，30取消面谈，40面谈成功, 50取消申请,60申请失败）',
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
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['productid' => 'productid'])->alias('product');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductApplyLogs()
    {
        return $this->hasMany(ProductApplyLog::className(), ['applyid' => 'applyid']);
    }
	
	public function getUser(){
		return $this->hasOne(\common\models\User::className(),['id'=>'create_by'])->alias('user')->select(['id','mobile','username','realname']);
	}
	
	 public function getCertification()
    {
        return $this->hasOne(\common\models\Certification::className(),['uid' =>'create_by'])->alias("certifications");
    }
	
		
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasOne(ProductOrders::className(), ['applyid' => 'applyid'])->alias("orders")->onCondition(['orders.validflag'=>1]);
    }
	
		/**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateuser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'create_by'])->alias("createuser")->select(['createuser.id',"createuser.username","createuser.realname","createuser.mobile","createuser.pid","createuser.picture"]);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrders()
    {
        return $this->hasOne(ProductOrders::className(),['applyid' =>'applyid'])
		->alias('ordersApply')
		->joinWith([
		    'productOrdersLogs'=>function($query){
				  $query->joinWith(['actionUser']);
				 },
		    'productOrdersProcesses',
			'productOrdersTerminations',
			'productOrdersClosed',
			'productOrdersOperators',
			])
		->asArray();
    }
	
	/**
	*删除接单
	*/
	public function applyDel($applyid){
		$status = $this->updateAll(['validflag'=>'0','modify_at'=>time(),'modify_by'=>Yii::$app->user->getId()],['validflag'=>'1','applyid'=>$applyid]);
		if($status){
			$applyLog = new ProductApplyLog;
			$applyLog->create($this->applyid,'删除成功',$this->status,$this->status,Yii::$app->user->getId());
			return OK;			
		}else{
			return MODELDATASAVE;
		}
	}
	/**
	* 接单方申请失败
	*/
	public function applyFail($productid,$number='',$uid=''){
		$status = $this::updateAll(['status'=>'60','modify_at'=>time(),'modify_by'=>Yii::$app->user->getId()],['validflag'=>'1','status'=>['10','20'],'productid'=>$productid]);
		$data = $this->find()->where(['productid'=>$productid,'validflag'=>'1','status'=>'60'])->asArray()->all();
		$number = isset($this->product->number)&&$this->product->number?$this->product->number:$number;
		$uid = isset($this->product->create_by)&&$this->product->create_by?$this->product->create_by:$uid;
		foreach($data as $value){
			$message = \app\models\Message::find();
			$data = $message->addMessage(118,['code'=>$number],$value['create_by'],$value['applyid'],50,$uid);
		}
		if($status>=0){
			$applyLog = new ProductApplyLog;
			$applyLog->create($this->applyid,'申请失败','60','10',Yii::$app->user->getId());
			return OK;
		}else{
			return MODELDATASAVE;
		}
		
	}
	

}
