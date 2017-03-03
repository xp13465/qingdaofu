<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\productStatus */
/* @var $dataProvider yii\data\ActiveDataProvider */

// echo "<pre>";
// print_r($dataProvider);die;
$this->title = '发布列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <button type="button" class="btn btn-2g btn-primary" onclick="window.open('<?php echo \yii\helpers\Url::to('/products/excel')?>')">导出</button><br /><br />
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
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
			['attribute'=>'productid',
			 'options' => ['style' => 'width:80px;'],
			],
			['attribute'=>'number',
			 'options' => ['style' => 'width:140px;'],
			],
			['attribute'=>'account',
			'label'=>'金额(万)',
			'value'=>function($data){
				return $data->account/10000;
			},
			'options' => ['style' => 'width:70px;'],
			],
			['attribute'=>'mobile',
			'label'=>'联系方式',
			'value'=>function($data){
				return $data->userMobile->mobile;
			},
			'options' => ['style' => 'width:120px;'],
			],
			
			['attribute'=>'create_at',
			'label'=>'发布时间',
			'value' => function ($data) {
					return date('Y-m-d H:i:s',$data->create_at);
				},
				'options' => ['style' => 'width:180px;'],
			],
			// ['attribute'=>'typenum',
			// 'label'=>'服务佣金',
			 // 'value'=>function($data){
				 // if($data->type=='1'){
					 // return $data->typenum/10000 .'万'.' 固定费用';
				 // }else if($data->type=='2'){
					 // return round($data->typenum) .'%'.' 代理费率';
				 // }
			 // },
				// 'options' => ['style' => 'width:130px;'],
			// ],
            [
                'attribute' => 'status',
				'label' => '状态',
                'filter' => \app\models\Product::$status,
                'value' => function ($data) {
					if(isset($data['productApplies'])&&!$data['productApplies']&&$data['status']=='10'){
							return '发布中';
						}else if(isset($data['productApplies'])&&$data['productApplies']){
							$a = '';
						    foreach($data['productApplies'] as $value){
								if($data['status']=='10'&&$value['status']=='20'){
									$statusApply = Yii::$app->request->get("ProductStatus");
									if($statusApply['status'] == '3'){
										$a = '面谈中';
									}else{
										$a = '发布中';
									}
									
								}else if(in_array($data['status'],['20'])){
									$a = '处理中';
								}else if(in_array($data['status'],['30'])){
									$a = '已终止';
								}else if(in_array($data['status'],['40'])){
									$a = '已结案';
								}else{
									$a = '已申请';
								}
							}
							return $a;
						}else if($data['status']=='0'){
							return '草稿';
						}
                },
                'format' => 'raw',
				'options' => ['style' => 'width:100px;'],
            ],
			['attribute'=>'validflag',
			 'filter' => \app\models\Product::$validflag,
			 'value'=>function($data){
				 return isset($data['validflag'])&&$data['validflag']=='1'?'未回收':'已回收';
			 },
				'options' => ['style' => 'width:100px;'],
			],
            [
			'class' => 'yii\grid\ActionColumn',
			'header' => '操作',
			'template' =>'{view}&nbsp;&nbsp;&nbsp;&nbsp;{delete}',
			'buttons'=>[
				'delete'=>function($url,$data){
					if($data->validflag == '1' && in_array($data->status,['0','10'])){
						 return Html::a('<span class="glyphicon glyphicon-trash"></span>','javascript:void(0);', ['title' => '删除','class'=>'delete','data-productid'=>$data->productid]);
					}
				}
			],
				'options' => ['style' => 'width:60px;'],
			],
        ]
    ]); ?>
	 <?php Pjax::end() ?>
</div>
<script>
$(document).ready(function(){
	$(document).on('click','.delete',function(){
		var productid = $(this).attr('data-productid');
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
				url:'<?= yii\helpers\Url::toRoute('products/delete')?>',
				type:'post',
				data:{productid:productid},
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
