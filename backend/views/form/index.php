<?php

use yii\grid\GridView;
use backend\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\editable\Editable;
use kartik\widgets\SwitchInput;
//use kartik\grid\GridView;
use backend\models\FormCategory;
use backend\models\Form;

backend\assets\SwitcherAsset::register($this);
backend\assets\FancyboxAsset::register($this);


$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('zcb', 'Form'), 'url' => ['/form/index','id'=> $model->primaryKey]];
$this->params['breadcrumbs'][] = $this->title;

use yii\bootstrap\Modal;
Modal::begin([
    'options'=>['id'=>'kartik'],
    'header' => '<h4 style="margin:0; padding:0">Switch Input Inside Modal</h4>',
    'toggleButton' => ['label' => 'Show Modal', 'class'=>'btn btn-lg btn-primary'],
]);
echo SwitchInput::widget(['name' => 'status_16']);
Modal::end();

?>
<div class="post-index">
    <div class="panel panel-default">
    <div class="panel-body">

    <div>
    <p>
    <?= Html::a(Yii::t('zcb', 'Create Form'), ['/form/create', 'id'=> $model->primaryKey], ['class' => 'btn btn-success']) ?>
    </p>

    </div>
      <?php
      Pjax::begin([
                'id' => 'form-grid-pjax',
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
                        'attribute' => 'category_id',
                        'filter' => Form::formCategory(),
                        'value' => function ($model) {
                            $category = Form::formCategory();
                            return $category[$model->category_id];
                        },
                        'format' => 'raw',
                        'options' => ['style' => 'width:80px'],
                    ],

                    [
                        'attribute' => 'code',
                        'value' => 'code',
                        'options' => ['style' => 'width:120px'],
                    ],

                    [
                        'attribute' => 'name',
                        'value' => 'name',
                        'options' => ['style' => 'width:120px'],
                        'format' => 'raw',
                    ],

                    [
                        'attribute' => 'department',
                        'value' => 'department',
                        'options' => ['style' => 'width:120px'],
                    ],


                    [
                        'attribute' => 'pay_time',
                        'value'=>function($model){
                            return  date('Y-m-d H:i:s',$model->pay_time);
                        },
                        'options' => ['style' => 'width:160px'],
                    ],

                    [
                        'attribute' => 'created_at',
                        'value'=>function($model){
                            return  date('Y-m-d H:i:s',$model->created_at);
                        },
                        'options' => ['style' => 'width:160px'],
                    ],

                    [
                        'attribute' => 'status',
                        'filter' => Form::getStatus(),
                        'value' => function ($model, $index, $widget) {
                            $status = Form::getStatus();
                            return  $status[$model->status];

                        },
                        'format' => 'raw',

                        'options' => ['style' => 'width:120px'],
                    ],

                    /*[
                        'attribute' => 'status',
                        'filter' => common\models\Form::getStatus(),
                        'value' => function ($model) {
                            $status = common\models\Form::getStatus();
                            return $status[$model->status];
                        },
                        'format' => 'raw',

                        'options' => ['style' => 'width:120px'],
                        ],*/

                    [
                        'class' => 'backend\grid\columns\ActionColumn',

                        'buttonsTemplate' => '{update} {view} {delete}',
                        'contentOptions' => ['style' => 'width:120px;'],
                        'header'=> Yii::t('zcb', 'Actions'),

                        ],

                    /*['class' => 'kartik\grid\ActionColumn',
                     'buttons' => [
                         'update' => function ($url, $model) {
                             return \Yii::$app->user->can('') ? Html::a('', $url, [
                                 'title' => 'Editar',
                                 'aria-label' => 'Editar',
                                 'data-pjax' => '0',
                                 'class' => 'btn btn-sm btn-info',
                             ]) : '';
                         }
                     ],

                     ],*/

                ],
            ]);
            ?>

<?php Pjax::end() ?>
        </div>
    </div>
</div>
