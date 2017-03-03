<?php
namespace frontend\services; 
use Yii; 
class WxMsg
{ 
	protected $appid;
	protected $secrect;
	 
	
	public function __construct($type='WXMSG')
	{ 
		$this->appid = Yii::$app->params[$type]['appid'];
        $this->secrect = Yii::$app->params[$type]['secrect'];
	}
	/**
	* 推送模板消息
	* 文本消息格式 
	* $template = array(
    *       'touser' => $openid,
    *       'msgtype' => 'text',
    *       'text' => array(
	*			'content' => $con
	*		)
    *   );
	* 图文消息格式   最多8条
	* $template = array(
	*        'touser' => $openid,
	*        'msgtype' => 'news',
	*        'news' => array(
	*			'articles' => array(
	*				[
	*					"title"=>"Happy Day我我",
	*					"description"=>"Is Really A Happy Day",
	*					"url"=>"http://www.zcb2016.com/",
	*					"picurl"=>"http://www.zcb2016.com/images/banner/banner1.png"
	*				],
	*				[
	*					"title"=>"Happy Day",
	*					"description"=>"Is Really A Happy Day",
	*					"url"=>"http://www.zcb2016.com/",
	*					"picurl"=>"http://www.zcb2016.com/images/banner/banner1.png"
	*				]
	*			)
	*		)
	*    ); 
	*/
	public  function sendMB($template,$type=''){
		if($type){
			if(in_array($type,['WXMSG','WXMSG2'])){
				$this->appid = Yii::$app->params[$type]['appid'];
				$this->secrect = Yii::$app->params[$type]['secrect'];
			}else{
				return [];
			}
		}
        $json_template = json_encode($template,JSON_UNESCAPED_UNICODE);
		$accessToken = $this->getToken($this->appid, $this->secrect); 
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $accessToken;
        $dataRes = $this->request_post($url, urldecode($json_template));
		$return = (array)json_decode($dataRes);
		if($return&&isset($return['errcode'])&&$return['errcode']=="40001"){
			Yii::$app->cache->delete($this->appid.'access_token');
			$accessToken = $this->getToken($this->appid, $this->secrect); 
			$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $accessToken;
			$dataRes = $this->request_post($url, urldecode($json_template));
		}
		
		return($return);
	}
	
	
	public  function sendM($template,$type=''){
		if($type){
			if(in_array($type,['WXMSG','WXMSG2'])){
				$this->appid = Yii::$app->params[$type]['appid'];
				$this->secrect = Yii::$app->params[$type]['secrect'];
			}else{
				return [];
			}
		}
        $json_template = json_encode($template,JSON_UNESCAPED_UNICODE);
		
		$accessToken = $this->getToken($this->appid, $this->secrect); 
		
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $accessToken;
        $dataRes = $this->request_post($url, urldecode($json_template));
		$return = (array)json_decode($dataRes);
		if($return&&isset($return['errcode'])&&$return['errcode']=="40001"){
			Yii::$app->cache->delete($this->appid.'access_token');
			$accessToken = $this->getToken($this->appid, $this->secrect); 
			$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $accessToken;
			$dataRes = $this->request_post($url, urldecode($json_template));
		}
		
		return($return);
	}
	/**
     * 发送post请求
     * @param string $url
     * @param string $param
     * @return bool|mixed
     */
    private function request_post($url = '', $param = ''){
        if (empty($url) || empty($param)) {
            return false;
        }
        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch); //运行curl
        curl_close($ch);
        return $data;
    }
	/**
     * @param $appid
     * @param $appsecret
     * @return mixed
     * 获取token
     */
    private function getToken($appid, $appsecret){
		$key = $appid.'access_token';
		$cache = Yii::$app->cache;
		$cacheToken = $cache->get($key);
		if(!empty($cacheToken)){
			return $cacheToken;
		}
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $appsecret;
        $token = $this->request_get($url); 
        $token = json_decode(stripslashes($token));
        $arr = json_decode(json_encode($token), true);
        $access_token = $arr['access_token']; 
		$cache->set($key, $access_token, 720);
        return $access_token;
    }
	/**
     * 发送get请求
     * @param string $url
     * @return bool|mixed
     */
    private  function request_get($url = ''){
        if (empty($url)) {
            return false;
        } 
		
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}