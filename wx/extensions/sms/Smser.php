<?php
namespace wx\extensions\sms;

use common\models;
use wx\services\Func;
use yii;
use yii\log;
/**
 * Created by PhpStorm.
 * User: ZXTZ
 * Date: 2015/12/9
 * Time: 12:34
 */
class Smser
{
    private $target = 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/http/sendBatchMessage';
    private $account = [1=>['username'=>'sdk_zhixiangzc','password'=>'20160119'],2=>['username'=>'','password'=>''],];
    private $length = '4';
    private $numeric = '1';

    public function sendValidateCode($mobile,$type = 1){
        if(!Func::isMobile($mobile)){
            \Yii::getLogger()->log($mobile.'手机号错误',log\Logger::LEVEL_ERROR, $category = 'rhythmk');
            return false;
        }
        $validateCode = Func::random($this->length,$this->numeric);
        if($type == 2){
            $msgText = '修改密码：您正在进行手机修改密码操作，验证码是'.$validateCode.'，在30分钟内有效。修改成功后，可以用新密码登录。请勿向他人泄露验证码。【直向资产】';
        }else{
            $msgText = '验证码：'.$validateCode.'，有效时间5分钟，为了保护您的帐号安全，验证短信请勿转发给其他人。【直向资产】';
        }
        $postData = [
            'account'=>$this->account[1]['username'],
            'password'=>$this->account[1]['password'],
            'destmobile'=>$mobile,
            'msgText'=>$msgText,
            'sendDateTime'=>'',
        ];

        $postData = http_build_query($postData);

        $gets = Func::CurlPost($this->target,$postData);

        if($gets > 0){
            /*
             *保存验证码到数据库
             * */
            $sms = new models\Sms();
            $sms->code = $validateCode;
            $sms->create_time = time();
            $sms->is_use = 0;
            $sms->mobile = $mobile;
            $sms->save();
            \Yii::getLogger()->log($mobile.'手机号验证码发送成功：'.$validateCode,log\Logger::LEVEL_INFO, $category = 'rhythmk');
            return true;
        }else{
            \Yii::getLogger()->log($mobile.'手机号验证码发送失败：'.$validateCode,log\Logger::LEVEL_ERROR, $category = 'rhythmk');
            return false;
        }


    }

    public function sendMsgByMobile($mobile,$msg){
        if(!Func::isMobile($mobile)){
            \Yii::getLogger()->log($mobile.'手机号错误',log\Logger::LEVEL_ERROR, $category = 'rhythmk');
            return false;
        }

        $postData = [
            'account'=>$this->account[1]['username'],
            'password'=>$this->account[1]['password'],
            'destmobile'=>$mobile,
            'msgText'=>$msg." 【直向资产】",
            'sendDateTime'=>'',
        ];

        $postData = http_build_query($postData);

        $gets = Func::CurlPost($this->target,$postData);

        if($gets > 0){
            return true;
        }else{
            return false;
        }
    }
}