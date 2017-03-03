<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$userid = Yii::$app->user->getId();
$label = "我的接单";
$url = ['index',"type"=>"0"];
$this->title = "接单详情";
if($data['orders']&&in_array($userid,$data['orders']["Operators"])){
	$label = "我的经办";
	$url = ['index',"type"=>"1"];
	$this->title = "经办详情";
}
$this->params['breadcrumbs'][] = ['label' => $label, 'url' =>$url ];
$this->params['breadcrumbs'][] = $this->title;

$action = Yii::$app->controller->action->id;

$StatusClass1=in_array($data['status'],[50,60])?'fail':"success";
$StatusClass1Label=in_array($data['status'],[50,60])?($data['status']==50?"取消申请":"申请失败"):"申请中";
$StatusClass1Time = $data['create_at']?date("Y-m-d H:i:s",$data['create_at']):'';

$StatusClass2=in_array($data['status'],[20,40])?'success':($data['status']==30?'fail':($data['status']==10?"waiting":"wait"));
$StatusClass2Label=in_array($data['status'],[20,40])?'面谈中':($data['status']==30?'面谈失败':($data['status']==10?"面谈中":"面谈中"));
$StatusClass2Time = $data['modify_at']?date("Y-m-d H:i:s",$data['modify_at']):'';

$StatusClass3=$data['status']==40?($data['orders']['status']==30?"fail":"success"):($data['status']==20?"waiting":"wait");
$StatusClass3Label=$data['status']==40?($data['orders']['status']==30?"已终止":"处理订单"):($data['status']==20?"处理订单":"处理订单");
$StatusClass3Time = $data['status']==40?($data['orders']['status']==30?date("Y-m-d H:i:s",$data["orders"]['modify_at']):date("Y-m-d H:i:s",$data["orders"]['create_at'])):"";

$StatusClass4=$data['orders']&&$data['orders']['status']==40?"success":($data['status']==40?($data['orders']['status']==30?"wait":"waiting"):"wait");
$StatusClass4Label='结案';
$StatusClass4Time = isset($data["productOrdersClosed"])&&$data["productOrdersClosed"]?date("Y-m-d H:i:s",$data["productOrdersClosed"]['modify_at']):'';
 
// echo "<pre>";
// print_r($data);die;
// echo "</pre>";
$statusLabelClass = $data['status']=="40"&&$data["orders"]&&$data["orders"]["status"]=="30"?"state":(in_array($data["status"],["50","60"])?"state":"success");
if($data['status']=="40"&&$data['checkStatus']=="ORDERSPROCESS"&&($data["productOrdersTerminationsApplyCount"]||$data["productOrdersClosedsApplyCount"])){
	$statusLabelClass="appl";
	$StatusClass3="waited";
	$StatusClass4="wait";
	if($data["productOrdersTerminationsApplyCount"]){
		$data['statusLabel'] = $StatusClass3Label = "申请终止中";
		// var_dump($data["productOrdersTerminationsApply"]);
		$StatusClass3Time =$data["productOrdersTerminationsApply"]['create_at']?date("Y-m-d H:i:s",$data["productOrdersTerminationsApply"]['create_at']):"";
	}
	if($data["productOrdersClosedsApplyCount"]){
		$data['statusLabel'] = $StatusClass3Label =  "申请结案中";
		// var_dump($data["productOrdersClosedsApply"]);
		$StatusClass3Time =$data["productOrdersClosedsApply"]['create_at']?date("Y-m-d H:i:s",$data["productOrdersClosedsApply"]['create_at']):"";
	}
	
}

    
// var_dump($data);
$statusData = '';
$statusModify = '';
if(!$data['hascertification']&&$data['status']!="40"){
	 $statusData .= ''.Html::a('立即认证', ["/certifications/authentication/"]).'';
} 
if($data['orders']['status']=="20"&&$data['accessClosedAPPLY']&&$data['create_by']==$userid && !$data["productOrdersClosedsApplyCount"]){
	 $statusData .= '<a class="Closed" data-applyid = '.Yii::$app->request->get('applyid').'>申请结案</a>';
}

if($data['status']=='10'){
	$statusModify .= '<p class="cancel"><a href="javascript:;" class="cancelbtn" data-applyid='.Yii::$app->request->get('applyid').' >取消申请</a></p>';
}else if($data['status']=="40"&&$data['checkStatus']=='ORDERSPROCESS'&&isset($data['accessTerminationAPPLY'])&&$data['accessTerminationAPPLY']&&$data["productOrdersTerminationsApplyCount"]==0&&$data['create_by']==$userid){
	$statusModify .= '<p class="apply"><a class="terminations" >申请终止</a></p>';
}else if(in_array($data['status'],['30','50','60'])){
	$statusModify .='<p class="delete"><a class="deleteOrder" data-applyid='.$data["applyid"].'>删除订单</a></p>';
}


if(isset($data['productOrdersClosed']['closedid'])&&$data['productOrdersClosed']['closedid']){
	$statusData .= Html::a('结清证明', ["productorders/orders-closed-detail","closedid"=>$data['productOrdersClosed']['closedid']],["target"=>"_blank"]);
	if($data['accessOrdersADDCOMMENT']){
		$statusModify .='<p class="look too"><a class="bun" data-attr=' .($data["myCommentTotal"]?"additional":"add").'>'.(isset($data["commentList"])&&$data["myCommentTotal"]?"追加评价":"评价").'</a></p>';
	}
}else{
	if(isset($data['productOrdersTerminationsApply']['terminationid'])&&$data['productOrdersTerminationsApply']['terminationid']){
	$statusData .= Html::a('查看终止详情', ["productorders/orders-termination-detail","terminationid"=>$data['productOrdersTerminationsApply']['terminationid']],["target"=>"_blank"]);
	}
	if(isset($data['productOrdersClosedsApply']['closedid'])&&$data['productOrdersClosedsApply']['closedid']){
		$statusData .= Html::a('查看结案详情', ["productorders/orders-closed-detail","closedid"=>$data['productOrdersClosedsApply']['closedid']],["target"=>"_blank"]);
	}
}
if($data['status']=='40'){
	 if($data['orders']["status"]=="10"){
		$statusData .='<p class="look"><a href="#3">签约图片上传</a></p>';
	}else if($data['orders']["status"]=="0"){ 
		$statusData .='<p class="look"><a class="ordersconfirm" data-productid='.$data['orders']['productid'].'>确定协议</a></p>';
	}
}

