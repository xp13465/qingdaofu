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
    public function init(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }

        $this->layout = "user";
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // your custom code here
            $url = strtolower(Yii::$app->controller->id.Yii::$app->controller->action->id);
            $user = \common\models\User::findOne(['id'=>\Yii::$app->user->getId()]);
            if($user->pid){
                $uid = $user->pid;
            }else{
                $uid = \Yii::$app->user->getId();
            }
            if($url != 'userindex' && !Certification::findOne(['uid'=>$uid,'state'=>1])){
                $this->redirect(\yii\helpers\Url::to("/user/index"));
            }
            return true;  // or false if needed
        } else {
            return false;
        }
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ],
        ];
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
            $model->fundstime = strtotime(Yii::$app->request->post('fundstime'));
            $model->create_time = time();
            $model->category = 1;
            $model->code = Func::createCatCode(1);
            $model->modify_time = time();
            $model->uid = \Yii::$app->user->getId();
            $model->is_del = 0;
            $model->save();

            if($model->progress_status  == 1)\frontend\services\Func::addMessage(1,\yii\helpers\Url::to(['/apply/financing','id'=>$model->id]),['{1}'=>$model->code]);

            if($model->progress_status  == 0)$this->redirect(\yii\helpers\Url::to(['/publish/editfinancing','id'=>$model->id]));
            else $this->redirect(\yii\helpers\Url::to(['/list/apply']));
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
            $this->redirect(\yii\helpers\Url::to(['/list/apply']));
        }
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post(), '');
            $progress_status = Yii::$app->request->post('progress_status');
            if($progress_status>1){
                $this->redirect(\yii\helpers\Url::to(['/list/apply']));
            }
            $model->fundstime = strtotime(Yii::$app->request->post('fundstime'));
            $model->create_time = time();
            $model->category = 1;
            //$model->code = Func::createCatCode(1);
           // $model->modify_time = time();
            $model->uid = \Yii::$app->user->getId();

            $model->is_del = 0;
            $model->save();
            if($model->progress_status  == 1)\frontend\services\Func::addMessage(1,\yii\helpers\Url::to(['/apply/financing','id'=>$model->id]),['{1}'=>$model->code]);

            if($model->progress_status  == 0)$this->redirect(\yii\helpers\Url::to(['/publish/editfinancing','id'=>$id]));
            else $this->redirect(\yii\helpers\Url::to(['/list/apply']));
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
                $this->redirect(\yii\helpers\Url::to(['/list/apply']));
            }
            $model->fundstime = strtotime(Yii::$app->request->post('fundstime'));
            $model->create_time = time();
            $model->category = 1;
            //$model->code = Func::createCatCode(1);
           // $model->modify_time = time();
            $model->uid = \Yii::$app->user->getId();

            $model->is_del = 0;
            $model->save();
            if($model->progress_status  == 1)\frontend\services\Func::addMessage(1,\yii\helpers\Url::to(['/apply/financing','id'=>$model->id]),['{1}'=>$model->code]);

            if($model->progress_status  == 0)$this->redirect(\yii\helpers\Url::to(['/publish/editfinancing','id'=>$id]));
            else $this->redirect(\yii\helpers\Url::to(['/list/apply']));
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
            if($model->progress_status  == 1)\frontend\services\Func::addMessage(2,\yii\helpers\Url::to(['/apply/creditor','id'=>$model->id]),['{1}'=>$model->code]);
            if($model->progress_status  == 0)$this->redirect(\yii\helpers\Url::to(['/publish/editcollection','id'=>$model->id]));
            else $this->redirect(\yii\helpers\Url::to(['/list/apply']));
        }

        return $this->render('collection', ['model' => $model]);
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
        if($model->progress_status > 1){
            $this->redirect(\yii\helpers\Url::to(['/list/apply']));
        }
        if (Yii::$app->request->post()) {
            $this->setData(2, $model);
            if($model->progress_status  == 1)\frontend\services\Func::addMessage(2,\yii\helpers\Url::to(['/apply/creditor','id'=>$model->id]),['{1}'=>$model->code]);
            if($model->progress_status  == 0)$this->redirect(\yii\helpers\Url::to(['/publish/editcollection','id'=>$id]));
            else $this->redirect(\yii\helpers\Url::to(['/list/apply']));
        }
        return $this->render('collection', ['model' => $model]);
    }


    public function actionAftercreditor($id)
    {
        $this->title = "清道夫债管家";
        $model = \common\models\CreditorProduct::findOne(['id' => $id]);
        if (Yii::$app->request->post()) {
            $this->setData(2, $model);
            if($model->progress_status  == 1)\frontend\services\Func::addMessage(2,\yii\helpers\Url::to(['/apply/creditor','id'=>$model->id]),['{1}'=>$model->code]);
            if($model->progress_status  == 0)$this->redirect(\yii\helpers\Url::to(['/publish/editcollection','id'=>$id]));
            else $this->redirect(\yii\helpers\Url::to(['/list/apply']));
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
            $this->redirect(\yii\helpers\Url::to(['/list/apply']));
        }
        $model->guaranteemethod = serialize(array_merge(is_array(Yii::$app->request->post('guaranteemethod')) ? Yii::$app->request->post('guaranteemethod') : [], ['other' => Yii::$app->request->post('other')]));
        $model->judicialstatusA = serialize(Yii::$app->request->post('judicialstatusA'));
        $model->agencycommissiontype = Yii::$app->request->post('agencycommissionf')?1:2;
        $model->agencycommission = Yii::$app->request->post('agencycommissionf')? Yii::$app->request->post('agencycommissionf'): Yii::$app->request->post('agencycommissions');

        $imgnotarization_path = Yii::$app->imgload->UploadPhoto($model, 'uploads/', 'imgnotarization');
        $imgcontract_path = Yii::$app->imgload->UploadPhoto($model, 'uploads/', 'imgcontract');
        $imgcreditor_path = Yii::$app->imgload->UploadPhoto($model, 'uploads/', 'imgcreditor');
        $imgpick_path = Yii::$app->imgload->UploadPhoto($model, 'uploads/', 'imgpick');
        $imgshouju_path = Yii::$app->imgload->UploadPhoto($model, 'uploads/', 'imgshouju');
        $imgbenjin_path = Yii::$app->imgload->UploadPhoto($model, 'uploads/', 'imgbenjin');
        $creditorcardimage_path = Yii::$app->imgload->UploadPhoto($model, 'uploads/', 'creditorcardimage[0]');
        $borrowingcardimage_path = Yii::$app->imgload->UploadPhoto($model, 'uploads/', 'borrowingcardimage[0]');

        $arrCre = unserialize($model->creditorfile);

        $model->creditorfile = serialize([
            'imgnotarization' => $imgnotarization_path ? $imgnotarization_path : (isset($arrCre['imgnotarization'])?$arrCre['imgnotarization']:''),
            'imgcontract' => $imgcontract_path ? $imgcontract_path : (isset($arrCre['imgcontract'])?$arrCre['imgcontract']:''),
            'imgcreditor' => $imgcreditor_path ? $imgcreditor_path : (isset($arrCre['imgcreditor'])?$arrCre['imgcreditor']:''),
            'imgpick' => $imgpick_path ? $imgpick_path : (isset($arrCre['imgpick'])?$arrCre['imgpick']:''),
            'imgshouju' => $imgshouju_path ? $imgshouju_path : (isset($arrCre['imgshouju'])?$arrCre['imgshouju']:''),
            'imgbenjin' => $imgbenjin_path ? $imgbenjin_path : (isset($arrCre['imgbenjin'])?$arrCre['imgbenjin']:''),
            'creditorcardimage' => $creditorcardimage_path ? $creditorcardimage_path : (isset($arrCre['creditorcardimage'])?$arrCre['creditorcardimage']:''),
            'borrowingcardimage'=> $borrowingcardimage_path ? $borrowingcardimage_path : (isset($arrCre['borrowingcardimage'])?$arrCre['borrowingcardimage']:'') ,
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
                $creditor[$k]['borrowingcardimage'] = Yii::$app->request->post('borrowingcardimage')[$k];

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
            if($model->progress_status  == 1)\frontend\services\Func::addMessage(3,\yii\helpers\Url::to(['/apply/creditor','id'=>$model->id]),['{1}'=>$model->code]);
            if($model->progress_status  == 0)$this->redirect(\yii\helpers\Url::to(['/publish/editlitigation','id'=>$model->id]));
            else $this->redirect(\yii\helpers\Url::to(['/list/apply']));
        }
        return $this->render('litigation', ['model' => $model]);
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
            $this->redirect(\yii\helpers\Url::to(['/list/apply']));
        }
        if (Yii::$app->request->post()) {
            $this->setData(3, $model);
            if($model->progress_status  == 1)\frontend\services\Func::addMessage(3,\yii\helpers\Url::to(['/apply/creditor','id'=>$model->id]),['{1}'=>$model->code]);
            if($model->progress_status  == 0)$this->redirect(\yii\helpers\Url::to(['/publish/editlitigation','id'=>$id]));
            else $this->redirect(\yii\helpers\Url::to(['/list/apply']));
        }
        return $this->render('litigation', ['model' => $model]);
    }

}

