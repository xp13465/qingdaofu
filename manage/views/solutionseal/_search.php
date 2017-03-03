<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SolutionsealSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="solutionseal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'solutionsealid') ?>

    <?= $form->field($model, 'productid') ?>

    <?= $form->field($model, 'personnel_id') ?>

    <?= $form->field($model, 'number') ?>

    <?= $form->field($model, 'category') ?>

    <?php // echo $form->field($model, 'category_other') ?>

    <?php // echo $form->field($model, 'entrust') ?>

    <?php // echo $form->field($model, 'entrust_other') ?>

    <?php // echo $form->field($model, 'account') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'typenum') ?>

    <?php // echo $form->field($model, 'overdue') ?>

    <?php // echo $form->field($model, 'province_id') ?>

    <?php // echo $form->field($model, 'city_id') ?>

    <?php // echo $form->field($model, 'district_id') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'browsenumber') ?>

    <?php // echo $form->field($model, 'validflag') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'create_by') ?>

    <?php // echo $form->field($model, 'modify_at') ?>

    <?php // echo $form->field($model, 'modify_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
