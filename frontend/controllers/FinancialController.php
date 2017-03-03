<?php
namespace frontend\controllers;

use yii;
use yii\web\Controller;
class FinancialController extends Controller{
    public $layout  = 'register';
    public function actionIndex(){
        return $this->render('financial_table');
    }
}