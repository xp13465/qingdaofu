<?php

namespace frontend\modules\wap\controllers;

use common\models\Area;
use common\models\Audi;
use common\models\Brand;
use common\models\City;
use common\models\Evaluate;
use common\models\Province;
use common\models\User;
use frontend\modules\wap\components\WapController;
use frontend\modules\wap\components\WapNoLoginController;
use yii;
use frontend\modules\wap\services\Func;
use yii\web\Controller;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

/**
 * 用户操作控制器
 */
class CommonController extends WapNoLoginController
{
    /**
     * 获取所有省份
     * @return json
     * **/
    public function actionProvince($type="web"){
		if($type=="app"){
			$province = Province::find()->select("provinceID,province")->asArray()->where("1")->all();
			
			echo Json::encode($province);die;
		}else{
			$province = Province::find()->select("provinceID,province")->where("1")->all();
			$desc = [];
			foreach($province as $p){
				$desc[$p->provinceID] = $p->province;
			}
			echo Json::encode($desc);die;
		}
    }



    /**
     * 获取所需城市
     * @param fatherID为空时获取全部
     * @return json
     * **/
    public function actionCity($type="web"){

        $fatherID = Yii::$app->request->post('fatherID');
        $where  = ' 1 ';
        if($fatherID)$where = "fatherID = '{$fatherID}'";
		
		if($type=="app"){
			$city = City::find()->select("cityID,city,fatherID")->asArray()->where($where)->all();
			
			echo Json::encode($city);die;
		}else{
			$city = City::find()->select("cityID,city,fatherID")->where($where)->all();
			$desc = [];
			$desc[$fatherID]['0'] = '全部';
			foreach($city as $c){
				$desc[$c->fatherID][$c->cityID] = $c->city;
			}
			echo Json::encode($desc);die;
		}
		
    }




    /**
     * 获取所需区域
     * @return json
     * **/
    public function actionArea($type="web"){

        $fatherID = Yii::$app->request->post('fatherID');
		$where  = ' 1 ';
		if($fatherID)$where = "fatherID = '{$fatherID}'";
		
		if($type=="app"){
			$area = Area::find()->select("areaID,area,fatherID")->asArray()->where($where)->all();
			
			echo Json::encode($area);die;
		}else{
			

			$area = Area::find()->select("areaID,area,fatherID")->where($where)->all();
			$desc = [];
			$desc[$fatherID]['0'] = '全部';
			foreach($area as $a){
				$desc[$a->fatherID][$a->areaID] = $a->area;
			}

			echo Json::encode($desc);die;
		}
       
        
    }

    //小区
    public function actionGetsearch(){
        if(Yii::$app->request->isPost){
            $community =  '';
            if(Yii::$app->request->post('name')) {
                $community = Yii::$app->db->createCommand("select * from zcb_community where name like '%" . Yii::$app->request->post('name') . "%'")->queryAll();
            }
            if(empty($community)){
                echo yii\helpers\Json::encode(['code'=>'1001','result'=>Yii::$app->request->post('name')]);die;
            }else{
                echo yii\helpers\Json::encode(['code'=>'0000','result'=>$community]);die;
            }
        }
    }

    public function actionBrand(){
        $bmodel = Brand::find()->select("id,name")->where("1")->all();
        $desc = [];

        foreach($bmodel as $p){
            $desc[$p->id] = $p->name;
        }

        echo Json::encode($desc);die;
    }


    public function actionBrandchild(){
        $pid = Yii::$app->request->post('pid');
        $bmodel = Audi::find()->select("id,name")->where("pid=$pid")->all();
        $desc = [];

        foreach($bmodel as $p){
            $desc[$p->id] = $p->name;
        }

        echo Json::encode($desc);die;
    }


