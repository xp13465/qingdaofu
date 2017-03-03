<?php

namespace frontend\modules\wap\controllers;

use common\models\User;
use frontend\modules\wap\components\WapController;
use yii;
use frontend\modules\wap\services\Func;
use yii\web\Controller;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

/**
 * 用户操作控制器
 */
class ListController extends WapController
{
    public function actionIndex(){
        $status = Yii::$app->request->post('status');
        $page = Yii::$app->request->post('page');
        $limit = Yii::$app->request->post('limit');
        $uid = Yii::$app->session->get('user_id');
        $res = Func::getProductByStatus($status,$page,$uid,$limit);
        echo Json::encode(['code'=>'0000','result'=>$res]);die;
    }


    /**
     * 融资、清收、诉讼单条数据提取
     * @param string id
     * @param string category
     * @return json
     **/
    public function actionView(){
        $id = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('category');

        if(is_numeric($id)&&is_numeric($category)&&ArrayHelper::isIn($category,[1,2,3])){
            $product = Func::getProduct($category,$id);
            if(!$product){
                echo Json::encode(['code'=>'4001','msg'=>'没有数据，请检查参数是否正确']);die;
            }else{
                echo Json::encode(['code'=>'0000','result'=>$product]);die;
            }
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    /**
     * 我的保存
     * @return json
     **/
    public function actionMysave(){
        $status = 0;
        $page = Yii::$app->request->post('page');
        $limit = Yii::$app->request->post('limit');
        $uid = Yii::$app->session->get('user_id');
        $res = Func::getProductByStatus($status,$page,$uid,$limit);
        echo Json::encode(['code'=>'0000','result'=>$res]);die;
    }
}
