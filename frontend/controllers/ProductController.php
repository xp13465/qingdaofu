<?php
namespace frontend\controllers;
use frontend\components\FrontController;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use app\models\Product;
use app\models\ProductCollect;
use yii\filters\AccessControl;
/**
 * 一键发布产品控制器  
 */
class ProductController extends FrontController
{
	public $layout ;
	public $productModel = 'app\models\Property';
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
					[
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' =>['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
	
	/*
	* 产品列表 *
	*/
	public function actionIndex($showtype ='1'){
		$ProductQuery=Product::find();
		$post = Yii::$app->request->get();
		$province_id = Yii::$app->request->get('province_id');
		$city_id = Yii::$app->request->get('city_id');
		$district_id = Yii::$app->request->get('district_id');
		$post["province"]=$province_id;
		$post["city"]=$city_id;
		$post["district"]=$district_id;
		$selfApply = $showtype==1?true:false;
		$productList = $ProductQuery->searchLists($post,$selfApply);
        
		$data = $ProductQuery->filterAll($productList->getModels());
		
		$province = \common\models\Province::find()->select("provinceID,province")->asArray()->all();
	 
	    $province_id = Yii::$app->request->get('province_id');
		$city_id = Yii::$app->request->get('city_id');
		$district_id = Yii::$app->request->get('district_id');
		
	    $city = $province_id? \common\models\City::find()->select("cityID,city")->where(['fatherID'=>$province_id])->asArray()->all():[];
		
		$district =$city_id? \common\models\Area::find()->select("areaID,area")->where(['fatherID'=>$city_id])->asArray()->all():[];
	 
		return $this->render("index",
			[
			"province"=>$province,
			"city"=>$city,
			"district"=>$district,
			
			"province_id"=>$province_id,
			"city_id"=>$city_id,
			"district_id"=>$district_id,
			
			"data"=>$data,
			"provider"=>$productList,
			"totalCount"=>$productList->getTotalCount(),
			"curCount"=>$productList->getCount(),
			"pageSize"=>$productList->pagination->getPageSize(),
			"curpage"=>$productList->pagination->getPage()+1
			]
		);
	}
	
	/**
	* 单条产品数据
	*/
	public function actionView(){
		$productid = Yii::$app->request->get('productid');
		$ProductQuery = Product::find();
		$product = $ProductQuery->view($productid);
        $data = $ProductQuery->filterOne($product);	
		$data = $this->success("",["data"=>$data]);
	}
	
	/*
	* 我的发布列表列表 *
	*
    *
	*/
	public function actionReleaseList($listtype='',$type='0'){
		//$this->layout = 'main';
		$uid = Yii::$app->user->getId();
		$ProductQuery = Product::find();
		$page = Yii::$app->request->get('page');
		$limit = Yii::$app->request->get('limit');
		$params = ['page'=>$page,'limit'=>$limit,'listtype'=>$listtype];
		$productList  = $ProductQuery->searchList($params,$uid,false);
		$data = $ProductQuery->filterAll($productList->getModels());
		foreach($data as $key => $value){
			$data[$key]['commentTotal'] = \app\models\ProductOrdersComment::find()->where(['productid'=>$value['productid'],'validflag'=>'1','action_by'=>$uid])->count('productid');
			if($value['curapply']){
				$data[$key]['applyStatus']=$value['curapply']['status'];
				$data[$key]['statusLabel'] ="面谈中";
			}else{
				$data[$key]['applyStatus']='';
			}
		}
		return $this->render("release",
			[
			"data"=>$data,
			'type'=>$type,
			'productList'=>$productList,
			"totalCount"=>$productList->getTotalCount(),
			"curCount"=>$productList->getCount(),
			"pageSize"=>$productList->pagination->getPageSize(),
			"curpage"=>$productList->pagination->getPage()+1
			]
		);
	}
	
	/**
	 *  进行中列表 *
	 */
	public function actionListProcessing()
    { 
		$listtype = "processing";
		$type = Yii::$app->request->get('type',0);
		return $this->actionReleaseList($listtype,$type);
    }
	/**
	 *  已完成列表 *
	 */
	public function actionListCompleted()
    { 
		$listtype = "completed";
		$type = Yii::$app->request->get('type',0);
		return $this->actionReleaseList($listtype,$type);
    }
	/**
	 *  已终止列表 *
	 */
	public function actionListAborted()
    { 
		$listtype = "aborted";
		$type = Yii::$app->request->get('type',0);
		return $this->actionReleaseList($listtype,$type);
    }
	
	
	/*
	* 产品详情 *
	* 
    * 
	*/
	public function actionDetail(){
		$productid = Yii::$app->request->get('productid',276);
		if($productid){
			$ProductQuery=Product::find();
			$query  = $ProductQuery->searchOrder(Yii::$app->request->get(),'',true,false)->andWhere(['product.productid'=>$productid]);
			$obj = $query->one();
			if($obj){
				$data =$obj->toArray();
				$data['User']=$obj->certification;
				$data['apply'] = $obj->applySelf;
				$data['areaname']=$obj->areaname;
				$data['provincename']=$obj->provincename;
				$data['cityname']=$obj->cityname;
				$arr = $obj->getStatistics($productid);
				$arrs = $obj->getJudge($productid);
				$data['applyTotal'] = $arr['applyTotal'];
				$data['collectionTotal'] = $arr['collectionTotal'];
				$data['applyPeople'] = $arrs['applyPeople'];
				$data['collectionPeople'] = $arrs['collectionPeople'];	
				$data = $ProductQuery->filterOne($data);
				$data['certification'] = \app\models\Certification::findOne(['uid'=>Yii::$app->user->getId()]);
				return $this->render("productdraft",["data"=>$data]);
			}else{
				$this->errorMsg(PARAMSCHECK,"",'view');
			}
		
		}
	}
	
	/**
	* 发布方产品详情1
	*/
	public function actionProductDetails($productid=''){
		$ProductQuery=Product::find();
		$uid = Yii::$app->user->getId();
		$data=[];
		$dataobj = $ProductQuery->where(['productid'=>$productid])->one();
		if($dataobj){
			$arr = $ProductQuery->statistics($productid);
			$data['applyTotal'] = $arr;
			if($dataobj['create_by'] == $uid){
				$data['productUser'] = true;
			}else{
				$data['productUser'] = false;
			}
			if($dataobj){
				$data['productMortgages1']=$ProductQuery->addressAll($dataobj->productMortgages1,$dataobj['status']);
				$data['productMortgages2']=$ProductQuery->addressAll($dataobj->productMortgages2,$dataobj['status']);
				$data['productMortgages3']=$ProductQuery->addressAll($dataobj->productMortgages3,$dataobj['status']);
			}
			return $data ;
		}else{
			$this->errorMsg(PARAMSCHECK,"",'view');
		}
	}
	/**
	* 发布方产品详情2
	*/
	public function actionProductDeta(){
		
        $productid = Yii::$app->request->get('productid');
		$messageId = Yii::$app->request->get('messageid');
		$applyid = Yii::$app->request->get('applyid');
		
		if($messageId){
			$MessageQuery = \app\models\Message::find();
			if(!$messageId) $updateType = '';
			$MessageQuery->setRead($messageId,$updateType='GROUP');
		}
		$productQuery = Product::find();
		$uid = Yii::$app->user->getId();
		$query = $productQuery->productDeta(['productid'=>$productid],$uid,true,false);
		$products = $query->one();
		$data = $productQuery->filterOne($query->asArray()->one());
		if($applyid){
			$apply = $ProductQuery->applyPeople($applyid);
			$data['applyPeople'] = $apply->asArray()->one();
		}
			$data['productMortgages1']=$productQuery->addressAll($products->productMortgages1,$data['status']);
			$data['productMortgages2']=$productQuery->addressAll($products->productMortgages2,$data['status']);
			$data['productMortgages3']=$productQuery->addressAll($products->productMortgages3,$data['status']);

			$data['Province'] = \common\models\Province::find()->select("provinceID,province")->asArray()->all();
			$data['Brand'] = \common\models\Brand::find()->select("id,name")->asArray()->all();
		
		if($data['productApply']&&$data['productApply']['orders']){
			$dataobj = \app\models\ProductOrders::find()->where(["ordersid"=>$data['productApply']['orders']['ordersid']])->one();
			$data['accessOrdersREAD'] = $dataobj->accessOrders('READ');
			$data['accessOrdersADDPROCESS'] = $dataobj->accessOrders('ADDPROCESS');
			$data['accessOrdersADDOPERATOR'] = $dataobj->accessOrders('ADDOPERATOR');
			$data['accessOrdersADDCOMMENT'] = $dataobj->accessOrders('ADDCOMMENT');
			$data['accessOrdersORDERCOMFIRM'] = $dataobj->accessOrders('ORDERCOMFIRM');
			$data['accessClosedREAD'] = $dataobj->accessClosed('READ');
			$data['accessClosedAPPLY'] = $dataobj->accessClosed('APPLY');
			$data['accessClosedAUTH'] = $dataobj->accessClosed('AUTH');
			$data['accessTerminationREAD'] = $dataobj->accessTermination('READ');
			$data['accessTerminationAPPLY'] = $dataobj->accessTermination('APPLY');
			$data['accessTerminationAUTH'] = $dataobj->accessTermination('AUTH');
			$data['checkStatus']= $dataobj->checkStatus();
			$data['commentTotal'] = \app\models\ProductOrdersComment::find()->where(['productid'=>$productid,'validflag'=>'1','action_by'=>$uid])->count('productid');
			$data['commentList'] = \app\models\ProductOrders::find()->commentList($data['productApply']['orders']['ordersid'],false);
			$data['pacts'] = $dataobj->pacts;
			if(in_array($data['status'],['20','30','40'])){
			  $data['SignPicture'] = $dataobj->pacts;	
			  $data['productOrdersClosed'] = $dataobj->productOrdersClosed;
			}
			$data['productOrdersTerminationsApply']= $dataobj?$dataobj->productOrdersTerminationsApply:[];
			$data['productOrdersClosedsApply']= $dataobj?$dataobj->productOrdersClosedsApply:[];
			$data['productOrdersOperators']= $dataobj?$dataobj->productOrdersOperators:[];
			$data['productOrdersTerminationsApplyCount']= count($data['productOrdersTerminationsApply']);
			$data['productOrdersClosedsApplyCount']= count($data['productOrdersClosedsApply']);
			$data['productOrdersOperatorsCount']= count($data['productOrdersOperators']);
			$Operators = [];
			foreach($data['productOrdersOperators'] as $v)$Operators[]=$v['operatorid'];
			$data['productApply']['orders']['Operators']=$Operators;
			$actionLabels = \app\models\ProductOrdersLog::$actionLabels;
			if($data['productApply']&&$data['productApply']['orders']&&$data['productApply']['orders']['productOrdersLogs']){
				$data['productApply']['orders']['productOrdersLogs'] = \app\models\ProductOrdersLog::filterAll(
				$data['productApply']['orders']['productOrdersLogs'],
				$data['accessTerminationAUTH'],
				$data['accessClosedAUTH'],
				$data['checkStatus'],
				$data["create_by"],
				$data['productApply']['orders']["create_by"],
				$data['productOrdersOperators'],
				$data
				);
			}
		}
		if($data){
			$applyList = $productQuery->applicantList(['productid'=>$productid]);
			$data['applyList'] = isset($applyList['apply'])?$applyList['apply']:[];
			$data['certificationState'] = isset($applyList['state'])?$applyList['state']:'';
			$arr = $productQuery->statistics($productid);
			$data['applyCount'] = $arr;
					// echo "<pre>";
 // print_r($data);die;
			return $this->render("details",["data"=>$data,"messageId"=>$messageId]);	
		}else{
			$this->errorMsg(PARAMSCHECK,"","view");
		}
		
	}
	
	public function actionCreate(){
		$productid = Yii::$app->request->get('productid');
		$province = \common\models\Province::find()->select("provinceID,province")->asArray()->all();
		$ProductQuery = Product::find();
		$product = $ProductQuery->view($productid);
        $data = $ProductQuery->filterOne($product);
	
		return $this->render('create',['province'=>$province,'data'=>isset($data)?$data:'']);
	}
	
	/*
	* 创建发布产品数据
	*
    *
	*/
	public function actionRelease(){
		$post = Yii::$app->request->post();
		$category = "";
		if(isset($post['category'])){
			$post['category']= implode(",",$post['category']);
		}
        if(isset($post['entrust'])){
			$post['entrust']= implode(",",$post['entrust']);
		}
		if(isset($post['type'])&& $post['type']==1){
			$post['typenum'] = $post['typenum1'];
		}else{
			$post['typenum'] = $post['typenum2'];
		}
		$params = [
		    'category'=>'',
			'entrust'=>'',
			'account'=>'',
			'category_other'=>'',
			'city_id'=>'',
			'district_id'=>'',
			'entrust_other'=>'',
			'overdue'=>"",
			'province_id'=>'',
			'type'=>'',
			'typenum'=>''
			];
		foreach($params as $k=>$v){
			$params[$k]=isset($post[$k])?$post[$k]:$params[$k];
		}
		$ProductQuery=Product::find();
		$status  = $ProductQuery->release($params);
		switch($status){
			case "ok":
				$a = $this->success("发布成功",["productid"=>$ProductQuery->productid]);
				break;
			default:
				$this->errorMsg($status,$ProductQuery->formatErrors());
				break;
		}
			  
	}
	
	/*
	* 创建保存产品数据
	* 
    * 
	*/
	public function actionDraft(){
		$post = Yii::$app->request->post();
		$post['token'] = Yii::$app->session->get('user_token');
		$category = "";
		if(isset($post['category'])){
			$post['category']= implode(",",$post['category']);
		}
        if(isset($post['entrust'])){
			$post['entrust']= implode(",",$post['entrust']);
		}
		if(isset($post['type'])&& $post['type']==1){
			$post['typenum'] = $post['typenum1'];
		}else{
			$post['typenum'] = $post['typenum2'];
		}
		$params = [
		    'productid'=>'',
		    'category'=>'',
			'entrust'=>'',
			'account'=>'',
			'category_other'=>'',
			'city_id'=>'',
			'district_id'=>'',
			'entrust_other'=>'',
			'overdue'=>"",
			'province_id'=>'',
			'type'=>'',
			'typenum'=>''
			];
		foreach($params as $k=>$v){
			$params[$k]=isset($post[$k])?$post[$k]:$params[$k];
		}
		$ProductQuery=Product::find();
		$status  = $ProductQuery->draft($params);
		switch($status){
			case "ok":
				//$message = \app\models\Message::find();
				//$data = $message->addMessage(101,['code'=>$ProductQuery->number],Yii::$app->user->getId(),$ProductQuery->productid,40);
				$this->success("保存成功",["productid"=>$ProductQuery->productid]);
				break;
			default:
				$this->errorMsg($status,$ProductQuery->formatErrors());
				break;
		}  
	}
	
	/**
	 * 草稿列表
	 */
	 public function actionPreservation($type=0){
		//$this->layout = 'main';
		$ProductQuery = Product::find();
		$params = Yii::$app->request->get();
		$Preservation = $ProductQuery->preservation($params);
		$data = $Preservation->getModels();
		return $this->render("draft",
			[
			"data"=>$data,
			'type'=>$type,
			'Preservation'=>$Preservation,
			"totalCount"=>$Preservation->getTotalCount(),
			"curCount"=>$Preservation->getCount(),
			"pageSize"=>$Preservation->pagination->getPageSize(),
			"curpage"=>$Preservation->pagination->getPage()+1
			]
		);
	 } 
	
	
	
	/*
	* 删除产品数据
	*/
	public function actionProductDelete(){
		$productid = Yii::$app->request->post('productid');
		$ProductQuery=Product::find();
		$status = $ProductQuery->productDelete($productid);
		switch($status){
			case "ok":
			    //$message = \app\models\Message::find();
				//$data = $message->addMessage(103,['code'=>$ProductQuery->number],Yii::$app->user->getId());
				$this->success("删除成功");
				break;
			default:
				$this->errorMsg($status,$ProductQuery->formatErrors());
				break;
		}
	}
	
	
	
	
	/*
	*编辑发布产品
	*
	*/
	public function actionEdit(){
		$post = Yii::$app->request->post();
		$post['token'] = Yii::$app->session->get('user_token');
		$category = "";
		if(isset($post['category'])){
			$post['category']= implode(",",$post['category']);
		}
        if(isset($post['entrust'])){
			$post['entrust']= implode(",",$post['entrust']);
		}
		if(isset($post['type'])&& $post['type']==1){
			$post['typenum'] = $post['typenum1'];
		}else{
			$post['typenum'] = $post['typenum2'];
		}
		$params = [
		    'productid'=>'',
		    'category'=>'',
			'entrust'=>'',
			'account'=>'',
			'category_other'=>'',
			'city_id'=>'',
			'district_id'=>'',
			'entrust_other'=>'',
			'overdue'=>"",
			'province_id'=>'',
			'type'=>'',
			'typenum'=>''
			];
		foreach($params as $k=>$v){
			$params[$k]=isset($post[$k])?$post[$k]:$params[$k];
		}
		$ProductQuery=Product::find();
		$status = $ProductQuery->edit($params,$params['productid'],'','2');
		switch($status){
			case "ok";
			  //$message = \app\models\Message::find();
			  //$data = $message->addMessage(104,['code'=>$ProductQuery->number],Yii::$app->user->getId());
			  $this->success("编辑成功");
			  break;
		    default:
			 $this->errorMsg($status,$ProductQuery->formatErrors());
			 break;
		}
	}
	
	/*
	*编辑草稿产品
	*
	*/
	public function actionPreservationEdit(){
		$post = Yii::$app->request->post();
		$post['token'] = Yii::$app->session->get('user_token');
		$category = "";
		if(isset($post['category'])){
			$post['category']= implode(",",$post['category']);
		}
        if(isset($post['entrust'])){
			$post['entrust']= implode(",",$post['entrust']);
		}
		if(isset($post['type'])&& $post['type']==1){
			$post['typenum'] = $post['typenum1'];
		}else{
			$post['typenum'] = $post['typenum2'];
		}
		$params = [
		    'productid'=>'',
		    'category'=>'',
			'entrust'=>'',
			'account'=>'',
			'category_other'=>'',
			'city_id'=>'',
			'district_id'=>'',
			'entrust_other'=>'',
			'overdue'=>"",
			'province_id'=>'',
			'type'=>'',
			'typenum'=>''
			];
		foreach($params as $k=>$v){
			$params[$k]=isset($post[$k])?$post[$k]:$params[$k];
		}
		$ProductQuery=Product::find();
		$status = $ProductQuery->edit($params,$params['productid'],'','1');
		switch($status){
			case "ok";
			  //$message = \app\models\Message::find();
			  //$data = $message->addMessage(105,['code'=>$ProductQuery->number],Yii::$app->user->getId());
			  $this->success("编辑成功");
			  break;
		    default:
			 $this->errorMsg($status,$ProductQuery->formatErrors());
			 break;
		}
	}
	
	/**
	* 收藏产品
	*
	*/
	
	public function actionCollect(){
		$productid = Yii::$app->request->post('productid');
	   // var_dump($productid);die;
		$productCollect = ProductCollect::find();
		$status = $productCollect->collect($productid);
		switch($status){
			case "ok";
			  //$message = \app\models\Message::find();
			  //$data = $message->addMessage(106,['code'=>$productCollect->number],Yii::$app->user->getId());
			  $this->success("收藏成功");
			  break;
		    default:
			 $this->errorMsg($status,$productCollect->formatErrors());
			 break;
		}
		
	}
	
	/**
	* 删除收藏产品
	*/
	
	public function actionCollectCancel(){
		$productid = Yii::$app->request->post('productid');
		$productCancel = ProductCollect::find();
		$status = $productCancel->collectCancel($productid);
		switch($status){
			case "ok";
			  $this->success("取消成功");
			  break;
		    default:
			 $this->errorMsg($status,$productCancel->formatErrors());
			 break;
		}
	}
	
	
	/**
	* 收藏列表
	*/
	
	public function actionCollectList($type='0'){
		$uid = Yii::$app->user->getId();
		$post = Yii::$app->request->get();
		$collect  = ProductCollect::find();
		$collectList = $collect->searchList($post,$uid);
		$data = $collect->collectAll($collectList->getModels());
		return $this->render("collection",
			[
			"data"=>$data,
			'type'=>$type,
			'collectList'=>$collectList,
			"totalCount"=>$collectList->getTotalCount(),
			"curCount"=>$collectList->getCount(),
			"pageSize"=>$collectList->pagination->getPageSize(),
			"curpage"=>$collectList->pagination->getPage()+1
			]
		);
	}

   /**
	* 抵押物地址新增
	*/	
	
	public function actionMortgageAdd(){
		$post = Yii::$app->request->post();
		$ProductQuery=Product::find();
		$status = $ProductQuery->mortgageAdd($post); 
		switch($status){
			case "ok":
				$this->success("添加成功");
				break;
			default:
				$this->errorMsg($status,$ProductQuery->formatErrors());
				break;
		}
	}
	
	/**
	* 抵押物地址编辑
	*/
	
	public function actionMortgageEdit(){
		$post = Yii::$app->request->post();
		$ProductQuery=Product::find();
		$status = $ProductQuery->mortgageEdit($post);
		switch($status){
			case 'ok':
				$this->success('修改成功');
				break;
			default:
				$this->errorMsg($status,$ProductQuery->formatErrors());
				break;
		}
	}
	
	/**
	* 抵押物地址删除
	*/
	
	public function actionMortgageDel(){
		$mortgageid = Yii::$app->request->post('mortgageid');
		$ProductQuery=Product::find();
		$status = $ProductQuery->mortgageDel($mortgageid);
		switch($status){
			case 'ok':
				$this->success('删除成功');
				break;
			default:
				$this->errorMsg($status,$ProductQuery->formatErrors());
				break;
		}
	}
	
	/**
	* 抵押物地址详情
	*/
	public function actionMortgageDetail(){
		$mortgageid = Yii::$app->request->get('mortgageid');
		$ProductQuery=Product::find();
		$status = $ProductQuery->mortgageDetail($mortgageid); 
	}
	
	/**
	* 接单方申请接单**
	*/
	public function actionApply(){
		$productid = Yii::$app->request->post('productid');
		//$create_at = Yii::$app->request->get('create_at');
		$ProductQuery=Product::find();
		$status = $ProductQuery->apply($productid);
		switch($status){
			case "ok":
				$this->success("申请成功",["applyid"=>$ProductQuery->applyid,"certification"=>"0"]);
				break;
			case "on":
			    $this->success("申请成功！您还未认证请尽快认证",["applyid"=>$ProductQuery->applyid,"certification"=>"1"]);
			    break;
			default:
				$this->errorMsg($status,$ProductQuery->formatErrors());
				break;
		}
	}
	
	/**
	* 接单方取消申请**
	*/
	public function actionApplyCancel(){
		$applyid = Yii::$app->request->post('applyid');
		$ProductQuery=Product::find();
		$status = $ProductQuery->applyCancel($applyid);
		switch($status){
			case "ok":
				$this->success("取消成功");
				break;
			default:
				$this->errorMsg($status,$ProductQuery->formatErrors());
				break;
		}
	}
	
	/**
	* 接单方申请列表
	*/
	public function actionApplicantList($productid){
	    //$productid = Yii::$app->request->get('productid');
		$ProductQuery=Product::find();
		$data = $ProductQuery->applicantList(['productid'=>$productid]);
		if(is_array($data)){
			return $data;
		}else{
			$this->errorMsg($data,"","view");
		}
	}
	
	/**
	* 选中的接单方
	*/
	public function actionApplyPeople(){
		$applyid = Yii::$app->request->get('applyid');
		$ProductQuery=Product::find();
		$apply = $ProductQuery->applyPeople($applyid);
		$data = $apply->asArray()->one();
		if(is_array($data)){
			$this->success("",['data'=>$data]);
		}else{
			$this->errorMsg($data,"",'view');
		}
	}
	
	/**
	* 接单方详情
	*/
	public function actionApplyDetails(){
		$userid = Yii::$app->request->get('userid');
		$ProductQuery=Product::find();
		$data = $ProductQuery->applyDetails($userid);
		if($data||$data==[]){
			$this->success("",['data'=>$data]);
		}elseif($data===false){
			$this->errorMsg('UserAuth',"",'view');
		}else{
			$this->errorMsg('NotFound',"",'view');
		}  
	}
	
	
	
	
	
	/**
	* 发布方选择接单方面谈
	*/
	public function actionApplyChat(){
		$applyid = Yii::$app->request->post('applyid');
		$ProductQuery=Product::find();
		$status=$ProductQuery->applyChat($applyid);
		switch($status){
			case "ok":
				$this->success("同意面谈");
				break;
			default:
				$this->errorMsg($status,$ProductQuery->formatErrors());
				break;
		}
	}
	
	/**
	* 发布方取消接单方面谈
	*/
	public function actionApplyVeto(){
		$applyid = Yii::$app->request->post('applyid');
		$ProductQuery=Product::find();
		$status=$ProductQuery->applyVeto($applyid);
		switch($status){
			case "ok":
				$this->success("取消面谈");
				break;
			default:
				$this->errorMsg($status,$ProductQuery->formatErrors());
				break;
		}
	}
	
	/**
	* 发布方同意接单方处理
	*/
	public function actionApplyAgree(){
		$applyid = Yii::$app->request->post('applyid');
		$ProductQuery=Product::find();
		$status=$ProductQuery->applyAgree($applyid);
		$ProductOrdersQuery = \app\models\ProductOrders::find(); 
		$ProductOrdersQuery->ordersGenerate($applyid);
		switch($status){
			case "ok":
				$this->success("同意接单");
				break;
			default:
				$this->errorMsg($status,$ProductQuery->formatErrors());
				break;
		}
	}
	
	/**
	* 省份
	*/
	public function actionProvince(){
	   $province = \common\models\Province::find()->select("provinceID,province")->asArray()->all();
	   return $province;
	}
	
	/**
	* 城市
	*/
	public function actionCity(){
	   $province_id = Yii::$app->request->post('province_id');
	   $city = \common\models\City::find()->select("cityID,city")->where(['fatherID'=>$province_id])->asArray()->all();
       if($city){
		 $html = \yii\helpers\Html::dropDownList('','',\yii\helpers\ArrayHelper::map($city,'cityID','city'));
		 $html = trim(str_replace('<select name="">','',$html));
		 $html = str_replace('</select>','',$html);
		 return $this->success('',['html'=>$html]);die;  
	   }
	}
	/**
	* 市区
	*/
	public function actionDistrict(){
		$city = Yii::$app->request->post('city_id');
		$district = \common\models\Area::find()->select("areaID,area")->where(['fatherID'=>$city])->asArray()->all();
		if($district){
		 $html = \yii\helpers\Html::dropDownList('','',\yii\helpers\ArrayHelper::map($district,'areaID','area'));
		 $html = trim(str_replace('<select name="">','',$html));
		 $html = str_replace('</select>','',$html);
		 return $this->success('',['html'=>$html]);die;  
	   }
	}
	
	/**
	* 车类型
	*/
	public function actionBrand(){
		$brand = \common\models\Brand::find()->select("id,name")->asArray()->all();
		return $this->success("",['data'=>$brand]);
	}
	
	/**
	* 车系
	*/
	public function actionAudi(){
		$brand_id = Yii::$app->request->get('brand_id');
		$audi = \common\models\Audi::find()->select("id,name")->where(['pid'=>$brand_id])->asArray()->all();
		return $this->success("",['data'=>$audi]);
	}
	
	
	/**
	* 车系处理
	*/
	public function actionAudis(){
		$brand_id = Yii::$app->request->post('brand_id');
		$data = \common\models\Audi::find()->select("id,name")->where(['pid'=>$brand_id])->asArray()->all();
		$html = \yii\helpers\Html::dropDownList('','',yii\helpers\ArrayHelper::map($data,'id','name'));
		$html = trim(str_replace('<select name="">','',$html));
		$html = str_replace('</select>','',$html);
		return $this->success('',['html'=>$html]);die;  
		
	}
	
	
	/**
	* 接单协议
	*/
    public function actionAgreement($productid,$type="view"){
	    $query = Product::find();
		$agreement = $query->agreement(['productid'=>$productid],'',false);
		$data  = $agreement->asArray()->one();
		if(!$data)$this->errorMsg("NotFound","","view");
		$this->layout =false;
		if ($data['create_by'] == Yii::$app->user->getId()) {
			//居间协议（清收委托人）
			$view="jujian";
		} else {
			//居间协议 （清收人）
			$view="jujian01";
		}
       
		if($type=="pdf"){
			$mpdf=new \mPDF('zh-CN','A4','','ccourierB','15','15','24','24','5','5');
			$mpdf->useAdobeCJK = true;
			$header='<table width="100%" style="">
			<tr>
			<td width="20%"></td>
			<td width="60%" align="center" style="font-size:px;color:#AAA">页眉</td>
			<td width="20%" style="text-align: right;"></td>
			</tr> 	
			</table>';
			//设置PDF页脚内容
			$footer='<table width="100%" style=" vertical-align: bottom; font-family:黑体; font-size:12pt; color: #;"><tr style="height:px"></tr><tr>
			<td width="20%"></td>
			<td width="60%" align="center" style="font-size:10px;">{PAGENO}/{nb}</td>
			<td width="20%" style="text-align: left;"></td>
			</tr></table>';
			//添加页眉和页脚到pdf中
			$mpdf->SetHTMLHeader($this->renderPartial('pdf_header'));
			$mpdf->SetHTMLFooter($this->renderPartial('pdf_footer'));
			//$mpdf->AddPage();
			
			$mpdf->WriteHTML($this->renderPartial($view,['data'=>$data]));
			// $mpdf->WriteHTML($this->renderPartial('myview'));
			//$mpdf->AddPage();
			$mpdf->Output('MyPDF.pdf', "I");//I查看D下载
			exit;
		}else{
			return $this->render($view,['data'=>$data]);
		}
		
		
		
		
		
		
		
        if($arrs['code'] == '0000'){
            $data  = $arrs['result']['data'];
			
			if ($data['create_by'] == Yii::$app->user->getId()) {
				//居间协议（清收委托人）
				echo $this->render('jujian',['data'=>$data]);
			} else {
				//居间协议 （清收人）
				echo $this->render('jujian01',['data'=>$data]);
			}
            // return $this->render('mediacys',['data'=>$data]);
        }else{
            // return $this->render('mediacys');
        }
		
		
		
    }
}
