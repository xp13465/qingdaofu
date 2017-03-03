<?php

use yii\helpers\Html; 
use yii\grid\GridView; 
use yii\widgets\Pjax; 

$this->title = '部门列表'; 
$this->params['breadcrumbs'][] = $this->title; 

?> 
<div class="department-index"> 
    <p> 
        <?= Html::a('新增', ['department-create'], ['class' => 'btn btn-success']) ?> 
    </p> 
<?php Pjax::begin(); ?>    <?= GridView::widget([ 
        'dataProvider' => $dataProvider, 
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号','options'=>['style'=>'width:40px;']], 
            ['attribute'=>'id',
			 'label'=>'ID',
			 'value'=>function($data){
				 return $data->id;
			 },
			 'options'=>['style'=>'width:60px;'],
			],
			['attribute'=>'name',
			 'label'=>'部门名称',
			 'value'=>function($data){
				 return $data->name;
			 },
			  'options'=>['style'=>'width:100px;'],
			],
			['attribute'=>'organization_id',
			 'label'=>'机构',
			 'value'=>function($data){
				 return isset($data->organization)&&$data->organization->organization_name?$data->organization->organization_name:'';
			 },
			  'options'=>['style'=>'width:130px;'],
			],
			['attribute'=>'validflag',
			 'filter' => \app\models\Organization::$validflag,
			 'value'=>function($data){
				 return isset($data['validflag'])&&$data['validflag']=='1'?'否':'是';
			 },
				'options' => ['style' => 'width:80px;'],
			],
			['attribute'=>'created_at',
			 'label'=>'创建时间',
			 'value'=>function($data){
				 return date('Y-m-d H:i',$data->created_at);
			 },
			 'options'=>['style'=>'width:120px;']
			],

            ['class' => 'yii\grid\ActionColumn',
			 'header'=>'操作',
			 'template'=>'{department-view}&nbsp;&nbsp;&nbsp;&nbsp;{department-update}&nbsp;&nbsp;&nbsp;&nbsp;{department-delete}{diy}',
			 'buttons'=>[
				'department-view'=>function($url,$data){
						return Html::a(' <span class="glyphicon glyphicon-eye-open"></span>',['department-view','id'=>$data->id], ['title' => '查看','class'=>'department-view']);		
				},
				'department-update'=>function($url,$data){
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>',['department-update','id'=>$data->id], ['title' => '修改','class'=>'department-update']);		
				},
				'department-delete'=>function($url,$data){
					if($data->validflag == '1'){
						return Html::a('<span class="glyphicon glyphicon-trash"></span>','javascript:void(0);', ['title' => '删除','class'=>'delete yangshi','data-type'=>'1','data-id'=>$data->id]);		
					}
				},
				'diy'=>function($url,$data){
					if($data->validflag == '0'){
						return Html::a('<span class="glyphicon glyphicon-refresh"></span>','javascript:void(0);', ['title' => '恢复','class'=>'delete yangshi','data-type'=>'2','data-id'=>$data->id]);
					}
				},
			 ],
			 'options' => ['style' => 'width:60px;'],
			],
        ], 
    ]); ?> 
<?php Pjax::end(); ?></div> 
<script>
$(document).ready(function(){
	$(document).on('click','.delete',function(){
		var id = $(this).attr('data-id');
		var type = $(this).attr('data-type');
		var obj = $(this);
		var load = layer.load(1, {
			  shade: [0.4,'#fff']
			});
		layer.confirm(type==1?'是否需要删除该部门信息':'确定是否恢复该部门信息',
		{	title:false,
			closeBtn:false,
			btn:['确认','取消'],
		},function(){
			$.ajax({
				url:'<?= yii\helpers\Url::toRoute('personnel/department-delete')?>',
				type:'post',
				data:{id:id,type:type},
				dataType:'json',
				success:function(json){
					if(json.code == '0000'){
					layer.msg(json.msg,{time:2000},function(){
						if(type == 1){
							obj.children('span').removeClass('glyphicon glyphicon-trash').addClass('glyphicon glyphicon-refresh');
							obj.attr('data-type','2')
						}else{
							obj.children('span').removeClass('glyphicon glyphicon-refresh').addClass('glyphicon glyphicon-trash');
							obj.attr('data-type','1')							
						}
					});
					layer.close(load);
					}else{
						layer.msg('参数错误');
						layer.close(load)
					}
					
				}
			})
		},function(){
			layer.close(load);
		})
			
	})
})
</script>