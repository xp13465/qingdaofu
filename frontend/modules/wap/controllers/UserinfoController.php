<?php

namespace frontend\modules\wap\controllers;
use frontend\modules\wap\components\WapController;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use app\models\ProductOrders;
use frontend\modules\wap\services\Func;

/**
 * 用户操作控制器
 */
class UserinfoController extends WapController
{
	/**
	 * 用户信息
	 */
	public function actionDetail(){
        $userid  = Yii::$app->request->post('userid');
        $productid  = Yii::$app->request->post('productid');
        $status = Yii::$app->request->post('status');
        $users = new \app\models\User;
		$User = $users->getCertifications($userid);
		if(!is_array($User)){
			$this->errorMsg($User);
		}
		$User['titleLabel']="用户详情";
		if($productid){
			$Product=\app\models\Product::findOne($productid);
			if($Product){
				$User['canContacts'] = $Product->accessContacts("APPLY",$userid);
				
				if($Product->create_by==$userid){
					$User['titleLabel']="发布方详情";
				}else if($Product->orders&&$Product->orders->create_by ==$userid){
					$User['titleLabel']="接单方详情";
				}
				
			}else{
				$User['canContacts'] = false;
			}
		}else{
			$User['canContacts'] = false;
		}
		if($User['canContacts']==false){
			$User['mobile']=\frontend\services\Func::HideStrRepalceByChar($User['mobile'],'*',3,4);
		}
        if($status){
			 $limit = Yii::$app->request->post('limit',10);
			 $page = Yii::$app->request->post('page',1);
             $User['commentdata'] = $users->commentList($userid,false,$limit,$page);
              foreach($User['commentdata']['Comments1'] as $key=>$value){
              $User['commentdata']['Comments1'][$key]['filesImg'] = \app\models\Files::getFiles(explode(',',$value['files']));  
              } 
          }
		$this->success("",['data'=>$User]);
	}
    
	/**
	 * 用户信息
	 */
	public function actionInfo(){
        $userid  = Yii::$app->user->getId();
		$User = \app\models\User::getCertifications($userid,true,['id','username','realname','mobile','picture','isSetPassword']);
		if(!is_array($User)){
			$this->errorMsg($User);
		}
		// $initialpassword = '998997996995';
		// $User['isSetPassword']=$this->user->validatePassword($initialpassword)?false:true;
		$productOrdersQuery = ProductOrders::find();
		$params = [];
		$params['listtype'] = "processing";
		$User['operatorDo'] = $productOrdersQuery->searchOrder($params,$userid,true,false,1)->count();
		$this->success("",['data'=>$User]);
	}
	
	/**
	*  用户评价列表
	*
	*/
	public function actionCommentList(){
		$ProductOrdersQuery = ProductOrders::find();
		$userid = Yii::$app->request->post('userid');
		$limit = Yii::$app->request->post('limit',10);
		$page = Yii::$app->request->post('page',1);
		$User = new \app\models\User;
		$data = $User->commentList($userid,false,$limit,$page);
		foreach($data['Comments1'] as $key=>$value){
              $data['Comments1'][$key]['filesImg'] = \app\models\Files::getFiles(explode(',',$value['files']));  
              } 
		if($data||$data==[]){
			$this->success("",['data'=>$data]);
		}elseif($data===false){
			$this->errorMsg('UserAuth');
		}else{
			$this->errorMsg('NotFound');
		}
	}
	
	/**
	*  用户评价详情
	*
	*/
	public function actionCommentDetail(){
		$ProductOrdersQuery = ProductOrders::find();
		$commentid = Yii::$app->request->post('commentid');
		$limit = Yii::$app->request->post('limit',10);
		$page = Yii::$app->request->post('page',1);
		$User = new \app\models\User;
		$data = $User->commentDetail($commentid);
		if($data||$data==[]){
			$this->success("",['data'=>$data]);
		}elseif($data===false){
			$this->errorMsg('UserAuth');
		}else{
			$this->errorMsg('NotFound');
		}
	}
	
	/**
	*  设置密码
	*
	*/
	public function actionSetpassword(){
		$userid = Yii::$app->user->getId();
		$password = Yii::$app->request->post("password");
		if(!$password)$this->errorMsg(PARAMSCHECK,"请输入密码");
		$initialpassword = '998997996995';
		$User= \common\models\User::findOne($userid);
		if(!$User)$this->errorMsg(PARAMSCHECK);
		$status = $User->validatePassword($initialpassword);
		if($status){
			$User->setPassword($password);
			$User->isSetPassword = 1 ;
            $User->generateAuthKey();
            if ($User->save(false)){
				$this->success("设置密码成功");
            }
		}else{
			$this->errorMsg(MODELDATACHECK,"已设置密码");
		}
	}
    
	/**
	*  更改绑定手机
	*
	*/
	public function actionChangemobile(){
		$userid = Yii::$app->user->getId();
		$oldmobile = Yii::$app->request->post("oldmobile");
		$oldcode = Yii::$app->request->post("oldcode");
		$newmobile = Yii::$app->request->post("newmobile");
		$newcode = Yii::$app->request->post("newcode");
		
		$count = \common\models\User::find()->where(['mobile'=>$newmobile])->count();
		if($newmobile==$oldmobile)$this->errorMsg(PARAMSCHECK,"新手机不可与原手机相同！");
		if($count)$this->errorMsg(PARAMSCHECK,"新手机已被使用！");
		if(!$oldmobile||!$newmobile||!$oldcode||!$newcode)$this->errorMsg(PARAMSCHECK);
		
		$User= \common\models\User::findOne($userid);
		if(!$User)$this->errorMsg(PARAMSCHECK);
		if($User->mobile!=$oldmobile)$this->errorMsg(MODELDATACHECK,"原手机号码错误");
		$sms = new \common\models\Sms();
		if(!$sms->isVildateCode($oldcode,$oldmobile,4,false)){
			$this->errorMsg(MODELDATACHECK,"原手机验证码错误！");
		}
		if(!$sms->isVildateCode($newcode,$newmobile,4,false)){
			$this->errorMsg(MODELDATACHECK,"新手机验证码错误！");
		}
		
		
		$User->mobile = $newmobile;
        if ($User->save()){
			$sms->isVildateCode($oldcode,$oldmobile,4,true);
			$sms->isVildateCode($newcode,$newmobile,4,true);
			$this->success("新手机绑定成功");
        }else{
			$this->success("新手机已被使用");
		}
		
	}
	
	/**
	 * 头像修改
	 *
	 */
    public function actionUploadsimg(){
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
					$return['url']=\yii\helpers\Url::toRoute("/",true).$return['url'];
					
					$userid = Yii::$app->user->getId();
					$status = \app\models\User::updateAll(['picture' => $return['fileid']],['id'=>$userid]);
					echo Json::encode(['code'=>'0000','msg'=>'头像已更换','result'=>$return]);die;
				}else{
					echo Json::encode(['code'=>$return['error'],'msg'=>isset($return['msg'])&&$return['msg']?$return['msg'][0]:'']);die;
				}
    			
            }
			
        }
    }
	
	/**
	 * 昵称修改
	 *
	 */
    public function actionNkname(){
        if (Yii::$app->request->isPost) { 
            $nickname = Yii::$app->request->post("nickname");
            $userid = Yii::$app->user->getId();
			$status = \app\models\User::updateAll(['realname' => $nickname],['id'=>$userid]);
			if($status>=0){
				$this->success("修改成功");
			}else{
				$this->success("修改失败");
			}
        }
    }
	
	
 
}
