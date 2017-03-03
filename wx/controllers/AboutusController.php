<?php
namespace wx\controllers;
use wx\services\Func;
use yii;

class AboutusController extends \wx\components\FrontController{
    public $enableCsrfValidation = false;

    public function actionIntro(){
        $this->title = "清道夫债管家-关于清道夫";
        $this->keywords = "清道夫债管家-关于清道夫";
        $this->description = "清道夫债管家-关于清道夫";
        return $this->render('intro');
    }

    //常见问答
    public function actionAskanswer(){
        $this->title = "清道夫债管家-常见问答";
        $this->keywords = "清道夫债管家-常见问答";
        $this->description = "清道夫债管家-常见问答";
        return $this->render('askanswer');
    }

    //联系我们
    public function actionContactus(){
        $this->title = "清道夫债管家-联系我们";
        $this->keywords = "清道夫债管家-联系我们";
        $this->description = "清道夫债管家-联系我们";
        return $this->render('contactus');
    }

    //意见反馈
    public function actionOpinion(){
        $this->title = "清道夫债管家-意见反馈";
        $this->keywords = "清道夫债管家-意见反馈";
        $this->description = "清道夫债管家-意见反馈";
        return $this->render('opinion');
    }

    public function actionOpiniondata(){
        $token   = Yii::$app->session->get('user_token');
        if(Yii::$app->request->isAjax) {
            $phone = Yii::$app->request->post('mobile');
            $opinion = Yii::$app->request->post('casedesc');
            $picture  = Yii::$app->request->post('picture');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/opinion'),['phone'=>$phone,'opinion'=>$opinion,'token'=>$token,'picture'=>$picture]);die;
        }
    }
}
