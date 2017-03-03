<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Feedback */

$this->title = "意见反馈详情#".$model->id;
$this->params['breadcrumbs'][] = ['label' => '意见反馈列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'opinion',
            'phone',
			[
                'attribute' => 'picture',
                'value' => $model->picture?Html::img(Yii::$app->params['www'].str_replace("'","",$model->picture)):'',
				'format' => 'raw',
            ],
			[
				'attribute'=>'uid',
				'value'=>$model->user?$model->user->username:'',
			],
			'createtime', 
        ],
    ]) ?>

</div>
