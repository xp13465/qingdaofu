<?php
namespace wx\controllers;
use wx\services\Func;
use yii;
class UsercenterController extends \wx\components\FrontController{
    public $enableCsrfValidation = false;

    //用户中心
    public function actionIndex(){
        $this->title = "清道夫债管家-用户中心";
        $this->keywords = "清道夫债管家-用户中心";
        $this->description = "清道夫债管家-用户中心";
        $token = Yii::$app->session->get('user_token');
        $str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/certification/view'),['token'=>$token]);
        $arr   = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $certi = $arr['result']['certification'];
            $uid   = $arr['result']['uid'];
            $user  = $arr['result']['user'];
        }else{
            $uid   = isset($arr['result']['uid'])?$arr['result']['uid']:'';
            $user  = isset($arr['result']['user'])?$arr['result']['user']:'';
        }
        return $this->render('usercenter',['certi'=>isset($certi)?$certi:'','uid'=>isset($uid)?$uid:'','mobile'=>isset($user)?$user:'']);
    }

    //我的发布列表
    public function actionRelease(){
        $this->title = "清道夫债管家-我的发布";
        $this->keywords = "清道夫债管家-我的发布";
        $this->description = "清道夫债管家-我的发布";
        $status = Yii::$app->request->get('status',0);
        $token  = Yii::$app->session->get('user_token');
        $str    = Func::CurlPost(yii\helpers\Url::toRoute('/wap/apply/released'),['token' => $token ,'progress_status' => $status,'limit'=>10]);
        $arr    = yii\helpers\Json::decode($str);
        //var_dump($arr);die;
        if($arr['code'] == '0000'){
            $rows       = $arr['result']['rows'];
            $page       = $arr['result']['page'];
            $creditor   = $arr['result']['creditor'];
            return $this->render('release',['rele'=>$rows,'creditor'=>$creditor]);

        }else{
            return $this->render('release');
        }

    }

    //查看接单申请人
    public function actionView(){
        $id       = Yii::$app->request->get('id');
        $token    = Yii::$app->session->get('user_token');
        $category = Yii::$app->request->get('category');
        $str      = Func::CurlPost(yii\helpers\Url::toRoute('/wap/apply/ordertaking'),['token' => $token ,'id' => $id ,'category' => $category]);
        $arr      = yii\helpers\Json::decode($str);
       if($arr['code'] == '0000'){
            $user = $arr['user'];
           return $this->render('viewapplication',['user'=>$user]);
        }else{
           return $this->render('viewapplication');
       }

    }

    //案例
    public function actionCase(){
        $id = Yii::$app->request->get('id');
        $token = Yii::$app->session->get('user_token');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/apply/case'),['token'=>$token,'id'=>$id]);
        $arr = yii\helpers\Json::decode($str);
        return $this->render('case',['case'=>$arr['certification']['casedesc']]);
    }

    //接单放信息
    public function actionUserinfo(){
        $pid   = Yii::$app->request->get('pid');
        $token = Yii::$app->session->get('user_token');
        $id    = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
        $str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/apply/user'),['token'=>$token,'pid'=>$pid,'id'=>$id,'category'=>$category]);
        $arr   = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $certification = $arr['result']['certification'];
            $product       = $arr['result']['product'];
            $uid           = $arr['result']['uid'];
            $pid           = $arr['result']['pid'];
            return $this->render('userinfo',['certi'=>$certification,'uid'=>$uid,'product'=>$product,'pid'=>$pid]);die;
        }else{
           echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/apply/user'),['token'=>$token,'pid'=>$pid,'id'=>$id,'category'=>$category]);
            return $this->render('userinfo');die;
        }

    }

    //发布方信息
    public function actionOrdersuser(){
        $this->title = "清道夫债管家-用户信息";
        $this->keywords = "清道夫债管家-用户信息";
        $this->description = "清道夫债管家-用户信息";
        $token = Yii::$app->session->get('user_token');
        $id    = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
        $str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/apply/users'),['token'=>$token,'id'=>$id,'category'=>$category]);
        $arr   = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $certification = $arr['result']['certification'];
            $product       = $arr['result']['product'];
            return $this->render('ordersuser',['certi'=>$certification,'product'=>$product]);
        }else{
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/apply/user'),['token'=>$token,'id'=>$id,'category'=>$category]);
            return $this->render('ordersuser');die;
        }

    }

    //接单方评价列表
    public function actionReceived(){
        $this->title = "清道夫债管家-评价信息";
        $this->keywords = "清道夫债管家-评价信息";
        $this->description = "清道夫债管家-评价信息";
        $pid = Yii::$app->request->get('certi');
        $category = Yii::$app->request->get('category');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/apply/evaluatewidget'),['pid'=>$pid,'token'=>Yii::$app->session->get('user_token'),'limit'=>10,'category'=>$category]);
        $arr = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $evaluate = $arr['result']['evaluate'];
            $creditor = $arr['result']['creditor'];
        }
        return $this->render('receivedevaluation',['evaluate'=>isset($evaluate)?$evaluate:'','creditor'=>isset($creditor)?$creditor:'']);
    }

    //消息中心的评价信息
    public function actionEvaluatelist(){
        $uid   = Yii::$app->request->get('certi');
        $token = Yii::$app->session->get('user_token');
        $str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/apply/userlist'),['token'=>$token,'uid'=>$uid]);
        $arr   = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $evaluate = $arr['result']['evaluate'];
            $creditor = $arr['result']['creditor'];
            $launchevaluation = $arr['result']['launchevaluation'];
            $uid      = $arr['result']['uid'];
            return $this->render('evaluatelist',['eva'=>$evaluate,'creditor'=>$creditor,'uid'=>$uid,'launchevaluation'=>$launchevaluation]);
        }else{
            return $this->render('evaluatelist');
        }

    }

    //发布接单完结后的互相评价列表
    public function actionEvaluatelists(){
        $id   = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
        $token = Yii::$app->session->get('user_token');
        $str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/apply/userlists'),['token'=>$token,'id'=>$id,'category'=>$category]);
        $arr   = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000') {
            $evaluate = $arr['result']['evaluate'];
            $creditor = $arr['result']['creditor'];
            $launchevaluation = $arr['result']['launchevaluation'];
            $uid = $arr['result']['uid'];
            $evalua = $arr['result']['evalua'];
            $creditors = $arr['result']['creditors'];
            return $this->render('evaluatelist',['eva'=>$evaluate,'creditor'=>$creditor,'uid'=>$uid,'launchevaluation'=>$launchevaluation,'evalua'=>$evalua,'creditors'=>$creditors]);
        }else{
            return $this->render('evaluatelist');
        }
    }

    //删除自己发出的评价
    public function actionDeleteeva(){
        if(Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $sid = Yii::$app->request->post('sid',0);
            $token = Yii::$app->session->get('user_token');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/apply/deleteeva'), ['token' => $token, 'id' => $id,'sid'=>$sid]);
            die;
        }
    }
    
    //申请成功
    public function actionAgreeorder(){
        $token = Yii::$app->session->get('user_token');
        if(Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $uid = Yii::$app->request->post('uid');
            $category = Yii::$app->request->post('category');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/apply/agreeorder'), ['token' => $token, 'uid' => $uid, 'category' => $category, 'id' => $id]);die;
        }
    }

    //我的接单
    public function actionOrders(){
        $this->title = "清道夫债管家-我的接单";
        $this->keywords = "清道夫债管家-我的接单";
        $this->description = "清道夫债管家-我的接单";
        $progress_status = Yii::$app->request->get('progress_status');
        $token = Yii::$app->session->get('user_token');
        switch($progress_status){
            case 0:
                $pro = "01234";
                $status = "01";
                break;
            case 1:
                $pro = "1";
                $status = "0";
                break;
            case 2:
                $pro = "2";
                $status = "1";
                break;
            case 3:
                $pro = "3";
                $status = "1";
                break;
            case 4:
                $pro = "4";
                $status = "1";
                break;
            default:
                break;
        }
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/apply/index'),['token'=>$token,'progress_status'=>$pro,'status'=>$status,'limit'=>10]);
        $arr = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $rows       = $arr['result']['rows'];
            $page       = $arr['result']['page'];
            $creditor   = $arr['result']['creditor'];
            return $this->render('orders',['rows'=>$rows,'creditor'=>$creditor]);
        }else{
            return $this->render('orders');
        }

    }

    //设置
    public function actionSetup(){
        $this->title = "清道夫债管家-设置";
        $this->keywords = "清道夫债管家-设置";
        $this->description = "清道夫债管家-设置";
        return $this->render('setup');
    }

    //退出登录
    public function actionOut(){
        $token = Yii::$app->session->get('user_token');
        echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/user/logout'), ['token' => $token]);
    }

    //查询进度
    public function actionSpeed(){
        $id       = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
        $token    = Yii::$app->session->get('user_token');
        $str      = Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/wxreleaseinformationwidget'),['token'=>$token,'id'=>$id,'category'=>$category]);
        $arr      = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $product = $arr['result']['product'];
            return $this->render('speed',['product'=>$product]);
        }else{
            return $this->render('speed');
        }

    }

    //我的保存
    public function actionPreservation(){
        $this->title = "清道夫债管家-我的保存";
        $this->keywords = "清道夫债管家-我的保存";
        $this->description = "清道夫债管家-我的保存";
        $token = Yii::$app->session->get('user_token');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/list/mysave'),['token'=>$token,'limit'=>10,'page'=>0]);
        $arr = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $pre = $arr['result']['rows'];
        }
        return $this->render('preservation',['pre'=>isset($pre)?$pre:'']);
    }

    //我的收藏
    public function actionCollection(){
        $this->title = "清道夫债管家-我的收藏";
        $this->keywords = "清道夫债管家-我的收藏";
        $this->description = "清道夫债管家-我的收藏";
        $token = Yii::$app->session->get('user_token');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/apply/myshoucang'),['token'=>$token,'limit'=>10,'page'=>0]);
        $arr = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $rows = $arr['result']['rows'];
        }
        return $this->render('collection',['rows'=>isset($rows)?$rows:'']);
    }

    //删除我的收藏
    public function actionDeletes(){
        $token    = Yii::$app->session->get('user_token');
        if(Yii::$app->request->isAjax) {
            $id       = Yii::$app->request->post('id');
            $category = Yii::$app->request->post('category');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/deletes'),['token'=>$token,'product_id'=>$id,'category'=>$category]);die;
        }
    }

    //暗号查询
    public function actionCode(){
        $token = Yii::$app->session->get('user_token');
        if(Yii::$app->request->isAjax){
            $category = Yii::$app->request->post('category');
            $audit    = Yii::$app->request->post('audit');
            $id       = Yii::$app->request->post('id');
            $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/code'),['token'=>$token,'category'=>$category,'audit'=>$audit,'id'=>$id]);
            $arr = yii\helpers\Json::decode($str);
            if($arr['code'] == '0000'){
                echo $arr['result'];die;
            }else{
                echo '1';
            }
        }
    }
}