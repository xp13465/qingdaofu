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
    <meta content="sss" name="keywords">
    <meta content="琐琐碎碎三" name="description">
    <?php echo $this->registerCssFile('/css/base.css');?>
    <?php echo $this->registerCssFile('/css/style.css');?>
    <?php echo $this->registerCssFile('/css/base1.css');?>
    <?php echo $this->registerCssFile('/css/register.css');?>
    <?php echo $this->registerCssFile('/css/look_message.css');?>
    <?php echo $this->registerCssFile('/css/Collection_detail.css');?>
    <?php echo $this->registerCssFile('/css/message.css');?>
    <?php echo $this->registerCssFile('/css/about_us.css');?>
    <?php echo $this->registerJsFile('/js/jquery-1.11.1.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/jquery.validate.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/index.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/card.js',['position'=>\Yii\Web\View::POS_END]);?>
    <?php $this->head() ?>
</head>
<?php $this->beginBody() ?>
<?php echo $this->renderFile('@app/views/common/header.php')?>
<?php echo $this->renderFile('@app/views/common/menu.php')?>
<?php echo $this->renderFile('@app/views/common/function.php')?>
<!-- 内容左边菜单开始-->
        <div class="h_center">
            <div class="menu">
                <ul class="menu_m">
                    <li><a href="<?php echo \yii\helpers\Url::to('/aboutus/intro')?>" class="fir_a">直向简介</a></li>
                    <li><a href="<?php echo \yii\helpers\Url::to('/aboutus/serviceclause')?>">服务协议</a></li>
                    <li><a href="<?php echo \yii\helpers\Url::to('/aboutus/helpcenter')?>">帮助中心</a></li>
                    <li><a href="<?php echo \yii\helpers\Url::to('/aboutus/feedback')?>">意见反馈</a></li>
                    <li><a href="<?php echo \yii\helpers\Url::to('/aboutus/cooperation')?>">商务合作</a></li>
                    <li><a href="<?php echo \yii\helpers\Url::to('/aboutus/contactus')?>">联系我们</a></li>
                </ul>
            </div>
            <?php echo $content;?>
            </div>
<!-- 内容左边菜单结束-->

<?php echo $this->renderFile('@app/views/common/footer.php')?>

<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
<script type="text/javascript">
    $('.menu_m li a').each(function(){
        if($($(this))[0].href==String(window.location))
            $(this).addClass('current1');
    });
</script>
