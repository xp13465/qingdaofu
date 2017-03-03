<?php

namespace frontend\modules\wap\components;

use common\models\User;
use yii;
use yii\web\Controller;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

/**
 * 用户操作控制器
 */
class WapNoLoginController extends Controller
{
    public $layout = false;
    public $enableCsrfValidation = false;
    public $user = null;
    public function init(){
        parent::init();

        if(!YII_ENV_TEST&&!Yii::$app->request->isPost){
            exit("You are not allow this interface.");
        }else{
			$this->loadUser(false);
        }
    }
	
	/**
	*  错误信息返回
	*	param $label String 错误信息代码
	*	param $type String 返回类型 可拓展
	*	return $Msg JsonString 默认为JSON格式
	*/
	protected function errorMsg($label,$msg='', $return = false,$type='json',$data=[]){
		
		$errorMsg = Yii::$app->params['errorMsg'];
		$jsonData = isset($errorMsg[$label])?$errorMsg[$label]:[];
		$jsonData['userid'] = Yii::$app->user->getId();
		if($msg)$jsonData['msg'] = $msg;
		if($data)$jsonData['data'] = $data;
		
		switch($type){
			case 'json':
				$Msg = \yii\helpers\Json::encode($jsonData);
				break;
			default:
				$Msg = \yii\helpers\Json::encode($jsonData);
				break;
		}
		if($return){
			return $Msg;
		}else{
			exit($Msg);
		}
	}
	/**
	*   正确信息返回
	*	param $msg String 提示文字
	*	param $data Array 返回数据
	*	param $type String  返回类型
	*	return $Msg JsonString 默认为JSON格式
	*/
	protected function success($msg='',$data=array(),$type='json'){
		$return = ['code'=>'0000','msg'=>$msg,'result'=>$data];
		$return['userid']=Yii::$app->user->getId();
		switch($type){
			case 'json':
				$Msg = \yii\helpers\Json::encode($return);
				break;
			default:
				$Msg = \yii\helpers\Json::encode($return);
				break;
		}
		exit($Msg);
	}
	/**
	*   加载用户
	*
	*/
	
	protected function loadUser($doExit = true){
		$token = Yii::$app->request->post('token');
		if($token){
            $user = User::findOne(['token'=>$token]);
            if(!isset($user->id)||!$user->id){
				Yii::$app->user->logout();
                if($doExit)$this->errorMsg("UserLogin");
            }else{
                $this->user = $user;
				if(!Yii::$app->user->getId()||Yii::$app->user->getId()!=$user->id){
					$model = new \common\models\LoginForm();
					$model->mobile = $this->user->mobile;
					$status = $model->login(false);
				}
                Yii::$app->session->set("user_id",$user->id);
				return $user;
            }
        }else{
			// Yii::$app->user->logout();
			if($doExit)$this->errorMsg("UserLogin");
        }
	}
}
