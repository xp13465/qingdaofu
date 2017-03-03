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
    <meta content="<?= Html::encode($this->context->keywords) ?>" name="keywords">
    <link rel="shortcut icon" type="image/ico" href="/images/favicon.png"/>
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
    <?php echo $this->registerJsFile('/js/My97DatePicker/WdatePicker.js',['position'=>\Yii\Web\View::POS_END]);?>
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
                <li><a href="<?php echo \yii\helpers\Url::toRoute('/order/index')?>" <?php  if(strtolower($controller.$action) == 'orderindex') echo "class='current'";?>></a></li>
                <!--li><a href="<?php echo \yii\helpers\Url::toRoute('/order/ordersave')?>" <?php  if(strtolower($controller.$action) == 'orderordersave') echo "class='current'";?>>收藏列表</a></li>
                <li><a href="<?php echo \yii\helpers\Url::toRoute('/order/orderapply')?>" <?php  if(strtolower($controller.$action) == 'orderorderapply') echo "class='current'";?>>申请中</a></li>
                <li><a href="<?php echo \yii\helpers\Url::toRoute('/order/ordertermination')?>" <?php  if(strtolower($controller.$action) == 'orderordertermination') echo "class='current'";?>>处理中</a></li>
                <li><a href="<?php echo \yii\helpers\Url::toRoute('/order/orderclosure')?>" <?php  if(strtolower($controller.$action) == 'orderorderclosure') echo "class='current'";?>>已结案</a></li-->
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
