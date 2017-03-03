<?php
use yii\helpers\Html;

if (Yii::$app->controller->action->id === 'login') {
/**
 * Do not use this code in your template. Remove it.
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('manage\assets\AppAsset')) {
        manage\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    } 
	// var_dump($_COOKIE);
	$classtype=isset($_COOKIE['classtype'])?$_COOKIE['classtype']:'';
// var_dump($classtype);
    manage\assets\AdminLteAsset::register($this);
    // backend\assets\ZcbAsset::register($this);
    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@manage/assets/AdminLte');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
		<script src="/js/jquery-1.11.1.js"></script>
		<script src="/js/divscroll.js"></script>
		<script src="/js/layer/layer.js"></script>
		<link href="/css/bootstrap.min.css" rel="stylesheet">
		<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.js"></script> 
		
		<style>
		img.photoShow{
			max-width:50%;
			display:block;
			margin:5px;
		}
		.detail-view>tbody>tr>th:nth-child(1){width:200px;}
		.form-control{padding:6px}
		</style>
    </head>
    <body class="hold-transition skin-blue sidebar-mini <?=$classtype==2?"sidebar-collapse":""?>">
    <?php $this->beginBody() ?>
    <div class="wrapper" style='min-width: 1300px;'>

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>
		
		
		<footer class="main-footer">
			<div class="pull-right hidden-xs">
				<b>Version</b> 2.0
			</div>
			<strong>Copyright &copy; 2015-2016 <a href="http://www.zcb2016.com">清道夫债管家</a>.</strong> All rights
			reserved.
		</footer>

		<!-- Add the sidebar's background. This div must be placed
			 immediately after the control sidebar -->
		<div class='control-sidebar-bg'></div>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
<script>
function setCookie(name,value)
{
	var Days = 30;
	var exp = new Date();
	exp.setTime(exp.getTime() + Days*24*60*60*1000);
	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString() + ';path=/';
}
$(document).ready(function(){
	<?php if(!$classtype){?>
		if(document.body.clientWidth<= 1400){
			$("body").addClass("sidebar-collapse") 
			setCookie('classtype',2)
		}
	<?php }?>
	$(".sidebar-toggle").click(function(){
		var type = ($("body").hasClass("sidebar-collapse"))?"1":"2";
		setCookie('classtype',type)
		// alert(type)
	});
})
</script>