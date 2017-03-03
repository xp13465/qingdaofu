<?php
namespace frontend\controllers;

use Yii;
use frontend\components\FrontController;
use app\models\Certification;
use frontend\services\Func;
/**
 * Site controller
 */
class CertificationsController extends FrontController
{
	public function init(){
		 
		if(Yii::$app->user->isGuest){
			$this->redirect('/site/login/')->send();
			exit;
		}
	}
    public $layout = 'info';

    public $enableCsrfValidation = false;
	
	public function actionAuthentication(){
		$model = new Certification();
		$certification = $model->getCertification();
		if($certification&&in_array($certification->state,['0','1'])){
			return $this->redirect('index');
		}
        return $this->render('authentication');
    }
	
	public function actionEdit(){
		$category = Yii::$app->request->get('category');
		$uid = Yii::$app->request->get('uid');
		switch($category){
			case 1:
			return $this->redirect(['personal','uid'=>$uid]);
			break;
			case 2:
			return $this->redirect(['lawyer','uid'=>$uid]);
			break;
			case 3:
			return $this->redirect(['orgnization','uid'=>$uid]);
			break;
		}
	}
	
	//用户信息
	public function actionIndex(){
		$model = new Certification();
		$certification = $model->getCertification();
		$data = Func::fillterCertification($certification);
		if($certification){
			return $this->render('index',['data'=>$data]);
		}else{
			return $this->redirect('authentication');
		}
		
	}
	
    //用户认证
	public function actionAdd(){
		$post = Yii::$app->request->post('Certification');
		$post['cardimg'] = Yii::$app->request->post('cardimg1');
		$model= new Certification();
		if($post){
			$status = $model->change($post,true);
			if($status == 'ok'){
				Yii::$app->smser->sendMsgByMobile(13918500509,'用户['.$model->mobile.']申请个人认证，请您尽快审核');
				$this->success("认证申请已发出，请耐心等待客服审核。",['id'=>$model->id]);die;
			}else{
				$this->errorMsg($status);die;
			}
			
		}
	}
	
	//个人认证
	public function actionPersonal(){
		$uid = Yii::$app->request->get('uid');
		if($uid){
			$model= Certification::findOne(['uid'=>$uid]);
			if($model){
				$data = $model->getCardimg();
			}else{
				return $this->redirect('authentication');
			}
			
		}else{
			$model = new Certification();
		}
		$certification = $model->getCertification();
		if($certification&&in_array($certification->state,['0','1'])){
			return $this->redirect('index');
		}
		return $this->render('personal',["model"=>$model,'data'=>isset($data)&&$data?$data:'']);
	}
	
	//律所认证
	public function actionLawyer(){
		$uid = Yii::$app->request->get('uid');
		if($uid){
			$model= Certification::findOne(['uid'=>$uid]);
			if($model){
				$data = $model->getCardimg();
			}else{
				return $this->redirect('authentication');
			}
		}else{
			$model = new Certification();
		}
		$certification = $model->getCertification();
		if($certification&&in_array($certification->state,['0','1'])){
			return $this->redirect('index');
		}
		return $this->render('lawyer',["model"=>$model,'data'=>isset($data)&&$data?$data:'']);
	}
	
	//公司认证
	public function actionOrgnization(){
		$uid = Yii::$app->request->get('uid');
		if($uid){
			$model= Certification::findOne(['uid'=>$uid]);
			if($model){
				$data = $model->getCardimg();
			}else{
				return $this->redirect('authentication');
			}
		}else{
			$model = new Certification();
		}
		$certification = $model->getCertification();
		if($certification&&in_array($certification->state,['0','1'])){
			return $this->redirect('index');
		}
		return $this->render('orgnization',["model"=>$model,'data'=>isset($data)&&$data?$data:'']);
	}
}

