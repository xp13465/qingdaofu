<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\AttendanceGoout */

$this->title = '详情#'.$model->id;
$this->params['breadcrumbs'][] = ['label' => '列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$type = Yii::$app->request->get('type')?Yii::$app->request->get('type'):'';
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
		  </tr>
		  <tr class='capacity'>
		     <td style="width:110px; text-align:center;">1</td>
			 <td style="width:100px;">
				<?= $form->field($model, 'username',['options'=>['tag'=>'div'],'template'=>'{input}'])->label("")->widget(\yii\jui\AutoComplete::classname(), ["options"=>["class"=>"form-control",'disabled'=>'disabled']]) ?>
				
				<?= $form->field($model, 'employeeid',['options'=>['tag'=>'div'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
				<?= $form->field($model, 'personnel_id',['options'=>['tag'=>'div'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
			 </td>
			 <td style="width:100px;"><?php echo $form->field($model, 'gooutdate',['options'=>['tag'=>'div'],'template'=>'{input}'])->widget(\kartik\date\DatePicker::classname(),
				[
							'name' => 'Attendancegoout[gooutdate]',
							'id' => 'attendancegoout-gooutdate',
							'disabled'=>'disabled',
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd',
							]
				]);?></td>
			 <td style="width:200px;"><?= $form->field($model, 'description',['options'=>['tag'=>'span'],'template'=>'{input}'])->textArea(['maxlength' => true,'style' => "resize:none;height:39px;",'disabled'=>'disabled']) ?></td>
			 <td style="width:140px;"><?php echo $form->field($model, 'gooutstart',['options'=>['tag'=>'div'],'template'=>'{input}'])->widget(\kartik\datetime\DateTimePicker::classname(),
				[
							'name' => 'Attendancegoout[gooutstart]',
							'id' => 'attendancegoout-gooutstart',
							'disabled'=>'disabled',
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd hh:ii',
							]
				]);?></td>
			<td style="width:140px;"><?php echo $form->field($model, 'gooutend',['options'=>['tag'=>'span'],'template'=>'{input}'])->widget(\kartik\datetime\DateTimePicker::classname(),
				[
							'name' => 'Attendancegoout[gooutend]',
							'id' => 'attendancegoout-gooutend',
							'disabled'=>'disabled',
							// 'layout' => "{input}{remove}",
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd hh:ii',
							]
				]);?></td>
			<td style="width:140px;"><?php echo $form->field($model, 'gooutend_valid',['options'=>['tag'=>'span'],'template'=>'{input}'])->widget(\kartik\datetime\DateTimePicker::classname(),
				[
							'name' => 'Attendancegoout[gooutend_valid]',
							'id' => 'attendancegoout-gooutend_valid',
							'disabled'=>'disabled',
							// 'layout' => "{input}{remove}",
							'type' => 1, 
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd hh:ii',
							]
				]);?></td>
				
		  </tr>
	      
		  <tr height="70">
		<td class='tdtitle' rowspan="2">部门主管意见</td>
		<td colspan="6">
			<?= $form->field($model, 'supervisormemo',['options'=>['tag'=>'span'],'template'=>'{input}'])->textArea(['maxlength' => true,"disabled"=>"disabled","disabled"=>"disabled",'style' => "resize:none;height:70px;"]) ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
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
		<td colspan="6">
			<?= $form->field($model, 'administrationmemo',['options'=>['tag'=>'span'],'template'=>'{input}'])->textArea(['maxlength' => true,"disabled"=>"disabled","disabled"=>"disabled",'style' => "resize:none;height:70px;"]) ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
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
		<td colspan="6">
			<?= $form->field($model, 'generalmanagermemo',['options'=>['tag'=>'span'],'template'=>'{input}'])->textArea(['maxlength' => true,"disabled"=>"disabled","disabled"=>"disabled","disabled"=>"disabled",'style' => "resize:none;height:70px;"]) ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
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
	  
 <?php if(!$model->status && $type){?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '草稿' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','data-type'=>'0']) ?>
		<?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','data-type'=>'20']) ?>
    </div>
 <?php } ?>
    <?php ActiveForm::end(); ?>

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
					 url:'<?= yii\helpers\Url::toRoute('goout/delete') ?>',
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