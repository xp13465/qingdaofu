<?php
use yii\helpers\Url;


$this->title = Yii::t('zcb', 'Update "{item}"', ['item' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('zcb', 'Post'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->primaryKey]];
$this->params['breadcrumbs'][] = Yii::t('zcb', 'Update');

$action = $this->context->action->id;

?>

<div class="page-create">
    <?= $this->render('_form', compact('model')) ?>
</div>