<?php
namespace frontend\modules\admin\services;
use \common\models;
use Yii;
/**
 *公共静态类
 * create by chris
 *
 */
class Func{
    public static function Callback($callback,$json){
        return $callback."(".$json.")";
    }
    public static function registerUser($mobile,$validateCode,$password){
        $sms = new \common\models\Sms();
        if(!\common\models\User::findByUsername($mobile)&&self::isMobile($mobile)&&$sms->isVildateCode($validateCode,$mobile)){
            $user = new \common\models\User;

            $user->username = 'qdf_'.self::random(4,1).substr(md5($mobile),8,8);
            $user->mobile = $mobile;
            $user->password_hash = Yii::$app->security->generatePasswordHash($password);
            $user->generateAuthKey();
            $user->save();
            return true;
        }
        return false;
    }

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

    public  static function isMobile($mobile){
        $reg = '/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/';
		if(!preg_match($reg, $mobile)){
			return false;
		}else{
            return true;
        }
    }

    public static function getCityByProvince($provinceId){
        $res = models\City::findAll(['fatherID'=>$provinceId]);

        $city = [];
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
        $strLeft =  '<i class="fill"></i>';
        $strRight =  '<i class="nofill"></i>';
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

    public static function getProduct($category,$id){
        $product = false;
        if($category == 1){
            $product = \common\models\FinanceProduct::findOne(['id'=>$id,'category'=>$category]);
        }else if(in_array($category,[2,3])){
            $product = \common\models\CreditorProduct::findOne(['id'=>$id,'category'=>$category]);
        }

        return $product;
    }
}


