<?php
namespace backend\controllers;

use common\models\User;
use frontend\services\Func;
use Yii;
use yii\helpers\StringHelper;
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
class CreditorController extends BackController
{

    public $modelClass = 'common\models\CreditorProduct';
    public $modelSearchClass = 'common\models\search\CreditorProductSearch';

    public function actionIndex(){

        $modelClass = $this->modelClass;
        $searchModel = $this->modelSearchClass ? new $this->modelSearchClass : null;

        if ($searchModel) {
                $searchName = StringHelper::basename($searchModel::className());
                $params = Yii::$app->request->getQueryParams();
                $dataProvider = $searchModel->search($params);
        } else {
                $restrictParams = ($restrictAccess) ? [$modelClass::getOwnerField() => Yii::$app->user->identity->id] : [];
                $dataProvider = new ActiveDataProvider(['query' => $modelClass::find()->where($restrictParams)]);
        }

        return $this->renderIsAjax('index', compact('dataProvider', 'searchModel'));

    }


    public function actionOutput(){
        $category = Yii::$app->request->get('category');
        $isPick = Yii::$app->request->get('isPick') == ''?-1:Yii::$app->request->get('isPick');
        $progress_status = Yii::$app->request->get('progress_status') == ''?-1:Yii::$app->request->get('progress_status');
        $wherel = "";
        $wherer = "";

        if($isPick == -1){

        }elseif($isPick == 0){
            $wherel .= " and a.app_id is null ";
            $wherer .= " and a.app_id is null ";
        }elseif($isPick == 1){
            $wherel .= " and a.app_id = '0' ";
            $wherer .= " and a.app_id = '0' ";
        }elseif($isPick == 2){
            $wherel .= " and a.app_id = '1' ";
            $wherer .= " and a.app_id = '1' ";
        }

        if(in_array($category,[1,2,3])){
            $wherel .= "and f.category = '{$category}' ";
            $wherer .= "and c.category = '{$category}' ";
        }
        if(in_array($progress_status,[0,1,2,3,4,5])){
            $wherel .= " and f.progress_status = '{$progress_status}' ";
            $wherer .= " and c.progress_status = '{$progress_status}' ";
        }

        $sql = "select f.id,seatmortgage,city_id,f.category,code,term,district_id,rate_cat,f.create_time,modify_time,money,progress_status,browsenumber,province_id ,f.uid from zcb_finance_product f left join zcb_apply a on (a.category=f.category and a.product_id = f.id) where  1  $wherel union
                select c.id,seatmortgage,city_id,c.category,code,term,district_id,rate_cat,c.create_time,modify_time,money,progress_status ,browsenumber,province_id ,c.uid from zcb_creditor_product c  left join zcb_apply a on (a.category=c.category and a.product_id = c.id) where  1  $wherer order by modify_time asc";
        $rows = \Yii::$app->db->createCommand($sql)->query();

        $arr = [];
        foreach($rows as $key=>$r){
            $aone = $r;
            $aone['sortID'] = $key+1;
            $aone['mobile'] = Yii::$app->db->createCommand("select mobile from zcb_user where id = ".$r['uid'])->queryScalar();

            $app_id = Yii::$app->db->createCommand("select app_id from zcb_apply where category = '{$r['category']}' and product_id = '{$r['id']}' ")->queryScalar()==''?-1:Yii::$app->db->createCommand("select app_id from zcb_apply where category = '{$r['category']}' and product_id = '{$r['id']}' ")->queryScalar();
            $aone['isPick'] = $app_id== 1?"已接单":( $app_id == 0?"已申请":"未申请");

            $array = ['0'=>'待发布','1'=>'已发布','2'=>'处理中','3'=>'已终止','4'=>'已结案'];
            $aone['progress_status'] = $array[$r['progress_status']];
            $aone['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
            $aone['category'] = \backend\services\Func::$category[$r['category']];

            $arr[] = $aone;
        }

        \moonland\phpexcel\Excel::export([
            'models' => $arr,
            'columns' => ['sortID','id',['attribute'=>'mobile','format'=>'text'],'category','money','code','create_time','isPick','progress_status'], //without header working, because the header will be get label from attribute label.
            'headers'=>['sortID'=>'序号','id'=>'ID','mobile'=>'手机号','category'=>'类型','money'=>'金额（万）','code'=>'编码','create_time'=>'发布时间','isPick'=>'是否接单','progress_status'=>'进度']
        ]);
    }

    public function actionChakan(){
        $category = Yii::$app->request->get('category');
        $id = Yii::$app->request->get('id');
        if($category == 1){
            $details = \common\models\FinanceProduct::findOne(['id'=>$id,'category'=>$category])->toArray();
        }else if(in_array($category,[2,3])){
            $details = \common\models\CreditorProduct::findOne(['id'=>$id,'category'=>$category])->toArray();
        }else{
            die;
        }
        return $this->render('chakan',['desc'=>$details]);
    }

    public function actionUploadscheck(){
        return $this->renderPartial('uploadsCheck');
    }
}
