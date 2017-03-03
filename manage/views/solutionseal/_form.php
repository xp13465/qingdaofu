<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use yii\jui\AutoComplete;

/* @var $this yii\web\View */
/* @var $model app\models\Solutionseal */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.field-solutionseal-category_other .help-block{display:inline}
.field-province .form-control,.field-city .form-control,.field-district .form-control{display: inline-block;}
.field-solutionseal-typenum .control-label {display: inline-block;}
.solutionsealform tr {height:35px;}
.solutionseal-form .help-block{margin-top:0;margin-bottom:0;}
</style>
<div class="solutionseal-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<table border=1 width="1000" class="solutionsealform" >
	<colgroup>
		<col width="120"/>
		<col/>
	</colgroup>
		<tr>
				<td>
				<?= $form->field($model, 'custname',['options'=>['tag'=>'span'],'template'=>'{label}'])->label("客户姓名") ?>
				</td>
				<td><?= $form->field($model, 'custname',['options'=>['tag'=>'div'],'template'=>'{input}{label}'])->label("")->textInput(['maxlength' => true,'style' => "width:300px;display:inline-block"]) ?></td>
		</tr>
		<tr>
				<td>
				<?= $form->field($model, 'custmobile',['options'=>['tag'=>'span'],'template'=>'{label}'])->label("联系方式") ?>
				</td>
				<td><?= $form->field($model, 'custmobile',['options'=>['tag'=>'div'],'template'=>'{input}{label}'])->label("")->textInput(['maxlength' => true,'style' => "width:300px;display:inline-block"]) ?></td>
		</tr>
		<tr>
				
			<td>
				<?= $form->field($model, 'personnel_name',['options'=>['tag'=>'div'],'template'=>'{label}'])->label("销售姓名");?>
			</td>
			<td>
				<?= $form->field($model, 'personnel_name',['options'=>['tag'=>'span'],'template'=>'{input}'])->widget(AutoComplete::classname(), [
					'clientOptions' => [
						'source' => "/personnel/autocomplete",
					],
					"options"=>["class"=>"form-control",'style' => "width:300px;display:inline-block"],
					'clientEvents' => [
					   'select'=>'function(event,ui){
						   $("#solutionseal-personnel_id").val(ui.item.personnel_id);
						   $(".field-solutionseal-personnel_id label").html(ui.item.name);
						}',
						'change'=>'function(event,ui){
						    if(ui.item){
								$("#solutionseal-personnel_id").val(ui.item.personnel_id);
								$(".field-solutionseal-personnel_id label").html(ui.item.name);	
							}else{
								$("#solutionseal-personnel_id").val(0);
								$(".field-solutionseal-personnel_id label").html("");
							}  
						}',
					],
				]) ?>
				员工：<?= $form->field($model, 'personnel_id',['options'=>['tag'=>'span'],'template'=>'{input}{label}'])->label("")->hiddenInput() ?>
			</td>
		</tr>
		
		<tr>
			<td>
				<?= $form->field($model, 'category',['options'=>['tag'=>'div',"style"=>"display:inline-block"],'template'=>'{label}'])->label("债权类型")->checkboxList(\app\models\Product::$category);; ?>
			</td>
			<td>
				<?= $form->field($model, 'category',['options'=>['tag'=>'div',"style"=>"display:inline-block;height: 24px;"],'template'=>'{input}'])->checkboxList(\app\models\Product::$category); ?>
				<?= $form->field($model, 'category_other',['options'=>['tag'=>'span',"style"=>"display:inline-block;",],'template'=>'{input}'])->textInput(['maxlength' => true,'style' => "display:none;width:150px;"]) ?>
				<?= $form->field($model, 'category',['options'=>['tag'=>'span',"style"=>"display:inline-block",],'template'=>'{error}'])->checkboxList(\app\models\Product::$category); ?>
				<?= $form->field($model, 'category_other',['options'=>['tag'=>'span',"style"=>"display:inline-block",],'template'=>'{error}']) ?>
			
			</td>
		</tr>
		
		<tr>
			<td>
				<?= $form->field($model, 'entrust_other',['options'=>['tag'=>'span'],'template'=>'{label}'])->label("委托事项") ?>
			</td>
			<td>
				<?= $form->field($model, 'entrust_other',['options'=>['tag'=>'span'],'template'=>'{input}{error}'])->textInput(['maxlength' => true,'style' => "width:300px;"]) ?>
			</td>
		</tr>
		
		<tr>
			<td>
				 <?= $form->field($model, 'account',['options'=>['tag'=>'span'],'template'=>'{label}']) ?>
			</td>
			<td>
				 <?= $form->field($model, 'account',['options'=>['tag'=>'span'],'template'=>'{input}{label}{error}'])->label("元")->textInput(['style' => "width:300px;display:inline-block"]) ?>
			</td>
		</tr>
		
		<tr>
			<td>
				<?= $form->field($model, 'type',['options'=>['tag'=>'span'],'template'=>'{label}']);?>
			</td>
			<td>
				<?= $form->field($model, 'type',['options'=>['tag'=>'span',"style"=>"display:inline-block",],'template'=>'{input}'])->dropDownList([ 1 => '固定费用', 2 => '比例费用', ], ['prompt' => '']) ?>
				<?= $form->field($model, 'typenum',['options'=>['tag'=>'span',"style"=>"display:inline-block;",],'template'=>'{input}{label}'])->label("")->textInput(['maxlength' => true,"style"=>"width:100px;display:inline;width:100px;"]) ?>
			</td>
		</tr>
		
		<tr>
			<td>
				 <?= $form->field($model, 'overdue',['options'=>['tag'=>'span'],'template'=>'{label}']) ?>
			</td>
			<td>
				 <?= $form->field($model, 'overdue',['options'=>['tag'=>'span'],'template'=>'{input}{label}{error}'])->label("月")->textInput(['style' => "width:300px;display:inline-block"]) ?>
			</td>
		</tr>
		<tr>
			<td>
				 <?= $form->field($model, 'province_id',['options'=>['tag'=>'span'],'template'=>'{label}'])->label("合同履行地") ?>
			</td>
			<td>
				 <?= $form->field($model, 'province_id',['options'=>['tag'=>'span'],'template'=>'{input}'])->dropDownList(
					ArrayHelper::map($province,'provinceID','province'),
					['id'=>'province','class'=>'form-control','placeholder'=>"请选择",'style'=>'width:131px;']
				 ) ?>
				 
				  <?= $form->field($model, 'city_id',['options'=>['tag'=>'span'],'template'=>'{input}'])->dropDownList(
						[""=>"请选择..."],
						['id'=>'city','class'=>'form-control','placeholder'=>"请选择",'style'=>'width:131px;','data-krajee-depdrop'=>'depdrop_2193fc09']
				 ) ?>
				  <?= $form->field($model, 'district_id',['options'=>['tag'=>'span'],'template'=>'{input}'])->dropDownList(
					[""=>"请选择..."],
					['id'=>'district','class'=>'form-control','placeholder'=>"请选择",'style'=>'width:131px;','data-krajee-depdrop'=>'depdrop_26c092ab']
				 ) ?>
				
			</td>
			<?= $form->field($model, 'status',['options'=>['tag'=>'span'],'template'=>'{input}'])->hiddenInput(['maxlength' => true,'value'=>'']) ?>
		</tr>
	</table>
	<br/>
	<?php if(!$model->status){?>
		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? '草稿' : '保存', ['class' => 'btn btn-primary','data-type'=>'0']) ?>
			<?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' =>'btn btn-success','data-type'=>'11']) ?>
		</div>
	<?php } ?>

    <?php ActiveForm::end(); ?>

