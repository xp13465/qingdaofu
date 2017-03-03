<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;
use yii\helpers\Html;
AppAsset::register($this);

$title = $this->title?$this->title:$this->context->title;
$title=$title?$title:Yii::$app->name;
Yii::$app->view->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($title); ?></title>
    <link rel="shortcut icon" type="image/ico" href="/images/favicon.png"/>
    <meta name="renderer" content="webkit" />
    <meta content="<?= Html::encode($this->context->keywords) ?>" name="keywords">
    <meta content="<?= Html::encode($this->context->description) ?>" name="description">
    <?php echo $this->registerCssFile('/css/base.css');?>
    <?php echo $this->registerCssFile('/css/style.css');?>
    <?php //echo $this->registerCssFile('/css/base1.css');?>
    <?php echo $this->registerCssFile('/css/register.css');?>
    <?php echo $this->registerCssFile('/css/look_message.css');?>
	<?php echo $this->registerCssFile('/bate2.0/css/index.css');?>
    <?php echo $this->registerCssFile('/bate2.0/css/center.css');?>
    <?php echo $this->registerJsFile('/js/jquery-1.11.1.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/jquery.validate.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
	<?php echo $this->registerJsFile('/js/layer/layer.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/index.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php $this->head() ?>
</head>
<?php $this->beginBody() ?>
<?php echo $this->render('header')?>
<!-- 内容左边菜单开始-->
<div class="cont">
    <div class="content clearfix">
       <?php echo $this->render('leftmenu')?>
        <div class="content_right">
		<br/>
            <ul class="info clearfix">
                <?php
                $controller = Yii::$app->controller->id;
                ?>
                <li <?php  if(strtolower($controller) == 'message') echo "class='current'";?>><a href="#">系统消息</a></li>
            </ul>
            <div class="info_noread">
                <?php echo $content;?>
            </div>
        </div>
    </div>
</div>
<!-- 内容左边菜单结束-->

<?php echo $this->render('footer')?>

<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
