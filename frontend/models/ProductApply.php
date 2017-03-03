<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product_apply}}".
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
 * @property ProductApplyMemo[] $productApplyMemos
 * @property ProductOrders[] $productOrders
 */
class ProductApply extends \yii\db\ActiveRecord
{
	public static $status = [
		'10'=>'申请中',
		'20'=>'面谈中',
		'30'=>'面谈失败',
		'40'=>'面谈成功',
		'50'=>'取消申请',
		'60'=>'申请失败',
	];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_apply}}';
    }

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
            'status' => '状态（10申请中，20面谈中，30取消面谈，40面谈成功）',
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
        return $this->hasOne(Product::className(), ['productid' => 'productid'])->alias("product")/*->onCondition(['product.validflag'=>1])*/;
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
        return $this->hasOne(User::className(), ['id' => 'create_by'])->alias("createuser")->select(['createuser.id',"createuser.username","createuser.realname","createuser.mobile","createuser.pid","createuser.picture"]);
    }
	
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductApplyLogs()
    {
        return $this->hasMany(ProductApplyLog::className(), ['applyid' => 'applyid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductApplyMemos()
    {
        return $this->hasMany(ProductApplyMemo::className(), ['applyid' => 'applyid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrders()
    {
        return $this->hasMany(ProductOrders::className(), ['applyid' => 'applyid']);
    }
	
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getCertification()
    {
        return $this->hasOne(\common\models\Certification::className(), ['uid' =>'create_by'])->alias("certi")->select(['certi.name','certi.email','certi.cardno','certi.state',"certi.uid"]);
    }
	
	
	/**
	* 接单方申请接单
	*/
	
	public function change($productid){
		$this->setAttributes($productid);
		if($this->isNewRecord){
			$this->productid = $productid;
			$this->status = '10';
			$this->create_at = time();
			$this->create_by = Yii::$app->user->getId();
			if($this->save()){
				$applyLog = new ProductApplyLog;
				$applyLog->create($this->applyid,'申请接单','10',$this->status,$this->create_by);
				return OK;
			}else{
				return MODELDATASAVE;
			}
		}else{
			$status = $this->updateAll(['status'=>'10','modify_at'=>time(),'modify_by'=>Yii::$app->user->getId()],['validflag'=>'1','status'=>'50','create_by'=>Yii::$app->user->getId(),'productid'=>$productid]);
			if($status){
				$applyLog = new ProductApplyLog;
				$applyLog->create($this->applyid,'取消申请后重新申请','10',$this->status,$this->create_by);
				return OK;
			}else{
				return MODELDATASAVE;
			}
		}
	}
	
	/**
	* 接单方取消申请
	*/
	public function applyCancel($applyid){
		$status = $this->updateAll(['status'=>'50','modify_at'=>time(),'modify_by'=>Yii::$app->user->getId()],['validflag'=>'1','status'=>'10','create_by'=>Yii::$app->user->getId(),'applyid'=>$applyid]);
		if($status){
			$applyLog = new ProductApplyLog;
			$applyLog->create($applyid,'取消申请','50',$this->status,Yii::$app->user->getId());
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
	
	/**
	* 发布方选择接单方面谈
	*/
	public function applyChat($applyid){
		$status = $this->updateAll(['status'=>'20','modify_at'=>time(),'modify_by'=>Yii::$app->user->getId()],['validflag'=>'1','status'=>'10','applyid'=>$applyid]);
		if($status){
			$applyLog = new ProductApplyLog;
			$applyLog->create($this->applyid,'同意面谈','20',$this->status,Yii::$app->user->getId());
			return OK;
		}else{
			return CANTSHENQING16;
		}
	}
	
	/**
	* 发布方取消接单方面谈
	*/
	public function applyVeto($applyid){
		$status = $this->updateAll(['status'=>'30','modify_at'=>time(),'modify_by'=>Yii::$app->user->getId()],['validflag'=>'1','status'=>'20','applyid'=>$applyid]);
		if($status){
			$applyLog = new ProductApplyLog;
			$applyLog->create($this->applyid,'取消面谈','30',$this->status,Yii::$app->user->getId());
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
	
	/**
	* 发布方同意接单方处理
	*/
	public function applyAgree($applyid,$productid){
		$status = $this->updateAll(['status'=>'40','modify_at'=>time(),'modify_by'=>Yii::$app->user->getId()],['validflag'=>'1','status'=>'20','applyid'=>$applyid]);
		if($status){
			$applyLog = new ProductApplyLog;
			$applyLog->create($this->applyid,'同意接单','40',$this->status,Yii::$app->user->getId());
			$this->applyFail($productid);
			return OK;			
		}else{
			return MODELDATASAVE;
		}
	}
	
	/**
	*删除接单
	*/
	public function applyDel($applyid){
		$status = $this->updateAll(['validflag'=>'0','modify_at'=>time(),'modify_by'=>Yii::$app->user->getId()],['status'=>['30','50','60'],'applyid'=>$applyid,'create_by'=>Yii::$app->user->getId()]);
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
		$status = $this::updateAll(['status'=>'60','modify_at'=>time(),'modify_by'=>Yii::$app->user->getId()],['validflag'=>'1','status'=>'10','productid'=>$productid]);
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
