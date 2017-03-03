<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Personnel */

$this->title = '员工内容#'.$model->id;
$this->params['breadcrumbs'][] = ['label' => '员工列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
 // var_dump($model->post);die;
?>
<div class="personnel-view">
    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a($model->validflag=='1'?'删除':'恢复','javascript:void(0);', [
            'class' => 'btn btn-danger',
			'data-id'=>$model->id,
			'data-type'=>$model->validflag=='1'?'1':'2',
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
			['attributes'=>'name',
			 'label'=>'姓名',
			 'value'=>$model->name,
			],
			['attributes'=>'headimg',
			 'label'=>'头像',
			 'format' => 'raw', 
			 'value'=>isset($model->files)&&$model->files->file?'<p><img  class="imageWxview" style="float: left;width: 50px;height: 50px;border-radius: 50%;margin-right: 10px; margin-top: -2px;" src="'.$model->files->file .'" align="absmiddle"></p>':'',
			],
			['attributes'=>'job',
			 'label'=>'职业描述',
			 'value'=>$model->job,
			],
			['attributes'=>'mobile',
			 'label'=>'联系方式',
			 'value'=>$model->mobile,
			],
			['attributes'=>'tel',
			 'label'=>'固话',
			 'value'=>$model->tel,
			],
			['attributes'=>'email',
			 'label'=>'邮箱',
			 'value'=>$model->email,
			],
			['attributes'=>'address',
			 'label'=>'地址',
			 'value'=>$model->address,
			],
			['attributes'=>'parentid',
			 'label'=>'直属上级领导',
			 'value'=>$model->parent?$model->parent->name:'',
			],
			['attributes'=>'organization_id',
			 'label'=>'机构',
			 'value'=>isset($model->organization)&&$model->organization->organization_name?$model->organization->organization_name:'',
			],
			['attributes'=>'department_pid',
			 'label'=>'部门',
			 'value'=>isset($model->departmentPid)&&$model->departmentPid->name?$model->departmentPid->name:'',
			],
			['attributes'=>'department_id',
			 'label'=>'二部门',
			 'value'=>isset($model->departmentId)&&$model->departmentId->name?$model->departmentId->name:'',
			],
			['attributes'=>'post_id',
			 'label'=>'岗位',
			 'value'=>isset($model->post)&&$model->post->department_id=='0'?$model->post->name:(isset($model->post)&&$model->post->department_id!=='0'?$model->post->name:''),
			],
			
			['attributes'=>'checkin',
			 'label'=>'是否统计考勤',
			 'value'=>$model->checkin?'是':'否',
			],
			['attributes'=>'validflag',
			 'label'=>'是否回收',
			 'value'=>isset($model->validflag)&&$model->validflag=='1'?'正常':'已回收',
			],
			['attributes'=>'created_at',
			 'label'=>'创建时间',
			 'value'=>date('Y-m-d H:i',$model->created_at),
			],
            ['attributes'=>'updated_at',
			 'label'=>'修改时间',
			 'value'=>date('Y-m-d H:i',$model->updated_at),
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
