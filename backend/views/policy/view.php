<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\models\Policy */

$this->title = "保函详情#".$model->id;
$this->params['breadcrumbs'][] = ['label' => '保函列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$status = app\models\Policy::$status;
$type = app\models\Policy::$type;
 
?>
<div class="policy-view">
	
	<p>
	<?php 
		$html = '';
		switch($model->status){
			case 1:
				$html .= Html::a('审核通过', 'javascript:void(0)', ['class' => 'btn btn-primary btnAudit' , 'title' => '审核通过','curid' => $model->id,'curstatus' => $model->status,'tostatus' => '10' ] );
				break;                                 
			case 10:                                   
				$html .= Html::a('协议签订', 'javascript:void(0)', ['class' => 'btn btn-primary btnAudit' , 'title' => '协议签订','curid' => $model->id,'curstatus' => $model->status,'tostatus' => '20' ] );
				break;                                 
			case 20:                                   
				$html .= Html::a('保函已出', 'javascript:void(0)', ['class' => 'btn btn-primary btnAudit' , 'title' => '保函已出','curid' => $model->id,'curstatus' => $model->status,'tostatus' => '30' ] );
				break;
			case 30:
				$html .= Html::a('完成/退保', 'javascript:void(0)',['class' => 'btn btn-primary btnAudit' , 'title' => '完成/退保','curid' => $model->id,'curstatus' => $model->status,'tostatus' => '40' ] );
				break;
		} 
		echo $html;
	?>
    
   </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'orderid',
			'anhao',
			// 'area_pid',
            // 'area_id',
            // 'area_name',
			'fayuan_name',
			'phone',
            'address',
            'fayuan_address',
			
            [
                'attribute' => 'money',
                'value' => ($model->money/10000).'万',
            ],
			[
                'attribute' => 'status',
                'value' => isset($status[$model->status])?$status[$model->status]:'',
            ], 
			[
                'attribute' => 'type',
                'value' => isset($type[$model->type])?$type[$model->type]:'',
            ], 
			[
				'attribute'=>'created_by',
				'value'=>$model->createuser?$model->createuser->username:'',
			],
			[
				'attribute'=>'created_at',
				'value'=>$model->created_at?date("Y-m-d H:i:s",$model->created_at):'',
			],
            
			[
				'attribute'=>'updated_by',
				'value'=>$model->updateuser?$model->updateuser->username:'',
			],
			[
				'attribute'=>'updated_at',
				'value'=>$model->updated_at?date("Y-m-d H:i:s",$model->updated_at):'',
			],
            
			[
                'attribute' => 'qisu',
                'value' => $model->getImgHtml($model->qisu),
				'format' => 'raw',
            ],
			[
                'attribute' => 'caichan',
                'value' => $model->getImgHtml($model->caichan),
				'format' => 'raw',
            ],
			[
                'attribute' => 'caichan',
                'value' => $model->getImgHtml($model->caichan),
				'format' => 'raw',
            ],
            [
                'attribute' => 'anjian',
                'value' => $model->getImgHtml($model->anjian),
				'format' => 'raw',
            ],
			
        ],
    ]) ?>
</div>
<script>
$(document).ready(function(){ 
	$(".content img.photoShow").on('click',function(){
		// alert($(this).parent().find("img"))
		// alert( $(this).index())
		var   json = {
			"status": 1,
			"msg": "查看",
			"title": "查看",
			"id": 0,
			"start": $(this).index(),
			"data": []
		};
		$(this).parent().find("img.photoShow").each(function(){
			console.log( $(this))
			json.data.push({
				"alt": "",
				"pid": 0,
				"src": $(this).attr("src"),
				"thumb": ""
			})
		  
		})  
		layer.photos({
			photos: json
		});
		
	})
	
	$(document).on("click",'.btnAudit',function(){
			var curstatus = $(this).attr("curstatus");
			var tostatus = $(this).attr("tostatus");
			var curid = $(this).attr("curid");
			var title = $(this).attr("title");
			var index = layer.prompt({title:"确认"+title+"?",formType:2 ,content:'审核备注：<br/><textarea class="layui-layer-input"></textarea>'},function(memo){
			var load = layer.load(1, {
			  shade: [0.4,'#fff'] //0.1透明度的白色背景
			});
			$.ajax({
				type: "POST",
				url: "<?php echo yii\helpers\Url::toRoute("audit") ?>",
				data: {id:curid, curstatus:curstatus, tostatus:tostatus, memo:memo},
				dataType: "json",
				success: function(data){
					layer.close(load)
					var alertindex = layer.alert(data.msg,function(){
						location.reload()
						// $(".form-control").eq(0).trigger("change")  
						layer.close(alertindex)
						
					})
					
                }
			});
			layer.close(index)
			return true;
		});
		
		
		
	})
	
	 
})
</script>
