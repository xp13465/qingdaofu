<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\modules\Policy;
// use kartik\widgets\ActiveForm;
// use kartik\form\ActiveField;
use kartik\widgets\DepDrop;
// use frontend\widget\JsBlock;
use yii\widgets\ActiveForm;  

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = '申请保函';
$this->params['breadcrumbs'][] = ['label' => '保单', 'url' => ['index', 'id'=> $model->primaryKey]];
$this->params['breadcrumbs'][] = $this->title;
$model->area_pid='868';
$showtype = 1;

?>
<div class="right-top">
  <div class="right-topl">申请保函</div>
  <div class="right-topr">
    <p>申请保函是在诉讼保全过程中法院要求提供诉讼前财产保全相应的担保所需要的材料由担保公司或保险公司开具</p>
  </div>
</div>
<?=$this->render("_form",compact('model', 'provinceData', 'showtype'))?>
