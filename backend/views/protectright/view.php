<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Protectright;
/* @var $this yii\web\View */
/* @var $model app\models\Protectright */

$this->title = "保全详情#".$model->id;
$this->params['breadcrumbs'][] = ['label' => '保全管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$status = Protectright::$status;
$type = Protectright::$type;
$category = Protectright::$category;
?>
<div class="protectright-view">
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
            'number',
            // 'area_pid',
            // 'area_id',
            // 'fayuan_id',
            'fayuan_name', 
			[
                'attribute' => 'category',
                'value' => isset($category[$model->category])?$category[$model->category]:'',
            ],
			[
                'attribute' => 'phone',
            ],
			[
                'attribute' => 'account',
                'value' => ($model->account/10000).'万',
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
                'attribute' => $model->type==1?'address':'fayuan_address',
                // 'value' => isset($type[$model->type])?$type[$model->type]:'',
            ], 
			[
				'attribute'=>'create_user',
				'value'=>$model->createuser?$model->createuser->username:'',
			],			
			[
				'attribute'=>'create_time',
				'value'=>$model->create_time?date("Y-m-d H:i:s",$model->create_time):'',
			],
            
			[
				'attribute'=>'modify_user',
				'value'=>$model->updateuser?$model->updateuser->username:'',
			],
			[
				'attribute'=>'modify_time',
				'value'=>$model->modify_time?date("Y-m-d H:i:s",$model->modify_time):'',
			],
            
            // 'step', 
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
				"alt": $(this).attr("alt"),
				"pid": 0,
				"src": $(this).attr("src"),
				"thumb": "111"
			})
		  
		})  
		layer.photos({
			area: ['auto', '80%'],
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