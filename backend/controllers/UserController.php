<?php
namespace backend\controllers;

use common\models\User;
use frontend\services\Func;
use Yii;
use backend\components\BackController;
use yii\widgets\ActiveForm;
use common\models\News;
use common\models\ClassicCase;
use yii\db\ActiveRecord;
use yii\helpers;
use common\components;
use yii\data\Pagination;
use yii\filters\VerbFilter;
/**
 * Site controller
 */
class UserController extends BackController
{


    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    public function actionIndex(){
        $this->header = "前端注册用户列表";
        $this->title = '你好';
        $news ="select * from zcb_user where 1";
        $query = \Yii::$app->db->createCommand($news)->query();
        $pagination = new Pagination(['defaultPageSize'=>10,'totalCount'=>$query->count()]);
        $news = $news . " limit ".$pagination->offset." , ".$pagination->limit;
        $rows = \Yii::$app->db->createCommand($news)->query();
        return $this->render('index',['pagination'=>$pagination,'models'=>$rows]);
    }

    public function actionStatistics(){

        $this->title = '你好';
        $news ="select * from zcb_user order by created_at desc";
        $query = \Yii::$app->db->createCommand($news)->query();
        $pagination = new Pagination(['defaultPageSize'=>6,'totalCount'=>$query->count()]);
        $news = $news . " limit ".$pagination->offset." , ".$pagination->limit;
        $rows = \Yii::$app->db->createCommand($news)->query();
        return $this->render('statistics',['pagination'=>$pagination,'news'=>$rows]);
    }

    public function actionOutput(){
        $allModels = User::find()->all();

        $arr = [];

        $cc = 1;
        foreach($allModels as $model){
            $aone = $model->toArray();

            $aone['status'] = $aone['status'] == 10?"启用":'禁用';

            $aone['created_at'] = date("Y-m-d H:i:s",$aone['created_at']);
            $aone['certification'] = isset($aone['pid'])?"经办人已认证":(Yii::$app->db->createCommand("select state from zcb_certification where uid = ".$aone['id'])->queryScalar() == 1?"已认证":(Yii::$app->db->createCommand("select state from zcb_certification where uid = ".$aone['id'])->queryScalar() == 2?'认证失败':'未认证'));
            $aone['isPublish'] = Yii::$app->db->createCommand("select id from zcb_finance_product "."where uid = ".$aone['id']." union select id from zcb_finance_product where uid = ".$aone['id'])->queryScalar()> 0?"已发布":"未发布";
            $aone['isPickUp'] = Yii::$app->db->createCommand("select id from zcb_apply  "."where 	app_id = 1 and  uid = ".$aone['id'])->queryScalar()> 0?"已接单":"未接单";
            $aone['sortID'] = $cc++;

            $arr[]  = $aone;
        }

        \moonland\phpexcel\Excel::export([
            'models' => $arr,
            'columns' => ['sortID','id',['attribute'=>'mobile','format'=>'text'],'created_at','status','certification','isPublish','isPickUp'], //without header working, because the header will be get label from attribute label.
            'headers'=>['sortID'=>'序号','id'=>'ID','mobile'=>'手机号','created_at'=>'注册时间','status'=>'状态','certification'=>'是否认证','isPublish'=>'是否发布','isPickUp'=>'是否接单']
        ]);

    }

    public function actionOutputpublishstatistics(){
        $sql = "select id,city_id,category,code,term,district_id,rate_cat,create_time,modify_time,money,progress_status,browsenumber from zcb_finance_product where  1   union
                select id,city_id,category,code,term,district_id,rate_cat,create_time,modify_time,money,progress_status ,browsenumber from zcb_creditor_product where  1  order by progress_status asc ,modify_time desc";

        $arrModels = Yii::$app->db->createCommand($sql)->queryAll();

        $mu = [];
        $cc = 1;
        foreach($arrModels as $am){
            $aone = $am;

            $aone['progress_status'] = Func::$listMenu[$aone['progress_status']];
            $aone['sortID'] = $cc++;


            $mu[$am['uid']][] = $aone;
        }


        \moonland\phpexcel\Excel::export([
            'models' => $mu,
            'columns' => ['sortID','uid','id'], //without header working, because the header will be get label from attribute label.
            'headers'=>['sortID'=>'序号','uid'=>'用户','ID']
        ]);
    }

    public function actionPublishStatistics(){
        return $this->render("publishstatistics");
    }

    public function actionAdduser(){

        if(Yii::$app->request->isPost){
            $username = Yii::$app->request->post('mobile');
            $password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('password_hash'));
            $admin = new User();
            $admin->password_hash = $password_hash;
            $admin->auth_key = Yii::$app->security->generateRandomString();;
            $admin->username = $username;
            $admin->mobile = $username;
            $admin->status = 10;
            $admin->created_at = time();
            $admin->updated_at = time();
            if($admin->save()){
                return $this->redirect("/user/index");
            }
        }

        return $this->render("adduser");
    }

    public function actionCertification($uid){

    }
}
