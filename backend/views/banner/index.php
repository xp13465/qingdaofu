<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Banner;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BannerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "横幅广告";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-index">


    <p>
        <?= Html::a("添加", ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
				'class' => 'yii\grid\SerialColumn',
				'headerOptions' => ['width' => '20']
			],
			[
                'attribute' => 'title',
                'headerOptions' => ['width' => '100'],
            ],
			[
                'attribute' => 'url',
                'headerOptions' => ['width' => '200'],
            ],
			[
                'attribute' => 'fileid',
                'value' => function ($model) {
                     
                    return '<img class="imgView" width="200px"  src="'.Yii::$app->params['www'].$model->file.'"/>';
                },
                'format' => 'raw',
                'headerOptions' => ['width' => '200'],
            ],
           
			[
                'attribute' => 'type',
                'filter' => Banner::$type,
                'value' => function (Banner $model) {
                    $type = Banner::$type;
                    return isset($type[$model->type])?$type[$model->type]:'';
                },
                'format' => 'raw',
                'headerOptions' => ['width' => '100'],
            ],
			[
				'attribute' => 'sort',
				'headerOptions' => ['width' => '80'],
			],
			[
                'attribute' => 'starttime',
                'filter' => '',
                'value' => function ($model) {
                    return $model->starttime?date("Y-m-d H:i",$model->starttime):"永久";
                },
               'headerOptions' => ['width' => '150'],
            ],
			[
                'attribute' => 'endtime',
				'filter' => '',
                'value' => function ($model) {
                    return $model->endtime?date("Y-m-d H:i",$model->endtime):"永久";
                },
				'headerOptions' => ['width' => '150'],
            ],
            // 'starttime:datetime',
            // 'endtime:datetime',
            // 'validflag',
            // 'create_at',
            // 'create_by',
            // 'modify_at',
            // 'modify_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
