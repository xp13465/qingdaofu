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
class ProtocolController extends FrontController
{
    public $enableCsrfValidation = false;
    public function init(){
        $this->layout = 'protocol';
    }
	
	 //协议
    public function actionIndex($productid,$type="view"){
		$productid = Yii::$app->request->get('productid','');
        $token    = Yii::$app->session->get('user_token');
        $strs       = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/agreement'), ['token' => $token,'productid'=>$productid]);
        $arrs       = yii\helpers\Json::decode($strs);
		
		if($arrs['code'] == '0000'){
            $data  = $arrs['result']['data'];
			if ($data['create_by'] == Yii::$app->user->getId()) {
				//居间协议（清收委托人）
				$view="jujian";
			} else {
				//居间协议 （清收人）
				$view="jujian01";
			}
        }else{
			$this->notFound();
        }
		 
		
		
		if($type=="pdf"){
			$mpdf=new \mPDF('zh-CN','A4','','ccourierB','15','15','24','24','5','5');
			$mpdf->useAdobeCJK = true;
			$header='<table width="100%" style="">
			<tr>
			<td width="20%"></td>
			<td width="60%" align="center" style="font-size:px;color:#AAA">页眉</td>
			<td width="20%" style="text-align: right;"></td>
			</tr> 	
			</table>';
			//设置PDF页脚内容
			$footer='<table width="100%" style=" vertical-align: bottom; font-family:黑体; font-size:12pt; color: #;"><tr style="height:px"></tr><tr>
			<td width="20%"></td>
			<td width="60%" align="center" style="font-size:10px;">{PAGENO}/{nb}</td>
			<td width="20%" style="text-align: left;"></td>
			</tr></table>';
			//添加页眉和页脚到pdf中
			$mpdf->SetHTMLHeader($this->renderPartial('pdf_header'));
			$mpdf->SetHTMLFooter($this->renderPartial('pdf_footer'));
			//$mpdf->AddPage();
			
			$mpdf->WriteHTML($this->renderPartial($view,['data'=>$data]));
			// $mpdf->WriteHTML($this->renderPartial('myview'));
			//$mpdf->AddPage();
			$mpdf->Output('MyPDF.pdf', "I");//I查看D下载
			exit;
		}else{
			return $this->render($view,['data'=>$data]);
		}
		
		
		
		
		
		
		
        if($arrs['code'] == '0000'){
            $data  = $arrs['result']['data'];
			
			if ($data['create_by'] == Yii::$app->user->getId()) {
				//居间协议（清收委托人）
				echo $this->render('jujian',['data'=>$data]);
			} else {
				//居间协议 （清收人）
				echo $this->render('jujian01',['data'=>$data]);
			}
            // return $this->render('mediacys',['data'=>$data]);
        }else{
            // return $this->render('mediacys');
        }
    }
	
    //居间协议（清收公司）
    public function actionMediacycollection(){
        $category=Yii::$app->request->get('category');
        $id=Yii::$app->request->get('id');

        if(!$category||!$id){
            return  $this->render('mediacycollection');
        }else{
            if(Yii::$app->user->isGuest){
                return $this->redirect('/');
            }
            $certification = $this->getCertification();
            $desc = $this->getDesc($category,$id,$this->getApplyUserId($category,$id,Yii::$app->user->getId()));
            $agree_time = $this->getAgreeTime($category,$id);
            return  $this->render('mediacycollection',array_merge($certification,['agree_time'=>$agree_time,'desc'=>$desc]));
        }

    }

    //居间协议（清收委托人）
    public function actionMediacyentrust(){
        $category=Yii::$app->request->get('category');
        $id=Yii::$app->request->get('id');
        if(!$category||!$id){
            return  $this->render('mediacyentrust');
        }else {
            if (Yii::$app->user->isGuest) {
                return $this->redirect('/');
            }
            $certification = $this->getCertification();
             $desc = $this->getDesc($category,$id,Yii::$app->user->getId());
            $agree_time = $this->getAgreeTime($category,$id);
            return  $this->render('mediacyentrust',array_merge($certification,['agree_time'=>$agree_time,'desc'=>$desc]));
        }
    }

    //居间协议（融资人）
    public function actionMediacyfinancing(){
        $category=Yii::$app->request->get('category');
        $id=Yii::$app->request->get('id');
        if(!$category||!$id){
            return  $this->render('mediacyfinancing');
        }else {
            if (Yii::$app->user->isGuest) {
                return $this->redirect('/');
            }
            $certification = $this->getCertification();
               $desc = $this->getDesc($category,$id,Yii::$app->user->getId());
            $agree_time = $this->getAgreeTime($category,$id);
            return  $this->render('mediacyfinancing',array_merge($certification,['agree_time'=>$agree_time,'desc'=>$desc]));
        }
    }

