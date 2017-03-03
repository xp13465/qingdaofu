<?php

use yii\helpers\Html; 
use yii\widgets\ActiveForm; 
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */ 
/* @var $model app\models\Department */ 
/* @var $form yii\widgets\ActiveForm */ 

$this->title = $model->isNewRecord?'新增部门':'修改部门';
$this->params['breadcrumbs'][] = ['label' => '部门列表', 'url' => ['department-index']];
$this->params['breadcrumbs'][] = $this->title;
?> 

<div class="department-form"> 

    <?php $form = ActiveForm::begin(); ?> 

    <?= $form->field($model, 'organization_id')->label('机构')->dropDownList(ArrayHelper::map($organization,'organization_id','organization_name'), ['prompt' => '','style'=>'width:200px;border-radius: 4px;']) ?>
    <?= $form->field($model, 'second')->label('')->radioList(['0'=>'部门','1'=>'二级部门']) ?>
	<?= $form->field($model, 'pid')->label('部门',['style'=>'display:none;','id'=>'bumen'])->dropDownList(ArrayHelper::map($department,'id','name'), ['prompt' => '请选择上级部门','style'=>'width:200px;border-radius: 4px;display:none;']) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'style'=>'width:200px;']) ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true,'style'=>'width:200px;']) ?>

    <div class="form-group"> 
        <?= Html::submitButton($model->isNewRecord ? '确定' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?> 
    </div> 

    <?php ActiveForm::end(); ?> 

</div>
<script>
$(document).ready(function(){
	var second = '<?= isset($model->second)&&$model->second?$model->second:''?>';
	if(second){
		$('#department-pid').css('display','block');
		$('#bumen').css('display','block');
	}
	$('input[name="Department[second]"]').click(function(){
		var type = $('input:radio:checked').val();
		if(type == 1){
			$('#department-pid').css('display','block');
			$('#bumen').css('display','block');
			
		}else{
			$('#department-pid').css('display','none');
			$('#bumen').css('display','none');
		}
	})
})
</script> 