<?php

use yii\helpers\Html; 
use yii\widgets\ActiveForm; 
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */ 
/* @var $model app\models\Department */ 
/* @var $form yii\widgets\ActiveForm */ 

$this->title = $model->isNewRecord?'新增岗位':'修改岗位';
$this->params['breadcrumbs'][] = ['label' => '岗位列表', 'url' => ['post-index']];
$this->params['breadcrumbs'][] = $this->title;
?> 

<div class="post-form"> 

    <?php $form = ActiveForm::begin(); ?> 

    <?= $form->field($model, 'type')->label('')->radioList(['0'=>'通用岗位','1'=>'部门岗位']) ?>
	<?= $form->field($model, 'department_id')->label('部门',['style'=>'display:none;','id'=>'bumen'])->dropDownList(ArrayHelper::map($html,'id','name'), ['prompt' => '请选择','style'=>'width:200px;border-radius: 4px;display:none;']) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'style'=>'width:200px;']) ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true,'style'=>'width:200px;']) ?>

    <div class="form-group"> 
        <?= Html::submitButton($model->isNewRecord ? '确定' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?> 
    </div> 

    <?php ActiveForm::end(); ?> 

</div>
<script>
$(document).ready(function(){
	var type = '<?= isset($model->type)&&$model->type?$model->type:''?>';
	if(type){
		$('#post-department_id').css('display','block');
		$('#bumen').css('display','block');
	}
	$('input[name="Post[type]"]').click(function(){
		var types = $('input:radio:checked').val();
		if(types == 1){
			$('#post-department_id').css('display','block');
			$('#bumen').css('display','block');
			
		}else{
			$('#post-department_id').css('display','none');
			$('#bumen').css('display','none');
		}
	})
})
</script> 