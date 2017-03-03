<?php

namespace backend\controllers;

use Yii; 
use app\models\Policy;
use app\models\PolicySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\BackController;

/**
 * PolicyController implements the CRUD actions for Policy model.
 */
class PolicyController extends BackController
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
                    'audit' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Policy models.
     * @return mixed
     */
    public function actionIndex()
    {
		
	
        $searchModel = new PolicySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		/*
		$orderIds = [];
		foreach($dataProvider->models as $data){
			if($data->orderid)$orderIds[]=$data->orderid;
		}
		var_dump($orderIds);
		
		三方数据 用于关联状态 暂不启用
		$policylist = \frontend\services\Func::CurlPostForAPI("http://api.ssbq.cn/api/Index/getPolicyList?order=201608041470308513747186",[
            'domain'=>'wx.zcb2016.com',
            'secret'=>'mi-xndy-mdc1odc5lxd4lnpjyjiwmtyuy29tleebtowqkei1hos6p+euoeeqhuaciemzkowfrowpuc3pg5hluizlm7-tmtgxntu2mje2oty=',
        ]);
        $policylistArray = \yii\helpers\Json::decode($policylist);
		var_dump($policylistArray);
		*/
		
        return $this->renderIsAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Policy model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderIsAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }
  
    /**
     * Finds the Policy model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Policy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Policy::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	/**
     * 流转审核
     * 
     * 
     */
    public function actionAudit()
    {
		$id = Yii::$app->request->post("id",0);
		$curstatus = Yii::$app->request->post("curstatus",0);
		$tostatus = Yii::$app->request->post("tostatus",0);
		$memo = Yii::$app->request->post("memo");
		$time = time();
		$model = $this->findModel($id);
		if($model->status != $curstatus){ 
			$this->errorMsg("PageTimeOut",'页面已过期,请刷新');
		}
		
		if($model->updateAll(['status'=>$tostatus],['status'=>$curstatus,'id'=>$model->id])==0){
			$this->errorMsg("PageTimeOut",'页面已过期,请刷新');
		}
		$AuditLogModel = new \app\models\AuditLog();
		
		$AuditLogModel->setAttributes([
			'relatype'=>'1',
			'relaid'=>$id,
			'beforestatus'=>$curstatus,
			'afterstatus'=>$tostatus,
			'action_by'=>Yii::$app->user->getId(),
			'action_at'=>$time,
			'memo'=>$memo,
		]);
		$AuditLogModel->save();
		
		$this->success("流转成功！");
		
    }
}
