<?php
namespace frontend\controllers;
use common\models\DisposingProcess;
use common\models\FinanceProduct;
use Yii;
use common\models\News;
use common\models\LoginForm;
use common\models\CreditorProduct;
use common\models\Apply;
use common\models\Certification;
use frontend\services\Func;
use app\models\user;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use frontend\components\FrontController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers;
use yii\helpers\BaseJson;
use yii\db\ActiveRecord;
use yii\data\Pagination;

class ProductsController extends FrontController{
    public $layout = "main";
    public function actionProducts(){
		$url = Yii::$app->request->url;
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => "url",
            'value'=>$url
        ]));
        $this->title = '产品服务-清道夫债管家';
        $this->keywords = '产品服务';
        $this->description = '产品服务';
        return $this->render('/products/ProductsServices');
    }
}

