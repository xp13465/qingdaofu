<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AttendanceLeave */

$this->title = '添加请假单';
$this->params['breadcrumbs'][] = ['label' => '请假单管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attendance-leave-create">
 

    <?= $this->render('_form', [
        'model' => $model,
		'type' => true,
    ]) ?>

</div>
