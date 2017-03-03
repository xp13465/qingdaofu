<?php
namespace frontend\controllers;

use common\models\FinanceProduct;
use Yii;
use common\models\LoginForm;
use common\models\CreditorProduct;
use common\models\Certification;
use common\models\Apply;
use frontend\services\Func;
use app\models\User;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\ErrorException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use frontend\components\FrontController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers;
use yii\helpers\BaseJson;
use yii\db\ActiveRecord;
use yii\data\Pagination;
/**
 * Site controller
 */
class CertificationController extends FrontController
{
	public function init(){
		 
		if(Yii::$app->user->isGuest){
			$this->redirect('/site/login/')->send();
			exit;
		}
	}
    public $layout = 'user';

    public $enableCsrfValidation = false;
    public function actionIndex(){
        $certification = $this->getCertification();
        if(isset($certification->id)){
            switch($certification->category){
                case 1:
                    $this->redirect(helpers\Url::to('/certification/personal'));
                    break;
                case 2:
                    $this->redirect(helpers\Url::to('/certification/lawyer'));
                    break;
                case 3:
                    $this->redirect(helpers\Url::to('/certification/orgnization'));
                    break;
            }
        }
        return $this->render('index');
    }

    //认证信息完善和重新认证
    public function actionPerfect(){
        $type = Yii::$app->request->get('type')?Yii::$app->request->get('type'):1;
        $uid = Yii::$app->user->getId();
        $model = Certification::findOne(['uid'=>$uid]);
            if (Yii::$app->request->isPost) {
                $model->uid = $uid;
                $model->state = 0;
                $model->name = Yii::$app->request->post('name');
                $model->cardno = Yii::$app->request->post('cardno');
                $model->email = Yii::$app->request->post('email');
                $model->contact = Yii::$app->request->post('contact');
                $model->mobile = Yii::$app->request->post('mobile');
                $model->casedesc = Yii::$app->request->post('casedesc');
                $model->enterprisewebsite = Yii::$app->request->post('enterprisewebsite');
                $model->address = Yii::$app->request->post('address');
                $number = Yii::$app->request->post('types');
				if($number == 1){
					$cardimgs = Yii::$app->request->post('cardimg1');
				    $cardimgimg = Yii::$app->request->post('cardimgId1');
				}else if($number == 2){
					$cardimgs = Yii::$app->request->post('cardimg2');
				    $cardimgimg = Yii::$app->request->post('cardimgId2');
					if(trim($cardimgs) == ''){
                        echo json_encode(['code'=>1006,'msg'=>'执业证照片未上传']);die;
                    }
				}else if($number == 3){
					$cardimgs = Yii::$app->request->post('cardimg3');
				    $cardimgimg = Yii::$app->request->post('cardimgId3');
					if(trim($cardimgs) == ''){
                        echo json_encode(['code'=>1006,'msg'=>'营业执照照片未上传']);die;
                    }
				}
				$model->cardimgimg = $cardimgimg;
                $model->cardimg = $cardimgs;
                if($model->category == $number ) {
                    if ($model->save()) {
                        exit(json_encode(['code' => 0000, 'msg' => '修改成功']));
                    } else {
                        exit(json_encode(['code' => 1001, 'msg' => '修改失败']));
                    }
                }else if(isset($model)&&!Apply::findOne(['uid'=>$uid])&&!Func::hasProduct() && $model->category == $number){
                    if ($model->save()) {
                        exit(json_encode(['code' => 0000, 'msg' => '修改成功']));
                    } else {
                        exit(json_encode(['code' => 1001, 'msg' => '修改失败']));
                    }
                }else{
                    exit(json_encode(['code' => 1002, 'msg' => '请去完善认证信息']));
                }
            }
        return $this->render('add',['type'=>$type,'model'=>$model]);
    }

    private function getCertification(){
        $uid = Yii::$app->user->getId();
        $user = \common\models\User::findOne(['id'=>$uid]);
        $certificationFromSelf = Certification::findOne(['uid'=>$uid]);
        if(!isset($certificationFromSelf->id)&&!$user->pid){
            return null;
        }else if(isset($certificationFromSelf->id)){
            return $certificationFromSelf;
        }else{
            $certificationFromParent = Certification::findOne(['uid'=>$user->pid]);
            if(isset($certificationFromParent->id))return $certificationFromParent;
            else return null;
        }
    }

