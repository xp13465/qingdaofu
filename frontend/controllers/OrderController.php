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
	
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
	
	
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
                header("Content-type:text/html;charset=utf-8");
               exit('<script>alert("您还未认证，请先认证后才可以接单。");location.href = "/certification/index";</script>');
              //$this->redirect(\yii\helpers\Url::to("/certification/index"));
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
    //接单收藏列表
    public function actionIndex(){
        $this->title = "清道夫债管家";
        $status = Yii::$app->request->get('status');
        switch($status){
            case 1:$res = $this->getOrderBySatus(2,1);break;
            case 2:$res = $this->getOrderBySatus(0,1);break;
            case 3:$res = $this->getOrderBySatus(1,2);break;
            case 4:$res = $this->getOrderBySatus(1,3);break;
            case 5:$res = $this->getOrderBySatus(1,4);break;
            default:$res = $this->getOrderBySatus('0,1,2','1,2,3,4');break;
        }
        return $this->render('index',['pagination'=>$res['pagination'] ,'creditor'=>$res['rows']]);
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
        $sql ="select zfp.id as id,province_id,zfp.category as category,zfp.city_id,zfp.district_id,zfp.seatmortgage,zfp.uid,code,zfp.create_time,zfp.modify_time,money,progress_status,ap.uid as fuid,ap.category,applyclose,applyclosefrom,ap.app_id
               ,loan_type from zcb_finance_product zfp left JOIN zcb_apply as ap  on (ap.product_id = zfp.id)
               where ap.uid = $uid and ap.app_id in ({$status})  and progress_status in ({$progress_status})  and ap.category  = 1 union
               select zcp.id as id, province_id,zcp.category as category,zcp.city_id,zcp.district_id,zcp.seatmortgage,zcp.uid,code,zcp.create_time,zcp.modify_time,money,progress_status,ap.uid as fuid,ap.category,applyclose,applyclosefrom,ap.app_id
               ,loan_type from zcb_creditor_product zcp left JOIN zcb_apply as ap  on (ap.product_id = zcp.id)
               where ap.uid = $uid and ap.app_id in ({$status}) and progress_status in ({$progress_status})   and ap.category in(2,3)  order by case app_id when 1 then 1 when 0 then 2 else 3 end ,progress_status asc, modify_time desc";
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
        $certification = Func::getCertification();
        if (($collection['product_id'] == $id) && ($collection['uid'] == $uid) && ($collection['app_id'] == 1)) {
            echo 1;die;
        } else if(!isset($certification->state)||$certification->state != 1){
            echo 2;die;
        }else{
            $certi = \common\models\FinanceProduct::findOne(['id'=>$id]);
            $mobile = \common\models\User::findOne(['id'=>$certi['uid']]);
            Yii::$app->smser->sendMsgByMobile($mobile['mobile'],'尊敬的用户：订单号:'.$certi["code"].'接单方申请接单，请您尽快处理，避免超时。详细信息请登录清道夫债管家账户系统查看。');
            $finance->modify_time = time();
            $collection->app_id = 0;
            $finance->save();
            if ($collection->save()) {
                echo 3;die;
            } else {
                echo 4;die;
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
            $certi = \common\models\CreditorProduct::findOne(['id'=>$id]);
            $mobile = \common\models\User::findOne(['id'=>$certi['uid']]);
            Yii::$app->smser->sendMsgByMobile($mobile['mobile'],'尊敬的用户：订单号:'.$certi["code"].'接单方申请接单，请您尽快处理，避免超时。详细信息请登录清道夫债管家账户系统查看。');
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
            $disposingprocess->audit = \yii::$app->request->post('audit');
            $disposingprocess->case = \yii::$app->request->post('case');

            if(\common\models\Apply::findOne(['uid'=>$uid,'product_id'=>$product_id,'category'=>$category,'app_id'=>1])->id){
                $disposingprocess->create_time = time();
                $disposingprocess->save();
            }

            $finance = Func::getProduct($category, $product_id);
            \frontend\services\Func::addMessage(13, \yii\helpers\Url::to(['/list/chakan', 'id' =>$product_id, 'category' =>$category]), ['{1}'=>Func::$category[$category],'{2}' => $finance->code],$finance->uid);
            if($category == 1){
                $this->redirect(\yii\helpers\Url::to(['/order/chakan','id'=>$disposingprocess->product_id,'category'=>$finance->category]));
            }elseif(in_array($category,[2,3])){
                $this->redirect(\yii\helpers\Url::to(['/order/chakan','id'=>$disposingprocess->product_id,'category'=>$finance->category]));
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


            $url1 = \yii\helpers\Url::to(['/order/chakan','id'=>$disposingprocess->product_id,'category'=>$category]);
            $url2 = \yii\helpers\Url::to(['/order/chakan','id'=>$disposingprocess->product_id,'category'=>$category]);
            $apply = \common\models\Apply::findOne(['uid'=>$uid,'product_id'=>$product_id,'category'=>$category,'app_id'=>1]);
            if($apply->id){
                $finance = Func::getProduct($category, $product_id);

                $disposingprocess->create_time = time();
                $disposingprocess->uid = $uid;
                $disposingprocess->save();
                \frontend\services\Func::addMessage(16, \yii\helpers\Url::to(['/list/chakan', 'id' =>$product_id, 'category' =>$category]), ['{1}'=>Func::$category[$category],'{2}' => $finance->code],$finance->uid);
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


    public function actionChakan($category,$id){
        $desc = \frontend\services\Func::getProduct($category,$id);

        return $this->render("chakan",['dt'=>$desc]);
    }

    public function actionAudit(){
        $case = \Yii::$app->request->post('cases');
        $id = \Yii::$app->request->post('id');
        $category =\Yii::$app->request->post('category');
        $cases = \common\models\DisposingProcess::findOne(['audit'=>$case,'product_id'=>$id,'category'=>$category]);
        echo $cases['case'];die;
    }

    public function actionRadio(){
        if(yii::$app->request->post('category') == 1){
            $radio = \frontend\services\Func::$Status[1][yii::$app->request->post('radio')];
        }else if(yii::$app->request->post('category') == 2){
            $radio = \frontend\services\Func::$Status[2][yii::$app->request->post('radio')];
        }else{
            $radio = \frontend\services\Func::$Status[3][yii::$app->request->post('radio')];
        }
        exit(json_encode($radio));
    }
}