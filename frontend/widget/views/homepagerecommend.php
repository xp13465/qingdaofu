<ul class="clearfix">
    <?php foreach ($products as $p){
		$userid = $p['pid']?$p['pid']:$p['uid'];
		?>
            <?php echo \frontend\widget\SingleproductWidget::widget(['category'=>$p['category'],'product_id'=>$p['id'],'userid'=>$p['pid']?$p['pid']:$p['uid']]);?>
    <?php  }?>
</ul>