<?php

namespace frontend\modules\wap\controllers;

use common\models\Apply;
use common\models\User;
use frontend\modules\wap\components\WapNoLoginController;
use yii;
use frontend\modules\wap\services\Func;
use yii\web\Controller;
use yii\helpers\Json;
use \yii\helpers\ArrayHelper;

/**
 * 用户操作控制器
 */
class CapitalController extends WapNoLoginController
{
    /**
     * 注册方法
     * @param string id
     * @param string category
     * @param string province
     * @param string city
     * @param string area
     * @param string money
     * @param string status
     * @param string page
     * @param string limit
     * @return json
     * **/
    public function actionList(){
        $id = Yii::$app->request->post('id')?Yii::$app->request->post('id'):'';
        $category = Yii::$app->request->post('category')?Yii::$app->request->post('category'):0;
        $province = Yii::$app->request->post('province')?Yii::$app->request->post('province'):0;
        $city = Yii::$app->request->post('city')?Yii::$app->request->post('city'):0;
        $area = Yii::$app->request->post('area')?Yii::$app->request->post('area'):0;
        $money= Yii::$app->request->post('money')?Yii::$app->request->post('money'):0;
        $status = Yii::$app->request->post('status')?Yii::$app->request->post('status'):0;
        $page = Yii::$app->request->post('page')?Yii::$app->request->post('page'):1;
        $limit = Yii::$app->request->post('limit')?Yii::$app->request->post('limit'):10;

        $where = "progress_status != 0  and is_del = 0 ";
        if($id){
            $where .= " and id = '{$id}' ";
        }
        if(!in_array($province,['0',''])) {
            $where .= " and province_id = '{$province}' ";
        }
        if(!in_array($city,['0',''])) {
            $where .= " and city_id = '{$city}' ";
        }
        if(!in_array($area,['0',''])) {
            $where .= " and district_id = '{$area}' ";
        }
        if(in_array($category,[1,2,3])){
            $where .= " and category = '{$category}'";
        }
        if(in_array($status,[1,2,3,4])){
            $where.=" and progress_status in ({$status})";
        }
        if ($money == 0 && !in_array($money, [1, 2, 3, 4])) {

        } else if (in_array($money, [1, 2, 3, 4])) {
            switch ($money) {
                case 1:
                    $where .= "and money < ".(30);
                    break;
                case 2:
                    $where .= "and money between ".(30). " and ".(100);
                    break;
                case 3:
                    $where .= "and money between ".(100)." and ".(500);
                    break;
                case 4:
                    $where .= "and money > ".(500);
                    break;
                default:
                    break;
            }
        }

        // $limitstr= "";
        // if(is_numeric($page)&&is_numeric($limit)){
            // $page = $page<=1?1:$page;
            // $limit = $limit<=0?10:$limit;
            // $limitstr = " limit ".($page-1)*$limit.",".$limit;
        // }
		
		$rows = (new \app\models\CreditorProduct)->getAllProduct($where,$page,$limit);
		foreach($rows as $k => $v){
			$rows[$k]['carbrand'] = \frontend\services\Func::getCarBrand($v['carbrand']);
			$rows[$k]['audi'] = \frontend\services\Func::getCarAudi($v['audi']);
		}
        echo Json::encode(['code'=>'0000','result'=>$rows]);die;
    }

    /**
     * 推荐产品
     * @param string page
     * @param string limit
     * @return json
     */
    public function actionRecommendlist(){
        $page = Yii::$app->request->post('page')?Yii::$app->request->post('page'):1;
        $limit = Yii::$app->request->post('limit')?Yii::$app->request->post('limit'):6;
        $limitstr= "";
		$where = 'is_del= 0 and  progress_status = 1 ';
		$rows = (new \app\models\CreditorProduct)->getAllTjProduct($where,$page,$limit);
		foreach($rows as $k => $v){
			$rows[$k]['carbrand'] = \frontend\services\Func::getCarBrand($v['carbrand']);
			$rows[$k]['audi'] = \frontend\services\Func::getCarAudi($v['audi']);
		}
		$Total['Qingshou'] = \frontend\services\Func::getQingshouTotal();
		$Total['Susong']   = \frontend\services\Func::getSusongTotal();
		$Total['Baohan']   =  \frontend\services\Func::getBaohanTotal();
		$Total['Baoquan']  =  \frontend\services\Func::getBaoquanTotal();
        echo Json::encode(['code'=>'0000','result'=>$rows,'sum'=>array_sum($Total)]);die;
    }

