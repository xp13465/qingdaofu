<?php

namespace frontend\modules\wap\controllers;

use common\models\User;
use frontend\modules\wap\components\WapNoLoginController;
use yii;
use frontend\modules\wap\services\Func;
use yii\web\Controller;
use yii\helpers\Json;

/**
 * 用户操作控制器
 */
class UserController extends WapNoLoginController
{
    public $layout = false;
    public $enableCsrfValidation = false;

    public function init()
    {
        parent::init();
        if (!Yii::$app->request->isPost) {
            exit("You are not allow this interface.");
        }
    }

    /**
     * 获取手机注册验证码
     * @param mobile string
	 * $params type 验证码类型 2
     * @return json
     * */
    public function actionSmscode()
    {
        $mobile = Yii::$app->request->post('mobile');
		$type=Yii::$app->request->post('type',1);
        if (!Func::isMobile($mobile)) {
            echo Json::encode(['code' => '1001', 'msg' => '手机号码不合法']);
            die;
        }
        if (Yii::$app->smser->sendValidateCode($mobile,$type)) {
            echo Json::encode(['code' => '0000', 'msg' => '发送手机验证码成功']);
            die;
        } else {
            echo Json::encode(['code' => '4001', 'msg' => '发送手机验证码失败']);
            die;
        }
    }
	
	/**
	*  设置密码
	*
	*/
	public function actionChecksmscode(){
		$userid = Yii::$app->user->getId();
		$type = Yii::$app->request->post("type",1); 
		$mobile = Yii::$app->request->post("mobile");
		$code = Yii::$app->request->post("code");
		if(!$mobile||!$code)$this->errorMsg(PARAMSCHECK);
		$sms = new \common\models\Sms();
		if($sms->isVildateCode($code,$mobile,$type,false)){
			$this->success("验证码正确");
		}else{
			$this->errorMsg(MODELDATACHECK,"验证码错误");
		}
	}

    /**
     * 注册方法
     * @param string mobile
     * @param string passoword
     * @param string validatecode
     * @return json
     * **/
    public function actionRegistery()
    {
        $mobile = Yii::$app->request->post('mobile');
        $password = Yii::$app->request->post('password');
        $validatecode = Yii::$app->request->post('validatecode');

        if (!Func::isMobile($mobile)) {
            echo Json::encode(['code' => '1001', 'msg' => '手机号码不合法']);
            die;
        }
        $user  = User::findOne(['mobile'=>$mobile]);

        if(isset($user)&& $user->id){
            echo Json::encode(['code' => '3001', 'msg' => '该手机号已存在']);die;
        }

        if (trim($password) == '' || strlen($password) < 6) {
            echo Json::encode(['code' => '1002', 'msg' => '密码不小于六位']);
            die;
        }
        if (trim($validatecode) == '') {
            echo Json::encode(['code' => '1003', 'msg' => '验证码不可为空']);
            die;
        }

        if (\frontend\modules\wap\services\Func::registerUser($mobile, $validatecode, $password)) {
            echo Json::encode(['code' => '0000', 'msg' => '注册账户成功']);
            die;
        } else {
            echo Json::encode(['code' => '2001', 'msg' => '您的帐号已存在']);
            die;
        }
    }

