<?php

use yii\helpers\Html;
use common\models\FinanceProduct;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = $model->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('zcb', 'Finance'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <?= Html::a(Yii::t('zcb', 'Index'), ['index'], ['class' => 'btn btn-success']) ?>
<?php //Html::a(Yii::t('zcb','Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

<?php
    $category = common\models\CreditorProduct::$categorys;
?>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'category',
                'value' => $category[$model->category],
            ],
            'code',
            'money',
            'term',
            'rate',
            'rate_cat',
            'rebate',
            'seatmortgage',
            'user.username',
            'progress_status',
            'create_time:datetime',
        ],
    ]) ?>

</div>
