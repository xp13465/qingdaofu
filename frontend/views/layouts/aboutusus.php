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
    <?php echo $this->registerCssFile('/css/style.css');?>
    <?php //echo $this->registerCssFile('/css/base1.css');?>
    <?php echo $this->registerCssFile('/css/about_us.css');?>
	<?php echo $this->registerCssFile('/bate2.0/css/index.css');?>
    <?php echo $this->registerJsFile('/js/jquery-1.11.1.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/jquery.validate.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/index.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/card.js',['position'=>\Yii\Web\View::POS_END]);?>
    <?php $this->head() ?>
</head>

<?php $this->beginBody() ?>
<?php echo $this->render('header')?> 
<?php echo $this->renderFile('@app/views/common/function.php')?>
<!-- 内容左边菜单开始-->
<?php
$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
?>
<div class="h_center">
    <div class="zx_center clearfix">
                <div class="intro_left">
                    <div class="beijin">
                        <img src="/images/duo.png" height="23" width="22" alt="" />
                        <span class="qd">关于清道夫</span>
                    </div>
                    <li><a onclick='change(this,0);' href="<?php echo \yii\helpers\Url::toRoute('/aboutus/intro')?>">公司简介</a></li>
                    <li><a onclick='change(this,1);' href="<?php echo \yii\helpers\Url::toRoute('/aboutus/serviceclause')?>">服务协议</a></li>
                    <li><a onclick='change(this,2);' href="<?php echo \yii\helpers\Url::toRoute('/aboutus/helpcenter')?>" <?php if(strtolower($controller.$action) == 'aboutushelpcenter' || strtolower($controller.$action) == 'aboutuscenter' || strtolower($controller.$action) == 'aboutushelpcenters'){echo "class='current'";}?>>帮助中心</a></li>
                    <li><a onclick='change(this,3);' href="<?php echo \yii\helpers\Url::toRoute('/aboutus/feedback')?>">意见反馈</a></li>
                    <li><a onclick='change(this,4);' href="<?php echo \yii\helpers\Url::toRoute('/aboutus/cooperation')?>">商务合作</a></li>
                    <li class="con_last"><a onclick='change(this,5);' href="<?php echo \yii\helpers\Url::toRoute('/aboutus/contactus')?>">联系我们</a></li>
                </div>
        <div class="intro_right">
            <?php echo $content;?>
        </div>
    </div>
</div>
<!-- 内容左边菜单结束-->

<?php echo $this->render('footer')?>

<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
<script type="text/javascript">
    $('.intro_left li a').each(function(){
        if($($(this))[0].href==String(window.location))
            $(this).addClass('current');
    });
</script>
