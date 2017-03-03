<?php
use backend\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\settings\models\Workflow;

?>

<div class="from-form">
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
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'description')->textarea() ?>
    <?= $form->field($model, 'steps')->dropDownList(ArrayHelper::map(Workflow::find()->all(), 'id', 'workname'), ['prompt'=>'请选择']) ?>
                </div>
            </div>
        </div>

        <div class="col-sm-12">

                        <div class="form-group">
                            <?php if ($model->isNewRecord): ?>
                                <?= Html::submitButton(Yii::t('zcb', 'Create'), ['class' => 'btn btn-primary']) ?>
                                <?= Html::a(Yii::t('zcb', 'Cancel'), ['/form/index'], ['class' => 'btn btn-default',]) ?>
                            <?php else: ?>
                                <?= Html::submitButton(Yii::t('zcb', 'Save'), ['class' => 'btn btn-primary']) ?>
                                <?= Html::a(Yii::t('zcb', 'Delete'), ['/from/delete', 'id' => $model->primaryKey], [
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



