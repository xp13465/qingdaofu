<?php
namespace wx\controllers;
use wx\services\Func;
use yii;
use common\models\CreditorProduct;

class ListController extends \wx\components\FrontController{
    public $enableCsrfValidation = false;
	
    //产品列表
    public function actionList(){
        $this->title = "清道夫债管家-产品列表";
        $this->keywords = "清道夫债管家-产品列表";
        $this->description = "清道夫债管家-产品列表";
        $cat         = yii::$app->request->get('cat','0');
        $money       = yii::$app->request->get('money','0');
        $city_id     = yii::$app->request->get('city_id') == 310100?0:yii::$app->request->get('city_id','0');
        $progress    = yii::$app->request->get('progress','0');
        $area_id = yii::$app->request->get('area_id') == 310100?0:yii::$app->request->get('area_id','0');
        $province_id = yii::$app->request->get('province_id') == 310000?0:yii::$app->request->get('province_id','0');
        $page = Yii::$app->request->get('page');
        $cats = Func::CurlPost(yii\helpers\Url::toRoute("/wap/capital/list"),['category'=>$cat,'status'=>$progress,'money'=>$money,'area'=> $area_id,'city'=>$city_id,'province'=>$province_id,'page'=>$page,'limit'=>10]);
        $strArr = yii\helpers\Json::decode($cats);
        if($strArr['code'] == '0000'){
            $catr = $strArr['result'];
        }

        $province = Func::CurlPost(yii\helpers\Url::toRoute("/wap/common/province"));
        $provinceArr = yii\helpers\Json::decode($province);
        natsort($provinceArr);

        $citys = Func::CurlPost(yii\helpers\Url::toRoute("/wap/common/city"));
        $citysArr = yii\helpers\Json::decode($citys);

        $areas = Func::CurlPost(yii\helpers\Url::toRoute("/wap/common/area"));
        $areaArr = yii\helpers\Json::decode($areas);
        $model = new CreditorProduct;
        return $this->render('list',[
            'catr'=>isset($catr)?$catr:'',
            'province'=>$provinceArr,
            'citys' => $citysArr,
            'areas' => $areaArr,
            'model' => $model,
        ]);


    }

    public function actionCity(){

        if(Yii::$app->request->isAjax){
            $fatherID = Yii::$app->request->post('fatherID');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/common/city'), ['fatherID'=>$fatherID]);die;
        }

    }

    public function actionArea(){

        if(Yii::$app->request->isAjax){
            $fatherID = Yii::$app->request->post('fatherID');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/common/area'), ['fatherID'=>$fatherID]);die;
        }

    }


