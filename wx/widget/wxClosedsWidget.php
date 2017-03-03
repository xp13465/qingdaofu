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

class wxClosedsWidget extends Widget{
    public $id;
    public $category;
    public $uid;
    public $product;
    public $creditor;
	public $data;
    public function init(){
        parent::init();
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/wxclosedswidget'),['id'=>$this->id,'category'=>$this->category,'token'=>Yii::$app->session->get('user_token')]);
		$arr = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $this->product = $arr['result']['product'];
            $this->uid     = $arr['result']['uid'];
            $this->creditor = $arr['result']['creditor'];
			$this->data = $arr['result']['data'];
        }
    }

    public function run(){
        return $this->render('wxClosedsWidget',['product'=>$this->product,'uid'=>$this->uid,'creditor'=>$this->creditor,'data'=>$this->data]);
    }
}