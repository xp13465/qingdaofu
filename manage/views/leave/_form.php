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
							'name' => 'AttendanceLeave[writedate]',
							'id' => 'attendanceleave-writedate',
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
		<td class='tdtitle'>假别</td>
		<td colspan="7">
		<?= $form->field($model, 'leavetype',['options'=>['tag'=>'div'],'template'=>'{input}{error}'])->radioList(["10"=>"事假","20"=>"病假","30"=>"调休","40"=>"婚假","50"=>"产(陪)嫁","60"=>"年休假","70"=>"其他",]) ?>
		<?= $form->field($model, 'employeeid',['options'=>['tag'=>'div'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
		<?= $form->field($model, 'personnel_id',['options'=>['tag'=>'div'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
		<?= $form->field($model, 'toexamineid',['options'=>['tag'=>'div'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
		
		</td>
	</tr>
	<tr>
		<td class='tdtitle'>请假日期</td>
		<td colspan="6">
		&nbsp;&nbsp;从<?php echo $form->field($model, 'leavestart',['options'=>['tag'=>'span'],'template'=>'{input}'])->widget(\kartik\datetime\DateTimePicker::classname(),
				[
							'name' => 'AttendanceLeave[leavestart]',
							'id' => 'attendanceleave-leavestart',
							"options"=>['style' => 'width:200px;'],
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd hh:ii',
							]
				]);?>
		至<?php echo $form->field($model, 'leaveend',['options'=>['tag'=>'span'],'template'=>'{input}'])->widget(\kartik\datetime\DateTimePicker::classname(),
				[
							'name' => 'AttendanceLeave[leaveend]',
							'id' => 'attendanceleave-leaveend',
							"options"=>['style' => 'width:200px;'],
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd hh:ii',
								'minView' => '0',
							]
				]);?>
		合计
		<?= $form->field($model, 'leaveday',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,"style"=>"width:50px"]) ?>天
		 <?= $form->field($model, 'leavehour',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,"style"=>"width:50px"]) ?>时
		</td>　　　　　
		<td>
		&nbsp;&nbsp;有效时长
		 <?= $form->field($model, 'totalhour',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,"style"=>"width:50px"]) ?>小时
		</td>
	</tr>
	<tr height="70">
		<td  class='tdtitle' rowspan="2">具体说明</td>
		<td colspan="7">
			<?= $form->field($model, 'description',['options'=>['tag'=>'span'],'template'=>'{input}'])->textArea(['maxlength' => true,'style' => "resize:none;height:70px;"]) ?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<input type="button" value="请假单附件"   class='attach_file' data_type="1" limit = '5' attach_file="attendanceleave-leavefile"   >
			<span>
			<?php if($model->leavefileAttr){
				foreach($model->leavefileAttr as $file){
			?>
			<img class="imgView" style="height:35px;width:100px;" src="<?=$file['file']?>" align="absmiddle">
			<?php 
				}
			}?>
			</span>
			<?= $form->field($model, 'leavefile',['options'=>['tag'=>'span'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
		</td>
		<td class='tdtitle'>
			签字
		</td>
		<td colspan="3">
			<?= $form->field($model, 'signature',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true]) ?>
		</td>
	</tr>
	
	<tr>
		<td class='tdtitle' colspan="3">休假期间工作交接联系人及联系电话：</td>
		<td colspan="5"> <?= $form->field($model, 'workhandover',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true]) ?></td>
	</tr>
	
	<tr height="70">
		<td class='tdtitle' rowspan="2">部门主管意见</td>
		<td colspan="7">
			<?= $form->field($model, 'supervisormemo',['options'=>['tag'=>'span'],'template'=>'{input}'])->textArea(['maxlength' => true,'style' => "resize:none;height:70px;",'disabled'=>'disabled']) ?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<input type="button" value="签名图附件"   class='attach_file' data_type="1" limit = '5' attach_file="attendanceleave-supervisorsignaturefile"   >
			<span>
			<?php if($model->supervisorsignaturefileAttr){
				foreach($model->supervisorsignaturefileAttr as $file){
			?>
			<img class="imgView" style="height:35px;width:100px;" src="<?=$file['file']?>" align="absmiddle">
			<?php 
				}
			}?>
			</span>
			<?= $form->field($model, 'supervisorsignaturefile',['options'=>['tag'=>'span'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
		</td>
		<td class='tdtitle'>
			签字
		</td>
		<td >
			<?= $form->field($model, 'supervisorsignature',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,'disabled'=>'disabled']) ?>
		</td>
		<td class='tdtitle'>
			日期
		</td>
		<td >
			<?php echo $form->field($model, 'supervisordate',['options'=>['tag'=>'div'],'template'=>'{input}'])->widget(\kartik\date\DatePicker::classname(),
				[
							'name' => 'AttendanceLeave[writedate]',
							'id' => 'attendanceleave-writedate',
							'disabled'=>'disabled',
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
			<?= $form->field($model, 'administrationmemo',['options'=>['tag'=>'span'],'template'=>'{input}'])->textArea(['maxlength' => true,'style' => "resize:none;height:70px;",'disabled'=>'disabled']) ?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<input type="button" value="签名图附件"   class='attach_file' data_type="1" limit = '5' attach_file="attendanceleave-administrationsignaturefile"   >
			<span>
			<?php if($model->administrationsignaturefileAttr){
				foreach($model->administrationsignaturefileAttr as $file){
			?>
			<img class="imgView" style="height:35px;width:100px;" src="<?=$file['file']?>" align="absmiddle">
			<?php 
				}
			}?>
			</span>
			<?= $form->field($model, 'administrationsignaturefile',['options'=>['tag'=>'span'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
		</td>
		<td class='tdtitle'>
			签字
		</td>
		<td >
			<?= $form->field($model, 'administrationsignature',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,'disabled'=>'disabled']) ?>
		</td>
		<td class='tdtitle'>
			日期
		</td>
		<td >
			<?php echo $form->field($model, 'administrationdate',['options'=>['tag'=>'div'],'template'=>'{input}'])->widget(\kartik\date\DatePicker::classname(),
				[
							'name' => 'AttendanceLeave[writedate]',
							'id' => 'attendanceleave-writedate',
							'disabled'=>'disabled',
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
			<?= $form->field($model, 'generalmanagermemo',['options'=>['tag'=>'span'],'template'=>'{input}'])->textArea(['maxlength' => true,'style' => "resize:none;height:70px;",'disabled'=>'disabled']) ?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<input type="button" value="签名图附件"   class='attach_file' data_type="1" limit = '5' attach_file="attendanceleave-generalmanagersignaturefile"   >
			<span>
			<?php if($model->generalmanagersignaturefileAttr){
				foreach($model->generalmanagersignaturefileAttr as $file){
			?>
			<img class="imgView" style="height:35px;width:100px;" src="<?=$file['file']?>" align="absmiddle">
			<?php 
				}
			}?>
			</span>
			<?= $form->field($model, 'generalmanagersignaturefile',['options'=>['tag'=>'span'],'template'=>'{input}'])->hiddenInput(['maxlength' => true,'disabled'=>'disabled']) ?>
		</td>
		<td class='tdtitle'>
			签字
		</td>
		<td >
			<?= $form->field($model, 'generalmanagersignature',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,'disabled'=>'disabled']) ?>
		</td>
		<td class='tdtitle'>
			日期
		</td>
		<td >
			<?php echo $form->field($model, 'generalmanagerdate',['options'=>['tag'=>'div'],'template'=>'{input}'])->widget(\kartik\date\DatePicker::classname(),
				[
							'name' => 'AttendanceLeave[writedate]',
							'id' => 'attendanceleave-writedate',
							'disabled'=>'disabled',
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd',
							]
				]);?>
		<?= $form->field($model, 'status',['options'=>['tag'=>'span'],'template'=>'{input}'])->hiddenInput(['maxlength' => true,'value'=>'']) ?>
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
	$(':button').click(function(){
			 $('#attendanceleave-status').val($(this).attr('data-type'));
	})
	
	$('#attendanceleave-leavestart').datetimepicker('setStartDate', defaultStart);
	$('#attendanceleave-leavestart').datetimepicker('setEndDate', defaultEnd);
	$('#attendanceleave-leaveend').datetimepicker('setStartDate', defaultStart);
	$('#attendanceleave-leaveend').datetimepicker('setEndDate', defaultEnd);
	$('#attendanceleave-leaveend').on("change",function(){
		var endtime = this.value?this.value:defaultEnd;
		$('#attendanceleave-leavestart').datetimepicker('setEndDate', endtime);
		
	})
	$('#attendanceleave-leavestart').on("change",function(){
		var starttime = this.value?this.value:defaultEnd;
		$('#attendanceleave-leaveend').datetimepicker('setStartDate', starttime);
		
	})
	
	
	$(document).on('click',".attach_file",function(){
		var limit = $(this).attr('limit')?$(this).attr('limit'):2;
		var attach_file = $(this).attr('attach_file')?$(this).attr('attach_file'):'';
		var data_type = $(this).attr('data_type');
		if(!attach_file)return false;
		$("#id_photos").attr({"attach_file":attach_file,"limit":limit,'data_type':data_type}).click();
	})

	$(document).on("change",'#id_photos',function(){ //此处用了change事件，当选择好图片打开，关闭窗口时触发此事件
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});
		var attach_file = $(this).attr('attach_file');
		var limit = $(this).attr('limit')?$(this).attr('limit'):2;
		var data_type = $(this).attr('data_type');
		// var album_list = $(this).attr('album_list');
		$.ajaxFileUpload({
			url:"<?php echo yii\helpers\Url::toRoute(['/site/upload','filetype'=>1,'_csrf'=>Yii::$app->getRequest()->getCsrfToken()])?>",
			type: "POST",
			secureuri: false,
			fileElementId: 'id_photos',
			data: {'_csrf':'<?=Yii::$app->getRequest()->getCsrfToken()?>'},
			textareas:{},
			dataType: "json",
			success: function (data) {
				layer.close(index) 
				if(data.error == '0'&&data.fileid){	
					 var aa = $("#album-album_list").val();
                    
					$("#"+attach_file).val(data.fileid).trigger("change");
					var div = '<img class="imgView" style="height:35px;width:100px;" src="'+data.url1+'" align="absmiddle">';
					$("#"+attach_file).parent("span").prev('span').html(div);
				 		
                    
				}else if(data.msg){
					layer.alert(""+data.msg)
				}
			},
			error:function(){
				layer.close(index)
			}
		}); 
	 });
})

</script>
