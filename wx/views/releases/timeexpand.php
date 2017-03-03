<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;


?>
<style>
.xiang{width: 70%;min-height: 50px;max-height: 500px;margin-left: auto;margin-right: auto;outline: 0;font-size: 16px;word-wrap: break-word;overflow-x: hidden;overflow-y: auto;}
.jxiang{width: 100%;max-width:640px; min-height: 50px; max-height: 500px;margin-left: auto; margin-right: auto;outline: 0;font-size: 16px; word-wrap: break-word; overflow-x: hidden;overflow-y: auto;}
</style>
<?=wxHeaderWidget::widget(['title'=>'处理进度','gohtml'=>''])?>
<section>
  <div class="show" style="margin-top:20px;">
    <ul class="revert">
      <li>
        <div class="revert_l" style="width:65%;float:left;"> <span style="color:#333;">延期申请</span> </div>
        <div class="revert_r" style="width:35%;float:right;">
         <button  class = "cancel" data-id = "<?php echo $data['id']?>" data-prodct-id="<?php echo $data['product_id'];?>" data-category="<?php echo $data['category'];?>" style="width:45%;float:left;height:35px;margin-top:10px;border:1px solid #ddd;border-radius:3px;background:#fff;font-size:16px;">拒绝</button>
         <button  class = "confirm" data-id = "<?php echo $data['id']?>" data-prodct-id="<?php echo $data['product_id'];?>" data-category="<?php echo $data['category'];?>" style="width:45%;float:left;margin-left:4%;height:35px;margin-top:10px;border-radius:3px;color:#fff;background:#0065b3;font-size:16px;">同意</button>
         </div>
      </li>
      <li class="jxiang">
        <p style="text-indent:10px;line-height:26px;">延期天数:<i style="color:#0065b3;"><?php echo isset($data['delay_days'])?$data['delay_days']:'暂无'?></i>天</p>
        <div class="revert_l"> <span style="color:#333;">详情</span> </div>
        <div class="revert_r xiang"> <span><?php echo isset($data['dalay_reason'])?$data['dalay_reason']:'暂无'?></span> </div>
      </li>
    </ul>
  </div>
</section>
<script>
    $('.confirm').click(function(){
        var id = $(this).attr('data-id');
		var pid = $(this).attr('data-prodct-id');
		var category = $(this).attr('data-category');
        $.ajax({
           url:"<?php echo yii\helpers\Url::toRoute('/releases/coufirm');?>",
                    type:'post',
                    data:{id:id},
                    dataType:'json',
                    success:function(json){
                        if(json.code == '0000'){
                            alert(json.msg);
							location.href = "<?php echo yii\helpers\Url::toRoute('/releases/index');?>?id="+pid+"&category="+category;
                        }else{
                            alert(json.msg);
                        }
                    }
                })
            });
            $('.cancel').click(function(){
                var id = $(this).attr('data-id');
				var pid = $(this).attr('data-prodct-id');
		        var category = $(this).attr('data-category');
                $.ajax({
                    url:"<?php echo yii\helpers\Url::toRoute('/releases/index');?>",
                    type:'post',
                    data:{id:id},
                    dataType:'json',
                    success:function(json){
                        if(json.code == '0000'){
                            alert(json.msg);
							location.href = "<?php echo yii\helpers\Url::toRoute('/releases/index');?>?id="+pid+"&category="+category;
                        }else{
                            alert(json.msg);
                        }
                    }
                })
            });
</script>
