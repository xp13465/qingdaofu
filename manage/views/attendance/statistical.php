<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AttendanceCheckinReportsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '考勤统计';
$this->params['breadcrumbs'][] = $this->title;
$this->blocks['content-header'] = Html::a('考勤报表', ['index'], ['class' => 'btn btn-default ']);
$this->blocks['content-header'].= "　".Html::a("考勤日历", ['calendar-all'], ['class' => 'btn btn-default    ']);
$this->blocks['content-header'].= "　".Html::a('考勤统计', ['statistical'], ['class' => 'btn btn-default active']);
$this->blocks['content-header'].= "　".Html::a('打卡记录', ['data'], ['class' => 'btn btn-default ']);
?>
<div class="attendance-checkin-reports-index">


<?php
     Pjax::begin([
         'id' => 'post-grid-pjax',
     ])
    ?> 
	<form id='post-grid-pjax-form'>
 
		<button type="button" class="btn btn-2g btn-primary" id='excelstatistical'>导出显示列表</button>
	<input name='sort' type='hidden' value='<?=isset($_GET['sort'])?$_GET['sort']:''?>'>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','headerOptions' => ['width' => '50'],],
 
			
			[
				'attribute' => 'year',
				'value' => function ($data) {
					return $data->year;
				},
				'headerOptions' => ['width' => '80'],
			],
			[
				'attribute' => 'month',
				'value' => function ($data) {
					return $data->month;
				},
				'headerOptions' => ['width' => '80'],
			],
			[
				'attribute' => 'username',
				'value' => function ($data) {
					return $data->username;
				},
				'headerOptions' => ['width' => '80'],
			],
			[
				'attribute' => 'overtime',
				'filter'=>'',
				'value' => function ($data) {
					return $data->overtime;
				},
				'headerOptions' => ['width' => '100'],
			],
			[
				'attribute' => 'overtime_valid',
				'filter'=>'',
				'value' => function ($data) {
					return $data->overtime_valid;
				},
				'headerOptions' => ['width' => '100'],
			],
			 
			 [
				'class' => 'yii\grid\ActionColumn',
				'header' => '操作',
				'template' => '',
				'buttons' => [
					'action' => function ($url, $model, $key) {
						$html = ''; 
						
						return $html;
						},
					], 
			], 
        ],
    ]); ?>
	</form>
	
	
<?php Pjax::end(); ?>

</div>
<script>
$(document).ready(function(){
	$(document).on("click",'.btnLate',function(){
		var id = $(this).attr('data-id');
		var latetime = $(this).attr('data-latetime');
		if(id){
			var confirmindex = layer.confirm(latetime+"分钟迟到补齐有效？",{"title":'提示'},function(){
				layer.close(confirmindex)
				$.post('valid-late',{id:id,latetime:latetime},function(s){
					var alertindex = layer.alert(s.msg,function(){ 
						$(".form-control").eq(0).trigger("change")  
						layer.close(alertindex)
					})
				},'json');
			})
		} 
	});
	$(document).on("click",'.btnOver',function(){
		var id = $(this).attr('data-id');
		var overtime = $(this).attr('data-overtime');
		if(id){
			var confirmindex = layer.confirm("<div style='padding:20px;'>有效加班时长：<input id='overtime_valid' value='"+overtime+"' size='2' maxlength='4' />小时</div>",{"title":'设置加班时长',type:1},function(){
				
				var overtime_valid = parseFloat($("#overtime_valid").val());
				if(isNaN(overtime_valid)||overtime_valid<=0){
					layer.msg("请输入正确的加班时长")
					return false;
				} 
				layer.close(confirmindex)
				$.post('valid-over',{id:id,overtime:overtime_valid},function(s){
					var alertindex = layer.alert(s.msg,function(){ 
						$(".form-control").eq(0).trigger("change")  
						layer.close(alertindex)
					})
				},'json');
			})
		} 
	});	
	
	$(document).on("click",'.btnRedress',function(){
		var id = $(this).attr('data-id');
		var signstatus_valid = $(this).attr('data-signstatus_valid');
		var memo = $(this).attr('data-memo');
		if(id){
			var confirmindex = layer.confirm("<div style='padding:20px;width:300px;'>签到状态：<br/><input id='redressstatus' value='"+signstatus_valid+"' maxlength='5' /><br/>备注：<br/><textarea style='width:100%;resize:none;height:60px;' id='redressmemo'>"+memo+"</textarea></div>",{"title":'纠正状态',type:1},function(){
				
				var redressmemo = $("#redressmemo").val();
				var redressstatus = $("#redressstatus").val();
				if(!redressstatus||!redressmemo){
					// layer.msg("纠正状态和纠正备注不能为空！")；
					// return false;
				} 
				layer.close(confirmindex)
				$.post('valid-redress',{id:id,redressstatus:redressstatus,redressmemo:redressmemo},function(s){
					var alertindex = layer.alert(s.msg,function(){ 
						$(".form-control").eq(0).trigger("change")  
						layer.close(alertindex)
					})
				},'json');
			})
		} 
	});
	
	$(document).on("click",'.btnLeave,.btnOvertime',function(){
		window.open(this.href)
		return false;
	});
	
	
	$(document).on("click",'#excelindex',function(){
		params = $("#post-grid-pjax-form").serialize()
		window.open('/attendance/output-index?'+params);
		 
	});
	$(document).on("click",'#excelstatistical',function(){
			params = $("#post-grid-pjax-form").serialize()
			window.open('/attendance/output-statistical?'+params);
			 
	});
})
</script>
 
