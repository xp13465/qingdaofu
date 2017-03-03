<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('zcb', 'Admin User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$status = backend\models\Admin::getStatusList();
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Html::a(Yii::t('zcb', 'Index'), ['index'], ['class' => 'btn btn-success']) ?></p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'created_at:date',
            [
                'attribute' => 'status',
                'value' => $status[$model->status],
            ],
        ],
    ]) ?>

</div>