    public function actionTerminate(){
        $status = Yii::$app->request->post('status');
        $id = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('category');
        $uidfrom = Yii::$app->request->post('uid');
        $uid = Yii::$app->session->get('user_id');
        if(is_numeric($id)&&is_numeric($uidfrom)&&is_numeric($category)&&ArrayHelper::isIn($category,[1,2,3])&&ArrayHelper::isIn($status,[3,4])){
            $product = Func::getProduct($category,$id);
            if(!$product){
                echo Json::encode(['code'=>'4001','msg'=>'没有数据，请检查参数是否正确']);die;
            }

            $apply = Func::getApply($id,$category,1);
            if(!$apply){
                echo Json::encode(['code'=>'4003','msg'=>'数据不合法，没有申请人的产品不能终止或者结案']);die;
            }

            if($product->applyclose&&ArrayHelper::isIn($product->applyclose,[3,4])){
                echo Json::encode(['code'=>'4002','msg'=>'您已经终止或者结案无法继续操作']);die;
            }


            if(!ArrayHelper::isIn($uidfrom,[$apply->uid,$product->uid])){
                echo Json::encode(['code'=>'3001','msg'=>'您不是申请人也不是发布人无法操作该产品']);die;
            }

            $user_apply = User::findOne(['id'=>$apply->uid]);
            $user_product = User::findOne(['id'=>$product->uid]);

            if(!isset($user_apply->id)||!isset($user_product->id)){
                echo Json::encode(['code'=>'3002','msg'=>'数据不合法，发布人或者接收人不存在']);die;
            }

            $product->applyclose = $status;
            $product->applyclosefrom = $uidfrom;

            if($product->save()){
                if($uidfrom == $apply->uid){
                    Func::addMessagesPerType('接单方结案发起',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",接单方发起结案，请您尽快确认。详细信息请登录清道夫债管家账户系统查看。",12,serialize(['id' => $product->id,'category' => $product->category]),$product->uid);
                    Yii::$app->smser->sendMsgByMobile($user_product['mobile'],'接单方结案发起 【直向资产】尊敬的用户：订单号:'.$product['code'] . '接单方发起结案，请您尽快确认。详细信息请登录清道夫债管家账户系统查看。');
                }elseif($uidfrom == $product->uid){
                    Func::addMessagesPerType('发布方结案发起',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",发布方发起结案，请您尽快确认。详细信息请登录清道夫债管家账户系统查看。",11,serialize(['id' => $product->id,'category' => $product->category]),$apply['uid']);
                    Yii::$app->smser->sendMsgByMobile($user_apply['mobile'],'发布方结案发起 【直向资产】尊敬的用户：订单号:'.$product['code'].'发布方发起结案，请您尽快确认。详细信息请登录清道夫债管家账户系统查看。');
                }
                echo Json::encode(['code'=>'0000','msg'=>'申请成功，等待对方处理']);die;
            }else{
                echo Json::encode(['code'=>'1014','msg'=>$product->errors]);die;
            }
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    public function actionTerminateAgree(){
        $id = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('category');
        $uidto = Yii::$app->request->post('uid');
        $uid = Yii::$app->session->get('user_id');

        if(!is_numeric($id)||!is_numeric($category)||!is_numeric($uidto)||!ArrayHelper::isIn($category,[1,2,3])){
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }

        $product = Func::getProduct($category,$id);
        if(!$product){
            echo Json::encode(['code'=>'4001','msg'=>'没有数据，请检查参数是否正确']);die;
        }


        $apply = Func::getApply($id,$category,1);
        if(!$apply){
            echo Json::encode(['code'=>'4003','msg'=>'数据不合法，没有申请人的产品不能终止或者结案']);die;
        }

        $user_apply = User::findOne(['id'=>$apply->uid]);
        $user_product = User::findOne(['id'=>$product->uid]);

        if(!isset($user_apply->id)||!isset($user_product->id)){
            echo Json::encode(['code'=>'3002','msg'=>'数据不合法，发布人或者接收人不存在']);die;
        }

        if(!$product->applyclose||!ArrayHelper::isIn($product->applyclose,[3,4])){
            echo Json::encode(['code'=>'4002','msg'=>'对方申请状态有误，无法继续操作']);die;
        }


        if($product->applyclosefrom == $uid){
            echo Json::encode(['code'=>'4004','msg'=>'自己不能同意自己的申请']);die;
        }

        if(!ArrayHelper::isIn($uidto,[$apply->uid,$product->uid])){
            echo Json::encode(['code'=>'3001','msg'=>'您不是申请人也不是发布人无法操作该产品']);die;
        }

        $product->progress_status = $product->applyclose;

        if($product->save()){
            /*if($uidfrom == $apply->uid){
                Yii::$app->smser->sendMsgByMobile($user_product['mobile'],'接单方结案发起 【直向资产】尊敬的用户：订单号:'.$product['code'] . '接单方发起结案，请您尽快确认。详细信息请登录清道夫债管家账户系统查看。');
            }elseif($uidfrom == $product->uid){
                Yii::$app->smser->sendMsgByMobile($user_apply['mobile'],'发布方结案发起 【直向资产】尊敬的用户：订单号:'.$product['code'].'发布方发起结案，请您尽快确认。详细信息请登录清道夫债管家账户系统查看。');
            }*/
            Func::addMessagesPerType('申请成功',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",申请成功，赶紧去处理吧。",7,serialize(['id' => $product->id,'category' => $product->category]));
            echo Json::encode(['code'=>'0000','msg'=>'已同意对方申请']);die;
        }else{
            echo Json::encode(['code'=>'1014','msg'=>$product->errors]);die;
        }
    }

    public function actionIslogin(){
        if($token = Yii::$app->request->post('token')){
            $user = User::findOne(['token'=>$token]);
            if($user){
                $certification = Func::getCertifications($user['id']);
            }
            if(!isset($user->id)||!$user->id){
                echo Json::encode(['code'=>'3001','msg'=>'请先登录账户']);die;
            }else{
                if($certification) {
                    echo Json::encode(['code' => '0000', 'msg' => '']);
                    die;
                }else{
                    echo Json::encode(['code' => '3006', 'msg' => '请先认证用户']);
                    die;
                }
            }
        }else{
            echo Json::encode(['code'=>'3001','msg'=>'请先登录账户']);die;
        }
    }

    public function actionApplication(){
        if($token = Yii::$app->request->post('token')){
            $user = User::findOne(['token'=>$token]);
            if($user){
                $certification = Func::getCertifications($user['id']);
            }
            if(!isset($user->id)||!$user->id){
                echo Json::encode(['code'=>'3001','msg'=>'请先登录账户']);die;
            }else{
                if($certification) {
                    $certifications = ['uid'=>$certification['uid'],'id'=>$user['id'],'mobile'=>$user['mobile'],'category'=>$certification['category'],'state'=>$certification['state']];
                    echo Json::encode(['code' => '0000', 'result' => $certifications]);
                    die;
                }else{
                    $usermobile = ['mobile'=>$user['mobile'],'pid'=>$user['pid']];
                    echo Json::encode(['code' => '3006', 'msg' => '请先认证用户','result'=>$usermobile]);
                    die;
                }
            }
        }else{
            echo Json::encode(['code'=>'3001','msg'=>'请先登录账户']);die;
        }
    }
    
    public function actionUploads(){
        $model = new \common\models\UploadForm();
        if (Yii::$app->request->isPost) { 
            $filetype = Yii::$app->request->post('filetype');
            $extension = Yii::$app->request->post('extension');

			$fileADDR="./uploads/".time().rand("1000","9999").".".$extension;
			if(Yii::$app->request->post('data')){
				$data = Yii::$app->request->post('data');
				$img_file = base64_decode($data);
			}else if(Yii::$app->request->post('picture')){
				$data = Yii::$app->request->post('picture');
				$img_file = Func::hex2bin($data);
			}

            if (empty($img_file)) $xmlstr = file_get_contents('php://input');
            $jpg = $img_file;//得到post过来的二进制原始数据
          //  echo $jpg;exit;
            $status = file_put_contents($fileADDR,$img_file);
            ($pathinfo = pathinfo($fileADDR));
            $_FILES=[
              "Filedata"=>[
                "name"=>$pathinfo['basename'],
                "type"=>mime_content_type($fileADDR),
                "tmp_name"=>$fileADDR,
                "error"=>"0",
                "size"=>filesize($fileADDR),
              ] 
            ];
            $model->imageFile = \frontend\components\UploadedFile::getInstance($model, 'Filedata');
            if($data){
                $return = $model->upload($filetype,true,false);
    			unset($return['tempName']);
				if($return['error']==0){
					echo Json::encode(['code'=>'0000','result'=>$return]);die;
				}else{
					echo Json::encode(['code'=>$return['error'],'msg'=>isset($return['msg'])?$return['msg']:'']);die;
				}
    			
            }
			
        }
    }

}
