<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\modules\Protectright;
// use kartik\widgets\ActiveForm;
// use kartik\form\ActiveField;
use kartik\widgets\DepDrop;
// use frontend\widget\JsBlock;
use yii\widgets\ActiveForm;  

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = '我的保全-完善资料';
$this->params['breadcrumbs'][] = ['label' => '保全', 'url' => ['index', 'id'=> $model->primaryKey]];
$this->params['breadcrumbs'][] = $this->title;
$model->area_pid='868';
$showtype = 2;

?>
<div class="write-top">
    <p>我的保全-完善资料</p>
</div>
<?=$this->render("_form",compact('model', 'showtype'))?>

