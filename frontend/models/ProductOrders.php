<?php

namespace app\models;
use Yii;
use app\models\ProductOrdersLog;

/**
 * This is the model class for table "{{%product_orders}}".
 *
 * @property integer $ordersid
 * @property integer $productid
 * @property integer $applyid
 * @property string $status
 * @property string $pact
 * @property string $validflag
 * @property integer $create_at
 * @property integer $create_by
 * @property integer $modify_at
 * @property integer $modify_by
 *
 * @property ProductApply $apply
 * @property ProductOrdersClosed[] $productOrdersCloseds
 * @property ProductOrdersComment[] $productOrdersComments
 * @property ProductOrdersLog[] $productOrdersLogs
 * @property ProductOrdersOperator[] $productOrdersOperators
 * @property ProductOrdersProcess[] $productOrdersProcesses
 * @property ProductOrdersTermination[] $productOrdersTerminations
 */
class ProductOrders extends \yii\db\ActiveRecord
{
	
	public static $status = [
		'0'=>'待确认',
		'10'=>'协议上传',
		'20'=>'处置中',
		'30'=>'已终止',
		'40'=>'已结案',
	];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_orders}}';
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
            [['applyid'], 'unique', 'message' => '重复' ],
            [['productid', 'applyid', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['status'], 'required'],
            [['status', 'validflag'], 'string'],
			[['pact'], 'string', 'max' => 255, 'tooLong'=>"上传协议太多！" ],
            [['applyid'], 'exist', 'skipOnError' => true, 'targetClass' => ProductApply::className(), 'targetAttribute' => ['applyid' => 'applyid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ordersid' => '接单id',
            'productid' => '产品ID',
            'applyid' => '申请ID',
            'status' => '状态（10调查，20处置，30终止，40结案）',
            'pact' => '协议',
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
    public function getCreateuser()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by'])->alias("createuser")->joinWith(["headimg"])->select(['createuser.id','createuser.picture',"createuser.realname","createuser.username","createuser.mobile"]);
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
    public function getApply()
    {
        return $this->hasOne(ProductApply::className(), ['applyid' => 'applyid'])->alias('apply');
    }

	
	/**
	 * 获取所有处置进度
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrdersProcesses()
    {
        return $this->hasMany(ProductOrdersProcess::className(), ['ordersid' => 'ordersid'])->alias('processes')->onCondition(['processes.validflag'=>1]);
    }
	 /**
	 * 获取所有评价数量
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrdersCommentsNum($where=[])
    {
		return $this->hasMany(ProductOrdersComment::className(), ['ordersid' => 'ordersid'])->alias('comments')
			->onCondition(['comments.validflag'=>1])
			->andWhere($where)
			->count();
	}
	
	 /**
	 * 获取所有评价
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrdersComments($isArray = false,$orderBy='comments.action_at asc')
    {
		if($isArray){
			return $this->hasMany(ProductOrdersComment::className(), ['ordersid' => 'ordersid'])->alias('comments')
			->select(["commentid","ordersid","type","touid","tocommentid","truth_score","assort_score","response_score","files","memo","(case  when comments.type= 2 and comments.tocommentid !=0  then comments.tocommentid else comments.commentid end) as owner"])
			->onCondition(['comments.validflag'=>1])
			->orderBy("(case  when comments.type= 2 and comments.tocommentid !=0  then comments.tocommentid else comments.commentid end) asc ,".$orderBy)->asArray($isArray)->all();
        }else{
			return $this->hasMany(ProductOrdersComment::className(), ['ordersid' => 'ordersid'])->alias('comments')->onCondition(['comments.validflag'=>1])->orderBy($orderBy);
		}	
	}
	
    /**
	 * 获取所有普通评价
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrdersComments1($isArray = false,$orderBy='comments.action_at asc')
    {
		if($isArray){
			return $this->hasMany(ProductOrdersComment::className(), ['ordersid' => 'ordersid'])->alias('comments')
			->onCondition(['comments.validflag'=>1])->andWhere(['comments.type'=>'1'])
			->joinWith(['userinfo'])
			->orderBy($orderBy)
			->asArray($isArray)
			->all();
        }else{
			return $this->hasMany(ProductOrdersComment::className(), ['ordersid' => 'ordersid'])->alias('comments')->onCondition(['comments.validflag'=>1])->andWhere(['comments.type'=>'1'])->orderBy($orderBy);
		}	
	}
	
	 /**
	 * 获取所有追加评价
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrdersComments2($isArray = false,$orderBy='comments.action_at asc')
    {
		if($isArray){
			return $this->hasMany(ProductOrdersComment::className(), ['ordersid' => 'ordersid'])->alias('comments')
			->onCondition(['comments.validflag'=>1])
			->andWhere(['comments.type'=>'2'])
			->joinWith(['userinfo'])
			->orderBy($orderBy)
			->asArray($isArray)
			->all();
        }else{
			return $this->hasMany(ProductOrdersComment::className(), ['ordersid' => 'ordersid'])->alias('comments')->onCondition(['comments.validflag'=>1])->andWhere(['comments.type'=>'2'])->orderBy($orderBy);
		}	
	}
	
    /**
     * 获取所有日志
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrdersLogs($isArray = false,$orderBy='logs.action_at desc,logs.logid desc')
    {
		if($isArray){
			return $this->hasMany(ProductOrdersLog::className(), ['ordersid' => 'ordersid'])->alias('logs')->onCondition(['logs.validflag'=>1])->orderBy($orderBy)->asArray($isArray)->all();
		}else{
			return $this->hasMany(ProductOrdersLog::className(), ['ordersid' => 'ordersid'])->alias('logs')->onCondition(['logs.validflag'=>1])->orderBy($orderBy);
		}
	}

    /**
	 * 获取所有经办人
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrdersOperators($isArray = false,$orderBy='(case operators.level when 1 then operators.operatorid else operators.create_by end) asc ,operators.level asc,operators.create_at asc ')
    {
		if($isArray){
			return $this->hasMany(ProductOrdersOperator::className(), ['ordersid' => 'ordersid'])->alias('operators')
			->onCondition(['operators.validflag'=>1])
			->joinWith([
				"userinfo"=>function($query){
					$query->joinWith([
						'headimg', 
					]);
				}
			])
			->select(["operators.id","operators.ordersid","operators.operatorid","operators.level","operators.validflag","operators.create_by","operators.create_at","(case operators.level when 1 then operators.operatorid else operators.create_by end) as owner"])
			->orderBy($orderBy)
			->asArray($isArray)
			->all();
		}else{
			return $this->hasMany(ProductOrdersOperator::className(), ['ordersid' => 'ordersid'])->alias('operators')
			->onCondition(['operators.validflag'=>1])
			->joinWith(['userinfo'])
			->select(["operators.id","operators.ordersid","operators.operatorid","operators.level","operators.validflag","operators.create_by","operators.create_at","(case operators.level when 1 then operators.operatorid else operators.create_by end) as owner"])
			->orderBy($orderBy);
		}
        
    }
	
	/**
	 * 获取订单相关结案列表
     * @return \yii\db\ActiveQuery  
	 * 
     */
    public function getProductOrdersCloseds()
    {
        return $this->hasMany(ProductOrdersClosed::className(), ['ordersid' => 'ordersid'])->alias('closed')->where(['closed.validflag'=>'1']);
    }
	
    /**
	 * 获取申请中订单结案列表
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrdersClosedsApply()
    {
        return $this->hasOne(ProductOrdersClosed::className(), ['ordersid' => 'ordersid'])->alias('closed')->where(['closed.validflag'=>'1','closed.status'=>'0']);
    }
	
	 /**
	 * 获取申请中订单结案数量
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrdersClosedsApplyCount()
    {
        return $this->hasOne(ProductOrdersClosed::className(), ['ordersid' => 'ordersid'])->alias('closed')->where(['closed.validflag'=>'1','closed.status'=>'0'])->count();
    }
	
	
    /**
	 * 获取订单相关结案列表
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrdersTerminations()
    {
        return $this->hasMany(ProductOrdersTermination::className(), ['ordersid' => 'ordersid'])->alias('terminations')->where(['terminations.validflag'=>'1']);
    }

    /**
	 * 获取申请中订单结案列表
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrdersTerminationsApply()
    {
        return $this->hasOne(ProductOrdersTermination::className(), ['ordersid' => 'ordersid'])->alias('terminations')->where(['terminations.validflag'=>'1','terminations.status'=>'0']);
    }
	
	/**
	 * 获取申请中订单终止数量
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrdersTerminationsApplyCount()
    {
        return $this->hasOne(ProductOrdersTermination::className(), ['ordersid' => 'ordersid'])->alias('terminations')->where(['terminations.validflag'=>'1','terminations.status'=>'0'])->count();
    }
	
	/**
	 * 获取订单结案详情
     * @return \yii\db\ActiveQuery  
	 * 
     */
    public function getProductOrdersClosed()
    {
        return $this->hasOne(ProductOrdersClosed::className(), ['ordersid' => 'ordersid'])->alias('productordersclosed')->onCondition(['productordersclosed.validflag'=>'1','productordersclosed.status'=>'20']);
    }
	
	/**
	 * 获取订单结案详情
     * @return \yii\db\ActiveQuery  
	 * 
     */
    public function getClosed()
    {
        return $this->hasOne(ProductOrdersClosed::className(), ['ordersid' => 'ordersid'])->alias('closed');
    }
	
	/**
	 * 获取订单结案详情
     * @return \yii\db\ActiveQuery  
	 * 
     */
    public function getProductOrdersTermination()
    {
        return $this->hasOne(ProductOrdersTermination::className(), ['ordersid' => 'ordersid'])->alias('productorderstermination')->onCondition(['productorderstermination.validflag'=>'1','productorderstermination.status'=>'20']);
    }
	
	/**
	 * 获取订单结案详情
     * @return \yii\db\ActiveQuery  
	 * 
     */
    public function getTermination()
    {
        return $this->hasOne(ProductOrdersTermination::className(), ['ordersid' => 'ordersid'])->alias('termination');
    }
    /**
     * @inheritdoc
     * @return ProductOrdersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductOrdersQuery(get_called_class());
    }
	
	/**
	*	订单生成
	*	
	*/
	public function create($data=[] , $create_by = ''){
		$this->applyid = $data['applyid'];
		if($data['create_by'])$this->create_by = $data['create_by'];
		if($create_by)$this->create_by = $create_by;
		$this->productid = $data['productid'];
		$this->status ='0';
		$this->validate();
		if(!$this->validate())return MODELDATACHECK;
		if($this->save()){
			$ProductOrdersLog = new ProductOrdersLog;
			$beforeStatus = '0';
			$afterStatus = $this->status;
			$ProductOrdersLog->create($this->ordersid,'0','面谈成功，自动生成订单',$beforeStatus,$afterStatus,10,1);
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
	
	/**
	*	订单居间确认
	*	
	*/
	public function ordersConfirm($modify_by = ''){
		$modify_by = $modify_by?$modify_by:Yii::$app->user->getId();
		$status = $this->updateAll(['modify_by'=>$modify_by,'modify_at'=>time(),'status'=>'10'],["ordersid"=>$this->ordersid,'status'=>'0']);
		if($status){
			$ProductOrdersLog = new ProductOrdersLog;
			$ProductOrdersLog->create($this->ordersid,"0",'居间协议确认',0,10,10,1);
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
	
	/**
	*	订单协议确认
	*	
	*/
	public function pactConfirm($modify_by = ''){
		$modify_by = $modify_by?$modify_by:Yii::$app->user->getId();
		$status = $this->updateAll(['modify_by'=>$modify_by,'modify_at'=>time(),'status'=>'20'],["ordersid"=>$this->ordersid,'status'=>'10']);
		if($status){
			$ProductOrdersLog = new ProductOrdersLog;
			$ProductOrdersLog->create($this->ordersid,"0",'协议上传确认',10,20,10,1);
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
	
	
	/**
	*	订单结案
	*	
	*/
	public function closed($modify_by = ''){
		$modify_by = $modify_by?$modify_by:Yii::$app->user->getId();
		$status = $this->updateAll(['modify_by'=>$modify_by,'modify_at'=>time(),'status'=>'40'],["ordersid"=>$this->ordersid,'status'=>'20']);
		if($status){
			$ProductOrdersLog = new ProductOrdersLog;
			$ProductOrdersLog->create($this->ordersid,"0",'订单结案',20,40,10,1);
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
	
	/**
	*	订单终止
	*	
	*/
	public function termination($modify_by = ''){
		$modify_by = $modify_by?$modify_by:Yii::$app->user->getId();
		$status = $this->updateAll(['modify_by'=>$modify_by,'modify_at'=>time(),'status'=>'30'],["ordersid"=>$this->ordersid,'status'=>'20']);
		if($status){
			$ProductOrdersLog = new ProductOrdersLog;
			$ProductOrdersLog->create($this->ordersid,"0",'订单终止',20,30,10,1);
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
	
	/**
	 * 返回订单所处阶段
	 *
	 */
	public function checkStatus(){
		switch($this->status){
			case 40:
			return 'ORDERSCLOSED';
			break;
			case 30:
			return 'ORDERSTERMINATION';
			break;
			case 10:
			return 'ORDERSPACT';
			break;
			case 0:
			return 'ORDERSCONFIRM';
			break;
		}
		return 'ORDERSPROCESS';
	}
	
	/**
	 *  终止权限判断
	 *	@param type
	 */
	public function accessTermination($type='READ',$uid = '',$exclude = []){
		$uid = $uid?$uid:Yii::$app->user->getId();
		$Access=[];
		switch($type){
			case 'READ':
				$Access[] = $this->create_by;//接单方
				$Access[] = $this->product->create_by;//发布方
				foreach($this->productOrdersOperators as $Operators){
					$Access[] = $Operators->operatorid;
				}
				break;
			case 'APPLY':
				$Access[] = $this->create_by;//接单方
				$Access[] = $this->product->create_by;//发布方
				break;
			case 'AUTH':
				$Access[] = $this->create_by;//接单方
				$Access[] = $this->product->create_by;//发布方
				break;	
		}
		// var_dump($Access);
		// var_dump($exclude);
		// return true;
		if(in_array($uid,$Access)&&!in_array($uid,$exclude))return true;
		return false;
	}
	
	/**
	 *  结案权限判断
	 *
	 */
	public function accessClosed($type='READ',$uid = '',$exclude = []){
		$uid = $uid?$uid:Yii::$app->user->getId();
		$Access=[];
		switch($type){
			case 'READ':
				$Access[] = $this->create_by;//接单方
				$Access[] = $this->product->create_by;//发布方
				foreach($this->productOrdersOperators as $Operators){
					$Access[] = $Operators->operatorid;
				}
				break;
			case 'APPLY':
				$Access[] = $this->create_by;//接单方
				break;
			case 'AUTH':
				$Access[] = $this->product->create_by;//发布方
				break;	
		}
		// var_dump($Access);
		// var_dump($exclude);
		// return true;
		if(in_array($uid,$Access)&&!in_array($uid,$exclude))return true;
		return false;
	}
	
	/**
	 *  接单权限判断
	 *
	 */
	public function accessOrders($type='READ',$uid = '',$exclude = []){
		
		$uid = $uid?$uid:Yii::$app->user->getId();
		$Access=[];
		switch($type){
			case 'READ'://接单查看
				$Access[] = $this->create_by;//接单方
				$Access[] = $this->product->create_by;//发布方
				foreach($this->productOrdersOperators as $Operators){
					$Access[] = $Operators->operatorid;
				}
				break;
			case 'ADDPROCESS'://进度添加
				$Access[] = $this->create_by;//接单方
				$Access[] = $this->product->create_by;//发布方
				foreach($this->productOrdersOperators as $Operators){
					$Access[] = $Operators->operatorid;
				}
				break;
			case 'ADDOPERATOR': //经办人设置
				$Access[] = $this->create_by;//接单方
				foreach($this->productOrdersOperators as $Operators){
					if($Operators->level ==1){
						$Access[] = $Operators->operatorid;
					}
					
				}
				break;
			case 'ADDCOMMENT'://评论添加
				$Access[] = $this->create_by;//接单方
				$Access[] = $this->product->create_by;//发布方
				break;
			case 'ORDERCOMFIRM'://接单确认|协议上传
				$Access[] = $this->create_by;//接单方
				break;
		}
		// var_dump($Access);
		// var_dump($exclude);
		// var_dump($uid);
		// return true;
		if(in_array($uid,$Access)&&!in_array($uid,$exclude))return true;
		return false;
	}
	
	/**
	 * 获取接单上传协议
	 * 
     */
	public function getPacts()
    {
		if($this->pact){
			return \app\models\Files::getFiles(explode(",",$this->pact),['id','name','file']);
		}else{
			return [];
		}
    }
	
	
}
