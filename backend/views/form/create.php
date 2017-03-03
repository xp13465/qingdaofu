<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = '创建表单';
$this->params['breadcrumbs'][] = ['label' => Yii::t('zcb', 'Form'), 'url' => ['/form/index', 'id' => $formcategory->id]];
//$this->params['breadcrumbs'][] = ['label' => Yii::t('zcb', 'Form'), 'url' => ['/form/index', 'id' =>$model->category->primaryKey]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'dataForm' => $dataForm,
        'formcategory' => $formcategory,
    ]) ?>

</div>
