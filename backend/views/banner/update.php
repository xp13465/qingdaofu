<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Banner */

$this->title = "编辑横幅广告#". $model->id;
$this->params['breadcrumbs'][] = ['label' => "横幅广告", 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => "#".$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = "编辑";
?>
<div class="banner-update">
 
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
