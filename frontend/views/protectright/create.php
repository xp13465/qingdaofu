<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\modules\protectright;
use kartik\widgets\DepDrop;
use yii\widgets\ActiveForm;

$this->title = '申请保全';
$this->params['breadcrumbs'][] = ['label' => '保全', 'url' => ['index', 'id'=> $model->primaryKey]];
$this->params['breadcrumbs'][] = $this->title;
$model->area_pid='868';
$showtype = 1;
?>
<div class="right-top">
	<div class="right-topl">申请保全</div>
	<div class="right-topr" >
		<p style="margin-top:44px;">诉讼保全指的是查封被申请人的具体财产，如房产银行账户等</p>
	</div>
</div>
<?= $this->render('_form',compact('model', 'provinceData', 'showtype'));?>