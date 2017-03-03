<?php

use yii\grid\GridView;
use backend\helpers\Html;
use common\models\Apply;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = '接单列表';
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
                        'value' => function (Apply $model) {
                            $category = common\models\CreditorProduct::$categorys;
                            return $category[$model->category];
                        },
                        'format' => 'raw',
                        'options' => ['style' => 'width:100px'],
                    ],

                    [
                        'attribute' => 'uid',
                        'filter' => common\models\User::getUsersList(),
                        'value' => function (Apply $model) {
                            return Html::a($model->user->username,
                                ['/update', 'id' => $model->uid],
                                ['data-pjax' => 0]);
                        },
                        'format' => 'raw',
                        'options' => ['style' => 'width:180px'],
                    ],
                    [
                        'attribute' => 'product_id',
                        'options' => ['style' => 'width:120px'],
                    ],
                    [
                        'attribute' => 'app_id',
                        'header'=> Yii::t('zcb', 'Apply_App_Id'),
                        'options' => ['style' => 'width:120px'],
                        'filter' => ['0'=>'已申请','1'=>'已接单', '2'=>'未申请'],
                        'format' => 'raw',
                        'value'=>function (Apply $model){
                            $app_id = Apply::$app_id;
                            return $app_id[$model->app_id];

                        }


                    ],

                    [
                        'attribute' => 'create_time',
                        'value'=>function($model){
                            return  date('Y-m-d H:i:s',$model->create_time);
                        },
                        'options' => ['style' => 'width:160px'],
                    ],

                    [
                        'header'=> Yii::t('zcb', 'money'),
                        'attribute' => 'creditorproduct',
                        'value' => 'creditorproduct.money',
                        'options' => ['style' => 'width:100px'],
                    ],

                    [
                        'header'=> Yii::t('zcb', 'Product_Code'),
                        'attribute' => 'creditorproduct',
                        'value' => 'creditorproduct.code',
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
