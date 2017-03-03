<?php
namespace wx\controllers;
use wx\services\Func;
use yii;
class PreservationController extends \wx\components\FrontController
{
    //保全
    public function actionIndex(){
        $this->title = "清道夫债管家-诉讼保全";
          $this->keywords = "清道夫债管家-诉讼保全";
          $this->description = "清道夫债管家-诉讼保全";
        $id = Yii::$app->request->get('id')?Yii::$app->request->get('id'):'';
        $token = Yii::$app->session->get('user_token');
        $data =  Func::CurlPost(Yii\helpers\Url::toRoute('/wap/producedata/user'),['token'=>$token,'id'=>$id]);
        $province =  Func::CurlPost(yii\helpers\Url::toRoute('/wap/policy/area-province'),['token'=>$token]);
        $provinces = $this->json2array($province);
        $phone = $this->json2array($data);
        if($phone['code'] == '0000' || $provinces['code'] == '0000'){
            $phones = $phone['result'];
            $provinceData = $provinces['result']['data'];
            return $this->render('index',['model'=>$phones,'provinces'=>$provinceData]);
        }else{
           $this->notFound(); 
        }
        
    }
    
    //保全动作
    public function actionBaoquan(){
        if(Yii::$app->request->isAjax){
            $post = Yii::$app->request->post();
            $area_pid = @$post['area_pid'];
            $area_id = @$post['area_id'];
            $fayuan_id = @$post['fayuan_id'];
            $fayuan_name = @$post['fayuan_name'];
            $post['token'] = Yii::$app->session->get('user_token');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/producedata/index'),$post);exit;
        }
    }
    
    //图片
    public function actionPicture($id){
        $token = Yii::$app->session->get('user_token');
        $data =  Func::CurlPost(Yii\helpers\Url::toRoute('/wap/producedata/tupian'),['token'=>$token,'id'=>$id]);
        $provinces = $this->json2array($data);
        //var_dump($provinces);die;
        if($provinces['code'] == '0000'){
            $model = $provinces['result']['model'];
            $qisu = $provinces['result']['qisu'];
            $caichan = $provinces['result']['caichan'];
            $zhengju = $provinces['result']['zhengju'];
            $anjian = $provinces['result']['anjian'];
            return $this->render('picture',['model'=>$model,'qisu'=>$qisu,'caichan'=>$caichan,'zhengju'=>$zhengju,'anjian'=>$anjian,'id'=>$id]);  
        }else{
           $this->notFound();
        }
        
    }
    
    //种类图片获取
    public function actionPicturecategory(){
        $id = Yii::$app->request->post('id');
        $token =  Yii::$app->session->get('user_token');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/producedata/picturecategory'),['token'=>$token,'id'=>$id]);
        $arr = $this->json2array($str);
        if($arr['code'] == '0000'){
            //var_dump($arr['result']['data']);die;
             exit(Json_encode(['code'=>'0000','piclist'=>$arr['result']['data']])); 
        }
    }
    
    public function actionPicturedata(){
        if(Yii::$app->request->isAjax){
            $post = Yii::$app->request->post();
            $post['token'] = Yii::$app->session->get('user_token');
            echo Func::CurlPost(Yii\helpers\Url::toRoute('/wap/producedata/picturedatas'),$post);
        }
        
    }
    
    //提交成功页面
    public function actionSuccess($id){
        $this->title = "清道夫债管家-诉讼保全";
        $this->keywords = "清道夫债管家-诉讼保全";
        $this->description = "清道夫债管家-诉讼保全";
        return $this->render('success',['id'=>$id]);
    }
    
    //保全列表
    public function actionBaoquanlist(){
        $this->title = "清道夫债管家-诉讼保全";
          $this->keywords = "清道夫债管家-诉讼保全";
          $this->description = "清道夫债管家-诉讼保全";
        $token = Yii::$app->session->get('user_token');
		$page = Yii::$app->request->get('page',1);
		$limit = Yii::$app->request->get('limit',10);
		$type = yii::$app->request->get('type');
        $data = Func::CurlPost(yii\helpers\Url::toRoute('/wap/producedata/baoquanlist'),['token'=>$token,'page'=>$page,'limit'=>$limit,'type'=>$type]);
        $models = $this->json2array($data);
        if($models['code'] == '0000'){
            $model = $models['result']['result'];
            //$WeiComplete = $models['result']['WeiComplete'];
			return $this->render('baoquan',['model'=>$model]);
        }else{
			$this->notFound();
		}
       
    }
    
    //审核结果
    public function actionAudit(){
        $this->title = "清道夫债管家-诉讼保全";
          $this->keywords = "清道夫债管家-诉讼保全";
          $this->description = "清道夫债管家-诉讼保全";
        $id = Yii::$app->request->get('id');
        $token = Yii::$app->session->get('user_token');
		$messageId = Yii::$app->request->get('messageid'); 
        $data = Func::CurlPost(yii\helpers\Url::toRoute('/wap/producedata/audit'),['token'=>$token,'id'=>$id,'messageid'=>$messageId]);
        $models = yii\helpers\Json::decode($data);
        if($models['code'] == '0000'){
            $model = $models['result'];
        }
        return $this->render('audit',['model'=>$model]);
    }
}