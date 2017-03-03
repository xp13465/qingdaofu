<?php

use yii\grid\GridView;
use backend\helpers\Html;
use common\models\FormCategory;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = '工作流列表';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="post-index">

    <div class="panel panel-default">
    <div class="panel-body">

    <p>
    <?= Html::a(Yii::t('zcb', 'Create Workflow'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

            <?php
            Pjax::begin([
                'id' => 'post-grid-pjax',
            ])
            ?>

            <?=
            GridView::widget([
                'id' => 'post-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn', 'options' => ['style' => 'width:10px']],

                    [
                        'attribute' => 'steps',
                        'value' => 'steps',
                        'options' => ['style' => 'width:120px'],
                    ],

                    [
                        'attribute' => 'workname',
                        'value' => 'workname',
                        'options' => ['style' => 'width:120px'],
                    ],

                    [
                        'attribute' => 'description',
                        'value' => 'description',
                        'options' => ['style' => 'width:120px'],
                    ],



                    [
                        'class' => 'backend\grid\columns\ActionColumn',
                        'buttonsTemplate' => '{update}',
                        'contentOptions' => ['style' => 'width:120px;'],
                        'header'=> Yii::t('zcb', 'Actions'),

                    ],


                ],
            ]);
            ?>

            <?php Pjax::end() ?>
        </div>
    </div>
</div>
