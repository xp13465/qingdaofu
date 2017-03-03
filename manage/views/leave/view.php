
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AttendanceLeave */
/* @var $form yii\widgets\ActiveForm */
$leavetypeLabel = \app\models\AttendanceLeave::$leavetypeLabel;
$this->title = "请假单:#".$model->id;
$this->params['breadcrumbs'][] = ['label' => '请假单管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$type = Yii::$app->request->get('type')?Yii::$app->request->get('type'):'';
?>
<div class="attendance-leave-view">
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
			<?=$model->username?>
		
		
		</td>
		<td class='tdtitle'>部门</td>
			
		<td><?=$model->department?></td>
		<td class='tdtitle'>职务</td>
		<td><?=$model->job?></td>
		<td class='tdtitle'>填写日期</td>
		<td><?=$model->writedate?></td>
	</tr>
	<tr>
		<td class='tdtitle'>假别</td>
		<td colspan="7">
		<?=isset($leavetypeLabel[$model->leavetype])?$leavetypeLabel[$model->leavetype]:""?>
		
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
							"disabled"=>"disabled",
							// 'layout' => "{input}{remove}",
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
							"disabled"=>"disabled",
							// 'layout' => "{input}{remove}",
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
		<?= $form->field($model, 'leaveday',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,"disabled"=>"disabled","disabled"=>"disabled","style"=>"width:50px"]) ?>天
		 <?= $form->field($model, 'leavehour',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,"disabled"=>"disabled","disabled"=>"disabled","style"=>"width:50px"]) ?>时
		</td>　　　　　
		<td>
		&nbsp;&nbsp;有效时长
		 <?= $form->field($model, 'totalhour',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,"disabled"=>"disabled","disabled"=>"disabled","style"=>"width:50px"]) ?>小时
		</td>
	</tr>
	<tr height="70">
		<td  class='tdtitle' rowspan="2">具体说明</td>
		<td colspan="7">
			<?= $form->field($model, 'description',['options'=>['tag'=>'span'],'template'=>'{input}'])->textArea(['maxlength' => true,"disabled"=>"disabled","disabled"=>"disabled",'style' => "resize:none;height:70px;"]) ?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<input type="button" value="请假单附件"  disabled class='attach_file' data_type="1" limit = '5' attach_file="attendanceleave-leavefile"   >
			<span>
			<?php if($model->leavefileAttr){
				foreach($model->leavefileAttr as $file){
			?>
			<img class="imgView" style="height:35px;width:100px;" src="<?=$file['file']?>" align="absmiddle">
			<?php 
				}
			}?>
			</span>
			<?= $form->field($model, 'leavefile',['options'=>['tag'=>'span'],'template'=>'{input}'])->hiddenInput(['maxlength' => true,"disabled"=>"disabled","disabled"=>"disabled",]) ?>
		</td>
		<td class='tdtitle'>
			签字
		</td>
		<td colspan="3">
			<?= $form->field($model, 'signature',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,"disabled"=>"disabled","disabled"=>"disabled",]) ?>
		</td>
	</tr>
	
	<tr>
		<td class='tdtitle' colspan="3">休假期间工作交接联系人及联系电话：</td>
		<td colspan="5"> <?= $form->field($model, 'workhandover',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,"disabled"=>"disabled","disabled"=>"disabled",]) ?></td>
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
	</table>
    <?php ActiveForm::end(); ?>

</div>


</div>
<script>
	$(document).ready(function(){
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
					 url:'<?= yii\helpers\Url::toRoute('leave/delete') ?>',
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
	})
</script>
