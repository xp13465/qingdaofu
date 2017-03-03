<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ServicesAgreement;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ServicesAgreementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '法律服务-合同起草';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="services-agreement-index">
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
                'attribute' => 'type',
                'filter' => ServicesAgreement::$type,
                'value' => function (ServicesAgreement $model) {
                    $type = ServicesAgreement::$type;
                    return isset($type[$model->type])?$type[$model->type]:'';
                },
                'format' => 'raw',
				'headerOptions' => ['width' => '90'],
            ],
			[
				'attribute' => 'contacts',
				'value' => function ($data) {
					return $data->contacts;
				},
				'headerOptions' => ['width' => '100'],
			],
			[
				'attribute' => 'tel',
				'value' => function ($data) {
					return $data->tel;
				},
				'headerOptions' => ['width' => '110'],
			],
			[
				'attribute' => 'desc',
				'value' => function ($data) {
					return Html::a("详情","javascript:void(0)",["class"=>"layertips","data-title"=>nl2br($data->desc)]);
				},
				'format' => 'raw',
				// 'headerOptions' => ['width' => '360'],
			], 
			[
                'attribute' => 'status',
                'filter' => ServicesAgreement::$status,
                'value' => function (ServicesAgreement $model) {
                    $status = ServicesAgreement::$status;
                    return isset($status[$model->status])?$status[$model->status]:'';
                },
                'format' => 'raw',
				'headerOptions' => ['width' => '90'],
            ],
            // 'validflag',
			[
                'attribute' => 'create_user',
                // 'filter' => common\models\User::getUsersList(),
                'value' => function (ServicesAgreement $model) { 
					if($model->createuser){
						$arr = explode("_",$model->createuser['username']);
						return count($arr)==3?$arr[1]:$model->createuser['username'];
					}
                },
                'format' => 'raw',
				'headerOptions' => ['width' => '100'],
                'options' => ['style' => 'width:105x'],
            ],
			[
				'attribute' => 'create_time',
				'value' => function ($data) {
					return date('Y-m-d H:i:s',$data->create_time);
				},
				'headerOptions' => ['width' => '100','min-width' => '150','max-width' => '200'],
			],
			
            // 'modify_user',
            // 'modify_time:datetime',

            [
			'class' => 'yii\grid\ActionColumn',
			'header' => '操作',
			'template' => '{agreement-view}', 
			'buttons' => [
					// 下面代码来自于 yii\grid\ActionColumn 简单修改了下
					'agreement-view' => function ($url, $model, $key) {
						$options = [
							'title' => Yii::t('yii', 'View'),
							'aria-label' => Yii::t('yii', 'View'),
							'data-pjax' => '0',
						];
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
					},
				],
			'headerOptions' => ['width' => '50'],
			],
        ],
    ]); ?>
	<?php Pjax::end() ?>
</div>
<script>
$(document).ready(function(){
	var curTips ="";
	$(document).on("click",".layertips",function(){
		var title =$(this).attr("data-title");
		layer.tips(title, $(this), {
		  tips: [1, '#3595CC'],
		  time: 4000
		});
	}).on("mouseover",".layertips",function(){
		var title =$(this).attr("data-title");
		curTips = layer.tips(title, $(this), {
		  tips: [1, '#3595CC'],
		  time: 4000
		});
	}).on("mouseout",".layertips",function(){
		if(curTips){
			layer.close(curTips)
			curTips = '';
		}
	})
	
})
</script>
