<?php if($delay['delays'] <= 700 &&  $delay['is_agree'] === "" && $uid != $product['uid'] && $product['progress_status'] == 2){ ?>
	  <div class="xin">
       <a href="<?php echo yii\helpers\Url::toRoute(['/releases/application','id'=>$product['id'],'category'=>$product['category']])?>">还有七天就要到达约定结案日期，点击申请延期></a>
      </div>
    <?php }else if($uid == $product['uid'] && $product['progress_status'] == 2 && $delay['is_agree'] == 0 && $delay['product_id'] == $product['id']){ ?>
	 <div class="xin">
       <a href="<?php echo yii\helpers\Url::toRoute(['/releases/time','id'=>$delay['id']])?>">收到延期申请，点击处理></a>
     </div>
    <?php }else if($uid != $product['uid']&& $product['progress_status'] == 2 && $delay['product_id'] == $product['id']){?>
       <?php if($delay['is_agree'] == 0 ){?>
	   <div class="xin">
          <a href="javascript:void(0)" >已申请延期，等待对方确认></a>
       </div>
	   <?php }else if($delay['is_agree'] == 1){?>
	    <div class="xin">
          <a href="javascript:void(0)">延期申请成功，请抓紧处理></a>
       </div> 
       <?php }else if($delay['is_agree'] == 2){ ?>
	   <div class="xin">
          <a href="javascript:void(0)">延期申请失败，请联系发布方></a>
       </div>
       <?php } ?>	   
	   
	<?php } ?>
	

