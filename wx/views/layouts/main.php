<?php
use yii\helpers\Html;
use wx\assets;
assets\AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $content string 字符串 */
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
		<meta content="telephone=no" name="format-detection">
        <?=Html::tag('meta','',['http-equiv'=>'Content-Type','content'=>'text/html;charset=UTF-8'])?>
        <?=Html::tag('meta','',['name'=>'viewport','content'=>'width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no'])?>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->context->title) ?></title>
        <?=Html::tag('meta','',['name'=>'keywords','content'=> Html::encode($this->context->keywords)])?>
        <?=Html::tag('meta','',['name'=>'description','content'=> Html::encode($this->context->description)])?>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
<script>
 $(document).ready(function () {
        $('.cm-header .icon-back').click(function () {
           var index = layer.load(1, {
			   time:2000,
				shade: [0.4,'#fff'] //0.1透明度的白色背景
			});
        });
		$('.cm-header a').click(function () {
           var index = layer.load(1, {
			   time:2000,
				shade: [0.4,'#fff'] //0.1透明度的白色背景
			});
        });
    });
</script>