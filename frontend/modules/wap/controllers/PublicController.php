<?php

namespace frontend\modules\wap\controllers;

use common\models\User;
use frontend\modules\wap\components\WapController;
use yii;
use frontend\modules\wap\services\Func;
use yii\web\Controller;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

/**
 * 公共发布列表数据展示
 */
class PublicController extends WapController{

    /**
     * 延期申请数据查询
     * @param id
     * @param category
     * @return json
     **/

    public function actionWxextendwidget(){
        $id = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('category');
		$uid = Yii::$app->session->get('user_id');
        if($token = Yii::$app->request->post('token')) {
            if (is_numeric($id) && is_numeric($category) && ArrayHelper::isIn($category, [1, 2, 3])) {
                $product = Func::getProduct($category, $id);
                $apply   = \common\models\Apply::findOne(['product_id'=>$id,'category'=>$category,'uid'=>$uid]);
                $delay   = \common\models\DelayApply::findOne(['product_id'=>$id,'category'=>$category]);
                if (!$product) {
                    echo Json::encode(['code' => '4001', 'msg' => '没有数据，请检查参数是否正确']);
                    die;
                } else {
                    if($product['category'] == 1){
                        $product = ['id'=>$product['id'],'category'=>$product['category'],'term'=>$product['term'],'rate_cat'=>$product['rate_cat'],'applyclose'=>$product['applyclose']];
                    }else{
                        $product = ['id'=>$product['id'],'category'=>$product['category'],'commissionperiod'=>$product['commissionperiod'],'applyclose'=>$product['applyclose'],'uid'=>$product['uid'],'progress_status'=>$product['progress_status']];
                    }
                    if($product['category'] == 1 && $product['rate_cat'] == 1){
                        $a = $product['term']?$product['term']:30;
                        $delayss = floor((strtotime(date("Y-m-d 23:59:59", $apply['agree_time']) . ' + ' . $a . " days ") - time()) / 3600 / 24);
                    }else if($product['category'] == 1 && $product['rate_cat'] == 2 ){
                        $a = $product['term']?$product['term']:1;
                        $delayss = floor((strtotime('+'.$a."months",$apply['agree_time'])-time())/3600/24) ;
                    }else{
                        $a = $product['commissionperiod']?$product['commissionperiod']:1;
                        $delayss = floor((strtotime('+'.$a."months",$apply['agree_time'])-time())/3600/24) ;
                    }
                    $delays   = ['is_agree'=>isset($delay['is_agree'])?$delay['is_agree']:'','delays'=>$delayss,'product_id'=>$delay['product_id'],'id'=>$delay['id']];
                    echo Json::encode(['code'=>'0000','result'=>['product'=>$product,'delay'=>$delays,'uid'=>$uid]]);
                    die;
                }
            } else {
                echo Json::encode(['code' => '1001', 'msg' => '参数错误']);
                die;
            }
        }else{
            echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
        }
    }

