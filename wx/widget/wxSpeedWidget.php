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

class wxSpeedWidget extends Widget{
    public $id;
    public $category;
    public $progress_status;
    public $uid;
    public $buid;
    public $disposing;
    public function init(){
        parent::init();
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/wxspeedwidget'),['id'=>$this->id,'category'=>$this->category,'token'=>Yii::$app->session->get('user_token')]);
        $arr = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $this->disposing = $arr['result']['disposing'];
            $this->buid       = $arr['result']['uid'];
        }
    }

    public function run(){
        return $this->render('wxSpeedWidget',['disposing'=>$this->disposing,'id'=>$this->id,'category'=>$this->category,'progress_status'=>$this->progress_status,'uid'=>$this->uid,'buid'=>$this->buid]);
    }
}