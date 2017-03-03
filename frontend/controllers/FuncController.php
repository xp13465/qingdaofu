<?php
namespace frontend\controllers;

use common\models\Evaluate;
use frontend\services\Func;
use Yii;
use frontend\components\FrontController;
use yii\helpers;

/**
 * Func controller
 * 所有公用的AJAX控制器
 */
class FuncController extends FrontController
{
    //根据省份获取城市
    public function actionGetcity(){
        if(Yii::$app->request->isAjax){
            $pid = Yii::$app->request->post('province_id');

            $citys = Func::getCityByProvince($pid);

            $str = "";

            foreach($citys as $k=>$v){
                $str .= "<option value='{$k}'>{$v}</option>";
            }

            echo $str;
            die;
        }
    }
    public function actionGetaudi(){
        if(Yii::$app->request->isAjax){
            $pid = Yii::$app->request->post('carbrand');

            $car = Func::getAudiByBrand($pid);

            $str = "";

            foreach($car as $k=>$v){
                $str .= "<option value='{$k}'>{$v}</option>";
            }

            echo $str;
            die;
        }
    }

    //根据城市获取区域
    public function actionGetdistrict(){
        if(Yii::$app->request->isAjax){
            $pid = Yii::$app->request->post('city_id');

            $districts = Func::getDistrictByCity($pid);

            $str = "";

            foreach($districts as $k=>$v){
                $str .= "<option value='{$k}'>{$v}</option>";
            }

            echo $str;
            die;
        }
    }

    public function actionViewimages(){
        $name = Yii::$app->request->get('name');
        $typeName = Yii::$app->request->get('typeName');
        return $this->renderPartial('viewimages',['name'=>$name,'typeName'=>$typeName]);
    }

    public function actionEvaluate(){

        if(Yii::$app->request->isPost){
            $evaluate = new Evaluate();
            $evaluate->load(Yii::$app->request->post(),'');

            $evaluate->create_time = time();
            if(Yii::$app->request->post('category') == 1){
                $product = \common\models\FinanceProduct::findOne(['category'=>Yii::$app->request->post('category'),'id'=>Yii::$app->request->post('product_id')]);
            }elseif(in_array(Yii::$app->request->post('category'),[2,3])){
                $product = \common\models\CreditorProduct::findOne(['category'=>Yii::$app->request->post('category'),'id'=>Yii::$app->request->post('product_id')]);

            }else{
                echo 2;die;
            }

            $apply = \common\models\Apply::findOne(['category'=>Yii::$app->request->post('category'),'product_id'=>Yii::$app->request->post('product_id'),'app_id'=>1]);

            if($product->uid == Yii::$app->user->getId()){
                $uid = Yii::$app->user->getId();
                $bid = $apply->uid;
            }else{
                $uid =Yii::$app->user->getId();
                $bid =  $product->uid;
            }
            $evaluate->uid = $uid;
            $evaluate->buid = $bid;


            $res = $evaluate->save();

            if($res){
                if($product->uid != Yii::$app->user->getId()) {
                    \frontend\services\Func::addMessage(10, \yii\helpers\Url::to(['/list/chakan', 'id' => $product->id, 'category' => $product->category]), ['{1}'=>Func::$category[$product->category],'{2}' => $product->code],$bid);
                }else{
                    \frontend\services\Func::addMessage(10, \yii\helpers\Url::to(['/order/chakan', 'id' => $product->id, 'category' => $product->category]), ['{1}'=>Func::$category[$product->category],'{2}' => $product->code],$bid);
                }
                echo 1;
                die;
            }
        }
        return $this->renderPartial('evaluate');
    }

    public function actionUploadsimg(){
        return $this->renderPartial('uploadsImg');
    }
    public function actionUploadsimgsingle(){
        return $this->renderPartial('uploadsImgsingle');
    }
}