    /**
     *  查看产品详细
     * @param string id
     * @param string category
     * @return json
     */
    public function actionView(){
	//	$user = $this->loadUser();
        $id = Yii::$app->request->post('id',30);
        $category = Yii::$app->request->post('category',2);
        if(is_numeric($id)&&is_numeric($category)&&$id>0&&ArrayHelper::isIn($category,[1,2,3])){
            //products = Func::getProduct($category,$id);
			//$product = $products->toArray();
			$apply = \common\models\Apply::find()->joinWith(['certificationdata'])->asArray()->where(['zcb_apply.app_id'=>1,'zcb_apply.product_id'=>$id,'zcb_apply.category'=>$category])->one();
			$product = \common\models\CreditorProduct::find()->joinWith(['certificationdata'])->asArray()->where(['zcb_creditor_product.progress_status'=>[0,1,2,3,4],'zcb_creditor_product.id'=>$id,'zcb_creditor_product.category'=>$category])->one();
			$name = isset($apply['certificationdata']['name'])?\frontend\services\Func::HideStrRepalceByChar($apply['certificationdata']['name'],'*',2,2):'';
			$names = isset($product['certificationdata']['name'])?\frontend\services\Func::HideStrRepalceByChar($product['certificationdata']['name'],'*',2,2):'';
			$a = $product['commissionperiod']?$product['commissionperiod']:1;
            $delay = date("Y-m-d",strtotime(date("Y-m-d",$apply['agree_time']).' + '.$a." months ")) ;
			$user = ['username'=>$names,'jusername'=>$name,'app_id'=>$apply['app_id'],'mobile'=>$product['certificationdata']['mobile'],'jmobile'=>$apply['certificationdata']['mobile'],'id'=>$apply['id'],'delay'=>$delay];  
			$product['mortorage_community'] = isset($product['mortorage_community'])?\frontend\services\Func::HideStrRepalceByChar($product['mortorage_community'],'*',2,0):'';
			$product['seatmortgage'] = isset($product['seatmortgage'])?\frontend\services\Func::getSubstrs($product['seatmortgage']):'';
            
			$users = User::findOne(['id'=>$product['uid']]);
            if($users['pid']){
                $certification = \common\models\Certification::findOne(['uid'=>$users['pid']]);
            }else{
                $certification = \common\models\Certification::findOne(['uid'=>$users['id']]);
            }
			
			
            if($product['category']!=1) {
                $car = isset($product['loan_type'])&&$product['loan_type'] == 3?\frontend\services\Func::getCarBrand($product['carbrand']) . \frontend\services\Func::getCarAudi($product['audi']):'';
                $license = isset($product['loan_type'])&&$product['loan_type'] == 3?\common\models\creditorProduct::$licenseplate[$product['licenseplate']]:'';
                $creditorfile =isset($product['creditorfile'])?unserialize($product['creditorfile']):['name'=>''];
                $creditorinfo = isset($product['creditorinfo'])?unserialize($product['creditorinfo']):[];
                $borrowinginfo = isset($product['borrowinginfo'])?unserialize($product['borrowinginfo']):[];
                foreach($creditorinfo as $k =>$v){
                    $creditorinfo[$k]['creditorcardimage'] = isset($v['creditorcardimage'])?explode(',',$v['creditorcardimage']):[];
                }
                foreach($borrowinginfo as $k=>$v){
                    $borrowinginfo[$k]['borrowingcardimage'] = isset($v['borrowingcardimage'])?explode(',',$v['borrowingcardimage']):[];
                }
                //$creditorfile = [];
                foreach($creditorfile as $k=>$v){
                    $creditorfile[$k] = explode(',',$v);
                }
				$city_id = \frontend\services\Func::getCityNameById($product['city_id']);
				$district_id = \frontend\services\Func::getAreaNameById($product['district_id']);
				$province_id = \frontend\services\Func::getProvinceNameById($product['province_id']);
                $product=['product'=>$product,
                          'guaranteemethod'=>is_numeric($product['guaranteemethod'])?$product['guaranteemethod']:unserialize($product['guaranteemethod']),
                          'creditorfile'=>$creditorfile,
                          'creditorinfo'=>$creditorinfo,
                          'borrowinginfo'=>$borrowinginfo,
                          'car' => $car,
                          'license' => $license,
                          'state'=>$certification['state'],
						  'city_id'=>$city_id,
						  'district_id'=>$district_id,
						  'province_id'=>$province_id,
                       ];
            }else{
                $product = ['product'=>$product,'state'=>$certification['state']];
            }
            echo Json::encode(['code'=>'0000','result'=>$product,'uid'=>$users->id,'user'=>$user]);die;
        }else{
             echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    /**
     *  是否收藏
     * @param string id
     * @param string category
     * @return json
     */
    public function actionShoucans(){
        $id = Yii::$app->request->post('id')?Yii::$app->request->post('id'):'';
        $category = Yii::$app->request->post('category')?Yii::$app->request->post('category'):0;
        // $token = Yii::$app->request->post('token');
        // $user = User::findOne(['token'=>$token]);
        // if(!isset($user->id)||!$user->id){
            // echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
        // }else{
            // $this->user = $user;
            // Yii::$app->session->set("user_id",$user->id);
        // }
		$user = $this->loadUser();
        if(is_numeric($id)&&is_numeric($category)&&$id>0&&ArrayHelper::isIn($category,[1,2,3])) {
            $product = Func::getProduct($category,$id);
            $users = User::findOne(['id'=>$product['uid']]);
            $apply = Apply::findOne(['uid' => $user->id, 'category' => $category, 'product_id' => $id]);
            $mobile = $users['mobile'];
            if ($apply) {
                echo Json::encode(['code' => '0000', 'result' => $apply, 'mobile' => $mobile]);
                die;
            }else{
                echo Json::encode(['code' => '1001', 'msg' =>'false']);
                die;
            }
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }
	
	
	 public function actionShoucanss(){
        $id = Yii::$app->request->post('id');
		$category = Yii::$app->request->post('category');
		if(is_numeric($id) && is_numeric($category) && ArrayHelper::isIn($category,[1,2,3])){
			$product = \common\models\CreditorProduct::find()->joinWith(['appCount','certificationdata'])
			->where([
			'zcb_creditor_product.progress_status'=>[0,1,2,3,4],
			'zcb_creditor_product.id'=>$id,
			'zcb_creditor_product.category'=>$category
			])->one();
			
            $UpdateCount = \common\models\CreditorProduct::updateAllCounters(['browsenumber'=>1],['id'=>$id]);		
			$number = [];
			foreach($product['appCount'] as $value){
				if($value['app_id'] == 0){
					$number['shenqing'] = $value['id'];
				}else{
					$number['shoucang'] = $value['id'];
				}
			}

			$product['carbrand'] = \frontend\services\Func::getCarBrand($product['carbrand']);
			$product['audi'] = \frontend\services\Func::getCarAudi($product['audi']);
			
			$address = isset($product['city_id'])?\frontend\services\Func::getCityNameById($product['city_id']).\frontend\services\Func::getAreaNameById($product['district_id']).$product['seatmortgage']:$product['seatmortgage'];
			$guaranteemethods=is_numeric($product['guaranteemethod'])?$product['guaranteemethod']:unserialize($product['guaranteemethod']);
            $creditorfiles =isset($product['creditorfile'])?unserialize($product['creditorfile']):[];
            $creditorfile = [];
            foreach($creditorfiles as $k=>$v){
               $creditorfile[$k] = explode(',',$v);
            }
			$borrowinginfo = isset($product['borrowinginfo'])?unserialize($product['borrowinginfo']):[];
			foreach ($borrowinginfo as $k => $v){
				$borrowinginfo[$k]['borrowingcardimage'] = explode(',',$v['borrowingcardimage']);
			}
			$creditorinfo = isset($product['creditorinfo'])?unserialize($product['creditorinfo']):[];
			foreach ($creditorinfo as $k => $v){
				$creditorinfo[$k]['creditorcardimage'] = explode(',',$v['creditorcardimage']);
			}
            $add = ['address'=>$address,'guaranteemethod'=>$guaranteemethods,'creditorfile'=>$creditorfile,'borrowinginfo'=>$borrowinginfo,'creditorinfo'=>$creditorinfo];
	        $this->success("",['add'=>$add,'data'=>$product,'number'=>$number,'certification'=>$product['certificationdata'],'appCount'=>isset($product['appCount'][0])?$product['appCount'][0]:'']);
		}else{
			$this->errorMsg('ParamsCheck');
		}
        
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

    /**
     *  收藏方法
     * @param string id
     * @param string category
     * @return json
    */
    public function actionShoucang(){
        $id = Yii::$app->request->post('id')?Yii::$app->request->post('id'):'';
        $category = Yii::$app->request->post('category')?Yii::$app->request->post('category'):0;
        // $token = Yii::$app->request->post('token');
        // $user = User::findOne(['token'=>$token]);
        // if(!isset($user->id)||!$user->id){
            // echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
        // }else{
            // $this->user = $user;
            // Yii::$app->session->set("user_id",$user->id);
        // }
		$user = $this->loadUser();
        if(is_numeric($id)&&is_numeric($category)&&$id>0&&ArrayHelper::isIn($category,[1,2,3])){
            $product = Func::getProduct($category,$id);

            $certification = Func::getCertification();
            if($certification['category'] == 1){
				$this->errorMsg('CANTSGHOUCANG1');
            }
            if($certification['category'] == 2 && $product->category == 1){
				$this->errorMsg('CANTSGHOUCANG7');
            }
            if($certification['category'] == 3 && $product->category == 3){
				$this->errorMsg('CANTSGHOUCANG3');
            }
            if($product->uid == $user->id){
				$this->errorMsg('CANTSGHOUCANG5');
            }
            $apply = Apply::findOne(['uid'=>$user->id,'category'=>$category,'product_id'=>$id]);

            if(isset($apply->id)&&$apply->app_id == 2){
				$this->errorMsg('CANTSGHOUCANG2');
            }

            if(isset($apply->id)&&$apply->app_id == 0){
				$this->errorMsg('CANTSGHOUCANG4');
            }

            if(isset($apply->id)&&$apply->app_id == 1){
				$this->errorMsg('CANTSGHOUCANG6');
            }

            $apply_new = new Apply;
            $apply_new->category = $category;
            $apply_new->product_id = $id;
            $apply_new->app_id = 2;
            $apply_new->create_time = time();
            $apply_new->uid = $user->id;
            $apply_new->is_del = 0;
            $apply_new->agree_time = 0;
            if($apply_new->save()){
                Func::addMessagesPerType('收藏成功',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",收藏成功。",8,serialize(['id' => $product->id,'category' => $product->category]));
                $this->success("恭喜您收藏成功");
				
            }else{
				$this->errorMsg('ModelDataSave',$apply_new->errors);
                // echo Json::encode(['code'=>'1014','msg'=>$apply_new->errors]);die;
            }

        }else{
			$this->errorMsg('ParamsCheck');
        }
    }

    /**
     *  申请接单方法
     * @param string id
     * @param string category
     * @return json
    */
    public function actionShenqing(){
        $id = Yii::$app->request->post('id')?Yii::$app->request->post('id'):'';
        $category = Yii::$app->request->post('category')?Yii::$app->request->post('category'):0;
        $token = Yii::$app->request->post('token');
		$user = $this->loadUser(['id'=>Yii::$app->request->get('user_id')]);
         //$user = User::findOne(['token'=>$token]);
         if(!isset($user->id)||!$user->id){
             echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
         }else{
             $this->user = $user;
             $uid = $user->id;
             Yii::$app->session->set("user_id",$user->id);
         }
		 //$uid = Yii::$app->request->get('user_id');
		//$user = $this->loadUser();
        if(is_numeric($id)&&is_numeric($category)&&$id>0&&ArrayHelper::isIn($category,[1,2,3])){
            $product = Func::getProduct($category,$id);
			if(!$product)$this->errorMsg('ParamsCheck');
            $certification = Func::getCertification();
            if($product->uid == $uid){
				$this->errorMsg('CANTSHENQING','自己不能申请自己发布的数据');
            }
            if($certification['category'] == 1){
				$this->errorMsg('CANTSHENQING','个人用户只能用于发布数据');
            }
            if($certification['category'] == 2 && $product->category == 1){
				$this->errorMsg('CANTSHENQING','律所用户只能申请诉讼、清收');
            }
            if($certification['category'] == 3 && $product->category == 3){
				$this->errorMsg('CANTSHENQING','公司用户只能申请清收');
            }
           
            if(!$certification) {
                $this->errorMsg('UserAuthenticate');
            }
			
            $apply = \common\models\Apply::findOne(['uid'=>$uid,'category'=>$category,'product_id'=>$id]);
			
	   if (isset($apply) && $apply->app_id == 2) {
                $apply->app_id = 0;
				$status = $apply->apply($product);
				switch($status){
					case 'ok':
						$this->success("恭喜您申请成功");
						break;
					case 'ModelDataSave':
						$this->errorMsg('ModelDataSave',$apply->errors);
						break;
				}
                /*if ($apply->save()) {
                    Func::addMessagesPerType('申请成功', ($product->category == 1 ? '融资' : ($product->category == 2 ? '清收' : '诉讼')) . "编号:" . $product->code . ",请耐心等待发布方同意。", 8, serialize(['id' => $product->id, 'category' => $product->category]));
                    Func::addMessagesPerType('申请接单', ($product->category == 1 ? '融资' : ($product->category == 2 ? '清收' : '诉讼')) . "编号:" . $product->code . ",有人接单，请赶快去看看。", 9, serialize(['id' => $product->id, 'category' => $product->category]),$product->uid);
                    // echo Json::encode(['code' => '0000', 'msg' => '恭喜您申请成功']);
					$this->success("恭喜您申请成功");
                    die;
                } else {
					$this->errorMsg('ModelDataSave',$apply_new->errors);
                    // echo Json::encode(['code' => '1014', 'msg' => $apply->errors]);
                    die;
                }*/
            }
            if(isset($apply)&&$apply->app_id == 0){
				$this->errorMsg('CANTSHENQING','您已经申请对该产品接单');
            }

            if(isset($apply)&&$apply->app_id == 1){
				$this->errorMsg('CANTSHENQING','您对该产品已经申请成功');
            }
			
            $apply_new = new Apply;

            $apply_new->category = $category;
            $apply_new->product_id = $id;
            $apply_new->app_id = 0;
            $apply_new->create_time = time();
            $apply_new->uid = $uid;
            $apply_new->is_del = 0;
            $apply_new->agree_time = 0;
	    $status = $apply_new->apply($product);	
	    switch($status){
		case 'ok':
		$this->success("恭喜您申请成功");
		break;
		case 'ModelDataSave':
		$this->errorMsg('ModelDataSave',$apply_new->errors);
		break;
		}
            // if($apply_new->save()){
                // Func::addMessagesPerType('申请成功',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",请耐心等待发布方同意。",8,serialize(['id' => $product->id,'category' => $product->category]));
                // Func::addMessagesPerType('申请接单',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",有人接单，请赶快去看看。",9,serialize(['id' => $product->id,'category' => $product->category]),$product->uid);
                // echo Json::encode(['code'=>'0000','msg'=>'恭喜您申请成功']);die;
            // }else{
                // echo Json::encode(['code'=>'1014','msg'=>$apply_new->errors]);die;
            // }
			
        }else{
			$this->errorMsg('ParamsCheck');
            // echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    public function actionBanner($type="APP"){
		$bannerModels = new \app\models\Banner;
		$type = strtoupper($type);
		switch($type){
			case "APP":
				$size=["width"=>"auto","height"=>"200px"];
				$images = $bannerModels->getBanners($type);
				break;
			case "WEB":
				$size=["width"=>"auto","height"=>"400px"];
				$images = $bannerModels->getBanners($type);
				break;
			default:
				$size=["width"=>"auto","height"=>"auto"];
				$images[] = "";
				break;
		}
        // $images = ['banner1ios'=>Yii::$app->params['www'].'/images/banner1ios.png','banner1'=>Yii::$app->params['www'].'/protocol/carousel?type=1','banner2ios'=>Yii::$app->params['www'].'/images/banner2ios.png','banner2'=>Yii::$app->params['www'].'/protocol/carousel?type=2','banner3ios'=>Yii::$app->params['www'].'/images/banner3ios.png','banner3'=>Yii::$app->params['www'].'/protocol/carousel?type=3','banner4ios'=>Yii::$app->params['www'].'/images/banner4ios.png','banner4'=>Yii::$app->params['www'].'/protocol/carousel?type=4','banner5ios'=>Yii::$app->params['www'].'/images/banner5ios.png','banner5'=>Yii::$app->params['www'].'/protocol/carousel?type=5'];
        $this->success("",["size"=>$size,"banner"=>$images]);
    }
	
	public function actionAppstart($type="AppStart"){
		$bannerModels = new \app\models\Banner;
		$ad = $bannerModels->getBanners($type);;
		$duration = 5;
		if(!$ad)$duration = 0 ;
        $result = ['duration'=>$duration,'ad'=>$ad];
        
		$this->success("",$result);
    }
	
	public function actionAppstartImg($type="AppStart",$key=0){
		$bannerModels = new \app\models\Banner;
		$ad = $bannerModels->getBanners($type);
		if($ad&&isset($ad[$key])){
			$fileAddr ="./".str_replace(Yii::$app->params["www"],"",$ad[$key]["file"]);
			echo file_get_contents($fileAddr);
		}
    }
	
	public function actionPictures(){
        $images = ['baohan1ios'=>Yii::$app->params['www'].'/images/banner/baohan1ios.jpg','baodan1ios'=>Yii::$app->params['www'].'/images/banner/baodan1ios.jpg','baodan2ios'=>Yii::$app->params['www'].'/images/banner/baodan2ios.jpg','baodan3ios'=>Yii::$app->params['www'].'/images/banner/baodan3ios.jpg'];
        echo Json::encode($images);die;
    }

    //安卓系统更新版本号
    public function actionVersion(){
        $version = ['version'=>'1.0.6'];
        echo Json::encode($version);die;
    }
	
	/**
	*  统计数据
	*
	*/
	public function actionTotalClaims(){
		
		$Total['Qingshou'] = \frontend\services\Func::getQingshouTotal();
		$Total['Susong']   = \frontend\services\Func::getSusongTotal();
		$Total['Baohan']   =  \frontend\services\Func::getBaohanTotal();
		$Total['Baoquan']  =  \frontend\services\Func::getBaoquanTotal();
		$this->success("",['sum'=>array_sum($Total),'total'=>$Total,]);
		
	}
	
}
