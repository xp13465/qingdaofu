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
                <img src="<?php echo 'http://zichanbao.com/'.substr($k, 1, -1)?>"/>
                <?php }else{ echo '暂无';}?>
            <?php endforeach;?>
        </span>
<?php }else if(yii::$app->request->get('pid') == 2){?>
        <span>
            <?php foreach($imgcontract as $v=>$k):?>
                <?php if(isset($k)&& $k ){?>
                    <img src="<?php echo 'http://zichanbao.com/'.substr($k, 1, -1)?>"/>
                <?php }else{ echo '暂无';}?>
            <?php endforeach;?>
        </span>
<?php }else if(yii::$app->request->get('pid') == 3){?>
        <span>
            <?php foreach($imgcreditor as $v=>$k):?>
                <?php if(isset($k)&& $k ){?>
                    <img src="<?php echo 'http://zichanbao.com/'.substr($k, 1, -1)?>"/>
                <?php }else{ echo '暂无';}?>
            <?php endforeach;?>
        </span>
<?php }else if(yii::$app->request->get('pid') == 4){?>
        <span>
            <?php foreach($imgpick as $v=>$k):?>
                <?php if(isset($k)&& $k ){?>
                    <img src="<?php echo 'http://zichanbao.com/'.substr($k, 1, -1)?>"/>
                <?php }else{ echo '暂无';}?>
            <?php endforeach;?>
        </span>
<?php }else if(yii::$app->request->get('pid') == 5){?>
        <span>
            <?php foreach($imgshouju as $v=>$k):?>
                <?php if(isset($k)&& $k ){?>
                    <img src="<?php echo 'http://zichanbao.com/'.substr($k, 1, -1)?>"/>
                <?php }else{ echo '暂无';}?>
            <?php endforeach;?>
        </span>
<?php }else {?>
        <span>
            <?php foreach($imgbenjin as $v=>$k):?>
                <?php if(isset($k)&& $k ){?>
                    <img src="<?php echo 'http://zichanbao.com/'.substr($k, 1, -1)?>"/>
                <?php }else{ echo '暂无';}?>
            <?php endforeach;?>
        </span>
<?php } ?>
</body>
</html>