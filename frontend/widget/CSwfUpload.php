<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 2016/4/13
 * Time: 14:22
 */
namespace frontend\widget;

use frontend\services\Func;
use yii;
use yii\base;
use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class SingleproductWidget extends Widget{
    public $product_id;
    public $category;
    public $product;
    public $certification;

    public function init(){
        parent::init();

        $this->product = Func::getProduct($this->category,$this->product_id);
        $this->certification = Func::getCertification();
    }

    public function run(){
        return $this->render('singleproduct',['product'=>$this->product,'certification'=>$this->certification]);
    }
}