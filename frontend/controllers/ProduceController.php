<?php
namespace frontend\controllers;
use Yii;
use frontend\components\FrontController;
use yii\helpers;

class ProduceController extends FrontController{
    public $layout = 'product';
    public function actionProduces(){
        $this->title = '产调查询-清道夫债管家';
        $this->keywords = '产调查询';
        $this->description = '产调查询';
        return $this->render('/produce/produce');
    }
}