?>
<?php if($messageId){echo '<script>window.opener.location.reload(); </script>';}?>
<?php if($data['validflag']=='0'){?>
<script>
$(document).ready(function(){
	layer.msg("该申请已删除",{time:3000,shade: [0.2,'#30393d']},function(){
		window.opener=null;
		window.close();
			//location.href="<?=Url::toRoute('/productorders/index?type=0')?>";
	})
	
})
</script>
<?php 
// return false;
} ?>

<?php if($data["product"]['validflag'] == '0' && $data['status'] == '60'){?>
<script>
$(document).ready(function(){
	layer.msg("发布方已取消该笔订单的发布",{time:3000,shade: [0.2,'#30393d']},function(){
		// window.opener=null;
		// window.close();
			//location.href="<?=Url::toRoute('/productorders/index?type=0')?>";
	})
	
})
</script>
<?php 
// return false;
} ?>
<?php if(in_array($data["product"]['status'],['20','30','40'])&& $data['status'] == '60'){?>
<script>
$(document).ready(function(){
	layer.msg("发布方已和其他接单方撮合",{time:3000,shade: [0.2,'#30393d']},function(){
		// window.opener=null;
		// window.close();
			//location.href="<?=Url::toRoute('/productorders/index?type=0')?>";
	})
	
})
</script>
<?php 
// return false;
} ?>
<script>
$(document).ready(function(){
	<?php  if($data["status"]==20&&$data['hascertification']==0){  ?>
				layer.confirm("您还未认证，发布方无法将您设为接单方！",{btn:["前往认证","取消"],title:false,closeBtn:false},function(){location.href="<?= yii\helpers\Url::toRoute('/certifications/index')?>"});
	<?php 	}	?>
})
</script>


 <!-------发布进度------>
<div class="fabu">
  <div class="progress">
    <div class="top"></div>
    <div class="infomation">
      <p class="orders"><?=$data["product"]["number"]?></p>
      <p class="<?=$statusLabelClass?>"><?=$data['statusLabel']?></p>
	  <p class="look"><?= $statusData?:"&nbsp;" ?></p>
	  <?= $statusModify ?>
	    
		
    </div>
    <div class="jind">
      <ul>
        <li class="<?=$StatusClass1?> first-child"> <i>1</i>
          <p class="art"><?=$StatusClass1Label?></p>
          <p class="arr">等待发布方同意<br/><?=$StatusClass1Time?></p>
        </li>
        <li class="<?=$StatusClass2?>"> <i>2</i>
          <p class="art"><?=$StatusClass2Label?></p>
          <p class="arr">面谈后等待发布方同意您作为他的接单方<br/><?=$StatusClass2Time?></p>
        </li>
        <li class="<?=$StatusClass3?>"> <i>3</i>
          <p class="art"><?=$StatusClass3Label?></p>
          <p class="arr">开始为发布方处理订单<br/><?=$StatusClass3Time?></p>
        </li>
        <li class="<?=$StatusClass4?> last-child"> <i>4</i>
          <p class="art"><?=$StatusClass4Label?></p>
          <p class="arr">订单处理完成<br/><?=$StatusClass4Time?></p>
        </li>
      </ul>
    </div>
  </div>
</div>
  <!-------处理进度------>
  
