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

class wxReleaseinformationWidget extends Widget{
    public $id;
    public $category;
    public $uid;
    public $product;
    public $pid;
	public $username;
    public function init(){
        parent::init();
        
		$str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/wxreleaseinformationwidget'),['id'=>$this->id,'category'=>$this->category,'token'=>Yii::$app->session->get('user_token')]);
		$arr = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
           $this->product = $arr['result']['product'];
           $this->uid     = $arr['uid'];
            $this->pid = $arr['pid'];
			$this->username = $arr['username'];
        }
    }

    public function run(){
        return $this->render('wxReleaseinformationWidget',['product'=>$this->product,'uid'=>$this->uid,'pid'=>$this->pid,'username'=>$this->username]);
    }
}