<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProtectrightSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="protectright-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'cardNo') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'account') ?>

    <?php // echo $form->field($model, 'jietiao') ?>

    <?php // echo $form->field($model, 'yinhang') ?>

    <?php // echo $form->field($model, 'danbao') ?>

    <?php // echo $form->field($model, 'caichan') ?>

    <?php // echo $form->field($model, 'other') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
