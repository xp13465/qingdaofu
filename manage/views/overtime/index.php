<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AttendanceOvertimeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '加班单管理';
$this->params['breadcrumbs'][] = $this->title;
$action = Yii::$app->controller->action->id;


if($type == '1' ){ 
	$this->title = '我的请假单';
	$this->blocks['content-header'] = Html::a('我的加班单', ['index'], ['class' => 'btn btn-default active']);
	$this->blocks['content-header'].= "　".Html::a("待审核", ['audit'], ['class' =>isset($action)&&$action=='audit'?'btn btn-default active':'btn btn-default']);
	$this->blocks['content-header'].= "　".Html::a('已审核', ['audited-list'], ['class' =>isset($action)&&$action=='audited-list'?'btn btn-default active':'btn btn-default']);
	$this->blocks['content-header'].= "　".Html::a('添加加班单', ['create'], ['class' => 'btn btn-primary']);
 } 
$this->params['breadcrumbs'][] = $this->title;


if($type == '1'){
	$statu = '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}{diy}';
	$style = ['style' => 'width:80px;'];
	$data = [ 
				'update'=>function($url,$data){
					if($data->validflag == '1'&& !$data->status){
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>',$url, ['title' => '跟新','data-id'=>$data->id]);
					}
				},	
				'delete'=>function($url,$data){
					if($data->validflag == '1'&& !$data->status){
						return Html::a('<span class="glyphicon glyphicon-trash"></span>','javascript:void(0);', ['title' => '注销','class'=>'delete yangshi','data-type'=>'1','data-id'=>$data->id]);
					}
				},
				'diy'=>function($url,$data){
					if($data->validflag == '0'&& !$data->status){
						return Html::a('<span class="glyphicon glyphicon-refresh"></span>','javascript:void(0);', ['title' => '恢复','class'=>'delete yangshi','data-type'=>'2','data-id'=>$data->id]);
					}
				},
			
			];
}else{
	$statu = '{view}';
	$style = ['style' => 'width:10px;'];
	$data = [
		'view'=>function($url,$data){
					return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',$url.'?type=2', ['title' => '跟新','data-id'=>$data->id]);
					
				},	
	];
}


?>
<div class="attendance-overtime-index"> 
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            ['attribute'=>'username',
			 'value'=>function($data){
				 return $data->username;
			 },
			 'options' => ['width' => '110'],
			],
            ['attribute'=>'department',
			 'value'=>function($data){
				 return $data->department;
			 },
			 'options' => ['width' => '110'],
			],
			['attribute'=>'overtimehour',
			 'label'=>'加班时间',
			 'value'=>function($data){
				 return $data->overtimehour;
			 },
			 'options' => ['width' => '90'],
			],
			['attribute'=>'status',
			 'filter' => \app\models\AttendanceOvertime::$statusLabel,
			 'label'=>'状态',
			 'value'=>function($data){
				return $data->statusLabel;
			 },
			 'options' => ['width' => '130'],
			],
			['attribute'=>'toexamineid',
			 'label'=>'待操作人',
			 'value'=>function($data){
				 $data = $data->getOperationUser($data->toexamineid);
				 return $data;
			 },
			 'options' => ['width' => '130'],
			],
            ['attribute'=>'writedate',
			 'value'=>function($data){
				 return $data->writedate;
			 },
			  'options' => ['width' => '130'],
			],
			[
				'attribute' => 'description',
				'filter'=>"",
				'value' => function ($data) {
					return Html::a("详情","javascript:void(0)",["class"=>"layertips","data-title"=>nl2br($data->description)]);
				},
				'format' => 'raw',
				'options' => ['width' => '90'],
			], 
            ['attribute'=>'overtimestart',
			 'value'=>function($data){
				 return date('Y-m-d H:i',$data->overtimestart);
			 },
			 'options' => ['width' => '140'],
			],
			['attribute'=>'overtimeend',
			 'value'=>function($data){
				 return date('Y-m-d H:i',$data->overtimeend);
			 },
			 'options' => ['width' => '140'],
			],
            ['class' => 'yii\grid\ActionColumn',
			 'header'=>'操作',
			 'template'=>$statu,
			 'buttons'=>$data,
			'options' =>$style,
        ],
		],
    ]); ?>
	
<?php Pjax::end(); ?></div>
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
						
		 $(document).on('click','.delete',function(){
			 var id = $(this).attr('data-id');
			 var type = $(this).attr('data-type');
			 var obj = $(this);
		     var load = layer.load(1,{shade:[0.4,'#fff']});
			 layer.confirm(type==1?'是否需要删除该员工信息':'确定是否恢复该员工信息',
			 {
				 title:false,
				 conseBtn:false,
				 btn:['确定','取消'],
			 },function(){
				 $.ajax({
					 url:'<?= yii\helpers\Url::toRoute('overtime/delete') ?>',
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
