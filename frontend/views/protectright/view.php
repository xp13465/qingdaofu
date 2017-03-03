<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Protectright */

$this->title = $model->name; 
$this->params['breadcrumbs'][] = ['label' => 'Protectrights', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="/js/jquery.SuperSlide.2.1.1.js"></script>
<div class="kuang" >


<script>
$(document).ready(function(){
	
	$(".t14").click(function(){
		$(this).siblings().removeClass("hover")
		$(this).addClass("hover")
		$(".ul2,.ul3,.ul4,.ul5").hide();
		$(".ul1").show();
		$(".ul1 li").eq(0).trigger("mouseover");
	})
	$(".t25").click(function(){
		$(this).siblings().removeClass("hover")
		$(this).addClass("hover")
		$(".ul1,.ul3,.ul4,.ul5").hide();
		$(".ul2").show();
		$(".ul2 li").eq(0).trigger("mouseover");
	})
	$(".t36").click(function(){
		$(this).siblings().removeClass("hover")
		$(this).addClass("hover")
		$(".ul1,.ul2,.ul4,.ul5").hide();		
		$(".ul3").show();
		$(".ul3 li").eq(0).trigger("mouseover");
		})
	$(".t4").click(function(){
		$(this).siblings().removeClass("hover")
		$(this).addClass("hover")
		$(".ul1,.ul2,.ul3,.ul5").hide();
		$(".ul4").show();
		$(".ul4 li").eq(0).trigger("mouseover");
	})
	$(".t5").click(function(){
		$(this).siblings().removeClass("hover")
		$(this).addClass("hover")
		$(".ul1,.ul2,.ul3,.ul4").hide();
		$(".ul5").show();
		$(".ul5 li").eq(0).trigger("mouseover");
	})	
	
});
</script>
<div id="demo2" class="picBtnTop" style="margin-top:0px">
<div class="pd">
		<ul>
        <li class="t14 hover"><a href="javascript:void(0)">借条凭证</a></li>
        <li class="t25"><a href="javascript:void(0)">转款凭证</a></li>
        <li class="t36"><a href="javascript:void(0)">担保合同</a></li>
        <li class="t4"><a href="javascript:void(0)">财产线索</a></li>
        <li class="t5"><a href="javascript:void(0)">其他证据</a></li>
        </ul>
	</div>
	<div class="hd">
        <ul class="ul1">
			<?php foreach($files['jietiao'] as $val):?>
			<li><img src="<?=$val['file']?>"></li>
			<?php endforeach;?>
		</ul>
        <ul class="ul2" style="display:none;">
			<?php foreach($files['yinhang'] as $val):?>
			<li><img src="<?=$val['file']?>"></li>
			<?php endforeach;?>
		</ul>
        <ul class="ul3" style="display:none;">
			<?php foreach($files['danbao'] as $val):?>
			<li><img src="<?=$val['file']?>"></li>
			<?php endforeach;?>
		</ul>
        <ul class="ul4" style="display:none;">
			<?php foreach($files['caichan'] as $val):?>
			<li><img src="<?=$val['file']?>"></li>
			<?php endforeach;?>
		</ul>
        <ul class="ul5" style="display:none;">
			<?php foreach($files['other'] as $val):?>
			<li><img src="<?=$val['file']?>"></li>
			<?php endforeach;?>
		</ul>
	</div>
	<div class="bd">
		<ul>
			<?php foreach($files['jietiao'] as $val):?>
			<li>
				<div class="bg"></div>
				<div class="pic"><img align="center" src="<?=$val['file']?>"></div>
				<div class="title"><?=$val['name']?></div>
			</li>
			<?php endforeach;?>
			<?php foreach($files['yinhang'] as $val):?>
			<li>
				<div class="bg"></div>
				<div class="pic"><img src="<?=$val['file']?>"></div>
				<div class="title"><?=$val['name']?></div>
			</li>
			<?php endforeach;?>
			<?php foreach($files['danbao'] as $val):?>
			<li>
				<div class="bg"></div>
				<div class="pic"><img src="<?=$val['file']?>"></div>
				<div class="title"><?=$val['name']?></div>
			</li>
			<?php endforeach;?>
			<?php foreach($files['caichan'] as $val):?>
			<li>
				<div class="bg"></div>
				<div class="pic"><img src="<?=$val['file']?>"></div>
				<div class="title"><?=$val['name']?></div>
			</li>
			<?php endforeach;?>
			<?php foreach($files['other'] as $val):?>
			<li>
				<div class="bg"></div>
				<div class="pic"><img src="<?=$val['file']?>"></div>
				<div class="title"><?=$val['name']?></div>
			</li>
			<?php endforeach;?>
		</ul>
	</div>
</div>
<script type="text/javascript">
jQuery("#demo2").slide({ mainCell:".bd ul",effect:"top",autoPlay:false,triggerTime:0 });
</script>
       </div>