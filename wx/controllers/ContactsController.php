<?php
namespace wx\controllers;
use wx\services\Func;
use yii;
class ContactsController extends \wx\components\FrontController{
    public $enableCsrfValidation = false;
	public $layout = 'newmain';
    //用户中心
    public function actionIndex($type=1,$ordersid =''){
        $this->title = "清道夫债管家-用户中心";
        $token = Yii::$app->session->get('user_token');
		$page = Yii::$app->request->get('page');
		$str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/contacts/index'),['token'=>$token,"ordersid"=>$ordersid,"limit"=>10,"page"=>$page]);
        $arr    = $this->json2array($str);
		
        if($arr['code'] == '0000'){
			// echo "<pre>";
			// print_r($arr['result']);
			return $this->render('index',array_merge($arr['result'],["type"=>$type,"ordersid"=>$ordersid]));
        }else{
           $this->notFound();
        }
    }
	 //搜索用户
    public function actionSearch(){
        $this->title = "清道夫债管家-用户中心";
        $mobile = Yii::$app->request->post('mobile');
        $token = Yii::$app->session->get('user_token');
		echo $str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/contacts/search'),['token'=>$token,'mobile'=>$mobile]);
    }
	//添加联系人
    public function actionApply(){
        $this->title = "清道夫债管家-用户中心";
        $userid = Yii::$app->request->post('userid');
        $token = Yii::$app->session->get('user_token');
		echo $str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/contacts/apply'),['token'=>$token,'userid'=>$userid]);
    }
	//删除联系人
    public function actionDel(){
        $this->title = "清道夫债管家-用户中心";
        $contactsid = Yii::$app->request->post('contactsid');
        $token = Yii::$app->session->get('user_token');
		echo $str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/contacts/del'),['token'=>$token,'contactsid'=>$contactsid]);
    }
	 
	
	
}