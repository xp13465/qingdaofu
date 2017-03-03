<?php
use backend\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Admin;
use backend\models\Post;

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

    <?= $form->field($model, 'username')->textInput($this->context->action->id === 'update' ? ['disabled' => 'disabled'] : []) ?>
    <?= $form->field($model, 'password_hash')->passwordInput(['value' => '']) ?>
    <?php //= $form->field($model, 'group')->dropDownList(ArrayHelper::map(Post::find()->all(), 'id', 'name'), ['prompt'=>'请选择']) ?>
    <?= $form->field($model, 'post_id')->dropDownList(ArrayHelper::map(Post::find()->all(), 'id', 'name'), ['prompt'=>'请选择']) ?>
    <?= $form->field($model, 'status')->dropDownList(Admin::getStatusList(), ['prompt'=>'请选择']) ?>
    </div>
    </div>
    </div>


    
    <div class="col-sm-12">

    <div class="form-group">
<?php if ($model->isNewRecord): ?>
    <?= Html::submitButton(Yii::t('zcb', 'Create'), ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('zcb', 'Cancel'), ['/admin/index'], ['class' => 'btn btn-default',]) ?>
<?php else: ?>
    <?= Html::submitButton(Yii::t('zcb', 'Save'), ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('zcb', 'Delete'), ['/admin/delete', 'id' => $model->primaryKey], [
        'class' => 'btn btn-default',
        'data' => [
            'confirm' => "是否要删除该帐号？",
            'method' => 'post',
        ],
    ]) ?>
<?php endif; ?>
    </div>

    </div><!-- col-sm120 -->
        
    </div><!-- row -->

<?php ActiveForm::end(); ?>
    </div>



