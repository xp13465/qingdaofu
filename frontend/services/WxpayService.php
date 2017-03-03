<?php
namespace frontend\services; 
use Yii; 
class WxpayService
{
	protected $mchid;
	protected $appid;
	protected $key;
	protected $code;
	protected $savedb;
	protected $openid;
	public static $err_code_name= [
		""				=> "",
		"NOAUTH"				=> "商户无此接口权限",
		"NOTENOUGH"				=> "余额不足",
		"ORDERPAID"				=> "商户订单已支付",
		"ORDERCLOSED"			=> "订单已关闭",
		"SYSTEMERROR"			=> "系统错误",
		"APPID_NOT_EXIST"		=> "APPID不存在",
		"MCHID_NOT_EXIST"		=> "MCHID不存在",
		"APPID_MCHID_NOT_MATCH"	=> "appid和mch_id不匹配",
		"LACK_PARAMS"			=> "缺少参数",
		"OUT_TRADE_NO_USED"		=> "商户订单号重复",
		"SIGNERROR"				=> "签名错误",
		"XML_FORMAT_ERROR"		=> "XML格式错误",
		"REQUIRE_POST_METHOD"	=> "请使用post方法",
		"POST_DATA_EMPTY"		=> "post数据为空",
		"NOT_UTF8"				=> "编码格式错误",
	]; 
	 
	
	public function __construct($config)
	{ 
		$this->mchid = $config['mchid']; // 微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
		$this->appid = $config['appid']; //公众号APPID 通过微信支付商户资料审核后邮件发送
		$this->key = $config['key']; //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
		$this->savedb = isset($config['savedb'])?$config['savedb']:true; //是否存储数据库日志
	}
	/**
	* @param string $openid 调用【网页授权获取用户信息】接口获取到用户在该公众号下的Openid
	* @param float $totalFee 收款总费用 单位元
	* @param string $outTradeNo 唯一的订单号
	* @param string $orderName 订单名称
	* @param string $notifyUrl 支付结果通知url 不要有问号
	*   https://mp.weixin.qq.com/ 微信支付-开发配置-测试目录
	*   测试目录 http://mp.izhanlue.com/paytest/  最后需要斜线，(需要精确到二级或三级目录)
	* @return string
	*/
	public function createPayOrder($totalFee, $outTradeNo, $orderName, $notifyUrl, $timestamp,$trade_type='APP',$openid='')
	{
    $config = array(
		'mch_id' => $this->mchid,
        'appid' => $this->appid,
		'key' => $this->key,
    );
    $unified = array(
		'appid' => $config['appid'],
		'mch_id' => $config['mch_id'],
		'device_info' => $trade_type!="APP"?"":"WEB",
		'attach' => '支付',    
		'detail' => '',
		'nonce_str' => self::createNonceStr(),
		'body' => $orderName,
		'notify_url' => $notifyUrl,   
		'out_trade_no' => $outTradeNo,
		'fee_type' => "CNY",
		'total_fee' => intval($totalFee * 100),       //单位 转为分
      	'openid' => $openid,  
     	'spbill_create_ip' => Yii::$app->request->userIP,
     	'trade_type' => $trade_type,
    );
    $unified['sign'] = self::getSign($unified, $config['key']);
	// echo (self::arrayToXml($unified));
	if($this->savedb){
		$UnifiedorderModel = new \app\models\Unifiedorder();
		$UnifiedorderModel->setAttributes($unified);
		$UnifiedorderModel->setAttributes(['actiontime'=>date("Y-m-d H:i:s")]); 
		$status = $UnifiedorderModel->save();
		$uoid = $status?$UnifiedorderModel->id:0;
	}
	
// exit;
    $responseXml = self::curlPost('https://api.mch.weixin.qq.com/pay/unifiedorder', self::arrayToXml($unified));
	
	// echo $responseXml;
    /*
    <xml>
    <return_code><![CDATA[SUCCESS]]></return_code>
    <return_msg><![CDATA[OK]]></return_msg>
    <appid><![CDATA[wx00e5904efec77699]]></appid>
    <mch_id><![CDATA[1220647301]]></mch_id>
    <nonce_str><![CDATA[1LHBROsdmqfXoWQR]]></nonce_str>
    <sign><![CDATA[ACA7BC8A9164D1FBED06C7DFC13EC839]]></sign>
    <result_code><![CDATA[SUCCESS]]></result_code>
    <prepay_id><![CDATA[wx2015032016590503f1bcd9c30421762652]]></prepay_id>
    <trade_type><![CDATA[JSAPI]]></trade_type>
    </xml>
    */
    $unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
    if ($unifiedOrder === false) {
		return ["code"=>"WXPayConn",'msg'=>"parse xml error"];
    } 
	
	if($this->savedb){
		$UnifiedorderApplyModel = new \app\models\UnifiedorderApply();
		$UnifiedorderApplyModel->setAttributes((array)$unifiedOrder);
		$UnifiedorderApplyModel->setAttributes(["uoid"=>$uoid]);
		$UnifiedorderApplyModel->setAttributes(['actiontime'=>date("Y-m-d H:i:s")]); 
		$status = $UnifiedorderApplyModel->save();
	}
	
    if ($unifiedOrder->return_code != 'SUCCESS') {
		return ["code"=>"WXPaySignCheck",'msg'=>$unifiedOrder->return_msg];
    }
    if ($unifiedOrder->result_code != 'SUCCESS') {
		$unifiedOrder = (array)$unifiedOrder;
		return ["code"=>"WXPayResult",'msg'=>self::$err_code_name[$unifiedOrder['err_code']]];
    }
    //$unifiedOrder->trade_type 交易类型 调用接口提交的交易类型，取值如下：JSAPI，NATIVE，APP
    //$unifiedOrder->prepay_id 预支付交易会话标识 微信生成的预支付回话标识，用于后续接口调用中使用，该值有效期为2小时
    //$unifiedOrder->code_url 二维码链接 trade_type为NATIVE是有返回，可将该参数值生成二维码展示出来进行扫码支付
	switch($trade_type){
		case "APP":
			$arr = array(
				"appid" => ($config['appid']),
				"partnerid" => ($this->mchid),
				"prepayid" => ("".$unifiedOrder->prepay_id),
				"package" => ('Sign=WXPay'),
				"noncestr" => (self::createNonceStr()),
				"timestamp" => ($timestamp),
			);
			$arr['paySign'] = self::getSign($arr, $config['key']);
			break;
		case "JSAPI":
			$arr = array(
			  "appId" => $config['appid'],
			  "timeStamp" => $timestamp,
			  "nonceStr" => self::createNonceStr(),
			  "package" => "prepay_id=" . $unifiedOrder->prepay_id,
			  "signType" => 'MD5',
			);
			$arr['paySign'] = self::getSign($arr, $config['key']);
			break;
		default:
			$arr = $unifiedOrder;
			break;
	}
	return ["code"=>"ok",'paydata'=>$arr];
  }
  public function notify($postStr)
  {
    $config = array(
      'mch_id' 	=> $this->mchid,
      'appid'	=> $this->appid,
      'key' 	=> 	$this->key,
    );
	
    $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
	
    if ($postObj === false) {
		return ["code"=>"WXPayConn",'data'=>""];
    }
	
	$arr = (array)$postObj; 
	
	if($this->savedb){
		$postObj->total_fee = $postObj->total_fee?($postObj->total_fee/100):$postObj->total_fee;
		$postObj->cash_fee = $postObj->cash_fee?($postObj->cash_fee/100):$postObj->cash_fee;
		$UnifiedorderResultModel = new \app\models\UnifiedorderResult();
		$UnifiedorderResultModel->setAttributes((array)$postObj); 
		$UnifiedorderResultModel->setAttributes(['actiontime'=>date("Y-m-d H:i:s")]); 
		$UnifiedorderResultModel->setAttributes(['requesturl'=>Yii::$app->request->url]); 
		 
		$status = $UnifiedorderResultModel->save();
	}
	
    if ($postObj->return_code != 'SUCCESS') {
		if($this->savedb){
			$UnifiedorderResultModel->setAttributes(['code'=>'WXPaySignCheck']); 
			$status = $UnifiedorderResultModel->save();
		}
		return ["code"=>"WXPaySignCheck",'data'=>$postObj->return_msg];
    }
    if ($postObj->result_code != 'SUCCESS') {
		if($this->savedb){
			$UnifiedorderResultModel->setAttributes(['code'=>'WXPayResult']); 
			$status = $UnifiedorderResultModel->save();
		}
		return ["code"=>"WXPayResult",'data'=>self::$postObj[$postObj->err_code]];
    }
    // var_dump($arr);
	// var_dump(self::getSign($arr, $config['key']));
    if(isset($arr['sign']))unset($arr['sign']);
    if(isset($arr['type']))unset($arr['type']);
    if (self::getSign($arr, $config['key']) == $postObj->sign) {
      // $mch_id = $postObj->mch_id; //微信支付分配的商户号
      // $appid = $postObj->appid; //微信分配的公众账号ID
      // $openid = $postObj->openid; //用户在商户appid下的唯一标识
      // $transaction_id = $postObj->transaction_id;//微信支付订单号
      // $out_trade_no = $postObj->out_trade_no;//商户订单号
      // $total_fee = $postObj->total_fee; //订单总金额，单位为分
      // $is_subscribe = $postObj->is_subscribe; //用户是否关注公众账号，Y-关注，N-未关注，仅在公众账号类型支付有效
      // $attach = $postObj->attach;//商家数据包，原样返回
      // $time_end = $postObj->time_end;//支付完成时间
	 
      echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
		if($this->savedb){
			$UnifiedorderResultModel->setAttributes(['code'=>'ok']); 
			$status = $UnifiedorderResultModel->save();
		}
	  return ["code"=>"ok",'data'=>$arr];
    }else{
		if($this->savedb){
			$UnifiedorderResultModel->setAttributes(['code'=>'WXPayResultSignCheck']); 
			$status = $UnifiedorderResultModel->save();
		}
		return ["code"=>"WXPayResultSignCheck"];
	}
  }
  /**
   * curl get
   *
   * @param string $url
   * @param array $options
   * @return mixed
   */
  public static function curlGet($url = '', $options = array())
  {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    if (!empty($options)) {
      curl_setopt_array($ch, $options);
    }
    //https请求 不验证证书和host
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
  }
  public static function curlPost($url = '', $postData = '', $options = array())
  {
    if (is_array($postData)) {
      $postData = http_build_query($postData);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
    if (!empty($options)) {
      curl_setopt_array($ch, $options);
    }
    //https请求 不验证证书和host
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
  }
  public static function createNonceStr($length = 16)
  {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $str = '';
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }
  public static function arrayToXml($arr)
  {
    $xml = "<xml>";
    foreach ($arr as $key => $val) {
      if (is_numeric($val)) {
        $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
      } else
        $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
    }
    $xml .= "</xml>";
    return $xml;
  }
  /**
   * 例如：
   * appid：  wxd930ea5d5a258f4f
   * mch_id：  10000100
   * device_info： 1000
   * Body：  test
   * nonce_str： ibuaiVcKdpRxkhJA
   * 第一步：对参数按照 key=value 的格式，并按照参数名 ASCII 字典序排序如下：
   * stringA="appid=wxd930ea5d5a258f4f&body=test&device_info=1000&mch_i
   * d=10000100&nonce_str=ibuaiVcKdpRxkhJA";
   * 第二步：拼接支付密钥：
   * stringSignTemp="stringA&key=192006250b4c09247ec02edce69f6a2d"
   * sign=MD5(stringSignTemp).toUpperCase()="9A0A8659F005D6984697E2CA0A9CF3B7"
   */
  public static function getSign($params, $key)
  {
    ksort($params, SORT_STRING);
    $unSignParaString = self::formatQueryParaMap($params, false);
    $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
    return $signStr;
  }
  protected static function formatQueryParaMap($paraMap, $urlEncode = false)
  {
    $buff = "";
    ksort($paraMap);
    foreach ($paraMap as $k => $v) {
      if (null != $v && "null" != $v) {
        if ($urlEncode) {
          $v = urlencode($v);
        }
        $buff .= $k . "=" . $v . "&";
      }
    }
    $reqPar = '';
    if (strlen($buff) > 0) {
      $reqPar = substr($buff, 0, strlen($buff) - 1);
    }
    return $reqPar;
  }
  
  /** 
   * @param string $openid 调用【网页授权获取用户信息】接口获取到用户在该公众号下的Openid 
   * @param float $totalFee 收款总费用 单位元 
   * @param string $outTradeNo 唯一的订单号 
   * @param string $orderName 订单名称 
   * @param string $notifyUrl 支付结果通知url 不要有问号 
   *   https://mp.weixin.qq.com/ 微信支付-开发配置-测试目录 
   *   测试目录 http://mp.izhanlue.com/paytest/  最后需要斜线，(需要精确到二级或三级目录) 
   * @return string 
   */  
  public function createJsBizPackage($openid, $totalFee, $outTradeNo, $orderName, $notifyUrl, $timestamp)  
  {  
    $config = array(  
      'mch_id' => $this->mchid,  
      'appid' => $this->appid,  
      'key' => $this->key,  
    );  
    $unified = array(  
      'appid' => $config['appid'],  
	  'mch_id' => $config['mch_id'],  
      'attach' => '支付',             //商家数据包，原样返回
	  
      'body' => $orderName,  
      'nonce_str' => self::createNonceStr(),  
      'notify_url' => $notifyUrl,  
      'openid' => $openid,            //rade_type=JSAPI，此参数必传  
      'out_trade_no' => $outTradeNo,  
      'spbill_create_ip' => '127.0.0.1',  
      'total_fee' => intval($totalFee * 100),       //单位 转为分  
      'trade_type' => 'JSAPI',  
    );  
    $unified['sign'] = self::getSign($unified, $config['key']);  
    $responseXml = self::curlPost('https://api.mch.weixin.qq.com/pay/unifiedorder', self::arrayToXml($unified));  
    /* 
    <xml> 
    <return_code><![CDATA[SUCCESS]]></return_code> 
    <return_msg><![CDATA[OK]]></return_msg> 
    <appid><![CDATA[wx00e5904efec77699]]></appid> 
    <mch_id><![CDATA[1220647301]]></mch_id> 
    <nonce_str><![CDATA[1LHBROsdmqfXoWQR]]></nonce_str> 
    <sign><![CDATA[ACA7BC8A9164D1FBED06C7DFC13EC839]]></sign> 
    <result_code><![CDATA[SUCCESS]]></result_code> 
    <prepay_id><![CDATA[wx2015032016590503f1bcd9c30421762652]]></prepay_id> 
    <trade_type><![CDATA[JSAPI]]></trade_type> 
    </xml> 
    */  
    $unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);  
    if ($unifiedOrder === false) {  
      // die('parse xml error');  
	  return ['error'=>1,'msg'=>'parse xml error'];  
    }  
    if ($unifiedOrder->return_code != 'SUCCESS') {  
      // die($unifiedOrder->return_msg);  
	  return ['error'=>2,'msg'=>$unifiedOrder->return_msg];  
    }  
    if ($unifiedOrder->result_code != 'SUCCESS') {  
      // die($unifiedOrder->err_code);  
	  return ['error'=>3,'msg'=>$unifiedOrder->return_msg];  
      /* 
      NOAUTH 商户无此接口权限 
      NOTENOUGH 余额不足 
      ORDERPAID 商户订单已支付 
      ORDERCLOSED 订单已关闭 
      SYSTEMERROR 系统错误 
      APPID_NOT_EXIST   APPID不存在 
      MCHID_NOT_EXIST MCHID不存在 
      APPID_MCHID_NOT_MATCH appid和mch_id不匹配 
      LACK_PARAMS 缺少参数 
      OUT_TRADE_NO_USED 商户订单号重复 
      SIGNERROR 签名错误 
      XML_FORMAT_ERROR XML格式错误 
      REQUIRE_POST_METHOD 请使用post方法 
      POST_DATA_EMPTY post数据为空 
      NOT_UTF8 编码格式错误 
      */  
    }  
    //$unifiedOrder->trade_type 交易类型 调用接口提交的交易类型，取值如下：JSAPI，NATIVE，APP  
    //$unifiedOrder->prepay_id 预支付交易会话标识 微信生成的预支付回话标识，用于后续接口调用中使用，该值有效期为2小时  
    //$unifiedOrder->code_url 二维码链接 trade_type为NATIVE是有返回，可将该参数值生成二维码展示出来进行扫码支付  
    $arr = array(  
		"appId" => $config['appid'],  
		"timeStamp" => "{$timestamp}",  
		"nonceStr" => self::createNonceStr(),  
		"package" => "prepay_id=" . $unifiedOrder->prepay_id,  
		"signType" => 'MD5',  
    );  
    $arr['paySign'] = self::getSign($arr, $config['key']);  
    return ['error'=>0,'paydata'=>$arr];  
  }
  /**
	 * 	作用：设置code
	 */
	function setCode($code_)
	{
		$this->code = $code_;
	}
  /**
	 * 	作用：生成可以获得openid的url
	 */
	function createOauthUrlForOpenid($appid='',$secret='')
	{
		$urlObj["appid"] = $appid?$appid:'wxad34114f5aaae230';
		$urlObj["secret"] = $secret?$secret:'d1700216db148d6f0b0656c8f90dfc1e';
		$urlObj["code"] = $this->code;
		$urlObj["grant_type"] = "authorization_code";
		$bizString = self::formatQueryParaMap($urlObj, false);
		return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
	}
	
	
	/**
	 * 	作用：通过curl向微信提交code，以获取openid
	 */
	function getOpenid($appid='',$secret='')
	{
		$url = $this->createOauthUrlForOpenid($appid,$secret);
        //初始化curl
       	$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//运行curl，结果以jason形式返回
        $res = curl_exec($ch);
		curl_close($ch);
		//取出openid
		$data = json_decode($res,true);
		$this->openid = isset($data['openid'])?$data['openid']:'';
		return $this->openid;
	}
	
	/**
	 * 	作用：生成可以获得code的url
	 */
	function createOauthUrlForCode($redirectUrl)
	{
		$urlObj["appid"] = $this->appid;
		$urlObj["redirect_uri"] = "$redirectUrl";
		$urlObj["response_type"] = "code";
		$urlObj["scope"] = "snsapi_base";
		$urlObj["state"] = "STATE"."#wechat_redirect";
		$bizString = self::formatQueryParaMap($urlObj, false);
		return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
	}
}