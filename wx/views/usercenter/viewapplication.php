<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<style>
.head{width:100%;max-width:640px;height:50px;}
.head-l{
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
	width:70%;height:50px;float:left;text-indent:20px;line-height:50px;color:#fff;font-size:16px;}
.head-r{width:30%;height:50px;text-align:right;padding-right:15px;float:left;line-height:50px;color:#fff;font-size:16px;}
.button{background:#0065b3;color:#fff;font-size:16px;border-radius:3px;line-height:30px;width:100%;height:30px;margin-top:10px;}
</style>

<?=wxHeaderWidget::widget(['title'=>'产品详情','gohtml'=>'<a href="#"></a>'])?>

<div class="head" style="background:#0065b3;">
  <div class="head-l" style="text-align:center;">只能选择一个接单方,选择后不能修改</div>
  <div class="head-r"><img src="/images/close@2x.png" width="25" height="25" style="margin-top:13px;"></div>
</div>
<div class="cp_xinxi">
        <ul>
		<?php if(isset($user) && $user!=''){ ?>
                <?php foreach($user as $value){ ?>
            <a href="#">
                <li style="line-height:30px;">
                    <div class="cp_right" style="width:70%;">
                        <span style="font-size:16px;">接单方:<?php echo isset($value['name'])?$value['name']:''?></span>
                        <p style="font-size:14px;color:#999;line-height:25px;"><?php echo isset($value['create_time'])?date('Y-m-d H:i',$value['create_time']):''?></p>
                    </div>
                    <div style="width:20%;float:right;">
                    <button class="button" data-state="<?php echo $value['state'];?>" data-pid = "<?php echo $value['uid'];?>" data-id="<?php echo $value['product_id']?>" data-category="<?php echo $value['category'];?>">同意</button>
                    </div>
                </li>
            </a>
			  <?php } ?>
            <?php } ?>
        </ul>
    </div>
    </script>
<script>
$(document).ready(function(){
	$('.button').click(function(){
		var state = $(this).attr('data-state');
		var id = $(this).attr('data-id');
		var category = $(this).attr('data-category');
		var pid = $(this).attr('data-pid');
		layer.confirm('同意之后将不能改变,请慎重考虑!',{
			btn:['确定','取消']
		},function(){
			if(state == 1){
				location.href= "<?php echo yii\helpers\Url::toRoute('/usercenter/userinfo')?>?pid="+pid+"&id="+id+"&category="+category;
			}else{
				location.href= "<?php echo yii\helpers\Url::toRoute('/certification/index')?>";
			}
		    layer.close(confirmindex)
		})
	})
})
</script>