<?php if($data["status"] != 40){?>
   <!-------发布方信息------>
	<div class="show">
		<div class="product-three">
			<div class="prduct-l">
				<div class="top">
					<p class="title">发布方</p>
				</div>
				<div class="infomation">
					<p class="head"><img src="<?=$data['product']['fabuuser']["headimg"]?$data['product']['fabuuser']["headimg"]['file']:''?>"></p>
					<p class="name"><a target="_blank" href="<?=Url::toRoute(["/userinfo/detail","userid"=>$data['product']['fabuuser']["id"]])?>"><?=$data['product']['fabuuser']["realname"]?:$data['product']['fabuuser']["username"]?></a></p>
					<?php if(in_array($data["status"],["20","30","40"])){?>
					<p class="phone"><?=$data['product']['fabuuser']["mobile"]?></p>
					<?php if($data["status"]=="20"){?>
						<p class="phone">你可以联系发布方进行面谈</p>
					<?php }?>
					<?php }else{?>
						<p class="phone"><a class="ProductUser" href="javascript:void(0);" data-userid="<?=$data['product']['fabuuser']["id"]?>">点击查看发布方详情</a></p>
					<?php }?>
				  
				</div>
			</div>
			<div class="prduct-l">
				<div class="top">
				   <p class="title">产品信息</p>
				</div>
				<div class="infor">
					<dl>
						<dt>债权类型：</dt>
						<dd><?=$data["product"]['categoryLabel']?></dd>
						<dt>委托事项：</dt>
						<dd><?=$data["product"]['entrustLabel']?></dd>
						<dt>委托金额：</dt>
						<dd><?=$data["product"]['accountLabel']?>万</dd>
						<dt>委托费用：</dt>
						<dd><?=$data["product"]['typenumLabel']?><?=$data["product"]['typeLabel']?></dd>
						<dt>违约期限：</dt>
						<dd><?=$data["product"]['overdue']?>个月</dd>
						<dt>合同履行地：</dt>
						<dd><?=$data["product"]['addressLabel']?></dd>
					</dl>
				</div>
				<div class="clear"></div>
			</div>
		   <div class="prduct-l">
				<div class="top">
				   <p class="title">更多信息</p>
				</div>
				<div class="prompt">
				<?php if($data["hascertification"]){?>
					<p>只有发布方同意您成为他的接单方后才有权限查看债权详细信息</p>
				<?php }else{?>
					<p>只有成为接单方才能才看更多信息，您还未认证，发您方无法将你设为接单方</p>
					<!--<a href="<?=Url::toRoute(["/certifications/index"])?>">现在去认证</a>-->
				<?php }?>
				</div>
		   </div> 
			<div class="clear"></div>
		</div>
	</div>
<?php }else{?>
	<div class="chuli">
		<div class="progress">
			<div class="infomation">
				<p class="jie" style="height:26px !important;width:auto !important;">发布方</p>
				<p class="head"><img src="<?=$data['product']['fabuuser']["headimg"]?$data['product']['fabuuser']["headimg"]['file']:''?>"></p>
				<p class="name"><a target="_blank" href='<?=Url::toRoute(["/userinfo/detail","userid"=>$data['product']['fabuuser']["id"]])?>'><?=$data['product']['fabuuser']["realname"]?:$data['product']['fabuuser']["username"]?></a></p>
				<p class="phone"><?=$data['product']['fabuuser']["mobile"]?></p>
			</div>
			<div class="xqing">
				<div class="tup">
					<p class="title">处理进度</p>
					<?php if($data["orders"]["status"]==20){?>
					<a href="#" class="applyed">添加进度</a>
					<?php }?>
				</div>
				<div class="clear"></div>
				<ul>
					<?php 
					$logCount = count($data['orders']['productOrdersLogs']);
					foreach($data["orders"]['productOrdersLogs'] as $item=>$OrdersLog):
					
					?> 
					<li class="<?=$item?"read":"";?>">
						<div class="time">
							<p class="dates"><?=date("Y-m-d H:i",$OrdersLog['action_at'])?></p>
						</div>
						<div class="<?=($item+1)==$logCount?"title3":"title1"?>"> <i class="<?=$item?"system":"blue";?> top"> <span> &nbsp;  </span> </i>
							<div class="message">
								<p class="article <?=$OrdersLog['class']?:""?>"> 
								<?php 
								echo "[".$OrdersLog['actionLabel']."]";
								
								echo nl2br($OrdersLog['memoLabel']);
								
								switch($OrdersLog['action']){
									case 40:
										if($OrdersLog['trigger']){
											echo Html::a($OrdersLog["triggerLabel"],Url::toRoute(["productorders/orders-closed-detail","closedid"=>$OrdersLog['relaid']]),["target"=>"_blank","style"=>""]);
										}else{
											echo Html::a("查看详情",Url::toRoute(["productorders/orders-closed-detail","closedid"=>$OrdersLog['relaid']]),["target"=>"_blank","style"=>""]);
										}
										break;
									case 41:
										echo Html::a("查看详情",Url::toRoute(["productorders/orders-closed-detail","closedid"=>$OrdersLog['relaid']]),["target"=>"_blank","style"=>""]);
										break;
									case 42:
										echo Html::a("查看详情",Url::toRoute(["productorders/orders-closed-detail","closedid"=>$OrdersLog['relaid']]),["target"=>"_blank","style"=>""]);
										break;
									case 50:
										if($OrdersLog['trigger']){
											echo Html::a($OrdersLog["triggerLabel"],Url::toRoute(["productorders/orders-termination-detail","terminationid"=>$OrdersLog['relaid']]),["targe="=>"_blank","style"=>""]);
										}else{
											echo Html::a("查看详情",Url::toRoute(["productorders/orders-termination-detail","terminationid"=>$OrdersLog['relaid']]),["target"=>"_blank","style"=>""]);
										}
										break;
									case 51:
										echo Html::a("查看详情",Url::toRoute(["productorders/orders-termination-detail","terminationid"=>$OrdersLog['relaid']]),["target"=>"_blank","style"=>""]);
										break;
									case 52:
										echo Html::a("查看详情",Url::toRoute(["productorders/orders-termination-detail","terminationid"=>$OrdersLog['relaid']]),["target"=>"_blank","style"=>""]);
										break;
								}
								echo "。&nbsp;操作人员：";
								if($OrdersLog["action_by"]==$userid){
									echo "自己";
								}else{
									echo ($OrdersLog["actionUser"]["realname"]?:$OrdersLog["actionUser"]["username"]);
									echo "&nbsp;<a>".$OrdersLog["actionUser"]["mobile"]."</a>";
								}
								 
								?> </p>
								 <?php 
								foreach($OrdersLog['filesImg'] as $file){
									echo '<span class="pig">'.Html::img($file['file'],['data-img'=>$file['file'],"class"=>"imgView"]).'</span> ';
								}
								?>
							</div>
						</div>
						<div class="clear"></div>
					</li>
					<?php endforeach;?> 
				</ul>
			</div> 
			<div class="clear"></div>
		</div>
	</div>  
	<?php if($data["orders"]&&in_array($data["orders"]['status'],["20","30",'40'])){?>
	<!-------经办人列表------>
	<div class="jinglist">
		<div class="top">
		  <p class="title">经办人列表</p>
		  <?php if($data["orders"]["status"]=="20"&&$data['accessOrdersADDOPERATOR']){?>
			<a target="_blank" href="<?=Url::toRoute(["contacts/index","type"=>"2","ordersid"=>$data["orders"]["ordersid"]])?>" class="apply">添加经办人</a>
		  <?php }?>
		</div>
	  <table>
	  <thead>
	  <tr>
		<th width="400"><p class="head">经办人<p></th>
		<th width="300"><p>联系方式<p></th>
		<th width="300"><p>经办人等级<p></th>
		<th width="200"><p>操作<p></th>
	  </tr>
	  </thead>
	  <tbody>
	  <?php 
	  foreach($data['orders']['productOrdersOperators'] as $Operators){
		  $OperatorsUserinfo = $Operators["userinfo"];
		if(!$OperatorsUserinfo)continue;  
		?>
	  <tr class="cancel1"  data-owner ="<?=$Operators['owner']?>" >
		<td><p class="head <?=$Operators['level']==1?(""):"up"?>"><i></i><img src="<?=$OperatorsUserinfo['headimg']?$OperatorsUserinfo['headimg']['file']:''?>">&nbsp;&nbsp;<?=$OperatorsUserinfo['realname']?:$OperatorsUserinfo['username']?></p></td>
		<td><p><?=($userid != $Operators['operatorid'])?$OperatorsUserinfo['mobile']:''?></p></td>
		<td><p><?=$Operators['level']==1?"一级":"二级"?></p></td>
		<td>
		<?php 
		if($userid == $Operators['create_by']/*||$userid == $Operators['operatorid']*/){
			echo '<p><a data-id="'.$Operators['id'].'" class="see delOperators">删除</a></p>';
		}
		?>
		</td>
	  </tr>
	  <?php } ?>
	  </tbody>
	</table>
  </div>
	<?php }?>
	<!--------展示信息-------->
	<div class="show" id="3">
		<div class="product-four">
		  <div class="prduct-l">
			<div class="top">
			   <p class="title">产品信息</p>
			</div>
			<div class="infor">
				<dl>
					<dt>债权类型：</dt>
					<dd><?=$data["product"]['categoryLabel']?></dd>
					<dt>委托事项：</dt>
					<dd><?=$data["product"]['entrustLabel']?></dd>
					<dt>委托金额：</dt>
					<dd><?=$data["product"]['accountLabel']?>万</dd>
					<dt>委托费用：</dt>
					<dd><?=$data["product"]['typenumLabel']?><?=$data["product"]['typeLabel']?></dd>
					<dt>违约期限：</dt>
					<dd><?=$data["product"]['overdue']?>个月</dd>
					<dt>合同履行地：</dt>
					<dd><?=$data["product"]['addressLabel']?></dd>
				</dl>
			  <div class="clear"></div>
			</div>
		  </div>
		  <div class="prduct-r">
		  <?php if(in_array('1',explode(',',$data['product']['category']))){ ?> 
			<div class="top">
			   <p class="title">房屋抵押信息</p>
			</div>
			<div class="editor">
			  <?php if(isset($data['productMortgages1'])&&$data['productMortgages1']){ $a=1; foreach ($data['productMortgages1'] as $key => $value){ ?>
					<div class="editor-l"><p><?= isset($value['addressLabel'])?$a++.'、'.$value['addressLabel']:'' ?></p></div>
			 <?php }}else{echo'暂无';} ?>
			</div>
		  <?php } ?>
		  <?php if(in_array('2',explode(',',$data['product']['category']))){ ?> 
			<div class="top">
			   <p class="title">机动车抵押信息</p>
			</div>
			<div class="editor">
			   <?php if(isset($data['productMortgages2'])&&$data['productMortgages2']){ $a=1; foreach ($data['productMortgages2'] as $key => $value){ ?>
					<div class="editor-l"><p><?= isset($value['brandLabel'])?$a++.'、'.$value['brandLabel']:'' ?></p></div>
			 <?php }}else{echo'暂无';} ?>
			</div>
			<?php } ?>
			<?php if(in_array('3',explode(',',$data['product']['category']))){ ?>
			<div class="top">
			   <p class="title">合同纠纷类型</p>
			</div>
			<div class="editor">
			   <?php if(isset($data['productMortgages3'])&&$data['productMortgages3']){ $a=1; foreach ($data['productMortgages3'] as $key => $value){ ?>
					<div class="editor-l"><p><?= isset($value['contractLabel'])?$a++.'、'.$value['contractLabel']:'' ?></p></div>
				<?php }}else{echo'暂无';} ?>
			</div>
			<?php } ?>
		  </div>
		  
			<div class="prduct-r">
				<div class="top">
				   <p class="title">签约图片</p>
				</div>
				<?php if($data['orders']["status"]!="0"){?>
					<ul>
						<?php 
						foreach($data['SignPicture'] as $key=>$value):
							if($data['orders']["status"]=="10"){
								echo '<li><i class="closebutton"></i>'.Html::img($value['file'],['data-img'=>$value['file'],'data_bid'=>$value['id'],'class'=>'imgView']).'</li>';
							}else{
								echo '<li>'.Html::img($value['file'],['data-img'=>$value['file'],'class'=>'imgView']).'</li>';
							}
						endforeach; 
						?>
						<?php if($data['orders']["status"]=="10"){?>
						<a class="addtu" inputName='files' limit = '10' ><li class="upbtn" ><?php echo Html::hiddenInput("files",$data["orders"]["pact"],['data_url'=>'']);?></li></a>
						<?php }?>
					 </ul>
					 <div class="clear"></div>
					 <?php if($data['orders']["status"]=="10"){?>
						 <div class="prompt">
						 <p>只有上传签约协议图片后才能继续操作</p>
						 <a class="refused" >确定上传</a>
						 </div>
					 <?php }?>
				<?php }?>
			</div>
			<div class="prduct-r" style="border-right:0px solid #e5e5e5;">
				<div class="top">
				   <p class="title">居间协议</p>
				</div>
				<div class="butn">
				<?php if($data['orders']["status"]!="0"){?>
					<a class='ordersconfirmDetail' data-productid='<?= $data['orders']['productid']?>'>点击查看</a>
				<?php }else{ ?>
					<a class='ordersconfirm' data-productid='<?= $data['orders']['productid']?>'>确定协议</a>
				<?php }?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php if($data["orders"]&&in_array($data["orders"]['status'],['40'])){?>
	  <!------评价列表------>
	  <div class="evaluation">
		<div class="top">
		   <p class="title">评价详情</p>
			<?php if($data['accessOrdersADDCOMMENT']){?>
				<a href="javascript:void(0);" class="bun" data-attr = '<?=$data["myCommentTotal"]?"additional":"add"?>'><i></i><?= isset($data['commentList'])&&$data['commentList']['Comments1']?'追加评价':'评价' ?></a>
			<?php }?>
		</div>
		<ul class="list">
			<?php foreach($data["commentList"]['Comments1'] as $key => $value): 
			$score = ($value['truth_score']+$value['assort_score']+$value['response_score'])/3;
			?>
			<li>
				<div class="star">
				  <ul>
					<?php for($i=1;$i<=5;$i++){
						echo $score>$i?'<li class="bright"></li>':'<li class="gray"></li>';
					 }?>
				  </ul>
				  <p><?= date('Y年m月d日 H:i',$value['action_at'])?></p>
				</div>
				<div class="comments">
					<p class="text"><?= $value['memo']?></p>
					<?php if(isset($value['filesImg'])&&$value['filesImg']){ ?>
						<?php foreach($value['filesImg'] as $v):?>
							<p class="pic"><img class='imgView' src="<?=$v['file']?>"></p>
						<?php endforeach; ?>
					<?php } ?>
				</div>
				<div class="portrait">
					<p class="head"><img src="<?= isset($value["userinfo"]["headimg"]['file'])?$value["userinfo"]["headimg"]['file']:'/images/defaulthead.png' ?>"></p>
					<p class="name"><?= $value["userinfo"]["realname"]?:$value["userinfo"]["username"] ?></p>
				</div>
				<div class="clear"></div>
			</li>
			<?php endforeach; ?>
			<?php foreach($data["commentList"]['Comments2'] as $key => $value): ?>
			<li>
				<div class="star">
				  <p class="zhui">【追加评论】</p>
				  <p><?= date('Y-m-d H:i',$value['action_at'])?></p>
				</div>
				<div class="comments">
					<p class="text"><?= $value['memo']?></p>
					<?php if(isset($value['filesImg'])&&$value['filesImg']){ ?>
						<?php foreach($value['filesImg'] as $v):?>
							<p class="pic"><img class='imgView' src="<?=$v['file']?>"></p>
						<?php endforeach; ?>
					<?php } ?>
				</div>
				<div class="portrait">
					<p class="head"><img src="<?= isset($value["userinfo"]["headimg"]['file'])?$value["userinfo"]["headimg"]['file']:'/images/defaulthead.png' ?>"></p>
					<p class="name"><?= $value["userinfo"]["realname"]?:$value["userinfo"]["username"] ?></p>
				</div>
				<div class="clear"></div>
			</li>
			<?php endforeach; ?>
		</ul>
	  </div>
	<?php }?>
<?php }?>


