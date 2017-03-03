<?php
namespace wx\controllers;
use wx\services\Func;
use yii;
class ReleasesController extends \wx\components\FrontController{
    public $enableCsrfValidation = false;
    //数据展示
    public function actionIndex(){
        $this->title = "清道夫债管家-产品详情";
        $this->keywords = "清道夫债管家-产品详情";
        $this->description = "清道夫债管家-产品详情";
        $id       = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
        $token    = Yii::$app->session->get('user_token');
        $str      = Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/wxreleaseinformationwidget'),['token'=>$token,'id'=>$id,'category'=>$category]);
		$arr      = $this->json2array($str);
        if($arr['code'] == '0000'){
            $product = $arr['result']['product'];
            $uid     = $arr['uid'];
            return $this->render('releasedisplay',['product'=>$product,'uid'=>$uid]);
        }else{
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/wxreleaseinformationwidget'),['token'=>$token,'id'=>$id,'category'=>$category]);die;
            return $this->render('releasedisplay');
        }

    }

    //补充信息
    public function actionSupplement(){
        $this->title = "清道夫债管家-补充信息";
        $this->keywords = "清道夫债管家-补充信息";
        $this->description = "清道夫债管家-补充信息";
        $id       = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
        $token    = Yii::$app->session->get('user_token');
        $str      = Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/wxreleaseinformationwidget'),['token'=>$token,'id'=>$id,'category'=>$category]);
        $arr      = yii\helpers\Json::decode($str);
        if($arr){
            $product = $arr['result']['product'];
        }
        return $this->render('supplement',['product'=>isset($product)?$product:'']);
    }

    //终止、结案
    public function actionClosed(){
        $token = Yii::$app->session->get('user_token');
        if(Yii::$app->request->isAjax) {
            $id       = Yii::$app->request->post('id');
            $status   = Yii::$app->request->post('status');
            $category = Yii::$app->request->post('category');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/closed'),['token'=>$token,'id'=>$id,'category'=>$category,'status'=>$status]);die;
        }
    }

    //填写进度
    public function actionSpeedo(){
        $id       = Yii::$app->request->get('id');
        $token    = Yii::$app->session->get('user_token');
        $category = Yii::$app->request->get('category');
        $str      = Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/wxreleaseinformationwidget'),['token'=>$token,'id'=>$id,'category'=>$category]);
        $arr      = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $product = $arr['result']['product'];
            $uid = $arr['uid'];
            return $this->render('speedofprogress',['pro'=>$product,'uid'=>$uid]);
        }else{
            return $this->render('speedofprogress');
        }

    }

    //进度数据保存
    public function actionSpee(){
        if(Yii::$app->request->isAjax) {
            $token    = Yii::$app->session->get('user_token');
            $id       = Yii::$app->request->post('id');
            $category = Yii::$app->request->post('category');
            $status   = Yii::$app->request->post('radio');
            $content  = Yii::$app->request->post('content');
            $audit    = Yii::$app->request->post('audit');
            $case     = Yii::$app->request->post('case');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/speedo'),['token'=>$token,'product_id'=>$id,'category'=>$category,'status'=>$status,'content'=>$content,'audit'=>$audit,'case'=>$case]);die;
        }
    }

    //申请延期
    public function actionApplication(){
        return $this->render('application');
    }
    public function actionDelayapply(){
        $token = Yii::$app->session->get('user_token');
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $category = Yii::$app->request->post('category');
            $dalay_reason = Yii::$app->request->post('dalay_reason');
            $day = Yii::$app->request->post('day');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/delayapply'),['token'=>$token,'id'=>$id,'category'=>$category,'dalay_reason'=>$dalay_reason,'day'=>$day]);die;
        }
    }

    //确认延长时间
    public function actionCoufirm(){
        $token = Yii::$app->session->get('user_token');
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/coufirm'),['token'=>$token,'id'=>$id]);die;
        }
    }

    //取消延长时间
    public function actionCancel(){
        $token = Yii::$app->session->get('user_token');
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/cancel'),['token'=>$token,'id'=>$id]);die;
        }
    }
	
	public function actionTime(){
		$id = Yii::$app->request->get('id');
		$token = Yii::$app->session->get('user_token');
		$arr = Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/time'),['id'=>$id,'token'=>$token]);
		$str = $this->json2array($arr);
		if($str['code'] == '0000'){
			$data = $str['result']['data'];
			return $this->render('timeexpand',['data'=>$data]);
		}
		
	}
	
	
	
	
	
	
    //我的保存中的立即发布
    public function actionReleaselist(){
        $token    = Yii::$app->session->get('user_token');
        if(Yii::$app->request->isAjax) {
            $id       = Yii::$app->request->post('id');
            $category = Yii::$app->request->post('category');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/releaselist'),['token'=>$token,'id'=>$id,'category'=>$category]);die;
        }

    }

    //我的保存中的删除
    public function actionDeleteproduct(){
        $token = Yii::$app->session->get('user_token');
        if(Yii::$app->request->isAjax){
			$type =Yii::$app->request->post('type');
			if($type==1){
				$id = Yii::$app->request->post('id');
                echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/deleteproduct'),['token'=>$token,'id'=>$id,'type'=>$type]);die;
			}else{
				$id = Yii::$app->request->post('id');
                $category = Yii::$app->request->post('category');
                echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/deleteproduct'),['token'=>$token,'id'=>$id,'category'=>$category,'type'=>$type]);die;
			}
            
        }
    }
    //添加评价
    public function actionAddevaluation(){
        $id = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
        $token = Yii::$app->session->get('user_token');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/wxreleaseinformationwidget'),['token'=>$token,'id'=>$id,'category'=>$category]);
        $arr = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $product = $arr['result']['product']['code'];
            $creditor= $arr['creditor'];
        }

        return $this->render('addevaluation',['product'=>isset($product)?$product:'','creditor'=>isset($creditor)?$creditor:'']);
    }

    //评价保存
    public function actionAddevalus(){
        $token = Yii::$app->session->get('user_token');
        if(Yii::$app->request->isAjax) {
            $add = [
                'token'                   => $token,
                'isHide'                  => Yii::$app->request->post('isHide',0),
                'picture'                 => Yii::$app->request->post('cardimg'),
                'content'                 => Yii::$app->request->post('content'),
                'category'                => Yii::$app->request->post('category'),
                'product_id'              => Yii::$app->request->post('id'),
                'workefficiency'         => Yii::$app->request->post('workefficiency'),
                'serviceattitude'        => Yii::$app->request->post('serviceattitude'),
                'professionalknowledge' => Yii::$app->request->post('professionalknowledge'),
                'type'                     =>  Yii::$app->request->post('type')
            ];
            $json = Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/evaluate'),$add);
            echo $json;
            die;
        }
    }
	
	//取消申请
	public function actionCancels(){
		$token = Yii::$app->session->get('user_token');
		if(Yii::$app->request->isAjax){
			$id = Yii::$app->request->post('id');
			echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/cancels'),['token'=>$token,'id'=>$id]);die;
		}
		
		
	}

}
