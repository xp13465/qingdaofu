<?php
namespace frontend\modules\wap\services;
use \common\models;
use \common\models\Certification;
use Yii;
use yii\data\Pagination;
use yii\filters\PageCache;
use yii\helpers\ArrayHelper;

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
    public static function addMessagesPerType($title,$content,$type,$params,$belonguid=''){
        $messageModel = new \common\models\Message();
        $messageModel->uid = Yii::$app->session->get('user_id');
        if($belonguid == ''){$belonguid = Yii::$app->session->get('user_id');}
        $messageModel->belonguid = $belonguid;
        $messageModel->title = $title;
        $messageModel->content = $content;
        $messageModel->isRead = 0;
        $messageModel->type = $type;
        $messageModel->uri = '';
        $messageModel->params = $params;
        $messageModel->create_time = time();
        $messageModel->save();
    }

    public static function addNewMessage($title,$content,$belonguid='',$uri = '#'){
        $messageModel = new \common\models\Message();
        $messageModel->uid = \Yii::$app->user->getId();
        if($belonguid == ''){$belonguid = \Yii::$app->user->getId();}
        $messageModel->belonguid = $belonguid;
        $messageModel->title = $title;
        $messageModel->content = $content;
        $messageModel->isRead = 0;
        $messageModel->uri = $uri;
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

    public static function getProduct($category,$id,$uid = 0,$is_del = 0){
        $product = false;

        if($category == 1){
            if($uid == 0)$product = \common\models\FinanceProduct::findOne(['id'=>$id,'category'=>$category,'is_del'=>$is_del]);
            else $product = \common\models\FinanceProduct::findOne(['id'=>$id,'category'=>$category,'uid'=>$uid,'is_del'=>$is_del]);
        }else if(in_array($category,[2,3])){
            if($uid == 0)$product = \common\models\CreditorProduct::findOne(['id'=>$id,'category'=>$category,'is_del'=>$is_del]);
            else $product = \common\models\CreditorProduct::findOne(['id'=>$id,'category'=>$category,'uid'=>$uid,'is_del'=>$is_del]);
        }

        return $product;
    }
	
	public static function getProducts($category,$id,$uid = 0,$is_del = [0,1]){
        $product = false;

        if($category == 1){
            if($uid == 0)$product = \common\models\FinanceProduct::findOne(['id'=>$id,'category'=>$category,'is_del'=>$is_del]);
            else $product = \common\models\FinanceProduct::findOne(['id'=>$id,'category'=>$category,'uid'=>$uid,'is_del'=>$is_del]);
        }else if(in_array($category,[2,3])){
            if($uid == 0)$product = \common\models\CreditorProduct::findOne(['id'=>$id,'category'=>$category,'is_del'=>$is_del]);
            else $product = \common\models\CreditorProduct::findOne(['id'=>$id,'category'=>$category,'uid'=>$uid,'is_del'=>$is_del]);
        }

        return $product;
    }
	

    public static function getCertification(){
        $uid = Yii::$app->session->get('user_id');
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

    public static function ArchivementRate($object){
        switch ($object['category']){
            case 1:
                $archivement = array(
                    1 => $object['name'],
                    2 => $object['cardno'],
                    3 => $object['mobile'],
                    4 => $object['email'],
                    5 => $object['casedesc'],
                    6 => $object['cardimg'],
                );
                break;
            case 2:
                $archivement = array(
                    1 => $object['name'],
                    2 => $object['mobile'],
                    3 => $object['cardno'],
                    4 => $object['email'],
                    5 => $object['contact'],
                    6 => $object['casedesc'],
                    7 => $object['cardimg'],
                );
                break;
            case 3:
                $archivement = array(
                    1 => $object['name'],
                    2 => $object['mobile'],
                    3 => $object['cardno'],
                    4 => $object['email'],
                    5 => $object['contact'],
                    6 => $object['casedesc'],
                    7 => $object['cardimg'],
                    8 => $object['enterprisewebsite'],
                    9 => $object['address']
                );
                break;
            default:
                break;
        }
        if(is_array($archivement)&&!empty($archivement)){
            $arcCount = 0;
            $totalCount = count($archivement);
            foreach ($archivement as $o){
                if($o != ''){
                    $arcCount++;
                }
            }
            return number_format($arcCount/$totalCount, 2, '.', '');
        }else{
            return false;
        }
    }
    public static function hasProducts(){
        $uid = Yii::$app->session->get('user_id');
        if(isset(models\FinanceProduct::findOne(['uid'=>$uid])->id)||isset(models\CreditorProduct::findOne(['uid'=>$uid])->id)){
            return true;
        }else{
            return false;
        }
    }

    public static function isTel($val){
        $reg = '/^\d{1,}$/';
        if(!preg_match($reg, $val)){
            return false;
        }else{
            return true;
        }
    }

    public static function isCardNo($val){
        $reg = '/(^\d{15}$)|(^\d{17}([0-9]|X)$)/';
        if(!preg_match($reg, $val)){
            return false;
        }else{
            return true;
        }
    }

    public static function isDecimal($val){
        $reg = '/^[1-9]\d*(\.\d+)?$/';
        if(!preg_match($reg, $val)){
            return false;
        }else{
            return true;
        }
    }

    public static function isInt($val){
        $reg = '/^[1-9]\d*|0$/';
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

    public static function getProductByStatus($status = '', $page = 1,$uid='', $limit = 10){
        $uid = $uid==''?Yii::$app->session->get('user_id'):$uid;

        $where = ' 1 ';
        if(ArrayHelper::isIn($status,[0,1,2,3,4])){
            $where .= " AND progress_status = '{$status}' ";
        }
        if(is_numeric($uid)){
            $where .= " AND uid = '{$uid}' ";
        }else{
            $where .= " and false ";
        }
        $limitstr= "";
        if(is_numeric($page)&&is_numeric($limit)){
            $page = $page<=1?1:$page;
            $limit = $limit<=0?10:$limit;
            $limitstr = " limit ".($page-1)*$limit.",".$limit;
        }
        $sql = "select id,uid,seatmortgage,province_id,city_id,district_id,category,code,create_time,modify_time,money,progress_status,applyclose,applyclosefrom from zcb_finance_product where {$where} and is_del = 0 union
               select id,uid,seatmortgage,province_id,city_id,district_id,category,code,create_time,modify_time,money,progress_status,applyclose,applyclosefrom from zcb_creditor_product where {$where} and is_del = 0 order by progress_status asc,modify_time desc";
        $rows = \Yii::$app->db->createCommand($sql.$limitstr)->query();
        return ['rows'=>$rows,'page'=>$page];
    }

    public static function getApply($product_id,$category,$status=1){
        if(is_numeric($product_id)&&is_numeric($category)&&is_numeric($status)&&ArrayHelper::isIn($category,[1,2,3])) {
            $apply = models\Apply::findOne(['product_id'=>$product_id,'category'=>$category,'app_id'=>$status]);
            if(isset($apply->id))return $apply;else return false;
        }else{
            return false;
        }
    }

    //安卓债权人和债务人数据处理
    public static function getDataprocessing($string){
        $arr = array();
        $data = explode(',',$string);
        foreach ($data as $v){
            if(empty($v)){
                continue;
            }
            $segmentation = explode('=',$v);
            $second_segmentation= explode('-', $segmentation[0]);
            $arr[$second_segmentation[1]][$second_segmentation[0]] = $segmentation[1];
        }
        for($i=0;$i<count($arr);$i++){
            if(isset($arr[$i]['creditorcardimage']) || isset($arr[$i]['borrowingcardimage'])){
                if(isset($arr[$i]['creditorcardimage'])){
                    $tmpArr = explode(':',$arr[$i]['creditorcardimage']);
                }else{
                    $tmpArr = explode(':',$arr[$i]['borrowingcardimage']);
                }
                $imgArr = '';
                for ($j= 0;$j<count($tmpArr);$j++){
                    if(empty($tmpArr[$j])){
                        continue;
                    }
                    if(substr($tmpArr[$j],0,8) == "/upload/"){
                        $filename = substr($tmpArr[$j],8);
                    }else {
                        $img_file = base64_decode(trim($tmpArr[$j]));
                        $filename = '0.' . time() . mt_rand(8, time()) . '.jpg';//要生成的图片名字
                        if (empty($img_file)) $xmlstr = file_get_contents('php://input');
                        $jpg = trim($img_file);//得到post过来的二进制原始数据
                        $file = fopen("upload/" . $filename, "w");//打开文件准备写入
                        fwrite($file, $jpg);//写入
                        fclose($file);
                    }
                     if(count($arr[$i])==1){
                        $imgArr .= "'".'/upload/'.$filename."'".',';
                    }else{
                        $imgArr .= "'".'/upload/'.$filename."'".',';
                    }
                    if(isset($arr[$i]['creditorcardimage'])){
                        $arr[$i]['creditorcardimage'] = substr($imgArr,0,-1);
                    }else{
                        $arr[$i]['borrowingcardimage'] =substr($imgArr,0,-1);
                    }
                }
            }else{
                continue;
            }
        }
        if(!empty($arr)) {
            return $arr;
        }else{
            return false;
        }
    }

    //安卓债权文件上传
    public static function getCreditors($string){
        $string = explode(',',$string);
        $imgArr = '';
        foreach ($string as $k=>$v){
            if(empty($v)){
                continue;
            }
            if(substr($v,0,8) == "/upload/"){
                $filename = substr($v,8);
            }else {
                $img_file = base64_decode(trim($v));
                $filename = '0.' . time() . rand(100000, 1000000) . '.jpg';//要生成的图片名字
                if (empty($img_file)) $xmlstr = file_get_contents('php://input');
                $jpg = $img_file;//得到post过来的二进制原始数据
                $file = fopen("upload/" . $filename, "w");//打开文件准备写入
                fwrite($file, $jpg);//写入
                fclose($file);
            }
            if (count($string) == 1) {
                $imgArr .= "'" . '/upload/' . $filename . "'" . ',';
            } else {
                $imgArr .= "'" . '/upload/' . $filename . "'" . ',';
            }
        }
        if(!empty($imgArr)) {
            return $imgArr = substr($imgArr,0,-1);
        }else{
            return false;
        }
    }

    //ios债权人和债务人数据处理
    public static function getDataprocessings($string){

        $arr = array();
        $data = explode(',',$string);
        foreach ($data as $v){
            if(empty($v)){
                continue;
            }
            $segmentation = explode('=',$v);
            $second_segmentation= explode('-', $segmentation[0]);
            $arr[$second_segmentation[1]][$second_segmentation[0]] = $segmentation[1];
        }
        for($i=0;$i<count($arr);$i++){
            if(isset($arr[$i]['creditorcardimage']) || isset($arr[$i]['borrowingcardimage'])){
                if(isset($arr[$i]['creditorcardimage'])){
                    $tmpArr = explode(':',$arr[$i]['creditorcardimage']);
                }else{
                    $tmpArr = explode(':',$arr[$i]['borrowingcardimage']);
                }
                $imgArr = '';
                for ($j= 0;$j<count($tmpArr);$j++){
                    if(empty($tmpArr[$j])){
                        continue;
                    }
                    if(substr($tmpArr[$j],0,8) == "/upload/"){
                        $filename = substr($tmpArr[$j],8);
                    }else{
                        $img_file = Func::hex2bin(trim($tmpArr[$j]));
                        $filename = '0.' . time() . mt_rand(8, time()) . '.jpg';//要生成的图片名字
                        if (empty($img_file)) $xmlstr = file_get_contents('php://input');
                        $jpg = trim($img_file);//得到post过来的二进制原始数据
                        $file = fopen("upload/" . $filename, "w");//打开文件准备写入
                        fwrite($file, $jpg);//写入
                        fclose($file);
                    }
                    if(count($arr[$i])==1){
                        $imgArr .= "'".'/upload/'.$filename."'".',';
                    }else{
                        $imgArr .= "'".'/upload/'.$filename."'".',';
                    }
                    if(isset($arr[$i]['creditorcardimage'])){
                        $arr[$i]['creditorcardimage'] = substr($imgArr,0,-1);
                    }else{
                        $arr[$i]['borrowingcardimage'] =substr($imgArr,0,-1);
                    }
                }
            }else{
                continue;
            }
        }
        if(!empty($arr)) {
            return $arr;
        }else{
            return false;
        }
    }

    //ios债权文件上传
    public static function getCreditor($string){
        $array = explode(',',$string);
        $imgArr = '';
        foreach($array as $key=>$value){
            if(empty($value)){
                continue;
            }
            if(substr($value,0,8) == "/upload/"){
                $filename = substr($value,8);
            }else {
                $file_data = Func::hex2bin(trim($value));
                $filename = '0.' . time() . mt_rand(8, time()) . '.jpg';//要生成的图片名字
                if (empty($file_data)) $xmlstr = file_get_contents('php://input');
                $jpg = $file_data;//得到post过来的二进制原始数据
                $file = fopen("upload/" . $filename, "w");//打开文件准备写入
                fwrite($file, $jpg);//写入
                fclose($file);
            }
            if (count($array) == 1) {
                $imgArr .= "'" . '/upload/' . $filename . "'" . ',';
            } else {
                $imgArr .= "'" . '/upload/' . $filename . "'" . ',';
            }
        }
        if(!empty($imgArr)) {
            return $imgArr = substr($imgArr,0,-1);
        }else{
            return false;
        }
    }


    //单张图片上传
    public static function getSinglepicture($string){
        if(empty($string)){
            die;
        }
        $imgArr = '';
        $filename = '0.'.time().rand(100000,1000000).'.jpg';//要生成的图片名字
        if(empty($string)) $xmlstr = file_get_contents('php://input');
        $jpg = $string;//得到post过来的二进制原始数据
        $file = fopen("upload/".$filename,"w");//将图片存入的路径
        fwrite($file,$jpg);//将内容写入到文件中
        fclose($file);
        if(empty( $imgArr)){
            $imgArr .= "'"."/upload/".$filename."'";
        } else {
            $imgArr .= "'"."/upload/".$filename."'";
        }
        if(!empty($imgArr)) {
            return $imgArr;
        }else{
            return false;
        }
    }

    //ios图片十六进制进行转换
    public static  function hex2bin($hexadecimal)
    {
        $byte = str_replace(' ','',$hexadecimal);   //处理数据替换
        $byte = str_ireplace("<",'',$byte);
        $byte = str_ireplace(">",'',$byte);
        if (!is_string($byte)) return null;
        $r='';
        for ($a=0; $a<strlen($byte); $a+=2) { $r.=chr(hexdec($byte{$a}.$byte{($a+1)}));}
        return $r;
    }
}


