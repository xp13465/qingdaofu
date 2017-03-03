<?php
use yii\helpers\Html;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit" />
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="shortcut icon" type="image/ico" href="/images/favicon.png"/>
    <title><?= Html::encode($this->context->title) ?></title>
    <meta content="<?= Html::encode($this->context->keywords) ?>" name="keywords">
    <meta content="<?= Html::encode($this->context->description) ?>" name="description">
    <script src="/js/jquery-1.11.1.js" type="text/javascript"></script>
</head>
<body>
<?php echo $content;?>
</body>
</html>