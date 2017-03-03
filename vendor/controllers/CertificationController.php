<?php
namespace frontend\controllers;

use common\models\FinanceProduct;
use Yii;
use common\models\LoginForm;
use common\models\CreditorProduct;
use common\models\Certification;
use common\models\Apply;
use frontend\services\Func;
use app\models\User;
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

/**
 * Site controller
 */
class CertificationController extends FrontController
{
    public $layout = 'user';
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ],
        ];
    }
    public function actionIndex()
    {
        $this->title = "清道夫债管家";
        $user = User::findOne(['id'=>\Yii::$app->user->getId()]);
        if($user['pid'] != ''){
            $certi =Certification::findOne(['uid'=>$user['pid']]);
            $user = User::findOne(['id'=>\Yii::$app->user->getId()]);
            if($certi['category'] == 3 && $certi['state'] == 1){
                 return $this->render('company',['certi'=>$certi,'username'=>$user]);
            }
        }else{
            $certi =Certification::findOne(['uid'=>\Yii::$app->user->getId()]);
            if(!$certi['uid']){
                return $this->render('index');
            }else if(!$certi['uid'] || $certi['state'] == 2 ){
                exit("<script>location.href='/user/index';</script>");
            }else if($certi['category'] == 1 && $certi['state'] == 1){
                exit("<script>location.href='/certification/personal';</script>");
            }else if($certi['category'] == 2 && $certi['state'] == 1){
                exit("<script>location.href='/certification/lawyer';</script>");
            }else if($certi['category'] == 3 && $certi['state'] == 1){
                exit("<script>location.href='/certification/corporation';</script>");
            }else{
                exit("<script>location.href='/user/index';</script>");
            };
        }
    }
    public function actionDex(){
        $this->title = "清道夫债管家";
        return $this->render('index');
    }
    //个人清收师资格认证
    public function actionPersonal(){
        $this->title = "清道夫债管家";
        $certi =Certification::findOne(['uid'=>\Yii::$app->user->getId()]);
        if($certi['uid']){
            if($certi['state'] == 2) {
                $user = Yii::$app->request;
                $certi->name = $user->post('name');
                $certi->uid = Yii::$app->user->getId();
                $certi->cardno = $user->post('cardno');
                $certi->cardimg = Yii::$app->imgload->UploadPhoto($certi, 'uploads/', 'cardimg');
                $certi->address = $user->post('address');
                $certi->mobile = $user->post('mobile');
                $certi->email = $user->post('email');
                $certi->casedesc = $user->post('casedesc');
                $certi->create_time = $user->post('create_time');
                $certi->category = 1;
                $certi->state = 0;
                if ($certi->save()) {
                    header("Content-type:text/html;charset=utf-8");
                    exit("<script>location.href='/certification/index';</script>");
                };
                return $this->render('personal');
            }else if($certi['state'] == 0){
                exit("<script>location.href='/certification/index';</script>");
            }else if($certi['state'] == 1){
                return $this->render('personaldata',['certi'=>$certi]);
            }
        }else {
            $user = Yii::$app->request;
            if ($user->post()) {
                $model = new \common\models\Certification();
                $model->load($user->post(), '');
                $model->name = $user->post('name');
                $model->uid = Yii::$app->user->getId();
                $model->cardno = $user->post('cardno');
                $model->cardimg = Yii::$app->imgload->UploadPhoto($model, 'uploads/', 'cardimg');
                $model->address = $user->post('address');
                $model->mobile = $user->post('mobile');
                $model->email = $user->post('email');
                $model->create_time = $user->post('create_time');
                $model->casedesc = $user->post('casedesc');
                $model->category = 1;
                $model->state = 0;
                if ($model->save()) {
                    header("Content-type:text/html;charset=utf-8");
                    exit("<script>alert('提交成功');location.href='/certification/index';</script>");
                };
            }
            return $this->render('personal');
        }

    }
    //公司资格人正
    public function actionCorporation(){
        $this->title = "清道夫债管家";
        $certi =Certification::findOne(['uid'=>\Yii::$app->user->getId()]);
        if($certi['uid']){
            if($certi['state'] == 2) {
                $user = Yii::$app->request;
                $certi->name = $user->post('name');
                $certi->uid = Yii::$app->user->getId();
                $certi->cardimg = Yii::$app->imgload->UploadPhoto($certi, 'uploads/', 'cardimg');

                $certi->mobile = $user->post('mobile');
                $certi->email = $user->post('email');
                $certi->casedesc = $user->post('casedesc');
                $certi->address = $user->post('address');
                $certi->enterprisewebsite = $user->post('enterprisewebsite');
                $certi->cardno = $user->post('cardno');
                $certi->create_time = $user->post('create_time');
                $certi->contact = $user->post('contact');
                $certi->category = 3;
                $certi->state = 0;
                if ($certi->save()) {
                        header("Content-type:text/html;charset=utf-8");
                        exit("<script>alert('提交成功');location.href='/certification/index';</script>");
                    };
                    return $this->render('corporation');
                }else if($certi['state'] == 0){
                exit("<script>location.href='/certification/index';</script>");
            }else if($certi['state'] == 1){
                $user = User::find()->where(['pid'=>\Yii::$app->user->getId()])->all();
                /*var_dump($user);die;*/
                return $this->render('company',['certi'=>$certi,'username'=>$user]);
            }

        }else {
            $user = Yii::$app->request;
            if ($user->post()) {
                $model = new \common\models\Certification();
                $model->load($user->post(), '');
                $model->name = $user->post('name');
                $model->uid = Yii::$app->user->getId();
                $model->cardimg = Yii::$app->imgload->UploadPhoto($model, 'uploads/', 'cardimg');
                $model->mobile = $user->post('mobile');
                $model->email = $user->post('email');
                $model->casedesc = $user->post('casedesc');
                $model->address = $user->post('address');
                $model->enterprisewebsite = $user->post('enterprisewebsite');
                $model->cardno = $user->post('cardno');
                $model->create_time = $user->post('create_time');
                $model->contact = $user->post('contact');
                $model->category = 3;
                $model->state = 0;
                if ($model->save()) {
                    header("Content-type:text/html;charset=utf-8");
                    exit("<script>alert('提交成功');location.href='/certification/index';</script>");
                };
            };
            return $this->render('corporation');
        };

    }

    //律师资格认证
    public function actionLawyer()
    {
        $this->title = "清道夫债管家";
        $certi = Certification::findOne(['uid' => \Yii::$app->user->getId()]);
        if ($certi['uid']) {
            if ($certi['state'] == 2) {
                $user = Yii::$app->request;
                $certi->name = $user->post('name');
                $certi->uid = Yii::$app->user->getId();
                $certi->cardno = $user->post('cardno');
                $certi->cardimg = Yii::$app->imgload->UploadPhoto($certi, 'uploads/', 'cardimg');
                $certi->address = $user->post('address');
                $certi->mobile = $user->post('mobile');
                $certi->email = $user->post('email');
                $certi->education_level = $user->post('education_level');
                $certi->lang = $user->post('lang');
                $certi->working_life = $user->post('working_life');
                $certi->law_cardno = $user->post('law_cardno');
                $certi->professional_area = $user->post('professional_area');
                $certi->create_time = $user->post('create_time');
                $certi->casedesc = $user->post('casedesc');
               $certi->category = 2;
                $certi->state = 0;
                if ($certi->save()) {
                    header("Content-type:text/html;charset=utf-8");
                    exit("<script>alert('提交成功');location.href='/certification/index';</script>");
                };
                    return $this->render('lawyer');
                } else if ($certi['state'] == 0) {
                    exit("<script>location.href='/certification/index';</script>");
                } else if ($certi['state'] == 1) {
                    return $this->render('lawyerdata', ['certi' => $certi]);
                }

        } else {
            $user = Yii::$app->request;
            if ($user->post()) {
                $model = new \common\models\Certification();
                $model->load($user->post(), '');
                $model->name = $user->post('name');
                $model->uid = Yii::$app->user->getId();
                $model->cardno = $user->post('cardno');
                $model->cardimg = Yii::$app->imgload->UploadPhoto($model, 'uploads/', 'cardimg');
                $model->address = $user->post('address');
                $model->mobile = $user->post('mobile');
                $model->email = $user->post('email');
                $model->education_level = $user->post('education_level');
                $model->lang = $user->post('lang');
                $model->working_life = $user->post('working_life');
                $model->law_cardno = $user->post('law_cardno');
                $model->professional_area = $user->post('professional_area');
                $model->create_time = $user->post('create_time');
                $model->casedesc = $user->post('casedesc');
                $model->category = 2;
                $model->state = 0;
                if ($model->save()) {
                    header("Content-type:text/html;charset=utf-8");
                    exit("<script>alert('提交成功');location.href='/certification/index';</script>");
                };
            }
            return $this->render('lawyer');
        };
    }

    //经办人数据保存
    public function actionSignup()
    {
        $mobile = user::findOne(['mobile'=>Yii::$app->request->post('mobile')]);
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post(),'')) {
            if (Yii::$app->request->post('mobile') == isset($mobile['mobile']) && $mobile['mobile']) {
                header("Content-type:text/html;charset=utf-8");
                exit("<script>alert('该手机已注册');location.href='/certification/index';</script>");
            } else {
                if ($model->registerCorperationUser()) {
                    if (true) {
                        header("Content-type:text/html;charset=utf-8");
                        exit("<script>alert('提交成功');location.href='/certification/index';</script>");
                    }
                }
            }
        }
    }

    //经办人登录查询
    public function actionStop(){
        $user = User::findOne(['id'=>Yii::$app->request->post('id')]);
        if($user){
            $user->isstop = 1;
            $user->updated_at = time();
            $user->save();
            //var_dump($user->errors);die;
            exit(json_encode(1));
        }
    }
}

