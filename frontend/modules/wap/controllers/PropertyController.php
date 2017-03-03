<?php

namespace frontend\modules\wap\controllers;

use frontend\modules\wap\components\WapController;
use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use frontend\services\Func;
use frontend\models\PolicyForm;
use frontend\models\Policy;
use frontend\components\FrontController;

/**
 * 用户操作控制器
 */
class PropertyController extends WapController
{
	public $modelClass = 'frontend\models\Property';
	
	public function actionIndex()
    { 
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
		$page = Yii::$app->request->post('page');
		$limit = Yii::$app->request->post('limit');
        $model = new $this->modelClass;
		// $data = (new \yii\db\Query())
			// ->from('zcb_property')
			// ->where(['uid'=>$uid])
			// ->orderby("time desc")
			// ->offset(($page-1)*$limit)
            // ->limit($limit)
			// ->all();
		if($uid == 55 || $uid == 213 || $uid == 568 || $uid == 665 ){
			$data = (new \yii\db\Query())
			->select('zcb_property.*,zcb_express.id as expressId,zcb_express.orderId as expressorderId')
			->from('zcb_property')
			->join('LEFT JOIN', 'zcb_express', 'zcb_express.jid = zcb_property.id')
			// ->where(['zcb_property.uid'=>$uid])
			->groupby("zcb_property.id")
			->orderby("zcb_property.time desc")
			->offset(($page-1)*$limit)
            ->limit($limit)
			->all();
		}else{
			$data = (new \yii\db\Query())
			->select('zcb_property.*,zcb_express.id as expressId,zcb_express.orderId as expressorderId')
			->from('zcb_property')
			->join('LEFT JOIN', 'zcb_express', 'zcb_express.jid = zcb_property.id')
			->where(['zcb_property.uid'=>$uid])
			->groupby("zcb_property.id")
			->orderby("zcb_property.time desc")
			->offset(($page-1)*$limit)
            ->limit($limit)
			->all();
		}
		
		foreach ($data as $key=>$val){
			if($val['status']!="2"||$val['expressId']||($val['uptime']+86400)<time()){
				$data[$key]['canExpress'] = "0";
				if($val['status']!="2"){
					$data[$key]['canExpressmsg'] = "未完成";
				}else if($val['expressId']){
					$data[$key]['canExpressmsg'] = "已申请快递";
				}else if(($val['uptime']+86400)<time()){
					$data[$key]['canExpressmsg'] = "已过24小时";
				}
			}else{
				$data[$key]['canExpress'] = "1";
				$data[$key]['canExpressmsg'] = "";
			}
			
			//类型处理
			if($val['type']==2){
				$val['typeLabel'] = "交易中心版";
			}else{
				$val['typeLabel'] = "电子版";
			}
			
			
			//状态处理
			if($val['status'] == 2){
			   $data[$key]['statusLabel'] = "已处理";
			}else if($val['status'] == 1 || $val['status'] == -1){
			    $data[$key]['statusLabel'] = "处理中";
			}else if($val['status'] == 3){
			    $data[$key]['statusLabel'] = "处理中";//退款中
			}else if($val['status'] == 4){
			    $data[$key]['statusLabel'] = "已退款";
			}else{
			    $data[$key]['statusLabel'] = "未付款";
			}
			//用时处理
			if($val['status'] == 2){ 
				if($val['uptime']){
					$data[$key]['yongshi'] = ($val['uptime'] - $val['time']) / 60;
				}else{
					$data[$key]['yongshi'] = "40";
				}
			}else if($val['status'] == 1 || $val['status'] == -1){
			    $data[$key]['yongshi'] = ((time()-$val['time'])/60);
			}else if($val['status'] == 3 || $val['status'] == 4){
				if($val['uptime']){
					$data[$key]['yongshi'] = ($val['uptime'] - $val['time']) / 60;
				}else{
					$data[$key]['yongshi'] = "40";
				}
			}else{
			    $data[$key]['yongshi'] = "0";
			}
			$data[$key]['yongshi'] = (int)$data[$key]['yongshi'];
			//结果处理
			if($val['status'] == 2){
				$data[$key]['result'] = ['type'=>"view",'attr'=>urlencode(base64_encode($val['cid'] .','.$val['id']))]; 
			}else if($val['status'] == 1 || $val['status'] == -1){
			    $data[$key]['result'] = ['type'=>"title",'attr'=>"等待"];
			}else if($val['status'] == 3){
				$fee = (new \yii\db\Query())
						->select(['refund_msg','refund_fee'])
						->from('zcb_cdcon')
						->where(['cid'=>$val['cid']]) 
						->one();
				$attr = $val['cid'] && $fee ? $fee['refund_msg'] : '地址错误对或无产权';
				$refund_fee =  $fee ? $fee['refund_fee'] : '0';
			    $data[$key]['result'] = ['type'=>"tips",'attr'=>$attr,'refund_fee'=>$refund_fee];
			}else if($val['status'] == 4){
				$fee = (new \yii\db\Query())
						->select(['refund_msg','refund_fee'])
						->from('zcb_cdcon')
						->where(['cid'=>$val['cid']]) 
						->one();
				$attr = $val['cid']&&$fee ? $fee['refund_msg'] : '地址错误对或无产权';
				$refund_fee =  $fee ? $fee['refund_fee'] : '0';
			    $data[$key]['result'] = ['type'=>"tips",'attr'=>$attr,'refund_fee'=>$refund_fee];
			}else{
			    $data[$key]['result'] = ['type'=>"title",'attr'=>"未付"];
			} 
		} 
		$this->success("",['data'=>$data]); 
		
		// echo Json::encode(['code'=>'0000','result'=>['data'=>$data]]);die;	 
    }
	
 
	
