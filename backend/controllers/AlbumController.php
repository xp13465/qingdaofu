<?php

namespace backend\controllers;

use Yii;
use backend\components\BackController;
use backend\components\Uploader;
use app\models\Album;
use app\models\AlbumContent;
use app\models\AlbumSearch;
use app\models\Tag;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AlbumController implements the CRUD actions for Album model.
 */
class AlbumController extends BackController
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Album models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AlbumSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Album model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Album model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Album();
	    $searchModel = new AlbumSearch();
		$post = Yii::$app->request->post();
        if ($post){
			$model->attributes = $post['Album'];
			$title_style = Yii::$app->request->post('style');
			 if($title_style) {
                $model->title_style = serialize($title_style);
            } else {    		
    			$model->title_style = '';
    		}
			$model->catalog_id =  $model->catalog_id?$model->catalog_id:0;
			$model->special_id =  $model->special_id?$model->special_id:0;
			$model->favorite_count =  $model->favorite_count?$model->favorite_count:0;
			$model->view_count =  $model->view_count?$model->view_count:0;
			$model->sort_order =  $model->sort_order?$model->sort_order:0;
			$model->attention_count =  $model->attention_count?$model->attention_count:0;
			$model->reply_count =  $model->reply_count?$model->reply_count:0;
			if(isset($post['Album']['tags']) && $post['Album']['tags']){
				$tag_name = str_replace(' ',',',trim($post['Album']['tags']));
		        $tag_name = str_replace('，',',',$tag_name);
				$tag_name = explode(',',$tag_name);
				$tag_name = implode(',',array_filter($tag_name));
				$model->tags = $tag_name;
			 }
			 if(!$post['Album']['introduce']){
				 $post['Album']['introduce'] = $searchModel::truncate_utf8_string(preg_replace('/\s+/',' ', $post['Album']['content']), 180);
			 }
			 
		   if($model->save()){
				$data = [
						'album_list' => $post['Album']['album_list'],
						'introduce' => $post['Album']['introduce'],
						'content' => $post['Album']['content'],
						'seo_description' => $post['Album']['seo_description'],
						'seo_title' => $post['Album']['seo_title'],
						'seo_keywords' => $post['Album']['seo_keywords'],
				];
				$searchModel->createContent($data,$model->id);
				$searchModel->createTag($model->tags);
				$searchModel->createTagDdata($model->tags,$model->id,$post['Album']['status']);
		        return $this->redirect(['view', 'id' => $model->id]);
			}else{
				var_dump($model->errors);die;
			}
			
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Album model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		// echo "<pre>";
		// print_r($model);die;
		$searchModel = new AlbumSearch();
		$post = Yii::$app->request->post();
        if ($post){
			$model->attributes = $post['Album'];
			$title_style = Yii::$app->request->post('style');
			 if($title_style) {
                $model->title_style = serialize($title_style);
            } else {    		
    			$model->title_style = '';
    		}
			if(isset($post['Album']['tags']) && $post['Album']['tags']){
				$tag_name = str_replace(' ',',',trim($post['Album']['tags']));
		        $tag_name = str_replace('，',',',$tag_name);
				$tag_name = explode(',',$tag_name);
				$tag_name = implode(',',array_filter($tag_name));
				$model->tags = $tag_name;
			 }
			 if(!$post['Album']['introduce']){
				 $post['Album']['introduce'] = $searchModel::truncate_utf8_string(preg_replace('/\s+/',' ', $post['Album']['content']), 180);
			 }
			 $model->special_id =  $model->special_id?$model->special_id:0;
			 $model->validflag = '1';
			 $model->update_time = time();
			 $data = [
						'album_id'=>$model->id,
						'album_list' => $post['Album']['album_list'],
						'introduce' => $post['Album']['introduce'],
						'content' => $post['Album']['content'],
						'seo_description' => $post['Album']['seo_description'],
						'seo_title' => $post['Album']['seo_title'],
						'seo_keywords' => $post['Album']['seo_keywords'],
				];
			if($model->save()){
				$searchModel->createContent($data,$model->id);
				$tagStatus = $searchModel->createTag($model->tags);
				if($tagStatus == 'OK'){
					$searchModel->createTagDdata($model->tags,$model->id,$model->status);
				}
				return $this->redirect(['view', 'id' => $model->id]);	
			}
        }else {		
    		$style = unserialize($model->title_style);
            return $this->render('update', [
                'model' => $model,
				'style' => $style,
            ]);
        }
    }

    /**
     * Deletes an existing Album model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id='')
    {
		$id = Yii::$app->request->post('id');
        $this->findModel($id)->updateAll(['validflag'=>'0'],['validflag'=>'1','id'=>$id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Album model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Album the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {   
	    $query = Album::find();
		$query->alias('Album');
		$query->joinWith(['contentOne','files','certification','user']);
		$query->andFilterWhere(['Album.id'=>$id]);
		$model = $query->one();
		$model->album_lists = isset($model->contentOne)&&$model->contentOne->album_list?$model->getFile(explode(',',$model->contentOne->album_list)):'';
		// echo "<pre>";
		// print_r($model->album_lists);die;
		if(isset($model->contentOne)&&$model->contentOne){
			$model->content = $model->contentOne->content;
			$model->album_list = $model->contentOne->album_list;
			$model->introduce = $model->contentOne->introduce;
			$model->seo_description = $model->contentOne->seo_description;
			$model->seo_title = $model->contentOne->seo_title;
			$model->seo_keywords = $model->contentOne->seo_keywords;
		}
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	//图片上传
	public function actionUploader(){
		
		// $model = new \common\models\UploadForm();
		// var_dump($model);die;
        // if (Yii::$app->request->isPost) { 
		// var_dump($model);die;
            // $model->imageFile = \frontend\components\UploadedFile::getInstance($model, 'Filedata');
			// $data = $return = $model->upload($filetype,true);
			// unset($return['tempName']);
			// echo \yii\helpers\Json::encode($return);
        // }
		
		
		
		// array(4) { 
		// ["file_name"]=> string(15) "c603d72643c.jpg" 
		// ["file_path"]=> string(35) "upload/album/201612/c603d72643c.jpg" 
		// ["file_path_full"]=> string(51) "http://xw.admin/upload/album/201612/c603d72643c.jpg" 
		// ["file_ext"]=> string(3) "jpg" }
		
		$uploader = new Uploader();
		$filetype = Yii::$app->request->get('filetype');
        if($filetype) {
            //断点上传
            $uploader->initResumable('album')->uploadResumable('file');
            $error = $uploader->getError();
			var_dump($error);die;
            if (!$error) {
                $data = array(
                    'file_name'      => $uploader->file_name,
                    'file_path'      => $uploader->file_path,
                    'file_path_full' => Helper::getFullUrl($uploader->file_path),
                    'file_ext'       => $uploader->file_ext
                );   
                	echo "<pre>";
		        print_r($uploader->file_name);die;		
                App::response(200, 'success', $data);
            } else {  		
                App::response(101 , $error);
            }
			
        } else {
			echo 123456;
            //校验已上传的片段
            $uploader->checkExistChunks();
        }
	}
}
