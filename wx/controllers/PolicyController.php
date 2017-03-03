<?php
namespace wx\controllers;
use wx\services\Func;
use yii;

class PolicyController extends \wx\components\FrontController
{
    public $enableCsrfValidation = false;

    /**
     * 添加代理人页面
     *
     **/
    public function actionAdd()
    {
        $this->title = "清道夫债管家-申请保函";
        $this->keywords = "清道夫债管家-申请保函";
        $this->description = "清道夫债管家-申请保函";
        $id = Yii::$app->request->get('id');
        $token = Yii::$app->session->get('user_token');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/policy/area-province'),['token'=>$token,'id'=>$id]);
        //echo $str;exit;
		$arr = yii\helpers\Json::decode($str);
		
        if($arr['code'] == '0000') {
            return $this->render('add',['provinceData'=>$arr['result']['data']]);
        }else{
            return $this->render('add',['provinceData'=>[]]);
        }
    }

    /**
     * 添加代理人数据保存
     *
     **/
    public function actionAdddata()
    {
		 $this->title = "清道夫债管家-申请保函";
		 $this->keywords = "清道夫债管家-申请保函";
        $this->description = "清道夫债管家-申请保函";
		// print_r($_POST);exit;
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $post['token'] = Yii::$app->session->get('user_token');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/policy/create'),$post);
            die;
        }
    }
    
    
    //图片
    public function actionPicture($id){
        $token = Yii::$app->session->get('user_token');
        $data =  Func::CurlPost(Yii\helpers\Url::toRoute('/wap/policy/tupian'),['token'=>$token,'id'=>$id]);
        $provinces = $this->json2array($data);
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
    
    public function actionPicturedata(){
        if(Yii::$app->request->isAjax){
            $post = Yii::$app->request->post();
            $post['token'] = Yii::$app->session->get('user_token');
            echo Func::CurlPost(Yii\helpers\Url::toRoute('/wap/policy/picturedatas'),$post);
        }
        
    }
    
    
    
    /**
     * 代理人展示页面
     *
     **/
    public function actionAddok(){
        return $this->render('addok');
         
    }

    /**
     * 代理人列表
     *
     */
    public function actionIndex(){
        $this->title = "清道夫债管家-我的保函";
        $this->keywords = "清道夫债管家-我的保函";
        $this->description = "清道夫债管家-我的保函";
        $token = Yii::$app->session->get('user_token');
	    $page = Yii::$app->request->get('page',1);
		$limit = Yii::$app->request->get('limit',10);
		$type = yii::$app->request->get('type');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/policy/index'),['token'=>$token,'page'=>$page,'limit'=>$limit,'type'=>$type]);
		$arr = $this->json2array($str);
        if($arr['code'] == '0000'){
            $data = $arr['result'];
            return $this->render('index',['model'=>$data]);
        }else{
            $this->notFound();
        }

    }
	
	 public function actionAreaCity()
    {
        $this->title = "清道夫债管家-城市列表";
        $this->keywords = "清道夫债管家-城市列表";
        $this->description = "清道夫债管家-城市列表";
		
		$depdrop_parents = Yii::$app->request->post('depdrop_parents');
        $token = Yii::$app->session->get('user_token');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/policy/area-city'),['token'=>$token,'depdrop_parents[0]'=>$depdrop_parents[0]]);
		
        $arr = yii\helpers\Json::decode($str);
		
        if($arr['code'] == '0000'){
            $data = $arr['result']['data'];
            $html = \yii\helpers\Html::dropDownList('', '',\yii\helpers\ArrayHelper::map($data, 'id','name'));
			$html = str_replace("</select>","",$html);
			$html = trim(str_replace('<select name="">',"",$html));
			echo yii\helpers\Json::encode(['code'=>'0000','html'=>$html]);die;
        }else{
			$html = \yii\helpers\Html::dropDownList('', '',[""=>"请选择..."]);
			$html = str_replace("</select>","",$html);
			$html = trim(str_replace('<select name="">',"",$html));
			echo yii\helpers\Json::encode(['code'=>'0000','html'=>$html]);die;
		}
    }
    
    //包涵数据查询
    public function actionBaohan(){
        $id = Yii::$app->request->get('id');
        $token = Yii::$app->session->get('user_token');
		
		$messageId = Yii::$app->request->get('messageid'); 
		
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/policy/baohan'),['token'=>$token,'id'=>$id,'messageid'=>$messageId]);
        $arr =$this->json2array($str);
        if($arr['code']== '0000'){
            $model = $arr['result']['model'];
        }
        return $this->render('baohan',['model'=>$model]);
    }

    public function actionFayuan() {
		
        $this->title = "清道夫债管家-法院列表";
        $this->keywords = "清道夫债管家-法院列表";
        $this->description = "清道夫债管家-法院列表";
		
		$depdrop_parents = Yii::$app->request->post('depdrop_parents');
        $token = Yii::$app->session->get('user_token');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/policy/fayuan'),['token'=>$token,'depdrop_parents[0]'=>$depdrop_parents[0],'depdrop_parents[1]'=>$depdrop_parents[1]]);
		
        $arr = yii\helpers\Json::decode($str);
		
        if($arr['code'] == '0000'){
            $data = $arr['result']['data'];
            $html = \yii\helpers\Html::dropDownList('', '',\yii\helpers\ArrayHelper::map($data, 'id','name'));
			$html = str_replace("</select>","",$html);
			$html = trim(str_replace('<select name="">',"",$html));
			echo yii\helpers\Json::encode(['code'=>'0000','html'=>$html]);die;
        }else{
			$html = \yii\helpers\Html::dropDownList('', '',[""=>"请选择..."]);
			$html = str_replace("</select>","",$html);
			$html = trim(str_replace('<select name="">',"",$html));
			echo yii\helpers\Json::encode(['code'=>'0000','html'=>$html]);die;
			
		}

    }
	
	 public function actionDemo()
    {
        // $this->title = "清道夫债管家-添加代理人";
        // $this->keywords = "清道夫债管家-添加代理人";
        // $this->description = "清道夫债管家-添加代理人";
        // $id = Yii::$app->request->get('id');
        // $token = Yii::$app->session->get('user_token');
        // $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/wxpay/unifiedorder'),['token'=>$token,'id'=>$id]);
        // $arr = yii\helpers\Json::decode($str);
		
	 
		
		
		// var_dump($arr);exit;
		
        // if($arr['code'] == '0000') {
            return $this->render('demo'/*,['wxdata'=>$arr['data']]*/);
        // }else{
            // return $this->render('demo',['arr'=>[]]);
        // }
    }

}


