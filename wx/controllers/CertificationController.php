<?php

namespace wx\controllers;
use wx\services\Func;
use yii;
class CertificationController extends \wx\components\FrontController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $this->title = "清道夫债管家-认证信息";
        $this->keywords = "清道夫债管家-认证信息";
        $this->description = "清道夫债管家-认证信息";
        $token = Yii::$app->session->get('user_token');
        $modify = Yii::$app->request->get('modify', 0);
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/certification/view'), ['token' => $token]);
        $arr = yii\helpers\Json::decode($str);
        $completionRate =  $this->Adds($arr);
        if($modify != 0){
            return $this->render('index',['status'=>isset($arr['result']['certification']['category'])?$arr['result']['certification']['category']:'']);die;
        }
        if ($arr['code'] == '0000') {
            $certification = $arr['result']['certification'];
            switch ($certification['category']) {
                case 1:
                    return $this->render('personalList', ['certification' => $certification,'completionRate'=>$completionRate]);
                    break;
                case 2:
                    return $this->render('lawyerList',['certification'=>$certification,'completionRate'=>$completionRate]);
                    break;
                case 3:
                    return $this->render('orgnizationList',['certification'=>$certification,'completionRate'=>$completionRate]);
                break;
                default;
                    break;
            }
        }
        return $this->render('index');die;
    }

    public function Adds($arrs){

        if($arrs['code'] == '000'){
            $certifi = $arrs['result']['certification'];
            switch ($certifi['category']){
                case 1:
                    $archivement = array(
                        1 => $certifi['name'],
                        2 => $certifi['cardno'],
                        3 => $certifi['mobile'],
                        4 => $certifi['email'],
                        5 => $certifi['casedesc'],
                        6 => $certifi['cardimg'],
                    );
                    break;
                case 2:
                    $archivement = array(
                        1 => $certifi['name'],
                        2 => $certifi['mobile'],
                        3 => $certifi['cardno'],
                        4 => $certifi['email'],
                        5 => $certifi['contact'],
                        6 => $certifi['casedesc'],
                        7 => $certifi['cardimg'],
                        );
                    break;
                case 3:
                    $archivement = array(
                        1 => $certifi['name'],
                        2 => $certifi['mobile'],
                        3 => $certifi['cardno'],
                        4 => $certifi['email'],
                        5 => $certifi['contact'],
                        6 => $certifi['casedesc'],
                        7 => $certifi['cardimg'],
                        8 => $certifi['enterprisewebsite'],
                        9 => $certifi['address']
                    );
                    break;
                default:
                    break;
            }
             return Func::ArchivementRate($archivement);
        }
    }


    //认证
    public function actionAdd(){
            $token = Yii::$app->session->get('user_token');
            $status = Yii::$app->request->get('status',0);
            $rate = Yii::$app->request->get('rate',0);
            $certification = Func::CurlPost(yii\helpers\Url::toRoute('/wap/certification/view'),['token'=>$token]);
            $certi = yii\helpers\Json::decode($certification);
            $completionRate =  $this->Adds($certi);

        if (Yii::$app->request->isAjax) {
            $name = Yii::$app->request->post('name');
            $cardno = Yii::$app->request->post('cardno');
            $contact = Yii::$app->request->post('contact');
            $mobile = Yii::$app->request->post('mobile');
            $email = Yii::$app->request->post('email');
            $casedesc = Yii::$app->request->post('casedesc');
            $cardimg = Yii::$app->request->post('cardimg');
            $enterprisewebsite = Yii::$app->request->post('enterprisewebsite');
            $address = Yii::$app->request->post('address');
            $status = Yii::$app->request->post('status',0);
			$cardimgimg = Yii::$app->request->post('cardimgimg');
        }
        switch($status){
            case 1:
                $this->title = "清道夫债管家-个人认证";
                $this->keywords = "清道夫债管家-个人认证";
                $this->description = "清道夫债管家-个人认证";
                if($certi['code'] == '0000'){
                    $type = "update";
                }else{
                    $type = "add";
                }
                if(Yii::$app->request->isAjax) {
                    $category = 1;
                    echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/certification/add'), ['name' => $name,'mobile'=>$mobile,'token' => $token, 'cardno' => $cardno,
                        'email' => $email, 'casedesc' => $casedesc, 'category' => $category, 'type' => $type, 'cardimg' => $cardimg, 'completionRate' => $completionRate,'cardimgimg'=>$cardimgimg]);
                    die;
                }
                 return $this->render('personal', ['certi' => isset($certi['result']['certification'])&&$certi['result']['certification']['category'] ==1?$certi['result']['certification']:'','mobile'=>isset($certi['result']['user'])?$certi['result']['user']:'']);
                break;
            case 2:
                $this->title = "清道夫债管家-律所认证";
                $this->keywords = "清道夫债管家-律所认证";
                $this->description = "清道夫债管家-律所认证";
                if($certi['code'] == '0000'){
                    $type = "update";
                }else{
                    $type = "add";
                }
                if(Yii::$app->request->isAjax) {
                    $category = 2;
                    $lawyers = array(
                        'name'     => $name,
                        'cardno'   => $cardno,
                        'contact'  => $contact,
                        'mobile'   => $mobile,
                        'email'    => $email,
                        'casedesc'=> $casedesc,
                        'category'=>$category,
                        'type'    => $type,
                        'completionRate'    => $completionRate,
                        'token'             => $token,
                        'cardimg'           =>$cardimg,
						'cardimgimg'=>$cardimgimg,
                    );
                    echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/certification/add'),$lawyers);
                    die;
                }
                return $this->render('lawyer',['certi'=>isset($certi['result']['certification'])&&$certi['result']['certification']['category'] ==2?$certi['result']['certification']:'','mobile'=>isset($certi['result']['user'])?$certi['result']['user']:'']);
                break;
            case 3:
                $this->title = "清道夫债管家-公司认证";
                $this->keywords = "清道夫债管家-公司认证";
                $this->description = "清道夫债管家-公司认证";
                if($certi['code'] == '0000'){
                        $type = "update";
                }else{
                    $type = "add";
                }
                if(Yii::$app->request->isAjax) {
                    $category = 3;
                    $lawyers = array(
                        'name'  => $name, 'cardno'    => $cardno, 'contact'            => $contact, 'mobile'             => $mobile,
                        'email' => $email, 'casedesc' => $casedesc, 'category'        => $category, 'type'              => $type,
                        'token' => $token, 'cardimg'  => $cardimg, 'enterprisewebsite' => $enterprisewebsite, 'address' => $address, 'completionRate' => $completionRate,
						'cardimgimg'=>$cardimgimg,
                    );
                    echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/certification/add'),$lawyers);
                    die;
                }
                return $this->render('orgnization',['certi'=>isset($certi['result']['certification'])&&$certi['result']['certification']['category'] ==3?$certi['result']['certification']:'','mobile'=>isset($certi['result']['user'])?$certi['result']['user']:'']);
                break;
            default:
                break;
        }
        if($status == 0){
            return $this->redirect(yii\helpers\Url::toRoute('/certification/index'));
        }
    }
}
