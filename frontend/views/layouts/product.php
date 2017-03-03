<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
Yii::$app->view->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->context->title) ?></title>
    <link rel="shortcut icon" type="image/ico" href="/images/favicon.png"/>
    <meta name="renderer" content="webkit" />
    <meta content="<?= Html::encode($this->context->keywords) ?>" name="keywords">
    <meta content="<?= Html::encode($this->context->description) ?>" name="description">
    <?php echo $this->registerCssFile('/css/base.css');?>
    <?php echo $this->registerCssFile('/css/base1.css');?>
    <?php echo $this->registerCssFile('/css/cd.css');?>
    <?php $this->head() ?>
</head>
<?php $this->beginBody() ?>
<?php echo $this->renderFile('@app/views/common/header.php')?>
<?php echo $this->renderFile('@app/views/common/menu.php')?>
<?php echo $this->renderFile('@app/views/common/function.php')?>
<?php echo $content;?>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>

