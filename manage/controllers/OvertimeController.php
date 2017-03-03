<?php
namespace manage\controllers;

use Yii;
use manage\components\BackController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\AttendanceOvertime;
use app\models\AttendanceOvertimeSearch;


/**
 * OvertimeController implements the CRUD actions for AttendanceOvertime model.
 */
class OvertimeController extends BackController
{
	
	public $errors = [];
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
     * Lists all AttendanceOvertime models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AttendanceOvertimeSearch();
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
        $searchModel = new AttendanceOvertimeSearch();
		//$data = ['uid'=>Yii::$app->user->getId()];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'type'=>'2',
        ]);
    }
	

    /**
     * Displays a single AttendanceOvertime model.
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
     * Creates a new AttendanceOvertime model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AttendanceOvertime();
		$data = $this->autocomplete(Yii::$app->user->getId());
		if(!$data)return PARAMSCHECK;
		$model->employeeid = $data['personnels']['employeeid'];
		$model->personnel_id = $data['personnels']['department_pid'];
		$model->department = $data['personnels']['departments']['name'];
		$model->job = $data['personnels']['job'];
		$model->toexamineid = $data['personnels']['admins']['id'];
		$model->username = $data['personnels']['name'];
		$post = Yii::$app->request->post();
		if($post){
			$post['AttendanceOvertime']['overtimestart'] =strtotime($post['AttendanceOvertime']['overtimestart']);
			$post['AttendanceOvertime']['overtimeend'] = strtotime($post['AttendanceOvertime']['overtimeend']);
			$post['AttendanceOvertime']['overtimeday'] = isset($post['AttendanceOvertime']['overtimeday'])&&$post['AttendanceOvertime']['overtimeday']?$post['AttendanceOvertime']['overtimeday']:'0';
			$model->load($post);
			$model->status = $post['AttendanceOvertime']['status'];
			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}else{
				var_dump($model->errors);die;
			}
		}else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AttendanceOvertime model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$post = Yii::$app->request->post();
        if($post){
			$post['AttendanceOvertime']['overtimestart'] =strtotime($post['AttendanceOvertime']['overtimestart']);
			$post['AttendanceOvertime']['overtimeend'] = strtotime($post['AttendanceOvertime']['overtimeend']);
			$post['AttendanceOvertime']['overtimeday'] = isset($post['AttendanceOvertime']['overtimeday'])&&$post['AttendanceOvertime']['overtimeday']?$post['AttendanceOvertime']['overtimeday']:'0';
			$model->load($post);
			$model->status = $post['AttendanceOvertime']['status'];
			$model->modify_by = Yii::$app->user->getId();
			$model->modify_at = time();
			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AttendanceOvertime model.
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
			$status = $this->findModel($id)->updateAll(['validflag'=>'1','modify_at'=>time(),'modify_by'=>Yii::$app->user->getId()],['validflag'=>'0','id'=>$id]);
			$data = "恢复成功";
		}
		if($status){
			 return $this->success($data);
		}

        return $this->redirect(['index']);
    }

	
	
	
	//审核列表
	public function actionAudit(){
		$searchModel = new AttendanceOvertimeSearch();
		$data = ['toexamineid'=>Yii::$app->user->getId()];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$data);
        return $this->render('manageIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
	//已审核列表
	public function actionAuditedList(){
		 $searchModel = new AttendanceOvertimeSearch();
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
		$query = AttendanceOvertime::find();
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
		   'relatype'=>'4',
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
     * Finds the AttendanceOvertime model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AttendanceOvertime the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($query = AttendanceOvertime::find()) !== null) {
			$model = $query->alias('overtime')->andFilterWhere(['overtime.id'=>$id])->joinWith(['departments'])->one();
			$model->overtimestart = isset($model->overtimestart)&&$model->overtimestart?date('Y-m-d H:i',$model->overtimestart):$model->overtimestart;
			$model->overtimeend = isset($model->overtimeend)&&$model->overtimeend?date('Y-m-d H:i',$model->overtimeend):$model->overtimeend;
			$model->supervisordate = $model->supervisordate&&$model->supervisordate!="0000-00-00" ?$model->supervisordate:'';
			$model->administrationdate = $model->administrationdate&&$model->administrationdate!="0000-00-00" ?$model->administrationdate:'';
			$model->generalmanagerdate = $model->generalmanagerdate&&$model->generalmanagerdate!="0000-00-00" ?$model->generalmanagerdate:'';
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
