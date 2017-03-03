<?php

namespace frontend\modules\wap\controllers;

use common\models\User;
use frontend\modules\wap\components\WapController;
use yii;
use frontend\modules\wap\services\Func;
use yii\web\Controller;
use yii\helpers\Json;

/**
 * 用户操作控制器
 */
class AddressController extends WapController
{
    public $layout = false;
    public $enableCsrfValidation = false;
    public $modelClass = 'frontend\models\ShoppingAddress';
	
	/**
	*  收货地址列表
	*  
	*/
	
	public function actionIndex()
    { 
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
		$page = Yii::$app->request->post('page',1);
		$limit = Yii::$app->request->post('limit',10);
        $model = new $this->modelClass;
		$data = $model::find()->asArray()
			->joinWith(["provincename","cityname","areaname"])
			->orderby("isdefault desc ,modifytime desc")
			->where(['validflag'=>'1','uid'=>$uid])
			->offset(($page-1)*$limit)
            ->limit($limit) 
			->all(); 
		$this->success("",['data'=>$data]);  
		 
    }
	
	/**
	*  新增收货地址
	*  
	*/
	
	public function actionCreate()
    {
        $model = new $this->modelClass;
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
		$post = Yii::$app->request->post();
		$params = ["province"=>"","city"=>"","area"=>"","nickname"=>"","tel"=>"","address"=>"","isdefault"=>""];
		foreach($params as $k=>$v)$params[$k]=isset($post[$k])?$post[$k]:$params[$k];
        $return = $model->change($params);
		switch($return){
			case "ModelDataCheck":
				$this->errorMsg("ModelDataCheck","失败！".$model->formatErrors(),false,'json',['errors'=>$model->errors]);
				break;
			case "ModelDataSave":
				$this->errorMsg("ModelDataSave","失败！");
				break;
			case "ok":
				$this->success("成功！",["id"=>$model->id]); 
				break;
		}
    }
	
	
	/**
	*  编辑收货地址
	*  
	*/
	public function actionUpdate()
    {
        $model = new $this->modelClass;
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
		$id = Yii::$app->request->post('id');
		// $id = 1;
		$model = $model::find()->where(['id'=>$id,'validflag'=>1,'uid'=>$uid])->one();
		if(!$model)$this->errorMsg("ParamsCheck");
		$post = Yii::$app->request->post();
		$params = ["province"=>"","city"=>"","area"=>"","nickname"=>"","tel"=>"","address"=>"","isdefault"=>""];
		foreach($params as $k=>$v){
			if(isset($post[$k])&&$post[$k]){
				$params[$k] = $post[$k];
			}else{
				unset($params[$k]);
			}
		}
        $return = $model->change($params);
		switch($return){
			case "ModelDataCheck":
				$this->errorMsg("ModelDataCheck","失败！".$model->formatErrors(),false,'json',['errors'=>$model->errors]);
				break;
			case "ModelDataSave":
				$this->errorMsg("ModelDataSave","失败！");
				break;
			case "ok":
				$this->success("成功！"); 
				break;
		}
    }
	/**
	*  收货地址详情
	*  
	*/
	
	public function actionInfo()
    {	
		$id = Yii::$app->request->post('id');
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
		if(!$id)$this->errorMsg("ParamsCheck",'');  
        $model = new $this->modelClass;
		$data = $model::find()->joinWith(["provincename","cityname","areaname"])->alias("address")->where(['address.id'=>$id,'address.validflag'=>1,'uid'=>$uid])->one();
		if($data){
			$this->success("",['data'=>$data]); 
		}else{
			$this->errorMsg("ParamsCheck",'');  
		}
    }
	
	/**
	*  回收/恢复收货地址
	*  
	*/
	public function actionRecy()
    {
        $model = new $this->modelClass;
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
		$id = Yii::$app->request->post('id');
		$type = Yii::$app->request->post('type',1);
		// $id = 1;
		$model = $model::find()->where(['id'=>$id,'validflag'=>($type==2?"0":"1"),'uid'=>$uid])->one();
		if(!$model)$this->errorMsg("ParamsCheck");
		
        $return = $model->recy($type);
		switch($return){
			case "0":
				$this->errorMsg("PageTimeOut",'请求超时！');
				break;
			case "1":
				$this->success("成功！"); 
				break;
		}
    }
	
	
	/**
	*  设置默认地址
	*  
	*/
	public function actionDefault()
    {
        $model = new $this->modelClass;
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
		$id = Yii::$app->request->post('id'); 
		$model = $model::find()->where(['id'=>$id,'validflag'=>"1"])->one();
		if(!$model)$this->errorMsg("ParamsCheck");
		
        $return = $model->setDefault();
		switch($return){
			case "0":
				$this->errorMsg("PageTimeOut",'请求超时！');
				break;
			case "1":
				$this->success("成功！"); 
				break;
			case "2":
				$this->success("已是默认！"); 
				break;
		}
    }
	
	/**
	*  获取默认地址
	*  
	*/
	
	public function actionGetdefault()
    { 
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId(); 
        $model = new $this->modelClass;
		$data = $model::find()->asArray()
			->orderby("isdefault desc ,modifytime desc")
			->where(['validflag'=>'1','uid'=>$uid])
			->one();
		$this->success("",['data'=>$data]);  
		 
    }
    
}
