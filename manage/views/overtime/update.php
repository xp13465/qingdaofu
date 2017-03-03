<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AttendanceOvertime */

$this->title = '修改加班单#' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '详情', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attendance-overtime-update">

    <?= $this->render('_form', [
        'model' => $model,
		'type' => true,
    ]) ?>

</div>
