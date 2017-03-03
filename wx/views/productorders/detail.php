<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
$category = ['1'=>'进行中','2'=>'已完成'];
$StatusClass1=in_array($data['status'],[50,60])?'fail':"success";
$StatusClass1Label=in_array($data['status'],[50,60])?($data['status']==50?"取消申请":"申请失败"):"申请中";

$StatusClass2=in_array($data['status'],[20,40])?'success':($data['status']==30?'fail':($data['status']==10?"waiting":"wait"));
$StatusClass2Label=in_array($data['status'],[20,40])?'面谈中':($data['status']==30?'面谈失败':($data['status']==10?"面谈中":"面谈中"));

$StatusClass3=$data['status']==40?($data['orders']['status']==30?"fail":"success"):($data['status']==20?"waiting":"wait");
$StatusClass3Label=$data['status']==40?($data['orders']['status']==30?"已终止":"处理订单"):($data['status']==20?"处理订单":"处理订单");

$StatusClass4=$data['orders']&&$data['orders']['status']==40?"success":($data['status']==40?($data['orders']['status']==30?"wait":"waiting"):"wait");
$StatusClass4Label='结案';
$gohtml = '';
// $data['status']=20;
// $data['orders']['status']=10;


if($data['status']==10){
	$gohtml = '<a href="javascript:;" class="cancelbtn" data-applyid='.Yii::$app->request->get('applyid').' >取消申请</a>';
}else if($data['status']==40&&$data['orders']['status']==20){
	if($data['productOrdersTerminationsApply']){
		if($data['productOrdersTerminationsApply']['create_by']==$userid){
			$label = '申请终止中';
		}else{
			$label = '处理终止';
		}
		if($data['accessTerminationAUTH']||$data['accessTerminationAPPLY']){
			$gohtml .= Html::a($label, ["productorders/orders-termination-detail","terminationid"=>$data['productOrdersTerminationsApply']['terminationid']], ['class'=>'btn orderstermination' ] );
		}else{
			$gohtml .=Html::a("终止中", "javascript:void(0)" );
		}
	}else{
		if($data['accessTerminationAPPLY']&&$data['create_by']==$userid){
			$gohtml = Html::a("申请终止",['/productorders/orders-termination-form',"ordersid"=>$data['orders']['ordersid']],['class'=>"termination"]);
		}
	}
	
}
$uagent = $_SERVER['HTTP_USER_AGENT'];
$showtype ="pdf";
if(stripos($uagent,"Android")>-1 ||stripos($uagent,"Linux")>-1 ){
	if(strpos($uagent, 'MicroMessenger')>-1){
		$showtype = 'view';
	}
}

