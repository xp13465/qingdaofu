<?php
namespace wx\controllers;
use wx\services\Func;
use yii;
use frontend\services\WxpayService;

class PropertyController extends \wx\components\FrontController
{
    public $enableCsrfValidation = false;
	
	/**
     * 我的产调
     *
     **/
    public function actionIndex()
    {
        $this->title = "清道夫债管家-产调查询";
        $this->keywords = "清道夫债管家-产调查询";
        $this->description = "清道夫债管家-产调查询";
        $id = Yii::$app->request->get('id');
        $token = Yii::$app->session->get('user_token');
		$page = Yii::$app->request->get('page',0);
		$limit = Yii::$app->request->get('limit',10);
		$str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/property/index'),['token'=>$token,'id'=>$id,'page'=>$page,'limit'=>$limit]);
        $arr = $this->json2array($str);
		// var_dump($arr);
        if($arr['code'] == '0000') {
            return $this->render('index',['data'=>$arr['result']['data']]);
        }else{
            return $this->render('index',['data'=>[]]);
        }
    }
	
    /**
     *  产调查询
     *
     **/
    public function actionAdd()
    {
		$this->title = "清道夫债管家-产调查询";
        $this->keywords = "清道夫债管家-产调查询";
        $this->description = "清道夫债管家-产调查询";
        $id = Yii::$app->request->get('id');
        $token = Yii::$app->session->get('user_token');
		
		$Referrer = Yii::$app->request->getReferrer();
		$cookies = Yii::$app->response->cookies;
		if(strpos($Referrer,$_SERVER['HTTP_HOST'])!==false&&strpos($Referrer,"add")===false){
			$cookies->add(new \yii\web\Cookie([
				'name' => 'addReferrer',
				'value' => $Referrer,
			]));
		}
		$prevpage = ($cookies->getValue('addReferrer'));
		
		$callbackUrl=\yii\helpers\Url::toRoute(['/property/add','id'=>$id],true);
		$openid = YII_ENV_TEST?'oNZOTszvLxTU9zaLKyvfXu--ynqQ':$this->getOpen1($callbackUrl);
		
        
		$str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/property/areas'),['token'=>$token,'id'=>$id]);
        $arr = $this->json2array($str);
		// print_r($arr);
		$UModel = (new \yii\db\Query())
						->select(['mobile'])
						->from('zcb_user')
						->where(['token'=>$token]) 
						->one();
		$defaultMobile =$UModel?$UModel['mobile']:'';
        if($arr['code'] == '0000') {
            return $this->render('add',['provinceData'=>$arr['result']['data'],'prevpage'=>$prevpage,'defaultMobile'=>$defaultMobile]);
        }else{
            $this->notFound();
        }
    }
	
