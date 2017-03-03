<?php
namespace frontend\services;
use \common\models;
use \common\models\Certification;
use Yii;
/**
 *公共静态类
 * create by chris
 *
 */
class Func{
    public static $category = ['1'=>'融资','2'=>'清收','3'=>'诉讼'];
    public static $listMenu = ['0'=>'待发布','1'=>'已发布','2'=>'处理中','3'=>'已终止','4'=>'已结案'];
    public static $case = ['0'=>'一审','1'=>'二审','2'=>'再审','3'=>'执行'];
    public static $inProvince = [
        '310000',
        '210000',
    ];
    public static $Status = [
        '1'=>[
            ''=>'请选择',
            '1'=>'尽职调查',
            '2'=>'公证',
            '3'=>'抵押',
            '4'=>'放款',
            '5'=>'返点',
            '6'=>'其他',
        ],
        '2'=>[
            ''=>'请选择',
            '1'=>'电话',
            '2'=>'上门',
            '3'=>'面谈',
        ],
        '3'=>[
            ''=>'请选择',
            '1'=>'债权人上传处置资产',
            '2'=>'律师接单',
            '3'=>'双方洽谈',
            '4'=>'向法院起诉(财产保全)',
            '5'=>'整理诉讼材料',
            '6'=>'法院立案',
            '7'=>'向当事人发出开庭传票',
            '8'=>'开庭前调解',
            '9'=>'开庭',
            '10'=>'判决',
            '11'=>'二次开庭',
            '12'=>'二次判决',
            '13'=>'移交执行局申请执行',
            '14'=>'执行中提供借款人的财产线索',
            '15'=>'调查（公告）',
            '16'=>'拍卖',
            '17'=>'流拍',
            '18'=>'拍卖成功',
            '19'=>'付费',
        ]
    ];

    public static function CurlGet($url){
        //初始化
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        return $output;
    }

    public static function CurlPost($url,$data = []){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

    public static function CurlPostForAPI($url,$data = []){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_REFERER, 'http://wx.zcb2016.com');
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $return_str = curl_exec($curl);
        curl_close($curl);

        return $return_str;
    }

