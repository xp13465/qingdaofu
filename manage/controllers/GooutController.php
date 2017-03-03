<?php

namespace manage\controllers;

use Yii;
use app\models\AttendanceGoout;
use app\models\AttendanceGooutSearch;
use manage\components\BackController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GooutController implements the CRUD actions for AttendanceGoout model.
 */
class GooutController extends BackController
{
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

    /**
     * Lists all AttendanceGoout models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AttendanceGooutSearch();
        $data = ['uid'=>Yii::$app->user->getId()];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$data);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'type'=>'1',
        ]);
    }
	
	/**
     * Lists all AttendanceLeave models.
     * @return mixed
     */
    public function actionManage()
    {
        $searchModel = new AttendanceGooutSearch();
		//$data = ['uid'=>Yii::$app->user->getId()];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'type'=>'2',
        ]);
    }

    /**
     * Displays a single AttendanceGoout model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AttendanceGoout model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AttendanceGoout();
		$data = $this->autocomplete(Yii::$app->user->getId());
		if(!$data)return PARAMSCHECK;
		$model->employeeid = $data['personnels']['employeeid'];
		$model->personnel_id = $data['personnels']['department_pid'];
		$model->department = $data['personnels']['departments']['name'];
		$model->job = $data['personnels']['job'];
		$model->toexamineid = $data['personnels']['admins']['id'];
		$model->username = $data['personnels']['name'];
		$post = Yii::$app->request->post();
        if($model->load($post)){
			$model->gooutstart =$model->gooutstart?strtotime($model->gooutstart):"";
			$model->gooutend =$model->gooutend?strtotime($model->gooutend):"";
			$model->gooutend_valid =$model->gooutend_valid?strtotime($model->gooutend_valid):"";
			$model->status = $model->status==20?"20":"0";
			$model->writedate = date('Y-m-d',time());
			if($model->save()){
				 return $this->redirect(['view', 'id' => $model->id]);
			}
		}
        
		return $this->render('create', [
            'model' => $model,
        ]);
        
    }

    /**
     * Updates an existing AttendanceGoout model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$post = Yii::$app->request->post();
        if($model->load($post)){
			$model->gooutstart =$model->gooutstart?strtotime($model->gooutstart):"";
			$model->gooutend =$model->gooutend?strtotime($model->gooutend):"";
			$model->gooutend_valid =$model->gooutend_valid?strtotime($model->gooutend_valid):"";
			$model->status = $model->status==20?"20":"0";
			$model->modify_by = Yii::$app->user->getId();
			$model->modify_at = time();
			if($model->save()){
				 return $this->redirect(['view', 'id' => $model->id]);
			}
        } 
        return $this->render('update', [
            'model' => $model,
        ]);
        
    }

    /**
     * Deletes an existing AttendanceGoout model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id='')
    {
		$id = Yii::$app->request->post('id');
		$type = Yii::$app->request->post('type');
		
        if($type == 1){
			$status = $this->findModel($id)->updateAll(['validflag'=>'0','modify_at'=>time(),'modify_by'=>Yii::$app->user->getId()],['validflag'=>'1','id'=>$id]);
			if($status){
			 $data = "删除成功" ;
			}
		}else{
			$status = $this->findModel($id)->updateAll(['validflag'=>'1'],['validflag'=>'0','id'=>$id]);
			$data = "恢复成功";
		}
          
        if($status){
			 return $this->success($data);
		}
    }
	
	
	//审核列表
	public function actionAudit(){
		$searchModel = new AttendanceGooutSearch();
		$data = ['toexamineid'=>Yii::$app->user->getId()];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$data);
        return $this->render('manageIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
	//已审核列表
	public function actionAuditedList(){
		 $searchModel = new AttendanceGooutSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,['status'=>true]);
        return $this->render('manageIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}

	
	//审核过程
	public function actionStatus($id=''){
		$id = Yii::$app->request->post('id');
		$afterstatus = Yii::$app->request->post('afterstatus');
		$status = Yii::$app->request->post('status');
		$query = AttendanceGoout::find();
		$username = $query->username(true);
		$supervisormemo = Yii::$app->request->post('supervisormemo');
		$tpid = Yii::$app->request->post('tpid');
		if(!$supervisormemo){
			return $this->errorMsg('','请输入备注');
		}
		if(!$tpid){
			return $this->errorMsg('','请签字');
		}
		$params = [
		   'supervisormemo'=>$supervisormemo,
		   'afterstatus'=>$afterstatus,
		   'beforestatus'=>Yii::$app->request->post('beforestatus'),
		   'memo'=>$query::$memoLabel[$status],
		   'signatureGraph'=>$query::$signatureGraph[$status],
		   'signatureMemo'=>$query::$signatureMemo[$status],
		   'supervi'=>$query::$supervi[$status],
		   'signatureTime'=>$query::$signatureTime[$status],
		   'tpid'=>Yii::$app->request->post('tpid'),
		   'username' =>$username,
		   'relatype'=>'3',
		];
		$status = $query->manageUpdate($id,$params);
		if($status == OK){
			return $this->success("审核成功");
		}else{
			return $this->errorMsg($status,$query->formatErrors());
		}
        return $this->redirect(['manage']);
	}
    /**
     * Finds the AttendanceGoout model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AttendanceGoout the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AttendanceGoout::findOne($id)) !== null) {
			$model->gooutstart = date('Y-m-d H:i',$model->gooutstart);
			$model->gooutend = date('Y-m-d H:i',$model->gooutend);
			$model->gooutend_valid = date('Y-m-d H:i',$model->gooutend_valid);
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
    protected function autocomplete($term=''){
		$term = Yii::$app->user->getId();
		$return = (new \manage\models\Admin)->autoComplete($term);
		return $return;
	}
}
