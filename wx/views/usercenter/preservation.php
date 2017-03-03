<style>
    *{padding:0;margin:0;list-style:none;}
    body{margin:0 auto;}
    .list-ul{overflow:hidden;}
    .list-li{line-height:60px;border-bottom:1px solid #ddd;position:relative;padding:0 12px;color:#666;background:#fff;-webkit-transform:translateX(0px);}
    .btn{position:absolute;top:0;right:-160px;text-align:center;color:#fff;}
    .btn .a1{width:80px;display:inline-block;background-color:#0065B3;color:#fff;font-size:14px;}
    .btn .a2{width:80px;display:inline-block;background:#FF3B2F;color:#fff;margin-left:-5px;font-size:14px;}
</style>
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
//var_dump($data);
?>
<?=wxHeaderWidget::widget(['title'=>'我的保存','gohtml'=>'','backurl'=>Url::toRoute('/user/index'),'reload'=>false])?>
<section>
  <p id="show"></p>
  <div id="wrapper">
	<div id="scroller" class="type" style="position:absolute;">
		<div id="pullDown" style="display:none">
			<span class="pullDownIcon"></span><span class="pullDownLabel"></span>
		</div>
            <ul id="thelist" class="list-ul thelist" data-catr="10" data-url="<?=  yii\helpers\Url::toRoute('/usercenter/preservation')?>">
                <?php foreach($data as $value){ ?>
                    <li id="li" class="list-li product" data-productid='<?= $value['productid']?>'>
                        <div class="con">
                                <span style="font-size:16px;float:left;margin-top:-13px;">债权</span>
                            <a href="javascript:void(0);">
                                <span class="ma" data-id="<?php echo $value['productid']?>" ><?php echo isset($value['number'])?$value['number']:''?></span>
                                <span style="position:absolute;top:13px;left:0px;float:left;"><?php echo isset($value['create_at'])?date('Y-m-d',$value['create_at']):''?></span>
                                <span class="date"></span>
                            </a>
                        </div>
                    </li>
                <?php } ?>
            </ul>

            <div id="pullUp" style="display:none;" >
                <span class="pullUpIcon"></span><span class="pullUpLabel"></span>
            </div>
        </div>
    </div>
	
</section>
<div style="height:50px;width:50px;position:fixed;bottom:50px;z-index:99999;" >
    <a href="<?php echo yii\helpers\Url::toRoute('/site/index')?>"><img src="/images/back_home.png"></a>
</div>
<script type="text/javascript" src="/js/fastclick.js"></script>
<script>
datalistClass = '#scroller ul.thelist li.product';
 $(document).ready(function(){
	 $('.list-li').click(function(){
		 var productid = $(this).attr('data-productid');
		 window.location.href = '<?= yii\helpers\Url::toRoute('/product/create')?>'+'?productid='+productid;
	 })
 })
</script>
