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
    //直向简介
    public function actionIntro(){
        $this->title = "清道夫债管家";
        return $this->render('/aboutus/intro');
    }

    //服务条款
    public function actionServiceclause(){
        $this->title = "清道夫债管家";
        return $this->render('/aboutus/serviceclause');
    }

    //帮助中心
    public function actionHelpcenter(){
        $this->title = "清道夫债管家";
        return $this->render('/aboutus/helpcenter');
    }

    //意见反馈
    public function actionFeedback(){
        $this->title = '清道夫债管家';
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
        $this->title = '清道夫债管家';
        return $this->render('/aboutus/cooperation');
    }

    //联系我们
    public function actionContactus(){
        $this->title = '清道夫债管家';
        return $this->render('/aboutus/contactus');
    }
}