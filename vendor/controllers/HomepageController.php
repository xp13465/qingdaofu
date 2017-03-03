<?php
namespace frontend\controllers;

use common\models\DisposingProcess;
use common\models\FinanceProduct;
use Yii;
use common\models\News;
use common\models\LoginForm;
use common\models\CreditorProduct;
use common\models\Apply;
use common\models\Certification;
use frontend\services\Func;
use app\models\user;
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

class HomepageController extends FrontController
{
    public $layout = 'register';

    //首页
    public function actionHomepages()
    {
        $this->title = '清道夫债管家';
        $sql = "select id,city_id,category,code,term,district_id,rate_cat,create_time,modify_time,money,progress_status,browsenumber from zcb_finance_product where  progress_status=1 union
                select id,city_id,category,code,term,district_id,rate_cat,create_time,modify_time,money,progress_status ,browsenumber from zcb_creditor_product where  progress_status=1";
        $release = \Yii::$app->db->createCommand($sql)->query();
        $news ="select * from zcb_news order by create_time desc";
        $query = \Yii::$app->db->createCommand($news)->query();
        $pagination = new Pagination(['defaultPageSize'=>6,'totalCount'=>$query->count()]);
        $news = $news . " limit ".$pagination->offset." , ".$pagination->limit;
        $rows = \Yii::$app->db->createCommand($news)->query();

        $category = Yii::$app->request->get('category');
        $money = Yii::$app->request->get('money');
        $district = Yii::$app->request->get('district');
        $nsearch = Yii::$app->request->get('nsearch');
        $where = "";
        if(in_array($category,[1,2,3])){
            $where .= " and category = '{$category}'";
        }
        if($nsearch){
            $where.=" and seatmortgage like '%{$nsearch}%'";
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
        if($district!=0){
            $where.=" and district_id = '{$district}'";
        }
        $this->title = '清道夫债管家';
        $sql1 = "select count(*) as cc,district_id from zcb_finance_product where  progress_status <> 0 {$where} group by district_id";
        $sql2= "select count(*) as cc,district_id from zcb_creditor_product where  progress_status <> 0 {$where}  group by district_id";

        $data1 =  \Yii::$app->db->createCommand($sql1)->query();
        $data2 =  \Yii::$app->db->createCommand($sql2)->query();


        $dataafter1= [];
        $dataafter2= [];
        $dataafter3= [];
        $datakey = [];

        foreach($data1 as $dd){
            $dataafter1[$dd['district_id']] = $dd['cc'];
            $datakey[] = $dd['district_id'];
        }

        foreach($data2 as $dd){
            $dataafter2[$dd['district_id']] = $dd['cc'];
            $datakey[] = $dd['district_id'];
        }

        $datakey = array_unique($datakey);

        foreach($datakey as $dt){
            $dataafter3[$dt] = (isset($dataafter1[$dt])?$dataafter1[$dt]:0)+(isset($dataafter2[$dt])?$dataafter2[$dt]:0);
        }

        return $this->render('/homepage/homepages',['release' => $release ,'pagination'=>$pagination,'news'=>$rows,'data'=>$dataafter3]);
    }

    public function actionHomemap(){
        //$this->layout = false;
        $this->title = '清道夫债管家';
        $category = Yii::$app->request->get('category');
        $money = Yii::$app->request->get('money');
        $district = Yii::$app->request->get('district');
        $nsearch = Yii::$app->request->get('nsearch');
        $where = "";
        if(in_array($category,[1,2,3])){
            $where .= " and category = '{$category}'";
        }
        if($nsearch){
            $where.=" and seatmortgage like '%{$nsearch}%'";
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
        if($district!=0){
            $where.=" and district_id = '{$district}'";
        }
        $this->title = '清道夫债管家';
        if($district==0){

            $sql1 = "select count(*) as cc,district_id from zcb_finance_product where  progress_status <> 0 {$where} group by district_id";
            $sql2= "select count(*) as cc,district_id from zcb_creditor_product where  progress_status <> 0 {$where}  group by district_id";

            $data1 =  \Yii::$app->db->createCommand($sql1)->queryAll();
            $data2 =  \Yii::$app->db->createCommand($sql2)->queryAll();


            $dataafter1= [];
            $dataafter2= [];
            $dataafter3= [];
            $datakey = [];

            foreach($data1 as $dd){
                $dataafter1[$dd['district_id']] = $dd['cc'];
                $datakey[] = $dd['district_id'];
            }

            foreach($data2 as $dd){
                $dataafter2[$dd['district_id']] = $dd['cc'];
                $datakey[] = $dd['district_id'];
            }

            $datakey = array_unique($datakey);

            foreach($datakey as $dt){
                $dataafter3[$dt] = (isset($dataafter1[$dt])?$dataafter1[$dt]:0)+(isset($dataafter2[$dt])?$dataafter2[$dt]:0);
            }

        }else{
            $sql = "select id,district_id,seatmortgage,category,browsenumber from zcb_finance_product where  progress_status <> 0 {$where}
             union select id,district_id,seatmortgage,category,browsenumber from zcb_creditor_product where  progress_status <> 0 {$where} ";

            $dataafter3 =  \Yii::$app->db->createCommand($sql)->queryAll();

        }

        return $this->render('/homepage/homemap',['data'=>$dataafter3]);
    }

    //新闻内容
    public function actionNewscontent($cid){
        $this->title = '清道夫债管家';
       $news = news::findOne(['id'=>$cid]);
        return $this->render('/homepage/NewsContent',['news'=>$news]);
    }

    //新闻列表
    public function actionNewslist(){
        $this->title = '清道夫债管家';
        $news ="select * from zcb_news order by create_time desc";
        $query = \Yii::$app->db->createCommand($news)->query();
        $pagination = new Pagination(['defaultPageSize'=>6,'totalCount'=>$query->count()]);
        $news = $news . " limit ".$pagination->offset." , ".$pagination->limit;
        $rows = \Yii::$app->db->createCommand($news)->query();
        return $this->render('/homepage/NewsList',['pagination'=>$pagination,'news'=>$rows]);
    }

    //个人、律所案例
    public function actionIndividual(){
        $this->title = '你好';
        $model = "select * from zcb_classic_case order by create_time desc";
        $individual = \Yii::$app->db->createCommand($model)->query();
        return $this->renderPartial('/homepage/IndividualCase',['individual'=>$individual]);
    }

    //个人，律所显示
    public function actionDescindividual(){
        $indi = \common\models\ClassicCase::findOne(['id'=>Yii::$app->request->post('id')]);
        echo $indi->abstract;die;
    }

    //个人、律所列表
    public function actionPersonal(){
        $this->title = '清道夫债管家';
        $sql = "select * from zcb_classic_case  order by create_time desc";
        $query = \Yii::$app->db->createCommand($sql)->query();
        $pagination = new Pagination(['defaultPageSize'=>6,'totalCount'=>$query->count()]);
        $personal = $sql . " limit ".$pagination->offset." , ".$pagination->limit;
        $individual = \Yii::$app->db->createCommand($personal)->query();
        return $this->render('/homepage/PersonalStory',['pagination'=>$pagination,'individual'=>$individual]);
    }
    //介绍
    public function actionIntroduce($id){
        $this->title = '你好';
        $intr = \common\models\ClassicCase::findOne(['id'=>$id]);
        return $this->render('/homepage/introduce',['intr'=>$intr]);
    }

    //介绍下面数据
    public function actionEvaluate(){
        $this->title = '你好';
        return $this->renderPartial('/homepage/Evaluate');
    }
    public function actionRecord(){
        $this->title = '你好';
        return $this->renderPartial('/homepage/Record');
    }
    public function actionStar(){
        $this->title = '你好';
        return $this->renderPartial('/homepage/StarLevel');
    }
}