<?php
namespace frontend\controllers;

use common\models\DisposingProcess;
use common\models\FinanceProduct;
use Yii;
use common\models\LoginForm;
use common\models\CreditorProduct;
use common\models\Apply;
use common\models\Certification;
use frontend\services\Func;
use app\models\user;
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
class ApplyController extends FrontController
{
    public $layout = 'user';
    public $enableCsrfValidation = false;
    public function actionFinancing($id)
    {
        $this->title = "清道夫债管家";
        $dt = \common\models\FinanceProduct::findOne(['id'=>$id]);
        if($dt->progress_status == 1){
            return $this->render('financing', [
                'dt' => $dt,
            ]);
        }else{
            $this->redirect(\yii\helpers\Url::to(['/']));
        }
    }

    /**
     *Displays 申请清收\诉讼查询
     */
    public function actionCreditor($id)
    {
        $this->title = "清道夫债管家";
        $dt = \common\models\CreditorProduct::findOne(['id'=>$id]);
        if($dt->progress_status == 1){
            return $this->render('creditor', [
                'dt' => $dt,
            ]);
        }else{
            $this->redirect(\yii\helpers\Url::to(['/']));
        }
    }


    /**
     *Displays 填写进度数据查询
     */
    public function actionTreatmentfinancing($id){
        $this->title = "清道夫债管家";
        $dt = \common\models\FinanceProduct::findOne(['id'=>$id]);
        if($dt->progress_status == 2){
            return $this->render('treatmentfinancing', [
                'dt' => $dt,
            ]);
        }else{
            $this->redirect(\yii\helpers\Url::to(['/']));
        }
    }

    public function actionTreatmentcreditor($id)
    {
        $this->title = "清道夫债管家";
        $dt = \common\models\CreditorProduct::findOne(['id'=>$id]);
        if($dt->progress_status == 2){
            return $this->render('treatmentcreditor', [
                'dt' => $dt,
            ]);
        }else{
            $this->redirect(\yii\helpers\Url::to(['/']));
        }
    }

    /**
     *Displays 填写进度数据查询
     */
    public function actionClosefinancing($id){
        $this->title = "清道夫债管家";
        $dt = \common\models\FinanceProduct::findOne(['id'=>$id]);
        if($dt->progress_status == 3||$dt->progress_status == 4){
            return $this->render('closefinancing', [
                'dt' => $dt,
            ]);
        }else{
            $this->redirect(\yii\helpers\Url::to(['/']));
        }
    }

    public function actionClosecreditor($id)
    {
        $this->title = "清道夫债管家";
        $dt = \common\models\CreditorProduct::findOne(['id'=>$id]);
        if($dt->progress_status == 3){
            return $this->render('closecreditor', [
                'dt' => $dt,
            ]);
        }else{
            $this->redirect(\yii\helpers\Url::to(['/']));
        }
    }

    public function actionDeterminefinancing()
    {
        $this->title = "清道夫债管家";
        $apply_id = Yii::$app->request->post('idlist');
        $apply =  \common\models\Apply::findOne(['id'=>$apply_id]);
        $mobile = \common\models\User::findOne(['id' => $apply['uid']]);
        if($apply['category'] == 1){
            $code = \common\models\FinanceProduct::findOne(['id' => $apply['product_id']]);
        }else{
            $code = \common\models\CreditorProduct::findOne(['id' => $apply['product_id']]);
        }
        Yii::$app->smser->sendMsgByMobile($mobile['mobile'],'申请成功 【直向资产】尊敬的用户：订单号:'.$code["code"].'申请成功，请您尽快处理，以避免超时。详细信息请登录清道夫债管家账户系统查看。');
        $uid = Yii::$app->user->getId();
        if($apply->category == 1){
            $finance = FinanceProduct::find()->where(['id' => $apply->product_id])->one();
        }elseif(in_array($apply->category,[2,3])){
            $finance = CreditorProduct::find()->where(['id' => $apply->product_id])->one();
        }else{
            echo 2;die;
        }

        if ($finance['progress_status'] != 1) {
            echo 2;die;
        } elseif($finance['progress_status'] == 1) {
            $test = Apply::find()->where(['id' => $apply_id])->one();
            $test->app_id = 1;
            $test->agree_time = time();
            $finance->progress_status = 2;
            $finance->modify_time = time();
            $finance->save();
            if ($test->save()) {
                switch($apply->category){
                    case 1:\frontend\services\Func::addMessage(7,\yii\helpers\Url::to(['/order/chakan','id'=>$finance->id,'category'=>$finance->category]),['{1}'=>$finance->code],$apply->uid);break;
                    case 2:\frontend\services\Func::addMessage(8, \yii\helpers\Url::to(['/order/chakan','id'=>$finance->id,'category'=>$finance->category]),['{1}'=>$finance->code],$apply->uid);break;
                    case 3:\frontend\services\Func::addMessage(9, \yii\helpers\Url::to(['/order/chakan','id'=>$finance->id,'category'=>$finance->category]),['{1}'=>$finance->code],$apply->uid);break;
                    default:break;
                }
                echo 1;die;
            };
        }
    }



