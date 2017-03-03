<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = '创建后台用户';
$this->params['breadcrumbs'][] = ['label' => Yii::t('zcb', 'Create Admin user'), 'url' => ['/admin/index', 'id' => $model->id]];

?>
<div class="form-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
