<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
// print_r($ones);
// echo "<br/>";
// print_r($cdcon);
$this->context->title = '产权调查详情';
$this->title = '产权调查详情';
$ordersid = date("Ymd",$ones['time']).str_pad($ones['id'],6,"0",STR_PAD_LEFT);
?>
<style>
.icon-back{display:none}
</style>
<?=wxHeaderWidget::widget(['title'=>'产权调查详情','gohtml'=>''])?>
	<p>
		产调编号：<font><?=$ordersid?></font><br>
		产调类型：<?=$ones['typeLabel']?> <br>
		产权人姓名：<?=$ones['name']?><?=$ones['name']?> <br>
		产调地址：<?=$ones['cityname']?><?=$ones['address']?> <br>
		产调状态：<?=$ones['statusLabel']?><br>
		<?php 
		if($cdcon&&$cdcon['status']=='SUCCESS'){
			echo $success_msg;
		}else{
			echo "备注：".$cdcon['refund_msg'];
		}
		
		?>
	</p>
	电子档：（<?=count($data)?(count($data)."份"):"无"?>）
	<div class="banner">
	<?php foreach($data as $v):
	if(strpos($v, 'http')===false)$v='http://m.zcb2016.com/'.$v;
	?>
	<img src="<?=$v?>" width='100%' />
	<?php endforeach;?>
	</div>
<script>
		$(document).ready(function(){
			$(".banner img").click(function(){
				var imgs=new Array();
				$(".banner img").each(function(index){
					imgs.push($(this).attr('src'));
				})
				// console.log(imgs)
				// var index = $(this).attr('src')
				WeixinJSBridge.invoke('imagePreview', {  
					 'current' : $(this).attr('src'),  
					 'urls' : imgs 
				});
			})
			
		})
		$(function(){
			if(!isWeixin()) {
				// location.href = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxad34114f5aaae230&redirect_uri=http%3A%2F%2Fm.zcb2016.com%2Fwx.html&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect';
				return false;
			}
		});
		function isWeixin(){
		   return /MicroMessenger/.test(navigator.userAgent);
		}
	</script>
