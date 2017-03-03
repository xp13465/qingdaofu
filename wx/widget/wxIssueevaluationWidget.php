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

class wxIssueevaluationWidget extends Widget{
    public $id;
    public $category;
    public $launchevaluation;
    public $creditor;

    public function init(){
        parent::init();
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/apply/userlists'),['id'=>$this->id,'token'=>Yii::$app->session->get('user_token'),'category'=>$this->category]);
        $arr = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $this->launchevaluation = $arr['result']['launchevaluation'];
            $this->creditor = $arr['result']['creditor'];
        }
    }

    public function run(){
        return $this->render('wxIssueevaluationWidget',['launchevaluation'=>$this->launchevaluation,'creditor'=>$this->creditor,'id'=>$this->id,'category'=>$this->category]);
    }
}