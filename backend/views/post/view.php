<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('zcb', 'Post'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Html::a(Yii::t('zcb', 'Index'), ['index'], ['class' => 'btn btn-success']) ?></p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description',
            'created_at:date',
            'updated_at:date'

        ],
    ]) ?>

</div>
