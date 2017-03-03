<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SolutionsealSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '垫资解查封管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solutionseal-index">

 
    <p>
        <?= Html::a('销售进单', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
             [
				'class' => 'yii\grid\SerialColumn',
				'headerOptions' => ['width' => '50'],
			],

            [
				'attribute' => 'personnel_name',
				'value' =>  function ($data) {
					$html = $data->personnel_name;
					if($data->personnelName && $data->personnelName == $data->personnel_name){
						$html.='(员工)';
					}else if($data->personnelName){
						$html.="({$data->personnelName})";
					}else{
						$html.='(未关联)';
					}
					return $html;
				},
				'headerOptions' => ['width' => '120'],
			],
			[
				'attribute' => 'entrust_other',
				'value' => "entrust_other",
				'headerOptions' => ['width' => '120'],
			],
			[
				'attribute' => 'account',
				'value' => function ($data) {
					if($data->type=='1'){
						return $data->typenum/10000 .'万'.' 固定费用';
					}else if($data->type=='2'){
						return round($data->typenum) .'%'.' 代理费率';
					}
				},
				'headerOptions' => ['width' => '70'],
			],
			
			[
				'attribute'=>'typenum',
				'label'=>'服务佣金',
				'value'=>"fuwufei",
				'options' => ['style' => 'width:110px;'],
			],	
			[
				'attribute' => 'earnestmoney',
				'value' => function ($data) {
					return $data->earnestmoney>0?($data->actualamount.'元'):'';
				},
				'headerOptions' => ['width' => '80'],
			],
			[
				'attribute' => 'pact',
				'format' => 'raw', 
				'value' => function ($data) {
					$pact = $data->getPact();
					if($pact){
						return Html::a('查看','javascript:void(0)', ['class'=>'btnLates btn btn-info btn-xs',"data-pact"=>$pact , 'title' => '查看' ] );
					}else{
						return '';
					}
				},
				'headerOptions' => ['width' => '80'],
			],
			[
                'attribute' => 'status',
				'label' => '状态',
                'filter' => \app\models\Solutionseal::$status,
                'value' => function ($data) {
					$status = \app\models\Solutionseal::$status;
					return isset($status[$data->status])?$status[$data->status]:"";
					 
                },
                'format' => 'raw',
				'options' => ['style' => 'width:100px;'],
            ],
			[
				'attribute' => 'productid',
				'filter'=>["1"=>"未生成","2"=>"已生成"],
				'format' => 'raw', 
				'value' => function ($data) {
					$html = "";
					if($data->productid){
						$html.=   "".Html::a('查看', ["/products/view","id"=>$data->productid], ['target'=>'_blank','class'=>'btn btn-info btn-xs' ,"data-productid"=>$data->productid , 'title' => '查看' ] );
					}elseif($data->status=="40"){
						 $html.=   "".Html::a('生成', 'javascript:void(0)', ['class'=>'btnGenerate btn btn-info btn-xs' ,"data-solutionsealid"=>$data->solutionsealid, 'title' => '生成' ] );
					}else{
						$html.=   "";
					}
					return $html;
				},
				'headerOptions' => ['width' => '60'],
			],
			[
				'attribute' => 'number',
				'value' => function ($data) {
					return $data->number;
				},
				'headerOptions' => ['width' => '140'],
			],
			[
				'attribute' => 'borrowmoney',
				'value' => function ($data) {
					return $data->borrowmoney>0?($data->borrowmoney.'元'):'';
				},
				'headerOptions' => ['width' => '90'],
			],
			[
				'attribute' => 'backmoney',
				'value' => function ($data) {
					return $data->backmoney>0?($data->backmoney.'元'):'';
				},
				'headerOptions' => ['width' => '90'],
			],
			[
				'attribute' => 'actualamount',
				'value' => function ($data) {
					return $data->actualamount>0?($data->actualamount.'元'):'';
				},
				'headerOptions' => ['width' => '90'],
			],
			[
				'attribute' => 'payinterest',
				'value' => function ($data) {
					return $data->payinterest>0?($data->payinterest.'元'):'';
				},
				'headerOptions' => ['width' => '90'],
			],
			[
				'attribute' => 'closeddate',
				'value' => function ($data) {
					return $data->closeddate!="0000-00-00"?date('Y-m-d',$data->closeddate):"";
				},
				'headerOptions' => ['width' => '100'],
			],
			
             ['class' => 'yii\grid\ActionColumn',
			 'header'=>'操作',
			 'template'=>'{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}{diy}',
			 'buttons'=>[
				'view'=>function($url,$data){
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>','/solutionseal/view/'.$data->solutionsealid, ['title' => '跟新','data-id'=>$data->solutionsealid]);
				},			 
				'update'=>function($url,$data){
					if($data->validflag == '1'&& !$data->status){
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>','/solutionseal/update/'.$data->solutionsealid, ['title' => '跟新','data-id'=>$data->solutionsealid]);
					}
				},	
				'delete'=>function($url,$data){
					if($data->validflag == '1'&& !$data->status){
						return Html::a('<span class="glyphicon glyphicon-trash"></span>','javascript:void(0);', ['title' => '注销','class'=>'delete yangshi','data-validflag'=>'0','data-id'=>$data->solutionsealid]);
					}
				},
				'diy'=>function($url,$data){
					if($data->validflag == '0'&& !$data->status){
						return Html::a('<span class="glyphicon glyphicon-refresh"></span>','javascript:void(0);', ['title' => '恢复','class'=>'delete yangshi','data-validflag'=>'1','data-id'=>$data->solutionsealid]);
					}
				},
			
			],
			'options' =>['style' => 'width:100px;'],
        ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
<script>
	$(document).ready(function(){
		 $(document).on('click','.delete',function(){
			 var id = $(this).attr('data-id');
			 var validflag = $(this).attr('data-validflag');
			 var obj = $(this);
		     var load = layer.load(1,{shade:[0.4,'#fff']});
			 layer.confirm(validflag=='0'?'是否需要删除该笔产品':'确定是否恢复该笔产品',
			 {
				 title:false,
				 conseBtn:false,
				 btn:['确定','取消'],
			 },function(){
				 $.ajax({
					 url:'<?= yii\helpers\Url::toRoute('solutionseal/delete') ?>',
					 type:'post',
					 data:{id:id,validflag:validflag},
					 dataType:'json',
					 success:function(json){
						 if(json.code == '0000'){
								layer.msg(json.msg,{time:2000},function(){
									if(validflag == '1'){
										obj.children('span').removeClass('glyphicon glyphicon-refresh').addClass('glyphicon glyphicon-trash');
										obj.attr('data-validflag','0')	
									}else{
										obj.children('span').removeClass('glyphicon glyphicon-trash').addClass('glyphicon glyphicon-refresh');
										obj.attr('data-validflag','1')
																
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
			layer.close(load);
		 })
		 
	$(document).on('click','.btnLates',function(){
		 var data = $(this).attr('data-pact');
		 if(!data)return false;
			var data = JSON.parse($(this).attr('data-pact'));
		 var html = '';
		 $.each(data, function (index,obj) {
			 html +='<img src="'+obj.file+'" class="imgView" style="float:left;margin:20px 0px 0px 20px;width:100px; height:100px;">';
            });
			layer.confirm('',
				{
				type: 1,
				skin:'blue',
				title: '服务合同',
				content: html,
				area: ['385px', '295px'],
				shade: 0.5,
				btn:false,
				closeBtn: 1,
				});
	 })

	$(document).on("click",".btnGenerate",function(){
		var id = $(this).attr("data-solutionsealid");
		if(!id)return false;
		$.ajax({
					 url:'<?= yii\helpers\Url::toRoute('solutionseal/generate') ?>',
					 type:'post',
					 data:{id:id},
					 dataType:'json',
					 success:function(json){
						 if(json.code == '0000'){
								layer.msg(json.msg,{time:2000},function(){
									// obj.children('span').removeClass('glyphicon glyphicon-trash').addClass('glyphicon glyphicon-refresh');
									// obj.attr('data-validflag','1')
									 
								});
								layer.close(load);
						}else{
								layer.msg(json.msg);
								layer.close(load)
						}
					 }
				 })
	})
})
</script>
