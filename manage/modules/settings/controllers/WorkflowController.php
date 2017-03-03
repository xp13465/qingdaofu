<?php
namespace manage\modules\settings\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\validators\FileValidator;
use yii\web\UploadedFile;
use manage\components\BackController;
/**
 * Site controller
 */
class WorkflowController extends BackController
{

    public $modelClass = 'manage\modules\settings\models\Workflow';
    public $modelSearchClass = 'manage\modules\settings\models\search\WorkflowSearch';

    public function actionIndex()
    {
        $modelClass = $this->modelClass;
        $searchModel = $this->modelSearchClass ? new $this->modelSearchClass : null;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', compact('dataProvider', 'searchModel'));
    }



}
