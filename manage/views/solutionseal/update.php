<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Solutionseal */

$this->title = '修改业务单' . $model->solutionsealid;
$this->params['breadcrumbs'][] = ['label' => '垫资解查封', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->solutionsealid, 'url' => ['view', 'id' => $model->solutionsealid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="solutionseal-update">

    <?= $this->render('_form', [
        'model' => $model,
        'province' => $province,
    ]) ?>

</div>