<script id='terminations' type="text/template">
      <div class="list_con1">
        <ul class="area">
			<span style="color:#333;margin:0px 20px 0px 0px;float:left;">终止原因:
			</span><code> 
			<textarea type="text" name="applymemo" id="applymemo" style="width:448px;height:100px;padding:5px;"></textarea>
			</code></li>
        </ul>
      </div>
	  <div class="tu">
		<ul>
		<a class="addtu" inputName='files' limit = '10' data-type='1' >
		  <li class="upbtn">
		  <?php echo Html::hiddenInput("files",'',['data_url'=>'','id'=>'fileData']);?>
		  <p>点击上传图片</p>
		  </li>
		  </a>
		</ul>
	  </div>
 </script>
 <script id='evaluations' type="text/template">
	  <div class="pj_star" <?= isset($data["commentList"])&&$data["myCommentTotal"]?"style='display:none'":""?>>
		<ul>
		  <li> 
		    <span>真实性</span>
			<div class="stars">
			  <div id="star1"><input type='checkbox' name='truth_score' class='truth_score'></div>
			  <div id="result1"><input type='checkbox' name='truth_score' class='truth_score'></div>
			</div>
		  </li>
		  <li>
		    <span>配合度</span>
			<div class="stars">
			  <div id="star2"><input type='checkbox' name='assort_score' class='assort_score'></div>
			  <div id="result2"><input type='checkbox' name='assort_score' class='assort_score'></div>
			</div>
		  </li>
		  <li>
		    <span>响应度</span>
			<div class="stars">
			  <div id="star3"><input type='checkbox' name='response_score' class='response_score'></div>
			  <div id="result3"><input type='checkbox' name='response_score' class='response_score'></div>
			</div>
		  </li>
		</ul>
	  </div>   
     <div class="list_con1">
        <ul class="area">
			<span style="color:#333;margin:20px 20px 0px 20px;float:left;">评价详情:
			</span><code> 
			<textarea type="text" name="memo" data-ordersid="<?= $data['orders']['ordersid']?>" data-type='<?= isset($data["commentList"])&&$data["commentList"]['Comments1']?"2'":"1"?>' id="memo" placeholder="请输入评价内容，不少于5个字" style="width:467px;height:100px;margin-top:20px;"></textarea>
			</code></li>
        </ul>
      </div>
	  <div class="tu" style="margin-left:97px;">
		<ul>
		   <a class="addtu" inputName='files' limit = '10' data-type='1' ><li class="upbtn">
		  <?php echo Html::hiddenInput("files",'',['data_url'=>'','id'=>'filesData']);?>
		 <p>点击上传图片</p>
		  </li></a>
		</ul>
	  </div>
 </script>
