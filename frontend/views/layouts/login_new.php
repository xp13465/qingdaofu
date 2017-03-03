<?php

/* @var $this \yii\web\View */
/* @var $content string */
 
use yii\helpers\Html; 
use yii\helpers\Url;
use frontend\assets\AppAsset; 
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);

$action = Yii::$app->controller->action->id;
Yii::$app->view->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->context->title) ?></title>
    <meta name="renderer" content="webkit" />
    <link rel="shortcut icon" type="image/ico" href="/images/favicon.png"/>
    <meta content="<?= Html::encode($this->context->keywords) ?>" name="keywords">
    <meta content="<?= Html::encode($this->context->description) ?>" name="description"> 
    <?php echo $this->registerCssFile('/css/index.css');?>   
	<?php echo $this->registerCssFile('/js/msgbox/jquery.msgbox.css');?>
    <?php echo $this->registerJsFile('/js/jquery-1.11.1.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/jquery.validate.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
	<?php echo $this->registerJsFile('/js/msgbox/jquery.msgbox.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php $this->head() ?>
</head>
<?php $this->beginBody() ?> 
<header>
  <div class="head">
    <div class="logo">
      <div class="logo-l"><a href="/"><img style="border:0" src="/images/login/logo.png"></a><a href="javascript:void(0)" style="margin-top:35px;margin-left:10px;">| <?=$action=='login'?"登录":"注册"?></a></div>
      <div class="logo-r">
	  <?php if($action=='login'){?>
	  <span>还没有账号，</span><?php echo Html::tag('a','立即注册',['href'=>Url::toRoute('/site/signup')]);?>
	  <?php }else{ ?>
	   <span>已有账号，</span><?php echo Html::tag('a','直接登录',['href'=>Url::toRoute('/site/login')]);?>
	  <?php } ?>
	  </div>
    </div>
  </div>
</header>
<?php echo $content;?> 
<footer>
<div class="foot">
<ul>
<li><a href="<?php echo yii\helpers\Url::toRoute('/aboutus/intro')?>" target ="_blank">公司简介 |</a></li>
<li><a href="<?php echo yii\helpers\Url::toRoute('/homepage/business')?>" target ="_blank">业务流程 |</a></li>
<li><a href="<?php echo yii\helpers\Url::toRoute('/aboutus/serviceclause')?>" target ="_blank">服务协议 |</a></li>
<li><a href="<?php echo yii\helpers\Url::toRoute('/site/help')?>" target ="_blank">新手指引 |</a></li>
<li><a href="<?php echo yii\helpers\Url::toRoute('/protocol/falvdeclaration')?>" target ="_blank">法律声明</a></li>
</ul>
<p>版权所有:Copyright2015-2016直向资产管理有限公司  沪ICP备15055061号-1</p>
</div>
</footer>
<?php $this->endBody() ?>

</html>
<?php $this->endPage() ?>

