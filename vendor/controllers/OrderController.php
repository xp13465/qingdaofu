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
class OrderController extends FrontController{
    public $layout  = 'order';
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
    //接单收藏列表
    public function actionOrdersave(){
        $this->title = "清道夫债管家";
        $res = $this->getOrderBySatus(2,1);
        return $this->render('order_save',['pagination'=>$res['pagination'] ,'creditor'=>$res['rows']]);
    }
    //接单申请列表
    public function actionOrderapply(){
        $this->title = "清道夫债管家";
        $res = $this->getOrderBySatus(0,1);
        return $this->render('order_apply',['pagination'=>$res['pagination'] ,'creditor'=>$res['rows']]);
    }
    //终止列表
    public function actionOrdertermination(){
        $this->title = "清道夫债管家";
        $res = $this->getOrderBySatus(1,2);
        return $this->render('order_termination',['pagination'=>$res['pagination'] ,'creditor'=>$res['rows']]);
    }
    //发布结案
    public function actionOrderclosure(){
        $res = $this->getOrderBySatus(1,'3,4');
        return $this->render('order_closure',['pagination'=>$res['pagination'] ,'creditor'=>$res['rows']]);
    }

    public function actionSavefinancing($id)
    {
        $this->layout = 'user';
        $this->title = "清道夫债管家";
        $dt = \common\models\FinanceProduct::findOne(['id'=>$id]);
        return $this->render('save_financing', [
            'dt' => $dt,
        ]);

    }

    /**
     * Displays 收藏清收、诉讼申请数据查询
     *
     * @return mixed
     */
    public function actionSavecollection($id)
    {
        $this->layout = 'user';
        $this->title = "清道夫债管家";
        $dt = \common\models\CreditorProduct::findOne(['id'=>$id]);
        return $this->render('save_coll', [
            'dt' => $dt,
        ]);
    }

    public function getOrderBySatus($status,$progress_status){
        $uid = Yii::$app->user->getId();
        $sql ="select zfp.id as id,city_id,zfp.category as category,code,zfp.create_time,zfp.modify_time,money,progress_status,ap.uid as fuid,ap.category
               from zcb_finance_product zfp left JOIN zcb_apply as ap  on (ap.product_id = zfp.id)
               where ap.uid = $uid and ap.app_id = {$status}  and progress_status in ({$progress_status})  and ap.category  = 1 union
               select zcp.id as id, city_id,zcp.category as category,code,zcp.create_time,zcp.modify_time,money,progress_status,ap.uid as fuid,ap.category
               from zcb_creditor_product zcp left JOIN zcb_apply as ap  on (ap.product_id = zcp.id)
               where ap.uid = $uid and ap.app_id = {$status} and progress_status in ({$progress_status})   and ap.category in(2,3) order by modify_time desc";
        $query = \Yii::$app->db->createCommand($sql)->query();
        $pagination = new Pagination(['defaultPageSize'=>5,'totalCount'=>$query->count()]);
        $sql = $sql . " limit ".$pagination->offset." , ".$pagination->limit;
        $rows = \Yii::$app->db->createCommand($sql)->query();
        return ['rows'=>$rows,'pagination'=>$pagination];
    }

    public function actionSaveapp()
    {
        $this->title = "清道夫债管家";
        $id = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('cate');
        $uid = Yii::$app->user->getId();
        $finance = FinanceProduct::find()->where(['id' => $id])->one();
        $collection = Apply::find()->where(['product_id' => $id, 'category' => $category, 'uid' => $uid])->one();
        if (($collection['product_id'] == $id) && ($collection['uid'] == $uid) && ($collection['app_id'] == 1)) {
            echo 1;die;
        } else {
            $finance->modify_time = time();
            $collection->app_id = 0;
            $finance->save();
            if ($collection->save()) {
                echo 2;die;
            } else {
                echo 3;die;
            }
        }
    }

    /**
     * Displays 收藏清收、诉讼申请数据保存
     *
     * @return mixed
     */
    public function actionSavecoll()
    {
        $this->title = "清道夫债管家";
        $id = Yii::$app->request->post('id');
        $uid = \Yii::$app->user->getId();
        $credition = CreditorProduct::find()->where(['id' => $id])->one();
        $collection = Apply::find()->where(['product_id' => $id, 'category' => $credition->category, 'uid' => $uid])->one();
        if (($collection['product_id'] == $id) && ($collection['uid'] == $uid) && ($collection['app_id'] == 1)) {
            echo 1;die;
        } else {
            $credition->modify_time = time();
            $collection->app_id = 0;
            $credition->save();
            if ($collection->save()) {
                echo 2;die;
            } else {
                echo 3;die;
            }
        }
    }

    public function actionOrderapplyv($id)
    {
        $this->layout = 'user';
        $this->title = "清道夫债管家";
        $dt = \common\models\FinanceProduct::findOne(['id'=>$id]);
        return $this->render('order_applyv', [
            'dt' => $dt,
        ]);
    }

    /**
     *Displays 接收申请清收\诉讼查询
     */
    public function actionOrdercollv($id)
    {
        $this->layout = 'user';
        $this->title = "清道夫债管家";
        $dt = \common\models\CreditorProduct::findOne(['id'=>$id]);
        return $this->render('order_collectionv', [
            'dt' => $dt,
        ]);
    }

