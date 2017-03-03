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
$title = $this->title?$this->title:$this->context->title;
$title=$title?$title:Yii::$app->name;

Yii::$app->view->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($title) ?></title>
    <link rel="shortcut icon" type="image/ico" href="/images/favicon.png"/>
    <meta name="renderer" content="webkit" />
    <meta content="<?= Html::encode($this->context->keywords) ?>" name="keywords">
    <meta content="<?= Html::encode($this->context->description) ?>" name="description">
    <?php echo $this->registerCssFile('/css/base.css');?>
    <?php echo $this->registerCssFile('/css/style.css');?>
    <?php echo $this->registerCssFile('/css/base1.css');?>
    <?php echo $this->registerCssFile('/css/register.css');?>
    <?php echo $this->registerCssFile('/css/look_message.css');?>
    <?php echo $this->registerCssFile('/css/Collection_detail.css');?>
    <?php echo $this->registerCssFile('/css/message.css');?>
    <?php echo $this->registerCssFile('/js/msgbox/jquery.msgbox.css');?>
    <?php echo $this->registerJsFile('/js/jquery-1.11.1.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/jquery.validate.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/index.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php echo $this->registerJsFile('/js/card.js',['position'=>\Yii\Web\View::POS_END]);?>
    <?php echo $this->registerJsFile('/js/msgbox/jquery.msgbox.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
    <?php $this->head() ?>
</head>
<?php $this->beginBody() ?>
<?php echo $this->renderFile('@app/views/common/header.php')?>
<?php echo $this->renderFile('@app/views/common/info_menu.php')?>
<?php echo $this->renderFile('@app/views/common/function.php')?>
<!-- 内容左边菜单开始-->
<div class="cont">
    <div class="content clearfix">
        <?php echo $this->renderFile('@app/views/common/leftmenu.php')?>
        <div class="message">
            <ul class="publish">
                <?php
                    $controller = Yii::$app->controller->id;
                    $action = Yii::$app->controller->action->id;
                ?>
                <li><a href="<?php echo \yii\helpers\Url::toRoute('/list/index')?>" <?php  if(strtolower($controller.$action) == 'listindex') echo "class='current'";?>></a></li>
                <!--li><a href="<?php echo \yii\helpers\Url::toRoute('/list/release')?>" <?php  if(strtolower($controller.$action) == 'listrelease') echo "class='current'";?>>待发布</a></li>
                <li><a href="<?php echo \yii\helpers\Url::toRoute('/list/apply')?>" <?php  if(strtolower($controller.$action) == 'listapply') echo "class='current'";?>>已发布</a></li>
                <li><a href="<?php echo \yii\helpers\Url::toRoute('/list/termination')?>" <?php  if(strtolower($controller.$action) == 'listtermination') echo "class='current'";?>>处理中</a></li>
                <li><a href="<?php echo \yii\helpers\Url::toRoute('/list/closure')?>" <?php  if(strtolower($controller.$action) == 'listclosure') echo "class='current'";?>>已结案</a></li-->
            </ul>
            <div class="mytable">
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

