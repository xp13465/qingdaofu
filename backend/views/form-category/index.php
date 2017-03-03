<?php

use yii\grid\GridView;
use backend\helpers\Html;
use common\models\FormCategory;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = '表单列表';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="post-index">

    <div class="panel panel-default">
    <div class="panel-body">

    <p>
    <?= Html::a(Yii::t('zcb', 'Create Form'), ['create'], ['class' => 'btn btn-success']) ?>
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
                        'attribute' => 'name',
                        'options' => ['style' => 'width:120px'],
                        'value' => function ($model, $key, $index, $column) {
                            return Html::a($model->name, ['form/index', 'id' => $key], ['target' => '_blank']);
                        },
                        'format' => 'raw',
                    ],

                    [
                        'attribute' => 'type',
                        'value' => 'type',
                        'options' => ['style' => 'width:120px'],
                    ],

                    [
                        'attribute' => 'description',
                        'value' => 'description',
                        'options' => ['style' => 'width:120px'],
                    ],

                    [
                        'attribute' => 'steps',
                        'value' => 'steps',
                        'options' => ['style' => 'width:120px'],
                    ],



                    [
                        'attribute' => 'created_at',
                        'value'=>function($model){
                            return  date('Y-m-d H:i:s',$model->created_at);
                        },
                        'options' => ['style' => 'width:160px'],
                    ],

                    [
                        'class' => 'backend\grid\columns\ActionColumn',
                        'template' => '{buttons}',
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
