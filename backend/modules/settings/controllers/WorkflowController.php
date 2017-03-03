<?php
namespace backend\modules\settings\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\validators\FileValidator;
use yii\web\UploadedFile;
use backend\components\BackController;
/**
 * Site controller
 */
class WorkflowController extends BackController
{

    public $modelClass = 'backend\modules\settings\models\Workflow';
    public $modelSearchClass = 'backend\modules\settings\models\search\WorkflowSearch';

    public function actionIndex()
    {
        $modelClass = $this->modelClass;
        $searchModel = $this->modelSearchClass ? new $this->modelSearchClass : null;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', compact('dataProvider', 'searchModel'));
    }



}