    public function actionPersonal(){
        $certification = $this->getCertification();
        if(isset($certification->state)&&$certification->category == 1)return $this->render('personal',['certi'=>$certification]);else return $this->redirect(helpers\Url::to('/certification/index'));
    }
    public function actionLawyer(){
        $certification = $this->getCertification();
        $cookies = Yii::$app->response->cookies;
        if(isset($certification->state)&&$certification->category == 2)return $this->render('lawyer',['certi'=>$certification]);else return $this->redirect(helpers\Url::to('/certification/index'));
    }
    public function actionOrgnization(){
        $certification = $this->getCertification();
        $cookies = Yii::$app->response->cookies;
        if(isset($certification->state)&&$certification->category == 3)return $this->render('orgnization',['certi'=>$certification]);else return $this->redirect(helpers\Url::to('/certification/index'));
    }

    public function actionAdd(){
        $type = Yii::$app->request->get('type')?Yii::$app->request->get('type'):1;
        $uid = Yii::$app->user->getId();
        $certification = Func::getCertification();
        if(isset($certification)/*|| Apply::findOne(['uid'=>$uid])&&Func::hasProduct()*/){
            $this->redirect(helpers\Url::to('/certification/index'));
        }
        if(isset($certification)&&!Apply::findOne(['uid'=>$uid])&&!Func::hasProduct()){
            $this->redirect(helpers\Url::toRoute(['/certification/perfect','type'=>$certification->category]));
        }
        $model = new Certification();
        if(Yii::$app->request->isPost){
            $type = Yii::$app->request->post('types');
            $name = Yii::$app->request->post('name');
            $cardno = Yii::$app->request->post('cardno');
            $email = Yii::$app->request->post('email');
            $casedesc = Yii::$app->request->post('casedesc');
            $mobile = Yii::$app->request->post('mobile');
            $contact = Yii::$app->request->post('contact');
            $address = Yii::$app->request->post('address');
            $enterprisewebsite = Yii::$app->request->post('enterprisewebsite');
            $cookies = Yii::$app->response->cookies;

            if(isset(Certification::findOne(['uid'=>$uid])->id)){
                echo json_encode(['code'=>1002,'msg'=>'您已经认证不可重复认证']);die;
            }
            if(!in_array($type,[1,2,3])){
                echo json_encode(['code'=>1003,'msg'=>'你选择的身份有误']);die;
            }

           /* if(trim($casedesc) == ''){
                echo json_encode(['code'=>1005,'msg'=>'您的经典案例不能为空']);die;
            }*/

            switch($type){
                case 1:
				    $cardimgs = Yii::$app->request->post('cardimg1');
                    $cardimgimg = Yii::$app->request->post('cardimgId1');
                    if(trim($name) == ''){
                        echo json_encode(['code'=>1004,'msg'=>'姓名不可为空']);die;
                    }
                    if(!$cardno&&Func::isCardNo($cardno)){
                        echo json_encode(['code'=>1010,'msg'=>'身份证号码不正确']);die;
                    }
                    $model->uid = $uid;
                    $model->category = 1;
                    $model->state = 0;
                    $model->name = $name;
                    $model->cardno = $cardno;
                    $model->email = $email;
                    $model->casedesc = $casedesc;
                    $model->cardimg = $cardimgs;
					$model->cardimgimg = $cardimgimg;
                    Yii::$app->session->set("fromWhat","personal");
                    if($model->save()){
						//Yii::$app->smser->sendMsgByMobile(13918500509,'有用户申请个人认证，请您尽快审核');
						\frontend\services\Func::addMessage(19,\yii\helpers\Url::to('/certification/index'),[]);
						echo json_encode(['code'=>0000,'msg'=>'申请认证已发出，请耐心等待客服审核']);
					}else{
                        echo json_encode(['code'=>1001,'msg'=>$model->errors]);
                    }
                    die;
                    break;
                case 2:
				    $cardimgs = Yii::$app->request->post('cardimg2');
                    $cardimgimg = Yii::$app->request->post('cardimgId2');
                    if(trim($name) == ''){
                        echo json_encode(['code'=>1004,'msg'=>'律所名称不可为空']);die;
                    }
                    if(trim($cardimgs) == ''){
                        echo json_encode(['code'=>1006,'msg'=>'执业证照片未上传']);die;
                    }
                    if(!$mobile&&Func::isTel($mobile)){
                        echo json_encode(['code'=>1008,'msg'=>'联系电话不可为空']);die;
                    }
                    if(!$cardno&&Func::isZhiyezhenghao($cardno)){
                        echo json_encode(['code'=>1010,'msg'=>'执业证号格式不正确']);die;
                    }
                    $model->uid = $uid;
                    $model->category = 2;
                    $model->state = 0;
                    $model->name = $name;
                    $model->cardno = $cardno;
                    $model->email = $email;
                    $model->contact = $contact;
                    $model->mobile = $mobile;
                    $model->casedesc = $casedesc;
                    $model->cardimg = $cardimgs;
					$model->cardimgimg = $cardimgimg;
                    Yii::$app->session->set("fromWhat","lawyer");
                    if($model->save()){
						//Yii::$app->smser->sendMsgByMobile(13918500509,'有用户申请律所认证，请您尽快审核');
						\frontend\services\Func::addMessage(28,\yii\helpers\Url::to('/certification/index'),[]);
						echo json_encode(['code'=>0000,'msg'=>'申请认证已发出，请耐心等待客服审核']);
					}else{
                        echo json_encode(['code'=>1001,'msg'=>$model->errors]);
                    }
                    die;
                    break;
                case 3:
				    $cardimgs = Yii::$app->request->post('cardimg3');
                    $cardimgimg = Yii::$app->request->post('cardimgId3');
                    if(trim($name) == ''){
                        echo json_encode(['code'=>1004,'msg'=>'企业名称不可为空']);die;
                    }
                    if(trim($cardimgs) == ''){
                        echo json_encode(['code'=>1006,'msg'=>'营业执照照片未上传']);die;
                    }
                    if(!$mobile&&Func::isTel($mobile)){
                        echo json_encode(['code'=>1008,'msg'=>'联系电话不可为空']);die;
                    }
                    if(!$enterprisewebsite&&Func::isNet($enterprisewebsite)){
                        echo json_encode(['code'=>1009,'msg'=>'网址格式不对']);die;
                    }
                    /*if(!$cardno&&Func::isZhiyezhenghao($cardno)){
                        echo json_encode(['code'=>1010,'msg'=>'营业执照号格式不正确']);die;
                    }*/
                    $model->uid = $uid;
                    $model->category = 3;
                    $model->state = 0;
                    $model->name = $name;
                    $model->cardno = $cardno;
                    $model->email = $email;
                    $model->contact = $contact;
                    $model->mobile = $mobile;
                    $model->casedesc = $casedesc;
                    $model->cardimg = $cardimgs;
					$model->cardimgimg = $cardimgimg;
                    $model->enterprisewebsite = $enterprisewebsite;
                    $model->address = $address;

                    Yii::$app->session->set("fromWhat","orgnization");
                    if($model->save()){
						//Yii::$app->smser->sendMsgByMobile(13918500509,'有用户申请企业认证，请您尽快审核');
						\frontend\services\Func::addMessage(28,\yii\helpers\Url::to('/certification/index'),[]);
						echo json_encode(['code'=>0000,'msg'=>'申请认证已发出，请耐心等待客服审核']);
					}else{
                        echo json_encode(['code'=>1001,'msg'=>$model->errors]);
                    }
                    die;
                    break;
                default:
                    break;
            }
        die;
        }

        return $this->render('add',['type'=>$type,'model'=>$model,'certification'=>$certification]);
    }