    /**
     * 延期数据提交
     * @param id
     * @param category
     * @param day
     * @param dalay_reason
     * @return json
     */
    public function actionDelayapply(){
        $id = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('category');
        $dalay_reason = Yii::$app->request->post('dalay_reason');
        $day = Yii::$app->request->post('day');
        $uid = Yii::$app->session->get('user_id');
        if(is_numeric($id) && is_numeric($category) && is_numeric($day) && ArrayHelper::isIn($category,[1,2,3])){
            $product = Func::getProduct($category, $id);
              if($day == ''){
                  echo Json::encode(['code'=>'1002','msg'=>'请输入天数']);die;
              }
            $model = new \common\models\DelayApply();
            $model->category = $category;
            $model->product_id = $id;
            $model->uid = $uid;
            $model->create_time = time();
            $model->delay_days = $day;
            $model->dalay_reason = $dalay_reason;
            $model->is_agree = 0;
            if($model->save()){
                Func::addMessagesPerType('申请延长时间',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",接单方发起延长时间请求，是否同意。<span><a href='javascript:void(0)'; class='cancel' style='background-color:#0065b3 ; color:#fff'>取消</a> <a href='javascript:void(0);' class='confirm' style='background-color:#0065b3;color:#fff'>确定</a><span>",19,serialize(['id' => $product->id,'category' => $product->category]),$product->uid);
                echo Json::encode(['code'=>'0000','msg'=>'提交成功']);die;
            }else{
                echo Json::encode(['code'=>'1014','msg'=>$model->errors]);die;
            }

        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }
	
	//数据查询
	public function actionTime(){
		$id = Yii::$app->request->post('id');
		if(is_numeric($id)){
		    $delay   = \common\models\DelayApply::findOne(['id'=>$id,'is_agree'=>0]);
            if($delay){
				$this->success("",['data'=>$delay]);
			}		
		}else{
			$this->errorMsg('ParamsCheck');
		}
	}

    /**
     * 延期申请确认
     * @param id
     * @param category
     * @return json
     */
    public function actionCoufirm(){
        $id = Yii::$app->request->post('id');
        if(is_numeric($id)){
                $delays=\common\models\DelayApply::updateAll(['is_agree'=>1],['is_agree'=>0,'id'=>$id]);
				if($delays){
					$this->success("确定成功");
				}else{
				    $this->errorMsg("PageTimeOut");	
				}
        }else{
            $this->errorMsg("ParamsCheck");
        }

    }

    /**
     * 延期申请取消
     * @param id
     * @param category
     * @return json
     */
    public function actionCancel(){
        $id = Yii::$app->request->post('id');
        if(is_numeric($id)){
			$delay=\common\models\DelayApply::updateAll(['is_agree'=>2],['is_agree'=>0,'id'=>$id]);
			if($delay){
				$this->success("取消成功");
			}else{
				$this->errorMsg("PageTimeOut");
			}
        }else{
            $this->errorMsg("ParamsCheck");
        }
    }

    /**
     * 融资、清收、诉讼 数据展示
     * @param id
     * @param category
     * @return json
     **/
    public function actionWxreleaseinformationwidget(){
        $id = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('category');
        if($token = Yii::$app->request->post('token')) {
            if (is_numeric($id) && is_numeric($category) && ArrayHelper::isIn($category, [1, 2, 3])) {
                $uid = Yii::$app->session->get('user_id');
                // $product = Func::getProduct($category, $id);
                // $product = $product->toArray();
                $creditor = Yii::$app->db->createCommand("select (count(uid)) from zcb_evaluate  where uid ={$uid} and product_id ={$id} and category={$category}")->queryScalar();
				$apply = \common\models\Apply::find()->joinWith(['certificationdata'])->asArray()->where(['zcb_apply.app_id'=>[0,1],'zcb_apply.product_id'=>$id,'zcb_apply.category'=>$category])->one();
				$product = \common\models\CreditorProduct::find()->joinWith(['certificationdata'])->asArray()->where(['zcb_creditor_product.progress_status'=>[0,1,2,3,4],'zcb_creditor_product.id'=>$id,'zcb_creditor_product.category'=>$category])->one();
				$name = isset($apply['certificationdata']['name'])?\frontend\services\Func::HideStrRepalceByChar($apply['certificationdata']['name'],'*',2,2):'';
				$names = isset($product['certificationdata']['name'])?\frontend\services\Func::HideStrRepalceByChar($product['certificationdata']['name'],'*',2,2):'';
				$a = $product['commissionperiod']?$product['commissionperiod']:1;
                $delay = date("Y-m-d",strtotime(date("Y-m-d",$apply['agree_time']).' + '.$a." months ")) ;
				$user = ['username'=>$names,'jusername'=>$name,'app_id'=>$apply['app_id'],'mobile'=>$product['certificationdata']['mobile'],'jmobile'=>$apply['certificationdata']['mobile'],'id'=>$apply['id'],'delay'=>$delay];              
			   
			   if (!$product) {
                    echo Json::encode(['code' => '4001', 'msg' => '没有数据，请检查参数是否正确']);
                    die;
                } else {
                    if($product['category']!=1) {
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
                        $car = isset($product['loan_type'])&&$product['loan_type'] == 3?\frontend\services\Func::getCarBrand($product['carbrand']) . \frontend\services\Func::getCarAudi($product['audi']) . \common\models\creditorProduct::$licenseplate[$product['licenseplate']]:'';
						$city_id = \frontend\services\Func::getCityNameById($product['city_id']);
						$district_id = \frontend\services\Func::getAreaNameById($product['district_id']);
						$province_id = \frontend\services\Func::getProvinceNameById($product['province_id']);
						$place_city_id = \frontend\services\Func::getCityNameById($product['place_city_id']);
						$place_province_id = \frontend\services\Func::getAreaNameById($product['place_province_id']);
						$place_district_id = \frontend\services\Func::getProvinceNameById($product['place_district_id']);
                        $product = [
						'product'=>$product,
						'guaranteemethod'=>is_numeric($product['guaranteemethod'])?$product['guaranteemethod']:unserialize($product['guaranteemethod']),
						'creditorfile'=>$creditorfile,
						'borrowinginfo'=>$borrowinginfo,
						'creditorinfo'=>$creditorinfo,
						'car'=>$car,
						'city_id'=>$city_id,
						'district_id'=>$district_id,
						'province_id'=>$province_id,
						'place_city_id'=>$place_city_id,
						'place_province_id'=>$place_province_id,
						'place_district_id'=>$place_district_id,
						];
                    }else{
                        $product = ['product'=>$product];
                    }
                    echo Json::encode(['code'=>'0000','result'=>$product,'uid'=>$uid,'creditor'=>$creditor,'pid'=>$apply['uid'],'username'=>$user]);
                    die;
                }
            } else {
                echo Json::encode(['code' => '1001', 'msg' => '参数错误']);
                die;
            }
        }else{
            echo Json::encode(['code'=>'3001','msg'=>'请先登录帐号']);die;
        }
    }

    /**
     * 终止、结案、追加评价等判断数据
     * @param id
     * @param category
     * @return json
     **/
    public function actionWxclosedswidget(){
        $id = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('category');
		$uid = Yii::$app->session->get('user_id');
        if($token = Yii::$app->request->post('token')) {
            if (is_numeric($id) && is_numeric($category) && ArrayHelper::isIn($category, [1, 2, 3])) {
                $product = Func::getProducts($category, $id);
                $uid = Yii::$app->session->get('user_id');
                $creditor = Yii::$app->db->createCommand("select (count(uid)) from zcb_evaluate  where uid ={$uid} and product_id ={$product['id']} and category={$product['category']}")->queryScalar();
				$apply = \common\models\Apply::findOne(['app_id'=>[0,1],'product_id'=>$product->id,'category'=>$product->category,'uid'=>$uid]);
				$data = ['app_id'=>$apply['app_id'],'id'=>$apply['id']];
				if (!$product) {
                    echo Json::encode(['code' => '4001', 'msg' => '没有数据，请检查参数是否正确']);
                    die;
                } else {
                    echo Json::encode(['code'=>'0000','result'=>['product'=>$product,'uid'=>$uid,'creditor'=>$creditor,'data'=>$data]]);
                    die;
                }
            } else {
                echo Json::encode(['code' => '1001', 'msg' => '参数错误']);
                die;
            }
        }else{
            echo Json::encode(['code'=>'3001','msg'=>'请先登录帐号']);die;
        }
    }
	
	//取消申请
	public function actionCancels(){
		$id = Yii::$app->request->post('id');
		if(is_numeric($id)){
			$applys = \common\models\Apply::findOne(['id'=>$id,'app_id'=>0]);
			if($applys){
				$apply = \common\models\Apply::deleteAll('id=:id and app_id =:app_id',[':id'=>$id,'app_id'=>0]);
			  if($apply){
				 $this->success("取消成功");
			  }else{
				$this->errorMsg("PageTimeOut");
			  }
			}else{
			   $this->errorMsg('ParamsCheck');	
			}		
		}else{
		   $this->errorMsg('ParamsCheck');
		}
	}

    /**
     * 填写进度数据查询
     * @param id
     * @param category
     * @return json
     **/
    public function actionWxspeedwidget(){
        $id = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('category');
        $page = Yii::$app->request->post('page')? Yii::$app->request->post('page') : 1;
        $limit = Yii::$app->request->post('limit')? Yii::$app->request->post('limit') : 10;
        $uid      = Yii::$app->session->get('user_id');
        if(is_numeric($id) && is_numeric($category) && ArrayHelper::isIn($category,[1,2,3])){
            $limitstr = "";
            if(is_numeric($page)&&is_numeric($limit)){
                $page = $page<=1?1:$page;
                $limit = $limit<=0?10:$limit;
                $limitstr = " limit ".($page-1)*$limit.",".$limit;
            }
            $sql = "select * from zcb_disposing_process where product_id = {$id} and category = {$category} order by create_time desc";
            $disposing = \Yii::$app->db->createCommand($sql.$limitstr)->query();
            if(!$disposing){
                echo Json::encode(['code'=>'4001','msg'=>'没有数据，请检查参数是否正确']);die;
            }else{
                echo Json::encode(['code'=>'0000','result'=>['disposing'=>$disposing,'uid'=>$uid]]);die;
            }
        }else{
            echo Json::encode(['code' => '1001', 'msg' => '参数错误']);
            die;
        }
    }

    /**
     * 终止、结案数据保存
     * @param id
     * @param status
     * @param category
     * @return json
     **/
    public function actionClosed(){
        $id       = Yii::$app->request->post('id');
        $uid      = Yii::$app->session->get('user_id');
        $status   = Yii::$app->request->post('status');
        $category = Yii::$app->request->post('category');
        if(is_numeric($id) && is_numeric($status) && is_numeric($category) && ArrayHelper::isIn($category,[1,2,3])){
            $product = Func::getProduct($category, $id);
            $apply = Func::getApply($id,$category,1);
            $user_apply = User::findOne(['id'=>$apply['uid']]);
            $user_product = User::findOne(['id'=>$product['uid']]);
            if(!$product){
                echo Json::encode(['code'=>'4001','msg'=>'没有数据，请检查参数是否正确']);die;
            }else{
                if($product['applyclose'] == 0 && $status == 3 || $product['applyclose'] == 3 && $status == 3){
                    $product->applyclosefrom  = $uid;
                    $product->applyclose       = $status;
                    $product->modify_time     = time();
                    $product->progress_status = 3;
                    $product->save();
                    if($product->uid == $uid){
                        Func::addMessagesPerType('发布方发起终止',($product['category'] == 1?'融资':($product['category'] == 2?'清收':'诉讼'))."编号:".$product['code'].",发布方已终止数据。详细信息请登录清道夫债管家账户系统查看。",11,serialize(['id' => $product['id'],'category' => $product['category']]),$apply['uid']);
                    }
                    echo Json::encode(['code'=>'0000','msg'=>'终止成功']);die;
                }else if($product['applyclose'] == 0 && $status == 4){
                    $product->applyclosefrom = $uid;
                    $product->applyclose      = $status;
                    $product->modify_time     = time();
                    if($product->save()){
                        if($product->uid == $uid){
                            Func::addMessagesPerType('发布方结案发起',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",发布方发起结案，请您尽快确认。详细信息请登录清道夫债管家账户系统查看。",11,serialize(['id' => $product->id,'category' => $product->category]),$apply->uid);
                            Yii::$app->smser->sendMsgByMobile($user_apply['mobile'],'发布方结案发起 【直向资产】尊敬的用户：订单号:'.$product['code'].'发布方发起结案，请您尽快确认。详细信息请登录清道夫债管家账户系统查看。');
                        }else{
                            Func::addMessagesPerType('接单方结案发起',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",接单方发起结案，请您尽快确认。详细信息请登录清道夫债管家账户系统查看。",12,serialize(['id' => $product->id,'category' => $product->category]),$product->uid);
                            Yii::$app->smser->sendMsgByMobile($user_product['mobile'],'接单方结案发起 【直向资产】尊敬的用户：订单号:'.$product['code'] . '接单方发起结案，请您尽快确认。详细信息请登录清道夫债管家账户系统查看。');
                        }
                        echo Json::encode(['code'=>'0000','msg'=>'请耐心等待对方确认结案']);die;
                    };
                }else if($product['applyclose'] == 4 && $status == 4){
                    $product->progress_status = 4;
                    $product->modify_time     = time();
                    if($product->save()){
                        if($product->uid == $uid){
                            Func::addMessagesPerType('发布方已同意结案',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",发布方已同意结案，请您尽快确认。详细信息请登录清道夫债管家账户系统查看。",12,serialize(['id' => $product->id,'category' => $product->category]),$apply->uid);
                            Yii::$app->smser->sendMsgByMobile($user_apply['mobile'],'发布方已同意结案 【直向资产】尊敬的用户：订单号:'.$product['code'].'发布方已同意结案，请您尽快确认。详细信息请登录清道夫债管家账户系统查看。');
                        }else{
                            Func::addMessagesPerType('接单方已同意结案',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",接单方已同意结案，请您尽快确认。详细信息请登录清道夫债管家账户系统查看。",11,serialize(['id' => $product->id,'category' => $product->category]),$product->uid);
                            Yii::$app->smser->sendMsgByMobile($user_product['mobile'],'接单方已同意结案 【直向资产】尊敬的用户：订单号:'.$product['code'] . '接单方已同意结案，请您尽快确认。详细信息请登录清道夫债管家账户系统查看。');
                        }
                        echo Json::encode(['code'=>'0000','msg'=>'结案成功']);die;
                    };

                }else if($product['applyclose'] == 4 && $status == 3 && $product['uid'] == $product['applyclosefrom']){
                    echo Json::encode(['code'=>'1001','msg'=>'您已申请结案']);die;
                }else{
                    echo Json::encode(['code'=>'1001','msg'=>'对方已申请结案，请尽快同意']);die;
                }
            }
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    /**
     * 填写进度数据提交
     * @param product_id
     * @param category
     * @param status
     * @param content
     * @param audit
     * @param case
     * @param user_id
     * @return json
     **/
    public function actionSpeedo(){
        $product_id  = Yii::$app->request->post('product_id');
        $category    = Yii::$app->request->post('category');
        $status      = Yii::$app->request->post('status');
        $content     = Yii::$app->request->post('content');
        $audit       = Yii::$app->request->post('audit');
        $case        = Yii::$app->request->post('case');
        $uid         = Yii::$app->session->get('user_id');
        if(is_numeric($product_id) && is_numeric($category) && ArrayHelper::isIn($category,[1,2,3])){
            $product = Func::getProduct($category, $product_id);
            if($status == '' || !$status){
                echo Json::encode(['code'=>'1010','msg'=>'请选择处置类型']);die;
            }
            $models = new \common\models\DisposingProcess();
			$models->setAttributes([
				$models->product_id = $product_id,
				$models->category   = $category,
				$models->status     = $status,
				$models->content    = $content,
				$models->create_time = time(),
			]);
            
            if($category == 3 && is_numeric($case)){
                if(!is_numeric($case)){
                    echo Json::encode(['code'=>'1001','msg'=>'案号请输入数字']);die;
                }
				$models->setAttributes([
					$models->audit = $audit,
					$models->case  = $case,
				]);
            }
			if($models->validate()){
			if($models->save()){
                Func::addMessagesPerType('有新进度',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",接单方已经更新了1条新的进度，赶快去瞅瞅吧。",14,serialize(['id' => $product->id,'category' => $product->category]),$product->uid);
                echo Json::encode(['code'=>'0000','msg'=>'提交成功']);die;
            }else{
                echo Json::encode(['code'=>'1014','msg'=>$models->errors]);die;
            }
			}
            
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }

    }

    /**
     * 待发布数据中的立即发布
     * @param id
     * @param category
     * @return json
     **/
    public function actionReleaselist(){
        $id = Yii::$app->request->post('id');
        $token = Yii::$app->request->post('token');
        $category = Yii::$app->request->post('category');
        if ($token) {
            if (is_numeric($id) && is_numeric($category) && ArrayHelper::isIn($category, [1, 2, 3])) {
                $product = Func::getProduct($category, $id);
                if (!$product) {
                    echo Json::encode(['code' => '4001', 'msg' => '没有数据，请检查参数是否正确']);
                    die;
                } else {
                    $product->progress_status = 1;
                    $product->modify_time = time();
                    if ($product->save()) {
                        Func::addMessagesPerType($product->category == 1?'融资发布':($product->category == 2?'清收发布':'诉讼发布'),"编号:".$product->code.",发布信息越详细，处置效率越高噢。",16,serialize(['id' => $product->id,'category' => $product->category]));
                        echo Json::encode(['code' => '0000', 'msg' => '提交成功']);
                        die;
                    } else {
                        echo Json::encode(['code' => '1014', 'msg' => $product->errors]);
                        die;
                    }
                }
            } else {
                echo Json::encode(['code' => '1001', 'msg' => '参数错误']);
                die;
            }
        }else{
            echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
        }
    }

    /**
     * 删除收藏
     * @param product_id
     * @param category
     * @return json
     **/
    public function actionDeletes(){
        $product_id = Yii::$app->request->post('product_id');
        $category   = Yii::$app->request->post('category');
        $uid        = Yii::$app->session->get('user_id');
        $token      = Yii::$app->request->post('token');
        if($token){
            if(is_numeric($product_id) && is_numeric($category) && ArrayHelper::isIn($category,[1,2,3])){
                $apply = \common\models\Apply::findOne(['uid'=>$uid,'product_id'=>$product_id,'category'=>$category]);
                $product = Func::getProduct($category, $product_id);
                if($apply){
                    if($apply->delete()){
                        Func::addMessagesPerType($product->category == 1?'融资收藏':($product->category == 2?'清收收藏':'诉讼收藏'),"编号:".$product->code.",取消成功",17,serialize(['id' => $product->id, 'category' => $product->category]));
                        echo Json::encode(['code'=>'0000','msg'=>'取消成功']);die;
                    }else{
                        echo Json::encode(['code'=>'1014','msg'=>$apply->errors]);die;
                    };
                }else{
                    echo Json::encode(['code' => '4001', 'msg' => '没有数据，请检查参数是否正确']);
                    die;
                }
            }else{
                echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
            }
        }else{
            echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
        }
    }

    /**
     * 意见反馈
     * @param phone
     * @param opinion
     * @return json
     **/
    public function actionOpinion(){
        $token   = Yii::$app->request->post('token');
        $phone   = Yii::$app->request->post('phone');
        $opinion = Yii::$app->request->post('opinion');
        $picture = Yii::$app->request->post('picture',"");
        $uid     = Yii::$app->session->get('user_id');
        if($token){
            if(!$phone || $phone == ''){
                echo Json::encode(['code'=>'1001','msg'=>'请输入手机号码']);die;
            }
            if(!$opinion || $opinion == ''){
                echo Json::encode(['code'=>'1002','msg'=>'请输入内容']);die;
            }
            $feedback = new \common\models\Feedback();
            $feedback->phone = $phone;
            $feedback->opinion = $opinion;
            $feedback->picture = $picture;
            $feedback->uid = $uid;
            if($feedback->save()){
                echo Json::encode(['code'=>'0000','msg'=>'提交成功']);die;
            }else{
                echo Json::encode(['code'=>'1014','msg'=>$feedback->errors]);die;
            }
        }else{
            echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
        }
    }

    /**
     * 案号查询
     * @param category
     * @param audit
     * @param id
     * @return json
     **/
    public function actionCode(){
        $category = Yii::$app->request->post('category');
        $audit    = Yii::$app->request->post('audit');
        $id       = Yii::$app->request->post('id');
        if(is_numeric($id) && is_numeric($category) && is_numeric($audit) && ArrayHelper::isIn($category,[1,2,3])){
            $disposing = \common\models\DisposingProcess::findOne(['product_id'=>$id,'category'=>$category,'audit'=>$audit]);
            if($disposing){
                echo Json::encode(['code'=>'0000','result'=>$disposing['case']]);die;
            }
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    /**
     * 删除保存数据
     * @param category
     * @param id
     * @return json
     **/
    public function actionDeleteproduct(){

		$type = Yii::$app->request->post('type');
		if(is_numeric($type)){
		if($type == 1){
			$id = Yii::$app->request->post('id');
			$apply = \common\models\Apply::updateAll(['is_del'=>1],['app_id'=>[0,1],'id'=>$id]);
			if($apply){
				$this->success("删除成功");
			}else{
				$this->errorMsg("PageTimeOut");
			}
		}else if($type == 2){
			$category = Yii::$app->request->post('category');
            $id = Yii::$app->request->post('id');
            $uid = $this->user->id;
			$product = \common\models\CreditorProduct::updateAll(['is_del'=>1],['id'=>$id,'category'=>$category,'uid'=>$uid]);
		if($product){
            $this->success("删除成功");
            //Func::addMessagesPerType($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'),"编号:".$product->code.",删除成功",18,serialize([]));
            echo Json::encode(['code'=>'0000','msg'=>'删除成功']);die;
        }else{
            $this->errorMsg("PageTimeOut");
        }
		}
		}else{
			$this->errorMsg("参数错误");
		}
        
    }


    public function actionEvaluate(){
        $serviceattitude = Yii::$app->request->post('serviceattitude');
        $professionalknowledge = Yii::$app->request->post('professionalknowledge');
        $workefficiency = Yii::$app->request->post('workefficiency');
        $content = Yii::$app->request->post('content')?Yii::$app->request->post('content'):'优质、专业、高效、快捷';
        $category = Yii::$app->request->post('category');
        $product_id = Yii::$app->request->post('product_id');
        $isHide = Yii::$app->request->post('isHide');
        if(Yii::$app->request->post('pictures')){
            $picture =Yii::$app->request->post('pictures')?Func::getCreditor(Yii::$app->request->post('pictures')):'';
        }else if(Yii::$app->request->post('picturess')){
            $picture =Yii::$app->request->post('picturess')?Func::getCreditors(Yii::$app->request->post('picturess')):'';
        }else{
            $picture = Yii::$app->request->post('picture');
        }

        $type = Yii::$app->request->post('type',0);

        if(trim($serviceattitude) == '' && !Func::isInt($serviceattitude) || trim($serviceattitude) == 0){
            echo Json::encode(['code'=>'1001','msg'=>'服务态度或者真实性不能为空']);die;
        }
        if(trim($professionalknowledge) == '' &&!Func::isInt($professionalknowledge) || trim($professionalknowledge) == 0){
            echo Json::encode(['code'=>'1002','msg'=>'专业知识或者配合度不能为空']);die;
        }
        if(trim($workefficiency) == '' && !Func::isInt($workefficiency) || trim($workefficiency) == 0){
            echo Json::encode(['code'=>'1003','msg'=>'办事效率或者响应度不能为空']);die;
        }

        if(!ArrayHelper::isIn($category,[1,2,3])){
            echo Json::encode(['code'=>'1004','msg'=>'产品类型不正确']);die;
        }

        if(!Func::isInt($product_id)){
            echo Json::encode(['code'=>'1005','msg'=>'产品ID不正确']);die;
        }

        $product = Func::getProduct($category,$product_id);

        if(!$product){
            echo Json::encode(['code'=>'4001','msg'=>'产品不存在或者已删除']);die;
        }
        $apply = \common\models\Apply::findOne(['category'=>$category,'product_id'=>$product_id,'app_id'=>1]);
        if(!$apply){
            echo Json::encode(['code'=>'4002','msg'=>'您尚未被接单，或者数据已删除，无法评价']);die;
        }

        if($product->uid == $this->user->id){
            $uid = $this->user->id;
            $bid = $apply->uid;
        }else{
            $uid = $this->user->id;
            $bid =  $product->uid;
        }

        $evaluate = new \common\models\Evaluate();
		$evaluate->setAttributes([
			$evaluate->serviceattitude = $serviceattitude,
			$evaluate->professionalknowledge = $professionalknowledge,
			$evaluate->workefficiency = $workefficiency,
			$evaluate->content = $content,
			$evaluate->isHide = $isHide,
			$evaluate->picture = $picture,
			$evaluate->category = $product->category,
			$evaluate->product_id = $product->id,
			$evaluate->create_time = time(),
			$evaluate->uid = $uid,
			$evaluate->buid = $bid,
			$evaluate->superaddition = $type,
		]);
         if($evaluate->validate()){
			 $s = $evaluate->save();
			if($s){
            if($product->uid == $this->user->id) {
                Func::addMessagesPerType('收到评价', ($product->category == 1 ? '融资' : ($product->category == 2 ? '清收' : '诉讼')) . "编号:" . $product->code . ",收到1条评价，看看他怎么说？", $product->uid == $this->user->id ? 13 : 3, serialize(['id' => $product->id, 'category' => $product->category]),$apply->uid);
            }else{
                Func::addMessagesPerType('收到评价', ($product->category == 1 ? '融资' : ($product->category == 2 ? '清收' : '诉讼')) . "编号:" . $product->code . ",收到1条评价，看看他怎么说？", $product->uid == $this->user->id ? 13 : 3, serialize(['id' => $product->id, 'category' => $product->category]),$product->uid);
            }
				echo Json::encode(['code'=>'0000','msg'=>'提交成功']);die;
			}else{
				echo Json::encode(['code'=>'1014','msg'=>$s->errors]);die;
			}
		 }else{
			 echo Json::encode(['code'=>'1014','msg'=>$evaluate->errors]);die;
		 }
			
    }

    //提醒消息
    public function actionRemind()
    {
        $uid = Yii::$app->session->get('user_id');
        $id = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('category');
        if (is_numeric($id) && is_numeric($category) && ArrayHelper::isIn($category, [1, 2, 3])) {
            $product = Func::getProduct($category,$id);
            $sql = "select create_time,params,type from zcb_message where type in(20,21,22) and uid = {$uid} and DATE_FORMAT(FROM_UNIXTIME(create_time),'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')";
            $reminds = \Yii::$app->db->createCommand($sql)->queryAll();
            if(!$reminds){
                if ($uid != $product['uid']) {
                    echo Json::encode(['code'=>'0000','msg'=>'提醒成功']);
                    Func::addMessagesPerType('提醒消息',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",接单方提醒您去完成补填信息。",($product->category == 1?20:($product->category == 2 ? 21:22)),serialize(['id' => $product->id,'category' => $product->category]),$product->uid);die;
                }else{
                    echo Json::encode(['code'=>'4003','msg'=>'自己不能提醒自己']);die;
                }
            }else {
                $flag  = false;
                $compair = ['id'=>$id,'category'=>$category];
                foreach($reminds as $key=>$value){
                   $params = array_diff(unserialize($value['params']),$compair);
                    if(empty($params['id']) && empty($params['category'])){
                        $flag = true;
                        break;
                    }else{
                        $flag  = false;
                    }

                }
                if($flag){
                    echo Json::encode(['code' => '4001', 'msg' => '一天只能提醒一次']);die;
                }else {
                    if ($uid != $product['uid']) {
                        echo Json::encode(['code' => '0000', 'msg' => '提醒成功']);
                        Func::addMessagesPerType('提醒消息', ($product->category == 1 ? '融资' : ($product->category == 2 ? '清收' : '诉讼')) . "编号:" . $product->code . ",接单方提醒您去完成补填信息。", ($product->category == 1 ? 20 : ($product->category == 2 ? 21 : 22)), serialize(['id' => $product->id, 'category' => $product->category]), $product->uid);
                        die;
                    } else {
                        echo Json::encode(['code' => '4003', 'msg' => '自己不能提醒自己']);
                        die;
                    }
                }
            }
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }
}