<script src="/bate2.0/js/jquery.raty.js" type="text/javascript"></script>
<script type="text/javascript">
function rat(star,result,m){

	star= '#' + star;
	result= '#' + result;
	$(result).hide();

	$(star).raty({
		hints: ['10','20', '30', '40', '50'],
		path: "/bate2.0/images",
		starOff: 'star-off-big.png',
		starOn: 'star-on-big.png',
		size: 24,
		start: 50,
		showHalf: true,
		target: result,
		targetKeep : true,
		click: function (score, evt) {
			//alert('你的评分是'+score*m+'分');
		}
	});

}
</script>
<script id='creditor' type="text/template">
      <div class="list_con1">
        <ul class="area">
          <li> <span style="color:#333;width:80px;float:left;">选择类型:</span>
             <code class="field-servicesreservation-city_id required">
            <select id="selectvalue" class="form-control" style="width:526px;">
				<option value="0" selected="">留言</option>
            </select>
            </code></li>
			<li> 
			<span style="color:#333;width:80px;margin-top:20px;float:left;">内容描述:
			</span><code> 
			<textarea type="text" name="memo" id="memo" style="width:515px;height:90px;margin-top:20px;padding:5px;"></textarea>
			</code></li>
        </ul>
      </div>
	  <div class="tu">
		<ul>
		  <a class="addtu" inputName='files' limit = '10' data-type='1' ><li class="upbtn">
		  <?php echo Html::hiddenInput("files",'',['data_url'=>'']);?>
		  <p>点击上传图片</p> 
		  </li></a>
		</ul>
	  </div>
 </script>
