<?php

namespace frontend\modules\admin\controllers;

use frontend\modules\admin\services\Func;
use yii;
use yii\web\Controller;

class WxController extends Controller
{
    public $layout = false;
    public $enableCsrfValidation = false;

    public function actionSendmsg(){
        if(Yii::$app->request->post()){

            $mobile = trim(Yii::$app->request->post('mobile'));
            $message = Yii::$app->request->post('msg');

            if(!Func::isMobile($mobile)){
                echo json_encode(['status'=>'2','msg'=>'手机号码不合法']);die;
            }
            if(Yii::$app->smser->sendMsgByMobile($mobile,$message)){
                echo json_encode(['status'=>'0','msg'=>'发送短信成功']);die;
            }else{
                echo json_encode(['status'=>'1','msg'=>'发送短信失败']);die;
            }
        }

        exit('you are not allow to access this.');
    }

    public function actionGetsmscode()
    {
        if(Yii::$app->request->get()){

            $callBack = Yii::$app->request->get('callback');
            $mobile = trim(Yii::$app->request->get('mobile'));

            if(!Func::isMobile($mobile)){
                echo Func::Callback($callBack,json_encode(['status'=>'2','msg'=>'手机号码不合法']));die;
            }
            if(Yii::$app->smser->sendValidateCode($mobile)){
                echo Func::Callback($callBack,json_encode(['status'=>'0','msg'=>'发送手机验证码成功']));die;
            }else{
                echo Func::Callback($callBack,json_encode(['status'=>'1','msg'=>'发送手机验证码失败']));die;
            }
        }

        exit('you are not allow to access this.');
    }

    public function actionRegister(){
        if(Yii::$app->request->get()){
            $mobile = Yii::$app->request->get('mobile');
            $callBack = Yii::$app->request->get('callback');
            $validateCode = Yii::$app->request->get('validateCode');
            $password = Yii::$app->request->get('password');
            if(!Func::isMobile($mobile)){
                echo Func::Callback($callBack,json_encode(['status'=>'2','msg'=>'手机号码不合法']));die;
            }
            if(\frontend\modules\admin\services\Func::registerUser($mobile,$validateCode,$password)){
                echo Func::Callback($callBack,json_encode(['status'=>'0','msg'=>'注册账户成功']));die;
            }else{
                echo Func::Callback($callBack,json_encode(['status'=>'1','msg'=>'注册账户失败']));die;
            }
        }

        exit('you are not allow to access this.');
    }


    public function actionReglogin(){
        if(Yii::$app->request->get()){
            $mobile = Yii::$app->request->get('mobile');
            $callBack = Yii::$app->request->get('callback');
            if(!Func::isMobile($mobile)){
                echo Func::Callback($callBack,json_encode(['status'=>'2','msg'=>'手机号码不合法']));die;
            }
            $model = new \common\models\LoginForm();
            if ($model->load(Yii::$app->request->get(), '') && $model->login()) {
                $user = \common\models\User::findByUsername($mobile);
                $user->token = md5(time().$user->id);
                $user->save();
                echo Func::Callback($callBack,json_encode(['status'=>'0','token'=>$user->token,'msg'=>'登录成功']));die;
            }else{
                echo Func::Callback($callBack,json_encode(['status'=>'1','msg'=>'登录失败']));die;
            }
        }

        exit('you are not allow to access this.');
    }
}
