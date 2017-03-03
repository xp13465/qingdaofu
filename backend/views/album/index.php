<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AlbumSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '新闻列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="album-index">
     <p>
        <?= Html::a('添加文章', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'title',
			['attribute'=>'catalog_id',
			 'filter' => \app\models\Album::$catalog,
			 'value'=>function($data){
				 return \app\models\Album::$catalog[$data['catalog_id']];
			 },
				'options' => ['style' => 'width:80px;'],
			],
			['attribute'=>'commend',
			 'filter' => \app\models\Album::$commend,
			 'value'=>function($data){
				 return isset($data['commend'])&&$data['commend']=='Y'?'是':'否';
			 },
				'options' => ['style' => 'width:50px;'],
			],
			
			
			['attribute'=>'top_line',
			 'filter' => \app\models\Album::$top_line,
			 'value'=>function($data){
				 return isset($data['top_line'])&&$data['top_line']=='Y'?'是':'否';
			 },
				'options' => ['style' => 'width:50px;'],
			],
			['attribute'=>'status',
			 'filter' => \app\models\Album::$status,
			 'value'=>function($data){
				 return isset($data['status'])&&$data['status']=='Y'?'显示':'隐藏';
			 },
				'options' => ['style' => 'width:80px;'],
			],
			[
				'attribute'=>'view_count',
				'filter' => "",
				'value'=>function($data){
				 return $data->view_count;
				},
				'options' => ['style' => 'width:80px;'],
			],
            ['attribute'=>'create_time',
			'label'=>'发布时间',
			'value' => function ($data) {
					return date('Y-m-d H:i:s',$data->create_time);
				},
				'options' => ['style' => 'width:160px;'],
			],
            ['class' => 'yii\grid\ActionColumn',
			 'header'=>'操作',
			 'template'=>'{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}',
			 'buttons'=>[
				'delete'=>function($url,$data){
					if($data->validflag == '1'){
						return Html::a('<span class="glyphicon glyphicon-trash"></span>','javascript:void(0);', ['title' => '删除','class'=>'delete','data-id'=>$data->id]);
					}
				}
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
