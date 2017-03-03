<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;
use yii\helpers\Html;
AppAsset::register($this);
Yii::$app->view->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->context->title) ?></title>
    <link rel="shortcut icon" type="image/ico" href="/images/favicon.png"/>
    <meta content="sss" name="keywords">
    <meta content="琐琐碎碎三" name="description">
    <?php echo $this->registerCssFile('/css/base.css');?>
    <?php echo $this->registerCssFile('/css/style.css');?>
    <?php echo $this->registerCssFile('/css/base1.css');?>
    <?php echo $this->registerCssFile('/css/register.css');?>
    <?php echo $this->registerCssFile('/css/look_message.css');?>
    <?php echo $this->registerJsFile('/js/jquery-1.11.1.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/jquery.validate.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/index.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php $this->head() ?>
</head>
<?php $this->beginBody() ?>
<?php echo $this->renderFile('@app/views/common/header.php')?>
<?php echo $this->renderFile('@app/views/common/menu.php')?>
<?php echo $this->renderFile('@app/views/common/function.php')?>
<!-- 内容左边菜单开始-->
<div class="cont">
    <div class="content clearfix">
        <?php echo $this->renderFile('@app/views/common/leftmenu.php')?>
        <div class="content_right">
            <ul class="info clearfix">
                <li><a href="<?php echo \yii\helpers\Url::to('/message/read')?>">已读</a></li>
                <li><a href="<?php echo \yii\helpers\Url::to('/message/unread')?>">未读</a></li>
            </ul>
            <div class="info_noread">
                <?php echo $content;?>
            </div>
        </div>
    </div>
</div>
<!-- 内容左边菜单结束-->

<?php echo $this->renderFile('@app/views/common/footer.php')?>

<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
