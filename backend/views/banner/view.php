<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Banner;

/* @var $this yii\web\View */
/* @var $model app\models\Banner */

$this->title = "横幅广告".$model->id;
$this->params['breadcrumbs'][] = ['label' => "横幅广告", 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$target = Banner::$target;
?>
<div class="banner-view">


    <p>
        <?= Html::a("编辑", ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a("删除", ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', '确定要删除此项么?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
			'title',
			[
				'attribute'=>'target',
				'value'=>isset($target[$model->target])?$target[$model->target]:'',
			],
			'url',
			[
				'attribute'=>'file',
				'value'=>'<img class="imgView" width="200px"  src="'.Yii::$app->params['www'].$model->file.'"/>',
				'format' => 'raw',
			],
            
            'type',
            'sort',
			[
				'attribute'=>'starttime',
				'value'=>$model->starttime?:"永久",
			],
			[
				'attribute'=>'endtime',
				'value'=>$model->endtime?:"永久",
			],
			[
				'attribute'=>'validflag',
				'value'=>$model->validflag?"启用":'删除',
			],
			// [
				// 'attribute'=>'create_by',
				// 'value'=>$model->createuser?$model->createuser->username:'',
			// ],
			[
				'attribute'=>'create_at',
				'value'=>$model->create_at?date("Y-m-d H:i:s",$model->create_at):'',
			],
            
			// [
				// 'attribute'=>'update_by',
				// 'value'=>$model->updateuser?$model->updateuser->username:'',
			// ],
			[
				'attribute'=>'modify_at',
				'value'=>$model->modify_at?date("Y-m-d H:i:s",$model->modify_at):'',
			],
        ],
    ]) ?>

</div>
