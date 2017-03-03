<?php

use yii\helpers\Html; 
use yii\widgets\DetailView; 

/* @var $this yii\web\View */ 
/* @var $model app\models\Organization */ 

$this->title = '部门内容#'.$model->id; 
$this->params['breadcrumbs'][] = ['label' => '部门列表', 'url' => ['department-index']]; 
$this->params['breadcrumbs'][] = $this->title; 
// var_dump($model->pid);die;

?> 
<div class="department-view"> 
    <p> 
        <?= Html::a('修改', ['department-update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?> 
        <?= Html::a($model->validflag=='1'?'删除':'恢复','javascript:void(0);', [ 
            'class' => 'btn btn-danger', 
			'data-id'=>$model->id,
			'data-type'=>$model->validflag=='1'?'1':'2',
        ]) ?> 
    </p> 
    
    <?= DetailView::widget([ 
        'model' => $model, 
        'attributes' => [ 
		    ['attributes'=>'id',
			 'label'=>'ID',
			 'value'=>$model->id,
			],
			['attributes'=>'organization_id',
			 'label'=>'机构',
			 'value'=>isset($data->organization)&&$data->organization->organization_name?$data->organization->organization_name:'',
			],
			['attributes'=>'name',
			 'label'=>'部门',
			 'value'=>$model->pid =='0'?$model->name:$model->secondName,
			],
			['attributes'=>isset($model->pid)&&$model->pid?'pid':'',
				'label'=>isset($model->pid)&&$model->pid?'二级部门':'',
				'value'=>isset($model->pid)&&$model->pid?$model->name:'',
			],
			['attributes'=>'status',
			 'label'=>'状态',
			 'value'=>$model->status=='0'?'离职':($model->status=='1'?'在职':'无效'),
			],
			['attributes'=>'validflag',
			 'label'=>'回收状态',
			 'value'=>$model->validflag=='1'?'未回收':'已回收',
			],
			['attributes'=>'created_at',
			 'label'=>'创建时间',
			 'value'=>date('Y-m-d H:i',$model->created_at),
			],
			['attributes'=>'created_by',
			 'label'=>'创建人',
			 'value'=>isset($model->admin)&&$model->admin->username?$model->admin->username:'',
			],
        ], 
    ]) ?> 

</div> 
<script>
$(document).ready(function(){
	$(document).on('click','.btn-danger',function(){
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
							obj.html('恢复');
							obj.attr('data-type','2')
						}else{
							obj.html('删除');
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