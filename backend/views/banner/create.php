<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Banner */

$this->title =  '添加横幅广告';
$this->params['breadcrumbs'][] = ['label' => "横幅广告", 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-create">
 

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
