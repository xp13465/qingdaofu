<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Solutionseal */

$this->title = '添加业务单';
$this->params['breadcrumbs'][] = ['label' => '垫资解查封', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solutionseal-create">

    <?= $this->render('_form', [
        'model' => $model,
        'province' => $province,
    ]) ?>

</div>
