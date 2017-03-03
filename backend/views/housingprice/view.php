<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\HousingPrice */

$this->title = "评估详情#".$model->id;
$this->params['breadcrumbs'][] = ['label' => '评估管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="housing-price-view">
 
 

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            
            'city',
            'district',
            'address',
			[
				'attribute'=>'buildingNumber',
				'value'=>$model->buildingNumber.'号',
			],
			[
				'attribute'=>'unitNumber',
				'value'=>$model->unitNumber.'室',
			],
			[
				'attribute'=>'size',
				'format' => 'raw',
				'value'=>$model->size.'M<sup>2</sup>',
			],
			[
				'attribute'=>'floor',
				'value'=>$model->floor.'楼',
			],
			[
				'attribute'=>'maxFloor',
				'value'=>$model->maxFloor.'楼',
			],
			
			[
				'attribute'=>'totalPrice',
				'value'=>$model->code==200?(round($model->totalPrice/10000).'万'):"评估失败",
			],
            'msg',
			[
				'attribute'=>'userid',
				'value'=>$model->userinfo?$model->userinfo->username:'',
			],
			[
				'attribute'=>'create_time',
				'value'=>$model->create_time?date("Y-m-d H:i:s",$model->create_time):'',
			],
			'ip',
			'code',
			'serviceCode',
            
            
            
        ],
    ]) ?>

</div>
