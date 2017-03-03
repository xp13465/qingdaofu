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
class PublishController extends FrontController
{
    public $enableCsrfValidation = false;
    public function init(){
        if (\Yii::$app->user->isGuest) {
            $this->redirect('/site/login/')->send();
			exit;
        }
       /* if(!Certification::findOne(['uid'=>\Yii::$app->user->getId(),'state'=>1])){
            $this->redirect(\yii\helpers\Url::to("/certification/index"));
        }*/
        $this->layout = "user";
    }

    public function actionPublish()
    {
        $this->title = "清道夫债管家";
        return $this->render('publish');
    }

    /**
     * Displays 融资.
     *
     * @return mixed
     */
    public function actionFinancing()
    {
        $this->title = "清道夫债管家";
        $model = new \common\models\FinanceProduct();
            if (Yii::$app->request->post()) {
                $model->load(Yii::$app->request->post(), '');
              //  $model->fundstime = strtotime(Yii::$app->request->post('fundstime'));
                $model->create_time = time();
                $model->category = 1;
                $model->code = Func::createCatCode(1);
                $model->modify_time = time();
                $model->uid = \Yii::$app->user->getId();
                $model->is_del = 0;
                $model->save();
                if ($model->progress_status == 1) \frontend\services\Func::addMessage(1, \yii\helpers\Url::to(['/list/chakan', 'id' => $model->id, 'category' => $model->category]), ['{1}' => $model->code]);
                if ($model->progress_status == 0) $this->redirect(\yii\helpers\Url::to(['/publish/editfinancing', 'id' => $model->id]));
                else $this->redirect(\yii\helpers\Url::to(['/list/chakan','id'=>$model->id,'category' => $model->category]));
        }
        return $this->render('financing', ['model' => $model]);
    }


