<?php
namespace backend\controllers;


use Yii;
// use app\models\ServicesAgreement;
// use app\models\ServicesAgreement;
// use app\models\ServicesAgreement;
use app\models\ServicesAgreementSearch;
use app\models\ServicesInstrumentSearch;
use app\models\ServicesReservationSearch;
use backend\components\BackController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ServicesAgreementController implements the CRUD actions for ServicesAgreement model.
 */
class ServicesController extends BackController
{
	public $defaultAction  ="agreement-index";
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
	public function actionIndex(){
		
		$this->redirect("agreement-index");
	}
    /**
     * Lists all ServicesInstrument models.
     * @return mixed
     */
    public function actionInstrumentIndex()
    {
        $searchModel = new ServicesInstrumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('instrumentindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ServicesInstrument model.
     * @param integer $id
     * @return mixed
     */
    public function actionInstrumentView($id)
    {
        return $this->render('instrumentview', [
            'model' => $this->findModel($id,'app\models\ServicesInstrument'),
        ]);
    }
	
	/**
     * Lists all ServicesAgreement models.
     * @return mixed
     */
    public function actionAgreementIndex()
    {
        $searchModel = new ServicesAgreementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('agreementindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ServicesAgreement model.
     * @param integer $id
     * @return mixed
     */
    public function actionAgreementView($id)
    {
        return $this->render('agreementview', [
            'model' => $this->findModel($id,'app\models\ServicesAgreement'),
        ]);
    }
	
	/**
     * Lists all ServicesReservation models.
     * @return mixed
     */
    public function actionReservationIndex()
    {
        $searchModel = new ServicesReservationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('reservationindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ServicesReservation model.
     * @param integer $id
     * @return mixed
     */
    public function actionReservationView($id)
    {
        return $this->render('reservationview', [
            'model' => $this->findModel($id,'app\models\ServicesReservation'),
        ]);
    }
	
	/**
     * 流转审核
     * 
     * 
     */
    public function actionAudit()
    {
		$id = Yii::$app->request->post("id",0);
		$type = Yii::$app->request->post("type",0);
		$curstatus = Yii::$app->request->post("curstatus",0);
		$tostatus = Yii::$app->request->post("tostatus",0);
		$memo = Yii::$app->request->post("memo");
		$time = time();
		if($type==1){
			$M = 'app\models\ServicesReservation';
		}elseif($type==2){
			$M = 'app\models\ServicesInstrument';
		}else{
			$M = 'app\models\ServicesAgreement';
		}
		$model = $this->findModel($id,$M);
		if($model->status != $curstatus){
			$this->errorMsg("PageTimeOut",'页面已过期,请刷新');
		}
		
		if($model->updateAll(['status'=>$tostatus,'modify_user'=>Yii::$app->user->getId(),'modify_time'=>$time,'auditmemo'=>$memo],['status'=>$curstatus,'id'=>$model->id])==0){
			$this->errorMsg("PageTimeOut",'页面已过期,请刷新');
		}
		
		$this->success("处理成功");
		
    }
    
 

    /**
     * Finds the ServicesAgreement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ServicesAgreement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id,$M = 'app\models\ServicesAgreement')
    {
        if (($model = $M::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
