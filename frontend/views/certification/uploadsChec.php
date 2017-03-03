<?php
$finles = \common\models\Certification::findOne(['id'=>yii::$app->request->get('id')]);
$img = explode(',',$finles['cardimg']);
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
    <span>
            <?php foreach($img as $v=>$k):?>
                <?php if(isset($k)&& $k ){?>
                    <img src="<?php echo 'http://zichanbao.com/'.str_replace("'",'',$k)?>"/>
                <?php }else{ echo '暂无';}?>
            <?php endforeach;?>
        </span>
</body>
</html>