<?php

use yii\grid\GridView;
use backend\helpers\Html;
use common\models\CreditorProduct;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = '债权和清收列表';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="post-index">

    <div class="panel panel-default">
        <div class="panel-body">

            <?php
            Pjax::begin([
                'id' => 'post-grid-pjax',
            ])
            ?>
    <p>
        <button type="button" class="btn btn-2g btn-primary" onclick="window.open('<?php echo \yii\helpers\Url::to('/certification/output')?>')">导出</button><br /><br />
    </p>
            <?=
            GridView::widget([
                'id' => 'post-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn', 'options' => ['style' => 'width:10px']],

                    [
                        'attribute' => 'category',
                        'filter' => common\models\CreditorProduct::$categorys,
                        'value' => function (CreditorProduct $model) {
                            $category = common\models\CreditorProduct::$categorys;
                            return $category[$model->category];
                        },
                        'format' => 'raw',
                        'options' => ['style' => 'width:80px'],
                    ],

                    [
                        'attribute' => 'uid',
                        'filter' => common\models\User::getUsersList(),
                        'value' => function (CreditorProduct $model) {
                         return $model->user->username;
                        },
                        'format' => 'raw',
                        'options' => ['style' => 'width:50px'],
                    ],


                    [
                        'attribute' => 'money',
						'value' => function (CreditorProduct $model) {
                            return $model->progress_status.'万';
                        },
                        'options' => ['style' => 'width:120px'],
                    ],



                    [
                        'attribute' => 'create_time',
                        'value'=>function($model){
                            return  date('Y-m-d H:i:s',$model->create_time);
                        },
                        'options' => ['style' => 'width:160px'],
                    ],


                    [
                        'header'=> Yii::t('zcb', 'Product_Code'),
                        'attribute' => 'code',
                        'value' => 'code',
                        'options' => ['style' => 'width:120px'],
                    ],

                    [
                        'attribute' => 'progress_status',
                        'filter' => common\models\CreditorProduct::$progressstatus,
                        'value' => function (CreditorProduct $model) {
                             $status = common\models\CreditorProduct::$progressstatus;
                            return isset($status[$model->progress_status])?$status[$model->progress_status]:'';
                        },
                        'format' => 'raw',

                        'options' => ['style' => 'width:120px'],
                    ],


                    [
                        'header'=> Yii::t('zcb', 'Mobile'),
                        'attribute' => 'user',
                        'value' => 'user.mobile',
                        'options' => ['style' => 'width:120px'],

                    ],

                    [
                        'class' => 'backend\grid\columns\ActionColumn',
                        'buttonsTemplate' => '{view}',
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
