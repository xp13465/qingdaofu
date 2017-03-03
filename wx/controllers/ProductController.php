<?php
namespace wx\controllers;
use Yii;
use wx\services\Func;

Class ProductController extends \wx\components\FrontController{
	public $layout = 'newmain';
	/**
	* 创建发布产品
	*/
	public function actionCreate(){
		$this->title = "清道夫债管家";
        $this->keywords = "清道夫债管家";
        $this->description = "清道夫债管家";
		$token = Yii::$app->session->get('user_token');
		$productid = Yii::$app->request->get('productid');
		$data =  Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/province'),['token'=>$token]);
		$province = $this->json2array($data);
		if($province['code'] == '0000'){
			$province = $province['result']['data'];
		}
		
		$arr = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/view'),['token'=>$token,'productid'=>$productid]);
		$product = $this->json2array($arr);
		if($product['code'] == '0000'){
			$product = $product['result']['data'];
			return $this->render('create',['province'=>$province,'data'=>$product]);
		}else{
			return $this->render('create',['province'=>$province]);
		}
		
	}
	
	/**
	* 编辑产品
	*/
	public function actionEdit(){
		$post = Yii::$app->request->post();
		$post['token'] = Yii::$app->session->get('user_token');
		$category = "";
		if(isset($post['category'])){
			$post['category']= implode(",",$post['category']);
		}
        if(isset($post['entrust'])){
			$post['entrust']= implode(",",$post['entrust']);
		}
		if(isset($post['type'])&& $post['type']==1){
			$post['typenum'] = $post['typenum1'];
		}else{
			$post['typenum'] = $post['typenum2'];
		}
		echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/edit'),$post);die;
	}
	
	/**
	* 产品保存
	*/
	public function actionDraft(){
		$post = Yii::$app->request->post();
		$post['token'] = Yii::$app->session->get('user_token');
		$category = "";
		if(isset($post['category'])){
			$post['category']= implode(",",$post['category']);
		}
        if(isset($post['entrust'])){
			$post['entrust']= implode(",",$post['entrust']);
		}
		if(isset($post['type'])&& $post['type']==1){
			$post['typenum'] = $post['typenum1'];
		}else{
			$post['typenum'] = $post['typenum2'];
		}
		echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/draft'),$post);die;
	}
	
	/**
	* 发布产品动作
	*/
	public function actionProductParam(){
		$post = Yii::$app->request->post();
		$category = "";
		if(isset($post['category'])){
			$post['category']= implode(",",$post['category']);
		}
        if(isset($post['entrust'])){
			$post['entrust']= implode(",",$post['entrust']);
		}
		if(isset($post['type'])&& $post['type']==1){
			$post['typenum'] = $post['typenum1'];
		}else{
			$post['typenum'] = $post['typenum2'];
		}
		$post['token'] = Yii::$app->session->get('user_token');
	    echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/release'),$post);die;
	}
	
	/**
	* 城市处理
	*/
	public function actionCity(){
		$province_id = Yii::$app->request->post('province_id','310000');
		$token = Yii::$app->session->get('user_token');
		$data = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/city'),['token'=>$token,'province_id'=>$province_id]);
		$city = $this->json2array($data);
		if($city['code'] == '0000'){
			$city = $city['result']['data'];
			$html = \yii\helpers\Html::dropDownList('','',\yii\helpers\ArrayHelper::map($city,'cityID','city'));
			$html = trim(str_replace('<select name="">','',$html));
			$html = str_replace('</select>','',$html);
			return yii\helpers\Json::encode(['code'=>'0000','html'=>$html]);die;
		}
	}
	
	/**
	* 市区处理
	*/
	public function actionDistrict(){
		$city_id = Yii::$app->request->post('city_id','310100');
		$token = Yii::$app->session->get('user_token');
		$data = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/district'),['token'=>$token,'city'=>$city_id]);
		$district = $this->json2array($data);
		if($district['code']=='0000'){
			$district = $district['result']['data'];
			$html = \yii\helpers\Html::dropDownList('','',yii\helpers\ArrayHelper::map($district,'areaID','area'));
			$html = trim(str_replace('<select name="">','',$html));
			$html = str_replace('</select>','',$html);
			return yii\helpers\Json::encode(['code'=>'0000','html'=>$html]);die;
		}
	}
	
	/**
	* 车系处理
	*/
	public function actionAudi(){
		$brand_id = Yii::$app->request->post('brand_id');
		$token = Yii::$app->session->get('user_token');
		$data = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/audi'),['token'=>$token,'brand_id'=>$brand_id]);
		$audi = $this->json2array($data);
		if($audi['code']=='0000'){
			$audi = $audi['result']['data'];
			$html = \yii\helpers\Html::dropDownList('','',yii\helpers\ArrayHelper::map($audi,'id','name'));
			$html = trim(str_replace('<select name="">','',$html));
			$html = str_replace('</select>','',$html);
			return yii\helpers\Json::encode(['code'=>'0000','html'=>$html]);die;
		}
		
	}
	
	/**
	* 产品详情
	*/
	public function actionDetails(){
		$productid = Yii::$app->request->get('productid');
		$messageId = Yii::$app->request->get('messageId');
		$token = Yii::$app->session->get('user_token');
		$arr =  Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/province'),['token'=>$token]);
		$province = $this->json2array($arr);
		if($province['code'] == '0000'){
			$province = $province['result']['data'];
		}
		$brand = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/brand'),['token'=>$token]);
		$brand = $this->json2array($brand);
		if($brand['code'] == '0000'){
			$brand = $brand['result']['data'];
		}
		
		$str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/product-details'),['token'=>$token,'productid'=>$productid,'messageId'=>$messageId]);
		$data = $this->json2array($str);
		if($data['code'] == '0000'){
			$data = $data['result']['data'];
			return $this->render('details',['data'=>$data,'province'=>$province,'brand'=>$brand]);
		}else{
			$this->notFound();
		}
	}
	
	/**
	* 添加地址
	*/
	
	public function actionAddress(){
		$post = Yii::$app->request->post();
		$post['token'] = Yii::$app->session->get('user_token');
		echo $address = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/mortgage-add'),$post);die;
	}
	
	/**
	* 删除地址
	*/
	public function actionMortgageDel (){
		$mortgageid = Yii::$app->request->post('mortgageid');
		$token = Yii::$app->session->get('user_token');
		echo $address = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/mortgage-del'),['token'=>$token,'mortgageid'=>$mortgageid]);die;
	}
	/**
	* 编辑地址
	*/
	public function actionMortgageEdit(){
		$post = Yii::$app->request->post();
		$post['token'] = Yii::$app->session->get('user_token');
		echo $address = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/mortgage-edit'),$post);die;
	}
	
	/**
	* 编辑草稿产品
	*/
	public function actionPreservation(){
		$post = Yii::$app->request->post();
		$post['token'] = Yii::$app->session->get('user_token');
		$category = "";
		if(isset($post['category'])){
			$post['category']= implode(",",$post['category']);
		}
        if(isset($post['entrust'])){
			$post['entrust']= implode(",",$post['entrust']);
		}
		if(isset($post['type'])&& $post['type']==1){
			$post['typenum'] = $post['typenum1'];
		}else{
			$post['typenum'] = $post['typenum2'];
		}
		echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/preservation-edit'),$post);die;
	}
	
}