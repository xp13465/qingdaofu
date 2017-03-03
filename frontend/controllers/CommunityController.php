<?php

namespace frontend\controllers;

use frontend\components\FrontController;
use frontend\services\Func;
use yii;
use yii\web\Controller;

class CommunityController extends FrontController
{
    public $layout = 'user';
    public $enableCsrfValidation = false;


    public function actionGetcommunity($cnum){
        //$content = Func::CurlGet("http://shanghai.anjuke.com/community/p".$cnum);
        try {
            $content = file_get_contents("http://shanghai.anjuke.com/community/p".$cnum);
        }catch (Exception $e){
            echo "<script type='text/javascript'> window.location = '/community/getcommunity?cnum=".$cnum."';</script>";
        }



        return $this->render("index",['content'=>$content,'cnum'=>$cnum]);
    }

    public function actionGetcc(){
            if(Yii::$app->request->post()){
                if(Yii::$app->db->createCommand("select name from zcb_community where name = '".Yii::$app->request->post('name')."'")->queryScalar()){

                }else{
                    Yii::$app->db->createCommand("insert into zcb_community set name = '".Yii::$app->request->post('name')."'")->query();
                }

                echo 1;die;
            }
    }

    public function actionSearchcommunity(){
        return $this->render("searchcommunity");
    }

    public function actionGetsearch(){
        if(Yii::$app->request->isPost&&Yii::$app->request->post('name')){
            $community = Yii::$app->db->createCommand("select * from zcb_community where name like '%".Yii::$app->request->post('name')."%'")->queryAll();

            echo json_encode($community);die;
        }
    }
}