	public function actionAreas()
    { 
		$rows = (new \yii\db\Query())
			->select(['id', 'name'])
			->from('zcb_areas')
			->where(['reid' => '321'])
			// ->limit(10)
			->all();
		$this->success("",['data'=>$rows]); 
		// echo Json::encode(['code'=>'0000','result'=>['data'=>$rows]]);die;	

    }
	
	public function actionCreate()
    {
        $model = new $this->modelClass;
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
        $model->setAttributes(Yii::$app->request->post());
		$model->province = "321";
		$model->money = "25";
		$model->uid = $uid;
		$model->type = Yii::$app->request->post('type');
		$model->name = Yii::$app->request->post('name','');
		$model->city = Yii::$app->request->post('area');
		$model->address = Yii::$app->request->post('address');
		$model->phone = Yii::$app->request->post('phone');
		// $model->money = Yii::$app->request->post('money');
		$model->time = time();
		$model->orderId = Func::getTxNo20();
		if($model->validate()){
			if($model->type==2){
				$model->money = "80";
			}
			if($model->save()){
				$smsbak = array(
					'mobile' => '15021476881',
					// 'mobile' => '15316602556',
					'msg' => $model->address . '已申请,请进入后台处理...'
				);
				if(YII_ENV_TEST)$sms['mobile'] = '15316602556';
				
				Func::curl(2,$smsbak);
				
				$this->success("申请成功！",['money'=>$model->money,"id"=>$model->id]); 
			}else{
				$this->errorMsg("ModelDataSave","申请失败！");
			}
        }else { 
			$this->errorMsg("ModelDataCheck","申请失败！".$model->formatErrors());  
        }
            
        
    }
 
