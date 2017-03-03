<?php
namespace wx\controllers;
use Yii;
use wx\services\Func;
use yii\helpers\Url;

Class ProductordersController extends \wx\components\FrontController{
	public $layout = 'newmain';
	/**
	* 创建发布产品
	*/
	public function actionIndex(){
		
		
		$type = Yii::$app->request->get('type','1');
		$list = Yii::$app->request->get('list','0');
		
		
		
		$this->title = "清道夫债管家-".($list==1?"经办事项":"我的接单");
		
		$token = Yii::$app->session->get('user_token');
		$page = Yii::$app->request->get('page');
		switch($type){
			case '1':
			$str =  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/list-processing'),['token'=>$token,'type'=>$list,'page'=>$page,'limit'=>10]);
			break;
			case '2':
			$str =  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/list-completed'),['token'=>$token,'type'=>$list,'page'=>$page,'limit'=>10]);
			break;
			case '3':
			$str =  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/list-aborted'),['token'=>$token,'type'=>$list,'page'=>$page,'limit'=>10]);
			break;
		}
		$data = $this->json2array($str);
		if($data['code'] == '0000'){
			$userid = $data['userid'];
			// $data = $data['result']['data'];
			if($data['result']['data'])Url::remember(["/productorders/index","type"=>$type,"list"=>$list], 'ordersDetailPrev');  
			return $this->render('index',array_merge($data['result'],['userid'=>$userid,'list'=>$list,'type'=>$type]));
		}else{
			$this->notFound();
		}
		
	}
	/**
	* 我的发布中的已终止
	*/
	public function actionDetail($applyid,$messageid=''){
		$this->title = "清道夫债管家-接单详情";
		$token = Yii::$app->session->get('user_token');
		$page = Yii::$app->request->get('page');
		// $applyid = Yii::$app->request->get('applyid');
		// $messageid = Yii::$app->request->get('messageid');
		$str =  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/detail'),['token'=>$token,'applyid'=>$applyid,'messageid'=>$messageid]);
		//var_dump($str);die;
		$data = $this->json2array($str);
		if($data['code'] == '0000'){
			$userid = $data['userid'];
			$data = $data['result']['data'];
			return $this->render('detail',['data'=>$data,'userid'=>$userid,'messageid'=>$messageid]);
		}else{
			$this->notFound();
		}
	}
	/**
	 *  居间确认
	 *
	 */
	public function actionOrdersConfirm(){
        if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $ordersid = Yii::$app->request->post('ordersid');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-confirm'), ['token'=>$token,'ordersid'=>$ordersid]);die;
        }
    }
	/**
	 *  协议详情
	 *
	 */
	public function actionOrdersPactDetail(){
		$this->title = "清道夫债管家-签约协议详情";
		$token = Yii::$app->session->get('user_token');
		$ordersid = Yii::$app->request->get('ordersid');
		$str =  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-pact-detail'),['token'=>$token,'ordersid'=>$ordersid]);
		$data = $this->json2array($str);
		if($data["code"]=="0000"){
			return $this->render('orderspactform',['data'=>$data["result"]['data']]);
		}else{
			$this->notFound();
		}
    }
	/**
	 *  协议添加
	 *
	 */
	public function actionOrdersPactAdd(){
        if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $ordersid = Yii::$app->request->post('ordersid');
            $files = Yii::$app->request->post('files');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-pact-add'), ['token'=>$token,'ordersid'=>$ordersid,'files'=>$files]);die;
        }
    }
	/**
	 *  协议确认
	 *
	 */
	public function actionOrdersPactConfirm(){
        if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $ordersid = Yii::$app->request->post('ordersid');
			$files = Yii::$app->request->post('files');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-pact-confirm'), ['token'=>$token,'ordersid'=>$ordersid,'files'=>$files]);die;
        }
    }
	
	/**
	* 接单方取消申请接单
	*/
	public function actionApplyCancel(){
        if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $applyid = Yii::$app->request->post('applyid');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/apply-cancel'), ['token'=>$token,'applyid'=>$applyid]);die;
        }
    }
	
	
	 
	/**
	 *  终止申请表单
	 *
	 */
	public function actionOrdersTerminationForm(){
		return $this->render('ordersterminationform');
    }
	
	/**
	 *  终止详情
	 *
	 */
	public function actionOrdersTerminationDetail(){
		$token = Yii::$app->session->get('user_token');
		$terminationid = Yii::$app->request->get('terminationid');
		$str =  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-termination-detail'),['token'=>$token,'terminationid'=>$terminationid]);
		
		$data = $this->json2array($str);
		if($data["code"]=="0000"){
			return $this->render('ordersterminationdetail',['data'=>$data["result"]['data'],'accessTerminationAUTH'=>$data["result"]['accessTerminationAUTH'],'dataLabel'=>$data["result"]['dataLabel'],'userid'=>$data["userid"]]);
		}else{
			$this->notFound();
		}
    }
	/**
	 *  终止申请
	 *
	 */
	public function actionOrdersTerminationApply(){
		if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $applymemo = Yii::$app->request->post('applymemo');
            $files = Yii::$app->request->post('files');
            $ordersid = Yii::$app->request->post('ordersid');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-termination-apply'), ['token'=>$token,'applymemo'=>$applymemo,'files'=>$files,'ordersid'=>$ordersid]);die;
        }
    }
	/**
	 *  同意终止
	 *
	 */
	public function actionOrdersTerminationAgree(){
		 if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $terminationid = Yii::$app->request->post('terminationid');
            $resultmemo = Yii::$app->request->post('resultmemo');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-termination-agree'), ['token'=>$token,'resultmemo'=>$resultmemo,'terminationid'=>$terminationid]);die;
        }
    }
	/**
	 *  否决终止
	 *
	 */
	public function actionOrdersTerminationVeto(){
		 if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $terminationid = Yii::$app->request->post('terminationid');
            $resultmemo = Yii::$app->request->post('resultmemo');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-termination-veto'), ['token'=>$token,'resultmemo'=>$resultmemo,'terminationid'=>$terminationid]);die;
        }
    }
	/**
	 *  进度添加
	 *
	 */
	public function actionOrdersProcessAdd(){
		 if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $ordersid = Yii::$app->request->post('ordersid');
            $memo = Yii::$app->request->post('memo');
            $type = Yii::$app->request->post('type');
            $files = Yii::$app->request->post('files');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-process-add'), ['token'=>$token,'ordersid'=>$ordersid,'files'=>$files,'type'=>$type,'memo'=>$memo]);die;
        }
    }
	/**
	 *  进度表单
	 *
	 */
	public function actionOrdersProcessForm(){
		$token = Yii::$app->session->get('user_token');
		$ordersid = Yii::$app->request->get('ordersid');
		$str =  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-process-actions'),['token'=>$token,'ordersid'=>$ordersid]);
		
		$data = $this->json2array($str);
		if($data["code"]=="0000"){
			$options = $data["result"]['actions'];
		}else{
			$options = [];
		}
		// var_dump($data);
		// exit;
		return $this->render('ordersprocessform',["options"=>$options]);
    }
	
	/**
	 *  结案申请表单
	 *
	 */
	public function actionOrdersClosedForm(){
		return $this->render('ordersclosedform');
    }
	
	/**
	 *  结案详情
	 *
	 */
	public function actionOrdersClosedDetail(){
		$token = Yii::$app->session->get('user_token');
		$closedid = Yii::$app->request->get('closedid');
		
		$str =  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-closed-detail'),['token'=>$token,'closedid'=>$closedid]);
		$data = $this->json2array($str);
		if($data["code"]=="0000"){
			return $this->render('orderscloseddetail',['data'=>$data["result"]['data'],'accessClosedAUTH'=>$data["result"]['accessClosedAUTH'],'userid'=>$data["userid"]]);
		}else{
			$this->notFound();
		}
    }
	/**
	 *  结案申请
	 *
	 */
	public function actionOrdersClosedApply(){
		if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $price = Yii::$app->request->post('price');
            $ordersid = Yii::$app->request->post('ordersid');
			$price2  = Yii::$app->request->post('price2');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-closed-apply'), ['token'=>$token,'price'=>$price,'ordersid'=>$ordersid,'price2'=>$price2]);die;
        }
    }
	/**
	 *  同意结案
	 *
	 */
	public function actionOrdersClosedAgree(){
		 if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $closedid = Yii::$app->request->post('closedid');
            $resultmemo = Yii::$app->request->post('resultmemo');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-closed-agree'), ['token'=>$token,'resultmemo'=>$resultmemo,'closedid'=>$closedid]);die;
        }
    }
	/**
	 *  否决结案
	 *
	 */
	public function actionOrdersClosedVeto(){
		 if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $closedid = Yii::$app->request->post('closedid');
            $resultmemo = Yii::$app->request->post('resultmemo');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-closed-veto'), ['token'=>$token,'resultmemo'=>$resultmemo,'closedid'=>$closedid]);die;
        }
    }
	
	/**
	 *  经办人
	 *
	 */
	public function actionOrdersOperatorList(){
		$token = Yii::$app->session->get('user_token');
		$ordersid = Yii::$app->request->get('ordersid');
		$str =  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-operator-list'),['token'=>$token,'ordersid'=>$ordersid]);
		$data = $this->json2array($str);
		if($data["code"]=="0000"){
			return $this->render('ordersoperatorlist',['data'=>$data["result"]['data'],'ordersid'=>$ordersid,'userid'=>$data["userid"]]);
		}else{
			$this->notFound();
		}
    }
	
	/**
	 *  分配经办人
	 *
	 */
	public function actionOrdersOperatorSet(){
		 if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $ordersid = Yii::$app->request->post('ordersid');
            $operatorIds = Yii::$app->request->post('operatorIds');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-operator-set'), ['token'=>$token,'operatorIds'=>$operatorIds,'ordersid'=>$ordersid]);die;
        }
    }
	/**
	 *  取消经办人
	 *
	 */
	public function actionOrdersOperatorUnset(){
		 if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $ordersid = Yii::$app->request->post('ordersid');
            $ids = Yii::$app->request->post('ids');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/orders-operator-unset'), ['token'=>$token,'ids'=>$ids,'ordersid'=>$ordersid]);die;
        }
    }
	/**
	 *  接单评价列表
	 *
	 */
	public function actionCommentList(){
		$ordersid = Yii::$app->request->get('ordersid');
		$token = Yii::$app->session->get('user_token');
		$comment = Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/comment-list'),['token'=>$token,'ordersid'=>$ordersid]);
		$data = $this->json2array($comment);
		if($data['code'] == '0000'){
			$data = $data['result']['data'];
			return $this->render('commentlist',['data'=>$data]);
		}else{
			$this->notFound();
		}
		
    }
	/**
	 *  接单评价表单
	 *
	 */
	public function actionCommentForm($ordersid,$type='1'){
		return $this->render('commentform',['type'=>$type,'ordersid'=>$ordersid]);
    }
	/**
	 *  评价产品
	 *
	 */
	public function actionCommentAdd(){
		 if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $ordersid = Yii::$app->request->post('ordersid');
			$truth_score = Yii::$app->request->post('truth_score');
			$assort_score = Yii::$app->request->post('assort_score');
			$response_score = Yii::$app->request->post('response_score');
            $memo = Yii::$app->request->post('memo');
            $files = Yii::$app->request->post('files');
			
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/comment-add'), 
			[
				'token'=>$token, 
				'ordersid'=>$ordersid,
				'memo'=>$memo,
				'files'=>$files,
				'truth_score'=>$truth_score,
				'assort_score'=>$assort_score,
				'response_score'=>$response_score,
				
			]
			);
		}
    }
	/**
	 *  追加评价
	 *
	 */
	public function actionCommentAdditional(){
		 if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $ordersid = Yii::$app->request->post('ordersid');
			$truth_score = Yii::$app->request->post('truth_score');
			$assort_score = Yii::$app->request->post('assort_score');
			$response_score = Yii::$app->request->post('response_score');
            $memo = Yii::$app->request->post('memo');
            $files = Yii::$app->request->post('files');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/comment-additional'), 
			[
				'token'=>$token, 
				'ordersid'=>$ordersid,
				'memo'=>$memo,
				'files'=>$files,
				/*'truth_score'=>$truth_score,
				'assort_score'=>$assort_score,
				'response_score'=>$response_score,*/
			]
			);
			die;
        }
    }
	
	/**
	* 删除接单
	*/
	public function actionApplyDel(){
		if(Yii::$app->request->isAjax){
			$applyid = Yii::$app->request->post('applyid');
			$token = Yii::$app->session->get('user_token');
			echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/productorders/apply-del'),['token'=>$token,'applyid'=>$applyid]);exit;
		}
	}
}