    //居间协议（投资人）
    public function actionMediacyinvestment(){

        $category=Yii::$app->request->get('category');
        $id=Yii::$app->request->get('id');
        if(!$category||!$id){
            return  $this->render('mediacyinvestment');
        }else {
            if(Yii::$app->user->isGuest){
                return $this->redirect('/');
            }
            $certification = $this->getCertification();
            $desc = $this->getDesc($category,$id,$this->getApplyUserId($category,$id,Yii::$app->user->getId()));
            $agree_time = $this->getAgreeTime($category,$id);
            return  $this->render('mediacyinvestment',array_merge($certification,['agree_time'=>$agree_time,'desc'=>$desc]));
        }
    }

    //居间协议（法律服务-律师事务所）
    public function actionMediacylawer(){
        $category=Yii::$app->request->get('category');
        $id=Yii::$app->request->get('id');
        if(!$category||!$id){
            return  $this->render('mediacylawer');
        }else {
            if (Yii::$app->user->isGuest) {
                return $this->redirect('/');
            }
            $certification = $this->getCertification();
            $desc = $this->getDesc($category,$id,$this->getApplyUserId($category,$id,Yii::$app->user->getId()));
            $agree_time = $this->getAgreeTime($category,$id);
            return  $this->render('mediacylawer',array_merge($certification,['agree_time'=>$agree_time,'desc'=>$desc]));
        }
    }

    //居间协议（法律服务-委托人）
    public function actionMediacylawentrust(){
        $category=Yii::$app->request->get('category');
        $id=Yii::$app->request->get('id');
        if(!$category||!$id){
            return  $this->render('mediacylawentrust');
        }else {
            if (Yii::$app->user->isGuest) {
                return $this->redirect('/');
            }
            $certification = $this->getCertification();
             $desc = $this->getDesc($category,$id,Yii::$app->user->getId());
            $agree_time = $this->getAgreeTime($category,$id);
            return  $this->render('mediacylawentrust',array_merge($certification,['agree_time'=>$agree_time,'desc'=>$desc]));
        }
    }

    public function actionRegisterprotocal(){
        return  $this->render('registerprotocal');
    }

    private function getDesc($category,$id,$uid){
        if(!$uid)return   $this->redirect('/');;
        $desc = '';
        if($category == 1){
            $desc = \common\models\FinanceProduct::findOne(['category'=>$category,'id'=>$id,'uid'=>$uid]);
        }elseif(in_array($category,[2,3])){
            $desc = \common\models\CreditorProduct::findOne(['category'=>$category,'id'=>$id,'uid'=>$uid]);
        }
        if(!$desc||$desc->progress_status < 1)return   $this->redirect('/');
        return $desc;
    }

    private function getApplyUserId($category,$id,$uid){
        $desca = \common\models\Apply::findOne(['category'=>$category,'product_id'=>$id,'uid'=>$uid,'app_id'=>1]);
        if(!$desca)return  $this->redirect('/');
        if($desca->category == 1){
            $desc = \common\models\FinanceProduct::findOne(['category'=>$desca->category,'id'=>$desca->product_id]);
        }elseif(in_array($desca->category,[2,3])){
            $desc = \common\models\CreditorProduct::findOne(['category'=>$desca->category,'id'=>$desca->product_id]);
        }
        if(!$desc) return $this->redirect('/');

        return $desc->uid;
    }

    private function getCertification(){
        $user = \common\models\User::findOne(['id'=>Yii::$app->user->getId()]);
        $certification = \common\models\Certification::findOne(['uid'=>Yii::$app->user->getId(),'state'=>1]);
        if($certification)return ['certification'=>$certification,'user'=>$user];

        $certification = \common\models\Certification::findOne(['uid'=>$user->pid,'state'=>1]);

        return ['certification'=>$certification,'user'=>$user];
    }

    private function getAgreeTime($category,$id){
        $desca = \common\models\Apply::findOne(['category'=>$category,'product_id'=>$id,'app_id'=>1]);

        if(isset($desca->agree_time))return $desca->agree_time;
        else return time();
    }

    //法律申明
    public function actionFalvdeclaration(){
        $this->title = '法律申明-清道夫债管家';
        $this->keywords = '法律申明';
        $this->description = '法律申明';
        return $this->render('/protocol/falvdeclaration');
    }

    public function actionMediacy()
    {
        $id = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
        if (is_numeric($id) && is_numeric($category)) {
            $data = Func::getProduct($category, $id);
            $certi = Func::getCertifications($data['uid']);
            $desca = \common\models\Apply::findOne(['category' => $category, 'product_id' => $id, 'app_id' => 1]);
            $user = \common\models\User::findOne(['id' => $data['uid']]);
            if ($data && $certi) {
                return $this->render('mediacy', ['certi' => $certi, 'data' => $data, 'desca' => isset($desca['agree_time'])?$desca['agree_time']:time(), 'user' => $user]);
            }
        }
    }

    //app 注册协议
    public function actionAgreements(){
        return $this->render('agreements');
    }

    //app常见问答
    public function actionQuestion(){
        return $this->render('question');
    }

    public function actionCarousel(){
        $type = Yii::$app->request->get('type');
        return $this->render('carousel',['type'=>$type]);
    }
}