    public function actionAgent(){
        $uid = Yii::$app->user->getId();
        $certificationFromSelf = Certification::findOne(['uid'=>$uid]);
        $certification = $this->getCertification();

        if(isset($certification->state)&&!isset($certificationFromSelf->state)){
            $delegateUser = \common\models\User::findOne(['pid'=>$certification->uid,'id'=>yii::$app->user->getId()]);
            return $this->render('agent',['certification'=>$certification,'delegateUser'=>$delegateUser]);
        }elseif(isset($certificationFromSelf->state)&&$certificationFromSelf->state == 1){

            if(Yii::$app->request->isPost && in_array($certificationFromSelf->category,[2,3])){
                $name = Yii::$app->request->post('name');
                $mobile = Yii::$app->request->post('mobile');
                $cardno = Yii::$app->request->post('cardno');
                $zycardno = Yii::$app->request->post('zycardno');
                $password = Yii::$app->request->post('password');
                 
                $model = \common\models\User::findOne(['mobile'=>$mobile]);
                $cardnos = \common\models\User::findOne(['cardno'=>$cardno]);
                $zycardnos = \common\models\User::findOne(['zycardno'=>$zycardno]);
                $agents = Yii::$app->db->createCommand("select count(pid) from zcb_user where pid = $certificationFromSelf->uid")->queryScalar();
                if(isset($model->mobile)&&$model->mobile){
                    echo json_encode(['code'=>'1001',"msg"=>"该手机号已存在"]);die;
                }
                if(isset($cardnos->cardno)&&$cardnos->cardno){
                    echo json_encode(['code'=>'1007',"msg"=>"该身份证已注册"]);die;
                }
                if(isset($zycardnos->zycardno)&&$zycardnos->zycardno){
                    echo json_encode(['code'=>'1006',"msg"=>"该执业照号已注册"]);die;
                }
                if(!Func::isMobile($mobile)){
                    echo json_encode(['code'=>'1003',"msg"=>"该手机号格式错误"]);die;
                }

                if(!Func::isCardNo($cardno)){
                    echo json_encode(['code'=>'1004',"msg"=>"身份证格式错误"]);die;
                }
                if($certificationFromSelf->category == 2){
                    if(!Func::isZhiyezhenghao($zycardno)){
                        echo json_encode(['code'=>'1005',"msg"=>"执业证号格式错误"]);die;
                    }
                }
                if(isset($certificationFromSelf->managersnumber)&&$certificationFromSelf->managersnumber == ''){
                    echo json_encode(['code'=>'1006',"msg"=>"请申请添加代理人个数"]);die;
                }

                if(intval($agents) >= $certificationFromSelf->managersnumber){
                    echo json_encode(['code'=>'1007',"msg"=>"添加代理人请不要超给".$certificationFromSelf->managersnumber."个"]);die;
                }

                $model  = new \common\models\User();
                $model->username = $name;
                $model->mobile = $mobile;
                $model->cardno = $cardno;
                $model->zycardno = $zycardno;
                $model->pid = Yii::$app->user->getId();
                $model->auth_key = Yii::$app->security->generateRandomString();
                $model->password_hash =  Yii::$app->security->generatePasswordHash($password);;
                if($model->save()){
                    echo json_encode(['code'=>'0000',"msg"=>"添加代理人成功"]);die;
                }else{
                    echo json_encode(['code'=>'1002',"msg"=>$model->errors]);die;
                }
            }

         //   $delegateUser = \common\models\User::findAll(['pid'=>Yii::$app->user->getId()]);
            $sql = "select * from zcb_user where pid = ".Yii::$app->user->getId();
            $query = \Yii::$app->db->createCommand($sql)->query();
            $pagination = new Pagination(['defaultPageSize'=>5,'totalCount'=>$query->count()]);
            $sqls = $sql . " limit ".$pagination->offset." , ".$pagination->limit;
            $delegateUser = \Yii::$app->db->createCommand($sqls)->query();
            return $this->render('agent',['certification'=>$certificationFromSelf,'delegateUser'=>$delegateUser,'pagination'=>$pagination]);
        }else{
            throw new BadRequestHttpException('此页面不存在',404);
        }
    }

    public function actionStop(){
        $id = yii::$app->request->post('id');
        $user = \common\models\User::findOne(['id'=>$id]);
        $user->isstop = 1;
        $user->save();
        exit(json_encode(1));
    }
}

