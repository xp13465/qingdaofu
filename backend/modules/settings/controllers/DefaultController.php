<?php

namespace backend\modules\settings\controllers;

use Yii;
use yii\web\Controller;

/**
 * Default controller for the `settings` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole('超级管理员');
        //print_r($role);
        print_r(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()));
        return $this->render('index');
    }
}
