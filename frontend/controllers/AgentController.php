<?php
namespace wx\controllers;
use wx\services\Func;
use yii;

class AgentController extends \wx\components\FrontController
{
    public $enableCsrfValidation = false;

    /**
     * 添加代理人页面
     *
     **/
    public function actionAddagent()
    {
        $this->title = "清道夫债管家-添加代理人";
        $this->keywords = "清道夫债管家-添加代理人";
        $this->description = "清道夫债管家-添加代理人";
        $id = Yii::$app->request->get('id');
        $token = Yii::$app->session->get('user_token');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/certification/agentexhibition'),['token'=>$token,'id'=>$id]);
        $arr = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000') {
            $user = $arr['result']['user'];
            $certification = $arr['result']['certification']['category'];
            return $this->render('addagent',['user'=>$user,'certification'=>$certification]);
        }else{
            $certification = $arr['result']['certification']['category'];
            return $this->render('addagent',['certification'=>$certification]);
        }
    }

    /**
     * 添加代理人数据保存
     *
     **/
    public function actionAddagentdata()
    {
        $token = Yii::$app->session->get('user_token');
        if (Yii::$app->request->post()) {
            $cardon = Yii::$app->request->post('cardon');
            $mobile = Yii::$app->request->post('mobile');
            $name = Yii::$app->request->post('name');
            $password = Yii::$app->request->post('password');
            $zycardno = Yii::$app->request->post('zycardno');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/certification/addagent'), ['token' => $token, 'cardno' => $cardon, 'mobile' => $mobile, 'name' => $name, 'password' => $password, 'zycardno' => $zycardno]);
            die;
        }
    }
    /**
     * 代理人展示页面
     *
     **/
    public function actionAgentexhibition(){
        $this->title = "清道夫债管家-代理人信息";
        $this->keywords = "清道夫债管家-代理人信息";
        $this->description = "清道夫债管家-代理人信息";
        $id = Yii::$app->request->get('id');
        $token = Yii::$app->session->get('user_token');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/certification/agentexhibition'),['token'=>$token,'id'=>$id]);
        $arr = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $user = $arr['result']['user'];
            $certification = $arr['result']['certification']['category'];
            return $this->render('agentexhibition',['user'=>$user,'certification'=>$certification]);
        }else{
            return $this->render('agentexhibition');
        }

    }

    /**
     * 代理人列表
     *
     */
    public function actionAgentlist(){
        $this->title = "清道夫债管家-我的代理人";
        $this->keywords = "清道夫债管家-我的代理人";
        $this->description = "清道夫债管家-我的代理人";
        $token = Yii::$app->session->get('user_token');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/certification/agentlist'),['token'=>$token]);
        $arr = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $user = $arr['result']['user'];
            return $this->render('agentlist',['user'=>$user]);
        }else{
            return $this->render('agentlist');
        }

    }

    /**
     * 停用代理人列表
     *
     */
    public function actionStopagent(){
        $token = Yii::$app->session->get('user_token');
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $status = Yii::$app->request->post('status');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/certification/stopagent'),['token'=>$token,'id'=>$id,'status'=>$status]);
        }
    }

    /**
     * 修改代理人列表
     *
     */
    public function actionModifyagent(){
        $token = Yii::$app->session->get('user_token');
        if (Yii::$app->request->post()) {
            $id     = Yii::$app->request->post('id');
            $cardon = Yii::$app->request->post('cardon');
            $mobile = Yii::$app->request->post('mobile');
            $name = Yii::$app->request->post('name');
            $password = Yii::$app->request->post('password');
            $zycardno = Yii::$app->request->post('zycardno');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/certification/modifyagent'), ['token' => $token, 'cardno' => $cardon, 'mobile' => $mobile, 'name' => $name, 'password' => $password, 'zycardno' => $zycardno,'id'=>$id]);
            die;
        }
    }

}


