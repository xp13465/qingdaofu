<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AttendanceCheckinReportsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '考勤报表';
$this->params['breadcrumbs'][] = $this->title;
$this->blocks['content-header'] = Html::a('考勤报表', ['index'], ['class' => 'btn btn-default active']);
$this->blocks['content-header'].= "　".Html::a("考勤日历", ['calendar-all'], ['class' => 'btn btn-default    ']);
$this->blocks['content-header'].= "　".Html::a('考勤统计', ['statistical'], ['class' => 'btn btn-default ']);
$this->blocks['content-header'].= "　".Html::a('打卡记录', ['data'], ['class' => 'btn btn-default ']);
?>
<div class="attendance-checkin-reports-index">
<style>
.btnLate{float:right;}
</style>

<?php
     Pjax::begin([
         'id' => 'post-grid-pjax',
     ])
    ?> 
	<form id='post-grid-pjax-form'> 
	<button type="button" class="btn btn-2g btn-primary" id='excelindex'>导出显示列表</button>
	<input name='sort' type='hidden' value='<?=isset($_GET['sort'])?$_GET['sort']:''?>'>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'personnel_id',
			[
				'attribute' => 'employeeid',
				'value' => function ($data) {
					return $data->employeeid;
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
				'attribute' => 'year',
				'value' => function ($data) {
					return $data->year;
				},
				'headerOptions' => ['width' => '60'],
			],
			[
				'attribute' => 'month',
				'value' => function ($data) {
					return $data->month;
				},
				'headerOptions' => ['width' => '60'],
			],
			[
				'attribute' => 'signdate',
				'value' => function ($data) {
					return $data->signdate;
				},
				'headerOptions' => ['width' => '100'],
			],
			[
				'attribute' => 'dayofweek',
				'filter'=>["work"=>"工作日","1"=>"星期一","2"=>"星期二","3"=>"星期三","4"=>"星期四","5"=>"星期五","6"=>"星期六","0"=>"星期日"],
				'value' => function ($data) {
					return $data->dayofweek;
				},
				'headerOptions' => ['width' => '70'],
			],
			[
				'attribute' => 'signtime1',
				'filter'=>'',
				'value' => function ($data) {
					return $data->signtime1!="0000-00-00 00:00:00"?date("H:i",strtotime($data->signtime1)):'';
				},
				'headerOptions' => ['width' => '80'],
			],
			[
				'attribute' => 'signtime2',
				'filter'=>'',
				'value' => function ($data) {
					return $data->signtime2!="0000-00-00 00:00:00"?date("H:i",strtotime($data->signtime2)):'';
				},
				'headerOptions' => ['width' => '80'],
			],
			[
				'attribute' => 'timediff',
				'filter'=>'',
				'value' => function ($data) {
					return $data->timediff;
				},
				'headerOptions' => ['width' => '50'],
			],
			[
				'attribute' => 'signstatus',
				'format' => 'raw', 
				'value' => function ($data) {
					$html ="";
					$html.= $data->signstatus_valid?$data->signstatus_valid:($data->signstatus?:"无");
					return $html;
				},
				'headerOptions' => ['width' => '80'],
			],
			[
				'attribute' => 'latetime',
				'filter'=>'',
				'label' => '迟到分钟',
				'format' => 'raw', 
				'value' => function ($data) {
					$html ="";
					$html .=$data->latetime."分钟";
					
					if(($data->latetime-$data->latetime_valid)>0 &&$data->signstatus == "迟到补齐"){
						$html.=   "".Html::a('有效', 'javascript:void(0)', ['class'=>'btnLate btn btn-info btn-xs' ,"data-id"=>$data->id,"data-latetime"=>$data->latetime, 'title' => '有效' ] );
					}
					return $html;
				},
				'headerOptions' => ['width' => '100'],
			], 
			[
				'attribute' => 'overtime',
				'filter'=>'',
				'label' => '加班时长(测算)',
				'format' => 'raw', 
				'value' => function ($data) {
					$html ="";
					if($data->overtime>0){
						if($data->overtime_valid>0){
							$html.= "<font  color='green'>".$data->overtime_valid."</font>&nbsp;";
						}
						
						$html.= "<font color='#FF9800'>(".$data->overtime.")</font>";
						if($data->overtime_valid==0){
							$html.=  "&nbsp;".Html::a('有效', 'javascript:void(0)', ['class'=>'btnOver btn btn-info btn-xs' ,"data-id"=>$data->id,"data-overtime"=>$data->overtime, 'title' => '有效' ] );
						}
						
					}
					
					
					
					return $html;
					
				},
				'headerOptions' => ['width' => '90'],
			],
			
			[
				'attribute' => 'memo',
				'filter'=>'',
				'format' => 'raw', 
				'value' => function ($data) {
					$html = $data->memo;
					if((!$data->memo&&$data->signstatus!="正常")||($data->signstatus_valid!=""&&$data->signstatus_valid!="正常")){
						$html.=   "".Html::a('纠正', 'javascript:void(0)', ['class'=>'btnRedress btn btn-info btn-xs' ,"data-id"=>$data->id,"data-signstatus_valid"=>$data->signstatus_valid,"data-memo"=>$data->memo, 'title' => '纠正' ] );
					}
					return $html;
				}, 
			],
            [
				'attribute' => 'gooutids',
				'filter'=>["1"=>"有","2"=>"无"],
				'format' => 'raw', 
				'value' => function ($data) {
					$html = $data->gooutids; 
					$html ="";
					$gooutids = $data->gooutids?explode(",",$data->gooutids):[];
					$gooutmemo = $data->gooutmemo?explode("||,||",$data->gooutmemo):[];
					foreach($gooutids as $key=>$gooutid){
						$html .=$html?",":"";
						$html.=Html::a($gooutid, ["/goout/view","id"=>$gooutid], ['class'=>'btnLeave btn btn-success btn-xs' ,'target'=>"_blank" ,'title' => $gooutmemo[$key] ] );
					}
					$html  =($data->goouthour>0?$data->goouthour:"")."　".$html ;
					// $html = $data->leaveids; 
					return $html;
				}, 
			],
			
			[
				'attribute' => 'leaveids',
				'filter'=>["1"=>"有","2"=>"无"],
				'format' => 'raw', 
				'value' => function ($data) {
					$html ="";
					$leaveids = $data->leaveids?explode(",",$data->leaveids):[];
					$leavememo = $data->leaveids?explode("||,||",$data->leavememo):[];
					foreach($leaveids as $key=>$leaveid){
						$html .=$html?",":"";
						$html.=Html::a($leaveid, ["/leave/view","id"=>$leaveid], ['class'=>'btnLeave btn btn-success btn-xs' ,'target'=>"_blank" ,'title' => $leavememo[$key] ] );
					}
					$html  =($data->leavehour>0?$data->leavehour:"")."　".$html ;
					// $html = $data->leaveids; 
					return $html;
				}, 
			],
			
			[
				'attribute' => 'overtimeids',
				'filter'=>["1"=>"有","2"=>"无"],
				'format' => 'raw', 
				'value' => function ($data) {
					$html ="";
					$overtimeids = $data->overtimeids?explode(",",$data->overtimeids):[];
					$overtimememo = $data->overtimeids?explode("||,||",$data->overtimememo):[];
					foreach($overtimeids as $key=>$overtimeid){
						$html .=$html?",":"";
						$html.=Html::a($overtimeid, ["/overtime/view","id"=>$overtimeid], ['class'=>'btnOvertime btn btn-success btn-xs' ,'target'=>"_blank" ,'title' => $overtimememo[$key] ] );
					}
					$html  =($data->overtimehour>0?$data->overtimehour:"")."　".$html ;
					// $html = $data->overtimeids; 
					return $html;
				}, 
			],
			
			[
				'class' => 'yii\grid\ActionColumn',
				'header' => '操作',
				'template' => '{view}',
				'buttons' => [
					'action' => function ($url, $model, $key) {
						$html = ''; 
						
						return $html;
						},
					],
				'headerOptions' => ['width' => '50'],
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
			var confirmindex = layer.confirm("<div style='padding:20px;width:300px;'><span>签到状态：<span><input id='redressstatus' value='"+signstatus_valid+"' maxlength='5' /style='width:190px;height:30px;'></br><span style='float:left;margin-top:20px;'>备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注：</span><textarea style='width:190px;height:60px;resize:none;height:60px;margin-top:20px;' id='redressmemo'>"+memo+"</textarea></div>",{"title":'纠正状态',type:1},function(){
				
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
