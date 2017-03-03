<?php

namespace frontend\modules\wap\controllers;
use Yii;
use frontend\modules\wap\components\WapNoLoginController;
use frontend\services\WxpayService;

class WxpayController extends WapNoLoginController
{
	
    public function actionIndex()
    {
		echo \yii\helpers\Url::toRoute(['/wap/wxpay/notify','type'=>'APP'],true);;
        die();
    }
	
	public function actionUnifiedorder(){
		$wxapiConfig = Yii::$app->params['WXAPI'];
		// $wxapiConfig['savedb'] = false;
		$WxpayService = new WxpayService($wxapiConfig); 
		$callbackUrl=\yii\helpers\Url::toRoute('wxpay/notify',true);
		$creatrTime = time();
		$orderNo = "1415659990".time();
		$orderName = "demoorder001";
		$fee = "0.01";
		$arr = $WxpayService->createPayOrder( $fee, $orderNo, $orderName, $callbackUrl, $creatrTime,"NATIVE");
		var_dump($arr);
		// echo json_encode($arr);
		if($arr['code']=='ok'){
			$url = $arr['paydata']->code_url;
			echo "<img style='width:200px' src = '".(\yii\helpers\Url::toRoute(['/site/ma','url'=>urldecode($url)]))."'>";    //调用二维码生成方法
		// var_dump($arr);
		}
		exit;
		
		// echo "unifiedorder";exit;
	}
	public function actionNotify(){
		$postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"])?$GLOBALS["HTTP_RAW_POST_DATA"]:'';
	/*
		$postStr = '<xml><appid><![CDATA[wx38a01306475488e3]]></appid>
<attach><![CDATA[支付]]></attach>
<bank_type><![CDATA[CFT]]></bank_type>
<cash_fee><![CDATA[1]]></cash_fee>
<device_info><![CDATA[WEB]]></device_info>
<fee_type><![CDATA[CNY]]></fee_type>
<is_subscribe><![CDATA[N]]></is_subscribe>
<mch_id><![CDATA[1351216801]]></mch_id>
<nonce_str><![CDATA[Ng9kSOBbPX7lw9rv]]></nonce_str>
<openid><![CDATA[oXGZWw5GCZKm_mjxEhHhYOzN5hlg]]></openid>
<out_trade_no><![CDATA[16082510556658858781]]></out_trade_no>
<result_code><![CDATA[SUCCESS]]></result_code>
<return_code><![CDATA[SUCCESS]]></return_code>
<sign><![CDATA[A8EB217C5992C16290AC7AC8D2352EE3]]></sign>
<time_end><![CDATA[20160825105559]]></time_end>
<total_fee>1</total_fee>
<trade_type><![CDATA[APP]]></trade_type>
<transaction_id><![CDATA[4000462001201608252184519929]]></transaction_id>
<type><![CDATA[APP]]></type>
</xml>'; */
	
		
		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		$type = 'JSAPI';
		if ($postObj === false) {
			$arr =  ["code"=>"WXPayConn",'data'=>""];
			exit;
		}
		if(@$postObj->type){
			$type = $postObj->type;
		}
		
	 
		
		if($type=='APP'){
			$wxapiConfig = Yii::$app->params['WXAPI'];
		}else{
			$wxapiConfig = Yii::$app->params['WXAPI2'];
		}
		$WxpayService = new WxpayService($wxapiConfig); 
		
		$arr = $WxpayService->notify($postStr);
		
		// var_dump($arr);
		error_log($postStr, 3, './postStrLog.txt'); 
		
		if($arr['code']=='ok'){
			$order_id = trim($arr['data']['out_trade_no']);
			\frontend\models\Property::updateAll(['status'=>"-1"],['status'=>"0",'orderId'=>$order_id]);
		}
		exit;
	}
	
}
