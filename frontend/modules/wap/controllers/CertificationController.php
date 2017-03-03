<?php

namespace frontend\modules\wap\controllers;

use common\models\Certification;
use common\models\User;
use frontend\modules\wap\components\WapController;
use yii;
use frontend\modules\wap\services\Func;
use yii\web\Controller;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

/**
 * 用户操作控制器
 */
class CertificationController extends WapController
{
    /**
     * 注册方法
     * @param string mobile
     * @return json
     * **/
    public function actionAdd(){
        $category = Yii::$app->request->post('category')?Yii::$app->request->post('category'):0;
        $name = Yii::$app->request->post('name');
        $cardno = Yii::$app->request->post('cardno');
        $email = Yii::$app->request->post('email');
        $casedesc = Yii::$app->request->post('casedesc');
        $mobile = Yii::$app->request->post('mobile');
        $contact = Yii::$app->request->post('contact');
        $address = Yii::$app->request->post('address');
        $enterprisewebsite = Yii::$app->request->post('enterprisewebsite');
        $cardimgs = Yii::$app->request->post('cardimgs');
        $type = Yii::$app->request->post('type');
        $completionRate = Yii::$app->request->post('completionRate',1);
        $uid  = Yii::$app->session->get('user_id');
		$cardimgimg = Yii::$app->request->post('cardimgimg');

        if($cardimgs){
            $cardimg = Func::getSinglepicture(Func::hex2bin($cardimgs));
        }else if(Yii::$app->request->post('picture')){
            $cardimg = Func::getSinglepicture(base64_decode(Yii::$app->request->post('picture')));//安卓的图片要先将进制进行解码。
        }else{
            $cardimg = Yii::$app->request->post('cardimg');
        }

        $certification = Func::getCertification();

        if(isset($certification)&&ArrayHelper::isIn($certification['category'],[2,3])&&Func::hasProducts()&&$completionRate==1){
            echo Json::encode(['code'=>'3001','msg'=>'您已发布或者接单不能修改认证信息']);die;
        }

        if(!ArrayHelper::isIn($category,[1,2,3])){
            echo Json::encode(['code'=>'1001','msg'=>'身份类型错误']);die;
        }

        /*if(isset($certification)&&ArrayHelper::isIn($certification->category,[2,3])&&!ArrayHelper::isIn($category,[2,3])){
            echo Json::encode(['code'=>'1002','msg'=>'非个人用户不能修改为个人']);die;
        }*/

        $model = isset($certification)?$certification:new Certification();
        if($type == 'add' && isset($certification)){
            echo Json::encode(['code'=>'1015','msg'=>'你已认证，不可重复认证']);die;
        }
        switch($category){
            case 1:
                if(trim($name) == ''){
                    echo Json::encode(['code'=>'1002','msg'=>'姓名不可为空']);die;
                }
                if(trim($mobile) == ''){
                    echo Json::encode(['code'=>'1002','msg'=>'手机号码不能为空']);die;
                }else if(!Func::isMobile($mobile)){
                    echo Json::encode(['code'=>'1012','msg'=>'手机号码格式错误']);die;
                }


                if(!Func::isCardNo($cardno)){
                    echo Json::encode(['code'=>'1003','msg'=>'身份证号码不正确']);die;
                }

                $model->uid = $uid;
                $model->category = 1;
                $model->state = 0;
                $model->name = $name;
                $model->mobile = $mobile;
                $model->cardno = $cardno;
                $model->email = $email;
                $model->casedesc = $casedesc;
                $model->cardimg = $cardimg;
				$model->cardimgimg = $cardimgimg;
				$model->create_time = time();
                Yii::$app->session->set("fromWhat","personal");
                if($model->save()){
					Yii::$app->smser->sendMsgByMobile(13918500509,'用户['.$model->mobile.']申请个人认证，请您尽快审核');
					Func::addMessagesPerType('申请认证',"认证申请已发出，请耐心等待客服审核。",10,serialize([]));
					
                    echo Json::encode(['code'=>'0000','msg'=>'申请认证已发出，请耐心等待客服审核']);
                }else{
                    echo Json::encode(['code'=>'1014','msg'=>$model->errors]);die;
                }
                die;
                break;
            case 2:
                if(trim($name) == ''){
                    echo Json::encode(['code'=>1005,'msg'=>'律所名称不可为空']);die;
                }
                if(trim($cardimg) == ''){
                    echo Json::encode(['code'=>1006,'msg'=>'执业证照片未上传']);die;
                }
                if(trim($mobile) == ''){
                    echo Json::encode(['code'=>'1002','msg'=>'联系电话不可为空']);die;
                }else if(!Func::isMobile($mobile)){
                    echo Json::encode(['code'=>'1012','msg'=>'联系电话格式错误']);die;
                }
                if(!Func::isZhiyezhenghao($cardno)){
                    echo Json::encode(['code'=>1008,'msg'=>'执业证号必须是17位']);die;
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
                $model->cardimg = $cardimg;
				$model->cardimgimg = $cardimgimg;
				 $model->create_time = time();
                Yii::$app->session->set("fromWhat","lawyer");
                if($model->save()){
					Yii::$app->smser->sendMsgByMobile(13918500509,'用户['.$model->mobile.']申请律所认证，请您尽快审核');
					Func::addMessagesPerType('申请认证',"认证申请已发出，请耐心等待客服审核。",10,serialize([]));
                    echo Json::encode(['code'=>'0000','msg'=>'申请认证已发出，请耐心等待客服审核']);
                }else{
                    echo Json::encode(['code'=>'1014','msg'=>$model->errors]);die;
                }
                die;
                break;
            case 3:
                if(trim($name) == ''){
                    echo Json::encode(['code'=>'1010','msg'=>'企业名称不可为空']);die;
                }
                if(trim($cardimg) == ''){
                    echo Json::encode(['code'=>'1011','msg'=>'营业执照照片未上传']);die;
                }
                if(trim($mobile) == ''){
                    echo Json::encode(['code'=>'1002','msg'=>'联系电话不可为空']);die;
                }else if(!Func::isMobile($mobile)){
                    echo Json::encode(['code'=>'1012','msg'=>'联系电话格式错误']);die;
                }
                $model->uid = $uid;
                $model->category = 3;
                $model->state = 0;
                $model->name = $name;
                $model->cardno = $cardno;
                $model->email = $email;
                $model->contact = $contact;
                $model->mobile = $mobile;
                $model->casedesc = $casedesc;
                $model->cardimg = $cardimg;
                $model->enterprisewebsite = $enterprisewebsite;
                $model->address = $address;
				$model->cardimgimg = $cardimgimg;
                $model->create_time = time();
                Yii::$app->session->set("fromWhat","orgnization");
                if($model->save()){
					Yii::$app->smser->sendMsgByMobile(13918500509,'用户['.$model->mobile.']申请企业认证，请您尽快审核');
                    Func::addMessagesPerType('申请认证',"认证申请已发出，请耐心等待客服审核。",10,serialize([]));
                    echo Json::encode(['code'=>'0000','msg'=>'申请认证已发出，请耐心等待客服审核']);
                }else{
                    echo Json::encode(['code'=>'1014','msg'=>$model->errors]);die;
                }
                die;
                break;
        }
    }

    /**
     * 用户中心的个人数据
     * @return json
     **/
    public function actionView(){
        $certification = Func::getCertification();
        $uid = Yii::$app->session->get('user_id');
        $user = \common\models\User::find()->select(["id","username","realname","mobile","picture"])->where(['id'=>$uid])->asArray()->one();
		$user['pictureimg'] = (new \yii\db\Query())
									->select('id,file')
									->from('zcb_files')
									->where(["id"=>$user['picture']])
									->limit(1)
									->one();
		if($user['pictureimg']){
			$user['pictureurl']	=\yii\helpers\Url::toRoute("/",true).$user['pictureimg']['file'];		
		}else{
			$user['pictureurl']= \yii\helpers\Url::toRoute("/",true).'/images/defaulthead.png';
		}
					
        if($certification == null){
            echo Json::encode(['code'=>'0001','msg'=>'您还未认证','result'=>['uid' => $uid,'userinfo'=>$user,'user'=>$user['mobile']]]);die;
        }else{
            $certification = $certification->toArray();
			$certification['img'] = (new \yii\db\Query())
									->select('id,file')
									->from('zcb_files')
									->where(["id"=>explode(",",$certification['cardimgimg'])])
									->limit(2)
									->all();
            $completionRate =  Func::ArchivementRate($certification);
            if(isset($certification)&&ArrayHelper::isIn($certification['category'],[2,3])&&Func::hasProducts()){
                $certification['canModify'] = 0;
            }elseif(isset($certification)){
                $certification['canModify'] = 1;
            }
            echo Json::encode(['code'=>'0000','result'=>['certification'=>$certification,'uid'=>$uid,'user'=>$user['mobile'],'userinfo'=>$user,'completionRate'=>$completionRate]]);die;
        }

    }

    /**
     * 拿取对方的个人数据
     * @param string uid
     * @return json
     **/
    public function actionViews(){
        $uid = \Yii::$app->request->post('uid');
        $user = User::findOne(['pid'=>$uid]);
        if($user){
            $certification = Certification::findOne(['uid'=>$user->pid]);
        }else{
            $certification = Certification::findOne(['uid'=>$uid]);
        }
        if(!$certification){
            echo Json::encode(['code'=>'4001','msg'=>'您还未认证']);die;
        }else{
            $certification = $certification->toArray();
            if(isset($certification)&&ArrayHelper::isIn($certification['category'],[2,3])&&Func::hasProducts()){
                $certification['canModify'] = 0;
            }elseif(isset($certification)){
                $certification['canModify'] = 1;
            }
            echo Json::encode(['code'=>'0000','result'=>$certification]);die;
        }

    }

    /**
     * 添加代理人
     * @param string name
     * @param string mobile
     * @param string cardno
     * @param string password
     * @param string zycardno
     * @return json
     **/
    public function actionAddagent(){
        $uid = Yii::$app->session->get('user_id');
        $certificationFromSelf = Certification::findOne(['uid'=>$uid]);
        if(isset($certificationFromSelf->state)&&$certificationFromSelf->state == 1&&ArrayHelper::isIn($certificationFromSelf->category,[2,3])){
            $name = Yii::$app->request->post('name');
            $mobile = Yii::$app->request->post('mobile');
            $cardno = Yii::$app->request->post('cardno');
            $password = Yii::$app->request->post('password');

            $model = \common\models\User::findOne(['mobile'=>$mobile]);
            $cardnos = \common\models\User::findOne(['cardno'=>$cardno]);

            $usernumber = Yii::$app->db->createCommand("select count(pid) from zcb_user where pid = {$uid}")->queryScalar();
            if(intval($usernumber) >= $certificationFromSelf['managersnumber']){
                echo Json::encode(['code'=>'1008','msg'=>'添加代理人已达上限，请联系我们工作人员']);die;
            }
            if(trim($name) == ''){
                echo Json::encode(['code'=>'1010','msg'=>'请填写用户名']);die;
            }
            if(trim($mobile) == ''){
                echo Json::encode(['code'=>'1011','msg'=>'请填写手机号码']);die;
            }
            if(trim($cardno) == ''){
                echo Json::encode(['code'=>'1012','msg'=>'请填写身份证号']);die;
            }
            if($certificationFromSelf['category'] == 2) {
                $zycardno = Yii::$app->request->post('zycardno','');
                $zycardnos = \common\models\User::findOne(['zycardno'=>$zycardno]);
                if (trim($zycardno) == '') {
                    echo Json::encode(['code' => '1013', 'msg' => '请填写执业证号']);
                    die;
                }
                if (isset($zycardnos->zycardno) && $zycardnos->zycardno) {
                    echo Json::encode(['code' => '1006', "msg" => "该执业照号已注册"]);
                    die;
                }
                if(!Func::isZhiyezhenghao($zycardno)){
                    echo Json::encode(['code'=>'1005',"msg"=>"执业证号必须是17位"]);die;
                }
            }
            if(trim($password) == ''){
                echo Json::encode(['code'=>'1014','msg'=>'请填写密码']);die;
            }
            if(isset($model->mobile)&&$model->mobile){
                echo Json::encode(['code'=>'1001',"msg"=>"该手机号已存在"]);die;
            }
            if(isset($cardnos->cardno)&&$cardnos->cardno){
                echo Json::encode(['code'=>'1007',"msg"=>"该身份证已注册"]);die;
            }

            if(!Func::isMobile($mobile)){
                echo Json::encode(['code'=>'1003',"msg"=>"该手机号格式错误"]);die;
            }

            if(!Func::isCardNo($cardno)){
                echo Json::encode(['code'=>'1004',"msg"=>"身份证格式错误"]);die;
            }

            $model  = new \common\models\User();
            $model->username = $name;
            $model->mobile = $mobile;
            $model->cardno = $cardno;
            $model->zycardno = isset($zycardno)?$zycardno:'';
            $model->pid = $uid;
            $model->auth_key = Yii::$app->security->generateRandomString();
            $model->password_hash =  Yii::$app->security->generatePasswordHash($password);;
            if($model->save()){
                Func::addMessagesPerType('添加经办人成功',"恭喜您添加经办人成功，经办人可以发布信息或者处置信息。",23,serialize([]));
                echo Json::encode(['code'=>'0000',"msg"=>"添加代理人成功"]);die;
            }else{
                echo Json::encode(['code'=>'1014',"msg"=>$model->errors]);die;
            }
        }else{
            echo Json::encode(['code'=>'3001',"msg"=>"你还未认证或者认证是个人不能添加代理人"]);die;
        }
    }


    /**
     * 修改代理人
     * @param string name
     * @param string mobile
     * @param string cardno
     * @param string password
     * @param string zycardno
     * @return json
     **/
    public function actionModifyagent(){
        $id = Yii::$app->request->post('id');
        $uid = Yii::$app->session->get('user_id');
        $certificationFromSelf = Certification::findOne(['uid'=>$uid]);
        if(isset($certificationFromSelf->state)&&$certificationFromSelf->state == 1&&ArrayHelper::isIn($certificationFromSelf->category,[2,3])&&is_numeric($id)){
            $name = Yii::$app->request->post('name');
            $mobile = Yii::$app->request->post('mobile');
            $cardno = Yii::$app->request->post('cardno');
            $password = Yii::$app->request->post('password');
            $modelc = \common\models\User::findOne(['id'=>$id]);
            $model = \common\models\User::findOne(['mobile'=>$mobile]);
            $cardnos = \common\models\User::findOne(['cardno'=>$cardno]);
            if(trim($name) == ''){
                echo Json::encode(['code'=>'1010','msg'=>'请填写用户名']);die;
            }
            if(trim($mobile) == ''){
                echo Json::encode(['code'=>'1011','msg'=>'请填写手机号码']);die;
            }
            if(trim($cardno) == ''){
                echo Json::encode(['code'=>'1012','msg'=>'请填写身份证号']);die;
            }
            if(trim($password) == ''){
                echo Json::encode(['code'=>'1014','msg'=>'请填写密码']);die;
            }
            if(isset($model->mobile)&&$model->mobile && $modelc->mobile != $mobile){
                echo Json::encode(['code'=>'1001',"msg"=>"该手机号已存在"]);die;
            }
            if(isset($cardnos->cardno)&&$cardnos->cardno &&  $modelc->cardno != $cardno){
                echo Json::encode(['code'=>'1007',"msg"=>"该身份证已注册"]);die;
            }
            if($certificationFromSelf['category'] == 2) {
                $zycardno = Yii::$app->request->post('zycardno','');
                if (trim($zycardno) == '') {
                    echo Json::encode(['code' => '1013', 'msg' => '请填写执业证号']);
                    die;
                }
                $zycardnos = \common\models\User::findOne(['zycardno'=>$zycardno]);
                if (isset($zycardnos->zycardno) && $zycardnos->zycardno && $modelc->zycardno != $zycardno) {
                    echo Json::encode(['code' => '1006', "msg" => "该执业照号已注册"]);
                    die;
                }
                if(!Func::isZhiyezhenghao($zycardno)){
                    echo Json::encode(['code'=>'1005',"msg"=>"执业证号格式错误"]);die;
                }
            }
            if(!Func::isMobile($mobile)){
                echo Json::encode(['code'=>'1003',"msg"=>"该手机号格式错误"]);die;
            }

            if(!Func::isCardNo($cardno)){
                echo Json::encode(['code'=>'1004',"msg"=>"身份证格式错误"]);die;
            }
            $modelc->username = $name;
            $modelc->mobile = $mobile;
            $modelc->cardno = $cardno;
            $modelc->zycardno = isset($zycardno)?$zycardno:'';
            $modelc->pid = $uid;
            $modelc->auth_key = Yii::$app->security->generateRandomString();
            $modelc->password_hash =  Yii::$app->security->generatePasswordHash($password);;
            if($modelc->save()){
                Func::addMessagesPerType('修改经办人成功',"恭喜您修改经办人成功，经办人可以发布信息或者处置信息。",23,serialize([]));
                echo Json::encode(['code'=>'0000',"msg"=>"修改代理人成功"]);die;
            }else{
                echo Json::encode(['code'=>'1014',"msg"=>$modelc->errors]);die;
            }
        }else{
            echo Json::encode(['code'=>'3001',"msg"=>"你还未认证或者认证是个人不能修改代理人"]);die;
        }
    }


    /**
     * 代理人列表
     * @param string page
     * @param string limit
     * @return json
     **/
    public function actionAgentlist(){
        $uid = Yii::$app->session->get('user_id');
        $page = Yii::$app->request->post('page')?Yii::$app->request->post('page'):1;
        $limit = Yii::$app->request->post('limit')?Yii::$app->request->post('limit'):10;
        if(Yii::$app->request->post('token')) {
            $limitstr= "";
            if(is_numeric($page)&&is_numeric($limit)){
                $page = $page<=1?1:$page;
                $limit = $limit<=0?10:$limit;
                $limitstr = " limit ".($page-1)*$limit.",".$limit;
            }
            $sql  = "select * from zcb_user where pid = {$uid}";
            $user = \Yii::$app->db->createCommand($sql.$limitstr)->query();
            echo Json::encode(['code'=>'0000','result'=>['user'=>$user]]);die;
        }else{
            echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
        }

    }

    /**
     * 代理人列表数据
     * @param string id
     * @return json
     **/
    public function actionAgentexhibition(){
        $id = Yii::$app->request->post('id');
        $uid = Yii::$app->session->get('user_id');
        $certification = Certification::findOne(['uid'=>$uid]);
        if(!empty($id) && is_numeric($id)){
            $user = \common\models\User::findOne(['id'=>$id,'pid'=>$uid]);
            if($user){
                echo Json::encode(['code'=>'0000','result'=>['user'=>$user,'certification'=>$certification]]);die;
            }else{
                echo Json::encode(['code'=>'4001','msg'=>'没有数据，请查询参数是否错误']);die;
            }
        }else{
            echo Json::encode(['code'=>'0001','result'=>['certification'=>$certification]]);die;
        }
    }

    /**
     * 停用代理人
     * @param string id
     * @param string status
     * @return json
     **/
    public function actionStopagent(){
        $id = yii::$app->request->post('id');
        $status = yii::$app->request->post('status')?yii::$app->request->post('status'):1;
        $uid = Yii::$app->session->get('user_id');
        if(is_numeric($id)&&is_numeric($status)&&ArrayHelper::isIn($status,[0,1])){
            $user = \common\models\User::findOne(['id'=>$id,"pid"=>$uid]);
            if(isset($user->id)){
                $user->isstop = $status;
                $msg = [0=>'启用代理人成功',1=>'停用代理人成功'];
                if($user->save()){
                    Func::addMessagesPerType('停用代理人成功',"停用代理人成功。",23,serialize([]));
                    echo Json::encode(['code'=>'0000',"msg"=>$msg[$status]]);die;
                }else{
                    echo Json::encode(['code'=>'1014',"msg"=>$user->errors]);die;
                }
            }else{
                echo Json::encode(['code'=>'4002',"msg"=>"代理人不存在"]);die;
            }
        }else{
            echo Json::encode(['code'=>'1001',"msg"=>"参数错误"]);die;
        }
    }
}
