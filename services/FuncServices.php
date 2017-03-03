<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 2016/4/8
 * Time: 17:22
 */

namespace services;
use yii;
class FuncServices{
    public static $category = ['1'=>'融资','2'=>'清收','3'=>'诉讼'];
    public static $listMenu = ['0'=>'待发布','1'=>'已发布','2'=>'处理中','3'=>'已终止','4'=>'已结案'];

    public static function sendMail($subject,$html,$to,$layouthtml='layouts\html',$layouttext='layouts\text'){
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($to);
        $mail->setSubject($subject);
        //$mail->setTextBody('zheshisha ');   //发布纯文字文本
        $mail->setHtmlBody($html);    //发布可以带html标签的文本
        return $mail->send();
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
}