// echo $showtype;
$prev = $messageid ?"messagePrev":'ordersDetailPrev';
$backurl = Url::previous($prev)?:true;
?>
<style>
title1 .article{padding-bottom: 10px;}
.num{padding:6px 12px;}
.num li{width:100%;height:auto;background:none;margin:0;}
.num li span{display:flex}
.num-l{text-align:right;display: flex;justify-content: space-between;
		width:30%;float:left;font-size:14px;line-height:26px;paddingsx-right:15px;color:#666;}
.num-r{width:70%;float:left;font-size:14px;text-align:left;line-height:24px;color:#666;} 
</style>
<?=wxHeaderWidget::widget(['title'=>'产品详情','gohtml'=>$gohtml,'backurl'=>$backurl,'homebtn'=>true,'reload'=>false])?>
<section>
<p id="show"></p>
<div  id="wrapper" style="min-height:300px;">
<div id="scroller"  >
			<div id="pullDown" style="display:none">
				<span class="pullDownIcon"></span><span class="pullDownLabel"></span>
			</div>
<?php if($data['status']==10){?>
  <div class="head-top">等待发布方同意并发起面谈</div>
<?php }?>
  <div class="jind">
    <ul>
      <li class='<?=$StatusClass1?>'> <i>1</i>
        <p><?=$StatusClass1Label?></p>
      </li>
      <li class='<?=$StatusClass2?>'> <i>2</i>
        <p><?=$StatusClass2Label?></p>
      </li>
      <li class='<?=$StatusClass3?>'> <i>3</i>
        <p><?=$StatusClass3Label?></p>
      </li>
      <li class='<?=$StatusClass4?>'> <i>4</i>
        <p><?=$StatusClass4Label?></p>
      </li>
    </ul>
  </div>
  
  <div class="cp_xinxi">
    <ul>
      <a href="#" style="margin-top:20px;">
      <li>
        <div class="cp_right" style="width:<?=!in_array($data['status'],[10])?"70":"100"?>%;white-space: nowrap; overflow: hidden;">
		<a style='display:block;height: 40px;' href='<?= yii\helpers\Url::toRoute(['user/detail','userid'=>$data['product']['create_by'],'productid'=>$data['product']['productid']]);?>'>
		<span style="font-size:16px;width: 90%;    overflow: hidden;    display: inline-block;">发布方:<?=$data['product']['fabuuser']['realname']?:$data['product']['fabuuser']['username']?></span>
		<div class="arrow_l"  > <i></i> </div>
		</a>
		</div>
		<?php if(!in_array($data['status'],[10])){?>
			<div><a href="tel:<?=$data['product']['fabuuser']['mobile']?>" class="phone"s>联系他</a></div>
		<?php }?>
      </li>
      </a>
    </ul>
  </div>
  <?php if($data['status']==10){?>
  <!------等待面谈------>
  <div class="results" style="margin-top:12px;">
   <p style="padding-top:12px;">申请中</p>
   <p class="waiting"></p>
    <p>申请中,等待发布方同意</p>
  </div>
  <?php }?>
  <?php if($data['status']==50){?>
  <div class="results" style="margin-top:12px;">
   <p style="padding-top:12px;">已取消申请</p>
    <p class="cancel"></p>
    <p>您已经取消本次申请</p>
  </div>
  <?php }?>
   <?php if($data['status']==20){?>
  <!------等待面谈------>
  <div class="results" style="margin-top:12px;">
	<p style="padding-top:12px;">等待面谈</p>
   <p class="wait"></p>
    <p>双方联系并约见面谈，面谈后由发布方确定是否由您来接单</p>
  </div>
  <?php }?>
   <?php if($data['status']==30){?>
  <!------面谈失败------>
  <div class="results" style="margin-top:12px;">
   <p style="padding-top:12px;">面谈失败</p>
    <p class="fail"></p>
    <p>面谈失败，对方未选择您作为接单方</p>
  </div>
    <?php }?>
  <!------产品信息------>
  <div class="show" <?php if($data['orders']&&in_array($data['orders']['status'],['40'])){echo 'style="display:none;"';} ?>>
	<div class="show_xx"> <span style="color:#333;"><?=($data['product']['number'])?></span> 
	<?php if($data['status']==40&&in_array($data['orders']['status'],[20,40])){?>
	<a href="<?=Url::toRoute(['/product/details',"productid"=>$data["productid"]])?>" style="color: #999; line-height: 50px;float:right;">更多信息</a> 
	<?php }?>
	</div>
    <ul class="revert">
      <li>
        <div class="revert_l"> <span>债权类型</span> </div>
        <div class="revert_r"> <span><?=str_replace(",","、",$data['product']['categoryLabel'])?></span> </div>
      </li>
      <li>
        <div class="revert_l"> <span>委托事项</span> </div>
        <div class="revert_r"> <?=str_replace(",","、",$data['product']['entrustLabel'])?></div>
      </li>
      <li>
        <div class="revert_l"> <span>委托金额</span> </div>
        <div class="revert_r"> <span><?=($data['product']['accountLabel'])?></span>万</div>
      </li>
      <li>
        <div class="revert_l"> <span>固定费用</span> </div>
        <div class="revert_r"> <span><?=($data['product']['typenumLabel'])?><?=($data['product']['typeLabel'])?></span> </div>
      </li>
      <li>
        <div class="revert_l"> <span>逾期期限</span> </div>
        <div class="revert_r"> <span><?=($data['product']['overdue'])?>个月</span> </div>
      </li>
      <li>
        <div class="revert_l"> <span>合同履行地</span> </div>
        <div class="revert_r"> <span><?=($data['product']['addressLabel'])?></span> </div>
      </li>
    </ul>
  </div>
  <?php if($data['status']==40&&in_array($data['orders']['status'],[10,20,30,40])){?>
  <div class="cp_xinxi" style="margin-top:10px;<?php if(in_array($data['orders']['status'],['40'])){echo 'display:none;';} ?>">
    <ul>
      <li>
        <div class="cp_right"> <span style="font-size:16px;">居间协议</span> </div>
        <!-----查看协议----->
        <div class="arrow_l arrow_t" style="">
			<a  style="color:#999;" href="javascript:void(0)" class='ordersconfirmDetail' data-productid='<?= $data['orders']['productid']?>'>点击查看</a> <i></i> 
		</div>
	
      </li>
    </ul>
  </div>
  <?php }?>
  <?php if($data['status']==40&&in_array($data['orders']['status'],[10,20,30,40])){?>
  <div class="cp_xinxi" style="margin-top:10px;<?php if(in_array($data['orders']['status'],['40'])){echo 'display:none;';} ?>">
    <ul>
      <li>
        <div class="cp_right"> <span style="font-size:16px;">签约协议详情</span> </div>
		<?php if(in_array($data['orders']['status'],[10])&&$data['accessOrdersORDERCOMFIRM']){?>
        <!-----上传协议----->
        <div class="arrow_l arrow_t">
			<a href="<?=Url::toRoute(["productorders/orders-pact-detail","applyid"=>$data['orders']['applyid'],"ordersid"=>$data['orders']['ordersid']])?>">上传协议</a>
        </div>
		<?php }else{?>
        <!-----查看协议----->
        <div class="arrow_l arrow_t" style="">
			<a  style="color:#999;" href="<?=Url::toRoute(["productorders/orders-pact-detail","ordersid"=>$data['orders']['ordersid']])?>">点击查看</a> <i></i> 
		</div>
		<?php }?>
      </li>
    </ul>
  </div>
  <?php }?>
  <?php if($data['status']==40&&in_array($data['orders']['status'],[20,30,40])){?>
  <div class="cp_xinxi" style="margin-top:10px;">
    <ul>
      <a href="#">
      <li>
        <div class="cp_right"> <span style="font-size:16px;">经办人</span> </div>
        <div class="arrow_l"><a href='<?=Url::toRoute(["productorders/orders-operator-list","ordersid"=>$data['orders']['ordersid']])?>' ><span style="color:#999;"><?=$data['productOrdersOperatorsCount']?>个经办人</span> <i></i></a></div>
      </li>
      </a>
    </ul>
  </div>
   <?php }?>
   <?php if($data['status']==40){?>
  <div class="jzdc" <?php if(in_array($data['orders']['status'],['40'])){echo 'style="display:none;"';} ?>> <span class="jz">处置进度</span> 
  <?php if($data['orders']&&$data['orders']['status']==20){?>
  <a href="<?=Url::toRoute(["productorders/orders-process-form","applyid"=>$data['orders']['applyid'],"ordersid"=>$data['orders']['ordersid']])?>">添加进度</a>
  <?php }?>
  </div>
  <div class="xqing jzdcxqing jiedanjindu" <?php if(in_array($data['orders']['status'],['40'])){echo 'style="display:none;"';} ?>>
    <ul style='overflow:hidden;'>
	<?php 
		
		$logCount = count($data['orders']['productOrdersLogs']);
		$relaid ='';
		foreach($data['orders']['productOrdersLogs'] as $item => $OrdersLog):
			$relaid .= $OrdersLog["action"]=='41'?$OrdersLog["relaid"]:'';
		?>
      <li class="<?=$item?"read":"";?>">
        <div class="time">
          <p class="hours"><?=date("H:i",$OrdersLog['action_at'])?></p>
          <p class="dates"><?=date("Y.m.d",$OrdersLog['action_at'])?></p>
        </div>
        <div class="<?=($item+1)==$logCount?"title3".(($item+1)==1?' first':''):"title1"?>"> 
		<i class='<?=$OrdersLog['class']?> <?=($item+1)==1?"top":""?>'>
		<span>
		<?php echo $OrdersLog['label'];?>
		</span>
		</i>
          <div  class='message'>
            <p class="article <?=$OrdersLog['class']?>">
			<?php 
			echo "[".$OrdersLog['actionLabel']."]";
			
			echo nl2br($OrdersLog['memo']);
			
			switch($OrdersLog['action']){
				case 40:
					if($OrdersLog['trigger']){
						echo Html::a($OrdersLog["triggerLabel"],Url::toRoute(["productorders/orders-closed-detail","closedid"=>$OrdersLog['relaid']]),["style"=>""]);
					}else{
						echo Html::a("查看详情",Url::toRoute(["productorders/orders-closed-detail","closedid"=>$OrdersLog['relaid']]),["style"=>""]);
					}
					break;
				case 41:
					echo Html::a("查看详情",Url::toRoute(["productorders/orders-closed-detail","closedid"=>$OrdersLog['relaid']]),["style"=>""]);
					break;
				case 42:
					echo Html::a("查看详情",Url::toRoute(["productorders/orders-closed-detail","closedid"=>$OrdersLog['relaid']]),["style"=>""]);
					break;
				case 50:
					if($OrdersLog['trigger']){
						echo Html::a($OrdersLog["triggerLabel"],Url::toRoute(["productorders/orders-termination-detail","terminationid"=>$OrdersLog['relaid']]),["style"=>""]);
					}else{
						echo Html::a("查看详情",Url::toRoute(["productorders/orders-termination-detail","terminationid"=>$OrdersLog['relaid']]),["style"=>""]);
					}
					break;
				case 51:
					echo Html::a("查看详情",Url::toRoute(["productorders/orders-termination-detail","terminationid"=>$OrdersLog['relaid']]),["style"=>""]);
					break;
				case 52:
					echo Html::a("查看详情",Url::toRoute(["productorders/orders-termination-detail","terminationid"=>$OrdersLog['relaid']]),["style"=>""]);
					break;
			}
			?> 
			</p>
			<span class="pig">
			  <?php 
			foreach($OrdersLog['filesImg'] as $file){
				echo Html::img(Yii::$app->params["wx"].$file['file'],['class'=>'imageWxview','data-img'=>Yii::$app->params["wx"].$file['file']]);
			}
			?></span>
          </div>
        </div>
        <div class='clear'></div>
      </li>
   
	  <?php  endforeach;?> 
    </ul>
  </div>
   <?php }?>
   <?php if(isset($data['Prompt'])&&$data['Prompt'] == 40){ ?>
	<div class="xin"> <a href="<?= yii\helpers\Url::toRoute(["productorders/orders-closed-detail","closedid"=>$data['relaid']]) ?>">对方申请结案此单,点击处理&gt;</a> </div>
	<?php } ?>
	
<?php if(in_array($data['orders']['status'],['40'])){ ?>

  <div class="jdan" style="height:80px;background:#fff url(/images/bg.png) no-repeat top;">
   <span class="jz" style="line-height:35px;margin-top:15px;font-size:16px;"><?= $data['product']['number'] ?></span>
    <p style="width:100px;height:20px;margin-left:10px;color:#999;font-size:14px;">订单已结案</p>
    <a href="<?= yii\helpers\Url::toRoute(["productorders/orders-closed-detail","closedid"=>isset($relaid)?$relaid:'']) ?>">结清证明</a> 
	 </div>
  <div class="qing">
    <table cellpadding="0" cellspacing="0" style="width:96%;margin:auto;">
      <tbody>
        <tr>
          <td width="30" class='xq_title'>产品信息</td>
          <td colspan="3" class='xq_con' >
			<p class='xq_con_title'>基本信息 <a href="<?= yii\helpers\Url::toRoute(['/product/details','productid'=>$data['product']['productid']]);?>" >查看全部</a></p>
            <div class="Methods3">
              <dl>
                <dt>债权类型：</dt>
                <dd><?= isset($data['product']['categoryLabel'])?$data['product']['categoryLabel']:''?></dd>
                <dt><?= $data['product']["type"]=='1'?'固定费用：':'服务佣金：'?></dt>
                <dd><?= isset($data['product']['typenumLabel'])?$data['product']['typenumLabel'].$data['product']['typeLabel']:''?></dd>
                <dt>委托金额：</dt>
                <dd><?= isset($data['product']['accountLabel'])?($data['product']['accountLabel']."万"):''?></dd>
              </dl>
            </div>
			</td>
        </tr>
      </tbody>
      <tbody>
        <tr>
          <td width="30" class='xq_title' >签约协议</td>
          <td colspan="3" class='xq_con' >
			<p class='xq_con_title'>签约详情 <a href="<?= yii\helpers\Url::toRoute(["productorders/orders-pact-detail","ordersid"=>$data['orders']['ordersid']])?>" >查看全部</a></p>
            <ul class="tu">
			<?php foreach($data['SignPicture'] as $key=>$value):
					if($key>3)break;
					echo '<li>'.Html::img(Yii::$app->params["wx"].$value['file'],['class'=>'imageWxview','data-img'=>Yii::$app->params["wx"].$value['file']]).'</li>';
			endforeach; ?>
            </ul>
            </td>
          </tr>
      </tbody>
      <tbody>
        <tr colspan="4">
          <td width="30" class='xq_title' style="border-bottom:0px solid #ddd;">处置进度</td>
          <td  class='xq_title' style="border-bottom:0px solid #ddd;padding:0px;text-align:center;">
			<a href="javascript:void(0)"  class ='ordersJDDetail'style="color:#10a1ec;">查看详情</a>
		  </td>
          <td width="30" class='xq_title' style="border-bottom:0px solid #ddd;">居间协议</td>
          <td  class='xq_title' style="border-bottom:0px solid #ddd;border-right:0px solid #ddd;padding:0px;text-align:center;">
			<a href="javascript:void(0)"  class ='ordersconfirmDetail'  data-productid='<?= $data['orders']['productid']?>' style="color:#10a1ec;">查看详情</a>
		  </td>
         </tr>
      </tbody>
    </table>
	

<?php } ?>
<div id="pullUp" style="display:none;" >
		<span class="pullUpIcon"></span><span class="pullUpLabel"></span>
	</div>
	</div>
  </div>
</section>
<?php if($data['orders'] && $data['orders']['status'] == 40 && !$data["myCommentTotal"]&& $data['accessOrdersADDCOMMENT']){ ?>
<footer> 
 <div class="bottom"> <a href="<?= yii\helpers\Url::toRoute(['/productorders/comment-form','ordersid'=>$data["orders"]["ordersid"],'type'=>1])?>" style="background:#10a1ec;">发布评价</a> </div>
</footer> 
<?php }else if($data['orders'] && $data['orders']['status'] == 40 && $data["productOrdersCommentsNum"]){ ?>
<footer> 
 <div class="bottom"> <a href="<?= yii\helpers\Url::toRoute(['/productorders/comment-list','ordersid'=>$data["orders"]["ordersid"]])?>" style="background:#10a1ec;">查看评价</a> </div>
</footer> 
<?php } ?>
<!-----处理订单-已上传协议照片结束-------->
<footer>
   <?php 
	$html = "";
	if($data['status']==40&&$data['orders']){
		if($data['orders']['status']=="0"&&$data['accessOrdersORDERCOMFIRM']){
			$html .= Html::a('居间协议确认', 'javascript:void(0)', ['class'=>'btn ordersconfirm' ,"style"=>'background:#10a1ec;'] );
		}
		if($data['orders']['status']=="10"&&$data['accessOrdersORDERCOMFIRM']){
			$html .= Html::a('上传签约协议', Url::toRoute(["productorders/orders-pact-detail","applyid"=>$data['orders']['applyid'],"ordersid"=>$data['orders']['ordersid']]), ['class'=>'btn ordersupload' ,"style"=>'background:#10a1ec;'] );
		}
		
		if($data['orders']['status']=="20"){
			if( $data['productOrdersClosedsApply']){
				if($data['productOrdersClosedsApply']['create_by']==$userid){
					$label = '申请结案中';
				}else{
					$label = '处理结案';
				}
				if($data['accessClosedAUTH']||$data['accessClosedAPPLY']){
					$html .= Html::a($label, ["productorders/orders-closed-detail","closedid"=>$data['productOrdersClosedsApply']['closedid']], ['class'=>'btn ordersclose' ] );
				}else{
					$html .=Html::a("结案中", "javascript:void(0)" );
				}
			}else{
				if($data['accessClosedAPPLY']&&$data['create_by']==$userid){
					$html .= Html::a("申请结案", ["productorders/orders-closed-form","ordersid"=>$data['orders']['ordersid'],'applyid'=>Yii::$app->request->get('applyid')], ['class'=>'btn' ,"style"=>'background:#10a1ec;'] );
				}
			}
			
		}
	}
	if($html){
		echo '<div class="bottom"> '.$html.' </div>';
	}
	?>
</footer>
<style>
/*.layui-layer-btn {
    text-align: center;
    padding: 0 10px 12px;
    pointer-events: auto;
}	
.layui-layer-btn a {
    width:120px;height:30px;line-height:30px;
    margin: 0 6px;
    border: 1px solid #dedede;
    background-color: #f1f1f1;
    color: #333;
    border-radius: 0;
    font-weight: 400;
    cursor: pointer;
    text-decoration: none;
}	*/
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
<?php if($data['validflag']==0){?>
<body></body>
<script>
$(document).ready(function(){
	layer.msg("该申请已删除",{time:3000,shade: [0.2,'#30393d']},function(){
		<?php if(Yii::$app->request->get("push")=="wx"){ ?>
			location.href="<?=Url::toRoute(['/message/index'])?>";
		<?php }else{ ?>
			history.go(-1)
		<?php }?>
	})
	
})
</script>
<?php 
return false;
} ?>

<?php if($data["product"]['validflag'] == '0' && $data['status'] == '60'){?>
<script>
$(document).ready(function(){
	layer.msg("发布方已取消该笔订单的发布",{time:3000,shade: [0.2,'#30393d']},function(){
		<?php if(Yii::$app->request->get("push")=="wx"){ ?>
			location.href="<?=Url::toRoute(['/message/index'])?>";
		<?php }else{ ?>
			history.go(-1)
		<?php }?>
	})
	
})
</script>
<?php 
return false;
} ?>
<?php if(in_array($data["product"]['status'],['20','30','40'])&& $data['status'] == '60'){?>
<script>
$(document).ready(function(){
	layer.msg("发布方已和其他接单方撮合",{time:30000,shade: [0.2,'#30393d']},function(){
		<?php if(Yii::$app->request->get("push")=="wx"){ ?>
			location.href="<?=Url::toRoute(['/message/index'])?>";
		<?php }else{ ?>
			history.go(-1)
		<?php }?>
	})
	
})
</script>
<?php 
return false;
} ?>


<script>
$(document).ready(function(){
<?php  if($data["status"]==20&&$data['hascertification']==0){  ?>
		layer.confirm("您还未认证，发布方无法将您设为接单方！",{btn:["前往认证","取消"],title:false,closeBtn:false},function(){location.href="<?= yii\helpers\Url::toRoute('/certification/index')?>"});
<?php 	}	?>
	var applyid = "<?=$data['applyid']?>";
	<?php if($data['orders']){?>
		var ordersid = "<?=$data['orders']['ordersid']?>";
		var productid = "<?=$data['orders']['productid']?>";
		$(".ordersconfirm").click(function(){
			// var productid = $(this).attr('data-productid');
			var index = layer.load(1, {
			   time:2000,
				shade: [0.4,'#fff'] //0.1透明度的白色背景
			});
			$.ajax({
				url:'<?= Yii\helpers\Url::toRoute('/protocol/index')?>'+'?productid='+productid,
				type:'post',
				data:{},
				dataType:'html',
				success:function(html){
					layer.close(index)
					layer.confirm("",{
						  title: '确认协议',
						  content: html,
						  type: 0,
						  closeBtn: false,
						  area: ["100%","100%"],
						  btn: ['确认','取消'],
						  formType:0 //prompt风格，支持0-2
						}, function(){
							var index = layer.load(1, {
							   time:2000,
								shade: [0.4,'#fff'] //0.1透明度的白色背景
							});
							$.ajax({
								url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-confirm')?>",
								type:'post',
								data:{ordersid:ordersid},
								dataType:'json',
								success:function(json){
									layer.close(index)
									if(json.code=="0000"){
										layer.msg(json.msg,{},function(){location.reload()})
									}else{
										layer.msg(json.msg)
									}
									
								}
							})
						});
				}
			})
			
			

		})
		$(".ordersconfirmDetail").click(function(){
			var productid = $(this).attr('data-productid');
			<?php if($showtype=="pdf"){?>
				var url = '<?= Yii\helpers\Url::toRoute(['/protocol/index',"type"=>"pdf"])?>'+'&productid='+productid;
				// alert(3)
				window.open(url)
			<?php }else{?>
				var index = layer.load(1, {
				   time:2000,
					shade: [0.4,'#fff'] //0.1透明度的白色背景
				});
				$.ajax({
					url:'<?= Yii\helpers\Url::toRoute('/protocol/index')?>'+'?productid='+productid,
					type:'post',
					data:{},
					dataType:'html',
					success:function(html){
						layer.close(index)
						layer.open({
							type: 1,
							title: '居间协议',
							shadeClose: true,
							scrollbar: false,
							// shade: 0.8,
							area: ['100%', '100%'],
							content: html,
						});
					}
				})
			<?php } ?>
		})
		$(".ordersJDDetail").click(function(){
			var productid = $(this).attr('data-productid');
			layer.open({
				type: 1,
				title: '处置进度',
				shadeClose: true,
				// shade: 0.8,
				area: ['100%', '100%'],
				content: '<div class="xqing jiedanjindu">'+$(".jzdcxqing").html()+'</div>',
			});
		})
		
		
/*
	$(".termination").click(function(){
			layer.confirm("<textarea id='applymemo' name='applymemo' style='width:100%;height:60px;resize:none'  placeholder='理由...'/></textarea>",{
			  title: '确定申请终止',
			  closeBtn: false,
			  btn: ['确认','取消'],
			  formType:0 //prompt风格，支持0-2
			}, function(){
				var applymemo = $("#applymemo").val();
				$.ajax({
					url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-termination-apply')?>",
					type:'post',
					data:{ordersid:ordersid,applymemo:applymemo},
					dataType:'json',
					success:function(json){
						if(json.code=="0000"){
							layer.msg(json.msg,{},function(){location.reload()})
						}else{
							layer.msg(json.msg)
						}
					}
				})
			});
		})*/
<?php }?>
	$('.cancelbtn').click(function(){
			var applyid = $(this).attr('data-applyid');
			layer.confirm("确定取消？",{title:false,closeBtn:false},function(){
				$.ajax({
				url:'<?= yii\helpers\Url::toRoute('/productorders/apply-cancel')?>',
				type:'post',
				data:{applyid:applyid},
				dataType:'json',
				success:function(json){
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
		
	$('.cha').click(function(){
		 // $('.wanjie').css('display','none');
		 // $('.jzdc').css('display','block');
		 // $('.xqing').css('display','block');
		layer.open({
			title:'进度详情',
			type: 1,
			area: ['100%', '100%'],
			shadeClose: true, //点击遮罩关闭
			content:$('.xqing').html()
		})
		$(".layui-layer-title").css({
			'text-align':'left',
			'height':'43px',
			'line-height':'21px',
			'padding':'10px',
		})
	 })
	 // $('.jzdc').click(function(){
		  // $('.wanjie').css('display','block');
		 // $('.jzdc').css('display','none');
		 // $('.xqing').css('display','none');
	 // })
})
</script>
<script src="/js/fastclick.js"></script>