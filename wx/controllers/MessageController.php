<?php

namespace wx\controllers;
use wx\services\Func;
use yii;
use yii\helpers\Url;

class MessageController extends \wx\components\FrontController
{
    public $enableCsrfValidation = false;
	public $layout = 'newmain';
    public function actionIndex()
    {
		Url::remember(Url::current(), 'messagePrev');  
        $this->title = "清道夫债管家-消息";
        $this->keywords = "清道夫债管家-消息";
        $this->description = "清道夫债管家-消息";
		$page = Yii::$app->request->get('page',0);
		$limit = Yii::$app->request->get('limit',10);
		$json = Func::CurlPost(yii\helpers\Url::toRoute(['/wap/message/group-list']),['token'=>Yii::$app->session->get('user_token'),"page"=>$page,"limit"=>$limit]);
		$json_arr = $this->json2array($json);
        if($json_arr['code'] == '0000'){
			$params = array_merge($json_arr['result'],["userid"=>$json_arr['userid']]);
			// var_dump($params);exit;
			return $this->render('index',$params);
        }else{
            $this->notFound();
        }

        
    }
	
	public function actionSystem()
    {
        $this->title = "清道夫债管家-消息";
        $this->keywords = "清道夫债管家-消息";
        $this->description = "清道夫债管家-消息";
		$page = Yii::$app->request->get('page',0);
		$limit = Yii::$app->request->get('limit',10);
        $json = Func::CurlPost(yii\helpers\Url::toRoute(['/wap/message/system-list']),['token'=>Yii::$app->session->get('user_token'),"page"=>$page,"limit"=>$limit]);
		$json_arr = $this->json2array($json);
        if($json_arr['code'] == '0000'){
			$params = array_merge($json_arr['result'],["userid"=>$json_arr['userid']]);
			// var_dump($params);exit;
			return $this->render('system',$params);
        }else{
            $this->notFound();
        }

    }

    public function actionCategorylist(){
        $type = Yii::$app->request->get('type',4);
        switch ($type){
            case 1:
                $this->title = "清道夫债管家-发布消息";
                $this->keywords = "清道夫债管家-发布消息";
                $this->description = "清道夫债管家-发布消息";
                break;
            case 2:
                $this->title = "清道夫债管家-接单消息";
                $this->keywords = "清道夫债管家-接单消息";
                $this->description = "清道夫债管家-接单消息";
                break;
            case 3:
                $this->title = "清道夫债管家-评价消息";
                $this->keywords = "清道夫债管家-评价消息";
                $this->description = "清道夫债管家-评价消息";
                break;
            case 4:
                $this->title = "清道夫债管家-系统消息";
                $this->keywords = "清道夫债管家-系统消息";
                $this->description = "清道夫债管家-系统消息";
                break;
            default:
                break;
        }
        $page = Yii::$app->request->get('page',0);
        $limit = Yii::$app->request->get('limit',10);
        $json = Func::CurlPost(yii\helpers\Url::toRoute(['/wap/message/index']),['token'=>Yii::$app->session->get('user_token'),'type'=>$type,'limit'=>$limit,'page'=>$page]);
        $json_arr = yii\helpers\Json::decode($json);

        if($json_arr['code'] == '0000'){
            $res = $json_arr['result'];
        }else{
            throw new yii\web\NotFoundHttpException();
        }
        return $this->render('categorylist',['result'=>$res]);
    }

    //阅读消息
    public function actionRead(){
        $token = Yii::$app->session->get('user_token');
        if(Yii::$app->request->isAjax){
            $messageId = Yii::$app->request->post('messageId');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/message/set-read'),['token'=>$token,'messageId'=>$messageId]);die;
        }
    }
}
