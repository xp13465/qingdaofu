<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Album */

$this->title = '添加文章';
$this->params['breadcrumbs'][] = ['label' => '新闻列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="album-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