    //产品信息
    public function actionProduct(){
        $this->title = "清道夫债管家-产品详情";
        $this->keywords = "清道夫债管家-产品详情";
        $this->description = "清道夫债管家-产品详情";
            $token    = Yii::$app->session->get('user_token');
            $id       = Yii::$app->request->get('id');
            $category = Yii::$app->request->get('category');
            $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/capital/view'), ['token' => $token,'category'=>$category,'id'=>$id]);
            $arr = yii\helpers\Json::decode($str);
            if($arr['code'] == '0000'){
                $product = $arr['result']['product'];
            }
        return $this->render('productList',['product'=>$product]);
    }
    //精选产品列表
    public function actionPro(){
        $token    = Yii::$app->session->get('user_token');
        $id       = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
        $type     = Yii::$app->request->get('type');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/capital/view'), ['token' => $token,'category'=>$category,'id'=>$id]);
        $arr = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $credi = $arr['result']['product'];
        }
        return $this->render('proList',['credi'=>$credi,'type' => $type]);
    }

    //申请接单页面
    public function actionApplyorder(){
		return $this->actionApplyordernew();
		
        $this->title = "清道夫债管家-产品申请";
        $this->keywords = "清道夫债管家-产品申请";
        $this->description = "清道夫债管家-产品申请";
        $token    = Yii::$app->session->get('user_token');
        $id       = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/capital/view'), ['token' => $token,'category'=>$category,'id'=>$id]);
        $arr = yii\helpers\Json::decode($str);
        if($arr['code'] == '0000'){
            $apply = $arr['result']['product'];
            $uid   = $arr['uid'];
        }
        $certification = Func::CurlPost(yii\helpers\Url::toRoute('/wap/certification/views'), ['token' => $token,'uid'=>$apply['uid']]);
        $certi = yii\helpers\Json::decode($certification);
        if($certi['code'] == '0000'){
            $cert = $certi['result'];
        }
        $applys = Func::CurlPost(yii\helpers\Url::toRoute('/wap/capital/shoucans'), ['token'=>$token,'category'=>$category,'id'=>$id]);
        $arrs = yii\helpers\Json::decode($applys);
        if($arrs['code'] == '0000'){
            $app = $arrs['result'];
            $mobile = $arrs['mobile'];
        }
		
        return $this->render('applyorder',['apply'=>$apply,'certi'=>isset($cert)?$cert:'','applys'=>isset($app)?$app:'']);
    }
	
	
	//申请接单页面
    private function actionApplyordernew(){
        $this->title = "清道夫债管家-产品申请";
        $this->keywords = "清道夫债管家-产品申请";
        $this->description = "清道夫债管家-产品申请";
        $token    = Yii::$app->session->get('user_token');
        $id       = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
		$applys = Func::CurlPost(yii\helpers\Url::toRoute('/wap/capital/shoucanss'), ['token'=>$token,'category'=>$category,'id'=>$id]);
		$arr = $this->json2array($applys);
		if($arr['code'] == '0000'){
			$apply = $arr['result']['data'];
			$number = $arr['result']['number'];
			$certification = $arr['result']['certification'];
			$data = $arr['result']['appCount'];
		}else{
			$this->notFound();
		}
		return $this->render('applyorder',compact('apply','number','certification','data'));
		
        
    }
    //申请接单处理
    public function actionApplication(){
        $token    = Yii::$app->session->get('user_token');
        if(\Yii::$app->request->isAjax) {
            $id       = Yii::$app->request->post('id');
            $category = Yii::$app->request->post('category');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/capital/shenqing'),['id' => $id, 'category' => $category,'token'=>$token]);die;
        }
    }

    //收藏发布数据
    public function actionCollection(){
        $token    = Yii::$app->session->get('user_token');
        if(\Yii::$app->request->isAjax) {
            $id       = Yii::$app->request->post('id');
            $category = Yii::$app->request->post('category');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/capital/shoucang'),['id' => $id, 'category' => $category,'token'=>$token]);die;
        }
    }

    //提醒消息
    public function actionRemind(){
        $token = Yii::$app->session->get('user_token');
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $category = Yii::$app->request->post('category');
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/public/remind'),['id' => $id,'category' => $category ,'token'=>$token]);die;
        }
    }
	
	
	
	//产品列表
    public function actionIndex(){
		$this->layout='newmain';
        $this->title = "清道夫债管家-产品列表";
        $this->keywords = "清道夫债管家-产品列表";
        $this->description = "清道夫债管家-产品列表";
        $cat         = yii::$app->request->get('cat','0');
        $account       = yii::$app->request->get('account','0');
        $city     = yii::$app->request->get('city','0');
        $status    = yii::$app->request->get('status','0');
        $district = yii::$app->request->get('district','0');
        $province = yii::$app->request->get('province','0');
        $page = Yii::$app->request->get('page');
        $cats = Func::CurlPost(yii\helpers\Url::toRoute("/wap/product/index"),['page'=>$page,'limit'=>10,'province'=>$province,'city'=>$city,'district'=>$district,'account'=>$account,'status'=>$status]);
		$strArr = $this->json2array($cats);
		
        if($strArr['code'] == '0000'){
            $products = $strArr['result'];
        }else{
			$this->notFound();
		}
        $province = Func::CurlPost(yii\helpers\Url::toRoute("/wap/common/province"));
        $provinceArr = yii\helpers\Json::decode($province);
        natsort($provinceArr);
        $citys = Func::CurlPost(yii\helpers\Url::toRoute("/wap/common/city"));
        $citysArr = yii\helpers\Json::decode($citys);

        $areas = Func::CurlPost(yii\helpers\Url::toRoute("/wap/common/area"));
        $areaArr = yii\helpers\Json::decode($areas);
        $model = new CreditorProduct;
        return $this->render('index',[
            'products'=>$products,
            'province'=>$provinceArr,
            'citys' => $citysArr,
            'areas' => $areaArr,
            'model' => $model,
        ]);
    }
	
	//产品列表
    public function actionDetail($id){
		$this->layout='newmain';
        $this->title = "清道夫债管家-产品详情";
        $this->keywords = "清道夫债管家-产品详情";
        $this->description = "清道夫债管家-产品详情"; 
		$token = Yii::$app->session->get('user_token');
        $str = Func::CurlPost(yii\helpers\Url::toRoute("/wap/product/detail"),['productid'=>$id,'token'=>$token]);
		$strArr = $this->json2array($str);
		if($strArr['code']!='0000')$this->notFound();
		//var_dump($strArr);die;
        return $this->render('detail',[
            'data'=>$strArr['result']['data'],'userid'=>$strArr['userid']
        ]);
    }
	
	//收藏产品
    public function actionCollect(){
        $this->title = "清道夫债管家-用户中心";
        $productid = Yii::$app->request->post('productid');
        $token = Yii::$app->session->get('user_token');
		echo $str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/collect'),['token'=>$token,'productid'=>$productid]);
    }
	
	//取消收藏产品
    public function actionCollectCancel(){
        $this->title = "清道夫债管家-用户中心";
        $productid = Yii::$app->request->post('productid');
        $token = Yii::$app->session->get('user_token');
		echo $str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/collect-cancel'),['token'=>$token,'productid'=>$productid]);
    }
	//申请接单
    public function actionApply(){
        $this->title = "清道夫债管家-用户中心";
        $productid = Yii::$app->request->post('productid');
        $token = Yii::$app->session->get('user_token');
		echo $str   = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/apply'),['token'=>$token,'productid'=>$productid]);
    }
	
	
	
}
