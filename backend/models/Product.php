<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zcb_product".
 *
 * @property integer $productid
 * @property string $number
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
 * @property integer $browsenumber
 * @property string $validflag
 * @property integer $create_at
 * @property integer $create_by
 * @property integer $modify_at
 * @property integer $modify_by
 *
 * @property ProductApply[] $productApplies
 * @property ProductCollect[] $productCollects
 * @property ProductLog[] $productLogs
 * @property ProductMortgage[] $productMortgages
 */
class Product extends \yii\db\ActiveRecord
{
	//状态
	public static $status = [
		// ''=>'全部',
		'1'=>'草稿',
		'2'=>'发布中',
		'3'=>'面谈中',
		'4'=>'处理中',
		'5'=>'已终止',
		'6'=>'已结案',
	];
	
	//状态
	public static $validflag = [
		// ''=>'全部',
		'1'=>'已回收',
		'2'=>'未回收',
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
        return 'zcb_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'entrust', 'type', 'status', 'validflag'], 'string'],
            [['account', 'typenum'], 'number'],
            [['type'], 'required'],
            [['overdue', 'province_id', 'city_id', 'district_id', 'browsenumber', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['number', 'category_other', 'entrust_other'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'productid' => 'ID',
            'number' => '编号',
            'category' => '债权类型（1房产抵押，2机动车抵押，3合同纠纷，4其他）',
            'category_other' => '其他债券类型',
            'entrust' => '委托权限（1诉讼,2清收3,债权转让，4其他）',
            'entrust_other' => '其他委托权限',
            'account' => '委托金额',
            'type' => '佣金类型',
            'typenum' => '类型值',
            'overdue' => '违约期限',
            'province_id' => '省份ID',
            'city_id' => '城市ID',
            'district_id' => '区域ID',
            'status' => '状态（0草稿，10发布，20处理，30终止，40结案）',
            'browsenumber' => '浏览次数',
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
    public function getProductApply()
    {
        return $this->hasMany(ProductApply::className(), ['productid' => 'productid'])->alias('productApply')
		->joinWith(['user','certification']);
    }
	
	public function getApplyOne(){
				return $this->hasOne(ProductApply::className(), ['productid'=>'productid'])->alias('applyOne')->orderBy(['applyOne.create_at'=>SORT_DESC]);
	}
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getProductApplies()
    {
        return $this->hasMany(ProductApply::className(), ['productid' => 'productid'])->alias('productApplies');
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCollects()
    {
        return $this->hasMany(ProductCollect::className(), ['productid' => 'productid']);
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
    public function getProductOrders()
    {
        return $this->hasOne(ProductOrders::className(), ['productid' => 'productid'])
		->alias('orders')
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
     * @return \yii\db\ActiveQuery
     */
    public function getProductMortgages()
    {
        return $this->hasMany(ProductMortgage::className(), ['productid' => 'productid']);
    }

	/**
     * @return \yii\db\ActiveQuery
     */
    public function getUserMobile()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'create_by'])->alias('userMobile')->select(['id','mobile','username','realname']);
    }
	
	 public function getUserName()
    {
        return $this->hasOne(\common\models\Certification::className(),['uid' =>'create_by'])->alias("certification");
    }
	
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
    public function getAgreementApply()
    {
			 return $this->hasOne(ProductApply::className(),['productid' => 'productid'])->alias('apply')
			  ->onCondition(['apply.validflag'=>'1','apply.status'=>['20','40']])
			  ->joinWith(['certification','createuser','orders'])
			  ->select(['apply.productid','apply.status','apply.applyid','apply.create_by','apply.create_at','apply.modify_at']);
    }
	
	public function getFabuuser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'create_by'])
		->alias("fabuuser")
		->joinWith(["headimg"=>function($query){
			$query->alias("fabuuserheadimg")->select(["fabuuserheadimg.file","fabuuserheadimg.id"]);
		}])
		->select(['fabuuser.id',"fabuuser.username","fabuuser.realname","fabuuser.mobile","fabuuser.pid","fabuuser.picture"]);
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
     * @inheritdoc
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }
	
	/**
	 * 返回数据处理
	 *
	 *
	 */
	 public static function category($data){
		 // 债权类型
			if(isset($data['category'])){
				$categoryLabel="";
				$category_other=$data['category_other'];
				$ProductCategory = Product::$category;
				$categoryList = explode(",",$data['category']);
				foreach($categoryList as $k){
					if(in_array($k,[1,2,3])){
						$categoryLabel.=$categoryLabel?(",".$ProductCategory[$k]):$ProductCategory[$k];
					}else if($k == 4){
						$categoryLabel.=$category_other?(",".$category_other):'';
					}
			    }
				return $categoryLabel;
			}
	 }
	 
	 public static function entrust($data){
		 // 委托权限
			if(isset($data['entrust'])){
				$entrustLabel="";
				$entrust_other=$data['entrust_other'];
				$ProductEntrust = Product::$entrust;
				$entrustList = explode(",",$data['entrust']);
				foreach($entrustList as $k){
					if(in_array($k,[1,2,3])){
						$entrustLabel.=$entrustLabel?(",".$ProductEntrust[$k]):$ProductEntrust[$k];
					}else if($k == 4){
						$entrustLabel.=$entrust_other?(",".$entrust_other):'';
					}
				}
				return $entrustLabel;
				
			}
	 }
	 
	 public static function typeLabel($data){
		 if(isset($data['type'])&&$data['type'] == '1'){
				return round($data['typenum']/10000,1).'万';
			}else{
				return round($data['typenum']).'%' ;
			}
	 }
	 
	 /**
	* 删除产品数据
	* 
	*/
	public function singleDelete($productid){
		$status = $this->updateAll(['validflag'=>'0'],["productid"=>$productid,"status"=>["0","10"],"validflag"=>'1']);
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
			return ParamsCheck;
		}
	}
}
