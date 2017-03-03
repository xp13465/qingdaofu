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
    public $layout = 'register';
    public function actionProducts(){
        $this->title = '清道夫债管家';
        return $this->render('/products/ProductsServices');
    }
}