	public function actionInfo()
    {	
		$id = Yii::$app->request->post('id');
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
        $model = new $this->modelClass;
		$data = $model::find()->where(['id'=>$id,/*'uid'=>$uid*/])->one();
		if($data){
			$this->success("",['data'=>$data]); 
		}else{
			$this->errorMsg("ParamsCheck",'');  
		}
		
		// echo Json::encode(['code'=>'0000','result'=>['data'=>$data]]);die;	
		// var_dump($data);
        
    }
	public function actionView()
    {	
		$id = Yii::$app->request->post('id');
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
        $model = new $this->modelClass;
		// $data = $model::find()->where(['id'=>$id,'uid'=>$uid])->one();
		 
		$id = base64_decode(urldecode($id));
		$ids = explode(',',$id);
		if(count($ids)!=2){
			$this->errorMsg("ParamsCheck","");  
		}
		// var_dump($ids);exit;
		$ones = $model::find()->where(['id'=>$ids[1]])->one();
		if(!$ones){
			$this->errorMsg("ParamsCheck","");  
		}
		$one = $ones->attributes;
		$cdcon = (new \yii\db\Query())
						// ->select(['images','cdcon'])
						->from('zcb_cdcon') 
						->where(['offlineId'=>$ones['id']]) 
						->one();
		$city = (new \yii\db\Query())
						->select(['name'])
						->from('zcb_areas')
						->where(['id'=>$ones['city']]) 
						->one();
		$one['cityname']=$city['name'];
		$data = []; 
		$success_msg = '';
		if($cdcon){
			if($cdcon['images']&&$cdcon['images']!='a:1:{i:0;s:15:"/Public/upload/";}'){
				$data = unserialize($cdcon['images']);
			}else{
				$data = [];
			}
		}else if($ones){
			$appid ='9f1727c84a' ;
			$token = '9efa016b0b46ef9e0dab906931cbca19';
			$timestamp = time();
			$parm = "appid={$appid}&id={$ones['cid']}&timestamp={$timestamp}";
			$sign = strtolower(md5($parm.$token));
			$url = 'http://www.1001fang.com/cdapi/query?'.$parm.'&sign='.$sign;
		
			$con = json_decode(Func::CurlGet($url),true);
			if($con&&$con['code']==1){
				$data = $con['response']['images'];
				$success_msg = $con['response']['success_msg'];
				if($success_msg == '改过地址'){
					$success_msg = '<br><font color=red>重要提示:'.$success_msg.'</font>';
				}else{
					$success_msg = '';
				}
			}
		}
		//类型处理
		if($one['type']==2){
			$one['typeLabel'] = "交易中心版";
		}else{
			$one['typeLabel'] = "电子版";
		}
		//状态处理
		if($one['status'] == 2){
			$one['statusLabel'] = "已处理";
		}else if($one['status'] == 1 || $one['status'] == -1){
		    $one['statusLabel'] = "处理中";
		}else if($one['status'] == 3){
		    $one['statusLabel'] = "处理中";//退款中
		}else if($one['status'] == 4){
		    $one['statusLabel'] = "已退款";
		}else{
		    $one['statusLabel'] = "未付款";
		}
		$this->success("",['data'=>$data,'ones'=>$one,'cdcon'=>$cdcon,'success_msg'=>$success_msg]); 
		// echo Json::encode(['code'=>'0000','result'=>['data'=>$data,'ones'=>$one,'success_msg'=>$success_msg]]);die;	
    }
	public function actionPay()
    {	
		$id = Yii::$app->request->post('id');
		$openid = Yii::$app->request->post('openid');
		$paytype = Yii::$app->request->post('paytype');
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
        $model = new $this->modelClass;
		$data = $model::find()->where(['id'=>$id,'uid'=>$uid])->one();
		if(!$data||!in_array($paytype,['APP','JSAPI'])){
			$this->errorMsg("ParamsCheck");
		}
		
		
		if($paytype=="JSAPI"){
			if(!$openid)$this->errorMsg("ParamsCheck");
			// $wxapiConfig['appid']='wxad34114f5aaae230';
			// $wxapiConfig['mchid']='1328619001';
			// $wxapiConfig['key']='4rfv7YUHB2wspllmn12wegf5tfs2GLI1';
			// $wxapiConfig['savedb'] = true;
			$wxapiConfig = Yii::$app->params['WXAPI2'];
		}else{
			$wxapiConfig = Yii::$app->params['WXAPI'];
		}
		
		$WxpayService = new \frontend\services\WxpayService($wxapiConfig); 
		$callbackUrl=\yii\helpers\Url::toRoute(['/wap/wxpay/notify','type'=>'APP'],true);
		$creatrTime = "".time();
		$orderNo = $data->orderId;
		$orderName = "付款直向";
		$fee = $data->money; 
		
		if(YII_ENV_TEST)$fee = '0.01';
		switch($paytype){
			case 'JSAPI':
				$callbackUrl = 'http://m.zcb2016.com/notifywx';
				
				if(YII_ENV_TEST)$callbackUrl = \yii\helpers\Url::toRoute(['/wap/wxpay/notify','type'=>'JSAPI'],true);
				
				$data = $WxpayService->createPayOrder($fee, $orderNo, $orderName, $callbackUrl, $creatrTime,$paytype,$openid);
			break;
			case 'APP': 
				$data = $WxpayService->createPayOrder($fee, $orderNo, $orderName, $callbackUrl, $creatrTime,$paytype);
				break;
		}
		
		switch($data['code']){
			case "ok":
				$this->success("",['paydata'=>$data['paydata']]);
				break;
			case "WXPayConn":
				$this->errorMsg("WXPayConn",$data['msg']);
				break;
			case "WXPaySignCheck":
				$this->errorMsg("WXPaySignCheck",$data['msg']);
				break;
			case "WXPayResult":
				$this->errorMsg("WXPayResult",$data['msg']);
				break;
		}
    }
	
