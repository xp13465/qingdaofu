<?php
/**
 * Created by PhpStorm.
 * User: ZXTZ
 * Date: 2015/12/17
 * Time: 18:08
 */
echo \Yii::$app->view->renderFile('@app/views/common/header.php');


?>
<?php echo \Yii::$app->view->renderFile('@app/views/common/banner.php');?>

<div id = "content" class = "clogin">
    <div class = "childcontent">
        <?php echo $content;?>
    </div>
</div>
<?php echo \Yii::$app->view->renderFile('@app/views/common/footer.php');?>