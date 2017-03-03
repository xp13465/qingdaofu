<?php
use yii\helpers\Url;
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
$category = ['1'=>'进行中','2'=>'已完成'];
$this->registerJsFile('@web/js/fastclick.js',['depends'=>['wx\assets\NewAppAsset']]);
// echo "<pre>";
// print_r($data[0]);
// exit;
// var_dump($data);exit;
?>

<style>
.num{padding:6px 12px;}
.num li{width:100%;height:auto;background:none;margin:0;}
.num li span{display:flex}
.num-l{text-align:right;display: flex;justify-content: space-between;
		width:30%;float:left;font-size:14px;line-height:26px;paddingsx-right:15px;color:#666;}
.num-r{width:70%;float:left;font-size:14px;text-align:left;line-height:24px;color:#666;} 
.ongoing{border-bottom:1px solid #ddd;}

.layui-layer-btn{padding: 10px 10px 10px;pointer-events: auto;border-top: 1px solid #ddd;}
.layui-layer-btn a {
    height: 35px;
    line-height: 35px;
    margin: 0 6px;
    width: 45%;
    text-align: center;
    border: 0px solid #dedede;
    background: #fff;
    color: #0da3f9;
    border-radius: 2px;
    font-weight: 400;
    cursor: pointer;
    text-decoration: none;
}
.layui-layer-btn .layui-layer-btn0 {background-color: #fff;color: #0da3f9;}

</style> 
<?=wxHeaderWidget::widget(['title'=>($type==3?"已终止的":($list==1?'经办事项':"我的接单")),'gohtml'=>'','backurl'=>$type==3?true:Url::toRoute("user/index"),'homebtn'=>true,'reload'=>true])?>
<section>
	<?php if($type!=3){?>
	  <div class="basic">
		<ul>
		  <?php foreach($category as $key => $value): ?>
			<li class="ongoing" id="<?= $key ?>"> <span <?php if($type == $key){echo 'class="current"';}?>><?= $value ?></span></li>
		  <?php endforeach; ?>
		  
		  <li style="width:100%;height:50px;border-bottom:0px solid #ddd;"> 
			<a style='display:block;' href="<?= yii\helpers\Url::toRoute(['productorders/index','type'=>'3','list'=>$list]);?>">
			<span style="float:left;margin-left:20px;">已终止</span>
			<div class="renzhen-01"><span>&nbsp;</span><i class="jiantou"></i> </div>
			<div class='clear'></div>
			</a>
		  </li>
		  
		</ul>
	  </div>
	<?php }?>
  <p id="show"></p>
  <div class="type" id="wrapper">
    <div id="scroller" class="type" style="position:absolute;">
			<div id="pullDown" style="display:none">
				<span class="pullDownIcon"></span><span class="pullDownLabel"></span>
			</div>
    <ul id="thelist" class="thelist" data-catr="<?=$curCount?>" data-url="<?=  yii\helpers\Url::toRoute(['/productorders/index','list'=>$list,'type'=>$type])?>">
	 <?php foreach($data as $key => $value): 
		$product = $value['product'];
		?>
		<li class="products">
		 
		<div class="types" data-id="129" data-category="2">
			<a href="<?= yii\helpers\Url::toRoute(['/productorders/detail','applyid'=>$value['applyid']]) ?>">
			<div class="rongzi" style="border-bottom: 0px solid #ddd;">
				<div class="over">
					<div class="flo_l"> 
						<span class="code"><?= isset($product['number'])?$product['number']:''; ?></span> <i class="jiantou"></i> 
					</div>
					<?php if($value['orders']&&$value['orders']['status']==30){ ?>
					<span class="flo_t">&nbsp;</span>
						
					<?php }else if($value['orders']&&$value['orders']['status']==40){ ?>
						<span class="flo_j">&nbsp;</span> 	
					<?php }else{ ?>
						<span class="flo_r"><?= isset($value['statusLabel'])?$value['statusLabel']:''; ?></span>
					<?php } ?> 
				</div>
			</div>
			 </a>
			<div class="num">
			<ul>
				<li> <span class="num-l"><font>委</font><font>托</font><font>托</font><font>本</font><font>金：</font></span> <span class="num-r"><?= isset($product['accountLabel'])?$product['accountLabel']:'' ?>万</span> </li>
				<li> <span class="num-l"><?php if($product['type']==1){echo '<font>固</font><font>定</font><font>费</font><font>用：</font>';}else{echo '<font>风</font><font>险</font><font>代</font><font>理：</font>';}?></span> <span class="num-r"><?php if($product['type']==1){echo $product['typenumLabel'].$product['typeLabel'];}else{echo $product['typenum'].$product['typeLabel'];}?></span> </li>
				<li> <span class="num-l"><font>委</font><font>托</font>期<font>限：</font></span> <span class="num-r"><?= isset($product['overdue'])?$product['overdue']:'' ?>个月 </span> </li>
				<li> <span class="num-l"><font>委</font><font>托</font><font>类</font><font>型：</font></span> <span class="num-r"><?= isset($product['categoryLabel'])?$product['categoryLabel']:'' ?></span> </li>
				<li> <span class="num-l"><font>合</font><font>同</font><font>履</font><font>行</font><font>地：</font></span> <span class="num-r"><?= isset($product['addressLabel'])?$product['addressLabel']:'' ?></span> </li>
			</ul>
			</div>
		</div>
		
		 <div class="sup_sq">
		<?php  if($value['status']=='40'){ ?>
		   <?php if($value['orders']['status']==10&&$value['orders']['create_by']==$userid){ ?>
					<div class="tip_l"> </div>
					<div class="tip_r"> 
					<a href="<?=Url::toRoute(["productorders/orders-pact-detail","applyid"=>$value['orders']['applyid'],"ordersid"=>$value['orders']['ordersid']])?>">上传协议</a>
					</div>
		   <?php }else if($value['orders']['status']==20){ ?>
					<div class="tip_l"> </div>
					<div class="tip_r"> 
					<a href="<?=Url::toRoute(["productorders/orders-process-form","applyid"=>$value['orders']['applyid'],"ordersid"=>$value['orders']['ordersid']])?>" class="current">填写进度</a>
					</div>
		   <?php }else if($value['orders']['status']==40){ ?>
					<div class="tip_l"> </div>
					<div class="tip_r"> 
					</div>
		   <?php }?>
		<?php }else if($value['status']=='10'){ ?>
				<div class="tip_l"> </div>
				<div class="tip_r"> 
				<a href="javascript:void(0)"  data-applyid='<?=$value["applyid"]?>' class="current cancel">取消申请</a>
				</div>
		<?php }else if(in_array($value['status'],['30','50','60'])){ ?>
				<div class="tip_l"> </div>
					<div class="tip_r"> 
					<a href="javascript:void(0)"  data-applyid='<?=$value["applyid"]?>' class="current cance2">删除订单</a>
				</div>
		<?php } ?>
		</div>
		  
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

var list ='<?=$list?>';
function rele(type){
	var index = layer.load(1, {
				   time:2000,
					shade: [0.4,'#fff'] //0.1透明度的白色背景
				});
	location.href = "<?= yii\helpers\Url::toRoute('/productorders/index')?>"+"?type="+type+"&list="+list;
}
$(document).ready(function(){
	datalistClass = '#scroller ul.thelist li.products';
	$(".ongoing").click(function(){
		rele($(this).attr('id'));
	})
	$('.cancel').click(function(){
			var applyid = $(this).attr('data-applyid');
			layer.confirm("确定取消？",{title:false,closeBtn:false},function(){
				var index = layer.load(1, {
				   time:2000,
					shade: [0.4,'#fff'] //0.1透明度的白色背景
				});
				$.ajax({
				url:'<?= yii\helpers\Url::toRoute('/productorders/apply-cancel')?>',
				type:'post',
				data:{applyid:applyid},
				dataType:'json',
				success:function(json){
					layer.close(index)
					if(json.code == '0000'){
						layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>取消成功",{time:500},function(){window.location.reload()});
						layer.close(index);
					}else{
						layer.msg(json.msg);
						layer.close(index);
					}
				}
			})
		})
	})
	
	$('.cance2').click(function(){
		var applyid = $(this).attr('data-applyid');
		layer.confirm("确定删除？",{title:false,closeBtn:false},function(){
			var index = layer.load(1, {
				   time:2000,
					shade: [0.4,'#fff'] //0.1透明度的白色背景
			});
			$.ajax({
				url:'<?= yii\helpers\Url::toRoute('/productorders/apply-del') ?>',
				type:'post',
				data:{applyid:applyid},
				dataType:'json',
				success:function(json){
					layer.close(index)
					if(json.code == '0000'){
						layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>删除成功",{time:500},function(){window.location.reload()});
						layer.close(index);
					}else{
						layer.msg(json.msg);
						layer.close(index);
					}
				}
			})
		})
	})
})
</script>