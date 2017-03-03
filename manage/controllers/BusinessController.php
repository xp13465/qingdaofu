<?php

namespace manage\controllers;

use Yii;
use manage\components\BackController;
use app\models\Solutionseal;
use app\models\SolutionsealSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class BusinessController extends BackController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
	
	 public function actionSolutionseal()
    {
		$model = new Solutionseal();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->solutionsealid]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

}