    public static function XmlToArray($xml){
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches)){
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++){
                $subxml= $matches[2][$i];
                $key = $matches[1][$i];
                if(preg_match( $reg, $subxml )){
                    $arr[$key] = self::XmlToArray( $subxml );
                }else{
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }

    public static function random($length = 6 , $numeric = 0) {
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        if($numeric) {
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
            $max = strlen($chars) - 1;
            for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }

    public static function isTel($val){
        $reg = '/^\d{1,}$/';
        if(!preg_match($reg, $val)){
            return false;
        }else{
            return true;
        }
    }public static function isCardNo($val){
        $reg = '/(^\d{15}$)|(^\d{17}([0-9]|X)$)/';
        if(!preg_match($reg, $val)){
            return false;
        }else{
            return true;
        }
    }

    public static function isEmail($val){
        $reg = '/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/';
        if(!preg_match($reg, $val)){
            return false;
        }else{
            return true;
        }
    }

    public static function isNet($val){
        $reg = '/^((https?|ftp|news):\/\/)?([a-z]([a-z0-9\-]*[\.。])+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)|(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))(\/[a-z0-9_\-\.~]+)*(\/([a-z0-9_\-\.]*)(\?[a-z0-9+_\-\.%=&]*)?)?(#[a-z][a-z0-9_]*)?$/';
        if(!preg_match($reg, $val)){
            return false;
        }else{
            return true;
        }
    }

    public static function isZhiyezhenghao($val){
        $reg = '/^\d{17}$/';
        if(!preg_match($reg, $val)){
            return false;
        }else{
            return true;
        }
    }

    public static function isZhizhao($val){
        $reg = '/^\d{15}$/';
        if(!preg_match($reg, $val)){
            return false;
        }else{
            return true;
        }
    }

    public  static function isMobile($mobile){
        $reg = '/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/';
		if(!preg_match($reg, $mobile)){
			return false;
		}else{
            return true;
        }
    }

    public static function getProvinceNameById($id){
        return Yii::$app->db->createCommand("select province from zcb_province where provinceID = '{$id}'")->queryScalar();
    }

    public static function getCityNameById($id){
        return Yii::$app->db->createCommand("select city from zcb_city where cityID = '{$id}'")->queryScalar();
    }

    public static function getAreaNameById($id){
        return Yii::$app->db->createCommand("select area from zcb_area where areaID = '{$id}'")->queryScalar();
    }

    public static function getCarBrand($id){
        return Yii::$app->db->createCommand("select name from zcb_brand where id = '{$id}'")->queryScalar();
    }
    public static function getCarAudi($id){
        return Yii::$app->db->createCommand("select name from zcb_audi where id = '{$id}'")->queryScalar();
    }

    public static function getProvince(){
        $res = models\Province::find()->all();

        $province = [''=>'请选择省份'];
        foreach($res as $r){
            //if(in_array($r->provinceID,self::$inProvince))
                $province[$r->provinceID] = $r->province;
        }

        return $province;
    }

    public static function getCityByProvince($provinceId){
        $res = models\City::findAll(['fatherID'=>$provinceId]);

        $city = [''=>'请选择城市'];
        foreach($res as $r){
            $city[$r->cityID] = $r->city;
        }

        return $city;
    }

    public static function getDistrictByCity($cityId){
        $res = models\Area::findAll(['fatherID'=>$cityId]);

        $area = [''=>'请选择区域'];
        foreach($res as $r){
            $area[$r->areaID] = $r->area;
        }

        return $area;
    }

    public static function getBrand(){
        $res = models\Brand::find()->all();

        $car = [''=>'请选择车品牌'];
        foreach($res as $r){
            //if(in_array($r->provinceID,self::$inProvince))
            $car[$r->id] = $r->name;
        }

        return $car;
    }

    public static function getAudiByBrand($brandId){
        $res = models\Audi::findAll(['pid'=>$brandId]);

        $audi = [''=>'请选择车系'];
        foreach($res as $r){
            $audi[$r->id] = $r->name;
        }

        return $audi;
    }

    public static function getNextCode($tableName,$prefix,$field='code',$time='create_time'){
        $sql = "select {$field} from {$tableName} where {$time} BETWEEN '".strtotime(date('Y-m-d 00:00:00'))."' and '".strtotime(date('Y-m-d 23:59:59'))."'  order by {$field} desc";
        $code = \Yii::$app->db->createCommand($sql)->queryScalar();
        $code = $prefix.date('Ymd').str_pad((strval(substr($code?$code:0,-4,4)) + 1),4,0,STR_PAD_LEFT);
        return $code;
    }

    public static function createCatCode($cat = 1){
        $code = '';
        if($cat == 1){
            $code = self::getNextCode('zcb_finance_product','RZ','code');
        }else if(in_array($cat,[2,3])){
            $code = self::getNextCode('zcb_creditor_product','BX','code');
        }else if(in_array($cat,[4])){
			$code = self::getNextCode('zcb_protectright','BQ','number');
        }else if(in_array($cat,[5])){
			$code = self::getNextCode('zcb_policy','BH','orderid','created_at');
        }else if(in_array($cat,[6])){
			$code = self::getNextCode('zcb_product','BX','number','create_at');
        }
        return $code;
    }

    public static function evaluateNumber($number,$pc=false){
        $number = $number>5?5:$number;
        $str = '';
		if($pc){
			$strLeft =  '<li class="bright"></li>';
			$strRight =  '<li class="gray"></li>';
		}else{
			$strLeft =  '<span class="keep_ig18"></span>';
			$strRight =  '<span class="keep_ig17"></span>';
			
		}
        
        for($i=0;$i<$number;$i++){
            $str .= $strLeft;
        }
        for($i=0;$i<5-$number;$i++){
            $str .= $strRight;
        }
        return $str;
    }

    public static function addMessage($num,$uri,$arr,$belonguid=''){
        $messageModel = new \common\models\Message();
        $messageModel->uid = \Yii::$app->user->getId();
        if($belonguid == ''){$belonguid = \Yii::$app->user->getId();}
        $messageModel->belonguid = $belonguid;
        $messageModel->title = \frontend\configs\MessageConfig::$Message[$num]['title'];
        $messageModel->content = \frontend\services\Func::replaceMessage(\frontend\configs\MessageConfig::$Message[$num]['content'],$arr);
        $messageModel->isRead = 0;
        $messageModel->uri =$uri;
        $messageModel->create_time = time();
        
        $messageModel->save();
    }


    public static function addNewMessage($title,$content,$belonguid=''){
        $messageModel = new \common\models\Message();
        $messageModel->uid = \Yii::$app->user->getId();
        if($belonguid == ''){$belonguid = \Yii::$app->user->getId();}
        $messageModel->belonguid = $belonguid;
        $messageModel->title = $title;
        $messageModel->content = $content;
        $messageModel->isRead = 0;
        $messageModel->uri ='1';
        $messageModel->create_time = time();

        $messageModel->save();
    }

    public static function replaceMessage($str,$arr = []){
        foreach($arr as $k=>$v){
            $str = str_replace($k,$v,$str);
        }
        return $str;
    }

    public static function HideStrRepalceByChar($str,$char,$left=1,$right=1){
        $str = (string)$str;
        $strlen     = mb_strlen($str, 'utf-8');
        $left =$left+$right>=$strlen?$strlen/2:$left;
        $right =$left+$right>=$strlen?$strlen/2-1:$right;
        $firstStr     = mb_substr($str, 0, $left, 'utf-8');
        $lastStr     = mb_substr($str, -$right, $right, 'utf-8');
        return $strlen <= 2 ? $firstStr . str_repeat($char, mb_strlen($str, 'utf-8') - $left-$right) : $firstStr . str_repeat($char, $strlen - $left-$right) . $lastStr;
    }

    public static function Substr($str,$left,$char){
        $strlen     = mb_strlen($str, 'utf-8');

        if($strlen<$left){
            $str = mb_substr($str, 0, $strlen, 'utf-8');
        }else{
            $str = mb_substr($str, 0, $left, 'utf-8');
        }

        return $str.$char;
    }
	
	 public static function getSubstrs($str){
        $strs =  preg_replace('/\d/',"*",$str);
        return $strs;
    }

    public static function getProduct($category,$id){
        $product = false;
        if($category == 1){
            $product = \common\models\FinanceProduct::findOne(['id'=>$id,'category'=>$category]);
        }else if(in_array($category,[2,3])){
            $product = \common\models\CreditorProduct::findOne(['id'=>$id,'category'=>$category]);
        }

        return $product;
    }

    public static function getCertification(){
        $uid = Yii::$app->user->getId();
        $user = \common\models\User::findOne(['id'=>$uid]);
        $certificationFromSelf = Certification::findOne(['uid'=>$uid]);
        if(!isset($certificationFromSelf['id'])&&!$user['pid']){
            return null;
        }else if(isset($certificationFromSelf['id'])){
            return $certificationFromSelf;
        }else{
            $certificationFromParent = Certification::findOne(['uid'=>$user['pid']]);
            if(isset($certificationFromParent['id']))return $certificationFromParent;
            else return null;
        }
    }

    public static function hasProduct(){
        $uid = Yii::$app->user->getId();
        if(isset(models\FinanceProduct::findOne(['uid'=>$uid])->id)||isset(models\CreditorProduct::findOne(['uid'=>$uid])->id)){
            return true;
        }else{
            return false;
        }
    }

    public static function agree($time)
    {
        $times = floor((time() - $time) / 3600 / 24);
        if($times == 1){
              return 1;
        }else if($times > 1 && $times <= 3 ) {
            return 2;
        }else if($times > 3 && $times >= 5){
            return 3;
        }else{
            return 4;
        }

    }



    public static function getCertifications($uid){
        $user = \common\models\User::findOne(['id'=>$uid]);
        $certificationFromSelf = Certification::findOne(['uid'=>$uid]);
        if(!isset($certificationFromSelf->id)&&!$user->pid){
            return null;
        }else if(isset($certificationFromSelf->id)){
            return $certificationFromSelf->toArray();
        }else{
            $certificationFromParent = Certification::findOne(['uid'=>$user->pid]);
            if(isset($certificationFromParent->id))return $certificationFromParent->toArray();
            else return null;
        }
    }
	
	public static function fillterCertification($certification){
		$data =[
			'category'=>$certification['category'],
			'name'=>isset($certification['name'])?\frontend\services\Func::HideStrRepalceByChar($certification['name'],'*',2,2):'',
			'cardno'=>isset($certification['cardno'])?\frontend\services\Func::HideStrRepalceByChar($certification['cardno'],'*',4,4):'',
			'cardimgimg'=>$certification['cardimgimg'],
			'cardimg'=>str_replace("'","",$certification['cardimg']),
			'contact'=>isset($certification['contact'])?\frontend\services\Func::HideStrRepalceByChar($certification['contact'],'*',1,0):'',
			'mobile'=>isset($certification['mobile'])?\frontend\services\Func::HideStrRepalceByChar($certification['mobile'],'*',3,4):'',
			'address'=>isset($certification['address'])?\frontend\services\Func::HideStrRepalceByChar($certification['address'],'*',8,0):'',
			'enterprisewebsite'=>$certification['enterprisewebsite'],
			'email'=>isset($certification['email'])?\frontend\services\Func::HideStrRepalceByChar($certification['email'],'*',3,10):'',
			'casedesc'=>$certification['casedesc'],
			'state'=>$certification['state'],
			'uid' => $certification['uid'],
		];
		return $data;
	}
	
	
	public static function genRandomString($lens){  
		$chars = array("0", "1", "2","3", "4", "5", "6", "7", "8", "9","0", "1", "2","3", "4", "5", "6", "7", "8", "9","0", "1", "2","3", "4", "5", "6", "7", "8", "9","0", "1", "2","3", "4", "5", "6", "7", "8", "9","0", "1", "2","3", "4", "5", "6", "7", "8", "9", "0", "1", "2","3", "4", "5", "6", "7", "8", "9");
		$charsLen = count($chars) - 1;
		shuffle($chars);
		$output = "";
		for ($i=0; $i<$lens; $i++){
			$output .= $chars[mt_rand(0, $charsLen)];
		}
		return $output;
	} 
	public static function getTxNo16(){
		$timePrefix = date("ymdH"); //yyMMddHH
		$randomString = self::genRandomString(8);
		return $timePrefix.$randomString;
	}
	public static function getTxNo20(){
		$timePrefix = date("ymdHi"); //yyMMddHHmm
		$randomString = self::genRandomString(10);
		return $timePrefix.$randomString;
	}
	public static function curl($type = 1,$post_data = array()){
		$ch = curl_init();
		if($type == 1){
			curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxad34114f5aaae230&secret=d1700216db148d6f0b0656c8f90dfc1e");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
		}else{
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);  
			curl_setopt($ch,CURLOPT_BINARYTRANSFER,1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			$post_data = http_build_query($post_data);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
			curl_setopt($ch, CURLOPT_URL, 'http://www.zcb2016.com/admin/wx/sendmsg');
		}
		$output  = curl_exec($ch);
		curl_close($ch);
		return $output ;
	}
	
	
    public static function getQingshouTotal(){
		$key = 'QingshouTotal';
		$cache = Yii::$app->cache;
		$sum = $cache->get($key);
		if(!empty($sum)){
			return $sum;
		}
		// $sum = (new \yii\db\Query())
			// ->from('zcb_creditor_product')
			// ->where(['category' => '2'])
			// ->sum('money');
		$sum = (new \yii\db\Query())
			->from('zcb_product')
			->where(['validflag' => '1','status'=>['10','20','30','40']])
			->sum('account');
		
		$sum = round($sum,2);
		$cache->set($key, $sum, 30);
		return $sum;
			
	}
	public static function getSusongTotal(){
		$key = 'SusongTotal';
		$cache = Yii::$app->cache;
		$sum = $cache->get($key);
		if(!empty($sum)){
			return $sum;
		}
		$sum = (new \yii\db\Query())
			->from('zcb_creditor_product')
			->where(['category' => '3'])
			->sum('money');
		
		$sum = round($sum*10000,2);
		$cache->set($key, $sum, 30);
		return $sum;
			
	}
	
	public static function getBaohanTotal(){
		$key = 'BaohanTotal';
		$cache = Yii::$app->cache;
		$sum = $cache->get($key);
		if(!empty($sum)){
			return $sum;
		}
		
		$sum = (new \yii\db\Query())
			->from('zcb_policy')
			->sum('money');
		$sum = round($sum,2);
		$cache->set($key, $sum, 30);
		return $sum;
			
	}
	
	public static function getBaoquanTotal(){
		$key = 'BaoquanTotal';
		$cache = Yii::$app->cache;
		$sum = $cache->get($key);
		if(!empty($sum)){
			return $sum;
		}
		$sum = (new \yii\db\Query())
			->from('zcb_protectright') 
			->sum('account');
		$sum = round($sum,2);
		$cache->set($key, $sum, 30);
		return $sum;
	}

	 public static function isDecimals($val){
        $reg = '/^[1-9]\d*(\.\d+)?$/';
        if(!preg_match($reg, $val)){
            return false;
        }else{
            return true;
        }
    }
	public static function permutations($arr1,$arr2=[]){
		$arr2=$arr2?:$arr1;
		if($arr1&&count(explode(",",$arr1[0])) == count($arr2)){
			return [];
		}
		$temp=$arr2;
		$reset = $arr1==$arr2;
		foreach($arr1 as $k1=>$v1){
			$temp=$reset?$temp:$arr2;
			foreach(explode(",",$v1) as $t){
				$key = array_search($t,$temp);
				unset($temp[$key]);
			}
			foreach($temp as $k2=>$v2){
				$return[]=$v1.",".$v2;
				$return[]=$v2.",".$v1;
			}
		}
		$return = array_unique($return);
		return $reset?array_merge($arr1,$return,self::permutations($return,$arr2)):array_merge($return,self::permutations($return,$arr2));
	}
	
	public static function searchParamsRoute($url = "",$newparams){
		$return=[$url];
		$oldparams=Yii::$app->request->queryParams;
		$return = array_merge($return,$oldparams);
		
		$true = 0;
		foreach($newparams as $k=>$v){
			if(isset($return[$k])&&$return[$k]==$v){
				$true++;
			}else{
				$return[$k]=$v;
			}
			
		}
		$hasParams = count($newparams)==$true?true:false;
		return $hasParams?false:\yii\helpers\Url::toRoute($return);
	}
	
	/**
	 * [asyncExecute PHP异步执行任务]
	 * @param  string $url       执行任务的url地址
	 * @param  array  $post_data 需要post提交的数据POST
	 * @param  array  $cookie    cookie数据用于登录等的设置
	 * @return boole
	 */
	public static function asyncExecute($url, $post_data = array(), $cookie = array()) {
		$method = "GET";
		$url_array = parse_url($url);
		$port = isset($url_array['port']) ? $url_array['port'] : 80;

		$fp = fsockopen($url_array['host'], $port, $errno, $errstr, 30);
		if (!$fp) {
			return FALSE;
		}
		$getPath = isset($url_array['path']) ? $url_array['path'] : '/';
		if (isset($url_array['query'])) {
			$getPath .= "?" . $url_array['query'];
		}
		if (!empty($post_data)) {
			$method = "POST";
		}
		$header = $method . " /" . $getPath;
		$header .= " HTTP/1.1\r\n";
		$header .= "Host: " . $url_array['host'] . "\r\n";

		$header .= "Connection: Close\r\n";
		if (!empty($cookie)) {
			$_cookie = strval(NULL);
			foreach ($cookie as $k => $v) {
				$_cookie .= $k . "=" . $v . "; ";
			}
			$cookie_str = "Cookie: " . base64_encode($_cookie) . " \r\n";
			$header .= $cookie_str;
		}
		if (!empty($post_data)) {
			$_post = strval(NULL);
			$atComma = '';
			foreach ($post_data as $k => $v) {
				$_post .= $atComma . $k . "=" . $v;
				$atComma = '&';
			}
			$post_str = "Content-Type: application/x-www-form-urlencoded\r\n";
			$post_str .= "Content-Length: " . strlen($_post) . "\r\n";
			$post_str .= "\r\n".$_post . "\r\n";
			$header .= $post_str;
		}
		$header .= "\r\n";
		fwrite($fp, $header);
		fclose($fp);
		return true;
	}
	public static function truncate_utf8_string($string, $length, $etc = '...')
	{
		$result = '';
		$string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
		$strlen = strlen($string);
		for ($i = 0; (($i < $strlen) && ($length > 0)); $i++){
			if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')){
				if ($length < 1.0){
					break;
				}
				$result .= substr($string, $i, $number);
				$length -= 1.0;
				$i += $number - 1;
			}else{
				$result .= substr($string, $i, 1);
				$length -= 0.5;
			}
		}
		$result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
		if ($i < $strlen){
		$result .= $etc;
		}
		return $result;
	}
	
	public static function mb_str_pad($string,$length,$pad_string="",$pad_type=STR_PAD_RIGHT){
		$mbstrlen = mb_strlen($string,"utf-8");
		$strlen = strlen($string);
		
		// var_dump($string);
		// var_dump($strlen);
		// var_dump($mbstrlen);
		// var_dump(($strlen-$mbstrlen)/2*3);
		
		if($mbstrlen==$strlen){
			$mbstrlen = ceil($mbstrlen/2);
		}else if($mbstrlen<$strlen){
			// var_dump($strlen-$mbstrlen);
			$mbstrlen = $mbstrlen- ceil(($strlen - ($strlen-$mbstrlen)/2*3)/3);
		}
		
		
		// var_dump($mbstrlen);
		// exit;
		
		if($length<=$mbstrlen)return $string;
		$diff = $length-$mbstrlen;
		$brfore="";
		$after = "";
		switch ($pad_type){
			case STR_PAD_LEFT :
				for($i=1;$i<=$diff;$i++){
					$brfore.=$pad_string;
				}
				break;
			case STR_PAD_RIGHT:
				for($i=1;$i<=$diff;$i++){
					$after.=$pad_string;
				}
				break;
			case STR_PAD_BOTH :
				$leftdiff = floor($diff/2);
				for($i=1;$i<=$leftdiff;$i++){
					$brfore.=$pad_string;
				}
				$rightdiff = ceil($diff/2);
				for($i=1;$i<=$rightdiff;$i++){
					$after.=$pad_string;
				}
				break;
		}
		$string = $brfore.$string.$after;
		
		return $string;
		
	}
}