    public function actionIsagree(){
        if(Yii::$app->request->post()){
            $id = Yii::$app->request->post('id');
            $is_agree = Yii::$app->request->post('is_agree');
            $delay =  \common\models\DelayApply::findOne(['id'=>$id,'is_agree'=>0]);
            if($delay->category == 1){
                $product = \common\models\FinanceProduct::findOne(['id'=>$delay->product_id,'category'=>$delay->category]);
            }elseif(in_array($delay->category,[2,3])){
                $product = \common\models\CreditorProduct::findOne(['id'=>$delay->product_id,'category'=>$delay->category]);
            }else{
                echo '2';die;
            }

            if($product->uid == Yii::$app->user->getId()&&$delay){
                $product->term += $delay->delay_days;
                $product->save();
                $delay->is_agree = $is_agree;
                $delay->save();
                echo '1';die;
            }else{
                echo '2';die;
            }
        }
    }
    public function actionCloseproduct(){
        $category = Yii::$app->request->post('category');
        $id = Yii::$app->request->post('id');
        $status = Yii::$app->request->post('status');
        $uid = Yii::$app->user->getId();
        if($category == 1){
            $res = \common\models\FinanceProduct::findOne(['category'=>$category,'id'=>$id]);
        }elseif(in_array($category,[2,3])){
            $res = \common\models\CreditorProduct::findOne(['category'=>$category,'id'=>$id]);
        }
        $code = \common\models\Apply::findOne(['product_id'=>$id,'category'=>$category,'app_id'=>1]);
        $mobile = isset($code['uid'])?\common\models\User::findOne(['id'=>$code['uid']]):null;
        if(isset($mobile)&&$mobile->id == Yii::$app->user->getId() && $status == 4) {
            $mobiles = \common\models\User::findOne(['id'=>$res['uid']]);
			if($category == 2){
				\frontend\services\Func::addMessage(24,\yii\helpers\Url::to(['/list/chakan','id' => $id,'category' =>$category]),['{1}'=>$res->code],$mobiles['id']);
			}else{
				\frontend\services\Func::addMessage(22,\yii\helpers\Url::to(['/list/chakan','id' => $id,'category' =>$category]),['{1}'=>$res->code],$mobiles['id']);
			}
			
            Yii::$app->smser->sendMsgByMobile($mobiles['mobile'],'接单方结案发起 【直向资产】尊敬的用户：订单号:'.$res['code'] . '接单方发起结案，请您尽快确认。详细信息请登录清道夫债管家账户系统查看。');
        }else if(isset($mobile)&&$status == 4){
			if($category == 2){
				\frontend\services\Func::addMessage(25,\yii\helpers\Url::to(['/list/chakan','id' => $id,'category' =>$category]),['{1}'=>$res->code],$mobile['id']);
			}else{
				\frontend\services\Func::addMessage(23,\yii\helpers\Url::to(['/list/chakan','id' => $id,'category' =>$category]),['{1}'=>$res->code],$mobile['id']);
			}
            Yii::$app->smser->sendMsgByMobile($mobile['mobile'],'发布方结案发起 【直向资产】尊敬的用户：订单号:'.$res['code'].'融资方发起结案，请您尽快确认。详细信息请登录清道夫债管家账户系统查看。');
        }
        if($status == 3){
            if($res['uid'] == $uid) {
                $res->progress_status = 3;
                $res->applyclosefrom = $uid;
                if($res->save()){
					if($category == 2){
							\frontend\services\Func::addMessage(30,\yii\helpers\Url::to(['/list/chakan','id' => $id,'category' =>$category]),['{1}'=>$res->code]);
						}else{
							\frontend\services\Func::addMessage(29,\yii\helpers\Url::to(['/list/chakan','id' => $id,'category' =>$category]),['{1}'=>$res->code]);
						}
					};
                echo 1;
                die;
            }
        }else {
            if ($res->id && !$res->applyclose && !$res->applyclosefrom) {
                $res->applyclose = 4;
                $res->applyclosefrom = $uid;
                $res->save();
                echo 1;
                die;
            } else {
                echo '2';
                die;
            }
        }
    }

    public function actionCloseproductagree(){
        $category = Yii::$app->request->post('category');
        $id = Yii::$app->request->post('id');
        $status = Yii::$app->request->post('status');
        $uid = Yii::$app->user->getId();
        if($category == 1){
            $res = \common\models\FinanceProduct::findOne(['category'=>$category,'id'=>$id]);
        }elseif(in_array($category,[2,3])){
            $res = \common\models\CreditorProduct::findOne(['category'=>$category,'id'=>$id]);
        }

        if($res->id&&$res->applyclose){
            $res->progress_status = $res->applyclose;
            $res->save();
            echo 1;die;
        }else{
            echo '2';die;
        }
    }


}

