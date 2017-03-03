<?php
namespace frontend\modules\wap\controllers;

use frontend\modules\wap\components\WapController;
use yii;
use yii\web\Controller;

// use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use app\models\Product;
use app\models\ProductCollect;
/**
 * 一键发布产品控制器  
 */
class ProductController extends WapController
{
	public $productModel = 'app\models\Property';
	/*
	public function behaviors()
	{
		
		$variations = array_merge(Yii::$app->request->post(),Yii::$app->request->get());
		return [
			'pageCache' => [
				'class' => 'yii\filters\PageCache',
				'only' => ['index'],
				'duration' => 600,
				'dependency' => [
					'class' => 'yii\caching\DbDependency',
					'sql' => 'SELECT  sum(productid) FROM zcb_product where validflag = "1" and status = "10" ',
				],
				'variations' => $variations
			],
		];
	}*/

	public function actionDemo()
    { 
		$prefix = '/' . $this->uniqueId . '/';
		$class = new \ReflectionClass($this);
		foreach ($class->getMethods() as $method) {
            $name = $method->getName();
            if ($method->isPublic() && !$method->isStatic() && strpos($name, 'action') === 0 && $name !== 'actions') {
                $name = strtolower(preg_replace('/(?<![A-Z])[A-Z]/', ' \0', substr($name, 6)));
                $id = $prefix . ltrim(str_replace(' ', '-', $name), '-');
                $result[$id] = $id;
            }
        }
		return $this->render("demo",['result'=>$result]);
		
    }
	
