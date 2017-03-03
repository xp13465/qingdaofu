<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
$category = ['1'=>'进行中','2'=>'已完成'];
$this->registerJsFile('@web/js/fastclick.js',['depends'=>['wx\assets\NewAppAsset']]);
?>

<style>
.num{padding:6px 12px;}
.num li{width:100%;height:auto;background:none;margin:0;}
.num li span{display:flex}
.num-l{text-align:right;display: flex;justify-content: space-between;
		width:30%;float:left;font-size:14px;line-height:26px;paddingsx-right:15px;color:#666;}
.num-r{width:70%;float:left;font-size:14px;text-align:left;line-height:24px;color:#666;} 
.ongoing{border-bottom:1px solid #ddd;}
</style>
<?=wxHeaderWidget::widget(['title'=>'我的发布','gohtml'=>'','homebtn'=>true,'backurl'=>Url::toRoute("user/index"),'reload'=>true])?>
<section>
  <div class="basic">
    <ul>
	  <?php foreach($category as $key => $value): ?>
		<li class="ongoing" id="<?= $key ?>"> <span <?php if(\Yii::$app->request->get('type') == $key){echo 'class="current"';}?>><?= $value ?></span></li>
	  <?php endforeach; ?>
      <a href="<?= yii\helpers\Url::toRoute('/wrelease/termination');?>">
      <li style="width:100%;height:50px;border-bottom:0px solid #ddd;"> <span style="float:left;margin-left:20px;">已终止</span>
        <div class="renzhen-01"><span>&nbsp;</span><i class="jiantou"></i> </div>
      </li>
      </a>
    </ul>
  </div>
  
  <p id="show"></p>
  <div class="type" id="wrapper">
    <div id="scroller" class="type" style="position:absolute;">
			<div id="pullDown" style="display:none">
				<span class="pullDownIcon"></span><span class="pullDownLabel"></span>
			</div>
    <ul id="thelist" class="thelist" data-catr="10" data-url="<?=  yii\helpers\Url::toRoute(['/wrelease/index','type'=>\Yii::$app->request->get('type')])?>">
	 <?php foreach($data as $key => $value): ?>
		<li class="products">
		
		<div class="types" data-id="129" data-category="2">
			<a href="<?= Url::toRoute(['/wrelease/details','productid'=>$value['productid']]) ?>">
			<div class="rongzi" style="border-bottom: 0px solid #ddd;">
				<div class="over">
					<div class="flo_l"> 
						<span class="code"><?= isset($value['number'])?$value['number']:''; ?></span> <i class="jiantou"></i> 
					</div>
					<?php if(in_array($value['status'],['30'])){ ?>
						<span class="flo_t">&nbsp;</span>
					<?php }else if(in_array($value['status'],['40'])){ ?>
						<span class="flo_j">&nbsp;</span> 	
					<?php }else{ ?>
					<span class="flo_r"><?= isset($value['statusLabel'])?$value['statusLabel']:''; ?></span>
                    <?php }?>			
				</div>
			</div>
			  </a> 
			<div class="num">
			<ul>
				<li> <span class="num-l"><font>委</font><font>托</font><font>托</font><font>本</font><font>金：</font></span> <span class="num-r"><?= isset($value['accountLabel'])?$value['accountLabel']:'' ?>万 </span> </li>
				<li> <span class="num-l"><?php if($value['type']==1){echo '<font>固</font><font>定</font><font>费</font><font>用：</font>';}else{echo '<font>风</font><font>险</font><font>代</font><font>理：</font>';}?></span> <span class="num-r"><?php if($value['type']==1){echo $value['typenumLabel'].$value['typeLabel'];}else{echo $value['typenum'].$value['typeLabel'];}?></span> </li>
				<li> <span class="num-l"><font>委</font><font>托</font>期<font>限：</font></span> <span class="num-r"><?= isset($value['overdue'])?$value['overdue']:'' ?>个月 </span> </li>
				<li> <span class="num-l"><font>委</font><font>托</font><font>类</font><font>型：</font></span> <span class="num-r"><?= isset($value['categoryLabel'])?$value['categoryLabel']:'' ?></span> </li>
				<li> <span class="num-l"><font>合</font><font>同</font><font>履</font><font>行</font><font>地：</font></span> <span class="num-r"><?= isset($value['addressLabel'])?$value['addressLabel']:'' ?></span> </li>
			</ul>
			</div>
		</div>
		<?php if($value['status']=='10'){ ?>
		   <?php if(isset($value['applyStatus'])&&$value['applyStatus']){ ?>
				<div class="sup_sq">
					<div class="tip_l"> </div>
					<div class="tip_r"> 
					<!--<a href="#" class="current">联系发布方</a>-->
					</div>
				</div>
		   <?php }else{ ?>
				<div class="sup_sq">
					<div class="tip_l"> </div>
					<div class="tip_r">  
					<a href="<?= Url::toRoute(['/product/details','productid'=>$value['productid']]) ?>" class="current">完善资料</a>
					</div>
				</div>
		   <?php } ?>
		<?php }else{ ?>
		     <div class="sup_sq">
				<div class="tip_l"> </div>
				<div class="tip_r">  
				<a href="<?= Url::toRoute(['/wrelease/details','productid'=>$value['productid']]) ?>" class="current">查看进度</a>
				</div>
			</div>
		<?php } ?>
		
		</li>
	  <?php endforeach; ?>
    </ul>
	<div id="pullUp" style="display:none;" >
		<span class="pullUpIcon"></span><span class="pullUpLabel"></span>
	</div>
	</div>
	
  </div>
</section>

<script>
datalistClass = '#scroller ul.thelist li.products';
function rele(type){
	location.href = "<?= yii\helpers\Url::toRoute('/wrelease/index')?>"+"?type="+type;
}
$(document).ready(function(){
	$(".ongoing").click(function(){
		rele($(this).attr('id'),0);
	})
})
</script>