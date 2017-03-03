<?php

use yii\grid\GridView;
use backend\helpers\Html;
use backend\models\Admin;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = '后台用户列表';

?>

<div class="post-index">

    <div class="panel panel-default">
    <div class="panel-body">

    <p>
    <?= Html::a(Yii::t('zcb', 'Create Admin user'), ['create'], ['class' => 'btn btn-success']) ?>
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
                    ['class' => 'yii\grid\SerialColumn', 'options' => ['style' => 'width:10px']],
                    'username',
                    [
                        'attribute' => 'status',
                        'value' => function($model) {
                            return $model->status == 0 ? '未激活' : '激活';
                        },
                        'filter' => Admin::getStatusList(),
                    ],
                    //'group',

                    [
                        'attribute' => 'postName',
                        'value' => 'postName',
                    ],


                    [
                        'class' => 'backend\grid\columns\ActionColumn',
                        'buttonsTemplate' => '{update} {view}',
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
