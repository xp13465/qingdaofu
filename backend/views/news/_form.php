<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\Redactor;
use common\models\News;

?>

<div class="page-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'page-form',
        'validateOnBlur' => false,
    ])
    ?>

    <div class="row">
        <div class="col-md-9">

            <div class="panel panel-default">
                <div class="panel-body">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
					<?= $form->field($model, 'content')->widget(Redactor::className(),[
					    'options' => [
					        'minHeight' => 400,
					        'imageUpload' => Url::to(['/redactor/upload', 'dir' => 'img']),
					        'fileUpload' => Url::to(['/redactor/upload', 'dir' => 'pages']),
					        'plugins' => ['fullscreen']
					    ]
					]) ?>

                </div>
            </div>
        </div>





        </div>
        
        <div class="col-sm-12">

                        <div class="form-group">
                            <?php if ($model->isNewRecord): ?>
                                <?= Html::submitButton(Yii::t('zcb', 'Create'), ['class' => 'btn btn-primary']) ?>
                                <?= Html::a(Yii::t('zcb', 'Cancel'), ['/page/default/index'], ['class' => 'btn btn-default',]) ?>
                            <?php else: ?>
                                <?= Html::submitButton(Yii::t('zcb', 'Save'), ['class' => 'btn btn-primary']) ?>
                                <?= Html::a(Yii::t('zcb', 'Delete'), ['/page/default/delete', 'id' => $model->primaryKey], [
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




