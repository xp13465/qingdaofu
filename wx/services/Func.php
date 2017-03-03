<?php
namespace wx\services;
use \common\models;
use \common\models\Certification;
use Yii;
use yii\helpers\ArrayHelper;

/**
 *公共静态类
 * create by chris
 *
 */
class Func{
    public static $category = ['1'=>'','2'=>'清收','3'=>'诉讼'];
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
        $url = Yii::$app->params['wx'].$url;
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
    public static function isInt($val){
        $reg = '/^[1-9]\d*|0$/';
        if(!preg_match($reg, $val)){
            return false;
        }else{
            return true;
        }
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

    public static function getProvince(){
        $res = models\Province::find()->all();

        $province = [''=>'请选择'];
        foreach($res as $r){
            //if(in_array($r->provinceID,self::$inProvince))
                $province[$r->provinceID] = $r->province;
        }

        return $province;
    }


    public static function getCityByProvince($provinceId){
        $res = models\City::findAll(['fatherID'=>$provinceId]);

        $city = [''=>'请选择'];
        foreach($res as $r){
            $city[$r->cityID] = $r->city;
        }

        return $city;
    }

    public static function getDistrictByCity($cityId){
        $res = models\Area::findAll(['fatherID'=>$cityId]);

        $area = [''=>'请选择'];
        foreach($res as $r){
            $area[$r->areaID] = $r->area;
        }

        return $area;
    }

    public static function getNextCode($tableName,$prefix){
        $sql = "select code from {$tableName} where create_time BETWEEN '".strtotime(date('Y-m-d 00:00:00'))."' and '".strtotime(date('Y-m-d 23:59:59'))."'  order by code desc";
        $code = \Yii::$app->db->createCommand($sql)->queryScalar();
        $code = $prefix.date('Ymd').str_pad((strval(substr($code?$code:0,-4,4)) + 1),4,0,STR_PAD_LEFT);
        return $code;
    }

    public static function createCatCode($cat = 1){
        $code = '';
        if($cat == 1){
            $code = self::getNextCode('zcb_finance_product','RZ');
        }else if(in_array($cat,[2,3])){
            $code = self::getNextCode('zcb_creditor_product','BX');
        }
        return $code;
    }

    public static function evaluateNumber($number){
        $number = $number>5?5:$number;
        $str = '';
        $strLeft =  '<a href="#"><img src="/images/xing.png" height="15" width="17" alt="" /></a>';
        $strRight =  '<a href="#"><img src="/images/star.jpg" height="15" width="17" alt="" /></a>';
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
        if(!isset($certificationFromSelf->id)&&!$user->pid){
            return null;
        }else if(isset($certificationFromSelf->id)){
            return $certificationFromSelf;
        }else{
            $certificationFromParent = Certification::findOne(['uid'=>$user->pid]);
            if(isset($certificationFromParent->id))return $certificationFromParent;
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

    public static function ArchivementRate($object){
        if(is_array($object)&&!empty($object)){
            $arcCount = 0;
            $totalCount = count($object);
            foreach ($object as $o){
                if($o != ''){
                    $arcCount++;
                }
            }
            return number_format($arcCount/$totalCount, 2, '.', '');
        }else{
            return false;
        }
    }

    /**
     * 系统加密方法
     * @param string $data 要加密的字符串
     * @param string $key  加密密钥
     * @param int $expire  过期时间 单位 秒
     * @return string
     */
    public static function otp_encrypt($data, $key = '', $expire = 0) {
        $key  = md5(empty($key) ? '&*@^@#^&*!@&#!@*((&@#()!@' : $key);
        $data = base64_encode($data);
        $x    = 0;
        $len  = strlen($data);
        $l    = strlen($key);
        $char = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($key, $x, 1);
            $x++;
        }
        $str = sprintf('%010d', $expire ? $expire + time():0);

        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
        }
        return str_replace(array('+','/','='),array('-','_',''),base64_encode($str));
    }

    /**
     * 系统解密方法
     * @param  string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
     * @param  string $key  加密密钥
     * @return string
     */
    public static function otp_decrypt($data, $key = ''){
        $key    = md5(empty($key) ? '&*@^@#^&*!@&#!@*((&@#()!@' : $key);
        $data   = str_replace(array('-','_'),array('+','/'),$data);
        $mod4   = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        $data   = base64_decode($data);
        $expire = substr($data,0,10);
        $data   = substr($data,10);
        if($expire > 0 && $expire < time()) {
            return '';
        }
        $x      = 0;
        $len    = strlen($data);
        $l      = strlen($key);
        $char   = $str = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1))<ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            }else{
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return base64_decode($str);
    }


}


