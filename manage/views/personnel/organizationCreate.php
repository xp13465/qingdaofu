<?php

use yii\helpers\Html; 
use yii\widgets\ActiveForm; 

$this->title = '新增机构';
$this->params['breadcrumbs'][] = ['label' => '机构列表', 'url' => ['organization-index']]; 
$this->params['breadcrumbs'][] = $this->title; 
?> 

<div class="organization-form"> 
    <?php $form = ActiveForm::begin(); ?> 

    <?= $form->field($model, 'organization_name')->textInput(['maxlength' => true,'style'=>'width:300px']) ?>
    <?= $form->field($model, 'organization_full_name')->textInput(['maxlength' => true,'style'=>'width:300px']) ?>
	<?= $form->field($model, 'office')->label('机构地址')->textInput(['maxlength' => true,'style'=>'width:300px']) ?>
    <div class="form-group"> 
        <?= Html::submitButton($model->isNewRecord ? '确定' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?> 
    </div> 

    <?php ActiveForm::end(); ?> 

</div> 