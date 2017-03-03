<?php
namespace frontend\controllers;

use common\models\DisposingProcess;
use common\models\FinanceProduct;
use Yii;
use common\models\LoginForm;
use common\models\CreditorProduct;
use common\models\Apply;
use common\models\Sms;
use common\models\Certification;
use frontend\services\Func;
use app\models\User;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
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
class UserController extends FrontController
{
    public $layout = 'user';



    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
		$this->redirect('/message/list');
        $this->title = "清道夫债管家";
        $user = User::findOne(['id'=>\Yii::$app->user->getId()]);
        if($user['pid'] != ''){
            $certi =Certification::findOne(['uid'=>$user['pid']]);
            return $this->render('index',['certi'=>$certi]);
        }else{
            $certi =Certification::findOne(['uid'=>\Yii::$app->user->getId()]);
            return $this->render('index',['certi'=>$certi]);
        }

    }



    /**
     *Displays 申请融资查询
     */
    public function actionApply()
    {
        $this->title = "清道夫债管家";
        $request = Yii::$app->request;
        $id = $request->get('id');
        $sql = "select z.id,z.city_id ,b.id as bid ,b.category, b.create_time,b.uid ,zc.name,zc.mobile from zcb_finance_product as z left join
                zcb_apply as b on z.id = b.product_id left join zcb_certification as zc on b.uid = zc.uid where b.category=1 and b.app_id = 0 and z.id=$id";
        $finances = \Yii::$app->db->createCommand($sql)->query();
        return $this->render('apply_financing_query', ['id' => $id, 'finances' => $finances]);


    }

    /**
     *Displays 申请清收\诉讼查询
     */
    public function actionColl()
    {
        $this->title = "清道夫债管家";
        $request = Yii::$app->request;
        $id = $request->get('id');
        $sql = "select z.id,z.city_id ,b.id as bid,b.category, b.create_time,b.uid ,zc.name,zc.mobile from zcb_creditor_product as z left join
                zcb_apply as b on z.id = b.product_id left join zcb_certification as zc on b.uid = zc.uid where b.category in(2,3) and b.app_id = 0 and z.id=$id";
        $credis = \Yii::$app->db->createCommand($sql)->query();
        return $this->render('collectionQuery', [
            'credis' => $credis,
            'id' => $id,
        ]);
    }
    /**
     *Displays 填写进度数据查询
     */
    public function actionFinancing_progress($id){
        $this->title = "清道夫债管家";
       $progress = FinanceProduct::findOne(['id' => $id]);
        $sql = "select z.id,z.city_id ,b.id as bid ,b.category, b.create_time,b.uid ,zc.username,zc.mobile from zcb_finance_product as z left join
                zcb_apply as b on z.id = b.product_id left join zcb_user as zc on b.uid = zc.id where b.category=1 and b.app_id = 0 and z.id=$id";
        $finances = \Yii::$app->db->createCommand($sql)->query();
        $type = new FinanceProduct();
        $type_data = $type::$mortgagecategory;
        $date = $type::$ratedatecategory;
        return $this->render('financing_progress', [
            'progress' => $progress,
            'type' => $type_data,
            'date' => $date, 'finances' => $finances,
            //'pagination' =>$pagination
        ]);

    }
    /**
     *Displays 填写进度数据查询
     */
    public function actionFinancing_progress_pub($id){
        $this->title = "清道夫债管家";
       $progress = FinanceProduct::findOne(['id' => $id]);
        $sql = "select z.id,z.city_id ,b.id as bid ,b.category, b.create_time,b.uid ,zc.username,zc.mobile from zcb_finance_product as z left join
                zcb_apply as b on z.id = b.product_id left join zcb_user as zc on b.uid = zc.id where b.category=1 and b.app_id = 0 and z.id=$id";
        $finances = \Yii::$app->db->createCommand($sql)->query();
        $type = new FinanceProduct();
        $type_data = $type::$mortgagecategory;
        $date = $type::$ratedatecategory;
        return $this->render('financing_progress_pub', [
            'progress' => $progress,
            'type' => $type_data,
            'date' => $date, 'finances' => $finances,
            //'pagination' =>$pagination
        ]);

    }
    /**
     *Displays 填写进度协议查询
     */
    public function actionAgreement(){
        $this->title = "清道夫债管家";
        $request = Yii::$app->request;
        $uid = $request->get('uid');
        //exit(json_encode(1));
        return $this->render('agreement_progress');
    }
    //确定用户申请清收、诉讼数据保存
    public function actionDetermine_user()
    {
        $this->title = "清道夫债管家";
        $list = Yii::$app->request->post('idlist');
        $uid = explode(',', $list);
        $creditor = CreditorProduct::find()->where(['id' => $uid[1]])->one();
        if ($creditor['progress_status'] == 3) {
            exit(json_encode(0));
        } else {
            $test = Apply::find()->where(['uid' => $uid[0], 'id' => $uid[2]])->one();
            $test->app_id = 1;
            $creditor->progress_status = 3;
            $creditor->modify_time = time();
            $creditor->save();
            if ($test->save()) {
                exit(json_encode(1));
            };
        }
    }

    //确定用户申请的融资数据保存
    public function actionDetermine_financing()
    {
        $this->title = "清道夫债管家";
        $apply_id = Yii::$app->request->post('idlist');

        $uid = Yii::$app->user->getId();

        $finance = FinanceProduct::find()->where(['id' => $uid[1]])->one();
        if ($finance['progress_status'] == 3) {
            exit(json_encode(0));
        } else {
            $test = Apply::find()->where(['uid' => $uid[0], 'id' => $uid[2]])->one();
            $test->app_id = 1;
            $finance->progress_status = 3;
            $finance->modify_time = time();
            $finance->save();
            if ($test->save()) {
                exit(json_encode(1));
            };
        }
    }

    /**
     *Displays 接收申请融资查询
     */
    public function actionOrder_apply()
    {
        $this->title = "清道夫债管家";
        $request = Yii::$app->request;
        $id = $request->get('id');
        $finance = FinanceProduct::find()
            ->where(['id' => $id])
            ->asArray()
            ->all();
        $type = new FinanceProduct();
        $type_data = $type::$mortgagecategory;
        $date = $type::$ratedatecategory;
        return $this->render('order_apply', ['finance' => $finance, 'type' => $type_data, 'date' => $date]);

    }

    /**
     *Displays 接收申请清收\诉讼查询
     */
    public function actionOrder_coll()
    {
        $this->title = "清道夫债管家";
        $request = Yii::$app->request;
        $id = $request->get('id');
        $credi = CreditorProduct::find()
            ->where(['id' => $id])
            ->asArray()
            ->all();
        $type = new CreditorProduct();
        $reimbursement = $type::$repaymethod;
        $guarantee = $type::$guaranteemethod;
        return $this->render('order_collection', [
            'credi' => $credi,
            'reimbursement' => $reimbursement,
            'guarantee' => $guarantee,
        ]);
    }

    /**
     * Displays 收藏申请数据查询
     *
     * @return mixed
     */
    public function actionSave_financing()
    {
        $this->title = "清道夫债管家";
        $request = Yii::$app->request;
        $id = $request->get('id');
        $finance = FinanceProduct::find()
            ->where(['id' => $id])
            ->asArray()
            ->all();
        $type = new FinanceProduct();
        $type_data = $type::$mortgagecategory;
        $date = $type::$ratedatecategory;
        return $this->render('save_financing', ['finance' => $finance, 'type' => $type_data, 'date' => $date]);

    }

    /**
     * Displays 收藏清收、诉讼申请数据查询
     *
     * @return mixed
     */
    public function actionSave_collection()
    {
        $this->title = "清道夫债管家";
        $request = Yii::$app->request;
        $id = $request->get('id');
        $credi = CreditorProduct::find()
            ->where(['id' => $id])
            ->asArray()
            ->all();
        $type = new CreditorProduct();
        $reimbursement = $type::$repaymethod;
        $guarantee = $type::$guaranteemethod;
        return $this->render('save_coll', ['credi' => $credi, 'reimbursement' => $reimbursement,
            'guarantee' => $guarantee]);
    }

    /**
     * Displays 收藏融资申请数据保存
     *
     * @return mixed
     */
    public function actionSave_app()
    {
        $this->title = "清道夫债管家";
        $id = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('cate');
        $user = User::findOne(['id' => \Yii::$app->user->getId()]);
        $uid = $user->id;
        $finance = FinanceProduct::find()->where(['id' => $id])->one();
        $collection = Apply::find()->where(['product_id' => $id, 'category' => $category, 'uid' => $uid])->one();
        if (($collection['product_id'] == $id) && ($collection['uid'] == $uid) && ($collection['app_id'] == 1)) {
            exit(json_encode(1));
        } else {
            $finance->modify_time = time();
            $collection->app_id = 0;
            $finance->save();
            if ($collection->save()) {
                exit(json_encode(2));
            } else {
                exit(json_encode(3));
            }
        }
    }

    /**
     * Displays 收藏清收、诉讼申请数据保存
     *
     * @return mixed
     */
    public function actionSave_coll()
    {
        $this->title = "清道夫债管家";
        $id = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('cate');
        $user = User::findOne(['id' => \Yii::$app->user->getId()]);
        $uid = $user->id;
        $credition = CreditorProduct::find()->where(['id' => $id])->one();
        $collection = Apply::find()->where(['product_id' => $id, 'category' => $category, 'uid' => $uid])->one();
        if (($collection['product_id'] == $id) && ($collection['uid'] == $uid) && ($collection['app_id'] == 1)) {
            exit(json_encode(1));
        } else {
            $credition->modify_time = time();
            $collection->app_id = 0;
            $credition->save();
            if ($collection->save()) {
                exit(json_encode(2));
            } else {
                exit(json_encode(3));
            }
        }
    }



    public function actionAgreements(){
        $this->title = "清道夫债管家";
        return $this->render('agreement_progress');
    }

    public function actionAddevaluate(){
        if( Yii::$app->request->post()){
            $evaluate = new \common\models\Evaluate();
            $evaluate->load(Yii::$app->request->post(),'');
            if(Yii::$app->request->post('category') == 1){
                $product = \common\models\FinanceProduct::findOne(['category'=>Yii::$app->request->post('category'),'id'=>Yii::$app->request->post('product_id')]);
            }elseif(in_array(Yii::$app->request->post('category'),[2,3])){
                $product = \common\models\CreditorProduct::findOne(['category'=>Yii::$app->request->post('category'),'id'=>Yii::$app->request->post('product_id')]);

            }else{
                echo 2;die;
            }

            $apply = \common\models\Apply::findOne(['category'=>Yii::$app->request->post('category'),'product_id'=>Yii::$app->request->post('product_id'),'app_id'=>1]);

            if($product->uid == Yii::$app->user->getId()){
                $uid = Yii::$app->user->getId();
                $bid = $apply->uid;
            }else{
                $uid =Yii::$app->user->getId();
                $bid =  $product->uid;
            }
            $evaluate->uid = $uid;
            $evaluate->buid = $bid;
            $evaluate->picture = Yii::$app->imgload->UploadPhoto($evaluate, 'uploads/','picture');
            $evaluate->serviceattitude = Yii::$app->request->post('serviceattitude');
            $evaluate->professionalknowledge =Yii::$app->request->post('professionalknowledge');
            $evaluate->workefficiency  =Yii::$app->request->post('workefficiency');
            $evaluate->content = Yii::$app->request->post('content');
            $evaluate->save();
            switch($product->category){
                case 1:\frontend\services\Func::addMessage(10,\yii\helpers\Url::to(['/apply/closefinancing','id'=>$product->id]),['{1}'=>$product->code],$evaluate->buid);break;
                case 2:\frontend\services\Func::addMessage(11,\yii\helpers\Url::to(['/apply/closecreditor','id'=>$product->id]),['{1}'=>$product->code],$evaluate->buid);break;
                case 3:\frontend\services\Func::addMessage(12,\yii\helpers\Url::to(['/apply/closecreditor','id'=>$product->id]),['{1}'=>$product->code],$evaluate->buid);break;
                default:break;
            }
            $this->redirect(\yii\helpers\Url::to("/list/chakan").'?category='.Yii::$app->request->post('category').'&id='.Yii::$app->request->post('product_id'));
        }
    }

    //手机验证码
    public function actionSms()
    {
        if (\Yii::$app->request->isAjax) {
            $mobile = \Yii::$app->request->post('mobile');
            if (!Func::isMobile($mobile)) {
                echo json_encode(['code' => '1001', 'msg' => '手机号格式错误']);
                die;
            }
            if (!YII_DEBUG && Yii::$app->smser->sendValidateCode($mobile)) {
                echo json_encode([
                    'code' => '0000',
                    'msg' => '发送验证码成功',
                ]);
                die;
            } else {
                echo json_encode(['code' => '1002', 'msg' => '发送验证码失败']);
                die;
            }
        } else {

        }
    }
    public function actionSecurity($type=''){
        $this->title = '清道夫债管家';
        return $this->render('/user/modification',["type"=>$type]);
    }
    //修改密码
    public function actionModification()
    {
            $this->title = '清道夫债管家';
            if (Yii::$app->request->post()) {
                $userpassword = User::findOne(['id' => \Yii::$app->user->getId()]);
                $uu = \common\models\User::findByUsername($userpassword->mobile);
                if ($uu->validatePassword(\Yii::$app->request->post('Current'))) {
                    $userpassword->password_hash =  Yii::$app->security->generatePasswordHash(\Yii::$app->request->post('passwords'));
                    if($userpassword->save()) exit(json_encode(2));
                }else{
                    exit(json_encode(1));
                }
            }
    }

    //修改手机号码
    public function actionModifymobile(){
        $this->title = '清道夫债管家';
        if(Yii::$app->request->post()){
           $mobile = User::findOne(['id' => \Yii::$app->user->getId()]);
            $sms = new Sms();
            if(isset($mobile['mobile'])&&$mobile['mobile'] != \Yii::$app->request->post('mobile')){
                exit(json_encode(1));
            }else if(User::findOne(['mobile'=>\Yii::$app->request->post('newphone')])){
                exit(json_encode(2));
            }else if(!$sms->isVildateCode(\Yii::$app->request->post('verifyCode'),\Yii::$app->request->post('mobile'))){
                exit(json_encode(3));
            }else{
                $mobile->mobile = \Yii::$app->request->post('newphone');
                if($mobile->save()){
                    exit(json_encode(4));
                }
            }

        }
    }

}

