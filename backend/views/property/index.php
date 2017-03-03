<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Property;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PropertySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
// echo "<pre>";
// print_r($searchModel);
// var_dump($_GET);

$this->title = '产调管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<script type="text/javascript" src="/My97DatePicker/WdatePicker.js" /></script> 
<div class="property-index">
	<?php
     Pjax::begin([
         'id' => 'post-grid-pjax',
     ])
    ?>
	<form id='post-grid-pjax-form'>
	<button type="button" class="btn btn-2g btn-primary" id='excel'>导出显示列表</button>
	<input name='sort' type='hidden' value='<?=isset($_GET['sort'])?$_GET['sort']:''?>'>
	 
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'layout' => "{summary}\n{items}\n{pager}\n{summary}",
		'pager'=>['firstPageLabel'=>true,'lastPageLabel'=>true],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[
				'attribute' => 'id',
				'label' => '订单号（ID检索）',
				'format' => 'raw',
				'value' => function ($data) {
					$ordersid = date("Ymd",$data->time)."<a href='/property/{$data->id}'>".str_pad($data->id,6,"0",STR_PAD_LEFT)."</a>";
					return $ordersid;
				},
				'headerOptions' => ['width' => '150'],
			],
			
			[
				'attribute' => 'orderId',
				'value' => function ($data) {
					return $data->orderId;
				},
				'headerOptions' => ['width' => '200'],
			],
			[
				'attribute' => 'province',
				'value' => function ($data) {
					return $data->provincename?$data->provincename->name:$data->province;
				},
				'headerOptions' => ['width' => '50'],
			], 
			[
				'attribute' => 'city',
				'value' => function ($data) {
					return $data->cityname?$data->cityname->name:$data->city;
				},
				'headerOptions' => ['width' => '80'],
			], 
			[
				'attribute' => 'address',
				'value' => function ($data) {
					return $data->address;
				},
				'headerOptions' => ['width' => '240'],
			], 
            'phone',
            // 'money',
			[
				'attribute' => 'time',
				'filter' => "
				<p style='width:120px;'>从：<input style='width:90px;display:inline-block' id='start' class='form-control CWdate'  value= '{$searchModel->start}'type='text' name='start' /></p>
				<p style='width:120px;margin-bottom:0'>到：<input style='width:90px;display:inline-block' id='end' class='form-control CWdate'  value= '{$searchModel->end}'type='text' name='end' /></p>
				",
				'value' => function ($data) {
					return date('Y-m-d H:i:s',$data->time);
				},
				'headerOptions' => ['min-width' => '150'],
			],
			// [
				// 'attribute' => 'uptime',
				// 'value' => function ($data) {
					// return $data->uptime?date('Y-m-d H:i:s',$data->uptime):'';
				// },
				// 'headerOptions' => ['width' => '100'],
			// ],
            [
                'attribute' => 'status',
                'filter' => Property::$status,
                'value' => function (Property $model) {
                    $status = Property::$status;
                    return isset($status[$model->status])?$status[$model->status]:'';
                },
                'format' => 'raw',
				'headerOptions' => ['width' => '90'],
            ],
			[
				'attribute' => 'kdorder',
				'value' => function ($data) {
					return $data->expressdata?$data->expressdata->orderId:'';
				},
				'headerOptions' => ['width' => '100'],
			],
            // 'orderId',

            [
			'class' => 'yii\grid\ActionColumn',
			'header' => '操作',
			'template' => '{view}&nbsp;{action}',
			'buttons' => [
				'action' => function ($url, $model, $key) {
					$html = '';
					switch($model->status){
						case -1:
							$html .= Html::a('仟房', 'javascript:void(0)', ['class'=>'btnAudit qfcd btn btn-primary btn-xs' , 'title' => '仟房', 'rel' => $model->id] );
							$html .= "&nbsp;";
							$html .= Html::a('本地', ['/property/setbd','od'=>$model->orderId], ['class'=>'btnAudit btn btn-primary btn-xs' , 'title' => '本地' ] );
							break;                                                        
						case 3:                                                          
							$html .= Html::a('本地', ['/property/setbd','od'=>$model->orderId], ['class'=>'btnAudit btn btn-primary btn-xs' , 'title' => '本地'] );
							break;                                                        
						case 2:      
							if($model->expressdata&&!$model->expressdata->orderId){
								$html .= Html::a('快递', ['/property/express','id'=>$model->id], ['class'=>'btnAudit btn btn-info btn-xs' , 'title' => '快递' ] );
							}
							break;
					} 
					
					return $html;
					},
				],
			'headerOptions' => ['width' => '130'],
			], 
        ],
    ]); ?>
	</form>
	<?php Pjax::end() ?>
</div>
<script>
$(document).ready(function(){
	$(document).on("click",'.CWdate',function(){
		WdatePicker({onpicked:function(){$(this).trigger("change")},onclearing:function(){$(this).val("").trigger("change")}})
		// $(".form-control").eq(0).trigger("change");
	})
	$(document).on("click",'.qfcd',function(){
		var id = $(this).attr('rel');
		if(id){
			var confirmindex = layer.confirm("仟房操作确认？",{"title":'提示'},function(){
				layer.close(confirmindex)
				$.post('/property/setqf',{id:id},function(s){
					$(".form-control").eq(0).trigger("change");
					if(s.status == 1){
						var msg='操作成功...';
					}else{
						var msg='操作失败...';
					}
					var alertindex = layer.alert(s.info,function(){ 
						$(".form-control").eq(0).trigger("change")  
						layer.close(alertindex)
					})
				},'json');
			})
		}
		/*
		
		if(id){
			$.post('/property/setqf',{id:id},function(s){
				$(this).attr('rel','');
				if(s.status == 1){
					alert('操作成功...');
					
					// self.location = '/property/index';
				}else{
					alert('操作失败...');
				}
			},'json');
		}*/
	});
	$(document).on("click",'#excel',function(){
		params = $("#post-grid-pjax-form").serialize()
		window.open('/property/outputnew?'+params);
		return ;
		var start = $('#start').val();
		var end = $('#end').val();
		if(! start || ! end){
			alert('请选择开始结束时间');
			return;
		}
		window.open('/property/output?s='+start+'&e='+end);
	});
})
</script>