    /**
     * Displays 融资.
     *
     * @return mixed
     */
    public function actionEditfinancing($id)
    {
        $this->title = "清道夫债管家";
        if (!$id) {
            die;
        }

        $model = \common\models\FinanceProduct::findOne(['id' => $id]);
        if($model->progress_status > 1){
            $this->redirect(\yii\helpers\Url::to(['/list/index']));
        }
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post(), '');
            $progress_status = Yii::$app->request->post('progress_status');
            if($progress_status>1){
                $this->redirect(\yii\helpers\Url::to(['/list/index']));
            }
           // $model->fundstime = strtotime(Yii::$app->request->post('fundstime'));
            $model->create_time = time();
            $model->category = 1;
            $model->uid = \Yii::$app->user->getId();
            $model->is_del = 0;
            $model->save();
            if($model->progress_status  == 1)\frontend\services\Func::addMessage(1,\yii\helpers\Url::to(['/list/chakan','id' => $model->id,'category' => $model->category]),['{1}'=>$model->code]);
            if($model->progress_status  == 0)$this->redirect(\yii\helpers\Url::to(['/publish/editfinancing','id'=>$id]));
            else $this->redirect(\yii\helpers\Url::to(['/list/chakan','id'=>$model->id,'category' => $model->category]));
        }
        return $this->render('financing', ['model' => $model]);
    }
    /**
     * Displays 融资.
     *
     * @return mixed
     */
    public function actionAfterfinancing($id)
    {
        $this->title = "清道夫债管家";
        if (!$id) {
            die;
        }

        $model = \common\models\FinanceProduct::findOne(['id' => $id]);

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post(), '');
            $progress_status = Yii::$app->request->post('progress_status');
            if($progress_status>1){
                $this->redirect(\yii\helpers\Url::to(['/list/index']));
            }
           // $model->fundstime = strtotime(Yii::$app->request->post('fundstime'));
            $model->create_time = time();
            $model->category = 1;
            //$model->code = Func::createCatCode(1);
           // $model->modify_time = time();
            $model->uid = \Yii::$app->user->getId();

            $model->is_del = 0;
            $model->save();
            if($model->progress_status  == 1)\frontend\services\Func::addMessage(1,\yii\helpers\Url::to(['/list/chakan', 'id' => $model->id, 'category' => $model->category]),['{1}'=>$model->code]);
            if($model->progress_status  == 0)$this->redirect(\yii\helpers\Url::to(['/publish/editfinancing','id'=>$id]));
            else $this->redirect(\yii\helpers\Url::to(['/list/chakan','id'=>$model->id,'category' => $model->category]));
        }
        return $this->render('afterfinancing', ['model' => $model]);
    }


    /**
     * Displays 清收.
     *
     * @return mixed
     */
    public function actionCollection()
    {
        $this->title = "清道夫债管家";
        $model = new \common\models\CreditorProduct();
        if (Yii::$app->request->post()) {
            $this->setData(2, $model);
            if($model->progress_status  == 1)\frontend\services\Func::addMessage(2,\yii\helpers\Url::to(['/list/chakan', 'id' => $model->id, 'category' => $model->category]),['{1}'=>$model->code]);
            if($model->progress_status  == 0)$this->redirect(\yii\helpers\Url::to(['/publish/editcollection','id'=>$model->id]));
            else $this->redirect(\yii\helpers\Url::to(['/list/chakan','id'=>$model->id,'category' => $model->category]));
        }

        return $this->render('collection', ['model' => $model,'coll'=>1]);
    }


    /**
     * Displays 清收.
     *
     * @return mixed
     */
    public function actionEditcollection($id)
    {
        $this->title = "清道夫债管家";
        $model = \common\models\CreditorProduct::findOne(['id' => $id]);
        //var_dump($model);die;
        if($model->progress_status > 1){
            $this->redirect(\yii\helpers\Url::to(['/list/index']));
        }
        if (Yii::$app->request->post()) {
            $this->setData(2, $model);
            if($model->progress_status  == 1)\frontend\services\Func::addMessage(2,\yii\helpers\Url::to(['/list/chakan', 'id' => $model->id, 'category' => $model->category]),['{1}'=>$model->code]);
            if($model->progress_status  == 0)$this->redirect(\yii\helpers\Url::to(['/publish/editcollection','id'=>$id]));
            else $this->redirect(\yii\helpers\Url::to(['/list/chakan','id'=>$model->id,'category' => $model->category]));
        }
        return $this->render('collection', ['model' => $model]);
    }


    public function actionAftercreditor($id,$category)
    {
        $this->title = "清道夫债管家";
        $model = \common\models\CreditorProduct::findOne(['id' => $id]);
        if (Yii::$app->request->post()) {
            $this->setData($category, $model);
            if($model->progress_status  == 1)\frontend\services\Func::addMessage($category,\yii\helpers\Url::to(['/list/chakan', 'id' => $model->id, 'category' => $model->category]),['{1}'=>$model->code]);
            if($model->progress_status  == 0)$this->redirect(\yii\helpers\Url::to(['/publish/editcollection','id'=>$id]));
            else $this->redirect(\yii\helpers\Url::to(['/list/chakan','id'=>$model->id,'category' => $model->category]));
        }
        if($model->category == 2){
            return $this->render('aftercollection', ['model' => $model]);
        }elseif($model->category == 3){
            return $this->render('afterlitigation', ['model' => $model]);
        }
    }

    private function setData($cat, $model)
    {
        $model->load(Yii::$app->request->post(), '');
        $progress_status = Yii::$app->request->post('progress_status');
        if($progress_status>1){
            $this->redirect(\yii\helpers\Url::to(['/list/index']));
        }
        $model->guaranteemethod = serialize(array_merge(is_array(Yii::$app->request->post('guaranteemethod')) ? Yii::$app->request->post('guaranteemethod') : [], ['other' => Yii::$app->request->post('other')]));
        //$model->judicialstatusA = serialize(Yii::$app->request->post('judicialstatusA'));
		// var_dump(Yii::$app->request->post('agencycommission'));exit;
		var_dump($cat);
		// var_dump($_POST);
// exit;
		
		if($cat==3){
			if(Yii::$app->request->post('agencycommission')){
				var_dump(Yii::$app->request->post('agencycommission'));exit;
				$model->agencycommissiontype = Yii::$app->request->post('agencycommission')?2:1;
				$model->agencycommission = Yii::$app->request->post('agencycommission');
			}else{
				$model->agencycommissiontype = Yii::$app->request->post('agencycommissionf')?1:2;
				$model->agencycommission = Yii::$app->request->post('agencycommissionf')? Yii::$app->request->post('agencycommissionf'): Yii::$app->request->post('agencycommissions');
			}
		}else {
			if(Yii::$app->request->post('agencycommission')){
				$model->agencycommissiontype = Yii::$app->request->post('agencycommission')?1:2;
				$model->agencycommission = Yii::$app->request->post('agencycommission');
			}else{
				$model->agencycommissiontype = Yii::$app->request->post('agencycommissionf')?2:1;
				$model->agencycommission = Yii::$app->request->post('agencycommissionf')? Yii::$app->request->post('agencycommissionf'): Yii::$app->request->post('agencycommissions');
			}
		}
        

        $imgnotarization_path = Yii::$app->request->post('imgnotarization');
        $imgcontract_path = Yii::$app->request->post('imgcontract');
        $imgcreditor_path = Yii::$app->request->post('imgcreditor');
        $imgpick_path = Yii::$app->request->post('imgpick');
        $imgshouju_path = Yii::$app->request->post('imgshouju');
        $imgbenjin_path = Yii::$app->request->post('imgbenjin');
        $arrCre = unserialize($model->creditorfile);

        $model->creditorfile = serialize([
            'imgnotarization' => $imgnotarization_path ,
            'imgcontract' => $imgcontract_path ,
           'imgcreditor' => $imgcreditor_path ,
            'imgpick' => $imgpick_path ,
            'imgshouju' => $imgshouju_path ,
            'imgbenjin' => $imgbenjin_path,
            ]);
        $creditor = [];
            foreach (Yii::$app->request->post('creditorname') as $k => $v) {
                if($v|| Yii::$app->request->post('creditormobile')[$k]|| Yii::$app->request->post('creditoraddress')[$k]|| Yii::$app->request->post('creditorcardcode')[$k]|| Yii::$app->request->post('creditorcardimage')[$k]){
                    $creditor[$k]['creditorname'] = $v;
                    $creditor[$k]['creditormobile'] = Yii::$app->request->post('creditormobile')[$k];
                    $creditor[$k]['creditoraddress'] = Yii::$app->request->post('creditoraddress')[$k];
                    $creditor[$k]['creditorcardcode'] = Yii::$app->request->post('creditorcardcode')[$k];
                    $creditor[$k]['creditorcardimage'] = Yii::$app->request->post('creditorcardimage')[$k];

                }else{
                    unset($v);
                    unset(Yii::$app->request->post('creditormobile')[$k]);
                    unset(Yii::$app->request->post('creditoraddress')[$k]);
                    unset(Yii::$app->request->post('creditorcardcode')[$k]);
                    unset(Yii::$app->request->post('creditorcardimage')[$k]);
                }
            }
            $model->creditorinfo = serialize($creditor);

        $borrowing = [];
        foreach (Yii::$app->request->post('borrowingname') as $k => $v) {
            if($v|| Yii::$app->request->post('borrowingmobile')[$k]|| Yii::$app->request->post('borrowingaddress')[$k]|| Yii::$app->request->post('borrowingcardcode')[$k]|| Yii::$app->request->post('borrowingcardimage')[$k]) {
                $borrowing[$k]['borrowingname'] = $v;
                $borrowing[$k]['borrowingmobile'] = Yii::$app->request->post('borrowingmobile')[$k];
                $borrowing[$k]['borrowingaddress'] = Yii::$app->request->post('borrowingaddress')[$k];
                $borrowing[$k]['borrowingcardcode'] = Yii::$app->request->post('borrowingcardcode')[$k];
                $borrowing[$k]['borrowingcardimage'] = Yii::$app->request->post('borrowingcardimage')[$k];

            }else {
                unset($v);
                unset(Yii::$app->request->post('borrowingmobile')[$k]);
                unset(Yii::$app->request->post('borrowingaddress')[$k]);
                unset(Yii::$app->request->post('borrowingcardcode')[$k]);
                unset(Yii::$app->request->post('borrowingcardimage')[$k]);
            }
        }

        $model->borrowinginfo = serialize($borrowing);

        $model->create_time = time();
        $model->category = $cat;
        if($model->isNewRecord){
            $model->code = Func::createCatCode(2);
        }
        $model->modify_time = time();
        $model->uid = \Yii::$app->user->getId();
        $model->progress_status = $progress_status;
        $model->is_del = 0;
        $model->validate();
        $model->save();
    }

    /**
     * Displays 诉讼.
     *
     * @return mixed
     */
    public function actionLitigation()
    {
        $this->title = "清道夫债管家";
        $model = new \common\models\CreditorProduct();
        if (Yii::$app->request->post()) {
                $this->setData(3, $model);
                if ($model->progress_status == 1) \frontend\services\Func::addMessage(3, \yii\helpers\Url::to(['/list/chakan', 'id' => $model->id, 'category' => $model->category]), ['{1}' => $model->code]);
                if ($model->progress_status == 0) $this->redirect(\yii\helpers\Url::to(['/publish/editlitigation', 'id' => $model->id]));
                else $this->redirect(\yii\helpers\Url::to(['/list/chakan','id'=>$model->id,'category' => $model->category]));
        }
        return $this->render('litigation', ['model' => $model,'liti'=>2]);
    }

    /**
     * Displays 诉讼.
     *
     * @return mixed
     */
    public function actionEditlitigation($id)
    {
        $this->title = "清道夫债管家";
        $model = \common\models\CreditorProduct::findOne(['id' => $id]);
        if($model->progress_status > 1){
            $this->redirect(\yii\helpers\Url::to(['/list/index']));
        }
        if (Yii::$app->request->post()) {
            $this->setData(3, $model);
            if($model->progress_status  == 1)\frontend\services\Func::addMessage(3,\yii\helpers\Url::to(['/list/chakan', 'id' => $model->id, 'category' => $model->category]),['{1}'=>$model->code]);
            if($model->progress_status  == 0)$this->redirect(\yii\helpers\Url::to(['/publish/editlitigation','id'=>$id]));
            else $this->redirect(\yii\helpers\Url::to(['/list/chakan','id'=>$model->id,'category' => $model->category]));
        }
        return $this->render('litigation', ['model' => $model]);
    }



    //发布成功
    public function actionRelease(){
        return $this->render('release');
    }
//个人信息查询
    public function actionInformation($id){
        $certification = \common\models\Certification::findOne(['id'=>$id]);
        $sql = "select * from zcb_evaluate where buid ={$certification['uid']} order by create_time desc";
        $query = \yii::$app->db->createCommand($sql)->query();
        $pagination = new Pagination(['defaultPageSize'=>5,'totalCount'=>$query->count()]);
        $sql = $sql . " limit ".$pagination->offset." , ".$pagination->limit;
        $evaluate = \Yii::$app->db->createCommand($sql)->query();
        return $this->render('information',['certification'=>$certification,'pagination'=>$pagination,'evaluate'=>$evaluate]);
    }

    public function actionUploadscheck(){
        return $this->renderPartial('uploadscheck');
    }
}

