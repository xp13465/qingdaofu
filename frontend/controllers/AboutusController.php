<?php
namespace frontend\controllers;

use common\models\DisposingProcess;
use common\models\FinanceProduct;
use Yii;
use common\models\LoginForm;
use common\models\CreditorProduct;
use common\models\Apply;
use common\models\Certification;
use frontend\services\Func;
use app\models\user;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use frontend\components\FrontController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers;
use yii\helpers\BaseJson;
use yii\db\ActiveRecord;
use yii\data\Pagination;

class AboutusController extends FrontController{
     public $layout = 'aboutusus';
    //公司简介
    public function actionIntro(){
		$url = Yii::$app->request->url;
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => "url",
            'value'=>$url
        ]));
        $this->title = "关于我们-公司简介";
        $this->keywords = "关于我们-公司简介";
        $this->description = "关于我们-公司简介";
        return $this->render('/aboutus/intro');
    }

    //服务条款
    public function actionServiceclause(){
		$url = Yii::$app->request->url;
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => "url",
            'value'=>$url
        ]));
        $this->title = "关于我们-服务协议";
        $this->keywords = "关于我们-服务协议";
        $this->description = "关于我们-服务协议";
        return $this->render('/aboutus/serviceclause');
    }

    //帮助中心
    public function actionHelpcenter(){
		$url = Yii::$app->request->url;
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => "url",
            'value'=>$url
        ]));
        $this->title = "关于我们-帮助中心";
        $this->keywords = "关于我们-帮助中心";
        $this->description = "关于我们-帮助中心";
        return $this->render('/aboutus/helpcenter');
    }

    //帮助中心分页
    public function actionHelpcenters(){
		$url = Yii::$app->request->url;
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => "url",
            'value'=>$url
        ]));
        $this->title = "关于我们-帮助中心";
        return $this->render('/aboutus/helpcenters');
    }
    //帮助中心分页
    public function actionCenter(){
		$url = Yii::$app->request->url;
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => "url",
            'value'=>$url
        ]));
        $this->title = "关于我们";
        return $this->render('/aboutus/center');
    }

    //意见反馈
    public function actionFeedback(){
		$url = Yii::$app->request->url;
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => "url",
            'value'=>$url
        ]));
        $this->title = '关于我们-意见反馈';
        $this->keywords = '关于我们-意见反馈';
        $this->description = '关于我们-意见反馈';
        if(\Yii::$app->user->getId()){
            $user = Yii::$app->request;
            if($user->post()){
                $model = new \common\models\Feedback();
                $model->opinion = $user->post('opinion');
                $model->phone = $user->post('phone');
                $model->uid = \Yii::$app->user->getId();
                if($model->save()){
                    header("Content-type:text/html;charset=utf-8");
                    exit("<script>alert('提交成功');location.href='/aboutus/feedback';</script>");
                }
            }
        }else{
            $user = Yii::$app->request;
            if($user->post()){
                $model = new \common\models\Feedback();
                $model->opinion = $user->post('opinion');
                $model->phone = $user->post('phone');
                if($model->save()){
                    header("Content-type:text/html;charset=utf-8");
                    exit("<script>alert('提交成功');location.href='/aboutus/feedback';</script>");
                }
            }
        }
        return $this->render('/aboutus/feedback');
    }

    //商务合作
    public function actionCooperation(){
		$url = Yii::$app->request->url;
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => "url",
            'value'=>$url
        ]));
        $this->title = '关于我们-商务合作';
        $this->keywords = '关于我们-商务合作';
        $this->description = '关于我们-商务合作';
        return $this->render('/aboutus/cooperation');
    }

    //联系我们
    public function actionContactus(){
		$url = Yii::$app->request->url;
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => "url",
            'value'=>$url
        ]));
        $this->title = '关于我们-联系我们';
        $this->keywords = '关于我们-联系我们';
        $this->description = '关于我们-联系我们';
        return $this->render('/aboutus/contactus');
    }

    public function actionDownload($type){
        $DOCROOT = $_SERVER['DOCUMENT_ROOT'];
        switch($type){
            case 1:
                return \Yii::$app->response->sendFile($DOCROOT.'/pdf/1.pdf','房屋租赁合同.pdf');break;
            case 2:
                return \Yii::$app->response->sendFile($DOCROOT.'/pdf/2.pdf','借条.pdf');break;
            case 3:
                return \Yii::$app->response->sendFile($DOCROOT.'/pdf/3.pdf','授权签字协议.pdf');break;
            case 4:
                return \Yii::$app->response->sendFile($DOCROOT.'/pdf/4.pdf','委托清收协议.pdf');break;
            case 5:
                return \Yii::$app->response->sendFile($DOCROOT.'/pdf/5.pdf','债务催讨授权委托书.pdf');break;
            default:return \Yii::$app->response->sendFile($DOCROOT.'/pdf/1.pdf','房屋租赁合同.pdf');break;
        }
    }
}