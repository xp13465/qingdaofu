<?php

namespace wx\controllers;
use wx\services\Func;
use yii;
class CommonController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    public $title = '';
    public $keywords = '';
    public $description = '';
    public function actionUploadimage(){
        $this->layout = false;
        return $this->render('uploadimage');
    }
    public function actionUploadimagegallery(){
        $this->layout = false;
        return $this->render('uploadimagegallery');
    }

    public function actionViewimages(){
        $this->layout = false;
        $name = Yii::$app->request->get('name');
        $typeName = Yii::$app->request->get('typeName');
        return $this->render('viewimages',['name'=>$name,'typeName'=>$typeName]);
    }

    public function actionSearchcommunity(){
        $this->layout = 'main';
        return $this->render('searchcommunity');
    }

    public function actionGetsearch(){
        if(Yii::$app->request->isPost){
            $community =  '';
            if(Yii::$app->request->post('name')) {
                $community = Yii::$app->db->createCommand("select * from zcb_community where name like '%" . Yii::$app->request->post('name') . "%'")->queryAll();
            }
            if(empty($community)){
                echo yii\helpers\Json::encode(['code'=>'1001','result'=>Yii::$app->request->post('name')]);die;
            }else{
                echo yii\helpers\Json::encode(['code'=>'0000','result'=>$community]);die;
            }
        }
    }

    public function actionGallery(){
        if(Yii::$app->request->isPost){
            $str  = Yii::$app->request->post('str');

            return $this->render('gallery',['str'=>$str]);
        }
    }
}
