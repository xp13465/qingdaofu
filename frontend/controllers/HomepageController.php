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
        return $this->redirect('/',301);
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
            $sql = "select id,district_id,code,seatmortgage,category,browsenumber from zcb_finance_product where  progress_status <> 0 {$where}
             union select id,district_id,code,seatmortgage,category,browsenumber from zcb_creditor_product where  progress_status <> 0 {$where} ";

            $dataafter3 =  \Yii::$app->db->createCommand($sql)->queryAll();

        }

        return $this->render('/homepage/homemap',['data'=>$dataafter3]);
    }

    //新闻内容
    public function actionNewscontent($cid){
		
		$brothers = true;
		$data = \app\models\Album::find()->detail($cid,false,$brothers);
		if(!$data)$this->errorMsg("NotFound","","view");
		
		
	
		$a = ( new \app\models\Album)->updateCounters(['view_count'=>1], 'id='.$data['id']);	//浏览次数  
		$newss = \app\models\Album::find()->getList([/*"category"=>$data["catalog_id"]*/],[],"10","HOT",false);
		
		// var_dump($data);
		
		
        // $newss = news::findOne(['id'=>$cid]);
        // $this->title = $newss->title.'-清道夫债管家';
        // $this->keywords = $newss->title.'-清道夫债管家';
        // $this->description = $newss->abstract.'-清道夫债管家';

        // $news ="select * from zcb_news order by create_time desc limit 10";
        // $newsRows = \Yii::$app->db->createCommand($news)->queryAll();

        // $next = Yii::$app->db->createCommand("select id from zcb_news where create_time<".$data['create_time']." order by create_time desc  limit 1")->queryScalar();
        // $pre = Yii::$app->db->createCommand("select id from zcb_news where create_time>".$data['create_time']." order by create_time asc limit 1")->queryScalar();

        return $this->render('/homepage/NewsContent',['news'=>$data,'rowss'=>$newss,'next'=>$brothers['next'],'pre'=>$brothers['prev']]);
    }

    //新闻列表
    public function actionNewslist($category=""){
        $this->title = '新闻列表-清道夫债管家';
		if($category&&in_array($category,[2,3,4])){
			$categoryArr = ["2"=>"公司新闻","3"=>"财经资讯","4"=>"行业动态"];
			$where =" category = {$category}";
			$label = $categoryArr[$category];
		}else{ 
			$where =" category != 0";
			$label ="新闻列表";
		}
		$params = Yii::$app->request->get();
		$provider = \app\models\Album::find()->getListProvider($params,["albumcontent"=>function($query){$query->select(["album_id","introduce"]);}]);
		// echo "<pre>";
		// print_r($provider->getModels());
		// exit;
		
        // $news ="select * from zcb_news where {$where} order by create_time desc";
        // $query = \Yii::$app->db->createCommand($news)->query();
        // $pagination = new Pagination(['defaultPageSize'=>6,'totalCount'=>$query->count()]);
        // $news = $news . " limit ".$pagination->offset." , ".$pagination->limit;
        // $rows = \Yii::$app->db->createCommand($news)->query();
		
        return $this->render('/homepage/NewsList',['provider'=>$provider,'news'=>$provider->getModels(),'label'=>$label]);
    }

    //个人、律所案例
    public function actionIndividual(){
        $this->title = '你好';
        $model = "select * from zcb_classic_case order by create_time desc";
        $individual = \Yii::$app->db->createCommand($model)->queryAll();
        return $this->renderPartial('/homepage/IndividualCase',['individual'=>$individual]);
    }

    //个人，律所显示
    public function actionDescindividual(){
        $indi = \common\models\ClassicCase::findOne(['id'=>Yii::$app->request->post('id')]);
        echo $indi->abstract;die;
    }

    //个人、律所列表
    public function actionPersonal(){
        $this->title = '经典案例-清道夫债管家';
        $sql = "select * from zcb_classic_case  order by create_time desc";
        $query = \Yii::$app->db->createCommand($sql)->query();
        $pagination = new Pagination(['defaultPageSize'=>6,'totalCount'=>$query->count()]);
        $personal = $sql . " limit ".$pagination->offset." , ".$pagination->limit;
        $individual = \Yii::$app->db->createCommand($personal)->query();
        return $this->render('/homepage/PersonalStory',['pagination'=>$pagination,'individual'=>$individual]);
    }
    //介绍
    public function actionIntroduce($id){
        $intr = \common\models\ClassicCase::findOne(['id'=>$id]);
        $this->title = $intr->name.'-清道夫债管家';
        return $this->render('/homepage/introduce',['intr'=>$intr]);
    }

    //介绍下面数据
    public function actionEvaluate(){
        $this->title = '清道夫债管家';
        return $this->renderPartial('/homepage/Evaluate');
    }
    public function actionRecord(){
        $this->title = '清道夫债管家';
        return $this->renderPartial('/homepage/Record');
    }
    public function actionStar(){
        $this->title = '清道夫债管家';
        return $this->renderPartial('/homepage/StarLevel');
    }

    //新手指引
    public function actionGuideline(){
        $this->title = '新手帮助-清道夫债管家';
        $this->keywords = '新手帮助';
        $this->description = '新手帮助';
        return $this->render('/homepage/guidelines');
    }

    //业务流程
    public function actionBusiness(){
        $this->title = '业务流程-清道夫债管家';
        $this->keywords = '业务流程';
        $this->description = '业务流程';
        return $this->render('/homepage/business');
    }

}