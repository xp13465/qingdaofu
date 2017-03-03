<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Personnel */

$this->title = '角色修改#' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '角色列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '角色内容', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '角色修改#' . $model->id;
?>
<div class="personnel-update">

    <?= $this->render('_form', [
        'model' => $model,
		'organization'=>$organization,
    ]) ?>

</div>
