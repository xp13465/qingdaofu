<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\AttendanceCheckinReports;
/* @var $this yii\web\View */
/* @var $model app\models\AttendanceCheckinReports */

$this->title = "详情#".$model->id;
$this->params['breadcrumbs'][] = ['label' => '考勤报表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$status = AttendanceCheckinReports::$status;
?>
<div class="attendance-checkin-reports-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            // 'personnel_id',
            'employeeid',
            'username',
            'signdate',
            'dayofweek',
            'signtime1',
            'signtime2',
            'timediff',
			[
                'attribute' => 'signstatus',
                'value' => $model->signstatus_valid?$model->signstatus_valid:$model->signstatus,
            ],
            'memo',
            'latetime',
            'latetime_valid',
            'overtime',
            'overtime_valid',
			[
                'attribute' => 'status',
                'value' => isset($status[$model->status])?$status[$model->status]:'',
            ],
            'modify_at',
            // 'dayofweekkey',
        ],
    ]) ?>

</div>
