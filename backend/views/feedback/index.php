<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Feedback;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FeedbackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '意见反馈列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'opinion',
            'phone', 
            
			[
				'attribute' => 'picture',
				'value' => function ($data) {
					if($data->picture){
						return Html::a("查看",['view','id'=>$data->id],["daia-url"=>("http://".Yii::$app->params['www'].str_replace("'","",$data->picture))]);
					}
				},
				'format' => 'raw',
				'headerOptions' => ['width' => '80'],
			],
			[
                'attribute' => 'uid',
                // 'filter' => common\models\User::getUsersList(),
                'value' => function (Feedback $model) { 
					if($model->user){
						$arr = explode("_",$model->user['username']);
						return count($arr)==3?$arr[1]:$model->user['username'];
					}
                },
                'format' => 'raw',
                'options' => ['style' => 'width:175x'],
            ],
			'createtime', 
            [
			'class' => 'yii\grid\ActionColumn',
			'header' => '操作',
			'template' => '{view}', 
			'headerOptions' => ['width' => '120'],
			],
        ],
    ]); ?>
</div>
