<?php
use yii\helpers\Html;

$this->title = Yii::t('zcb', 'Create page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('zcb', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-create">
    <?= $this->render('_form', compact('model')) ?>
</div>
