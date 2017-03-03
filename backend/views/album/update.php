<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Album */

$this->title = '修改文章#' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '新闻列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' =>'新闻内容', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改文章#' . $model->id;
?>
<div class="album-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
