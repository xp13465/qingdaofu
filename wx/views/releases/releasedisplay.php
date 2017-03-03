<?php if(!empty($product) && !empty($uid)){ ?>

<!--数据展示-->
<?php echo  \wx\widget\wxReleaseinformationWidget::widget(['id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category')])?>
<!-- 申请延期  -->

<?php echo \wx\widget\wxExtendWidget::widget(['id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category')])?>

<!--结案、终止-->
<?php echo  \wx\widget\wxClosedsWidget::widget(['id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category')])?>
<?php } ?>
