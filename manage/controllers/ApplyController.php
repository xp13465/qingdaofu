<?php
namespace manage\controllers;

use common\models\User;
use frontend\services\Func;
use Yii;
use yii\helpers\StringHelper;
use manage\components\BackController;
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
class ApplyController extends BackController
{


    public $modelClass = 'common\models\Apply';
    public $modelSearchClass = 'common\models\search\ApplySearch';

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


    public function actionIndex2()
    {
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

        return $this->renderIsAjax('index2', compact('dataProvider', 'searchModel'));
    }



    public function actionIndex(){
        $this->header = "接单列表";
        $category = Yii::$app->request->get('category');
        $isPick = Yii::$app->request->get('isPick') == ''?-1:Yii::$app->request->get('isPick');
        $progress_status = Yii::$app->request->get('progress_status') == ''?-1:Yii::$app->request->get('progress_status');
        $mobile = Yii::$app->request->get('mobile');

        $wherel = "";
        $wherer = "";


        $wherel .= " and a.app_id = '1' ";
        $wherer .= " and a.app_id = '1' ";


        if(trim($mobile) != ''){
            $mobiles = Yii::$app->db->createCommand("select id from zcb_user where mobile like '%{$mobile}%'")->queryAll();

            $mobileArr = [];
            foreach($mobiles as $m){
                array_push($mobileArr,$m['id']);
            }

            $mobilestr = implode(',',$mobileArr);
            if($mobilestr == ''){
                $inwhere  = "and a.uid = ''";
            }else{
                $inwhere  = "and a.uid in ({$mobilestr})";
            }

            $wherel .= $inwhere;
            $wherer .= $inwhere;
        }

        if(in_array($category,[1,2,3])){
            $wherel .= "and f.category = '{$category}' ";
            $wherer .= "and c.category = '{$category}' ";
        }
        if(in_array($progress_status,[0,1,2,3,4,5])){
            $wherel .= " and f.progress_status = '{$progress_status}' ";
            $wherer .= " and c.progress_status = '{$progress_status}' ";
        }

        $sql = "select f.id,a.uid as apply_uid,seatmortgage,city_id,f.category,code,term,district_id,rate_cat,f.create_time,modify_time,money,progress_status,browsenumber,province_id ,f.uid from zcb_finance_product f left join zcb_apply a on (a.category=f.category and a.product_id = f.id) where  1  $wherel union
                select c.id,a.uid as apply_uid,seatmortgage,city_id,c.category,code,term,district_id,rate_cat,c.create_time,modify_time,money,progress_status ,browsenumber,province_id ,c.uid from zcb_creditor_product c  left join zcb_apply a on (a.category=c.category and a.product_id = c.id) where  1  $wherer order by apply_uid asc";

        $querys = \Yii::$app->db->createCommand($sql)->query();
        $pagination = new Pagination(['defaultPageSize'=>10,'totalCount'=>$querys->count()]);
        $sql = $sql . " limit ".$pagination->offset." , ".$pagination->limit;
        $rows = \Yii::$app->db->createCommand($sql)->queryAll();

        //$arr = [];
        //$this->collectProduct($rows,$arr);

        return $this->render('index',['pagination'=>$pagination ,'models'=>$rows ]);
    }

    public function actionOutput(){
        $wherel = "";
        $wherer = "";


        $wherel .= " and a.app_id = '1' ";
        $wherer .= " and a.app_id = '1' ";
        $sql = "select f.id,a.uid as apply_uid,seatmortgage,city_id,f.category,code,term,district_id,rate_cat,f.create_time,modify_time,money,progress_status,browsenumber,province_id ,f.uid from zcb_finance_product f left join zcb_apply a on (a.category=f.category and a.product_id = f.id) where  1  $wherel union
                select c.id,a.uid as apply_uid,seatmortgage,city_id,c.category,code,term,district_id,rate_cat,c.create_time,modify_time,money,progress_status ,browsenumber,province_id ,c.uid from zcb_creditor_product c  left join zcb_apply a on (a.category=c.category and a.product_id = c.id) where  1  $wherer order by apply_uid asc";

        $rows = \Yii::$app->db->createCommand($sql)->queryAll();

        $arr = [];
        foreach($rows as $key=>$r){
            $aone = $r;
            $aone['sortID'] = $key+1;
            $aone['mobile'] = Yii::$app->db->createCommand("select mobile from zcb_user where id = ".$r['apply_uid'])->queryScalar();

            $app_id = Yii::$app->db->createCommand("select app_id from zcb_apply where category = '{$r['category']}' and product_id = '{$r['id']}' ")->queryScalar()==''?-1:Yii::$app->db->createCommand("select app_id from zcb_apply where category = '{$r['category']}' and product_id = '{$r['id']}' ")->queryScalar();
            $aone['isPick'] = $app_id== 1?"已接单":( $app_id == 0?"已申请":"未申请");

            $array = ['0'=>'待发布','1'=>'已发布','2'=>'处理中','3'=>'已终止','4'=>'已结案'];
            $aone['progress_status'] = $array[$r['progress_status']];
            $aone['category'] = \manage\services\Func::$category[$r['category']];
            $aone['create_time'] = date('Y-m-d H:i:s',$r['create_time']);

            $arr[] = $aone;
        }

        \moonland\phpexcel\Excel::export([
            'models' => $arr,
            'columns' => ['sortID','id',['attribute'=>'mobile','format'=>'text'],'category','money','code','create_time','isPick','progress_status'], //without header working, because the header will be get label from attribute label.
            'headers'=>['sortID'=>'序号','id'=>'ID','mobile'=>'手机号','category'=>'类型','money'=>'金额（万）','code'=>'编码','create_time'=>'发布时间','isPick'=>'是否接单','progress_status'=>'进度']
        ]);

    }


    private function collectProduct($rows,&$rr=[]){
        foreach($rows as $key=>$r){
            $rc = count($rr)-1;
            if($rc == -1){
                $rr[0][]= $rows[$key];
                unset($rows[$key]);
            }else{
                if($rows[$key]['apply_uid'] == $rr[$rc][0]['apply_uid']){
                    $rr[$rc][] = $rows[$key];
                }else{
                    $rr[$rc+1][] = $rows[$key];
                }

                unset($rows[$key]);
            }

            return $this->collectProduct($rows,$rr);
            break;
        }
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
        return $this->renderPartial('uploadscheck');
    }

    public function actionModify(){
        $id = yii::$app->request->post('id');
        $category = yii::$app->request->post('category');
        if($category == 1){
            $modify ="update zcb_finance_product set progress_status = 1 where id ={$id}";
            $apply = \common\models\Apply::findOne(['product_id'=>$id,'category'=>$category,'app_id'=>1]);
        }else{
            $modify = "update zcb_creditor_product set progress_status = 1 where id ={$id}";
            $apply = \common\models\Apply::findOne(['product_id'=>$id,'category'=>$category,'app_id'=>1]);
        }
        if($apply ){
            $apply->app_id = 0;
            if($apply->save() && \Yii::$app->db->createCommand($modify)->query()){
                exit(json_encode(1));
            }
        }
    }
}