</body>

 <script id='price' type="text/template">
      <div class="list_con1">
        <ul class="area">
			<li> 
				<span style="color:#333;margin:20px 20px 0px 0px;float:left;">实际结案金额:</span>
				<?= Html::input('text','price','',['placeholder'=>'实际结案金额','style'=>'width:78%;height:30px;line-height:35px;','id'=>'applymemo']);?>万
			</li>
			<li> 
				<span style="color:#333;margin:20px 48px 0px 0px;float:left;">实收佣金:</span>
				<?= Html::input('text','price','',['placeholder'=>'实收佣金','style'=>'width:78%;height:30px;line-height:35px;','id'=>"price2"]);?>万
			</li>
        </ul>
      </div>
 </script>

<script>
$(document).ready(function(){
	 
	
	
		$('.xqing ul').perfectScrollbar();
		var csrf = '<?=Yii::$app->request->getCsrfToken()?>'
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
				url:'<?= Url::toRoute('/product/agreement')?>'+'?productid='+productid,
				type:'post',
				data:{_csrf:csrf},
				dataType:'html',
				success:function(html){
					layer.close(index)
					layer.confirm("",{
						  title: '确认协议',
						  content: html,
						  type: 0,
						  closeBtn: false,
						  area: ["800px","90%"],
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
								data:{ordersid:ordersid,_csrf:csrf},
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
				var url = '<?= Yii\helpers\Url::toRoute(['/product/agreement',"type"=>"pdf"])?>'+'&productid='+productid;
				window.open(url)
		})
		

		$(".delOperators").click(function(){
			curdel = $(this).parents("tr")
			var ordersid = '<?=$data['orders']['ordersid']?>';	
			var id = $(this).attr("data-id");
			layer.confirm("确定删除？",{},function(){
				$.ajax({
					url:"<?php echo Url::toRoute('/productorders/orders-operator-unset')?>",
					type:'post',
					data:{ordersid:ordersid,ids:id,_csrf:csrf},
					async: false,
					dataType:'json',
					success:function(json){
						if(json.code=="0000"){
							layer.msg(json.msg,{time:2000},function(){
								var owner = curdel.attr("data-owner")
								$("tr[data-owner='"+owner+"']").each(function(){
									$(this).remove()
								});
							})
						}else{
							layer.msg(json.msg)
						}
					}
				})
				
			})
			
			
		})
		$(".refused").click(function(){
			var files = $("input[name='files']").val();
			$.ajax({
				url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-pact-confirm')?>",
				type:'post',
				data:{ordersid:ordersid,files:files,_csrf:csrf},
				dataType:'json',
				success:function(json){
					if(json.code=="0000"){
						layer.msg(json.msg,{time:2000},function(){location.reload()})
					}else{
						layer.msg(json.msg)
					}
				}
			})
		})
		<?php }?>
		
		
		
		
		$(".remind").click(function(){
			layer.open({
			  type: 1,
			  btn:'我知道了',
			  title: "<span class='tips' ><span class='tip_Bg'></span></span><p>材料清单 </p><p> 约见面谈时需准备以下材料</p> ",
			  skin: 'data', 
			  area: ['550px', '240px'],
			  content: '<p>身份证</p><p>户口本</p><p>银行凭证</p><p>公证书</p><p >借款合同</p><p>他项权证</p><p>案件受理通知书(若立案)</p>'
			})		
		})
		 $(".applyed").click(function(){
			layer.confirm($("#creditor").html(), {
					type:1,
					skin:'tian',
					btn: ['提交'] ,
					title:'添加进度',
					area: ['650px', '390px']
				},function(){
						
						
						var memo = $("#memo").val();
						var type = $("#selectvalue").val();
						var files = $("input[name='files']").val();
						var ordersid = '<?=$data['orders']['ordersid']?>';
						// alert(type)
						$.ajax({
							url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-process-add')?>",
							type:'post',
							data:{ordersid:ordersid,memo:memo,files:files,type:type,_csrf:csrf},
							dataType:'json',
							success:function(json){
								if(json.code=="0000"){
									layer.msg(json.msg,{},function(){location.reload()})
								}else{
									layer.msg(json.msg)
								}
							}
						})
				}
			)
			var ordersid = '<?=$data['orders']['ordersid']?>';
			$.ajax({
				url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-process-actions')?>",
				type:'post',
				async: true,
				data:{ordersid:ordersid,_csrf:csrf},
				dataType:'json',
				success:function(json){
					if(json.code=="0000"){
						var options ='';
						var disabled ='';
						$.each(json.data.actions, function(key, val) {
							disabled =val['disabled']==1?"disabled":"";
							options += "<option "+disabled+" value='"+val['id']+"'>"+val['name']+"</option>";
						});
						$("#selectvalue").html(options)
					}
				}
			})
	   	})
		/*$(".jiean").click(function(){
			layer.open({
			skin:'zhi',
			btn:'提交',
			type: 1,
			shadeClose: true,
			title: '填写进度',
			area: ['600px', '260px'],
			content: '<p class="text">选择类型</p><select><option value="0" selected="">留言</option><option value="3">材料复核</option><option value="4">产调</option><option value="5">实地评估</option><option value="1">查封</option><option value="2">清收</option></select><p class="text">进度详情</p><textarea></textarea><li class="upbtn" ><input name="files" value="" data_url="" type="hidden"><a class="addtu" inputName="files" limit = "10" ><img src="/bate2.0/images/skin.png"></a></li>' 		
		   })
		 })
		$(".reply").click(function(){
			layer.open({
			  type: 1,
			  btn:'确定',
			  title: " ",
			  skin: 'massage', 
			  area: ['300px', '240px'],
			  content: '<p class="title">留言回复</p><p>您可以直接留言给接单方进行在线交流</p><textarea  placeholder="请输入你想输入的话"></textarea>'
			})		
		})
		*/
		$(".terminations").click(function(){
			layer.confirm($("#terminations").html(), {
					skin:'zhong',
					btn: ['提交'] ,
					title:'申请终止',
					area: ['580px', 'auto']
				},function(){
						var applymemo = $("#applymemo").val();			
						var files = $("#fileData").val();
						var ordersid = '<?=$data['orders']['ordersid']?>';
						$.ajax({
							url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-termination-apply')?>",
							type:'post',
							data:{ordersid:ordersid,applymemo:applymemo,files:files,_csrf:csrf},
							dataType:'json',
							success:function(json){
								if(json.code=="0000"){
									layer.msg(json.msg,{},function(){location.reload()})
								}else{
									layer.msg(json.msg)
								}
							}
						})
					}
				)
			})

		$(document).on("click",".bun",function(){
			var Comments1 = "<?= isset($data['commentList'])&&$data['myCommentTotal']?true:false ?>";
			layer.confirm($("#evaluations").html(), {
					skin:Comments1?'zhuiping':'ping',
					btn: ['提交'] ,
					type:1,
					title:Comments1?'追加评价':'发布评价',
					area: ['600px', 'auto']
			},function(){
				var truth_score = $("#star1").children('input[name=score]:hidden').val();
				var assort_score = $("#star2").children('input[name=score]:hidden').val();
				var response_score = $("#star3").children('input[name=score]:hidden').val();
				var memo = $("#memo").val();
				var files = $("#filesData").val();
				var ordersid = $('#memo').attr('data-ordersid');
				var commentType = $("#memo").attr('data-type');
				if(commentType == 1){
					var url = "<?php echo yii\helpers\Url::toRoute('/productorders/comment-add')?>";
					var data = {ordersid:ordersid,truth_score:truth_score,assort_score:assort_score,response_score:response_score,memo:memo,files:files,_csrf:csrf};
				}else if(commentType == 2){
					var url = "<?php echo yii\helpers\Url::toRoute('/productorders/comment-additional')?>";
					var data = {ordersid:ordersid,memo:memo,files:files,_csrf:csrf};
				}else{
					var url = "<?php echo yii\helpers\Url::toRoute('/productorders/comment-add')?>";
					var data = {ordersid:ordersid,truth_score:truth_score,assort_score:assort_score,response_score:response_score,memo:memo,files:files,_csrf:csrf};
				}
				var index = layer.load(1,{
					shade:[0.4,'#fff']
				});
				$.ajax({
					url:url,
					type:'post',
					data:data,
					dataType:'json',
					success:function(json){
						if(json.code=="0000"){
							layer.msg(json.msg,{time:2000},function(){location.href=''})
						}else{
							layer.msg(json.msg)
						}
						layer.close(index);
					}
				})
				
			})
			rat('star1','result1',2);
			rat('star2','result2',2);
			rat('star3','result3',2);
		})

		$('.deleteOrder').click(function(){
				var applyid = $(this).attr('data-applyid');
				layer.confirm("确定删除？",{title:false,closeBtn:false},function(){
					var index = layer.load(1, {
						time:2000,
							shade: [0.4,'#fff'] //0.1透明度的白色背景
					});
					$.ajax({
						url:'<?= yii\helpers\Url::toRoute("/productorders/apply-del") ?>',
						type:'post',
						data:{applyid:applyid,_csrf:csrf},
						dataType:'json',
						success:function(json){
							layer.close(index)
							if(json.code == '0000'){
								layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>删除成功",{time:2000},function(){location.href='<?= Url::toRoute('/productorders/index?type=0')?>'});
								layer.close(index);
							}else{
								layer.msg(json.msg);
								layer.close(index);
							}
						}
					})
				})
			})				
				
		$(document).on('click',".Closed",function(){
			layer.confirm($("#price").html(), {
				skin:'jiean',
				type:'1',
				btn: ['提交'] ,
				title:'申请结案',
				area: ['600px', 'auto']
			},function(){
				var price = $("#applymemo").val();	
				var price2 = $("#price2").val();			
				var ordersid = '<?=$data['orders']['ordersid']?>';
				$.ajax({
					url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-closed-apply')?>",
					type:'post',
					data:{ordersid:ordersid,price:price,price2:price2,_csrf:csrf},
					dataType:'json',
					success:function(json){
						if(json.code=="0000"){
							layer.msg(json.msg,{},function(){location.href='<?= yii\helpers\Url::toRoute(['/productorders/detail','applyid'=>Yii::$app->request->get('applyid')])?>';});
						}else{
							layer.msg(json.msg)
						}
					}
				})
			  }
			)
		})
		
		
		$('.cancelbtn').click(function(){
			var applyid = $(this).attr('data-applyid');
			layer.confirm("确定取消？",{title:false,closeBtn:false},function(){
				$.ajax({
				url:'<?= yii\helpers\Url::toRoute('/product/apply-cancel')?>',
				type:'post',
				data:{applyid:applyid,_csrf:csrf},
				dataType:'json',
				success:function(json){
					if(json.code == '0000'){
						layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>取消成功",{time:2000},function(){window.location.reload()});
						layer.close(index);
					}else{
						layer.msg(json.msg);
						layer.close(index);
						}
					}
				})
			})
		})
		
		
		$(document).on("click",".ProductUser",function(){
		var productid = $(this).attr("data-userid");
		var url = "<?=Url::toRoute(["/userinfo/detail","userid"=>""])?>"+productid;
		window.open(url)
	})
		
	})