	public function actionExpress()
    {	
		$jid = Yii::$app->request->post('jid');
		$cityid = Yii::$app->request->post('cityid');
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId(); 
		$id = Yii::$app->request->post('id');
		$propertyModel = \frontend\models\Property::findOne($jid);
		if(!$propertyModel){
			$this->errorMsg("ParamsCheck","");  
		}
		$model = new \frontend\models\Express();;
        $model->setAttributes(Yii::$app->request->post());
		$model->time = time(); 
		
		$city = (new \yii\db\Query())
			->select(['name'])
			->from('zcb_areas')
			->where(['id' => $cityid])
			->limit(1)
			->one();
		
		if($model->validate()){ 
			$model->address=$city['name'].$model->address;
			if($model->save()){
				$phone = (new \yii\db\Query())
				->select(['phone'])
				->from('zcb_property')
				->where(['id' => $model->jid])
				->limit(1)
				->one(); 
				$sms = array(
					'mobile' => '15021476881',
					// 'mobile' => '15316602556',
					'msg' => '手机号为('.$phone['phone'].')已为['.$propertyModel->orderId.']申请快递'
				);
				if(YII_ENV_TEST)$sms['mobile'] = '15316602556';
				
				Func::curl(2,$sms);
				$this->success("申请快递成功！",['jid'=>$model->jid,"id"=>$model->id]); 
			}else{
				$this->errorMsg("ModelDataSave","申请失败！");
			}
        }else { 
			$this->errorMsg("ParamsCheck","");  
        }
    }
	
	public function actionUpdate()
    {
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
		$id = Yii::$app->request->post('id');
		$model = \frontend\models\Property::findOne($id);
		
		if($model){
			$model->type = Yii::$app->request->post('type');
			$model->name = Yii::$app->request->post('name');
			$model->city = Yii::$app->request->post('area');
			$model->address = Yii::$app->request->post('address');
			$model->phone = Yii::$app->request->post('phone');
			if($model->validate()){
				$model->orderId = Func::getTxNo20();
				if($model->type==2){
					$model->money = "40";
				}else{
					$model->money = "25";
				}
				if($model->save()){
					$this->success("修改成功！",['money'=>$model->money,"id"=>$model->id]); 
				}else{
					$this->errorMsg("ModelDataSave","修改失败！");
				}
			}else { 
				$this->errorMsg("ModelDataCheck","修改失败！".$model->formatErrors());  
			}
		}else{
			$this->errorMsg("ParamsCheck",'');  
		}
       
        
    }
	/*
	public function actionNotify(){
		$postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"])?$GLOBALS["HTTP_RAW_POST_DATA"]:'';
		//error_log($postStr, 3, './str.txt');
		$wxapiConfig = Yii::$app->params['WXAPI'];
		// $wxapiConfig['savedb'] = false;
		$WxpayService = new WxpayService($wxapiConfig); 
		$postStr = '<xml>
		<appid><![CDATA[wx00e5904efec77699]]></appid>
		<attach><![CDATA[支付测试]]></attach>
		<bank_type><![CDATA[CMB_CREDIT]]></bank_type>
		<cash_fee><![CDATA[1]]></cash_fee>
		<fee_type><![CDATA[CNY]]></fee_type>
		<is_subscribe><![CDATA[Y]]></is_subscribe>
		<mch_id><![CDATA[1220647301]]></mch_id>
		<nonce_str><![CDATA[a0tZ41phiHm8zfmO]]></nonce_str>
		<openid><![CDATA[oU3OCt5O46PumN7IE87WcoYZY9r0]]></openid>
		<out_trade_no><![CDATA[550bf2990c51f]]></out_trade_no>
		<result_code><![CDATA[SUCCESS]]></result_code>
		<return_code><![CDATA[SUCCESS]]></return_code>
		<sign><![CDATA[F6F519B4DD8DB978040F8C866C1E6250]]></sign>
		<time_end><![CDATA[20150320181606]]></time_end>
		<total_fee>1</total_fee>
		<trade_type><![CDATA[JSAPI]]></trade_type>
		<transaction_id><![CDATA[1008840847201503200034663980]]></transaction_id>
		</xml>';
		$arr = $WxpayService->notify($postStr);
		var_dump($arr);
		echo "notify";exit;
	}
	*/
}
