<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Album */

$this->title = '新闻内容#'.$model->id;
$this->params['breadcrumbs'][] = ['label' => '新闻列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="album-view">
    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('删除','javascript:void(0);', ['class'=>'btn btn-danger delete','data-id'=>$model->id]);?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
			['attribute'=>'user_id',
			 'value'=>isset($model['certification'])&&$model['certification']['name']?$model['certification']['name']:(isset($model['user'])&&$model['user']['realname']?$model['user']['realname']:$model['user']['username']),
			],
            'title',
            'title_second',
			['attribute'=>'catalog_id',
			 'value'=>\app\models\Album::$catalog[$model['catalog_id']],
			],
            'special_id',
            'copy_from',
            'copy_url:url',
            'redirect_url:url',
            'tags',
            'view_count',
			['attribute'=>'commend',
			 'value'=>isset($model['commend'])&&$model['commend']=='Y'?'是':'否',
			],
			['attribute'=>'attach_file',
			 'value'=>isset($model['files']['file'])?Html::img($model['files']['file'],['style'=>'width:50px;height:50px;']):'',
			 'format' => 'raw', 
			],
            'favorite_count',
            'attention_count',
			['attribute'=>'top_line',
			 'value'=>isset($model['top_line'])&&$model['top_line']=='Y'?'是':'否',
			],
            'reply_count',
			['attribute'=>'reply_allow',
			 'value'=>isset($model['reply_allow'])&&$model['reply_allow']=='Y'?'是':'否',
			],
            'sort_order',
			['attribute'=>'status',
			 'value'=>isset($model['status'])&&$model['status']=='Y'?'显示':'隐藏',
			],
			['attribute'=>'validflag',
			 'value'=>isset($model['validflag'])&&$model['validflag']=='1'?'未回收':'已回收',
			],
			['attribute'=>'create_time',
			'label' => '创建时间',
			'value'=> isset($model['create_time'])?date('Y-m-d H:i:s',$model['create_time']):'',
			],
			['attribute'=>'update_time',
			'label' => '修改时间',
			'value'=> isset($model['update_time'])?date('Y-m-d H:i:s',$model['update_time']):'',
			],
        ],
    ]) ?>

</div>
<script>
$(document).ready(function(){
	$(document).on('click','.delete',function(){
		var id = $(this).attr('data-id');
		//alert(productid);die;
		var load = layer.load(1, {
			  shade: [0.4,'#fff'] //0.1透明度的白色背景
			});
		layer.confirm('是否需要删除该笔产品',
		{	title:false,
			closeBtn:false,
			btn:['确认','取消'],
		},function(){
			$.ajax({
				url:'<?= yii\helpers\Url::toRoute('album/delete')?>',
				type:'post',
				data:{id:id},
				dataType:'json',
				success:function(json){
					if(json.code == '0000'){
						layer.msg(json.msg,{time:2000},function(){window.location.reload()});
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
