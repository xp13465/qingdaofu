<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use wx\widget\wxHeaderWidget;
$category = ['1'=>'进行中','2'=>'已完成'];
$this->registerJsFile('@web/js/fastclick.js',['depends'=>['wx\assets\NewAppAsset']]);
//var_dump($data);die;
?>

<style>
.num{padding:6px 12px;}
.num li{width:100%;height:auto;background:none;margin:0;}
.num li span{display:flex}
.num-l{text-align:right;display: flex;justify-content: space-between;
		width:30%;float:left;font-size:14px;line-height:26px;paddingsx-right:15px;color:#666;}
.num-r{width:70%;float:left;font-size:14px;text-align:left;line-height:24px;color:#666;} 
</style>
<?=wxHeaderWidget::widget(['title'=>'我的收藏','gohtml'=>'','backurl'=>yii\helpers\Url::toRoute('/user/index'),'reload'=>true])?>
<section>
  <p id="show"></p>
  <div id="wrapper">
	<div id="scroller" class="type" style="position:absolute;">
	<div id="pullDown" style="display:none">
		<span class="pullDownIcon"></span><span class="pullDownLabel"></span>
	</div>
	 <ul id="thelist" class="thelist" data-catr="10" data-url="<?=  yii\helpers\Url::toRoute(['/usercenter/collection'])?>">
	 <?php foreach($data as $key => $value): ?>
		<li class="product">
		 <a href="<?php echo yii\helpers\Url::toRoute(['/list/detail',"id"=>$value['productid']])?>">
		<div class="types" data-id="129" data-category="2">
			<div class="rongzi" style="border-bottom: 0px solid #ddd;">
				<div class="over">
					<div class="flo_l"> 
						<span class="code"><?= isset($value["product"]['number'])?$value["product"]['number']:''; ?></span> <i class="jiantou"></i> 
					</div>
					<?php if(in_array($value["product"]['status'],['10','20'])){ ?>
						<span class="flo_r"><?= isset($value["product"]['statusLabel'])?$value["product"]['statusLabel']:''; ?></span>
					<?php }else if(in_array($value["product"]['status'],['30'])){ ?>
						<span class="flo_t">&nbsp;</span>
					<?php }else if(in_array($value["product"]['status'],['40'])){ ?>
						<span class="flo_j">&nbsp;</span> 	
					<?php } ?>
                    				
				</div>
			</div>
			<div class="num">
			<ul>
				<li> <span class="num-l"><font>委</font><font>托</font><font>费</font><font>用：</font></span> <span class="num-r"><?php if($value["product"]['type']==1){echo $value["product"]['typenumLabel'].$value["product"]['typeLabel'];}else{echo $value["product"]['typenum'].$value["product"]['typeLabel'];}?></span> </li>
				<li> <span class="num-l"><font>委</font><font>托</font><font>托</font><font>本</font><font>金：</font></span> <span class="num-r"><?= isset($value["product"]['accountLabel'])?$value["product"]['accountLabel']:'' ?>万 </span> </li>
				<li> <span class="num-l"><font>委</font><font>托</font>期<font>限：</font></span> <span class="num-r"><?= isset($value["product"]['overdue'])?$value["product"]['overdue']:'' ?>个月 </span> </li>
				<li> <span class="num-l"><font>委</font><font>托</font><font>类</font><font>型：</font></span> <span class="num-r"><?= isset($value["product"]['categoryLabel'])?$value["product"]['categoryLabel']:'' ?></span> </li>
				<li> <span class="num-l"><font>合</font><font>同</font><font>履</font><font>行</font><font>地：</font></span> <span class="num-r"><?= isset($value["product"]['addressLabel'])?$value["product"]['addressLabel']:'' ?></span> </li>
			</ul>
			</div>
		</div>
		  </a> 
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
datalistClass = '#scroller ul.thelist li.product';
</script>