<?php

use yii\grid\GridView;
use backend\helpers\Html;
use backend\models\User;
use common\models\Certification;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = '资格认证';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="post-index">

    <div class="row">
        <div class="col-sm-12">
            <h3 class="lte-hide-title page-title"><?= Html::encode($this->title) ?></h3>
            <?= Html::a(Yii::t('zcb', 'Add'), ['/certification/create'], ['class' => 'btn btn-sm btn-primary']) ?>
            <?= Html::a(Yii::t('zcb', 'List'), ['/certification/index'], ['class' => 'btn btn-sm btn-primary']) ?>
        </div>
    </div>

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
                        'header'=> Yii::t('zcb', 'Category'),
                        'options' => ['style' => 'width:120px'],
                        'filter' => Certification::$certifi,
                        'value'=>function (Certification $model){
                            $category = Certification::$certifi;
                            return $category[$model->category];
                        }
                    ],

                    'name',
                    'address',
                    'email',
                    'mobile',
                    'cardno',
                    'contact',
                    [
                        'attribute' => 'state',
                        'header'=> Yii::t('zcb', 'Status'),
                        'options' => ['style' => 'width:120px'],
                        'filter' => ['1'=>'申请成功','2'=>'申请失败'],
                        'format' => 'raw',
                        'value'=>function (Certification $model, $key, $index, $column){
                            $state = ['0'=> '审核', '1'=>'申请成功', '2'=>'申请失败'];
                            //return $state[$model->state];
                            return $model->state == 0 ? Html::a('审核', ['/certification/sh', 'id' => $key]) : $state[$model->state] ;
                        }
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
