<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 2016/4/13
 * Time: 14:22
 */
namespace wx\widget;

use yii;
use yii\base;
use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use wx\services\Func;

class wxFooterWidget extends Widget{
     //public $footer;
    public $number;

    public function init(){
        parent::init();
        $json = Func::CurlPost(yii\helpers\Url::toRoute(['/wap/message/noread']),['token'=>Yii::$app->session->get('user_token')]);
        $json_arr = yii\helpers\Json::decode($json);
	
        if($json_arr['code'] == '0000'){
            $this->number = $json_arr['number'];
        }else{
			$this->number = 0;
		}
    }

    public function run(){
        return $this->render('wxFooterWidget',['number'=>$this->number]);
    }
}