	/*
	* 产品列表 *
	*/
	public function actionIndex($showtype =''){
		$ProductQuery=Product::find();
		$post = Yii::$app->request->post();
		$showtype = isset($post['showtype'])?$post['showtype']:$showtype;
		//$productList = $ProductQuery->searchLists($post,'',false);
		$selfApply = $showtype == 1?true:false;
		$productList = $ProductQuery->searchLists($post,$selfApply);
        
		$data = $ProductQuery->filterAll($productList->getModels());
		$Total['Qingshou'] = \frontend\services\Func::getQingshouTotal();
		$Total['Susong']   = \frontend\services\Func::getSusongTotal();
		$Total['Baohan']   =  \frontend\services\Func::getBaohanTotal();
		$Total['Baoquan']  =  \frontend\services\Func::getBaoquanTotal();
		$data = $this->success("",
			[
			"data"=>$data,
			"sum"=>array_sum($Total),
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
		$productid = Yii::$app->request->post('productid');
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
	public function actionReleaseList($params){
		
		$uid = Yii::$app->user->getId();
		$ProductQuery = Product::find();
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
		$data = $this->success("",
			[
			"data"=>$data,
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
		$page = Yii::$app->request->post('page');
		$limit = Yii::$app->request->post('limit');
		$params = ['page'=>$page,'limit'=>$limit,'listtype'=>$listtype]; 
		return $this->actionReleaseList($params);
    }
	/**
	 *  已完成列表 *
	 */
	public function actionListCompleted()
    { 
		$listtype = "completed";
		$page = Yii::$app->request->post('page');
		$limit = Yii::$app->request->post('limit');
		$params = ['page'=>$page,'limit'=>$limit,'listtype'=>$listtype];
		return $this->actionReleaseList($params);
    }
	/**
	 *  已终止列表 *
	 */
	public function actionListAborted()
    { 
		$listtype = "aborted";
		$page = Yii::$app->request->post('page');
		$limit = Yii::$app->request->post('limit');
		$params = ['page'=>$page,'limit'=>$limit,'listtype'=>$listtype];
		return $this->actionReleaseList($params);
    }
	
	
	/*
	* 产品详情 *
	* 
    * 
	*/
	public function actionDetail(){
		$productid = Yii::$app->request->post('productid');
		if($productid){
			$ProductQuery=Product::find();
			$query  = $ProductQuery->searchOrder(Yii::$app->request->post(),'',true,false)->andWhere(['product.productid'=>$productid]);
			$obj = $query->one();
			if($obj){
				$data =$obj->toArray();
				$data['User']=$obj->certification;
				$data['apply'] = $obj->applySelf;
				//$data['fabuuser']=$obj->fabuuser;
				$data['areaname']=$obj->areaname;
				$data['provincename']=$obj->provincename;
				$data['cityname']=$obj->cityname;
				$arr = $obj->getStatistics($productid);
				$arrs = $obj->getJudge($productid);
				// var_dump($arrs);die;
				$data['applyTotal'] = $arr['applyTotal'];
				$data['collectionTotal'] = $arr['collectionTotal'];
				$data['applyPeople'] = $arrs['applyPeople'];
				$data['collectionPeople'] = $arrs['collectionPeople'];	
				$data = $ProductQuery->filterOne($data);
				$this->success("",["data"=>$data]);
			}else{
				$this->errorMsg(PARAMSCHECK);
			}
		
		}
	}
	
	/**
	* 发布方产品详情
	*/
	public function actionProductDetails(){
		$productid = Yii::$app->request->post('productid');
		$ProductQuery=Product::find();
		$uid = Yii::$app->user->getId();
		$query  = $ProductQuery->searchOrder(['productid'=>$productid],'',true,false)->andFilterWhere(['product.productid'=>$productid]);
		$query->joinWith(['productMortgages']);
		$obj = $query->asArray();
		$data = $obj->one();
		if($data){
			$arr = $ProductQuery->statistics($productid);
			$data['applyTotal'] = $arr;
			$data = $ProductQuery->filterOne($data);
			if($data['create_by'] == $uid){
				$data['productUser'] = true;
			}else{
				$data['productUser'] = false;
			}
			if($data){
				$dataobj = $ProductQuery->where(['productid'=>$productid])->one();
				$data['productMortgages1']=$ProductQuery->addressAll($dataobj->productMortgages1,$data['status']);
				$data['productMortgages2']=$ProductQuery->addressAll($dataobj->productMortgages2,$data['status']);
				$data['productMortgages3']=$ProductQuery->addressAll($dataobj->productMortgages3,$data['status']);
			}
			$this->success("",["data"=>$data]);
		}else{
			$this->errorMsg(PARAMSCHECK);
		}
	}
	//oncondition
	public function actionProductDeta(){
        $productid = Yii::$app->request->post('productid');
		$messageId = Yii::$app->request->post('messageid');
		if($messageId){
			$MessageQuery = \app\models\Message::find();
			if(!$messageId) $updateType = '';
			$MessageQuery->setRead($messageId,$updateType='GROUP');
		}
		$productQuery = Product::find();
		$uid = Yii::$app->user->getId();
		$query = $productQuery->productDeta(['productid'=>$productid],$uid,true,false);
		$data = $productQuery->filterOne($query->asArray()->one());
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
		//var_dump($data);die;
		if($data){
			$arr = $productQuery->statistics($productid);
			$data['applyCount'] = $arr;
			$this->success("",["data"=>$data]);	
		}else{
			$this->errorMsg(PARAMSCHECK);
		}
		
	}
	
	/*
	* 创建发布产品数据
	*
    *
	*/
	public function actionRelease(){
		$post = Yii::$app->request->post();
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
				$this->success("发布成功",["productid"=>$ProductQuery->productid]);
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
	 public function actionPreservation(){
		$ProductQuery = Product::find();
		$params = Yii::$app->request->post();
		$Preservation = $ProductQuery->preservation($params);
		$data = $Preservation->getModels();
		$data = $this->success("",
			[
			"data"=>$data,
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
	
	public function actionCollectList(){
		$uid = Yii::$app->user->getId();
		$post = Yii::$app->request->post();
		$collect  = ProductCollect::find();
		$collectList = $collect->searchList($post,$uid);
		$data = $collect->collectAll($collectList->getModels());
		$a = $this->success("",
			[
			"data"=>$data,
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
		$mortgageid = Yii::$app->request->post('mortgageid');
		$ProductQuery=Product::find();
		$status = $ProductQuery->mortgageDetail($mortgageid);
        echo "<pre>";
		print_r($status);die;   
	}
	
	/**
	* 接单方申请接单
	*/
	public function actionApply(){
		$productid = Yii::$app->request->post('productid');
		//$create_at = Yii::$app->request->post('create_at');
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
	* 接单方取消申请
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
	public function actionApplicantList(){
		$productid = Yii::$app->request->post('productid');
		$ProductQuery=Product::find();
		$data = $ProductQuery->applicantList(['productid'=>$productid]);
		if(is_array($data)){
			$this->success("",['data'=>$data]);
		}else{
			$this->errorMsg($data);
		}
	}
	
	/**
	* 选中的接单方
	*/
	public function actionApplyPeople(){
		$applyid = Yii::$app->request->post('applyid');
		$ProductQuery=Product::find();
		$apply = $ProductQuery->applyPeople($applyid);
		$data = $apply->asArray()->one();
		if(is_array($data)){
			$this->success("",['data'=>$data]);
		}else{
			$this->errorMsg($data);
		}
	}
	
	/**
	* 接单方详情
	*/
	public function actionApplyDetails(){
		$userid = Yii::$app->request->post('userid');
		$ProductQuery=Product::find();
		$data = $ProductQuery->applyDetails($userid);
		if($data||$data==[]){
			$this->success("",['data'=>$data]);
		}elseif($data===false){
			$this->errorMsg('UserAuth');
		}else{
			$this->errorMsg('NotFound');
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
		$type = Yii::$app->request->post("type","web");
		$select = $type=="app"?["id"=>"provinceID","name"=>"province"]:"provinceID,province";
		$province = \common\models\Province::find()->select($select)->asArray()->all();
		return $this->success($type,['data'=>$province]);
	}
	
	/**
	* 城市
	*/
	public function actionCity(){
	   $province_id = Yii::$app->request->post('province_id','310000');
	   $type = Yii::$app->request->post("type","web");
	   $select = $type=="app"?["id"=>"cityID","name"=>"city"]:"cityID,city";
	   $city = \common\models\City::find()->select($select)->where(['fatherID'=>$province_id])->asArray()->all();
	   return $this->success($type,['data'=>$city]);
	}
	/**
	* 市区
	*/
	public function actionDistrict(){
		$city = Yii::$app->request->post('city','310100');
	    $type = Yii::$app->request->post("type","web");
	    $select = $type=="app"?["id"=>"areaID","name"=>"area"]:"areaID,area";
		$district = \common\models\Area::find()->select($select)->where(['fatherID'=>$city])->asArray()->all();
		return $this->success($type,['data'=>$district]);
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
		$brand_id = Yii::$app->request->post('brand_id');
		$audi = \common\models\Audi::find()->select("id,name")->where(['pid'=>$brand_id])->asArray()->all();
		return $this->success("",['data'=>$audi]);
	}
	
	/**
	* 接单协议
	*/
    public function actionAgreement($type="json"){
        $type = Yii::$app->request->post('type',$type);
        $productid = Yii::$app->request->post('productid','69');
	    $query = Product::find();
		$agreement = $query->agreement(['productid'=>$productid],'',false);
		$data  = $agreement->asArray()->one();
		if(!$data)$this->errorMsg("NotFound");
		
		if($type=="json"){
			return $this->success("",['data'=>$data]);
		}
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
		
		
    }
}