    /**
     * 登陆系统获取TOKEN
     * @param string mobile
     * @param string passoword
     * @return json
     **/
    public function actionLogin()
    {
        $mobile = Yii::$app->request->post('mobile');
        $client = Yii::$app->request->post('client','app');
        if (!Func::isMobile($mobile) || $mobile == '') {
            echo Json::encode(['code' => '1001', 'msg' => '请输入正确的手机号']);
            die;
        }
        $model = new \common\models\LoginForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            $user = User::findByUsername($mobile);
			// if($client=='app'){
				// if(!$user->apptoken){
					// $user->apptoken = md5(time() . $user->id);
					// $user->save(false);
				// }
			// }
			
			if(!$user->token){
				$user->token = md5(time() . $user->id);
				$user->save(false);
			}
			if($client=='wx'){
				\common\models\Openid::updateAll(["autologin"=>"1"],["uid"=>$user->id]);
			}
			
            
			
            echo Json::encode(['code' => '0000', 'token' => $user->token, 'msg' => '登录成功']);
            die;
        } else {
            echo Json::encode(['code' => '2001', 'msg' => $model->errors['password'][0]]);
            die;
        }
    }

    public function actionLogout()
    {
        $token = Yii::$app->request->post('token');
        $client = Yii::$app->request->post('client','app');
        $user = User::findOne(['token' => $token]);
		
        if ($token && isset($user->id) && $user->id) {
			// if($client=="app"){
				// $user->apptoken = '';
				// $user->save(false);
			// }else{
			if($client=='wx'){
				\common\models\Openid::updateAll(["autologin"=>"0"],["uid"=>$user->id]);
			}
			$user->token = '';
			$user->save(false);
			// }
            Yii::$app->user->logout();
            Yii::$app->session->destroySession("user_id");
            echo Json::encode(['code' => '0000', 'msg' => '登出成功']);
        } else {
            echo Json::encode(['code' => '3001', 'msg' => '你无此权限']);
        }
    }

    /**
     * 修改密码
     * @param string old_password
     * @param string new_password
     * @return json
     **/
    public function actionModifypassword()
    {
        $token = Yii::$app->request->post('token');
        $old_password = Yii::$app->request->post('old_password');
        $new_password = Yii::$app->request->post('new_password');
        $user = User::findOne(['token' => $token]);

        if(trim($old_password) == ''){
			$this->errorMsg(PARAMSCHECK,"旧密码不能为空");
            // echo Json::encode(['code' => '1001', 'msg' => '旧密码不能为空']);die;
        }
        if(trim($new_password) == ''){
			$this->errorMsg(PARAMSCHECK,"新密码不能为空");
            // echo Json::encode(['code' => '1002', 'msg' => '新密码不能为空']);die;
        }
        if (isset($user->id) && $user->id) {
           if(Yii::$app->security->validatePassword($old_password, $user->password_hash)){
               $user->password_hash = Yii::$app->security->generatePasswordHash($new_password);
               $user->save(false);
			   $this->success('修改密码成功');
               // echo Json::encode(['code' => '0000', 'msg' => '修改密码成功']);die;
           }else{
				$this->errorMsg(MODELDATACHECK,"旧密码不正确");
				// echo Json::encode(['code' => '4001', 'msg' => '旧密码不正确']);die;
           }
        } else {
			$this->errorMsg(USERAUTH,"你无此权限");
            // echo Json::encode(['code' => '3001', 'msg' => '你无此权限']);die;
        }
    }

    /**
     * 重置密码
     * @param string mobile
     * @param string validatecode
     * @param string new_password
     * @return json
     **/
    public function actionResetpassword(){
        $mobile       = Yii::$app->request->post('mobile');
        $validatecode = Yii::$app->request->post('validatecode');
        $new_password = Yii::$app->request->post('new_password');
		if(!$mobile)$this->errorMsg(PARAMSCHECK,"请输入手机号！");
		if(!Func::isMobile($mobile)){
			$this->errorMsg(PARAMSCHECK,"手机号码格式错误");
        }
        $user  = \common\models\User::findByUsername($mobile);
        $sms = new \common\models\Sms();
        if(isset($user->id) && $user->id){
            if(!$sms->isVildateCode($validatecode,$mobile)){
                echo Json::encode(['code' => '1002', 'msg' => '验证码错误']);die;
            }
            $user->password_hash = Yii::$app->security->generatePasswordHash($new_password);
            $user->save();
            echo Json::encode(['code' => '0000', 'msg' => '重置密码成功']);die;
        }else {
            echo Json::encode(['code' => '3001', 'msg' => '手机号不存在']);die;
        }
    }

    public function actionIslogin(){
        if($token = Yii::$app->request->post('token')){
            $user = User::findOne(['token'=>$token]);
            if(!isset($user->id)||!$user->id){
                echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
            }else{
                echo Json::encode(['code'=>'0000','msg'=>'您已登录']);die;
            }
        }

        echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
    }
}
