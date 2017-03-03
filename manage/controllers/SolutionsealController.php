<?php

namespace manage\controllers;

use Yii;
use manage\components\BackController;
use app\models\Solutionseal;
use app\models\SolutionsealSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SolutionsealController implements the CRUD actions for Solutionseal model.
 */
class SolutionsealController extends BackController
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
     * Lists all Solutionseal models.
     * @return mixed
     */
    public function actionGenerate()
    {
		$id = Yii::$app->request->post("id");
        $SolutionsealQuery = Solutionseal::find();
		$data = $SolutionsealQuery->where(['solutionsealid'=>$id])->one();
		$pact = $data->getPact($type=true);
		if(!$data)$this->errorMsg(PARAMSCHECK,"参数错误");
		if($data->personnelUserid){ 
			$status = $SolutionsealQuery->ordersGenerate($id,$data->personnelUserid,'992',$pact);
		}else{
			$this->errorMsg(PARAMSCHECK,"订单生成失败（风控无清道夫帐号）");
		}
        switch($status){
			case "ok":
				$this->success("生成成功");
				break;
			default:
				$this->errorMsg($status,$SolutionsealQuery->formatErrors());
				break;
		}
    }
    /**
     * Lists all Solutionseal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SolutionsealSearch();
		$params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params,Yii::$app->user->getId());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'type' => '1',
        ]);
    }
	
	/**
     * Lists all Solutionseal models.
     * @return mixed
     */
    public function actionManage()
    {
		// (($abc = \app\models\Solutionseal::findOne(46)));
		// var_dump($abc->costestimates);
        $searchModel = new SolutionsealSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'type'=>'2',
        ]);
    }
	
	/**
     * Lists all Solutionseal models.
     * @return mixed
     */
    public function actionStatistics()
    {
		$statisticsData = \app\models\Solutionseal::find()->statistics();
        return $this->render('statistics', [
			'statisticsData' => $statisticsData,
        ]);
    }
	
	/**
     * Lists all Solutionseal models.
     * @return mixed
     */
    public function actionTop($month)
    {
		$SolutionsealQuery =    \app\models\Solutionseal::find();
		$top['jinjianTopList'] = $SolutionsealQuery->topList("status <> 0","create_at",$month);
		$top['jiedanTopList'] = $SolutionsealQuery->topList(["status"=>["40","50","60","70"]],"create_at",$month);
		$top['jieanTopList'] = $SolutionsealQuery->topList(["status"=>["60","70"]],"closeddate",$month);
        $this->success("",$top);
    }

    /**
     * Displays a single Solutionseal model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id="")
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Solutionseal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Solutionseal();
		$province = \app\models\Province::find()->select("provinceID,province")->asArray()->all();
		$model->entrust = 4;
		$model->personnel_name = Yii::$app->user->identity->personnel?Yii::$app->user->identity->personnel->name:'';
		$model->personnel_id = Yii::$app->user->identity->personnel?Yii::$app->user->identity->personnel->id:'';
		$model->province_id = '310000';
		$model->city_id = '310100';
		$model->district_id = '310115';
        if (Yii::$app->request->post()) {
			$post = Yii::$app->request->post();
			$model->load($post);
			if(is_array($model->category))$model->category=join(",",$model->category);
			$model->status = $post['Solutionseal']['status'];
			$model->entrust = "4";
			if($model->save()){
				return $this->redirect(['view', 'id' => $model->solutionsealid]);
			}else{
				var_dump($model->errors);
			}
            
        }
        return $this->render('create', [
            'model' => $model,
			'province'=>$province,
        ]);
        
    }

    /**
     * Updates an existing Solutionseal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id="")
    {
        $model = $this->findModel($id);
		$province = \app\models\Province::find()->select("provinceID,province")->asArray()->all();
		
        if (Yii::$app->request->post()) {
			$post = Yii::$app->request->post();
			$model->load($post);
			if(is_array($model->category))$model->category=join(",",$model->category);
			$model->status = $post['Solutionseal']['status'];
			$model->entrust = "4";
			$model->modify_at = time();
			$model->modify_by = Yii::$app->user->getId();
			if($model->save()){
				return $this->redirect(['view', 'id' => $model->solutionsealid]);
			}
            
        }
	
		if(is_string($model->category)&&$model->category)$model->category=explode(",",$model->category);
        return $this->render('update', [
            'model' => $model,
            'province' => $province,
        ]);
    }

    /**
     * Deletes an existing Solutionseal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id='')
    {
		$id = Yii::$app->request->post('id');
		$validflag = Yii::$app->request->post('validflag');
		$query = Solutionseal::find();
		$status = $query->solutionsealDelete($id,$validflag);
		if($status){
			 return $this->success($status);
		}
        //return $this->redirect(['index']);
    }
	
	//产品发布审核
	public function actionReleaseManage(){
		$searchModel = new SolutionsealSearch();
		$params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params,Yii::$app->user->getId(),'0');
        return $this->render('manageIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
	//风控审核
	public function actionRiskManagement(){
		$searchModel = new SolutionsealSearch();
		$params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params,'','11');
        return $this->render('manageIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
	//合同审核
	public function actionContractManage(){
		$searchModel = new SolutionsealSearch();
		$params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params,'','20');
        return $this->render('manageIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
	//财务审核
	public function actionFinanceManage(){
		$searchModel = new SolutionsealSearch();
		$params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params,'',['30','40']);
        return $this->render('manageIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
	//产品跟进
	public function actionFollowManage(){
		$searchModel = new SolutionsealSearch();
		$params = Yii::$app->request->queryParams;
		$query = Solutionseal::find();
		// $data = $query->auditLog();
        $dataProvider = $searchModel->search($params,'','',true);
        return $this->render('manageIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'type' => true,
        ]);
	}
	
	
	
	//面谈失败
	public function actionInterviewFailure($id=''){
		if(Yii::$app->request->post()){
			$params = Yii::$app->request->post();
			if(!$params['memo']){
				return $this->errorMsg('','请填写留言.');
			}
			$status = $this->actionAuditStatus($params);
			return $status;
		}
	}
	
	//面谈成功
	public function actionInterviewSuccess($id=''){
		if(Yii::$app->request->post()){
			$params = Yii::$app->request->post();
			if(!$params['custname']&&!$params['custmobile']&&!$params['account']&&!$params['typenum']){
				return $this->errorMsg('','请不要留空.');
			}
			$status = $this->actionAuditStatus($params);
			return $status;
		}
	}
	
	// 确认合同
	public function actionContractConfirmation($id=''){
		if(Yii::$app->request->post()){
			$params = Yii::$app->request->post();
			if(!$params['pact']){
				return $this->errorMsg('','请上传图片.');
			}
			$status = $this->actionAuditStatus($params);
			return $status;
		}
	}
	
	// 定金确认
	public function actionDepositConfirmation($id=''){
		$query = Solutionseal::find();
		if(Yii::$app->request->post()){
			$params = Yii::$app->request->post();
			if($params['earnestmoney']>0){
				$status = $this->actionAuditStatus($params);				
				return $status;				
			}else{
				return $this->errorMsg('','请输入定金.');
			}
			
		}
	}
	
	// 确认放款
	public function actionLoanConfirmation($id=''){
		if(Yii::$app->request->post()){
			$params = Yii::$app->request->post();
			if($params['borrowmoney']>0){
				$status = $this->actionAuditStatus($params);
				return $status;
			}else{
				return $this->errorMsg('','请输入金额.');
			}
			
		}
	}
	
	// 确认回款
	public function actionPaymentConfirmation($id=''){
		if(Yii::$app->request->post()){
			$params = Yii::$app->request->post();
			if($params['payinterest']>0 && $params['actualamount']>0 && $params['backmoney']>0 ){
				$status = $this->actionAuditStatus($params);
				return $status;
			}else{
				return $this->errorMsg('','请输入金额.');
			}
		}
	}
	
	
	public function actionAuditStatus($params=[]){
		if($params){
			$query = Solutionseal::find();
			$status = $query->audit($params['id'],$params);
			if($status == OK){
				$msg="成功";
				$data = $query->where(['solutionsealid'=>$params['id']])->one();
				$pact = $data->getPact($type=true);
				$user = $data->getUser(Yii::$app->user->identity->personnel->mobile);
				$uid = $user->id?$user->id:$data->operatorid['operatorid'];
				if($params['beforestatus'] == '30'){
					if($data->personnelUserid){
						$status = $query->ordersGenerate($params['id'],$data->personnelUserid);
						$pactId = $query->pacts($pact);
						if($status == OK){
							$orders = $query->agreement($params['id'],$uid,'',$pactId);
							if($orders == OK){
								$msg ="确定成功";
							}
						}else{
							$msg ="定金支付成功,订单生成失败（错误代码:{$status}）";
						}
					}else{
						$msg ="定金支付成功,订单生成失败（风控无清道夫帐号）";
					}
					
					
				}else if($data->status == '60'){
					// if(!$data->operatorid['operatorid']){
						// $msg = "没有添加经办人将以接单方的名义发起结案。";
					// }
					$status = $query->record($params['id'],$params['memo'],$data->operatorid['operatorid'],$uid);
					if($status == OK){
						$msg = "结案成功";
					}else{
						$msg = "结案失败（错误代码:{$status}）";
					}
				}else if($params['beforestatus'] == '40'){
					$status = $query->contract($params['id'],$uid);
					if($status == OK){
						$msg = "放款成功";
					}
				}
				return $this->success($msg);
			}else{
				return $this->success($status);
			}
		}
	}


    /**
     * Finds the Solutionseal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Solutionseal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Solutionseal::find()->where(["solutionsealid"=>$id])->joinWith(["provincename","cityname","areaname"])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}