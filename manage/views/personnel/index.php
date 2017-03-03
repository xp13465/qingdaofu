<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PersonnelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '员工列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personnel-index">
    <p>
        <?= Html::a('新增员工', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号','options'=>['style'=>'width:60px;']],
            ['attribute'=>'id',
			 'value'=>function($data){
				 return $data->id;
			 },
			 'options'=>['style'=>'width:70px;'],
			],
			['attribute'=>'name',
			 'label'=>'姓名',
			 'value'=>function($data){
				 return $data->name;
			 },
			 'options'=>['style'=>'width:80px;']
			],
			['attribute'=>'job',
			 'label'=>'职业描述',
			 'value'=>function($data){
				 return $data->job;
			 },
			 'options'=>['style'=>'width:120px;']
			],
			['attribute'=>'employeeid',
			 'label'=>'工号',
			 'value'=>function($data){
				 return $data->employeeid;
			 },
			 'options'=>['style'=>'width:100px;']
			],
			['attribute'=>'mobile',
			 'label'=>'联系方式',
			 'value'=>function($data){
				 return $data->mobile;
			 },
			 'options'=>['style'=>'width:120px;']
			],
			['attribute'=>'validflag',
			 'filter' => \app\models\personnel::$validflag,
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
			 'options'=>['style'=>'width:130px;']
			],
			
            ['class' => 'yii\grid\ActionColumn',
			 'header'=>'操作',
			 'template'=>'{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}{diy}',
			 'buttons'=>[
				'delete'=>function($url,$data){
					if($data->validflag == '1'){
						return Html::a('<span class="glyphicon glyphicon-trash"></span>','javascript:void(0);', ['title' => '注销','class'=>'delete yangshi','data-type'=>'1','data-id'=>$data->id]);
					}
				},
				'diy'=>function($url,$data){
					if($data->validflag == '0'){
						return Html::a('<span class="glyphicon glyphicon-refresh"></span>','javascript:void(0);', ['title' => '恢复','class'=>'delete yangshi','data-type'=>'2','data-id'=>$data->id]);
					}
				},
			 ],
			 'options' => ['style' => 'width:80px;'],
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
		layer.confirm(type==1?'是否需要删除该员工信息':'确定是否恢复该员工信息',
		{	title:false,
			closeBtn:false,
			btn:['确认','取消'],
		},function(){
			$.ajax({
				url:'<?= yii\helpers\Url::toRoute('personnel/delete')?>',
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
