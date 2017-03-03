<?php
use backend\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

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

    <?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'bank')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'bank_account')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'item')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'item_money')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'item_type')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'pay_type')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'cny')->textInput(['maxlength' => true]) ?>
    
    <?= $dataForm ?>
    
    </div>
    </div>
    </div>


        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-body">
    <div class="record-info">

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'department')->textInput(['maxlength' => true]) ?>
    
                    </div>
                    
                    <div class="record-info">

					</div>
					
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-body">
    <div class="record-info">
        <?= $form->field($model, 'pay_time')->widget(DateTimePicker::className()); ?>
    <?= $form->field($model, 'pay_name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'remark')->textarea(['rows' => '6']) ?>
                    </div>
                </div>
            </div>

        </div><!-- end col-md-3  -->
    
    <div class="col-sm-12">

    <div class="form-group">
<?php if ($model->isNewRecord): ?>
    <?= Html::submitButton(Yii::t('zcb', 'Create'), ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('zcb', 'Cancel'), ['/form/index'], ['class' => 'btn btn-default',]) ?>
<?php else: ?>
    <?= Html::submitButton(Yii::t('zcb', 'Save'), ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('zcb', 'Delete'), ['/from/index', 'id' => $model->primaryKey], [
        'class' => 'btn btn-default',
        'data' => [
            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'method' => 'post',
        ],
    ]) ?>
<?php endif; ?>
    </div>

    </div><!-- col-sm120 -->
        
    </div><!-- row -->

<?php ActiveForm::end(); ?>
    </div>