</div>
<script>
$(document).ready(function(){
	
	$(':button').click(function(){
			 $('#solutionseal-status').val($(this).attr('data-type'));
	})
	var personnel_name = "<?= isset($model['personnel_name'])?$model['personnel_name']:''?>";
	if(personnel_name){
		$(".field-solutionseal-personnel_id label").html(personnel_name);	
	}
	
	var city_id = "<?= isset($model['city_id'])?$model['city_id']:''?>";
	var district_id = "<?= isset($model['district_id'])?$model['district_id']:''?>";
	var _csrf = "<?= Yii::$app->request->csrfToken ?>";
	
	$('input[name="Solutionseal[category][]"][value="4"]').change(function(){
		if($('input[name="Solutionseal[category][]"][value="4"]').prop('checked')){
			$('#solutionseal-category_other').show()
		}else{
			$('#solutionseal-category_other').hide()
		}
	}).trigger("change");
	
	$('#solutionseal-type').change(function(){
		var type = $(this).val();
		if(type == 1){
			$(".field-solutionseal-typenum .control-label").html("元");
			$(".field-solutionseal-typenum").show();
		}else if(type == 2){
			$(".field-solutionseal-typenum .control-label").html("%");
			$(".field-solutionseal-typenum").show();
		}else{
			$(".field-solutionseal-typenum").hide();
		}
		
		
	}).trigger("change");
	
	
	
	
	$('#province').change(function(){
		var province_id = $(this).val();
		$.ajax({
			url:'<?= Yii\helpers\Url::toRoute('/site/city'); ?>',
			type:'post',
			data:{province_id:province_id,_csrf:_csrf},
			dataType:'json',
			success:function(json){
				var html='<option value="0" selected="">请选择...</option>';
				if(json['code'] == '0000'){
					$('#city').html(html+json['data']['html']);
					if(city_id){
						$('#city').val(city_id).trigger("change")
					}
				}
			}
		})
	}).trigger("change");
	
	$('#city').change(function(){
		var city_id = $(this).val();
		$.ajax({
			url:'<?= Yii\helpers\Url::toRoute('/site/district'); ?>',
			type:'post',
			data:{city_id:city_id},
			dataType:'json',
			success:function(json){
				var html='<option value="0" selected="">请选择...</option>';
				if(json['code'] == '0000'){
						$('#district').html(html+json['data']['html']);
					if(district_id){
						$('#district').val(district_id)
					}
				}
			}
		})
	});
	
})
</script>