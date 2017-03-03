<?php

namespace backend\controllers;

use Yii;
use app\models\Protectright;
use app\models\ProtectrightSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\BackController;

/**
 * ProtectrightController implements the CRUD actions for Protectright model.
 */
class ProtectrightController extends BackController
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
     * Lists all Protectright models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProtectrightSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderIsAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Protectright model.
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
			'relatype'=>'2',
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

    /**
     * Finds the Protectright model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Protectright the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Protectright::findOne($id)) !== null) {
            return $model;
        } else {
			if(Yii::$app->request->isAjax){
				$this->errorMsg("ParamsCheck",'');
			}else{
				throw new NotFoundHttpException('The requested page does not exist.');
			}
        }
    }
	public function actionIndexold(){
		$this->header = "产调管理";
		$status = Yii::$app->request->get('status');
		$phone = Yii::$app->request->get('phone');
		$query = Property::find();
		if($status != ''){
			$query->andFilterWhere(['status' => $status]);
		}
		if($phone != ''){
			// if(preg_match("/^1[34578]\d{9}$/", $phone)){
				// $query->andFilterWhere(['phone|address' => $phone]);
			// }else{
				$query->andFilterWhere(["or",['like', 'phone', $phone],['like', 'address', $phone],['like', 'orderId', $phone]]);
			// }
		}
		$pagination = new Pagination([
			'defaultPageSize' => 18,
			'totalCount' => $query->count(),
		]);
		$properties = $query->orderBy('time DESC')->offset($pagination->offset)->limit($pagination->limit)->all();
		$proArr = [];
		foreach($properties as $k => $v){
			$v = $v->toArray();
			$area = Areas::findOne($v['city']);
			$Express = Express::find()->where(['jid'=>$v['id']])->one();
			$cdcon = Cdcon::find()->where(['cid'=>$v['cid']])->one();
			if(! empty($Express)){
				$v['orderd'] = $Express->toArray()['id'];
				$v['eid'] = $Express->toArray()['orderId'];
			}else{
				$v['orderd'] = '';
				$v['eid'] = '';
			}
			if(! empty($cdcon)){
				$v['refund_msg'] = $cdcon->toArray()['refund_msg'];
			}else{
				$v['refund_msg'] = '';
			}
			$v['city'] = $area['name'];
			$proArr[] = $v;
		}
		return $this->render('index_old',['pagination'=>$pagination,'data'=>$proArr,'status'=>$status,'phone'=>$phone]);
	}
}
