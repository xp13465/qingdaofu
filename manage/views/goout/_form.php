<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AttendanceGoout */
/* @var $form yii\widgets\ActiveForm */
$startday = date("Y-m-d",strtotime("-2 Month",time()));
?>

<style>
a:hover{text-decoration:none;}
.attendance-goout-form input.form-control{display:inline-block;}
.attendance-goout-form .form-control{border:1px solid #fff;}
.attendance-goout-form .has-error .form-control{border:1px solid #a94442;}
.attendance-goout-form td{height:40px;}
.attendance-goout-form tr.tdtitle{padding:0 5px;font-weight:bold;text-align:center;}
#attendancegoout-leavetype label{margin:0 10px;}
.field-attendancegoout-leavetype div{display:inline-block}
.field-attendancegoout-leavetype .help-block{margin:0;}
.error-summary{color:red;}
.error-summary ul{margin:0;padding:0;}
.error-summary ul li{float:left;margin-left:24px}
.form-group .add{
	background:#0da3f8 none repeat scroll 0 0;
	border: medium none;
	border-radius: 5px;
	color: #fff;
	font-size: 14px;
    height: 30px;
    line-height: 30px;
    position: absolute;
    text-align: center;
    transition: all 0.3s ease 0s;
    width: 5%;}
</style>
<div class="attendance-goout-form">

    <?php $form = ActiveForm::begin(); ?>
	<?=$form->errorSummary($model)?>
		<div class="form-group" style='float:right; width:200px; height:30px; margin-right:45px; '>
		    <b style='width:129px;height: 30px;line-height: 30px;float:left;'><?= date('Y年m月d日',time())?></b>
			<!--<a class="add" href="javascript:void(0);" data-id="40">添加一行</a>-->
		</div><br>
      <table border=1 width="1120">
	      <tr class='tdtitle'>
		     <td>序号</td>
			 <td>姓名</td>
			 <td>外出日期</td>
			 <td>事由（详细）</td>
			 <td>出公司时间</td>
			 <td>回公司时间（预计）</td>
			 <td>回公司时间 （实际）</td>
			 <td>领导批准</td>
			 <td>备注</td>
		  </tr>
		  <tr class='capacity'>
		     <td style="width:50px; text-align:center;">1</td>
			 <td style="width:80px;">
				<?= $form->field($model, 'username',['options'=>['tag'=>'div'],'template'=>'{input}'])->label("")->widget(\yii\jui\AutoComplete::classname(), ["options"=>["class"=>"form-control",'disabled'=>'disabled']]) ?>
				
				<?= $form->field($model, 'employeeid',['options'=>['tag'=>'div'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
				<?= $form->field($model, 'personnel_id',['options'=>['tag'=>'div'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
				<?= $form->field($model, 'toexamineid',['options'=>['tag'=>'div'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
			 </td>
			 <td style="width:100px;"><?php echo $form->field($model, 'gooutdate',['options'=>['tag'=>'div'],'template'=>'{input}'])->widget(\kartik\date\DatePicker::classname(),
				[
							'name' => 'Attendancegoout[gooutdate]',
							'id' => 'attendancegoout-gooutdate',
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd',
								'startDate' => $startday,
							]
				]);?></td>
			 <td style="width:200px;"><?= $form->field($model, 'description',['options'=>['tag'=>'span'],'template'=>'{input}'])->textArea(['maxlength' => true,'style' => "resize:none;height:39px;"]) ?></td>
			 <td style="width:140px;"><?php echo $form->field($model, 'gooutstart',['options'=>['tag'=>'div'],'template'=>'{input}'])->widget(\kartik\datetime\DateTimePicker::classname(),
				[
							'name' => 'Attendancegoout[gooutstart]',
							'id' => 'attendancegoout-gooutstart',
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd hh:ii',
								'startDate' => $startday,
							]
				]);?></td>
			<td style="width:140px;"><?php echo $form->field($model, 'gooutend',['options'=>['tag'=>'span'],'template'=>'{input}'])->widget(\kartik\datetime\DateTimePicker::classname(),
				[
							'name' => 'Attendancegoout[gooutend]',
							'id' => 'attendancegoout-gooutend',
							// 'layout' => "{input}{remove}",
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd hh:ii',
								'startDate' => $startday,
							]
				]);?></td>
			<td style="width:140px;"><?php echo $form->field($model, 'gooutend_valid',['options'=>['tag'=>'span'],'template'=>'{input}'])->widget(\kartik\datetime\DateTimePicker::classname(),
				[
							'name' => 'Attendancegoout[gooutend_valid]',
							'id' => 'attendancegoout-gooutend_valid',
							// 'layout' => "{input}{remove}",
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd hh:ii',
								'startDate' => $startday,
							]
				]);?></td>
			 <td style="width:120px;"><?= $form->field($model, 'supervisorsignature',['options'=>['tag'=>'span'],'template'=>'{input}'])->textInput(['maxlength' => true,'disabled'=>'disabled']) ?></td>
			 <td style="width:120px;">
			 <?= $form->field($model, 'supervisormemo',['options'=>['tag'=>'span'],'template'=>'{input}'])->textArea(['maxlength' => true,'style' => "resize:none;border:0px;height:39px;",'disabled'=>'disabled']) ?>
			 <?= $form->field($model, 'status',['options'=>['tag'=>'span'],'template'=>'{input}'])->hiddenInput(['maxlength' => true,'value'=>'']) ?>
			 </td>
		  </tr>
	  </table>
 
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '草稿' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','data-type'=>'0']) ?>
		<?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','data-type'=>'20']) ?>
    </div> 
    <?php ActiveForm::end(); ?>

</div>
<script>
     $(document).ready(function(){
		 $(':button').click(function(){
			 $('#attendancegoout-status').val($(this).attr('data-type'));
		 })
		// var i= 2;
		// $('.add').click(function(){
			// $("table .capacity:last").after($("table tr:eq(1)").clone()).next().children(':first').html(i++);
		// })
	 })
</script>
