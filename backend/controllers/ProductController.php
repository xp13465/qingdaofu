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
use app\models\Product;
use yii\data\ActiveDataProvider;
/**
 * Site controller
 */
class ProductController extends BackController
{

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
        $this->header = "发布列表";
        $category = Yii::$app->request->get('category');
        $isPick = Yii::$app->request->get('isPick') == ''?-1:Yii::$app->request->get('isPick');
        $progress_status = Yii::$app->request->get('progress_status') == ''?-1:Yii::$app->request->get('progress_status');
        $mobile = Yii::$app->request->get('mobile');

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

        if(trim($mobile) != ''){
            $mobiles = Yii::$app->db->createCommand("select id from zcb_user where mobile like '%{$mobile}%'")->queryAll();

            $mobileArr = [];
            foreach($mobiles as $m){
                array_push($mobileArr,$m['id']);
            }

            $mobilestr = implode(',',$mobileArr);
            if($mobilestr == ''){
                $inwherel  = "and f.uid = ''";
            }else{
                $inwherel  = "and f.uid in ({$mobilestr})";
            }
            if($mobilestr == ''){
                $inwherer  = "and c.uid = ''";
            }else{
                $inwherer  = "and c.uid in ({$mobilestr})";
            }

            $wherel .= $inwherel;
            $wherer .= $inwherer;
        }

        if(in_array($category,[1,2,3])){
            $wherel .= "and f.category = '{$category}' ";
            $wherer .= "and c.category = '{$category}' ";
        }
        if(in_array($progress_status,[0,1,2,3,4])){
            $wherel .= " and f.progress_status = '{$progress_status}' ";
            $wherer .= " and c.progress_status = '{$progress_status}' ";
        }

        $sql = "select f.rentmoney,f.mortgagemoney,f.id,seatmortgage,city_id,f.category,code,term,district_id,rate_cat,f.create_time,modify_time,money,progress_status,browsenumber,province_id ,f.uid from zcb_finance_product f left join zcb_apply a on (a.category=f.category and a.product_id = f.id) where  1  $wherel union
                select c.agencycommissiontype,c.agencycommission,c.id,seatmortgage,city_id,c.category,code,term,district_id,rate_cat,c.create_time,modify_time,money,progress_status ,browsenumber,province_id ,c.uid from zcb_creditor_product c  left join zcb_apply a on (a.category=c.category and a.product_id = c.id) where  1  $wherer order by modify_time desc";
        $querys = \Yii::$app->db->createCommand($sql)->query();
        $pagination = new Pagination(['defaultPageSize'=>10,'totalCount'=>$querys->count()]);
        $sql = $sql . " limit ".$pagination->offset." , ".$pagination->limit;
        $rows = \Yii::$app->db->createCommand($sql)->query();

        return $this->render('index',['pagination'=>$pagination ,'models'=>$rows ]);
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

        $sql = "select f.rentmoney,f.mortgagemoney,f.id,seatmortgage,city_id,f.category,code,term,district_id,rate_cat,f.create_time,modify_time,money,progress_status,browsenumber,province_id ,f.uid from zcb_finance_product f left join zcb_apply a on (a.category=f.category and a.product_id = f.id) where  1  $wherel union
                select c.agencycommissiontype,c.agencycommission,c.id,seatmortgage,city_id,c.category,code,term,district_id,rate_cat,c.create_time,modify_time,money,progress_status ,browsenumber,province_id ,c.uid from zcb_creditor_product c  left join zcb_apply a on (a.category=c.category and a.product_id = c.id) where  1  $wherer order by modify_time asc";
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

            
			$aone['daili'] ='';
			if($r['category'] == 1){
				$aone['daili'].= isset($r['rebate'])?$r['rebate'].'%':'';
            }else if($r['category'] == 2){
				$aone['daili'].= isset($r['rentmoney'])&&$r['rentmoney']==1?$r['mortgagemoney'].'%':$r['mortgagemoney'].'万';
            }else if($r['category'] == 3){
				if($r['rentmoney']==1){
					$aone['daili'].= isset($r['mortgagemoney'])?$r['mortgagemoney'].'万':'';
				}else{
					$aone['daili'].= isset($r['mortgagemoney'])?$r['mortgagemoney'].'%':'';
				} 
            } 
				
			if($r['category'] == 1){ 
				$aone['daili'].= '返点';
			}else if($r['category'] == 2){
				$aone['daili'].=isset($r['rentmoney'])&&$r['rentmoney']==1?'服务佣金':'固定费用';
			}else if($r['category'] == 3){ 
				$aone['daili'].= isset($r['mortgagemoney'])?\common\models\CreditorProduct::$agencycommissiontype[$r['rentmoney']]:'';
			} 
			$arr[] = $aone;
        }

        \moonland\phpexcel\Excel::export([
            'models' => $arr,
            'columns' => ['sortID','id',['attribute'=>'mobile','format'=>'text'],'category','money','daili','code','create_time','isPick','progress_status'], //without header working, because the header will be get label from attribute label.
            'headers'=>['sortID'=>'序号','id'=>'ID','mobile'=>'手机号','category'=>'类型','money'=>'金额（万）','daili'=>'代理费用','code'=>'编码','create_time'=>'发布时间','isPick'=>'是否接单','progress_status'=>'进度']
        ]);
    }

    public function actionChakan(){
        $category = Yii::$app->request->get('category');
        $id = Yii::$app->request->get('id');
        if($category == 1){
            $details = \common\models\FinanceProduct::findOne(['id'=>$id,'category'=>$category]);
        }else if(in_array($category,[2,3])){
            $details = \common\models\CreditorProduct::findOne(['id'=>$id,'category'=>$category]);
        }else{
            die;
        }
        return $this->render('chakan',['desc'=>$details]);
    }

    public function actionUploadscheck(){
        return $this->renderPartial('uploadsCheck');
    }
}
