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
class CapitalController extends FrontController{
    public $layout  = 'register';
    public function actionList(){

        $category = Yii::$app->request->get('cat');
        //$district = Yii::$app->request->get('district');
        $money = Yii::$app->request->get('money');
        $term = Yii::$app->request->get('term');
        $where = "";
        if(in_array($category,[1,2,3])){
            $where .= " and category = '{$category}'";
        }
        if ($money == 0 && !in_array($money, [1, 2, 3, 4])) {

            } else if (in_array($money, [1, 2, 3, 4])) {
                switch ($money) {
                    case 1:
                        $where .= "and money < ".(30);
                        break;
                    case 2:
                        $where .= "and money between ".(30). " and ".(100);
                        break;
                    case 3:
                        $where .= "and money between ".(100)." and ".(500);
                        break;
                    case 4:
                        $where .= "and money > ".(500);
                        break;
                    default:
                        break;
                }
            }
        if($term == 0 && !in_array($term,[1,2,3,4,5])){
            $termwhereBX = '  1 ';
            $termwhereRZ1 = '  1 ';
            $termwhereRZ2 = ' 1 ';
        }else if(in_array($term,[1,2,3,4,5])){
            $termwhereBX = ' 1 ';
            $termwhereRZ1 = ' 1 ';
            $termwhereRZ2 = ' 1 ';
            switch($term){
                case 1:
                    $termwhereBX.=" and term < ".(3*30);
                    $termwhereRZ1 .= " and term < ".(3*30)." and rate_cat =1 ";
                    $termwhereRZ2 .= " and term < 3 and rate_cat =2 ";
                    break;
                case 2:
                    $termwhereBX.=" and term between ".(3*30)." and ".(6*30);
                    $termwhereRZ1.=" and term between ".(3*30)." and ".(6*30)." and rate_cat =1 ";
                    $termwhereRZ2.=" and term between  3 and 6 and rate_cat =2 ";
                    break;
                case 3:
                    $termwhereBX.=" and term between ".(6*30)." and ".(9*30);
                    $termwhereRZ1.=" and term between ".(6*30)." and ".(9*30)." and rate_cat =1 ";
                    $termwhereRZ2.=" and term between 6 and 9 and rate_cat =2 ";
                    break;
                case 4:
                    $termwhereBX.=" and term between ".(9*30)." and ".(12*30);
                    $termwhereRZ1.=" and term between ".(9*30)." and ".(12*30)." and rate_cat =1 ";
                    $termwhereRZ2.=" and term between 9 and 12 and rate_cat =2 ";
                    break;
                case 5:
                    $termwhereBX.=" and term > ".(12*30);
                    $termwhereRZ1.=" and term > ".(12*30)." and rate_cat =1 ";
                    $termwhereRZ2.=" and term > 12 and rate_cat =2 ";
                    break;
                default:
                    break;
            }
        }


        $this->title = "清道夫债管家";
            $sql = "select id,city_id,category,code,term,district_id,rate_cat,create_time,modify_time,money,progress_status,browsenumber from zcb_finance_product where  progress_status=1 ".$where." and (".$termwhereRZ1." or ".$termwhereRZ2.") union
                select id,city_id,category,code,term,district_id,rate_cat,create_time,modify_time,money,progress_status ,browsenumber from zcb_creditor_product where  progress_status=1  ".$where." and ".$termwhereBX."  order by modify_time desc";

       $query = \Yii::$app->db->createCommand($sql)->query();
        $pagination = new Pagination(['defaultPageSize'=>2,'totalCount'=>$query->count()]);
        $sql = $sql . " limit ".$pagination->offset." , ".$pagination->limit;
        $rows = \Yii::$app->db->createCommand($sql)->query();
        return $this->render('list',['pagination'=>$pagination ,'creditor'=>$rows ]);
    }
    public function actionApplyorder($tid,$category,$browsenumber){
        $this->title = "清道夫债管家";
       // $arr = explode("/",$id);
        if($category == 1){
            $data = FinanceProduct::findOne(['id'=>$tid]);
            $data->browsenumber = $data['browsenumber']+$browsenumber;
            $data->save();
            return $this->render('applyorder',['finance'=>$data]);
        }else{
            $data = CreditorProduct::findOne(['id'=>$tid]);
            $data->browsenumber = $data['browsenumber']+$browsenumber;
            $data->save();
            return $this->render('applyorder',['finance'=>$data]);
        }
    }

    public function actionCollectionlist()
    {
        $this->title = "清道夫债管家";
        $data = Yii::$app->request;
        $credi_id = apply::find()->where(['product_id' => $data->post('id'), 'uid' => \Yii::$app->user->getId(),'category'=>$data->post('category')])->One();
        if(\Yii::$app->user->getId()) {
            if($data->post('uid') == \Yii::$app->user->getId()){
                exit(json_encode(1));
            }else if(isset($credi_id->product_id)&&$credi_id->product_id == $data->post('id')  && isset($credi_id->category)&&$credi_id->category == $data->post('category') && isset($credi_id->uid)&&$credi_id->uid == \Yii::$app->user->getId()) {
                exit(json_encode(2));
            } else{
                if ($data->post()) {
                    $model = new \common\models\apply();
                    $model->load(Yii::$app->request->post(), '');
                    $model->category = $data->post('category');
                    $model->uid = \Yii::$app->user->getId();
                    $model->product_id = $data->post('id');
                    $model->create_time = time();
                    $model->agree_time = time();
                    $model->app_id = 2;
                    $model->is_del =0;
                    if($model->save()){
                        exit(json_encode(3));
                    };
                }

            }

        }else{
            exit(json_encode(0));
        }
    }



    public function actionApplysuccessful()
    {
        $this->title = "清道夫债管家";
        $data = Yii::$app->request;
        $credi_id = apply::find()->where(['product_id' => $data->post('id'), 'uid' => \Yii::$app->user->getId(),'category'=>$data->post('category')])->One();
        if (\Yii::$app->user->getId()) {
            if($data->post('uid') == \Yii::$app->user->getId()){
                exit(json_encode(1));
            }else if (isset($credi_id->app_id)&&$credi_id->app_id == 0 && isset($credi_id->product_id)&&$credi_id->product_id == $data->post('id')  && isset($credi_id->category)&&$credi_id->category == $data->post('category') && isset($credi_id->uid)&&$credi_id->uid == \Yii::$app->user->getId()) {
                exit(json_encode(2));
            } else if(isset($credi_id->id)&&$credi_id->app_id==2){
                $credi_id->app_id = 0;
                $credi_id->create_time = time();
                if($credi_id->save()){
                    exit(json_encode(3));
                };
            }else {
                if ($data->post()) {
                    $model = new \common\models\apply();
                    $model->load(Yii::$app->request->post(), '');
                    $model->category = $data->post('category');
                    $model->uid = \Yii::$app->user->getId();
                    $model->product_id = $data->post('id');
                    $model->create_time = time();
                    $model->agree_time = time();
                    $model->app_id = 0;
                    $model->is_del = 0;
                    if ($model->save()) {
                        exit(json_encode(3));
                    };
                }
            }
        }else{
            exit(json_encode(0));
        }

    }
}