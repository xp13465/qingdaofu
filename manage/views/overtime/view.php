<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\AttendanceOvertime */

// echo "<pre>";
// var_dump($model['departments']['name']);die;

$this->title = '详情#'.$model->id;
$this->params['breadcrumbs'][] = ['label' => '列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$type = Yii::$app->request->get('type')?Yii::$app->request->get('type'):'';
?>
<div class="attendance-overtime-view">
<?php if(!$model->status && $type != '2'){ ?>
    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a($model->validflag=='1'?'删除':'恢复','javascript:void(0);', [
            'class' => 'btn btn-danger',
			'data-id'=>$model->id,
			'data-type'=>$model->validflag=='1'?'1':'2',
        ]) ?>
    </p>
<?php } ?>
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
		<?= $form->field($model, 'username',['options'=>['tag'=>'div'],'template'=>'{input}'])->label("")->widget(\yii\jui\AutoComplete::classname(), [
			'clientOptions' => [
				// 'source' => [["value"=>'陈明',"label"=>'陈明：QDF2263陈明：QDF2263陈明：QDF2263陈明：QDF2263陈明：QDF2263陈明：QDF2263',"personnel_id"=>'11'],["value"=>'RUSvalue',"label"=>'RUSlabel',"personnel_id"=>'22']],
				'source' => "/personnel/autocomplete",
			],
			"options"=>["class"=>"form-control",'disabled'=>'disabled'],
			'clientEvents' => [
			   'select'=>'function(event,ui){
				   $("#attendanceovertime-employeeid").val(ui.item.employeeid);
				   $("#attendanceovertime-personnel_id").val(ui.item.personnel_id);
				   $("#attendanceovertime-department").val(ui.item.departments.name);
				   $("#attendanceovertime-job").val(ui.item.job);
				    $("#attendanceovertime-toexamineid").val(ui.item.parentid);
				}',
			],
		]) ?>
		
		
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
							'disabled'=>'disabled',
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
		<?= $form->field($model, 'overtimetype',['options'=>['tag'=>'div'],'template'=>'{input}{error}'])->radioList(["10"=>"工作日","20"=>"休息日","30"=>"节假日","40"=>"其他"],['disabled'=>'disabled']) ?>
		<?= $form->field($model, 'employeeid',['options'=>['tag'=>'div'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
		<?= $form->field($model, 'personnel_id',['options'=>['tag'=>'div'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
		<?= $form->field($model, 'toexamineid',['options'=>['tag'=>'div'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
		</td>
	</tr>
	
	<tr>
		<td  class='tdtitle' rowspan="1">加班事由：</td>
		<td colspan="7">
			<?= $form->field($model, 'description',['options'=>['tag'=>'span'],'template'=>'{input}'])->textArea(['maxlength' => true,'style' => "resize:none;height:70px;",'disabled'=>'disabled']) ?>
		</td>
	</tr>
	
	
	<tr>
		<td class='tdtitle'>加班日期</td>
		<td colspan="7">
		&nbsp;&nbsp;从<?php echo $form->field($model,'overtimestart',['options'=>['tag'=>'span'],'template'=>'{input}'])->widget(\kartik\datetime\DateTimePicker::classname(),
				[
							'name' => 'Attendanceovertime[overtimestart]',
							'id' => 'attendanceovertime-overtimestart',
							'disabled'=>'disabled',
							"options"=>['style' => 'width:200px;'],
							// 'layout' => "{input}{remove}",
							//"value"=>isset($model->overtimestart)&&$model->overtimestart?date('Y-m-d H:i',$model->overtimestart):'',
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
							'disabled'=>'disabled',
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
		<?= $form->field($model, 'overtimeday',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,"style"=>"width:50px",'disabled'=>'disabled']) ?>天
		 <?= $form->field($model, 'overtimehour',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,"style"=>"width:50px",'disabled'=>'disabled']) ?>时
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
	<?= $form->field($model, 'status',['options'=>['tag'=>'span'],'template'=>'{input}'])->hiddenInput(['maxlength' => true,'value'=>'','disabled'=>'disabled']) ?>
	<tr height="70">
		<td colspan="8">
			备注:l 员工需在加班前填写《加班申请表》，所在部门经理审批结束后交人事行政部，实际加班时间以考勤记录时间为准；2.补报加班必须在发生后一周内提报本表，逾期作废。
		</td>
	</tr>
	</table>
    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript" src="/js/jquery.fileupload.js"></script> 
<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<input  style='display:none' type="file" name="Filedata" id='id_photos' value="" />
<script>
	$(document).ready(function(){
		
			var department = "<?= $model['departments']['name'] ?>";
			if(department){
				$("#attendanceovertime-department").val(department);
			}
			
		
		 $(document).on('click','.btn-danger',function(){
			 var id = $(this).attr('data-id');
			 var type = $(this).attr('data-type');
			 var obj = $(this);
		     var load = layer.load(1,{shade:[0.4,'#fff']});
			 layer.confirm(type==1?'是否需要删除该员工信息':'确定是否恢复该员工信息',
			 {
				 title:false,
				 conseBtn:false,
				 btn:['确定','取消'],
			 },function(){
				 $.ajax({
					 url:'<?= yii\helpers\Url::toRoute('overtime/delete') ?>',
					 type:'post',
					 data:{id:id,type:type},
					 dataType:'json',
					 success:function(json){
						 if(json.code == '0000'){
								layer.msg(json.msg,{time:2000},function(){
									if(type == 1){
											obj.html('恢复');
											obj.attr('data-type','2')
										}else{
											obj.html('删除');
											obj.attr('data-type','1')							
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
		 })
		 
		 
		 $('#attendanceovertime-overtimetype').each(function(){
			 $(this).children().children().attr('disabled','disabled');
		 })
	})
</script>