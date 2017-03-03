<?php
use yii\helpers\Html;
use yii\helpers\Url;

use wx\widget\wxHeaderWidget;

//echo Yii::$app->user->getId();
//var_dump($data);
$StatusClass1= 'success';
$StatusClass1Label = '发布成功';

$StatusClass2= isset($data['productApply'])&&$data['productApply']?($data['productApply']['status']==20?'success':(in_array($data['status'],['20','30','40'])?'success':'waiting')):'waiting';
$StatusClass2Label = '面谈中';

$StatusClass3=isset($data['productApply'])&&$data['productApply']?($data['productApply']['status']==40?($data['status']==30?"fail":(in_array($data['status'],['20','40'])?'success':'wait')):'waiting'):'wait';
$StatusClass3Label=isset($data['productApply'])&&$data['productApply']?($data['productApply']['status']==40?($data['status']==30?"已终止":"处理订单"):'处理订单'):'处理订单';

$StatusClass4=isset($data['productApply'])&&$data['productApply']?($data['productApply']['status']==40?($data['status']==40?"success":(in_array($data['status'],['10','30'])?'wait':'waiting')):'wait'):'wait';		
$StatusClass4Label='结案';
	
$gohtml = '';	
if($data['status'] == '10' || $data['status'] == '10'&&$data['productApply']['status']== '20' ){
			$gohtml = '<a href="javascript:;" class="cancel" data-productid='.Yii::$app->request->get('productid').' >删除订单</a>';
}else if($data['productApply']['status']==40&&$data['productApply']['orders']['status']==20){
	if($data['productOrdersTerminationsApply']){
		if($data['productOrdersTerminationsApply']['create_by'] == Yii::$app->user->getId()){
			$label = '申请终止中';
		}else{
			$label = '处理终止';
		}
		if($data['accessTerminationAUTH']||$data['accessTerminationAPPLY']){
			$gohtml .= Html::a($label, ["productorders/orders-termination-detail","terminationid"=>$data['productOrdersTerminationsApply']['terminationid']],['class'=>'btn orderstermination' ]);
		}else{
			$gohtml .=Html::a("终止中", "javascript:void(0)" );
		}
	}else{
		if($data['accessTerminationAPPLY']){
			$gohtml = Html::a("申请终止",['/productorders/orders-termination-form',"ordersid"=>$data['productApply']['orders']['ordersid']],['class'=>"termination"]);
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
$prev = $messageid ?"messagePrev":'productDetailPrev';
$backurl = Url::previous($prev)?:true;//Url::toRoute(['/wrelease/details','productid'=>$data['productid']]);
$detailUrl = Url::toRoute(['/wrelease/details','productid'=>$data['productid']]);
?>
<style>
.tips{
	margin:0 auto;
    width: 72px;
	height:0px;
    display: block;
	position: relative;
}
.tips .tip_Bg{
	position: absolute;
    top: -64px;
    left: 0px;
	background: url(/bate2.0/images/ser1.png) left 0px top -218px no-repeat; width: 72px;height: 72px; 
	background-size: 96px;
	}
</style>
<?=wxHeaderWidget::widget(['title'=>'产品详情','gohtml'=>isset($gohtml)?$gohtml:'','backurl'=>$backurl,'reload'=>false])?>
<!-------产品详情信息编辑------->
<section>
<p id="show"></p>
<div  id="wrapper" style="min-height:300px;">
<div id="scroller"  >
			<div id="pullDown" style="display:none">
				<span class="pullDownIcon"></span><span class="pullDownLabel"></span>
			</div>

  <ul class="keep" style="margin-top:0px;<?php if(in_array($data['status'],['40'])){echo 'display:none;';} ?>">
    <a href="<?=Url::toRoute(['/product/details','productid'=>$data['productid']]);?>">
    <li class="keep01" style="border:0px solid #ddd;"> <span class="keep_ig1"></span> <span class="baocun"><?= $data['number']?></span> <i class="jiantou jiantou01"></i> <span style="float:right;margin-right:10px;vertical-align: middle;color: #999; font-size: 14px;"><?php if($data['status']=='10'){echo '完善信息';}else{echo '查看详情';}?></span> </li>
    </a>
  </ul>
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
  
 <?php if(in_array($data['status'],['20','30','40'])||$data['status']=='10'&&$data['productApply']&&$data['productApply']['status']=='20'){ ?>
		<div class="cp_xinxi">
			<ul>
				<a href="#" style="margin-top:12px;">
				<li>
				<div class="cp_right" style="width:70%; overflow: hidden;">
					<a style='display:block;height: 40px;' href='<?= yii\helpers\Url::toRoute(['user/detail','userid'=>$data['productApply']['create_by'],'productid'=>$data['productid']]);?>'>
					<span style="font-size:16px;width: 90%; overflow: hidden; display: inline-block;">接单方:
						<?php if($applyPeople || isset($data['productApply'])&&$data['productApply']){ ?>
						<?php if($applyPeople){ ?>
							<?= $applyPeople['createuser']['realname']?$applyPeople['createuser']['realname']:$applyPeople['createuser']['username'] ?>
						<?php }else{ ?>
							<?= $data['productApply']['createuser']['realname']?$data['productApply']['createuser']['realname']:$data['productApply']['createuser']['username'] ?>
						<?php } ?>
						<?php } ?>
					</span>
					<div class="arrow_l"  > <i></i> </div>
					</a>
					</div>
					<?php if($applyPeople || isset($data['productApply'])&&$data['productApply']){?>
						<div><a href="tel:<?=$data['productApply']['createuser']['mobile']?>" class="phone"s>联系他</a></div>
					<?php }?>
				</li>
				</a>
			</ul>
		</div>
 <?php } ?>
 <?php if($data['status']=='10'){ ?>
    <?php if($data['productApply']&&$data['productApply']['status']=='20'){ ?>
		<div class="results">
			<p style="padding-top:12px;">等待面谈</p>
			<p class="wait"></p>
			<p>双方联系并约见面并确定是否由TA作为接单方面谈时可能需要准备的<i class='remind' style="color:#148fcc;">《材料清单》</i></p>
		</div>
	<?php }else{ ?>
		<div class="results" style="margin-top:0px;">
			<p style="padding-top:12px;">发布成功</p>
			<p class="success"></p>
			<p>选择一个申请方作为意向接单方进行面谈</p>
		</div>
		
		<ul class="keep" style="margin-top:10px;">
			<li class="keep01" style="border-bottom:0px solid #ddd;">
				<a href="<?= yii\helpers\Url::toRoute(['/wrelease/apply-record','productid'=>$data['productid']])?>">
					<span class="keep_ig5"></span> <span class="baocun" style="font-size:16px;color:#333;"><?php if($applyPeople || isset($data['productApply'])&&$data['productApply']){echo '申请方'; }else{ echo '选择申请方';}?></span> 
					<i class="jiantou jiantou01"></i> 
					
					<?php if($applyPeople || isset($data['productApply'])&&$data['productApply']){ ?>
					<span style="float:right;margin-right:10px;vertical-align: middle;color: #999; font-size: 14px;">
						<?php if($applyPeople){ ?>
							<?= $applyPeople['createuser']['realname']?$applyPeople['createuser']['realname']:$applyPeople['createuser']['username'] ?>
						<?php }else{ ?>
							<?= $data['productApply']['createuser']['realname']?$data['productApply']['createuser']['realname']:$data['productApply']['createuser']['username'] ?>
						<?php } ?>
					</span> 
					<?php }else{
						echo $data['applyCount']?"<span class='cicle'>{$data['applyCount']}</span>":'';
					} ?>
					
					 
				</a>
			</li>
		</ul>
	<?php } ?>
 <?php } ?>
 <?php if(isset($data['productApply'])&&$data['productApply']['status']==40&&in_array($data['productApply']['orders']['status'],[10,20,30,40])){?>
  <div class="cp_xinxi" style="margin-top:10px;<?php if(in_array($data['status'],['40'])){echo 'display:none;';} ?>" > 
    <ul>
      <li>
        <div class="cp_right"> <span style="font-size:16px;">居间协议</span> </div>
        <!-----查看协议----->
        <div class="arrow_l arrow_t" style="">
			<a  style="color:#999;" href="javascript:void(0)" class='ordersconfirmDetail' data-productid='<?= $data['productid']?>'>点击查看</a> <i></i> 
		</div>
	
      </li>
    </ul>
  </div>
  <?php }?>
 <?php if(in_array($data['status'],['20','30','40'])&&in_array($data['productApply']['status'],['40'])){?>
  <div class="cp_xinxi" style="margin-top:10px; <?php if(in_array($data['status'],['40'])){echo 'display:none;';} ?>">
    <ul>
      <li>
        <div class="cp_right"> <span style="font-size:16px;">签约协议详情</span> </div>
		<?php if(in_array($data['status'],['20'])&&!isset($data['productApply']['orders'])||isset($data['productApply']['orders'])&&in_array($data['productApply']['orders']['status'],['0','10'])){?>
        <!-----上传协议----->
			<div class="arrow_l arrow_t">
				<a href="javascript:void(0);">等待接单方上传</a>
			</div>
		<?php }else{?>
        <!-----查看协议----->
			<div class="arrow_l arrow_t" style="">
				<a  style="color:#999;" href="<?= yii\helpers\Url::toRoute(["productorders/orders-pact-detail","ordersid"=>$data['productApply']['orders']['ordersid']])?>">点击查看</a> <i></i> 
			</div>
		<?php }?>
      </li>
    </ul>
  </div>
  <?php }?>
<?php if(in_array($data['status'],['0','10'])|| isset($data['productApply'])&&in_array($data['productApply']['status'],['20'])){ ?>
<style>
   .title1 .article{padding-bottom: 10px;}
  .layui-layer-rim{width: 80% !important;height: 360px !important;border: 0px solid #8D8D8D;border: 0px solid rgba(0,0,0,.3);}
  .layui-layer-title{	background-color: #ecf3fd;height:80px;line-height:24px;text-align:center;padding:30px 0px;    overflow: inherit;} 
  .cname{float:right;margin-right:10px;vertical-align: middle;color: #999; font-size: 14px;}
  .cicle{top: 20px;
	  float: right;
	  margin-right: 10px;
	  vertical-align: middle;
	  color: #fff;
	  font-size: 14px;
	  height: 20px;
	  width: 20px;
	  border-radius: 10px;
	  background: #f4ae5c;
	  position: relative;
	  display: block;
	  line-height: 20px;
	  text-align:center;
	  }
	  
.layui-layer-btn {
    text-align: center;
    padding: 0 10px 12px;
    pointer-events: auto;
}	  
.layui-layer-btn a {
	font-size:12px;
    width:120px;height:30px;line-height:30px;
    margin: 0 6px;
    border: 1px solid #dedede;
    background-color: #f1f1f1;
    color: #333;
    border-radius: 20px;
    font-weight: 400;
    cursor: pointer;
    text-decoration: none;
}	
</style>
<?php } ?>
 
 <?php if(isset($data['productApply'])&&$data['productApply']['status']=='40'){ ?>
  <div class="jzdc" <?php if(in_array($data['status'],['40'])){echo 'style="display:none;"';} ?>> <span class="jz">处理进度</span> 

  </div>
  <div class="xqing fabujindu" <?php if(in_array($data['status'],['40'])){echo 'style="display:none;"';} ?>>
    <ul>
	<?php 
		$logCount = count($data['productApply']['orders']['productOrdersLogs']);
		$relaid = '';
		foreach($data['productApply']['orders']['productOrdersLogs'] as $item => $OrdersLog):
		    $relaid .= $OrdersLog["action"]=='41'?$OrdersLog["relaid"]:'';
			// if($OrdersLog['action']!='10'){
				// if($OrdersLog['action_by']!=$data['productApply']['orders']['create_by']){
					// $label = "我";
					// $class = "me";
				// }else{
					// $label = "接";
					// $class = "";
				// }
			// }else{
				// $label = "系";
				// $class = "system";
			// }
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
            <p class="article <?= $OrdersLog['class'] ?>">
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
	  <?php endforeach; ?> 
    </ul>
  </div>
   <?php }?>
<?php if(isset($data['Prompt'])&&$data['Prompt'] == 40){ ?>
	<div class="xin"> <a href="<?= yii\helpers\Url::toRoute(["productorders/orders-closed-detail","closedid"=>$data['relaid']]) ?>">对方申请结案此单,点击处理&gt;</a> </div>
<?php }else if(isset($data['Prompt'])&&$data['Prompt'] == 50){ ?>
	<div class="xin"> <a href="<?= yii\helpers\Url::toRoute(["productorders/orders-termination-detail","terminationid"=>$data['relaid']]) ?>">对方申请终止此单,点击处理&gt;</a> </div>
<?php } ?>
 
<!-----发布结案----->
<?php if(in_array($data['status'],['40'])){?>
 
  <div class="jdan" style="height:80px;background:#fff url(/images/bg.png) no-repeat top;">
   <span class="jz" style="line-height:35px;margin-top:15px;font-size:16px;"><?= $data['number'] ?></span>
    <p style="width:100px;height:20px;margin-left:10px;color:#999;font-size:14px;">订单已结案</p>
    <a href="<?= yii\helpers\Url::toRoute(["productorders/orders-closed-detail","closedid"=>isset($relaid)?$relaid:'']) ?>">结清证明</a>
	</div>
	
  <div class="qing">
    <table cellpadding="0" cellspacing="0" style="width:96%;margin:auto;">
      <tbody>
        <tr>
          <td width="30" class='xq_title'>产品信息</td>
          <td colspan="3" class='xq_con' >
			<p class='xq_con_title'>基本信息 <a href="<?= yii\helpers\Url::toRoute(['/product/details','productid'=>$data['productid']]);?>" >查看全部</a></p>
            <div class="Methods3">
              <dl>
                <dt>债权类型：</dt>
                <dd><?= isset($data['categoryLabel'])?$data['categoryLabel']:''?></dd>
                <dt><?= $data["type"]=='1'?'固定费用：':'服务佣金：'?></dt>
                <dd><?= isset($data['typenumLabel'])?$data['typenumLabel'].$data['typeLabel']:''?></dd>
                <dt>委托金额：</dt>
                <dd><?= isset($data['accountLabel'])?$data['accountLabel']:''?>万</dd>
              </dl>
            </div>
			</td>
        </tr>
      </tbody>
      <tbody>
        <tr>
          <td width="30" class='xq_title' >签约协议</td>
          <td  colspan="3" class='xq_con' >
			<p class='xq_con_title'>签约详情 <a href="<?= yii\helpers\Url::toRoute(["productorders/orders-pact-detail","ordersid"=>$data['productApply']['orders']['ordersid']])?>" >查看全部</a></p>
            <ul class="tu">
			<?php foreach($data['SignPicture'] as $key=>$value): 
				if($key>3)break;
				echo "<li>".Html::img(Yii::$app->params["wx"].$value['file'],['class'=>'imageWxview','data-img'=>Yii::$app->params["wx"].$value['file']]).'</li>';
				endforeach; ?>
            </ul>
            </td>
          </tr>
      </tbody>
      <tbody>
        <tr colspan="4">
          <td width="30" class='xq_title' style="border-bottom:0px solid #ddd;">处置进度</td>
          <td  class='xq_title' style="border-bottom:0px solid #ddd;padding:0px;text-align:center;"><a href="javascript:void(0);" style="color:#10a1ec;" class='cha'>查看详情</a></td>
          <td width="30" class='xq_title' style="border-bottom:0px solid #ddd;">居间协议</td>
          <td  class='xq_title' style="border-bottom:0px solid #ddd;border-right:0px solid #ddd;padding:0px;text-align:center;"><a href="javascript:void(0);" style="color:#10a1ec;" class='ordersconfirmDetail' data-productid='<?= $data['productid']?>' >查看详情</a></td>
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
<?php if($data['status']=='20'&&isset($data['productApply']['orders'])&&in_array($data['productApply']['orders']['status'],['0','10','20'])){ ?>
   <footer>
   <div class="bottom">
    <div class="bottom-l">
      <input type="text" name="memo" placeholder="请输入留言" style="width:95%;height:35px;padding-left:10px;background:#f2f3f7;border:1px solid #ddd;border-radius:3px;">
    </div>
    <div class="bottom-r"> <a href="javascript:void(0);" class='SendOut' data-orders='<?= $data['productApply']['orders']['ordersid']?>' style="background:#10a1ec;margin-top:0px;">发送</a> </div>
  </div>
  </footer> 
<?php } ?>
<?php if($data['status']=='10'&&!$data['productApply']){ ?> 
<footer>
  <div class="bottom" style="height:80px;">
    <p class="remind" style="text-align:center;margin-top: 5px;font-size:12px;"><i class="keep_ig6"></i>需准备的<i style="color:#148fcc;font-size:12px;">《材料清单》</i></p>
    <!-- <a href="cpxqjzdc7.html">发起面谈</a> -->
    <a href="javascript:void(0);" <?php if(Yii::$app->request->get('applyid')){echo 'class="miantan"';}else{echo 'class="tishi"';}?>  data-applyid='<?= Yii::$app->request->get('applyid')?>' style="<?php if(Yii::$app->request->get('applyid')){echo 'background:#10a1ec;';}?>margin-top:5px;">发起面谈</a>
  </div>
</footer> 
<?php }else if($data['status']=='10'&&$data['productApply']&&$data['productApply']['status']=='20'){ ?> 
<footer> 
  <div class="bottom" style="height:120px;"> 
		<a href="javascript:void(0);" class="jiedan" data-applyid='<?= isset($data['productApply'])&&$data['productApply']?$data['productApply']['applyid']:''?>' data-productid='<?= isset($data['productApply'])&&$data['productApply']?$data['productApply']['productid']:''?>'style="background:#10a1ec;">同意TA作为接单方</a> 
		<a href="javascript:void(0);" class='quxiao' data-applyid='<?= isset($data['productApply'])&&$data['productApply']?$data['productApply']['applyid']:''?>' style="background:#fff;color:#666;border:1px solid #ddd;">不合适,重新选择接单方</a> 
  </div>
</footer> 
<?php }else if($data['status'] == 40 && !$data["commentTotal"]){ ?>
<footer> 
 <div class="bottom"> <a href="<?= yii\helpers\Url::toRoute(['/productorders/comment-form','ordersid'=>$data["productApply"]["orders"]["ordersid"],'type'=>1])?>" style="background:#10a1ec;">发布评价</a> </div>
</footer> 
<?php }else if($data['status'] == 40 && $data["commentTotal"]){ ?>
<footer> 
 <div class="bottom"> <a href="<?= yii\helpers\Url::toRoute(['/productorders/comment-list','ordersid'=>$data["productApply"]["orders"]["ordersid"]])?>" style="background:#10a1ec;">查看评价</a> </div>
</footer> 
<?php } ?>

<?php if($data['validflag']==0){?>
<body></body>
<script>
$(document).ready(function(){
	layer.msg("该订单已删除",{time:3000,shade: [0.2,'#30393d']},function(){
		<?php if(Yii::$app->request->get("push")=="wx"){ ?>
			location.href="<?=Url::toRoute(['/message/index'])?>";
		<?php }else{ ?>
			history.go(-1)
		<?php }?>
	
	})
})
</script>
<?php 
// return false;
} ?>
  
  
  
  <script>
$(document).ready(function(){
	
	$(".remind").click(function(){
			layer.open({
			  type: 1,
			  btn:'我知道了',
			  title: "<span class='tips' ><span class='tip_Bg'></span></span><p>材料清单 </p><p> 约见面谈时需准备以下材料</p> ",
			  skin: 'layui-layer-rim', 
			  area: ['420px', '240px'],
			  content: '<p style="margin-top:20px;text-indent:50px;color:#148fcc;background:url(images/sfk.png)  no-repeat  10px ;">身份证</p><p style="margin-top:8px;text-indent:50px;color:#148fcc;background:url(images/sfk.png)  no-repeat  10px ;"">户口本</p><p style="margin-top:8px;text-indent:50px;color:#148fcc;background:url(images/sfk.png)  no-repeat  10px ;"">银行凭证</p><p style="margin-top:8px;text-indent:50px;color:#148fcc;background:url(images/sfk.png)  no-repeat  10px ;"">公证书</p><p style="margin-top:8px;text-indent:50px;color:#148fcc;background:url(images/sfk.png)  no-repeat  10px ;"">借款合同</p><p style="margin-top:8px;text-indent:50px;color:#148fcc;background:url(images/sfk.png)  no-repeat  10px ;"">他项权证</p><p style="margin-top:8px;text-indent:50px;color:#148fcc;background:url(images/sfk.png)  no-repeat  10px ;"">案件受理通知书(若立案)</p>'
			});
		})
	 $(".miantan").click(function(){
		 var applyid = $(this).attr('data-applyid');
		 var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});
		 $.ajax({
			 url:'<?= yii\helpers\Url::toRoute('/wrelease/apply-chat') ?>',
			 type:'post',
			 data:{applyid:applyid},
			 dataType:'json',
			 success:function(json){
				 if(json.code == '0000'){
					 layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>发起面谈成功",{time:500},function(){window.location.href='<?=$detailUrl?>'});
					 layer.close(index);
				 }else{
					 layer.msg(json.msg);
					 layer.close(index);
				 }
			 }
		 })
	 })
	 $('.jiedan').click(function(){
		 var applyid = $(this).attr('data-applyid');
		 var productid = $(this).attr('data-productid');
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
					var confirmindex = layer.confirm("",{
						  title: '确认协议',
						  content: html,
						  type: 0,
						  closeBtn: false,
						  area: ["100%","100%"],
						  btn: ['确认','取消'],
						  formType:0 //prompt风格，支持0-2
						}, function(){
							index = layer.load(1, {
							   time:2000,
								shade: [0.4,'#fff'] //0.1透明度的白色背景
							});
							$.ajax({
								url:'<?= yii\helpers\Url::toRoute('/wrelease/apply-agree') ?>',
								type:'post',
								data:{applyid:applyid},
								dataType:'json',
								success:function(json){
									layer.close(confirmindex)
									if(json.code == '0000'){
										layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>成功",{time:1000},function(){window.location.href='<?=$detailUrl?>'});
										layer.close(index);
									}else{
										layer.msg(json.msg);
										layer.close(index);
									}
								}
							})
						});
					
					$(".layui-layer-title").css({'padding':'0 80px 0 20px','height':'42px','line-height':'42px','text-align':'left'})
					$(".layui-layer-btn a").css({'border-radius':'0'})
					$(".layui-layer-content").animate({'height':'+=38px'},'fast')
				}
			})
	 })
	 
	 $('.quxiao').click(function(){
		 var applyid = $(this).attr('data-applyid');
		 var index = layer.load(1,{
			 shade:[0.4,'#fff']
		 });
		 $.ajax({
			 url:'<?= yii\helpers\Url::toRoute('/wrelease/apply-veto') ?>',
			 type:'post',
			 data:{applyid:applyid},
			 dataType:'json',
			 success:function(json){
				 if(json.code == '0000'){
					 layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>取消成功",{time:500},function(){window.location.href='<?=$detailUrl?>'});
					 layer.close(index);
				 }else{
					 layer.msg(json.msg);
					 layer.close(index);
				 }
			 }
		 })
	 })
	 
	 $('.SendOut').click(function(){
		 var ordersid = $(this).attr('data-orders');
		 var memo = $('input[name=memo]').val();
		 var index = layer.load(1,{
			 shade:[0.4,'#fff']
		 });
		 $.ajax({
			 url:'<?= yii\helpers\Url::toRoute('/wrelease/orders-process-add')?>',
			 type:'post',
			 data:{ordersid:ordersid,memo:memo,type:0},
			 dataType:'json',
			 success:function(json){
				 if(json.code == '0000'){
					 window.location.reload();
					 layer.close(index);
				 }else{
					 layer.msg(json.msg);
					 layer.close(index);
				 }
			 }
		 })
	 })
	 
	 $(".ordersconfirmDetail").click(function(){
			var productid = $(this).attr('data-productid');
			<?php if($showtype=="pdf"){?>
				
				var url = '<?= Yii\helpers\Url::toRoute(['/protocol/index',"type"=>"pdf"])?>'+'&productid='+productid;
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
	 
	 $('.cancel').click(function(){
		 var productid = $(this).attr('data-productid');
		 var index = layer.load(1,{
			 shade:[0.4,'#fff']
		 });
		 $.ajax({
			 url:'<?= yii\helpers\Url::toRoute('/wrelease/product-delete')?>',
			 type:'post',
			 data:{productid:productid},
			 dataType:'json',
			 success:function(json){
				 if(json.code == '0000'){
					 layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>删除成功",{time:500},function(){window.location.href="/wrelease/index?type=1"});
					 layer.close(index);
				 }else{
					 layer.msg(json.msg);
					 layer.close(index);
				 }
			 }
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
			content:'<div class="xqing fabujindu">'+$(".xqing").html()+'</div>'
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
	
	 $('.tishi').click(function(){
		 layer.msg('请选择面谈用户');
	 })
	 var type = '<?= Yii::$app->request->get('type')?>';
	if(type=='1'){
	    layer.msg("恭喜用户已完成你的订单，您可以去评价接单方的服务是否满意！",{time:6000},function(){window.location.href="<?= yii\helpers\Url::toRoute(['/wrelease/details','productid'=>Yii::$app->request->get('productid')])?>"});
	}
})

</script>
<script src="/js/fastclick.js"></script>