</script>
<?php if($data["orders"]&&in_array($data["orders"]["status"],['10','20','40'])){?>
<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<input  style='display:none' type="file" name="Filedata" id='id_photos' value="" />
<script>	
$(document).ready(function(){
    $(document).on("click",".closebutton",function(){
                var id = $(this).parents().children('.upbtn').children('input').val();
                var bid = $(this).next().attr('data_bid');
                var temp='';
                var ids =id.split(',');
                for(i in ids){
                	if(ids[i]==bid){
                		continue;
                	}
                	temp+=temp?","+ids[i]:ids[i];
                }
               	$(this).parents().children('.upbtn').children('input').val(temp)
            	$(this).parent().remove();
    });
		
	//照片异步上传
	$(document).on('click',".addtu",function(){
		var limit = $(this).attr('limit')?$(this).attr('limit'):5;
		var inputName = $(this).attr('inputName')?$(this).attr('inputName'):'';
		var DataType = $(this).attr('data-type')?$(this).attr('data-type'):''
		if(!inputName)return false;
		$("#id_photos").attr({"inputName":inputName,"limit":limit,'dataType':DataType}).click();
	})
	$(document).on("change",'#id_photos',function(){ //此处用了change事件，当选择好图片打开，关闭窗口时触发此事件
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});
		var inputName = $(this).attr('inputName');
		var limit = $(this).attr('limit')?$(this).attr('limit'):5;
		var DataType = $(this).attr('dataType');
		var aa = $("input[name='" + inputName + "']").val();
		if(limit&&aa.split(",").length>=limit){
			layer.close(index)
			layer.alert("最多上传"+limit+"张图片");
			return false;
		} 
		if(!inputName)return false;
		$.ajaxFileUpload({
			url:"<?php echo yii\helpers\Url::toRoute(['/site/upload','filetype'=>1,'_csrf'=>Yii::$app->getRequest()->getCsrfToken()])?>",
			type: "POST",
			secureuri: false,
			fileElementId: 'id_photos',
			data: {'_csrf':'<?=Yii::$app->getRequest()->getCsrfToken()?>'},
			textareas:{},
			dataType: "json",
			success: function (data) {
				// console.log(data);
				layer.close(index)
				 var wx = "";
				if(data.error == '0'&&data.fileid){ 
					var aa = $("input[name='" + inputName + "']").val();
					if(limit&&aa.split(",").length>=limit){
						layer.alert("最多上传"+limit+"张图片");
						return false;
					}  
					$("input[name='" + inputName + "']").val((aa ? (aa + ",") : '')+data.fileid).trigger("change");
                    if(DataType){
						var div ='<li><img class="closebutton" src="/bate2.0/images/delete.png" style="position: absolute;z-index: 20;display: block;height: 11px; width: 10px;" /><img class="imgView" data-img='+wx+data.url+' data_bid='+data.fileid+' src='+wx+data.url+' /></li>';
					}else{
						var div ='<li><i class="closebutton"/><img class="imgView" data-img='+wx+data.url+' data_bid='+data.fileid+' src='+wx+data.url+' /></li>';
					}					
					var spandiv = $("input[name='" + inputName + "']").parent("li");
					if(spandiv.index()==0){
						spandiv.parent().prepend(div)
					}else{
						spandiv.prev().after(div);
					}
				}else if(data.msg){
					
					layer.alert(""+data.msg)
				}
			},
			error:function(){
				layer.close(index)
			}
		}); 
	});
});
</script>	
<?php }?>