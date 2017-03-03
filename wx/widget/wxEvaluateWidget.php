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

class wxEvaluateWidget extends Widget{
    public $pid;
    public $evaluate;
    public $creditor;

    public function init(){
        parent::init();
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/apply/evaluatewidget'),['pid'=>$this->pid,'token'=>Yii::$app->session->get('user_token'),'limit'=>5]);
        $arr = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $this->evaluate = $arr['result']['evaluate'];
            $this->creditor = $arr['result']['creditor'];
        }
    }

    public function run(){
        return $this->render('wxEvaluateWidget',['evaluate'=>$this->evaluate,'creditor'=>$this->creditor,'pid'=>$this->pid]);
    }
}