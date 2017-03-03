<?php
use yii\helpers\Url;


$this->title = Yii::t('zcb', 'Update "{item}"', ['item' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('zcb', 'Form'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->primaryKey]];
$this->params['breadcrumbs'][] = Yii::t('zcb', 'Update');

$action = $this->context->action->id;

?>

<div class="page-create">
<div class="nav-tabs-justified">
    <ul class="nav nav-tabs">
        <li <?= ($action === 'update') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/form-category/update', 'id' => $model->primaryKey]) ?>"><?= Yii::t('zcb', 'Edit') ?></a></li>
        <li <?= ($action === 'fields') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/form-category/fields', 'id' => $model->primaryKey]) ?>"><span class="glyphicon glyphicon-cog"></span> <?= Yii::t('zcb', 'Fields') ?></a></li>
    </ul>
</div>

   <?= $this->render('_form', compact('model')) ?>
</div>