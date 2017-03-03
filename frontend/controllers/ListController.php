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
class ListController extends FrontController{
    public $layout  = 'inquire';
    public $enableCsrfValidation = false;
    public function getListByStatus($status){
        $uid = \Yii::$app->user->getId();
        $sql = "select id,uid,seatmortgage,province_id,city_id,district_id,category,code,create_time,modify_time,money,progress_status,applyclose,applyclosefrom,loan_type from zcb_finance_product where uid = '{$uid}'&& progress_status in ({$status}) union
               select id,uid,seatmortgage,province_id,city_id,district_id,category,code,create_time,modify_time,money,progress_status,applyclose,applyclosefrom ,loan_type from zcb_creditor_product where uid = '{$uid}'&& progress_status in ({$status}) order by progress_status asc,modify_time desc";
        $query = \Yii::$app->db->createCommand($sql)->query();
        $pagination = new Pagination(['defaultPageSize'=>5,'totalCount'=>$query->count()]);
        $sql = $sql . " limit ".$pagination->offset." , ".$pagination->limit;
        $rows = \Yii::$app->db->createCommand($sql)->query();
        return ['rows'=>$rows,'pagination'=>$pagination];
    }
    //发布列表(待发布)
    public function actionRelease(){
        $this->title = "清道夫债管家";
        $res = $this->getListByStatus(0);
        return $this->render('release',['pagination'=>$res['pagination'] ,'creditor'=>$res['rows']]);
    }
    //申请列表(发布中)
    public function actionApply(){
        $this->title = "清道夫债管家";
        $res = $this->getListByStatus(1);
        return $this->render('apply',['pagination'=>$res['pagination'] ,'creditor'=>$res['rows']]);
    }
    //终止列表(处理中)
    public function actionTermination(){
        $this->title = "清道夫债管家";
        $res = $this->getListByStatus(2);
        return $this->render('termination',['pagination'=>$res['pagination'] ,'creditor'=>$res['rows']]);
    }
    //发布结案(处理完成)
    public function actionClosure(){
        $res = $this->getListByStatus('3,4');
        return $this->render('Closure',['pagination'=>$res['pagination'] ,'creditor'=>$res['rows']]);
    }


    public function actionIndex(){
        $status = Yii::$app->request->get('status');
        if($status==''||$status == -1){
            $res = $this->getListByStatus('0,1,2,3,4');
        }elseif(in_array($status,[0,1,2,3,4])){
            $res = $this->getListByStatus($status);
        }
        return $this->render('index',['pagination'=>$res['pagination'] ,'creditor'=>$res['rows']]);
    }
    //判断用户是否认证
    public function actionAttestation(){
        $cerfication = \common\models\Certification::findOne(['uid'=>\Yii::$app->user->getId()]);
        $username = \common\models\User::findOne(['id'=>\Yii::$app->user->getId()]);
        $agency = \common\models\Certification::findOne(['uid'=>$username['pid']]);
        if($cerfication || $agency){
            $info['status'] = 0;
        }else{
            $info['status'] = 1;
        }
        exit(json_encode($info));
    }
    //用户发布数据查询
    public function actionChakan($id,$category){
        $desc = \frontend\services\Func::getProduct($category,$id);
        if(!$desc)return $this->redirect('/user/index');
        if($desc->progress_status == 0){
            $url = '/';
            switch($category){
                case 1:$url = helpers\Url::to(['/publish/editfinancing','id'=>$id]);break;
                case 2:$url = helpers\Url::to(['/publish/editcollection','id'=>$id]);break;
                case 3:$url = helpers\Url::to(['/publish/editlitigation','id'=>$id]);break;
            }
            return $this->redirect($url);
        }

        return $this->render("chakan",['dt'=>$desc]);
    }
}