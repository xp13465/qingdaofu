<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Property;
/* @var $this yii\web\View */
/* @var $model app\models\Property */

$this->title = "产调详情#".$model->orderId;
$this->params['breadcrumbs'][] = ['label' => '产调管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
// var_dump($model->provincename->name);
// var_dump($model->cityname);
// var_dump($model->expressdata);
// var_dump($model->username);
$status = Property::$status;
$type = Property::$type;
?>
<div class="property-view">
 
	<?php 
	echo isset($status[$model->status])?("<h2>产调".$status[$model->status]."</h2>"):'';
	// if($model->status==2){
		// echo '<p><button type="button" class="btn btn-primary" id="resultdetail">结果查看</button></p>';
	// }
	$html = '';
	switch($model->status){
		case -1:
			$html .= Html::a('仟房', 'javascript:void(0)', ['class'=>'btn btn-primary qfcd' , 'title' => '仟房', 'rel' => $model->id] );
			$html .='&nbsp;&nbsp;';
			$html .= Html::a('本地', ['/property/setbd','od'=>$model->orderId], ['class'=>'btn btn-primary ' , 'title' => '本地' ] );
			break;                                                        
		case 2:                                                
			$html .= Html::a('结果查看', 'javascript:void(0)', ['class'=>'btn btn-success','id'=>'resultdetail', 'title' => '结果查看']);
			if($model->expressdata&&!$model->expressdata->orderId){
				$html .='&nbsp;&nbsp;';
				$html .= Html::a('快递', ['/property/express','id'=>$model->id], ['class'=>'btn btn btn-info' , 'title' => '快递' ] );
			}
			break;  
		case 3:                                                          
			$html .= Html::a('本地', ['/property/setbd','od'=>$model->orderId], ['class'=>'btn btn-primary' , 'title' => '本地'] );
			break; 	 
	} 
	echo "<p>{$html}</p>";
	?>
	
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
			[
                'attribute' => 'uid',
                'value' => $model->username?$model->username->username:$model->uid,
            ],
			'orderId', 
			[
                'attribute' => 'type',
                'value' =>isset($type[$model->type])?$type[$model->type]:'',
            ],
			[
                'attribute' => 'province',
                'value' => $model->provincename?$model->provincename->name:$model->province,
            ],
			[
                'attribute' => 'city',
                'value' => $model->cityname?$model->cityname->name:$model->city,
            ],
            'address',
            'name',
            'phone',
			[
                'attribute' => 'status',
                'value' => isset($status[$model->status])?$status[$model->status]:'',
            ],
            'money',
			[
				'attribute'=>'time',
				'value'=>$model->time?date("Y-m-d H:i:s",$model->time):'',
			],
			'cid',
			[
				'attribute'=>'uptime',
				'value'=>$model->uptime?date("Y-m-d H:i:s",$model->uptime):'',
			],
             
            
        ],
    ]) ?>
	 <?php
		if($expressdata = $model->expressdata){
			echo $expressdata->orderId?"<h2>已发送快递</h2>":'<h2>已申请快递</h2>';
			echo DetailView::widget([
				'model' => $expressdata,
				'attributes' => [
					'name', 
					'phone', 
					'address', 
					[
						'attribute' => 'orderId',
						'value' => $expressdata->orderId,
					],
					[
						'attribute'=>'time',
						'value'=>$expressdata->time?date("Y-m-d H:i:s",$expressdata->time):'',
					],
					[
						'attribute'=>'uptime',
						'value'=>$expressdata->uptime?date("Y-m-d H:i:s",$expressdata->uptime):'',
					],
				],
			]);
		}else{
			echo '<h2>未申请快递</h2>';
		}
		$html = ''; 
		if($result){
			$html ="<h2>仟房反馈信息：</h2>";
			$html .="<p><b>申请时间：</b>".date("Y-m-d H:i:s" , $result['create_time'])."</p>";
			$html .="<p><b>处理时间：</b>".date("Y-m-d H:i:s" , $result['deal_time'])."</p>";
			$html .="<p><b>支付金额：</b>{$result['amount']}</p>";
			if($result['status']=="SUCCESS"){
				$html .="<p><b>备注：</b>{$result['success_msg']}</p>";
			}
			if($result['status']=="REFUND"){
				$html .="<p><b>退款原因：</b>{$result['refund_msg']}</p>";
				$html .="<p><b>退款金额：</b>{$result['refund_fee']}</p>";
			}
		}else if($model->cid){
			$html ="<h2>仟房无反馈信息！</h2>";
		}
		echo Html::tag("h4",$html);
		
     ?>
	
</div>
<script>
$(document).ready(function(){
	$("#resultdetail").click(function(){
			layer.open({
		  type: 2,
		  title: '产调结果详情页',
		  shadeClose: true,
		  shade: 0.8,
		  area: ['60%', '90%'],
		  content: '<?=Url::toRoute(["/property/resultdetail",'id'=>$model->id])?>' //iframe的url
		}); 
		
	})
	$(document).on("click",'.qfcd',function(){
		var id = $(this).attr('rel');					
		if(id){
			var confirmindex = layer.confirm("仟房操作确认？",{"title":'提示'},function(){
				layer.close(confirmindex)
				$.post('/property/setqf',{id:id},function(s){
					// $(".form-control").eq(0).trigger("change");
					if(s.status == 1){
						var msg='操作成功...';
					}else{
						var msg='操作失败...';
					}
					var alertindex = layer.alert(s.info,function(){ 
						location.href = location.href
					})
				},'json');
			})
		}
		/*
		
		if(id){
			$.post('/property/setqf',{id:id},function(s){
				$(this).attr('rel','');
				if(s.status == 1){
					alert('操作成功...');
					
					// self.location = '/property/index';
				}else{
					alert('操作失败...');
				}
			},'json');
		}*/
	});
})

</script>

