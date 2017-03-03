<?php
use yii\helpers\Url;


$this->title = Yii::t('zcb', 'Update "{item}"', ['item' => $model->workname]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('zcb', 'Form'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->workname, 'url' => ['view', 'id' => $model->primaryKey]];
$this->params['breadcrumbs'][] = Yii::t('zcb', 'Update');

$action = $this->context->action->id;

?>

   <?= $this->render('_form', compact('model')) ?>
</div>