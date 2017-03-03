<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\HousingPrice;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\HousingPriceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '评估管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="housing-price-index">

    <?php
     Pjax::begin([
         'id' => 'post-grid-pjax',
     ])
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id', 
			[
				'attribute' => 'city', 
				'headerOptions' => ['width' => '60'],
			],
			[
				'attribute' => 'district', 
				'headerOptions' => ['width' => '80'],
			],
			[
				'attribute' => 'address', 
				'headerOptions' => ['width' => '220'],
			],
			[
				'attribute' => 'size', 
				'format' => 'raw',
				'value' => function ($data) {
					return $data->size."M<sup>2</sup>";
				},
				'headerOptions' => ['width' => '70'],
			],
			[
				'attribute' => 'buildingNumber', 
				'value' => function ($data) {
					return $data->buildingNumber."号";
				},
				'headerOptions' => ['width' => '70'],
			],
			[
				'attribute' => 'unitNumber', 
				'value' => function ($data) {
					return $data->unitNumber."室";
				},
				'headerOptions' => ['width' => '70'],
			],
            [
				'label' => '楼层', 
				'value' => function ($data) {
					return $data->floor."/".$data->maxFloor;
				},
				'headerOptions' => ['width' => '50'],
			], 
			[
				'attribute' => 'create_time', 
				'value' => function ($data) {
					return date('Y-m-d H:i:s',$data->create_time);
				},
				'headerOptions' => ['width' => '160'],
			],
			[
				'attribute' => 'totalPrice', 
				'value' => function ($data) {
					if($data->code==200){
						return $data->totalPrice?(round($data->totalPrice/10000)."万"):'';
					}else{
						return '评估失败';
					}
					
				},
				'headerOptions' => ['width' => '90'],
			],
			[
                'attribute' => 'userid',
                // 'filter' => common\models\User::getUsersList(),
                'value' => function (HousingPrice $model) { 
					if($model->userinfo){
						$arr = explode("_",$model->userinfo['username']);
						return count($arr)==3?$arr[1]:$model->userinfo['username'];
					}
                },
                'format' => 'raw',
                'options' => ['style' => 'width:175x'],
            ],

            [
			'class' => 'yii\grid\ActionColumn',
			'header' => '操作',
			'template' => '{view}',
			
			'headerOptions' => ['width' => '50'],
			], 
        ],
    ]); ?>
	<?php Pjax::end() ?>	
</div>
