<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AttendanceGoout */

$this->title = '添加外出单';
$this->params['breadcrumbs'][] = ['label' => '列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attendance-goout-create">

    <?= $this->render('_form', [
        'model' => $model,
		'type'  => true,
    ]) ?>

</div>
