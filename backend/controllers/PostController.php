<?php
namespace backend\controllers;

use Yii;
use backend\components\BackController;
use yii\widgets\ActiveForm;
use yii\db\ActiveRecord;
use yii\helpers;
use yii\filters\VerbFilter;
use yii\helpers\StringHelper;

class PostController extends BackController
{
    public $modelClass = 'backend\models\Post';
    public $modelSearchClass = 'backend\models\PostSearch';

    /**
     * @inheritdoc
     e*/
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $group = Yii::$app->user->identity->group;

        $modelClass = $this->modelClass;
        $searchModel = $this->modelSearchClass ? new $this->modelSearchClass : null;

        if ($searchModel) {
                $searchName = StringHelper::basename($searchModel::className());
                $params = Yii::$app->request->getQueryParams();


                $dataProvider = $searchModel->search($params);
        } else {
                $restrictParams = ($restrictAccess) ? [$modelClass::getOwnerField() => Yii::$app->user->identity->id] : [];
                $dataProvider = new ActiveDataProvider(['query' => $modelClass::find()->where($restrictParams)]);
        }

        return $this->renderIsAjax('index', compact('dataProvider', 'searchModel'));

    }


    public function actionCreate()
    {
        $model = new $this->modelClass;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('crudMessage', '用户岗位添加成功.');
                return $this->redirect($this->getRedirectPage('create', $model));
        }

        return $this->renderIsAjax('create', compact('model'));
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) AND $model->save()) {
                Yii::$app->session->setFlash('crudMessage', '用户岗位更新成功');
                return $this->redirect($this->getRedirectPage('update', $model));
        }

        return $this->renderIsAjax('update', compact('model'));
    }

}
