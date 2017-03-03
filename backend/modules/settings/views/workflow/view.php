<?php

use yii\helpers\Html;
use common\models\FinanceProduct;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = $model->workname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('zcb', 'Finance'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->workname;
?>
<div class="order-view">

    <h1><?= Html::encode($model->workname) ?></h1>

    <p>
    <?= Html::a(Yii::t('zcb', 'Index'), ['index'], ['class' => 'btn btn-success']) ?>
<?php //Html::a(Yii::t('zcb','Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

<?php
    $category = common\models\FinanceProduct::$categorys;
$status = common\models\FinanceProduct::$status;
?>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'steps',
            'workname',
            'description',
        ],
    ]) ?>

</div>
