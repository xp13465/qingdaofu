<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%solutionseal}}".
 *
 * @property integer $solutionsealid
 * @property integer $productid
 * @property integer $personnel_id
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
 * @property string $modify_by
 */
class Solutionseal extends \yii\db\ActiveRecord
{
	public static $status = [
		'0'=>'草稿',
		'11'=>'面谈中',
		'12'=>'面谈失败',
		'20'=>'合同签订中',
		'30'=>'订金支付中',
		'40'=>'处置中',
		'50'=>'已中止',
		'60'=>'已结案',
		'70'=>'已归档'
	];

	// public static function getDb()  
    // {  
        // return \Yii::$app->qdfdb;  
    // }  
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '`manage`.{{%solutionseal}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['validflag'], 'default', 'value' => '1' ],
			[['create_by'], 'default', 'value' => Yii::$app->user->getId() ],
			[['create_at'], 'default', 'value' => time()],
			[['type','entrust_other','account','overdue','custname','custmobile','city_id', 'district_id'], 'required',"message"=>""],
			['category','required',"message"=>"请选择债权类型!"],
			['personnel_name','required',"message"=>"请输入销售姓名!"],
			['category_other','required', 'when' => function ($model) {
				$category = $model->category?explode(',',$model->category):[];
				if(in_array('4',$category)){
					return true;
				}else{
					$model->category_other = '';
					return false;
				}
			}, 'whenClient' => "function (attribute, value) {
				if($('input[name=\"Solutionseal[category][]\"][value=\"4\"]').prop('checked')){
					return true;
				}else{
					$('input[name=\"Solutionseal[category_other]\"]').val('')
					return false;
				}
			}","message"=>"其他不能为空!"],
			[['custmobile'],'match','pattern'=>'/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/','message'=>'请输入正确的手机号'],
            [['productid', 'personnel_id', 'overdue', 'province_id', 'city_id', 'district_id', 'browsenumber', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['category', 'entrust', 'type', 'status', 'validflag','memo'], 'string'],
            [['account', 'typenum'], 'number'],
           
            [['number', 'category_other', 'entrust_other'], 'string', 'max' => 20],
        ];
    }
	

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'solutionsealid' => 'ID',
            'productid' => '订单',
			'custname'=>'客户姓名',
			'custmobile'=>'客户联系方式',
            'personnel_name' => '销售',
            'personnel_id' => '员工ID',
            'number' => '编号',
            'category' => '债权类型',//（1房产抵押,2机动车抵押,3合同纠纷,4其他）
            'category_other' => '其他债券类型',
            'entrust' => '委托权限',//（1诉讼,2清收3,债权转让,4其他）
            'entrust_other' => '委托事项',
            'account' => '委托金额',
            'type' => '佣金类型',
            'typenum' => '类型值',
            'overdue' => '违约期限',
            'province_id' => '省份ID',
            'city_id' => '城市ID',
            'district_id' => '区域ID',
			'memo'=>'备注',
			'windcontrolId'=>'风控ID',
            'status' => '状态',//（0草稿,10提交,11面谈,12面谈失败,20面谈通过,30合同签订,40订金支付处置中,50中止,60结案,70归档）
            'browsenumber' => '浏览次数',
            'validflag' => '回收状态',
            'create_at' => '创建时间',
            'create_by' => '创建人',
            'modify_at' => '修改时间',
            'modify_by' => '修改人',
            'earnestmoney' => '定金',
            'borrowmoney' => '放款金额',
            'backmoney' => '回款金额',
            'actualamount' => '实收佣金',
            'payinterest' => '资方利息',
            'closeddate' => '结案时间',
            'pact' => '服务合同',
        ];
    }

    /**
     * @inheritdoc
     * @return SolutionsealQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SolutionsealQuery(get_called_class());
    }
	
	/**
	 * 返回订单所处阶段
	 *
	 */
	public function checkStatus(){
		switch($this->status){
			case 30:
			return 'PAYMENT';
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
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['productid' => 'productid'])->alias('product');
    }
	
	 public function getProductOrders()
    {
        return $this->hasOne(ProductOrders::className(), ['productid' =>'productid'])->alias('productOrders');
    }
	
	public function getProductOrdersClosed()
    {
        return $this->hasOne(ProductOrdersClosed::className(), ['productid' =>'productid'])->alias('productOrdersClosed');
    }
	
	public function getProductOrdersOperator()
    {
        return $this->hasOne(ProductOrdersOperator::className(), ['productid' =>'productid'])->alias('productOrdersOperator');
    }
	
	public function getOperatorid(){
		return $this->productOrdersOperator;
	}
	
	public function getClosedid(){
		return $this->productOrdersClosed;
	}
	
	public function getOrderData(){
		return $this->productOrders;
	}
	
	public function getProvincename(){
		return $this->hasOne(Province::className(), ['provinceID'=>'province_id'])->select('province,provinceID');
	}
	public function getCityname(){
		return $this->hasOne(City::className(), ['cityID'=>'city_id'])->select('city,cityID');
	}
	public function getAreaname(){
		return $this->hasOne(Area::className(), ['areaID'=>'district_id'])->select('area,areaID');
	}

	
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function auditBtns($uid)
    {
		$btnHtml="";
		switch($this->status){
			case '0':
				$btnHtml.= $this->auditBtn('申请面谈','11');
				break;
			case '11':
				$btnHtml.= $this->auditBtn('面谈成功','20');
				$btnHtml.= $this->auditBtn('面谈失败','12');
				break;
			case '20':
				$btnHtml.= $this->auditBtn('合同确认','30');
				break;
			case '30':
				$btnHtml.= $this->auditBtn('订金确定','40');
				break;
			case '40':
			    if($this->borrowmoney > 0){
					$btnHtml.= $this->auditBtn('放款确认','40');
					$btnHtml.= $this->auditBtn('回款确认','60');
				}else{
					$btnHtml.= $this->auditBtn('放款确认','40');
				}
				break;
		}
		
		return $btnHtml;
    }
	
	
	public function getPersonnelModel(){
		  return $this->hasOne(Personnel::className(),['id'=>'windcontrolId'])->alias('personnel')->select('personnel.mobile,personnel.id,personnel.name')->joinWith(['userid']);
	}
	public function getPersonnelModelName(){
		  return $this->personnelModel['name'];
	}
	public function getPersonnelModelMobile(){
		  return $this->personnelModel['mobile'];
	}
	
	public function getPersonnelUserid(){
		  return $this->personnelModel['userid']['id'];
	}
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function auditBtn($title,$afterStatus)
    {
		return \yii\helpers\Html::a($title,'javascript:void(0);', ['title' =>'','class'=>'delete yangshi btn btn-primary btn-xs','data-afterstatus'=>$afterStatus,'data-beforestatus'=>$this->status,'data-id'=>$this->solutionsealid,'data-account'=>$this->account,'data-type'=>$this->type,'data-typenum'=>$this->typenum,'data-earnestmoney'=>$this->earnestmoney,'data-borrowmoney'=>$this->borrowmoney,'data-actualamount'=>$this->actualamount,'data-payinterest'=>$this->payinterest,'data-kefuID'=>$this->create_by,'data-custname'=>$this->custname,'data-custmobile'=>$this->custmobile,'data-windcontrolId'=>$this->windcontrolId,'data-pact'=>$this->getPact($type=true)]);
    }
	
	public function getFuwufei(){
		if($this->type=='1'){
			 return $this->typenum/10000 .'万'.' 固定费用';
		}else if($this->type=='2'){
			 return round($this->typenum) .'%'.' 代理费率';
		}
	}
	
	public function getPact($type=false){
		$pact = explode(',',$this->pact);
		if($type){
			$data = \app\models\Files::getFiles($pact,'*');
		}else{
			$data = \app\models\Files::getFiles($pact,'file,addr',true);
		}
		return $data;
	}
	
	public function getUser($mobile=""){
		$data = User::findByUsername($mobile);
		return $data ; 
	}
	
	public function getPersonnel()
    {
        return $this->hasOne(\app\models\Personnel::className(), ['id' => 'personnel_id']);
    }
	
	public function getPersonnelName()
    {
        return $this->personnel['name'];
    }
	public function getAudit(){
		return $this->hasOne(AuditLog::className(),['relaid'=>'solutionsealid'])->alias('audit')->where(['audit.action_by'=>Yii::$app->user->getId()]);
	}
	
	public function getOrders()
    {
        return $this->hasOne(ProductOrders::className(), ['productid' => 'productid'])->alias('orders')->joinWith(['ordersLogs'])->asArray();
    }
	
	public function getAuditLog(){
		return $this->hasMany(AuditLog::className(),['relaid'=>'solutionsealid'])
		->alias('audit')
		->joinWith(['admin']);
	}
	
	public function userId($mobile=""){
		$userId = User::find()->where(['mobile'=>$mobile])->alias('user')->select('id,mobile,username')->one();
		return $userId;
		
	}
	public function getCostestimates(){
		$return = [];
		$doing = [];
		$end = [];
		$endtime =time();
		$day = 15;
		foreach($this->auditLog as $log){
			if($log['afterstatus']=="40"&&$log['beforestatus']=="40"){
				$doing[]=$log;
			}
			if($log['afterstatus']=="60"&&$log['beforestatus']=="40"){
				$end[] = $log;
				$endtime = $log["action_at"];
			}
		}
		// var_dump($doing);
		
		foreach($doing as $do){
			$params = $do['params']?unserialize($do['params']):[];
			// var_dump($params);
		}
		$this->borrowmoney * $day * 0.003;
		$start = $this->borrowtime?strtotime(date("Y-m-d",$this->borrowtime)):0;
		$end = strtotime(date("Y-m-d",$endtime));
		$doDay = 1;
		if($start)$doDay += (($end-$start)/86400);
		$day = max($day,$doDay);
		// echo date("YmdHis",$endtime);
		// var_dump($end);
		// var_dump($auditLogGroup);
		$return["doDay"] = $doDay;
		$return["day"] = $day;
		$return["yongjin"] = $this->borrowmoney * $doDay * 0.003;
		$return["yongjinStr"] = $return["yongjin"]."元 = {$this->borrowmoney}元 * {$doDay}天 * 0.003";

		// var_dump($return);
		return $return;
	}
}
