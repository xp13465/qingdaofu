<?php
namespace frontend\components;

use Yii;
use yii\helpers\StringHelper;
use yii\web\NotFoundHttpException;


class FrontController extends \yii\web\Controller
{
    public $title = '';
    public $keywords = '';
    public $description = '';

    public $modelClass;

    public function init(){
		
    }

    protected function findModel($id)
    {
        $modelClass = $this->modelClass;

        $model = $modelClass::findOne($id);

        if ($model !== null) {
                return $model;
        } else {
                throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
    }


    protected function renderIsAjax($view, $params = [])
    {
        if (Yii::$app->request->isAjax) {
                return $this->renderAjax($view, $params);
        } else {
                return $this->render($view, $params);
        }
    }

    protected function getRedirectPage($action, $model = null)
    {
        switch ($action) {
                case 'delete':
                        return ['index'];
                        break;
                case 'update':
                        return ['view', 'id' => $model->id];
                        break;
                case 'create':
                        return ['view', 'id' => $model->id];
                        break;
                default:
                        return ['index'];
        }
    }
	
	/**
	*  错误信息返回
	*	param $label String 错误信息代码
	*	param $type String 返回类型 可拓展
	*	return $Msg JsonString 默认为JSON格式
	*/
	protected function errorMsg($label,$msg='',$type='json'){
		$errorMsg = Yii::$app->params['errorMsg'];
		$jsonData = isset($errorMsg[$label])?$errorMsg[$label]:[];
		if($msg)$jsonData['msg'] = $msg;
		switch($type){
			case 'view': 
				throw new NotFoundHttpException($jsonData['msg']);
				exit;
			break;
			case 'json':
				$Msg = \yii\helpers\Json::encode($jsonData);
				break;
			default:
				$Msg = \yii\helpers\Json::encode($jsonData);
				break;
		}
		exit($Msg);
	}
	
	/**
	*   正确信息返回
	*	param $msg String 提示文字
	*	param $data Array 返回数据
	*	param $type String  返回类型
	*	return $Msg JsonString 默认为JSON格式
	*/
	protected function success($msg='',$data=array(),$type='json'){
		$return = ['code'=>'0000','msg'=>$msg,'data'=>$data];
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
	*   正确信息返回
	*	param $msg String 提示文字
	*	param $data Array 返回数据
	*	param $type String  返回类型
	*	return $Msg JsonString 默认为JSON格式
	*/
	protected function flash($code='',$data=''){
		Yii::$app->session->setFlash($code, $data);
	}
}
