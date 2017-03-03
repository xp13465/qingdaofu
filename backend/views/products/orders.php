<?php

use yii\helpers\Html; 
use yii\grid\GridView;
use yii\widgets\Pjax; 

/* @var $this yii\web\View */ 
/* @var $searchModel app\models\ProductApplySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */ 

$this->title = '接单列表'; 
$this->params['breadcrumbs'][] = $this->title; 
?> 
<div class="product-apply-index"> 
	<?php
	 Pjax::begin([
		 'id' => 'post-grid-pjax',
	 ]);
	?>
    <?= GridView::widget([ 
        'dataProvider' => $dataProvider, 
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号',
			 'options' => ['style' => 'width:50px;']],
            ['attribute'=>'applyid',
			 'options' => ['style' => 'width:80px;'],
			],
			['attribute'=>'productid',
			 'options' => ['style' => 'width:80px;'],
			],
			['attribute'=>'mobile',
			'label'=>'联系方式',
			'value'=>function($data){
				return $data->user->mobile;
			},
			'options' => ['style' => 'width:130px;'],
			],
			['attribute'=>'status',
			 'label'=>'状态',
			 'filter' => \app\models\ProductApply::$status,
			 'value'=>function($data){
				 $a =  \app\models\ProductApply::$ApplyStatus[$data->status];
				 if($data['product']['status'] == '30' && $data['status']=='40'){
					 $a = '已终止';
				 }else if($data['product']['status'] == '40' && $data['status']=='40'){
					 $a = '已结案';
				 }
				return $a ;
			 },
			 'format' => 'raw',
			 'options' => ['style' => 'width:110px;'],
			],
            ['attribute'=>'validflag',
			 'filter' => \app\models\ProductApply::$validflag,
			 'value'=>function($data){
				 return isset($data['validflag'])&&$data['validflag']=='1'?'未回收':'已回收';
			 },
				'options' => ['style' => 'width:100px;'],
			],
			['attribute'=>'create_at',
			 'label'=>'申请时间',
			 'value'=>function($data){
				 return date('Y-m-d H:i:s',$data->create_at);
			 },
				'options' => ['style' => 'width:180px;'],
			
			],
			[
			'class' => 'yii\grid\ActionColumn',
			'header' => '操作',
			'template' =>'{view}&nbsp;&nbsp;&nbsp;&nbsp;{delete}',
			'buttons'=>[
				'delete'=>function($url,$data){
					if($data->validflag == '1'){
						 return Html::a('<span class="glyphicon glyphicon-trash"></span>','javascript:void(0);', ['title' => '删除','class'=>'deleteOrder','data-applyid'=>$data->applyid]);
					}
				},
				'view'=>function($url,$data){
					$url = 'orders-list?id='.$data->applyid;
					 return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',$url, ['title' => '查看']);
				}
			],
				'options' => ['style' => 'width:60px;'],
			],
        ], 
    ]); ?> 
	
	 <?php Pjax::end() ?>
</div> 
<script>
$(document).ready(function(){
	$('.deleteOrder').click(function(){
				var applyid = $(this).attr('data-applyid');
				layer.confirm("确定删除？",{title:false,closeBtn:false},function(){
					var index = layer.load(1, {
						time:2000,
							shade: [0.4,'#fff'] //0.1透明度的白色背景
					});
					$.ajax({
						url:'<?= yii\helpers\Url::toRoute("/products/apply-del") ?>',
						type:'post',
						data:{applyid:applyid},
						dataType:'json',
						success:function(json){
							layer.close(index)
							if(json.code == '0000'){
								layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>删除成功",{time:2000},function(){window.location.reload()});
								layer.close(index);
							}else{
								layer.msg(json.msg);
								layer.close(index);
							}
						}
					})
				})
			})	
})
</script>