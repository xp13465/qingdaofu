<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
$this->registerJsFile('@web/js/fastclick.js',['depends'=>['wx\assets\NewAppAsset']]);
$labelImg=[
	"1"=>"/bate2.0/images/system.jpg",
	"10"=>"/bate2.0/images/bao.png",
	"20"=>"/bate2.0/images/csh.png",
	"30"=>"/bate2.0/images/cja.png",
	"40"=>"/bate2.0/images/system.jpg",
	"50"=>"/bate2.0/images/system.jpg",
];
$labelUrl=[
	"1"=>["/message/system"],
	"10"=>["preservation/audit", 'id'=>""],
	"20"=>["policy/baohan", 'id'=>""],
	"30"=>["/property/view", 'id'=>""],
	"40"=>["/productorders/detail", 'applyid'=>""],
	"50"=>["wrelease/details", 'productid'=>""],
];
?>
<?=wxHeaderWidget::widget(['title'=>'消息','homebtn'=>false,'backurl'=>Url::toRoute('/message/index')])?>
<style>
.thelast{width: 100%;margin-top: 12px;background-color: #fff;}
.thislast{width: 100%;background-color: #fff;height:110px;}
.lost {margin: 0px 10px;}
.one{font-size:18px;color:#666;padding: 10px 0px 10px 0px;}
.one i{font-size:12px;float:right;color:#999;}
.two{font-size:14px;color:#999;line-height:24px;    word-break: break-all;   word-wrap: break-word;}
</style>
<section>

<p id="show"></p>
<div id="wrapper" >
  <div id="scroller" >
	<div id="pullDown" style="display:none">
				<span class="pullDownIcon"></span><span class="pullDownLabel"></span>
	</div>
	 
	<div id="thelist" class='thelist' data-catr="<?=count($data)?>" data-url="<?php echo yii\helpers\Url::toRoute(['/message/system'])?>">
	<?php foreach($data as $message):?>
	<div class="thelast">
		<div class="lost">
		  <p class="one">系统消息<i><?=date("Y/m/d H:i",$message['create_time'])?></i></p>
		  <p class="two"><?=$message['title']?>,<?=$message['relatitle']?><?=$message['content']?></p>
		</div>
	</div>
	<?php endforeach;?>
	
	</div>
	 
	<div id="pullUp" style="display:none;" >
			<span class="pullUpIcon"></span><span class="pullUpLabel"></span>
	</div>
	</div>
  </div>
</section>
<?php echo  \wx\widget\wxFooterWidget::widget()?>
<script>
    $(document).ready(function(){
			datalistClass = "#scroller div.thelist div.thelast";
            // $('.xiaoxi_b').click(function(){
                // window.location  = "<?php echo \yii\helpers\Url::toRoute('/message/categorylist')?>?type="+$(this).attr('data-content-id');
            // });
        }
    );
</script>
