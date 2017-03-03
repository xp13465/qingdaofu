<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Policy;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PolicySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '保函管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="policy-index">
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
	 
			[
				'attribute' => 'orderid',
				'options' => ['style' => 'width:210px'],
			],
			[
				'attribute' => 'anhao',
				'options' => ['style' => 'width:10%'],
			],
			[
				'attribute' => 'phone',
				'options' => ['style' => 'width:10%'],
			],
			
			[
				'attribute' => 'fayuan_name',
				'options' => ['style' => 'width:12%'],
			], 
			/*[
                'attribute' => 'type',
                'filter' => Policy::$type,
                'value' => function (Policy $model) {
                    $type = Policy::$type;
                    return isset($type[$model->type])?$type[$model->type]:'';
                },
                'format' => 'raw',
                'options' => ['style' => 'width:80px'],
            ],
			[
				'attribute' => 'fayuan_address',
				 'options' => ['style' => 'width:10%'],
			],*/
			[
				'attribute' => 'money',
				'label' => '保函金额',
				'value' => function ($data) {
					return ($data->money/10000).'万';
				},
				'format' => 'raw',
				'headerOptions' => ['width' => '80'],
			],
			[
				'attribute' => 'created_at',
				'label' => '申请时间',
				'value' => function ($data) {
					return date('Y-m-d H:i:s',$data->created_at);
				},
				'headerOptions' => ['width' => '100'],
			], 
			
			
			[
                'attribute' => 'created_by',
                // 'filter' => common\models\User::getUsersList(),
                'value' => function (Policy $model) { 
					if($model->createuser){
						$arr = explode("_",$model->createuser['username']);
						return count($arr)==3?$arr[1]:$model->createuser['username'];
					}
                },
                'format' => 'raw',
                'options' => ['style' => 'width:175x'],
            ],
            // 'updated_by',
			[
                'attribute' => 'status',
                'filter' => Policy::$status,
                'value' => function (Policy $model) {
                    $status = Policy::$status;
                    return isset($status[$model->status])?$status[$model->status]:'';
                },
                'format' => 'raw',
				'headerOptions' => ['width' => '90'],
            ], 
			[
			'class' => 'yii\grid\ActionColumn',
			'header' => '操作',
			'template' => '{view}&nbsp;&nbsp;{audit}',
			'buttons' => [
				'audit' => function ($url, $model, $key) {
					$html = '';
					switch($model->status){
						case 1:
							$html .= Html::a('审核通过', 'javascript:void(0)', ['class'=>'btnAudit' , 'title' => '审核通过','curid' => $model->id,'curstatus' => $model->status,'tostatus' => '10' ] );
							break;                                                        
						case 10:                                                          
							$html .= Html::a('协议签订', 'javascript:void(0)', ['class'=>'btnAudit' , 'title' => '协议签订','curid' => $model->id,'curstatus' => $model->status,'tostatus' => '20' ] );
							break;                                                        
						case 20:                                                          
							$html .= Html::a('保函已出', 'javascript:void(0)', ['class'=>'btnAudit' , 'title' => '保函已出','curid' => $model->id,'curstatus' => $model->status,'tostatus' => '30' ] );
							break;
						case 30:
							$html .= Html::a('完成/退保', 'javascript:void(0)',['class'=>'btnAudit' , 'title' => '完成/退保','curid' => $model->id,'curstatus' => $model->status,'tostatus' => '40' ] );
							break;
					} 
					return $html;
					},
				],
			'headerOptions' => ['width' => '120'],
			],     
		],
    ]); ?>
	<?php Pjax::end() ?>
</div>

<script>
$(document).ready(function(){
	
	$(document).on("click",'.btnAudit',function(){
			var curstatus = $(this).attr("curstatus");
			var tostatus = $(this).attr("tostatus");
			var curid = $(this).attr("curid");
			var title = $(this).attr("title");
			var index = layer.prompt({title:"确认"+title+"?",formType:2 ,content:'审核备注：<br/><textarea class="layui-layer-input"></textarea>'},function(memo){
			var load = layer.load(1, {
			  shade: [0.4,'#fff'] //0.1透明度的白色背景
			});
			$.ajax({
				type: "POST",
				url: "<?php echo yii\helpers\Url::toRoute("audit") ?>",
				data: {id:curid, curstatus:curstatus, tostatus:tostatus, memo:memo},
				dataType: "json",
				success: function(data){
					layer.close(load)
					var alertindex = layer.alert(data.msg,function(){
						// location.reload()
						$(".form-control").eq(0).trigger("change")  
						layer.close(alertindex)
						
					})
					
                }
			});
			layer.close(index)
			return true;
		});
		
		
		
	})
	
})

</script>