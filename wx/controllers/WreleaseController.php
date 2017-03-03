<?php
namespace wx\controllers;
use Yii;
use wx\services\Func;
use yii\helpers\Url;

Class WreleaseController extends \wx\components\FrontController{
	public $layout = 'newmain';
	
	public function actionIndex(){
		
		
		
		$this->title = "清道夫债管家-我的发布";
        $this->keywords = "清道夫债管家-我的发布";
        $this->description = "清道夫债管家-我的发布";
		$type = Yii::$app->request->get('type','1');
		$token = Yii::$app->session->get('user_token');
		$page = Yii::$app->request->get('page');
		switch($type){
			case '1':
			$str =  Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/list-processing'),['token'=>$token,'page'=>$page,'limit'=>10]);
			break;
			case '2':
			$str =  Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/list-completed'),['token'=>$token,'page'=>$page,'limit'=>10]);
			break;
		}
		$data = $this->json2array($str);
		if($data['code'] == '0000'){
			$data = $data['result']['data'];
			if($data)Url::remember(["/wrelease/index","type"=>$type], 'productDetailPrev');  
			return $this->render('index',['data'=>$data]);
		}else{
			$this->notFound();
		}
		
	}
	
	
	/**
	* 我的发布中的已终止
	*/
	public function actionTermination(){
		
		$this->title = "清道夫债管家-已终止";
        $this->keywords = "清道夫债管家-已终止";
        $this->description = "清道夫债管家-已终止";
		$token = Yii::$app->session->get('user_token');
		$page = Yii::$app->request->get('page');
		$str =  Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/list-aborted'),['token'=>$token,'page'=>$page,'limit'=>10]);
		$data = $this->json2array($str);
		
		if($data['code'] == '0000'){
			$data = $data['result']['data'];
			if($data)Url::remember(["/wrelease/termination"], 'productDetailPrev');  
			return $this->render('termination',['data'=>$data]);
		}else{
			$this->notFound();
		}
	}
	
	/**
	* 我的发布详情
	*/
	public function actionDetails($productid,$messageid=''){
		$token = Yii::$app->session->get('user_token');
		$applyid = Yii::$app->request->get('applyid');
		
		
		$arr = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/apply-people'),['token'=>$token,'applyid'=>$applyid]);
		$str = $this->json2array($arr);
		if(isset($str['code'])&&$str['code'] == '0000'){
			$applyPeople = $str['result']['data'];
		}
		
		$str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/product-deta'),['token'=>$token,'productid'=>$productid,"messageid"=>$messageid]);
		//var_dump($str);die;
		$data = $this->json2array($str);
		if($data['code'] == '0000'){
			$data = $data['result']['data'];
			return $this->render('details',['data'=>$data,'messageid'=>$messageid,'applyPeople'=>isset($applyPeople)?$applyPeople:'']);
		}else{
			$this->notFound();
		}
	}
	
	/**
	* 我的发布中的申请记录
	*/
	
	public function actionApplyRecord($productid){
		$token = Yii::$app->session->get('user_token');
		$str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/applicant-list'),['token'=>$token,'productid'=>$productid]);
		$data = $this->json2array($str);
		if($data['code'] == '0000'){
			$data = isset($data['result']['data'])?$data['result']['data']:'';
			
		}
		return $this->render('applyRecord',['data'=>$data]);
	}
	
	/**
	* 发布方选择申请方面谈
	*/
	
	public function actionApplyChat(){
		$applyid = Yii::$app->request->post('applyid');
		$token = Yii::$app->session->get('user_token');
		echo $apply = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/apply-chat'),['token'=>$token,'applyid'=>$applyid]);die;
	}
	
	/**
	* 同意申请人为接单方
	*/
	public function actionApplyAgree(){
		$applyid = Yii::$app->request->post('applyid');
		$token = Yii::$app->session->get('user_token');
		echo $apply = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/apply-agree'),['token'=>$token,'applyid'=>$applyid]);die;
	} 
	
	/**
	* 和申请人面谈失败
	*/
	public function actionApplyVeto(){
		$applyid = Yii::$app->request->post('applyid');
		$token = Yii::$app->session->get('user_token');
		echo $apply = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/apply-veto'),['token'=>$token,'applyid'=>$applyid]);die;
	}

    /**
    * 发布方的聊天记录
    */	
    public function actionOrdersProcessAdd(){
		$post = Yii::$app->request->post();
		$token = Yii::$app->session->get('user_token');
		$post['token'] = $token;
		echo $process = Func::CurlPost(Yii\helpers\Url::toRoute('/wap/productorders/orders-process-add'),$post);die;
	}	
	
	/**
	* 删除订单
	*/
	public function actionProductDelete(){
		$productid = Yii::$app->request->post('productid');
		$token = Yii::$app->session->get('user_token');
		echo $product = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/product-delete'),['token'=>$token,'productid'=>$productid]);die;
	}
	
	
}