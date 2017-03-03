<?php if(!empty($product)){ ?>
<?php echo  \wx\widget\wxSpeedWidget::widget(['id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category'),'progress_status'=>$product['progress_status']])?>
<?php } ?>
