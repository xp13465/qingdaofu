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
        <meta name="renderer" content="webkit" />
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <meta content="<?= Html::encode($this->context->keywords) ?>" name="keywords">
        <link rel="shortcut icon" type="image/ico" href="/images/favicon.png"/>
        <meta content="<?= Html::encode($this->context->description) ?>" name="description">
        <?php echo $this->registerJsFile('/js/jquery-1.11.1.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
        <?php echo $this->registerJsFile('/js/jquery.validate.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
        <?php $this->head() ?>
    </head>
<?php $this->beginBody() ?>
<div id = "container">
    <?php echo $this->renderFile('@app/views/common/header.php')?>
    <?php echo $this->renderFile('@app/views/common/menu.php')?>
<div id = "content" class = "clogin">
    <div class = "childcontent">
        <?php echo $content;?>
    </div>
</div>
    <?php echo $this->renderFile('@app/views/common/footer.php')?>

</div>
    <?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>

