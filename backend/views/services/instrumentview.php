<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\ServicesInstrument;
/* @var $this yii\web\View */
/* @var $model app\models\ServicesInstrument */
$status = ServicesInstrument::$status;
$type = ServicesInstrument::$type;

$this->title = "详情#".$model->id;
$this->params['breadcrumbs'][] = ['label' => '法律服务-快捷文书', 'url' => ['instrument-index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="services-instrument-view">

   <p>
        <?php 
		$html = '';
		if($model->status == 10){
			$html .= Html::a('处理', 'javascript:void(0)',['class' => 'btn btn-success btnAudit' , 'title' => '处理该申请','curid' => $model->id,'curstatus' => $model->status,'tostatus' => '20' ] )."&nbsp;";
			$html .= Html::a('忽略', 'javascript:void(0)',['class' => 'btn btn-danger btnAudit' , 'title' => '忽略该申请','curid' => $model->id,'curstatus' => $model->status,'tostatus' => '30' ] )."&nbsp;";
		}
		echo $html;
		?>
    </p>  

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'province_id',
                'value' => $model->provincename?$model->provincename->province:"",
            ],
			[
                'attribute' => 'city_id',
                'value' => $model->cityname?$model->cityname->city:"",
            ],
			[
                'attribute' => 'district_id',
                'value' => $model->areaname?$model->areaname->area:"",
            ],
			'address',
			[
                'attribute' => 'type',
                'value' => isset($type[$model->type])?$type[$model->type]:'',
            ],
			[
                'attribute' => 'desc',
				'format' => 'raw',
                'value' => nl2br($model->desc),
            ], 
            [
                'attribute' => 'status',
                'value' => isset($status[$model->status])?$status[$model->status]:'',
            ], 
            'plaintiff',
            'defendant',
            'contacts',
            'tel',
            [
                'attribute' => 'create_user',
                'value' => $model->createuser?$model->createuser->username:"",
            ], 
            [
				'attribute'=>'create_time',
				'value'=>$model->create_time?date("Y-m-d H:i:s",$model->create_time):'',
			],
			[
                'attribute' => 'modify_user',
                'value' => $model->modifyuser?$model->modifyuser->username:"",
            ], 
            [
				'attribute'=>'modify_time',
				'value'=>$model->modify_time?date("Y-m-d H:i:s",$model->modify_time):'',
			],
			[
				'attribute'=>'auditmemo',
				'format' => 'raw',
				'value'=>nl2br($model->auditmemo),
			],
        ],
    ]) ?>

</div>
<script>
$(document).ready(function(){ 
	
	$(document).on("click",'.btnAudit',function(){
			var curstatus = $(this).attr("curstatus");
			var tostatus = $(this).attr("tostatus");
			var curid = $(this).attr("curid");
			var title = $(this).attr("title");
			var index = layer.prompt({title:"确认"+title+"?",formType:2 ,content:'备注：<br/><textarea class="layui-layer-input"></textarea>'},function(memo){
			var load = layer.load(1, {
			  shade: [0.4,'#fff'] //0.1透明度的白色背景
			});
			$.ajax({
				type: "POST",
				url: "<?php echo yii\helpers\Url::toRoute("audit") ?>",
				data: {id:curid, curstatus:curstatus, tostatus:tostatus, memo:memo,type:2},
				dataType: "json",
				success: function(data){
					layer.close(load)
					var alertindex = layer.msg(data.msg,function(){
						layer.close(alertindex)
						location.reload()
					})
					
                }
			});
			layer.close(index)
			return true;
		});
		
		
		
	})
	
	 
})
</script>