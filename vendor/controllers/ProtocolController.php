<?php
namespace frontend\controllers;

use common\models\DisposingProcess;
use common\models\FinanceProduct;
use Yii;
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

/**
 * Site controller
 */
class ProtocolController extends FrontController
{

    public function init(){
        $this->layout = 'protocol';
    }

    //居间协议（清收公司）
    public function actionMediacycollection(){
        $category=Yii::$app->request->get('category');
        $id=Yii::$app->request->get('id');

        if(!$category||!$id){
            return  $this->render('mediacycollection');
        }else{
            if(Yii::$app->user->isGuest){
                return $this->redirect('/');
            }
            $certification = $this->getCertification();
            $desc = $this->getDesc($category,$id,$this->getApplyUserId($category,$id,Yii::$app->user->getId()));
            return  $this->render('mediacycollection',['certification'=>$certification,'desc'=>$desc]);
        }

    }

    //居间协议（清收委托人）
    public function actionMediacyentrust(){
        $category=Yii::$app->request->get('category');
        $id=Yii::$app->request->get('id');
        if(!$category||!$id){
            return  $this->render('mediacyentrust');
        }else {
            if (Yii::$app->user->isGuest) {
                return $this->redirect('/');
            }
            $certification = $this->getCertification();
            $desc = $this->getDesc($category,$id,Yii::$app->user->getId());
            return  $this->render('mediacyentrust',['certification'=>$certification,'desc'=>$desc]);
        }
    }

    //居间协议（融资人）
    public function actionMediacyfinancing(){
        $category=Yii::$app->request->get('category');
        $id=Yii::$app->request->get('id');
        if(!$category||!$id){
            return  $this->render('mediacyfinancing');
        }else {
            if (Yii::$app->user->isGuest) {
                return $this->redirect('/');
            }
            $certification = $this->getCertification();
            $desc = $this->getDesc($category,$id,Yii::$app->user->getId());
            return  $this->render('mediacyfinancing',['certification'=>$certification,'desc'=>$desc]);
        }
    }

    //居间协议（投资人）
    public function actionMediacyinvestment(){
        $category=Yii::$app->request->get('category');
        $id=Yii::$app->request->get('id');
        if(!$category||!$id){
            return  $this->render('mediacyinvestment');
        }else {
            if(Yii::$app->user->isGuest){
                return $this->redirect('/');
            }
            $certification = $this->getCertification();
            $desc = $this->getDesc($category,$id,$this->getApplyUserId($category,$id,Yii::$app->user->getId()));
            return  $this->render('mediacyinvestment',['certification'=>$certification,'desc'=>$desc]);
        }
    }

    //居间协议（法律服务-律师事务所）
    public function actionMediacylawer(){
        $category=Yii::$app->request->get('category');
        $id=Yii::$app->request->get('id');
        if(!$category||!$id){
            return  $this->render('mediacylawer');
        }else {
            if (Yii::$app->user->isGuest) {
                return $this->redirect('/');
            }
            $certification = $this->getCertification();
            $desc = $this->getDesc($category,$id,$this->getApplyUserId($category,$id,Yii::$app->user->getId()));
            return  $this->render('mediacylawer',['certification'=>$certification,'desc'=>$desc]);
        }
    }

    //居间协议（法律服务-委托人）
    public function actionMediacylawentrust(){
        $category=Yii::$app->request->get('category');
        $id=Yii::$app->request->get('id');
        if(!$category||!$id){
            return  $this->render('mediacylawentrust');
        }else {
            if (Yii::$app->user->isGuest) {
                return $this->redirect('/');
            }
            $certification = $this->getCertification();
            $desc = $this->getDesc($category,$id,Yii::$app->user->getId());
            return  $this->render('mediacylawentrust',['certification'=>$certification,'desc'=>$desc]);
        }
    }

    public function actionRegisterprotocal(){
        return  $this->render('registerprotocal');
    }

    private function getDesc($category,$id,$uid){
        if(!$uid)return   $this->redirect('/');;
        $desc = '';
        if($category == 1){
            $desc = \common\models\FinanceProduct::findOne(['category'=>$category,'id'=>$id,'uid'=>$uid]);
        }elseif(in_array($category,[2,3])){
            $desc = \common\models\CreditorProduct::findOne(['category'=>$category,'id'=>$id,'uid'=>$uid]);
        }
        if(!$desc||$desc->progress_status < 2)return   $this->redirect('/');
        return $desc;
    }

    private function getApplyUserId($category,$id,$uid){
        $desca = \common\models\Apply::findOne(['category'=>$category,'product_id'=>$id,'uid'=>$uid,'app_id'=>1]);
        if(!$desca)return  $this->redirect('/');
        if($desca->category == 1){
            $desc = \common\models\FinanceProduct::findOne(['category'=>$desca->category,'id'=>$desca->product_id]);
        }elseif(in_array($desca->category,[2,3])){
            $desc = \common\models\CreditorProduct::findOne(['category'=>$desca->category,'id'=>$desca->product_id]);
        }
        if(!$desc) return $this->redirect('/');

        return $desc->uid;
    }

    private function getCertification(){
        $certification = \common\models\Certification::findOne(['uid'=>Yii::$app->user->getId(),'state'=>1]);
        return $certification;
    }
}

