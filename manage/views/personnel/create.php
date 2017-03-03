<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Personnel */

$this->title = '新增角色';
$this->params['breadcrumbs'][] = ['label' => '角色列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personnel-create">
    <?= $this->render('_form', [
        'model' => $model,
		'organization' => $organization,
    ]) ?>

</div>
