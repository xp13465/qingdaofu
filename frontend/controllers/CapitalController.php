<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use common\models\FinanceProduct;
use common\models\CreditorProduct;
use common\models\Certification;
use common\models\Apply;
use app\models\user;
use frontend\services\Func;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use frontend\components\FrontController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\ActiveRecord;
use yii\helpers;
use yii\helpers\BaseJson;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\CookieCollection;
class CapitalController extends FrontController{
    public $layout  = 'register';
    public function actionList(){

        $this->title = "产品列表_清收_诉讼-清道夫债管家";
        $this->keywords = "产品列表";
        $this->description = "清道夫债管家产品列表专注于为广大客户提供详细的清收、诉讼等服务信息。";
        $category = Yii::$app->request->get('cat');
        $province_id = Yii::$app->request->get('province_id')?Yii::$app->request->get('province_id'):0;
        $city_id = Yii::$app->request->get('city_id')?Yii::$app->request->get('city_id'):0;
        $district = Yii::$app->request->get('district_id')?Yii::$app->request->get('district_id'):0;
        $money = Yii::$app->request->get('money');
        $four = Yii::$app->request->get('progress');
        $where = " and is_del = 0 ";
        if(!in_array($province_id,['0',''])) {
            $where .= " and province_id = '{$province_id}' ";
        }
        if(!in_array($city_id,['0',''])) {
            $where .= " and city_id = '{$city_id}' ";
        }
        if(!in_array($district,['0',''])) {
            $where .= " and district_id = '{$district}' ";
        }
        if(in_array($category,[1,2,3])){
            $where .= " and category = '{$category}'";
        }
        if(in_array($four,[1,2,3,4])){
            $where.=" and progress_status in ({$four})";
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
            $sql = "select id,seatmortgage,city_id,category,code,term,district_id,rate,rate_cat,create_time,modify_time,money,progress_status,browsenumber,province_id ,uid ,loan_type ,rebate , loan_type ,mortgagemoney ,rentmoney from zcb_finance_product where  progress_status != 0 ".$where." union
                select id,seatmortgage,city_id,category,code,term,district_id,rate,rate_cat,create_time,modify_time,money,progress_status ,browsenumber,province_id ,uid ,loan_type ,rebate ,loan_type ,agencycommission ,agencycommissiontype from zcb_creditor_product where  progress_status != 0  ".$where." order by progress_status asc ,modify_time desc";
       $querys = \Yii::$app->db->createCommand($sql)->query();
       //$query = \Yii::$app->db->createCommand($sql)->queryAll();
        $pagination = new Pagination(['defaultPageSize'=>10,'totalCount'=>$querys->count()]);
        $sql = $sql . " limit ".$pagination->offset." , ".$pagination->limit;
        $rows = \Yii::$app->db->createCommand($sql)->query();
       /* $rowsall = [];
        foreach($rows as $kk=>$rr){
            $rr['nextID'] = isset($query[$pagination->defaultPageSize*$pagination->page+$kk+1]['id'])?$query[$pagination->defaultPageSize*$pagination->page+$kk+1]['id']:0;
            $rowsall[] = $rr;
        }*/
        $url = Yii::$app->request->url;
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => "name",
            'value'=>$url
        ]));
		$cookies->add(new \yii\web\Cookie([
            'name' => "url",
            'value'=>$url
        ]));
        return $this->render('list',['pagination'=>$pagination ,'creditor'=>$rows ]);
    }

    public function actionIschakan(){
        if(Yii::$app->request->isPost){
            $category = Yii::$app->request->post('category');
            $id = Yii::$app->request->post('id');
            $uid = Yii::$app->user->getId();

            if(Yii::$app->user->isGuest){
                echo json_encode(['code'=>"1001",'msg'=>'您尚未登录，请登录后查看']);die;
            }

            /*$certification = Func::getCertification();
            if(!isset($certification->state)||$certification->state != 1){
                echo json_encode(['code'=>"1002",'msg'=>'您尚未认证，请认证后查看']);die;
            }

            $product = Func::getProduct($category,$id);
            switch($certification->category){
                case 1:
                    if($product->uid != $uid){
                        echo json_encode(['code'=>"1003",'msg'=>'个人只能查看自己发布的数据']);die;
                    }
                break;
                case 2:
                    if(!in_array($category,[1,2])){
                        echo json_encode(['code'=>"1004",'msg'=>'律所只能申请清收和诉讼']);die;
                    }
                break;
                case 3:
                    if(!in_array($category,[2,3])){
                        echo json_encode(['code'=>"1005",'msg'=>'公司只能申请融资和清收']);die;
                    }
                break;
            }*/

            echo json_encode(['code'=>"0000",'msg'=>'']);die;
        }
    }

    public function actionIsapply(){

        if(Yii::$app->request->isPost){
            $category = Yii::$app->request->post('category');
            $id = Yii::$app->request->post('id');
            $uid = Yii::$app->user->getId();

            if(Yii::$app->user->isGuest){
                echo json_encode(['code'=>"1001",'msg'=>'您尚未登录，请登录后查看']);die;
            }

            $certification = Func::getCertification();
            if(!isset($certification->state)||$certification->state != 1){
                echo json_encode(['code'=>"1002",'msg'=>'您尚未认证，请认证后查看']);die;
            }

            $product = Func::getProduct($category,$id);
            switch($certification->category){
                case 1:
                    if($product->uid != $uid){
                        echo json_encode(['code'=>"1003",'msg'=>'个人只能查看自己发布的数据']);die;
                    }
                    break;
                case 2:
                    if(!in_array($category,[2,3])){
                        echo json_encode(['code'=>"1004",'msg'=>'律所只能申请清收和诉讼']);die;
                    }
                    break;
                case 3:
                    if(!in_array($category,[1,2])){
                        echo json_encode(['code'=>"1005",'msg'=>'公司只能申请融资和清收']);die;
                    }
                    break;
            }

            if($product->uid == $uid){
                echo json_encode(['code'=>"1007",'msg'=>'请不要申请自己发布的数据']);die;
            }

            $apply = \common\models\Apply::findOne(['category'=>$category,'product_id'=>$id,'uid'=>$uid]);

            if(isset($apply->app_id)&&$apply->app_id == 1 || $apply->app_id === 0){
                echo json_encode(['code'=>"1007",'msg'=>'请不要重复申请']);die;
            }

            echo json_encode(['code'=>"1006",'msg'=>'请确认是否申请']);die;
        }
    }
    //认证方法
    public function actionCertification(){
        $id = \yii::$app->request->post('id');
        $certification=\common\models\Certification::findOne(['uid'=>\Yii::$app->user->getId()]);
        $creditor = \common\models\CreditorProduct::findOne(['id'=>$id]);
        $finance = \common\models\FinanceProduct::findOne(['id'=>$id]);
        $username = \common\models\User::findOne(['id'=>\Yii::$app->user->getId()]);
        $agency = \common\models\Certification::findOne(['uid'=>$username['pid']]);
        if(!\Yii::$app->user->getId()){
            $info['status'] = 0;
        }else if($certification || $agency || $creditor['uid']==\Yii::$app->user->getId() || $finance['uid']==\Yii::$app->user->getId()){
            $info['status'] = 1;
        }else{
            $info['status'] = 2;
        }
        exit(json_encode($info));
    }
    public function actionApplyorder($id,$category){
        $this->title = "清道夫债管家";

            $browsenumber =1;
            if($category == 1){
                $data = FinanceProduct::findOne(['id'=>$id]);
                $data->browsenumber = $data['browsenumber']+$browsenumber;
                $data->save();
            }else{
                $data = CreditorProduct::findOne(['id'=>$id]);
                $data->browsenumber = $data['browsenumber']+$browsenumber;
                $data->save();
            }
            $apply = \common\models\Apply::findOne(['product_id'=>$id,'category'=>$category,'uid'=>\Yii::$app->user->getId(),'app_id'=>1]);
            $read = \common\models\Statistics::findOne(['cid'=>$id,'category'=>$category,'uid'=>\Yii::$app->user->getId()]);
            if($read){
                $readCount =1;
                $read->status = isset($apply['app_id'])?1:0;
                $read->readCount = $read['readCount']+$readCount;
                $read->save();
            }else{
                $statistics = new \common\models\Statistics();
                $statistics->cid = $id;
                $statistics->category = $category;
                $statistics->uid = \Yii::$app->user->getId();
                $statistics->status = isset($apply['app_id'])?1:0;
                $statistics->readCount = 1;
                $statistics->save();
            }
        $certification = \common\models\Certification::findOne(['uid'=>\Yii::$app->user->getId(),'state'=>1]);
        $agency = \common\models\User::findOne(['id'=> Yii::$app->user->getId()]);
        $certi =\common\models\Certification::findOne(['uid'=>$agency['pid']]);
         return $this->render('applyorder',['finance' => $data , 'certification'=>$certification,'certi'=>$certi]);

    }

    public function actionCollectionlist()
    {
        $this->title = "清道夫债管家";
        $data = Yii::$app->request;
        $credi_id = \common\models\Apply::find()->where(['product_id' => $data->post('id'), 'uid' => \Yii::$app->user->getId(),'category'=>$data->post('category')])->One();
        $agency = \common\models\User::findOne(['id'=>\Yii::$app->user->getId()]);
        $certi = \common\models\Certification::findOne(['uid'=>$agency['pid']]);
        $finance =FinanceProduct::findOne(['id'=>$data->post('id'),'category'=>$data->post('category')]);
        $cerditor=CreditorProduct::findOne(['id'=>$data->post('id'),'category'=>$data->post('category')]);
        if(\Yii::$app->user->getId()) {
            if($data->post('uid') == \Yii::$app->user->getId()){
                $info['status'] = 1;
            }else if($credi_id['uid'] == \Yii::$app->user->getId()) {
                $info['status'] = 2;
            } else if(\common\models\Certification::findOne(['uid'=>\Yii::$app->user->getId(),'category'=>1])){
                $info['status'] = 3;
            }else if(\common\models\Certification::findOne(['uid'=>\Yii::$app->user->getId(),'category'=>2]) && $finance || $certi['category'] == 2 && $finance){
                $info['status'] = 4;
            }else if(\common\models\Certification::findOne(['uid'=>\Yii::$app->user->getId(),'category'=>3]) && $cerditor['category'] == 3 || $certi['category'] == 3 && $cerditor['category'] == 3){
                $info['status'] = 5;
            }else{
                if ($data->post()) {
                    $model = new \common\models\Apply();
                    $model->load(Yii::$app->request->post(), '');
                    $model->category = $data->post('category');
                    $model->uid = \Yii::$app->user->getId();
                    $model->product_id = $data->post('id');
                    $model->create_time = time();
                    $model->agree_time = time();
                    $model->app_id = 2;
                    $model->is_del =0;
                    if($model->save()){
                       $info['status'] = 6;
                    };
                }
            }
        }else{
           $info['status'] = 0;
        }
        exit(json_encode($info));
    }



    public function actionApplysuccessful()
    {
        $this->title = "清道夫债管家";
        $data = Yii::$app->request;
        $credi_id = \common\models\Apply::find()->where(['product_id' => $data->post('id'), 'uid' => \Yii::$app->user->getId(),'category'=>$data->post('category')])->One();
        $agency = \common\models\User::findOne(['id'=>\Yii::$app->user->getId()]);
        $certi = \common\models\Certification::findOne(['uid'=>$agency['pid']]);
        $finance =FinanceProduct::findOne(['id'=>$data->post('id'),'category'=>$data->post('category')]);
        $cerditor=CreditorProduct::findOne(['id'=>$data->post('id'),'category'=>$data->post('category')]);
        $certification = Func::getCertification();
        if (\Yii::$app->user->getId()) {
            if($data->post('uid') == \Yii::$app->user->getId()){
                $info['status'] = 1;
            }else if($credi_id['uid'] == \Yii::$app->user->getId()) {
                $info['status'] = 2;
            } else if(\common\models\Certification::findOne(['uid'=>\Yii::$app->user->getId(),'category'=>1])){
                $info['status'] = 3;
            }else if(\common\models\Certification::findOne(['uid'=>\Yii::$app->user->getId(),'category'=>2]) && $finance || $certi['category'] == 2 && $finance){
                $info['status'] = 4;
            }else if(\common\models\Certification::findOne(['uid'=>\Yii::$app->user->getId(),'category'=>3]) && $cerditor['category'] == 3 || $certi['category'] == 3 && $cerditor['category'] == 3) {
                $info['status'] = 5;
            }else if(!isset($certification->state)||$certification->state != 1){
                $info['status'] = 6;
            }else if(isset($credi_id->id)&&$credi_id->app_id==2){
                if($data->post('category') == 1){
                    $certi = \common\models\FinanceProduct::findOne(['id'=>$data->post('id')]);
                }else{
                    $certi = \common\models\CreditorProduct::findOne(['id'=>$data->post('id')]);
                }
                $mobile = \common\models\User::findOne(['id'=>$certi['uid']]);
                Yii::$app->smser->sendMsgByMobile($mobile['mobile'],'尊敬的用户：订单号:'.$certi["code"].'接单方申请接单，请您尽快处理，避免超时。详细信息请登录清道夫债管家账户系统查看。');
                $credi_id->app_id = 0;
                $credi_id->create_time = time();
                if($credi_id->save()){
                    $info['status'] = 7;
                };
            }else {
               /* if($data->post('category') == 1){
                    $certi = \common\models\FinanceProduct::findOne(['id'=>$data->post('id')]);
                }else{
                    $certi = \common\models\CreditorProduct::findOne(['id'=>$data->post('id')]);
                }
                $mobile = \common\models\User::findOne(['id'=>$certi['uid']]);
                Yii::$app->smser->sendMsgByMobile($mobile['mobile'],'尊敬的用户：订单号:'.$certi["code"].'接单方申请接单，请您尽快处理，避免超时。详细信息请登录清道夫债管家账户系统查看。');
                if ($data->post()) {
                    $read = \common\models\Statistics::findOne(['cid'=>$data->post('id'),'category'=>$data->post('category'),'uid'=>yii::$app->user->getId()]);
                    $read->status = 1;
                    $model = new \common\models\apply();
                    $model->load(Yii::$app->request->post(), '');
                    $model->category = $data->post('category');
                    $model->uid = \Yii::$app->user->getId();
                    $model->product_id = $data->post('id');
                    $model->create_time = time();
                    $model->agree_time = time();
                    $model->app_id = 0;
                    $model->is_del = 0;
                    $read->save();
                    if($model->save()) {
                        $info["status"] = 6;
                    }
                }*/
                $info["status"] = 7;
            }
        }else{
            $info['status'] = 0;
        }
        exit(json_encode($info));

    }

    public function actionRead(){
        $this->title = "清道夫债管家";
        $data = Yii::$app->request;
        if($data->post('category') == 1){
            $certi = \common\models\FinanceProduct::findOne(['id'=>$data->post('id')]);
        }else{
            $certi = \common\models\CreditorProduct::findOne(['id'=>$data->post('id')]);
        }
        $mobile = \common\models\User::findOne(['id'=>$certi['uid']]);
        if ($data->post()) {
            $read = \common\models\Statistics::findOne(['cid'=>$data->post('id'),'category'=>$data->post('category'),'uid'=>yii::$app->user->getId()]);
            Yii::$app->smser->sendMsgByMobile($mobile['mobile'],'尊敬的用户：订单号:'.$certi["code"].'接单方申请接单，请您尽快处理，避免超时。详细信息请登录清道夫债管家账户系统查看。');
            $read->status = 1;
            $model = new \common\models\Apply();
            $model->load(Yii::$app->request->post(),'');
            $model->category = $data->post('category');
            $model->uid = \Yii::$app->user->getId();
            $model->product_id = $data->post('id');
            $model->create_time = time();
            $model->agree_time = time();
            $model->app_id = 0;
            $model->is_del = 0;
            $read->save();
            if($model->save()) {
                exit(json_encode(1));
            }
        }
    }
}