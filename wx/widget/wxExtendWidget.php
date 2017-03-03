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

class wxExtendWidget extends Widget{
    public $id;
    public $category;
    public $product;
    public $apply;
    public $delay;
	public $uid;

    public function init(){
        parent::init();
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/wxextendwidget'),['id'=>$this->id,'category'=>$this->category,'token'=>Yii::$app->session->get('user_token')]);
        $arr = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $this->product = $arr['result']['product'];
            $this->delay   = $arr['result']['delay'];
			$this->uid = $arr['result']['uid'];		
        }
    }
    public function run(){
        return $this->render('wxExtendWidget',['product'=>$this->product,'delay'=>$this->delay,'uid'=>$this->uid]);
    }
}