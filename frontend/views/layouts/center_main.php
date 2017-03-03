<?php
use frontend\assets\ZcbAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
// ZcbAsset::register($this);
$title = $this->title?$this->title:$this->context->title;
$title=$title?$title:Yii::$app->name;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <?php echo $this->registerJsFile('/js/My97DatePicker/WdatePicker.js',['position'=>\Yii\Web\View::POS_END]);?>
    <?php echo $this->registerJsFile('/js/msgbox/jquery.msgbox.js',['position'=>\Yii\Web\View::POS_HEAD]);?>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?= $this->renderFile('@app/views/common/header.php')?>
<?= $this->renderFile('@app/views/common/info_menu.php')?>
<?= $this->renderFile('@app/views/common/function.php')?>
<!-- 内容左边菜单开始-->
<div class="cont">
    <div class="content clearfix">
<?php echo $this->renderFile('@app/views/common/leftmenu.php')?>
        <div class="content_right">
                <div class="info_noread">
                         <?php foreach(Yii::$app->session->getAllFlashes() as $key => $message) : ?>

<div class="alert alert-<?= $key ?>">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
    <?= $message ?>
    </div>
                        <?php endforeach; ?>
                <?php echo $content;?>
            </div>
        </div>
    </div>
</div>
<!-- 内容左边菜单结束-->
<?php echo $this->renderFile('@app/views/common/footer.php')?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