    public function actionFinancingprogress($id){
        $this->layout = 'user';
        $this->title = "清道夫债管家";
        $dt = \common\models\FinanceProduct::findOne(['id'=>$id]);
        return $this->render('financing_progress', [
            'dt' => $dt,
        ]);
    }

    public function actionCreditorprogress($id){
        $this->layout = 'user';
        $this->title = "清道夫债管家";
        $dt = \common\models\CreditorProduct::findOne(['id'=>$id]);
        return $this->render('creditor_progress', [
            'dt' => $dt,
        ]);
    }
    public function actionDisposingprocessadd(){
        $disposingprocess = new \common\models\DisposingProcess();

        if(Yii::$app->request->post()){
            $disposingprocess->load(Yii::$app->request->post(), '');


            $uid = \yii::$app->user->getId();
            $product_id = $disposingprocess->product_id;
            $category = $disposingprocess->category;

            if(\common\models\Apply::findOne(['uid'=>$uid,'product_id'=>$product_id,'category'=>$category,'app_id'=>1])->id){
                $disposingprocess->create_time = time();
                $disposingprocess->save();
            }
            if($category){
                $finance = FinanceProduct::find()->where(['id' =>$product_id])->one();
            }elseif(in_array($category,[2,3])){
                $finance = CreditorProduct::find()->where(['id' => $product_id])->one();
            }
            switch($category){
                case 1:\frontend\services\Func::addMessage(13,\yii\helpers\Url::to(['/apply/treatmentfinancing','id'=>$finance->id]),['{1}'=>$finance->code],$finance->uid);break;
                case 2:\frontend\services\Func::addMessage(14,\yii\helpers\Url::to(['/apply/treatmentcreditor','id'=>$finance->id]),['{1}'=>$finance->code],$finance->uid);break;
                case 3:\frontend\services\Func::addMessage(15,\yii\helpers\Url::to(['/apply/treatmentcreditor','id'=>$finance->id]),['{1}'=>$finance->code],$finance->uid);break;
                default:break;
            }


            if($category == 1){
                $this->redirect(\yii\helpers\Url::to(['/order/financingprogress','id'=>$disposingprocess->product_id]));
            }elseif(in_array($category,[2,3])){
                $this->redirect(\yii\helpers\Url::to(['/order/creditorprogress','id'=>$disposingprocess->product_id]));
            }
        }
    }
    public function actionDisposingdelayadd(){
        $disposingprocess = new \common\models\DelayApply();


        if(Yii::$app->request->post()){
            $disposingprocess->load(Yii::$app->request->post(), '');


            $uid = \yii::$app->user->getId();
            $product_id = $disposingprocess->product_id;
            $category = $disposingprocess->category;


            $url1 = \yii\helpers\Url::to(['/order/financingprogress','id'=>$disposingprocess->product_id]);
            $url2 = \yii\helpers\Url::to(['/order/creditorprogress','id'=>$disposingprocess->product_id]);
            $apply = \common\models\Apply::findOne(['uid'=>$uid,'product_id'=>$product_id,'category'=>$category,'app_id'=>1]);
            if($apply->id){
                if($category){
                    $finance = FinanceProduct::find()->where(['id' =>$product_id])->one();
                }elseif(in_array($category,[2,3])){
                    $finance = CreditorProduct::find()->where(['id' => $product_id])->one();
                }else{
                    echo 2;die;
                }

                $disposingprocess->create_time = time();
                $disposingprocess->uid = $uid;
                $disposingprocess->save();
                switch($category){
                    case 1:\frontend\services\Func::addMessage(16,\yii\helpers\Url::to(['/apply/treatmentfinancing','id'=>$finance->id]),['{1}'=>$finance->code,'{2}'=>$disposingprocess->delay_days],$finance->uid);break;
                    case 2:\frontend\services\Func::addMessage(17,\yii\helpers\Url::to(['/apply/treatmentcreditor','id'=>$finance->id]),['{1}'=>$finance->code,'{2}'=>$disposingprocess->delay_days],$finance->uid);break;
                    case 3:\frontend\services\Func::addMessage(18,\yii\helpers\Url::to(['/apply/treatmentcreditor','id'=>$finance->id]),['{1}'=>$finance->code,'{2}'=>$disposingprocess->delay_days],$finance->uid);break;
                    default:break;
                }
            }


            if($category == 1){
                $this->redirect($url1);
            }elseif(in_array($category,[2,3])){
                $this->redirect($url2);
            }
        }
    }

    public function actionOrderapplychakan($id)
    {
        $this->title = "清道夫债管家";
        $this->layout = 'user';
        $dt = \common\models\FinanceProduct::findOne(['id'=>$id]);
        return $this->render('order_apply_chakan', ['dt' => $dt]);

    }

    /**
     *Displays 接收申请清收\诉讼查询
     */
    public function actionOrdercollchakan($id)
    {
        $this->title = "清道夫债管家";
        $this->layout = 'user';
        $dt = \common\models\CreditorProduct::findOne(['id'=>$id]);
        return $this->render('order_collection_chakan', ['dt' => $dt]);
    }
}