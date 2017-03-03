<?php
$finle = \common\models\CreditorProduct::findOne(['id'=>yii::$app->request->get('id')]);
$imgnotarization = unserialize($finle['creditorfile']);
$img = explode(',',$imgnotarization['imgnotarization']);
$imgcontract = explode(',',$imgnotarization['imgcontract']);
$imgcreditor = explode(',',$imgnotarization['imgcreditor']);
$imgpick = explode(',',$imgnotarization['imgpick']);
$imgshouju = explode(',',$imgnotarization['imgshouju']);
$imgbenjin = explode(',',$imgnotarization['imgbenjin']);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href='/diyUpload/css/webuploader.css'/>
    <link rel="stylesheet" type="text/css" href='/diyUpload/css/diyUpload.css'/>
    <script src="/js/jquery-1.11.1.js" data-content-id="sb"></script>
    <script src="/diyUpload/js/webuploader.html5only.min.js"></script>
    <script src="/diyUpload/js/diyUpload.js"></script>

    <style>
        #demo{ margin:0px; width:570px;}
    </style>
</head>
<body>
<div id = "demo"><div id="test"></div></div>
<?php if(yii::$app->request->get('pid') == 1){?>
        <span>
            <?php foreach($img as $v=>$k):?>
                <?php if(isset($k)&& $k ){?>
                <img src="<?php echo Yii::$app->params['www'].str_replace("'",'',$k)?>"/>
                <?php }else{ echo '暂无';}?>
            <?php endforeach;?>
        </span>
<?php }else if(yii::$app->request->get('pid') == 2){?>
        <span>
            <?php foreach($imgcontract as $v=>$k):?>
                <?php if(isset($k)&& $k ){?>
                    <img src="<?php echo Yii::$app->params['www'].str_replace("'",'',$k)?>"/>
                <?php }else{ echo '暂无';}?>
            <?php endforeach;?>
        </span>
<?php }else if(yii::$app->request->get('pid') == 3){?>
        <span>
            <?php foreach($imgcreditor as $v=>$k):?>
                <?php if(isset($k)&& $k ){?>
                    <img src="<?php echo Yii::$app->params['www'].str_replace("'",'',$k)?>"/>
                <?php }else{ echo '暂无';}?>
            <?php endforeach;?>
        </span>
<?php }else if(yii::$app->request->get('pid') == 4){?>
        <span>
            <?php foreach($imgpick as $v=>$k):?>
                <?php if(isset($k)&& $k ){?>
                    <img src="<?php echo Yii::$app->params['www'].str_replace("'",'',$k)?>"/>
                <?php }else{ echo '暂无';}?>
            <?php endforeach;?>
        </span>
<?php }else if(yii::$app->request->get('pid') == 5){?>
        <span>
            <?php foreach($imgshouju as $v=>$k):?>
                <?php if(isset($k)&& $k ){?>
                    <img src="<?php echo Yii::$app->params['www'].str_replace("'",'',$k)?>"/>
                <?php }else{ echo '暂无';}?>
            <?php endforeach;?>
        </span>
<?php }else {?>
        <span>
            <?php foreach($imgbenjin as $v=>$k):?>
                <?php if(isset($k)&& $k ){?>
                    <img src="<?php echo Yii::$app->params['www'].str_replace("'",'',$k)?>"/>
                <?php }else{ echo '暂无';}?>
            <?php endforeach;?>
        </span>
<?php } ?>
</body>
</html>