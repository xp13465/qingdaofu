<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AttendanceOvertime */

$this->title = '员工加班申请单';
$this->params['breadcrumbs'][] = ['label' => '加班单列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attendance-overtime-create">
    <?= $this->render('_form', [
        'model' => $model,
		'type' => true,
    ]) ?>

</div>
