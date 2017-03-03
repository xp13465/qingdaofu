<?php
namespace backend\controllers;

use Yii;
use backend\components\BackController;
// use yii\widgets\ActiveForm;
use common\models\News;
// use common\models\ClassicCase;
// use yii\db\ActiveRecord;
use yii\helpers;
use common\components;
use yii\data\Pagination;
use yii\filters\VerbFilter;
/**
 * Site controller
 */
class NewsController extends BackController
{

    public $modelClass = 'common\models\News';
    
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
	/*

    public function actionCreate()
    {
        $model = new $this->modelClass;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('crudMessage', 'Your item has been created.');
            return $this->redirect($this->getRedirectPage('create', $model));
        }

        return $this->renderIsAjax('create', compact('model'));
    }*/

    
    //添加新闻
    public function actionAddnews()
    {
        $id = Yii::$app->request->post('id');
        $news = news::findOne(['id' => $id]);
        if (isset($news['id'])&&$news['id'] == $id) {
              $news->title = Yii::$app->request->post('title');
              $news->abstract =  mb_substr(strip_tags(Yii::$app->request->post('content')),0,100,'utf8');
            
              $news->content = Yii::$app->request->post('content');
              $news->update_time = time();
              $news->adminId = Yii::$app->user->getId();
              $news->category = 0;
              if ($news->save()) {
                header("Content-type:text/html;charset=utf-8");
                exit("<script>alert('提交成功');location.href='/news/list';</script>");
            }
        } else {
            if (Yii::$app->request->post()) {
                $model = new \common\models\news();
                $model->load(Yii::$app->request->post(), '');
                $model->title = Yii::$app->request->post('title');
                $model->content = Yii::$app->request->post('content');
                $model->create_time = time();
                $model->update_time = time();
                $model->abstract = mb_substr(strip_tags(Yii::$app->request->post('content')),0,100,'utf8');
                $model->adminId = Yii::$app->user->getId();
                $model->category = 0;
                if ($model->save()) {
                    header("Content-type:text/html;charset=utf-8");
                    exit("<script>alert('提交成功');location.href='/news/list';</script>");
                };
            }
            return $this->render('/news/collectionNews',["news"=>$news]);
        }
    }
	/*
    //公告
    public function actionNotice(){
        $id = Yii::$app->request->post('id');
        $news = news::findOne(['id' => $id]);
        if (isset($news['id'])&&$news['id'] == $id) {
            $news->title = Yii::$app->request->post('title');
            $news->content = Yii::$app->request->post('content');
            $news->update_time = time();
            $news->adminId = Yii::$app->user->getId();
            $news->abstract = mb_substr(strip_tags(Yii::$app->request->post('content')),0,100,'utf8');
            $news->category = 1;
            if ($news->save()) {
                header("Content-type:text/html;charset=utf-8");
                exit("<script>alert('提交成功');location.href='/news/list';</script>");
            }
        } else {
            if (Yii::$app->request->post()) {
                $model = new \common\models\news();
                $model->load(Yii::$app->request->post(), '');
                $model->title = Yii::$app->request->post('title');
                $model->content = Yii::$app->request->post('content');
                $model->abstract = mb_substr(strip_tags(Yii::$app->request->post('content')),0,100,'utf8');
                $model->create_time = time();
                $model->update_time = time();
                $model->adminId = Yii::$app->user->getId();
                $model->category = 1;
                if ($model->save()) {
                    header("Content-type:text/html;charset=utf-8");
                    exit("<script>alert('提交成功');location.href='/news/list';</script>");
                };
            }
            return $this->render('/news/notice');
        }
    }*/
	
    //添加债权和清收新闻
    public function actionCollectionNews(){
        $id = Yii::$app->request->post('id');
        $news = news::findOne(['id' => $id]);
        if (isset($news['id'])&&$news['id'] == $id) {
            $news->title = Yii::$app->request->post('title');
            $news->abstract = mb_substr(strip_tags(Yii::$app->request->post('content')),0,100,'utf8');
            $news->content = Yii::$app->request->post('content');
            $news->category = Yii::$app->request->post('category');
			$create_time = Yii::$app->request->post('create_time');
			if($create_time && $time = strtotime($create_time)){
				$news->create_time = $time;
			}
            $news->update_time = time();
            $news->adminId = Yii::$app->user->getId();
            if ($news->save()) {
                header("Content-type:text/html;charset=utf-8");
                exit("<script>alert('提交成功');location.href='/news/list';</script>");
            }
        } else {
            if (Yii::$app->request->post()) {
                $model = new \common\models\news();
                $model->load(Yii::$app->request->post(), '');
                $model->title = Yii::$app->request->post('title');
                $model->content = Yii::$app->request->post('content');
                $model->category = Yii::$app->request->post('category');
                $create_time = Yii::$app->request->post('create_time');
				if($create_time&&$time = strtotime($create_time)){
					$model->create_time = $time;
				}else{
					$model->create_time = time();
				}
               
                $model->update_time = time();
                $model->abstract = mb_substr(strip_tags(Yii::$app->request->post('content')),0,100,'utf8');
                $model->adminId = Yii::$app->user->getId();
                if ($model->save()) {
                    header("Content-type:text/html;charset=utf-8");
                    exit("<script>alert('提交成功');location.href='/news/list';</script>");
                };
            }
            return $this->render('/news/collectionNews',["news"=>$news]);
        }
    }
	
    //新闻列表
    public function actionList(){
        $this->title = '你好';
        $news ="select * from zcb_news order by create_time desc";
        $query = \Yii::$app->db->createCommand($news)->query();
        $pagination = new Pagination(['defaultPageSize'=>10,'totalCount'=>$query->count()]);
        $news = $news . " limit ".$pagination->offset." , ".$pagination->limit;
        $rows = \Yii::$app->db->createCommand($news)->query();
        return $this->render('/news/NewsList',['pagination'=>$pagination,'news'=>$rows]);
    }

    //编辑新闻
    public function actionEditor($id){
        $this->title = '你好';
        $news = news::findOne(['id'=>$id]);
        // if($news['category'] == 0) {
            // return $this->render('/news/AddNews',['news' => $news]);
        // }else if($news['category'] == 1){
            // return $this->render('/news/notice',['news' => $news]);
        // }else{
            return $this->render('/news/collectionNews',['news' => $news]);
        // }
    }

    //删除新闻
    public function actionDelete($id){
        $this->title = '你好';
        $news = news::findOne(['id'=>$id]);
        if ($news ->delete()) {
            header("Content-type:text/html;charset=utf-8");
            exit("<script>alert('删除成功');location.href='/news/list';</script>");
        };
    }
/*
    //添加个人律师新闻
    public function actionPersonal(){
        $this->title = '你好';
        $classic = new \common\models\ClassicCase();
        $classic->load(Yii::$app->request->post(), '');
        if(Yii::$app->request->post()){
            $picture = Yii::$app->imgload->UploadPhoto($classic, 'uploads/', 'picture');
            $classic->name = Yii::$app->request->post('name');
            $classic->occupation = Yii::$app->request->post('occupation');
            $classic->picture = $picture;
            $classic->casetext = Yii::$app->request->post('casetext');
            $classic->abstract = mb_substr(strip_tags(Yii::$app->request->post('content')),0,100,'utf8');
            $classic->create_time = time();
            $classic->update_time = time();
            if ($classic->save()) {
                header("Content-type:text/html;charset=utf-8");
                exit("<script>alert('提交成功');location.href='/news/personal';</script>");
            };
        }
        return $this->render('/news/PublicNwes');
    }
	*/
}
