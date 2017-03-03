<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AttendanceLeave */
/* @var $form yii\widgets\ActiveForm */
$model->writedate = $model->writedate&&$model->writedate!="0000-00-00"?$model->writedate:date("Y-m-d",time());

?>
<style>
.attendance-leave-form input.form-control{display:inline-block;}
.attendance-leave-form .form-control{border:1px solid #fff;}
.attendance-leave-form .has-error .form-control{border:1px solid #a94442;}
.attendance-leave-form td{height:34px;}
.attendance-leave-form td.tdtitle{padding:0 5px;font-weight:bold;}
#attendanceleave-leavetype label{margin:0 10px;}
.field-attendanceleave-leavetype div{display:inline-block}
.field-attendanceleave-leavetype .help-block{margin:0;}
.error-summary{color:red;}
.error-summary ul{margin:0;padding:0;}
.error-summary ul li{float:left;margin-left:24px}
</style>
<div class="attendance-leave-form">

    <?php $form = ActiveForm::begin(); ?>
	<?=$form->errorSummary($model)?>
	<table border=1 width="1000" >
	<colgroup>
		<col width="120"/>
		<col width="130"/>
		<col width="80"/>
		<col width="170"/>
		<col width="80"/>
		<col width="170"/>
		<col width="80"/>
		<col width="170"/>
	</colgroup>
	<tr>
		<td class='tdtitle'>姓名</td>
		<td>
		<?= $form->field($model, 'username',['options'=>['tag'=>'div'],'template'=>'{input}'])->label("")->widget(\yii\jui\AutoComplete::classname(), ["options"=>["class"=>"form-control",'disabled'=>'disabled']]) ?>
		</td>
		<td class='tdtitle'>部门</td>
		<td> <?= $form->field($model, 'department',['options'=>['tag'=>'div'],'template'=>'{input}'])->textInput(['maxlength' => true,'disabled'=>'disabled']) ?></td>
		<td class='tdtitle'>职务</td>
		<td><?= $form->field($model, 'job',['options'=>['tag'=>'div'],'template'=>'{input}'])->textInput(['maxlength' => true,'disabled'=>'disabled']) ?></td>
		<td class='tdtitle'>填写日期</td>
		<td><?php echo $form->field($model, 'writedate',['options'=>['tag'=>'div'],'template'=>'{input}'])->widget(\kartik\date\DatePicker::classname(),
				[
							'name' => 'Attendanceovertime[writedate]',
							'id' => 'attendanceovertime-writedate',
							// 'layout' => "{input}{remove}",
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd',
							]
				]);?></td>
	</tr>
	<tr>
		<td class='tdtitle'>加班类别</td>
		<td colspan="7">
		<?= $form->field($model, 'overtimetype',['options'=>['tag'=>'div'],'template'=>'{input}{error}'])->radioList(["10"=>"工作日","20"=>"休息日","30"=>"节假日","40"=>"其他",]) ?>
		<?= $form->field($model, 'employeeid',['options'=>['tag'=>'div'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
		<?= $form->field($model, 'personnel_id',['options'=>['tag'=>'div'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
		<?= $form->field($model, 'toexamineid',['options'=>['tag'=>'div'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
		</td>
	</tr>
	
	<tr>
		<td  class='tdtitle' rowspan="1">加班事由：</td>
		<td colspan="7">
			<?= $form->field($model, 'description',['options'=>['tag'=>'span'],'template'=>'{input}'])->textArea(['maxlength' => true,'style' => "resize:none;height:70px;"]) ?>
		</td>
	</tr>
	
	
	<tr>
		<td class='tdtitle'>加班日期</td>
		<td colspan="7">
		&nbsp;&nbsp;从<?php echo $form->field($model,'overtimestart',['options'=>['tag'=>'span'],'template'=>'{input}'])->widget(\kartik\datetime\DateTimePicker::classname(),
				[
							'name' => 'Attendanceovertime[overtimestart]',
							'id' => 'attendanceovertime-overtimestart',
							"options"=>['style' => 'width:200px;'],
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd hh:ii',
							]
				]);?>
		至<?php echo $form->field($model,'overtimeend',['options'=>['tag'=>'span'],'template'=>'{input}'])->widget(\kartik\datetime\DateTimePicker::classname(),
				[
							'name' => 'Attendanceovertime[overtimeend]',
							'id' => 'attendanceovertime-overtimeend',
							"options"=>['style' => 'width:200px;'],
							// 'layout' => "{input}{remove}",
							//"value"=>isset($model->overtimeend)&&$model->overtimeend?date('Y-m-d H:i',$model->overtimeend):'',
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd hh:ii',
								'minView' => '0',
							]
				]);?>
		&nbsp;&nbsp;　　　　　　　　　　　　　　　　合计
		<?= $form->field($model, 'overtimeday',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,"style"=>"width:50px"]) ?>天
		 <?= $form->field($model, 'overtimehour',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,"style"=>"width:50px"]) ?>时
		</td>　　　　　
	</tr>
	
	
	<tr height="70">
		<td class='tdtitle' rowspan="2">部门主管意见</td>
		<td colspan="7">
			<?= $form->field($model, 'supervisormemo',['options'=>['tag'=>'span'],'template'=>'{input}'])->textArea(['maxlength' => true,"disabled"=>"disabled","disabled"=>"disabled",'style' => "resize:none;height:70px;"]) ?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<input type="button" value="签名图附件" disabled  class='attach_file' data_type="1" limit = '5' attach_file="attendanceleave-supervisorsignaturefile"   >
			<span>
			<?php if($model->supervisorsignaturefileAttr){
				foreach($model->supervisorsignaturefileAttr as $file){
			?>
			<img class="imgView" style="height:35px;width:100px;" src="<?=$file['file']?>" align="absmiddle">
			<?php 
				}
			}?>
			</span>
			<?= $form->field($model, 'supervisorsignaturefile',['options'=>['tag'=>'span'],'template'=>'{input}'])->hiddenInput(['maxlength' => true,"disabled"=>"disabled"]) ?>
		</td>
		<td class='tdtitle'>
			签字
		</td>
		<td >
			<?= $form->field($model, 'supervisorsignature',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,"disabled"=>"disabled"]) ?>
		</td>
		<td class='tdtitle'>
			日期
		</td>
		<td > 
			<?php echo $form->field($model, 'supervisordate',['options'=>['tag'=>'div'],'template'=>'{input}'])->widget(\kartik\date\DatePicker::classname(),
				[
							'name' => 'AttendanceLeave[writedate]',
							'id' => 'attendanceleave-writedate',
							// 'layout' => "{input}{remove}",
							"disabled"=>"disabled",
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd',
							]
				]);?>
		</td>
	</tr>
	
	
	<tr height="70">
		<td class='tdtitle' rowspan="2">人事行政部意见</td>
		<td colspan="7">
			<?= $form->field($model, 'administrationmemo',['options'=>['tag'=>'span'],'template'=>'{input}'])->textArea(['maxlength' => true,"disabled"=>"disabled","disabled"=>"disabled",'style' => "resize:none;height:70px;"]) ?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<input type="button" value="签名图附件" disabled  class='attach_file' data_type="1" limit = '5' attach_file="attendanceleave-administrationsignaturefile"   >
			<span>
			<?php if($model->administrationsignaturefileAttr){
				foreach($model->administrationsignaturefileAttr as $file){
			?>
			<img class="imgView" style="height:35px;width:100px;" src="<?=$file['file']?>" align="absmiddle">
			<?php 
				}
			}?>
			</span>
			<?= $form->field($model, 'administrationsignaturefile',['options'=>['tag'=>'span'],'template'=>'{input}'])->hiddenInput(['maxlength' => true,"disabled"=>"disabled","disabled"=>"disabled","disabled"=>"disabled"]) ?>
		</td>
		<td class='tdtitle'>
			签字
		</td>
		<td >
			<?= $form->field($model, 'administrationsignature',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,"disabled"=>"disabled","disabled"=>"disabled","disabled"=>"disabled"]) ?>
		</td>
		<td class='tdtitle'>
			日期
		</td>
		<td >
			<?php echo $form->field($model, 'administrationdate',['options'=>['tag'=>'div'],'template'=>'{input}'])->widget(\kartik\date\DatePicker::classname(),
				[
							'name' => 'AttendanceLeave[writedate]',
							'id' => 'attendanceleave-writedate',
							"disabled"=>"disabled",
							// 'layout' => "{input}{remove}",
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd',
							]
				]);?>
		</td>
	</tr>
	<tr height="70">
		<td class='tdtitle' rowspan="2">总经理意见</td>
		<td colspan="7">
			<?= $form->field($model, 'generalmanagermemo',['options'=>['tag'=>'span'],'template'=>'{input}'])->textArea(['maxlength' => true,"disabled"=>"disabled","disabled"=>"disabled","disabled"=>"disabled",'style' => "resize:none;height:70px;"]) ?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<input type="button" value="签名图附件" disabled class='attach_file' data_type="1" limit = '5' attach_file="attendanceleave-generalmanagersignaturefile"   >
			<span>
			<?php if($model->generalmanagersignaturefileAttr){
				foreach($model->generalmanagersignaturefileAttr as $file){
			?>
			<img class="imgView" style="height:35px;width:100px;" src="<?=$file['file']?>" align="absmiddle">
			<?php 
				}
			}?>
			</span>
			<?= $form->field($model, 'generalmanagersignaturefile',['options'=>['tag'=>'span'],'template'=>'{input}'])->hiddenInput(['maxlength' => true,"disabled"=>"disabled"]) ?>
		</td>
		<td class='tdtitle'>
			签字
		</td>
		<td >
			<?= $form->field($model, 'generalmanagersignature',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,"disabled"=>"disabled","disabled"=>"disabled","disabled"=>"disabled"]) ?>
		</td>
		<td class='tdtitle'>
			日期
		</td>
		<td >
			<?php echo $form->field($model, 'generalmanagerdate',['options'=>['tag'=>'div'],'template'=>'{input}'])->widget(\kartik\date\DatePicker::classname(),
				[
							'name' => 'AttendanceLeave[writedate]',
							'id' => 'attendanceleave-writedate',
							'options' => ["disabled"=>"disabled"],
							// 'layout' => "{input}{remove}",
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd',
							]
				]);?>
		</td>
	</tr>
	<?= $form->field($model, 'status',['options'=>['tag'=>'span'],'template'=>'{input}'])->hiddenInput(['maxlength' => true,'value'=>'']) ?>
	<tr height="70">
		<td colspan="8">
			备注:l 员工需在加班前填写《加班申请表》，所在部门经理审批结束后交人事行政部，实际加班时间以考勤记录时间为准；2.补报加班必须在发生后一周内提报本表，逾期作废。
		</td>
	</tr>
	</table>
	<?php if(!$model->status && $type){?>
    <div class="form-group">
	    
        <?= Html::submitButton($model->isNewRecord ? '草稿' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','data-type'=>'0']) ?>
		<?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','data-type'=>'20']) ?>
    </div>
	<?php } ?>
    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript" src="/js/jquery.fileupload.js"></script> 
<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<input  style='display:none' type="file" name="Filedata" id='id_photos' value="" />
<script>
$(document).ready(function(){
	var defaultStart = '<?=date("Y-m-d",strtotime("-1 month"))?>';
	var defaultEnd = '<?=date("Y-m-d",strtotime("+1 month"))?>';
	var department = "<?= $model['departments']['name'] ?>";
	if(department){
		$("#attendanceovertime-department").val(id);
	}
	
	$('#attendanceovertime-overtimestart').datetimepicker('setStartDate', defaultStart);
	$('#attendanceovertime-overtimestart').datetimepicker('setEndDate', defaultEnd);
	$('#attendanceovertime-overtimeend').datetimepicker('setStartDate', defaultStart);
	$('#attendanceovertime-overtimeend').datetimepicker('setEndDate', defaultEnd);
	$('#attendanceovertime-overtimeend').on("change",function(){
		var endtime = this.value?this.value:defaultEnd;
		$('#attendanceovertime-overtimestart').datetimepicker('setEndDate', endtime);
		var overtimestart = $('#attendanceovertime-overtimestart').val();
		var overtimeend = this.value;
		var endTime =DateToUnix(overtimeend);
		var startTime = DateToUnix(overtimestart);
		var time = endTime-startTime;
		var overtime = time / 3600;
		var a = overtime.toFixed(1).split('.');
		if(a[1] >= 5){
			var a = a[0]+'.'+5;
		}else{
			var a = a[0];
		}
		$('#attendanceovertime-overtimehour').val(a);
	})
	function DateToUnix(string) {
        var f = string.split(' ', 2);
        var d = (f[0] ? f[0] : '').split('-', 3);
        var t = (f[1] ? f[1] : '').split(':', 3);
        return (new Date(
            parseInt(d[0], 10) || null,
           (parseInt(d[1], 10) || 1) - 1,
            parseInt(d[2], 10) || null,
            parseInt(t[0], 10) || null,
            parseInt(t[1], 10) || null,
            parseInt(t[2], 10) || null
            )).getTime() / 1000;
      }
	$('#attendanceovertime-overtimestart').on("change",function(){
		var starttime = this.value?this.value:defaultEnd;
		$('#attendanceovertime-overtimeend').datetimepicker('setStartDate', starttime);
	})
	$(':button').click(function(){
	 $('#attendanceovertime-status').val($(this).attr('data-type'));
	})
})

</script>