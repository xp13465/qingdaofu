<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AttendanceLeave */

$this->title = '修改请假单:# ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '请假单管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改请假单:#'. $model->id;
?>
<div class="attendance-leave-update"> 

    <?= $this->render('_form', [
        'model' => $model,
		'type' => true,
    ]) ?>

</div>