	/**
     *  产调单生成
     *
     **/
    public function actionAdddata()
    {
		$this->title = "清道夫债管家-申请保函";
		$this->keywords = "清道夫债管家-申请保函";
        $this->description = "清道夫债管家-申请保函";
		// print_r($_POST);exit;
        $token = Yii::$app->session->get('user_token');
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post('post');
            $area = Yii::$app->request->post('area'); 
            $address = Yii::$app->request->post('address');
            $phone = Yii::$app->request->post('phone');
            $type = Yii::$app->request->post('type');
            $name = Yii::$app->request->post('name');
            $money = '25';
			// $this->delFormKey();
			// exit;
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/property/create'), ['token' => $token, 'area' => $area , 'address' => $address, 'phone' => $phone,'type' => $type,'name' => $name, 'money' => $money]);
            die;
        }
    }
	
	/**
     * 产调支付
     *
     **/
    public function actionPay()
    { 
		$id = Yii::$app->request->get('id');
        $token = Yii::$app->session->get('user_token');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/property/info'),['token'=>$token,'id'=>$id]);
        $arr = $this->json2array($str);
		// var_dump(Yii::$app->user->getId());
		
		$Referrer = Yii::$app->request->getReferrer();
		$cookies = Yii::$app->response->cookies;
		if(strpos($Referrer,$_SERVER['HTTP_HOST'])!==false&&strpos($Referrer,"pay")===false){
			$cookies->add(new \yii\web\Cookie([
				'name' => 'payReferrer',
				'value' => $Referrer,
			]));
		}
		$prevpage = ($cookies->getValue('payReferrer'));
		
		
		$callbackUrl=\yii\helpers\Url::toRoute(['/property/pay','id'=>$id],true);
		$openid = YII_ENV_TEST?'oC2RrwHbLPYQsYXBR2Ozc2pwQCI4':$this->getOpen2($callbackUrl);
		if($arr['code'] == '0000') {
			return $this->render('pay',['data'=>$arr['result']['data'],'openid'=>$openid,'prevpage'=>$prevpage]);
		}else{
			$this->notFound();
		}
		
    }
	/**
     *  支付单生成
     *
     **/
    public function actionPaydata()
    {
		
        $token = Yii::$app->session->get('user_token');
        if (Yii::$app->request->post()) {   
            $openid = Yii::$app->request->post('openid');
            $id = Yii::$app->request->post('id');
            $paytype = 'JSAPI';
			
			$data = [
				'token' => $token, 
				'id' => $id ,
				'openid' => $openid ,
				'paytype' => $paytype,
			];
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/property/pay'), $data);
            die;
        }
    }
	
     /**
     *   产调查询提交成功
     *
     **/
    public function actionAddok()
    { 
		$id = Yii::$app->request->get('id');
        $token = Yii::$app->session->get('user_token');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/property/info'),['token'=>$token,'id'=>$id]);
        $arr = $this->json2array($str);
		if($arr['code'] == '0000') {
			return $this->render('addok',['data'=>$arr['result']['data']]);
		}else{
			$this->notFound();
		}
	}
	
	/**
     *   产调查询提交成功
     *
     **/
    public function actionView()
    { 
		$id = Yii::$app->request->get('id');
        $token = Yii::$app->session->get('user_token');
		$str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/property/view'),['token'=>$token,'id'=>$id]);
         $arr = $this->json2array($str);
		
		if($arr['code'] == '0000') {
			return $this->render('view',$arr['result']);
		}else{
			$this->notFound();
		}
	}
	
	
	/**
     *   快递信息
     *
     **/
    public function actionExpress()
    { 
		
		$id = Yii::$app->request->get('id');
        $token = Yii::$app->session->get('user_token');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/property/areas'),['token'=>$token,'id'=>$id]);
        $arr = $this->json2array($str);
		if($arr['code'] == '0000') {
			return $this->render('express',['provinceData'=>$arr['result']['data'],'id'=>$id]);
		}else{
			$this->notFound();
		}
		
	}
	
	/**
     *   快递信息保存
     *
     **/
    public function actionExpressdata()
    { 
		$jid = Yii::$app->request->post('jid');
		$name = Yii::$app->request->post('name');
		$phone = Yii::$app->request->post('phone');
		$area = Yii::$app->request->post('area');
		$address = Yii::$app->request->post('address');
		
        $token = Yii::$app->session->get('user_token');
        echo $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/property/express'),['token'=>$token,'jid'=>$jid,'name'=>$name,'phone'=>$phone,'cityid'=>$area,'address'=>$address]);
        
	}
	/**
     *   快递信息
     *
     **/
    public function actionExpressok()
    { 
		
		$id = Yii::$app->request->get('id');
        $token = Yii::$app->session->get('user_token');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/property/info'),['token'=>$token,'id'=>$id]);
         $arr = $this->json2array($str);
		
		if($arr['code'] == '0000') {
			return $this->render('expressok',['data'=>$arr['result']['data']]);
		}else{
			$this->notFound();
		}
		
	}
	
	/**
     *  产调查询
     *
     **/
    public function actionEdit()
    {
        $this->title = "清道夫债管家-产调查询";
        $this->keywords = "清道夫债管家-产调查询";
        $this->description = "清道夫债管家-产调查询";
        $id = Yii::$app->request->get('id');
        $token = Yii::$app->session->get('user_token');
		
		$Referrer = Yii::$app->request->getReferrer();
		$cookies = Yii::$app->response->cookies;
		if(strpos($Referrer,$_SERVER['HTTP_HOST'])!==false&&strpos($Referrer,"edit")===false){
			$cookies->add(new \yii\web\Cookie([
				'name' => 'editReferrer',
				'value' => $Referrer,
			]));
		}
		$prevpage = ($cookies->getValue('editReferrer'));
		
		
		$callbackUrl=\yii\helpers\Url::toRoute(['/property/edit','id'=>$id],true);
		$openid1 = YII_ENV_TEST?'oNZOTszvLxTU9zaLKyvfXu--ynqQ':$this->getOpen1($callbackUrl);
		
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/property/areas'),['token'=>$token]);
        $areas = $this->json2array($str);
		
		$str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/property/info'),['token'=>$token,'id'=>$id]);
        $arr = $this->json2array($str);
		
		
		if($arr['code'] == '0000'&&$areas['code'] == '0000') {
            return $this->render('edit',['id'=>$id,'provinceData'=>$areas['result']['data'],'data'=>$arr['result']['data'],'prevpage'=>$prevpage]);
		}else{
			$this->notFound();
		}
    }
	
	/**
     *  产调单生成
     *
     **/
    public function actionEditdata()
    {
		 $this->title = "清道夫债管家-申请保函";
		 $this->keywords = "清道夫债管家-申请保函";
        $this->description = "清道夫债管家-申请保函";
		// print_r($_POST);exit;
        $token = Yii::$app->session->get('user_token');
        if (Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id'); 
            $area = Yii::$app->request->post('area'); 
            $address = Yii::$app->request->post('address');
            $phone = Yii::$app->request->post('phone');
			$type = Yii::$app->request->post('type');
            $name = Yii::$app->request->post('name');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/property/update'), ['token' => $token, 'area' => $area , 'address' => $address, 'type' => $type, 'name' => $name, 'phone' => $phone, 'id' => $id]);
            die;
        }
    }
	
	public function getOpen2($callbackUrl){
		$wxapiConfig = [];
		$wxapiConfig['savedb'] = false;
		$wxapiConfig['appid'] = 'wxad34114f5aaae230';
		$wxapiConfig['mchid'] = '1328619001';
		$wxapiConfig['key'] = '4rfv7YUHB2wspllmn12wegf5tfs2GLI1';
		$WxpayService = new WxpayService($wxapiConfig); 
		$code = Yii::$app->request->get('code'); 
		
		$url = $WxpayService->createOauthUrlForCode(urlencode($callbackUrl));
		
		$WxpayService->setCode($code);
		
		$openid = $WxpayService->getOpenId();
		
		if(!$openid){
			$this->redirect($url); 
		}else{ 
			$userid = Yii::$app->user->getId();
			$userOpenIdModel = \common\models\Openid::find()->where(["uid"=>$userid])->one();
			
			if($userid&&!$userOpenIdModel){
				$userOpenIdModel = new \common\models\Openid;
				$userOpenIdModel->uid = $userid;
			}
			if($userid&&$userOpenIdModel->openid_2!=$openid){
				$userOpenIdModel->openid_2 = $openid;
				$userOpenIdModel->save();
			}
			return $openid;
		}
	}
	public function getOpen1($callbackUrl){
		
		$wxapiConfig = [];
		$wxapiConfig['savedb'] = false;
		$wxapiConfig['appid'] = 'wxb31437c7395f1399';
		$wxapiConfig['mchid'] = '1328619001';
		$wxapiConfig['key'] = '8c777e91602c930f443015952db70d16';
		$WxpayService = new WxpayService($wxapiConfig); 
		
		
		$code = Yii::$app->request->get('code'); 
		$url = $WxpayService->createOauthUrlForCode(urlencode($callbackUrl));
		$WxpayService->setCode($code);
		
		$openid = $WxpayService->getOpenId('wxb31437c7395f1399','8c777e91602c930f443015952db70d16');
		
		if(!$openid){
			$this->redirect($url); 
		}else{ 
			$userid = Yii::$app->user->getId();
			$userOpenIdModel = \common\models\Openid::find()->where(["uid"=>$userid])->one();
			
			if($userid&&!$userOpenIdModel){
				$userOpenIdModel = new \common\models\Openid;
				$userOpenIdModel->uid = $userid;
			}
			if($userid&&$userOpenIdModel->openid!=$openid){
				$userOpenIdModel->updateAll(["openid"=>""],["openid"=>$openid]);
				$userOpenIdModel->openid = $openid;
				$userOpenIdModel->save();
			}
			return $openid;
		}
	}
}


