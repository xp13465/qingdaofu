<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use manage\models\Admin;

$this->title = '修改密码';
?>

<div class="form-form">
<?php
    $form = ActiveForm::begin([
        'id' => 'form-form',
        'validateOnBlur' => false,
    ])
    ?>

    <div class="row">
    <div class="col-md-9">
    <div class="panel panel-default">
    <div class="panel-body">

    <?= $form->field($model, 'username')->textInput(['disabled' => 'disabled']) ?>
    <?= $form->field($model, 'oldpassword')->label("原密码")->passwordInput(['value' => '','name' => 'oldpassword']) ?> 
    <?= $form->field($model, 'password_hash')->label("新密码")->passwordInput(['value' => '','name' => 'password_hash']) ?> 
    </div>
    </div>
    </div>


    
    <div class="col-sm-12">

    <div class="form-group">
  
 
    <?= Html::submitButton("修改", ['class' => 'btn btn-primary']) ?>
    
 
    </div>

    </div><!-- col-sm120 -->
        
    </div><!-- row -->

<?php ActiveForm::end(); ?>
    </div>



