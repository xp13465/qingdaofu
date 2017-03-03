<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
$title = $this->title?$this->title:$this->context->title;
$title=$title?$title:Yii::$app->name;
AppAsset::register($this);
Yii::$app->view->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($title) ?></title>
    <meta name="renderer" content="webkit" />
    <link rel="shortcut icon" type="image/ico" href="/images/favicon.png"/>
    <meta content="<?= Html::encode($this->context->keywords) ?>" name="keywords">
    <meta content="<?= Html::encode($this->context->description) ?>" name="description">
    <?php echo $this->registerCssFile('/css/base.css');?>
    <?php echo $this->registerCssFile('/css/style.css');?>
    <?php //echo $this->registerCssFile('/css/base1.css');?>
    <?php echo $this->registerCssFile('/js/msgbox/jquery.msgbox.css');?>
	<?php echo $this->registerCssFile('/bate2.0/css/index.css');?>
	<?php echo $this->registerCssFile('/bate2.0/css/center.css');?>
    <?php echo $this->registerJsFile('/js/jquery-1.11.1.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/jquery.validate.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/msgbox/jquery.msgbox.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/index.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/card.js',['position'=>\Yii\Web\View::POS_END]);?>
    <?php echo $this->registerJsFile('/js/My97DatePicker/WdatePicker.js',['position'=>\Yii\Web\View::POS_END]);?>
	<?php echo $this->registerJsFile('/js/layer/layer.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php $this->head() ?>
</head>
<?php $this->beginBody() ?>
<?php echo $this->render('header')?>
<?php //echo $this->renderFile('@app/views/common/function.php')?>

<!-- 内容左边菜单开始-->

<div class="cont">
    <div class="content clearfix">
        <?php echo $this->render('leftmenu')?>
        <?php echo $content;?>
    </div>
</div>
<!-- 内容左边菜单结束-->

<?php echo $this->render('footer')?>

<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>

