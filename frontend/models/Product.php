<?php

namespace app\models;

use Yii;
use app\models\ProductLog;
/**
 * This is the model class for table "{{%product}}".
 *
 * @property integer $productid
 * @property string $category
 * @property string $category_other
 * @property string $entrust
 * @property string $entrust_other
 * @property string $account
 * @property string $type
 * @property string $typenum
 * @property integer $overdue 
 * @property integer $province_id 
 * @property integer $city_id 
 * @property integer $district_id 
 * @property string $status
 * @property string $validflag
 * @property integer $create_at
 * @property integer $create_by
 * @property integer $modify_at
 * @property integer $modify_by
 *
 * @property ProductApply[] $productApplies
 * @property ProductAttribute[] $productAttributes
 * @property ProductCollect[] $productCollects
 * @property ProductLog[] $productLogs
 * @property ProductMortgage[] $productMortgages
 */
class Product extends \yii\db\ActiveRecord
{
	//状态
	public static $status = [
		'0'=>'草稿',
		'10'=>'发布中',
		'20'=>'处理中',
		'30'=>'已终止',
		'40'=>'已结案',
	];
	//债权类型
	public static $category = [
		'1'=>'房产抵押',
		'2'=>'机动车抵押',
		'3'=>'合同纠纷',
		'4'=>'其他',
	];
	//委托权限
	public static $entrust = [
		'1'=>'诉讼',
		'2'=>'清收',
		'3'=>'债权转让',
		'4'=>'其他',
	];
	//佣金类型
	public static $type = [
		'1'=>'万',
		'2'=>'%',
	];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['create_at'], 'default', 'value' => time()],
            [['create_by'], 'default', 'value' => Yii::$app->user->getId()],
            [['validflag'], 'default', 'value' => '1' ],
			[['account','typenum','overdue','province_id','city_id','type','district_id'],'required'],
            [['category', 'entrust', 'type', 'status', 'validflag'], 'string'],
            [['account', 'typenum'],'number'],
            [['overdue'],'integer'],
			[['category'], 'required',"message"=>"债权类型不能为空！"],
			['category_other','required', 'when' => function ($model) {
				return in_array('4',explode(',',$model->category));
			},"message"=>"债权类型其他不能为空!"],
			[['entrust'], 'required',"message"=>"委托权限不能为空！"],
			['entrust_other','required', 'when' => function ($model) {
				return in_array('4',explode(',',$model->entrust));
			},"message"=>"委托类型其他不能为空!"],
			[['category'], 'in', 'range'=>\frontend\services\Func::permutations(['1','2','3','4']),"message"=>"请选择正确的债权类型"],
			[['entrust'], 'in', 'range'=>\frontend\services\Func::permutations(['1','2','3','4']),"message"=>"请选择正确的委托事项"],
			
			['type','in','range'=>['1','2'],'message'=>'请选择费用类型'],
			['typenum','HandleType', 'when' => function ($model) {
				return $model->type == '1'; 
			}],
			['typenum','compare', 'when' => function ($model) {
				return $model->type == '2'; 
			},'compareValue' => 100, 'operator' => '<','message'=>'风险代理不能大于100%'],
			
            
			['account','Handle'],
			// ['typenum','HandleType'],
			['overdue','HandleOverdue'],
            [['overdue', 'province_id', 'city_id', 'district_id', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['category_other', 'entrust_other'], 'string', 'max' => 20],
        ];
    }
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'productid' => 'ID',
            'category' => '债权类型（1房产抵押，2机动车抵押，3合同纠纷，4其他）',
            'category_other' => '其他债券类型',
            'entrust' => '委托权限（1诉讼,2清收3,债权转让，4其他）',
            'entrust_other' => '其他委托权限',
            'account' => '委托金额',
            'type' => '佣金类型',
            'typenum' => '费用',
            'overdue' => '违约期限', 
            'province_id' => '省份ID', 
            'city_id' => '城市ID', 
            'district_id' => '区域ID', 
            'status' => '状态（0草稿，10发布，20处理，30终止，40结案）',
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
        return $this->hasOne(ProductOrders::className(), ['productid' => 'productid'])->alias('orders');
    }
	
	public function getApplyOne(){
				return $this->hasOne(ProductApply::className(), ['productid'=>'productid'])->alias('applyOne')->orderBy(['applyOne.create_at'=>SORT_DESC]);
	}
	
	public function getApplySelf(){
		$userid=Yii::$app->user->getId();
		return $this->hasOne(ProductApply::className(), ['productid'=>'productid'])->alias('applySelf')->onCondition(['applySelf.create_by'=>$userid,'applySelf.validflag'=>'1','applySelf.status'=>['10','20','40']]);
	}
	// public function getApplySelfOne(){
		// $userid=Yii::$app->user->getId();
		// return $this->hasOne(ProductApply::className(), ['productid'=>'productid'])->alias('applySelfOne')->onCondition(['applySelfOne.create_by'=>$userid,'applySelfOne.validflag'=>'1','applySelfOne.status'=>'10']);
	// }
	public function getCollectSelf(){
		$userid=Yii::$app->user->getId();
		return $this->hasOne(ProductCollect::className(), ['productid'=>'productid'])->alias('collectSelf')->onCondition(['collectSelf.create_by'=>$userid,'collectSelf.validflag'=>'1']);
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
	public function getCurapply(){
		return $this->hasOne(ProductApply::className(), ['productid'=>'productid'])->alias("curapply")->onCondition(["curapply.validflag"=>"1","curapply.status"=>"20"]);
	}
	 public function getFabuuser()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by'])
		->alias("fabuuser")
		->joinWith(["headimg"=>function($query){
			$query->alias("fabuuserheadimg")->select(["fabuuserheadimg.file","fabuuserheadimg.id"]);
		}])
		->select(['fabuuser.id',"fabuuser.username","fabuuser.realname","fabuuser.mobile","fabuuser.pid","fabuuser.picture"]);
    }
	
	 public function getHomeUsesrHead()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by'])
		->alias("head")
		->joinWith(["headimg"])
		->select(['head.id',"head.username","head.realname","head.mobile","head.picture"]);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getReleaseUser()
    {
        return $this->hasOne(\common\models\Certification::className(),['uid' =>'create_by'])->alias("certification")->select(['certification.name','certification.email','certification.cardno','certification.state']);
    }
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getCerti()
    {
        return $this->hasOne(\common\models\Certification::className(), ['uid' =>'create_by'])->alias("certi")->select(["certi.state"]);
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductApplies($isArray=false,$joinWith=['createuser'])
    {
		if($isArray){
			return $this->hasMany(ProductApply::className(),['productid' => 'productid'])->alias('apply')
			 ->onCondition(['apply.validflag'=>'1'])
			 ->joinWith([
				'createuser'=>function($query){
					$query->joinWith(["headimg"]);
				}])
			 ->asArray($isArray)
			 ->orderBy('apply.create_at desc')
			 ->select(['apply.productid','apply.status','apply.applyid','apply.create_by','apply.create_at'])
			 ->all();
		}else{
			  return $this->hasMany(ProductApply::className(),['productid' => 'productid'])->alias('apply')
			  ->onCondition(['apply.validflag'=>'1'])
			  ->joinWith($joinWith)
			  ->orderBy('apply.create_at desc')
			  ->select(['apply.productid','apply.status','apply.applyid','apply.create_by']);
		}
       
    }
	
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductApply()
    {
			 return $this->hasOne(ProductApply::className(),['productid' => 'productid'])->alias('apply')
			  ->onCondition(['apply.validflag'=>'1','apply.status'=>['20','40']])
			  ->joinWith([
			  'createuser'=>function($query){
				  $query->joinWith(["headimg"]);
			  },
			  'orders'=>function($query){
				  $query->joinWith([
					'productOrdersLogs'=>function($query){
						$query->joinWith("actionUser");
					}
				]);
			  }
			  ])
			  ->select(['apply.productid','apply.status','apply.applyid','apply.create_by','apply.create_at']);
    }
	
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getAgreementApply()
    {
			 return $this->hasOne(ProductApply::className(),['productid' => 'productid'])->alias('apply')
			  ->onCondition(['apply.validflag'=>'1','apply.status'=>['20','40']])
			  ->joinWith(['certification','createuser','orders'])
			  ->select(['apply.productid','apply.status','apply.applyid','apply.create_by','apply.create_at','apply.modify_at']);
    }
	

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributes()
    {
        return $this->hasMany(ProductAttribute::className(), ['productid' => 'productid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCollects()
    {
        return $this->hasMany(ProductCollect::className(), ['productid' => 'productid'])->alias("collect")->onCondition(['collect.validflag'=>'1'])->select(["productid","count(collectid) as collectid","create_by"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductLogs()
    {
        return $this->hasMany(ProductLog::className(), ['productid' => 'productid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductMortgages()
    {
        return $this->hasMany(ProductMortgage::className(), ['productid' => 'productid'])->alias('mortgage')->onCondition(['mortgage.validflag'=>'1']);
    }
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductMortgages1()
    {
        return $this->hasMany(ProductMortgage::className(),['productid' => 'productid'])->alias('mortgage')
		->onCondition(['mortgage.validflag'=>'1'])
		->joinWith(['provincename','cityname','areaname'])
		->andWhere(["type"=>"1"])
		->asArray()
		->all();
    }
	
	
	
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductComment()
    {
        return $this->hasOne(ProductOrdersComment::className(),['productid' =>'productid'])
		->alias('Comment')
		->onCondition(['Comment.validflag'=>'1','Comment.action_by'=>Yii::$app->user->getId(),'Comment.type'=>'1'])
		->select(['productid','type']);
    }
	
	
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductMortgages2()
    {
        return $this->hasMany(ProductMortgage::className(), ['productid' => 'productid'])->alias('mortgage')
		->onCondition(['mortgage.validflag'=>'1'])
		->joinWith(['brand','audi'])
		->asArray()
		->andWhere(["type"=>"2"])
		->all();
	}
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductMortgages3()
    {
		return $this->hasMany(ProductMortgage::className(), ['productid' => 'productid'])->alias('mortgage')
		->onCondition(['mortgage.validflag'=>'1'])
		->andWhere(["type"=>"3"])
		->asArray()
		->all();
	}
	
	/**
	* 认证信息
	*/
	public function getCertification(){
		$userid  = $this->create_by;
        $productid  = $this->productid;
		$User = \app\models\User::getCertifications($userid);
		if(!is_array($User)){
			return NULL;
		}
		$User['mobile']=\frontend\services\Func::HideStrRepalceByChar($User['mobile'],'*',3,4);
		return $User;
	}
	
	/**
	* 统计申请和收藏数据
	*/
	public function getStatistics($productid,$total=true){
		   if($total){
			 $number = $this->updateAllCounters(['browsenumber'=>1],['productid'=>$productid]);	//浏览次数  
		   }
		   $applyTotal = ProductApply::find()->where(['productid'=>$productid,'validflag'=>'1'])->count('productid');
		   $applyCount = ProductApply::find()->where(['productid'=>$productid,'validflag'=>'1','status'=>['10','40']])->count('productid');
		   $collectionTotal = ProductCollect::find()->where(['productid'=>$productid,'validflag'=>'1'])->count('productid');
		   $data = ['applyTotal'=>$applyTotal,'applyCount'=>$applyCount,'collectionTotal'=>$collectionTotal];
		   return $data;
	}
	
	/**
	* 判断用户是否收藏或申请
	*/
	public function getJudge($productid){
		// var_dump(Yii::$app->user->getId());die;
		$apply = ProductApply::find()->where(['validflag'=>'1','status'=>['10',"20","40"],'productid'=>$productid,'create_by'=>Yii::$app->user->getId()])->one();
		$collection = ProductCollect::find()->where(['productid'=>$productid,'validflag'=>'1','create_by'=>Yii::$app->user->getId()])->one();
		//var_dump($collection);die;
		$data = [];
		if($apply){
			$data['applyPeople'] = true;
		}else{
			$data['applyPeople'] = false;//$this->accessProduct('MULTIPLE','',['10']);
		}
		if($collection){
			$data['collectionPeople'] = true;
		}else{
			$data['collectionPeople'] = false;
		}
		return $data;
	}
	
	
	
	/**
     * @inheritdoc
     * @return ProductOrdersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }
	
	public function formatErrors($isAll=false)
    {
        $result = '';
        foreach($this->getErrors() as $attribute => $errors) {
            $result .= implode(" ", $errors)." ";
			if(!$isAll)break;
        }
        return $result;
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
	*	产品结案
	*	
	*/
	public function closed($modify_by = ''){
		$modify_by = $modify_by?$modify_by:Yii::$app->user->getId();
		$status = $this->updateAll(['modify_by'=>$modify_by,'modify_at'=>time(),'status'=>'40'],["productid"=>$this->productid,'status'=>'20']);
		if($status){
			// $ProductOrdersLog = new ProductLog;
			// $ProductOrdersLog->create($this->productid,'订单结案',20,40);
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
	/**
	*	产品结案
	*	
	*/
	public function termination($modify_by = ''){
		$modify_by = $modify_by?$modify_by:Yii::$app->user->getId();
		$status = $this->updateAll(['modify_by'=>$modify_by,'modify_at'=>time(),'status'=>'30'],["productid"=>$this->productid,'status'=>'20']);
		if($status){
			// $ProductOrdersLog = new ProductLog;
			// $ProductOrdersLog->create($this->productid,'订单结案',20,40);
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
	
	public function Handle($attribute){
		$reg = '/^(?!(0{1,4}(((\\.0{0,2})?))$))([1-9]{1}[0-9]{0,3}|0)(\\.[0-9]{1,2})?$/';
		if($this->account>99990000){
			$this->addError($attribute, '输入的金额不能大于亿元');
		}
		if($this->account < 10000){
			$this->addError($attribute, '输入的金额不能小于万元');
		}
	}
	
	public function HandleType($attribute){
		if($this->type == '1'){
			if(isset($this->typenum)){
				if($this->typenum>=$this->account){
					$this->addError($attribute, '固定费用必须小于委托金额');
				}
			}
		}
	}
	public function HandleOverdue($attribute){
		if($this->overdue){
			if($this->overdue > 36){
				$this->addError($attribute, '请不要大于36个月');
			}
		}
	}
	 
	 /**
     * 保存产品数据
     * @param data
	 * @param create_by  用户id;
	 * @param draft 判断用户是保存还是发布，1为保存,2为发布；
     */
	 public function change($data=[],$create_by='',$draft){
		$create_by = $create_by?$create_by:Yii::$app->user->getId();
		if(isset($data['account'])&&$data['account']!=='') $data['account'] =is_numeric($data['account'])?$data['account']*10000:$data['account'];
		if(isset($data['type'])&&$data['type']=='1'&&isset($data['typenum'])&&$data['typenum']!=='') $data['typenum'] = is_numeric($data['typenum'])?$data['typenum']*10000:$data['typenum'];
		$this->setAttributes($data);
		if($this->isNewRecord){
			if($draft == '2'){
				if(!$this->validate())return MODELDATACHECK;		
			}else{
				$this->number = \frontend\services\Func::createCatCode(6);
				$this->account = $this->account?:0;
				$this->overdue = $this->overdue?:0;
				$this->province_id = $this->province_id?:0;
				$this->city_id = $this->city_id?:0;
				$this->district_id = $this->district_id?:0;
				$this->typenum = $this->typenum?:0;	
			}
			$this->category_other = trim($this->category_other);
			$this->entrust_other = trim($this->entrust_other);
			$this->create_by = $create_by;
			$this->create_at = time();
			$this->status = '0';
            if($this->save(false)){
				return OK;
		    }else{
				return MODELDATASAVE;
		    };			 
		 }else{		
			$this->modify_by = $create_by;
			$this->modify_at = time();
			if($this->save()){
			  return OK;
		     }else{
			  return MODELDATASAVE;
		     };	
		 }
	}
	
	/**
	* 发布数据
	* @param data
	* @param create_by  用户id;
	*/
	public function release(){
		$number = \frontend\services\Func::createCatCode(6);
		$status = $this->updateAll(['status'=>"10",'number'=>$number],["productid"=>$this->productid,"validflag"=>'1',"status"=>"0"]);
		if($status){
			$this->number =$number;
			$this->status =$status;
			$ProductLog= new \app\models\ProductLog;
			$afterstatus = '10';
			$beforestatus = $this->status?$this->status:0;
            $ProductLog->create($this->productid,'1','发布成功',$afterstatus,$beforestatus,$this->create_by);
			return $status;
		}
	}
	
	/**
	* 编辑数据
	* 
	*/
	public function edit($data,$productid,$draft='1'){
		if(is_numeric($productid)){
		   if(isset($data['account'])&&$data['account']!=='') $data['account'] = $data['account']*10000;
		   if(isset($data['type'])&&$data['type']=='1'&&isset($data['typenum'])&&$data['typenum']!=='') $data['typenum'] = $data['typenum']*10000;
		   if(!$this) return PARAMSCHECK;
		   $this->setAttributes($data);MODELDATACHECK;
		   $this->modify_at = time();	
		   if($draft == '1'){
			    //$this->number = \frontend\services\Func::createCatCode(6);
				$this->account = $this->account?:0;
				$this->overdue = $this->overdue?:0;
				$this->city_id = $this->city_id?:0;
				$this->district_id = $this->district_id?:0;
				$this->typenum = $this->typenum?:0;	
                if($this->update(false)){
			    return OK;
		        }else{
			    return MODELDATASAVE;
		        };	 
			 }else if($draft == '2'){
				if(!$this->validate()) return MODELDATACHECK ;
                    $this->status = '10';
					$this->modify_at=time();
					$this->modify_by=Yii::$app->user->getId();
			    if($this->update()){
					$ProductLog= new \app\models\ProductLog;
					$afterstatus = $this->status;
					$beforestatus = $this->status?$this->status:0;
					$ProductLog->create($productid,'1','编辑发布产品',$afterstatus,$beforestatus,$this->create_by);
					return OK;
		        }else{
					return MODELDATASAVE;
		        }; 
			 }		
		}else{
			return PARAMSCHECK;
		}
	}
	
	/**
	* 删除产品数据
	* 
	*/
	public function singleDelete($productid){
		$status = $this->updateAll(['validflag'=>'0'],["productid"=>$productid,"status"=>["0","10"],"validflag"=>'1','create_by'=>Yii::$app->user->getId()]);
		if($status){
			$apply = ProductApply::findOne(['productid'=>$productid]);
			if($apply){
				$this->applyOne->applyFail($productid,$this->number,$this->create_by);
			}
			$ProductLog= new \app\models\ProductLog;
			$afterstatus = $this->status?$this->status:0;
			$beforestatus = $this->status?$this->status:0;
            $ProductLog->create($this->productid,'1','删除产品数据成功',$afterstatus,$beforestatus,$this->create_by);
			return OK;
		}else{
			return PARAMSCHECK;
		}
	}
	
	/**
	 *  联系权限判断
	 *  必须满足当前用户和提供用户都存在于某个产品
	 */
	public function accessContacts($type='APPLY',$uid = ''){
		
		$uid = $uid;
		$Access=[];
		switch($type){
			case 'APPLY'://申请人
				$Access[] = $this->create_by;//发布方
				foreach($this->productApplies as $Applies){
					if(in_array($Applies->status,[20,30,40]))$Access[] = $Applies->create_by;
				}
				break;
		}
		$userid = Yii::$app->user->getId();
		if(in_array($userid,$Access)&&in_array($uid,$Access))return true;
		return false;
	}
	
	/**
	* 发布方权限判断
	*/
	public function accessProduct($type='',$uid='',$status=['0','10','20','30','40']){
		$uid = $uid;
		$Access=[];
		switch($type){
			case 'MULTIPLE'://编辑方
				$Access[] = $this->create_by;//发布方
				break;
            case 'COLLECTION':
				$Access[] = $uid;//接单方
                break;				
		}
		$userid = Yii::$app->user->getId();
       if(in_array($userid,$Access)&&in_array($this->status,$status))return true;
       return false;	   
	}
		
	/**
	* 发布方同意接单方处理
	*/
	public function productAgree($productid){
		$status = $this->updateAll(['status'=>"20",'modify_at'=>time(),'modify_by'=>Yii::$app->user->getId()],["productid"=>$productid,"validflag"=>'1',"status"=>"10"]);
		if($status){
			$ProductLog= new \app\models\ProductLog;
			$afterstatus = '20';
			$beforestatus = $this->status?$this->status:0;
            $ProductLog->create($this->productid,'1','同意接单',$afterstatus,$beforestatus,$this->create_by);
			return $status;
		}
	}
	
	/**
	* 判断接单方是否认证
	*/
	public function CertificationUser($uid=''){
		$uid = $uid?$uid:Yii::$app->user->getId();
		$user = User::findOne(['id'=>$uid]);
		if($user){
			if($user->pid){
				$data = \common\models\Certification::findOne(['uid'=>$user->pid]);
			}else{
				$data = \common\models\Certification::findOne(['uid'=>$uid]);
			}
			if($data&&$data->state==1){
				return true;
			}else{
				return false;
			}
		}
		
	}

}
