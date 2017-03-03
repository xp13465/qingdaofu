<?php
namespace wx\controllers;
use wx\services\Func;
use yii;
class UserController extends \wx\components\FrontController{
    public $enableCsrfValidation = false;
	public $layout = 'newmain';
    //用户中心
    public function actionIndex(){
        $this->title = "清道夫债管家-用户中心";
        $token = Yii::$app->session->get('user_token');
		$str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/userinfo/info'),['token'=>$token]);
        $arr    = $this->json2array($str);
        if($arr['code'] == '0000'){
           $data  = $arr['result']['data'];
			return $this->render('index',['data'=>$arr['result']['data'],'userid'=>$arr['userid']]);
        }else{
            $this->notFound();
        }
		
    }
	
	//个人中心
    public function actionInfo(){
		$token = Yii::$app->session->get('user_token');
		$str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/userinfo/info'),['token'=>$token]);
        $arr    = $this->json2array($str);
		if($arr['code'] == '0000'){
           $data  = $arr['result']['data'];
			return $this->render('info',['data'=>$arr['result']['data'],'userid'=>$arr['userid']]);
        }else{
            $this->notFound();
        }
    }
	
	//个人中心
    public function actionHead(){
		
		$token = Yii::$app->session->get('user_token');
		$str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/userinfo/info'),['token'=>$token]);
        $arr    = $this->json2array($str);
		if($arr['code'] == '0000'){
           $data  = $arr['result']['data'];
			return $this->render('head',['data'=>$arr['result']['data'],'userid'=>$arr['userid']]);
        }else{
            $this->notFound();
        }
    }
	
	 //设置
    public function actionSetup(){
        $this->title = "清道夫债管家-帮助中心";
        return $this->render('setup');
    }
	
	
	/**
	*	 附件上传
	*	param Filetype integer  上传规则和地址识别码 
	*/
	public function actionUpload($filetype=1)
    {    
        $model = new \common\models\UploadForm();
        $model->imageFile = \wx\components\UploadedFile::getInstance($model, 'file');
        $return = $model->upload($filetype,false);
		if($return['error']!=0){ 
			echo  \yii\helpers\Json::encode(['code'=>$return['error'],'msg'=>isset($return['msg'])&&$return['msg']?$return['msg'][0]:'']);die;
		}
		$token = Yii::$app->session->get('user_token');
        $image_file = ".".$return['url'];
        $info = pathinfo($image_file);
        $image_info = getimagesize($image_file);
        $base64_image_content = chunk_split(base64_encode(file_get_contents($image_file))); 
		var_dump(base64_encode(file_get_contents($image_file)));
		var_dump($base64_image_content);
		exit;
        echo Func::CurlPost(yii\helpers\Url::toRoute(['/wap/userinfo/uploadsimg']),['token'=>$token,'data'=>$base64_image_content,'filetype'=>1,'extension'=>isset($info['extension'])?$info['extension']:'']);
        
    }
	
	/**
	*	 附件上传
	*	param Filetype integer  上传规则和地址识别码 
	*/
	public function actionUpload2($filetype=1)
    {    
		$token = Yii::$app->session->get('user_token');
		// var_dump($_POST);
		// $base64_image_content = str_replace($_POST['basechar']);
		$basechar = Yii::$app->request->post("basechar");
		if(!$basechar)return false;
		$basecharArr=explode(",",$basechar);
		if(count($basecharArr)!==2)return false;
		$basecharArr1 = explode(";",$basecharArr[0]);
		$basecharArr2 = explode("/",$basecharArr1[0]);
		$extension = $basecharArr2[1];
		$base64_image_content = $basecharArr[1];
		// exit;
		echo Func::CurlPost(yii\helpers\Url::toRoute(['/wap/userinfo/uploadsimg']),['token'=>$token,'data'=>$base64_image_content,'filetype'=>1,'extension'=>$extension]);
       
    }
	public function actionNkname(){
        if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $nickname = Yii::$app->request->post('nickname');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/userinfo/nkname'), ['token'=>$token,'nickname'=>$nickname]);die;
        }
    }
	public function actionChangemobileform(){
		$this->title = "清道夫债管家-修改手机号";
        $token = Yii::$app->session->get('user_token');
        $backurl = Yii::$app->request->get('backurl',true);
		$str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/certification/view'),['token'=>$token]);
        $arr    = $this->json2array($str);
        
		$userinfo  = $arr['result']['userinfo'];
		return $this->render("changemobileform",['userinfo'=>$userinfo,'backurl'=>$backurl]);
    }
	
	public function actionChecksmscode(){

        if(Yii::$app->request->isAjax){
            $type = Yii::$app->request->post('type',4);
            $mobile = Yii::$app->request->post('mobile');
            $code = Yii::$app->request->post('code');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/user/checksmscode'), ['mobile'=>$mobile,'type'=>$type,'code'=>$code]);die;
        }

    }
	public function actionSmscode(){

        if(Yii::$app->request->isAjax){
            $type = Yii::$app->request->post('type',4);
            $mobile = Yii::$app->request->post('mobile');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/user/smscode'), ['mobile'=>$mobile,'type'=>$type]);die;
        }

    }
	public function actionChangemobile(){
        if(Yii::$app->request->isAjax){
			$token = Yii::$app->session->get('user_token');
            $oldmobile = Yii::$app->request->post('oldmobile');
            $newmobile = Yii::$app->request->post('newmobile');
            $oldcode = Yii::$app->request->post('oldcode');
            $newcode = Yii::$app->request->post('newcode');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/userinfo/changemobile'), ['token'=>$token,'oldmobile'=>$oldmobile,'newmobile'=>$newmobile,'oldcode'=>$oldcode,'newcode'=>$newcode]);die;
        }

    }
	
	//用户详情
    public function actionDetail(){
		$token = Yii::$app->session->get('user_token');
		$userid = Yii::$app->request->get('userid');
        $productid = Yii::$app->request->get('productid');
		$str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/userinfo/detail'),['token'=>$token,'userid'=>$userid,'productid'=>$productid]);
		$arr    = $this->json2array($str);
		
		$str1   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/userinfo/comment-list'),['token'=>$token,'userid'=>$userid,'page'=>1,'limit'=>10]);
		$arr1    = $this->json2array($str1);
		 // echo "<pre>";
		 // print_r($arr1['result']['data']);exit;
        if($arr['code'] == '0000'&&$arr1['code'] == '0000'){
            return $this->render('detail',['data'=>$arr["result"]['data'],"commentdata"=>$arr1['result']['data'],'userid'=>$arr["userid"],]);
        }else{
            $this->notFound();
        }
        
    }
}