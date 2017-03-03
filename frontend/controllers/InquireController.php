<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use common\models\FinanceProduct;
use common\models\CreditorProduct;
use common\models\Certification;
use common\models\Apply;
use app\models\user;
use frontend\services\Func;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use frontend\components\FrontController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\ActiveRecord;
use yii\helpers;
use yii\helpers\BaseJson;
use yii\web\Controller;
use yii\data\Pagination;
class InquireController extends FrontController{
    public $layout  = 'inquire';

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // your custom code here
            $url = strtolower(Yii::$app->controller->id.Yii::$app->controller->action->id);
            $user = \common\models\User::findOne(['id'=>\Yii::$app->user->getId()]);
            if($user->pid){
                $uid = $user->pid;
            }else{
                $uid = \Yii::$app->user->getId();
            }
            if($url != 'userindex' && !Certification::findOne(['uid'=>$uid,'state'=>1])){
                $this->redirect(\yii\helpers\Url::to("/user/index"));
            }
            return true;  // or false if needed
        } else {
            return false;
        }
    }
    //发布列表(待发布)
    public function actionRelease(){
        $this->title = "清道夫债管家";
        $user = User::findOne(['id'=>\Yii::$app->user->getId()]);
        $uid =$user->id;
        $sql = "select id,city_id,category,code,create_time,modify_time,money,progress_status from zcb_finance_product where uid = '$uid'&& progress_status=1 union
               select id,city_id,category,code,create_time,modify_time,money,progress_status from zcb_creditor_product where uid = '$uid'&& progress_status=1 order by modify_time desc";
        $query = \Yii::$app->db->createCommand($sql)->query();
        $pagination = new Pagination(['defaultPageSize'=>3,'totalCount'=>$query->count()]);
        $sql = $sql . " limit ".$pagination->offset." , ".$pagination->limit;
        $rows = \Yii::$app->db->createCommand($sql)->query();
        return $this->render('release',['pagination'=>$pagination ,'creditor'=>$rows ]);
    }
    //申请列表(发布中)
    public function actionApply(){
        $this->title = "清道夫债管家";
        $user = User::findOne(['id'=>\Yii::$app->user->getId()]);
        $uid =$user->id;
        $sql ="select id,city_id,category,code,create_time,modify_time,money,progress_status from zcb_finance_product where uid = '$uid'&& progress_status=2  union
               select id,city_id,category,code,create_time,modify_time,money,progress_status from zcb_creditor_product where uid = '$uid'&& progress_status=2 order by modify_time desc";
        $query = \Yii::$app->db->createCommand($sql)->query();
        $pagination = new Pagination(['defaultPageSize'=>3,'totalCount'=>$query->count()]);
        $sql = $sql . " limit ".$pagination->offset." , ".$pagination->limit;
        $rows = \Yii::$app->db->createCommand($sql)->query();
        //$cc = \Yii::$app->db->createCommand($sql)->where('uid')->query();
        return $this->render('apply',['pagination'=>$pagination ,'creditor'=>$rows ,'pro_id'=>$pro_id ,'pro_count'=>$pro_count]);
    }
    //终止列表(处理中)
    public function actionTermination(){
        $this->title = "清道夫债管家";
        $user = User::findOne(['id'=>\Yii::$app->user->getId()]);
        $uid =$user->id;
        $sql ="select id,city_id,category,code,create_time,modify_time,money,progress_status from zcb_finance_product where uid = '$uid' && progress_status = 3 union
               select id,city_id,category,code,create_time,modify_time,money,progress_status from zcb_creditor_product where uid = '$uid' && progress_status = 3 order by modify_time desc";
        $query = \Yii::$app->db->createCommand($sql)->query();
        $pagination = new Pagination(['defaultPageSize'=>3,'totalCount'=>$query->count()]);
        $sql = $sql . " limit ".$pagination->offset." , ".$pagination->limit;
        $rows = \Yii::$app->db->createCommand($sql)->query();
        $pro_id = Apply::find()->where('product_id = 2')->asArray()->all();
        $pro_count = Apply::find()->where('product_id = 2')->groupBy('id')->count('product_id');
        return $this->render('termination',['pagination'=>$pagination ,'creditor'=>$rows ,'pro_id'=>$pro_id ,'pro_count'=>$pro_count]);
    }
    //发布结案(处理完成)
    public function actionClosure(){
        $sql ="select city_id,category,code,create_time,modify_time,money,progress_status from zcb_finance_product where uid = 5 &&  progress_status = 4 union
               select city_id,category,code,create_time,modify_time,money,progress_status from zcb_creditor_product where uid = 5 &&  progress_status = 4 order by modify_time desc";
        $query = \Yii::$app->db->createCommand($sql)->query();
        $pagination = new Pagination(['defaultPageSize'=>3,'totalCount'=>$query->count()]);
        $sql = $sql . " limit ".$pagination->offset." , ".$pagination->limit;
        $rows = \Yii::$app->db->createCommand($sql)->query();
        return $this->render('Closure',['pagination'=>$pagination ,'creditor'=>$rows]);
    }

}