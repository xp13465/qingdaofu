<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
use yii\helpers\ArrayHelper;


$userid = Yii::$app->user->getId();
$label = "我的发布";
$url = ['release-list',"type"=>"0"];
$this->title = "发布详情";
$this->params['breadcrumbs'][] = ['label' => $label, 'url' =>$url ];
$this->params['breadcrumbs'][] = $this->title;







//接单动作日志 10系统 20进度（留言等），30评论31追评 404142结案申请同意否决 505152中止申请同意否决
 // echo "<pre>";
 // print_r($data);die;
$StatusClass1= 'success';
$StatusClass1Label = '发布成功';
$StatusClass1Time = $data['create_at']?date("Y-m-d H:i:s",$data['create_at']):'';

$StatusClass2= isset($data['productApply'])&&$data['productApply']?($data['productApply']['status']==20?'success':(in_array($data['status'],['20','30','40'])?'success':'waiting')):'waiting';
$StatusClass2Label = '面谈中';
$StatusClass2Time = isset($data['productApply'])&&$data['productApply']?date("Y-m-d H:i:s",$data['productApply']['create_at']):'';


$StatusClass3=isset($data['productApply'])&&$data['productApply']?($data['productApply']['status']==40?($data['status']==30?"fail":(in_array($data['status'],['20','40'])?'success':'wait')):'waiting'):'wait';
$StatusClass3Label=isset($data['productApply'])&&$data['productApply']?($data['productApply']['status']==40?($data['status']==30?"已终止":"处理订单"):'处理订单'):'处理订单';
$StatusClass3Time = in_array($data["status"],["20","30"])?($data['status']==30?date("Y-m-d H:i:s",$data["productApply"]["orders"]['modify_at']):date("Y-m-d H:i:s",$data["productApply"]["orders"]['create_at'])):"";


$StatusClass4=isset($data['productApply'])&&$data['productApply']?($data['productApply']['status']==40?($data['status']==40?"success":(in_array($data['status'],['10','30'])?'wait':'waiting')):'wait'):'wait';		
$StatusClass4Label='结案';
$StatusClass4Time = isset($data["productOrdersClosed"])&&$data["productOrdersClosed"]?date("Y-m-d H:i:s",$data["productOrdersClosed"]['modify_at']):'';

$statusLabelClass = in_array($data["status"],["30"])?"state":"success";
// var_dump($data);
if($data['productApply']['status']=="40"&&$data['checkStatus']=="ORDERSPROCESS"&&($data["productOrdersTerminationsApplyCount"]||$data["productOrdersClosedsApplyCount"])){
	
	$statusLabelClass="appl";
	$StatusClass3="waited";
	$StatusClass4 = "wait";
	if($data["productOrdersTerminationsApplyCount"]){
		$data['statusLabel'] = $StatusClass3Label = "申请终止中";
		$StatusClass3Time =$data["productOrdersTerminationsApply"]['create_at']?date("Y-m-d H:i:s",$data["productOrdersTerminationsApply"]['create_at']):"";
	}
	if($data["productOrdersClosedsApplyCount"]){
		$data['statusLabel'] = $StatusClass3Label =  "申请结案中";
		$StatusClass3Time =$data["productOrdersClosedsApply"]['create_at']?date("Y-m-d H:i:s",$data["productOrdersClosedsApply"]['create_at']):"";
	}
	
}	
$userid = Yii::$app->user->getId();

$detailUrl = Url::toRoute(['/wrelease/details','productid'=>$data['productid']]);
$uagent = $_SERVER['HTTP_USER_AGENT'];
$showtype ="pdf";
if(stripos($uagent,"Android")>-1 ||stripos($uagent,"Linux")>-1 ){
	if(strpos($uagent, 'MicroMessenger')>-1){
		$showtype = 'view';
	}
}
$gohtml = '';
$relaid = '';	
// echo "<pre>";
// print_r($data);
// echo "</pre>";
$statusData = '';
$statusModify = '';
if($data['status']=="20"&&$data['checkStatus']=='ORDERSPROCESS'&&(isset($data['accessTerminationAPPLY'])&&$data['accessTerminationAPPLY']&&$data['productOrdersTerminationsApplyCount']==0&&$data['create_by']==$userid)){
	$statusModify .= '<p class="apply"><a class="terminations" >申请终止</a></p>';
}
if(in_array($data['status'],["0","10"])&&$data['create_by']==$userid){
	$statusModify .= '<p class="delete"><a class="deleteOrder" data-productid='.Yii::$app->request->get('productid').'>删除订单</a></p>';
}
if($data['status']=="10"){
	 $statusData .= '<a href="'.Url::toRoute(['/product/create','productid'=>Yii::$app->request->get('productid')]).'" >编辑</a>';
}



if(isset($data['productOrdersClosed']['closedid'])&&$data['productOrdersClosed']['closedid']){
	$statusData .= Html::a('结清证明', ["productorders/orders-closed-detail","closedid"=>$data['productOrdersClosed']['closedid']],["target"=>"_blank"]);
    $statusModify .='<p class="look too"><a class="bun pingjia">'.(isset($data["commentList"])&&$data["commentList"]["Comments1"]?"追加评价":"评价").'</a></p>';
}else if(isset($data['productOrdersTerminationsApply']['terminationid'])&&$data['productOrdersTerminationsApply']['terminationid']){
	$statusData .= Html::a('查看终止详情', ["productorders/orders-termination-detail","terminationid"=>$data['productOrdersTerminationsApply']['terminationid']],["target"=>"_blank"]);
}

if(isset($data['productOrdersClosedsApply']['closedid'])&&$data['productOrdersClosedsApply']['closedid']){
	$statusData .= Html::a('处理结案', ["productorders/orders-closed-detail","closedid"=>$data['productOrdersClosedsApply']['closedid']],["target"=>"_blank"]);
}
if($data['productApply']['status']=='40'){
	 if($data['productApply']['orders']["status"]=="10"){
		$statusData .='<p class="look"><a class="ordersconfirmDetail" data-productid='.$data['productApply']['orders']['productid'].'>点击查看</a></p>';
	}
}			
?>

<?php if($messageId){echo '<script>window.opener.location.reload(); </script>';}?>
<div class="fabu">
<div class="progress">
    <div class="top"></div>
    <div class="infomation">
		<p class="orders"><?=$data['number']?></p>
		<p class="<?=$statusLabelClass?>"><?=$data['statusLabel']?></p>
		<p class="look"><?= $statusData?:"&nbsp;" ?></p>
	   <?= $statusModify ?>
    </div>
    <div class="jind">
      <ul>
        <li class="<?= $StatusClass1 ?>"> <i>1</i>
          <p class="art"><?= $StatusClass1Label ?></p>
          <p class="arr">从申请记录中选择一个申请方进行面谈，面谈时请准备好以下<a href="#" class="remind">《材料清单》</a><br/><?=$StatusClass1Time?></p>
        </li>
        <li class="<?= $StatusClass2 ?>"> <i>2</i>
          <p class="art"><?= $StatusClass2Label ?></p>
          <p class="arr">约见面谈后确认选择的申请方是否为接单方<br/><?=$StatusClass2Time?></p>
        </li>
        <li class="<?= $StatusClass3?>"> <i>3</i>
          <p class="art"><?= $StatusClass3Label ?></p>
          <p class="arr">等待接单方为您处理订单<br/><?=$StatusClass3Time?></p>
        </li>
        <li class="<?= $StatusClass4 ?>"> <i>4</i>
          <p class="art"><?= $StatusClass4Label ?></p>
          <p class="arr">订单处理完成<br/><?=$StatusClass4Time?></p>
        </li>
      </ul>
    </div>
  </div>
 </div>
<?php if(in_array($data['status'],['20','30','40'])){ ?> 
<div class="chuli">
<div class="progress">
  <div class="infomation">
    <p class="jie">接单方</p>
    <p class="head"><img src="<?= isset($data['productApply'])&&$data['productApply']?$data['productApply']['createuser']['headimg']['file']:'/images/4.png'?>"></p>
    <p class="name">
		<?php if(isset($data['productApply'])&&$data['productApply']){ ?>
			<a href='<?=Url::toRoute(["/userinfo/detail","userid"=>$data['productApply']['createuser']["id"]])?>'><?= $data['productApply']['createuser']['realname']?$data['productApply']['createuser']['realname']:$data['productApply']['createuser']['username'] ?></a>
		<?php } ?>
	</p>
    <p class="phone"><?=$data['productApply']['createuser']['mobile']?></p>
	<?php if($data['status']=="20"){?>
    <p class="but"><a >留言回复</a></p>
	<?php }?>
  </div>
<?php if(isset($data['productApply'])&&$data['productApply']['status']=='40'){ ?>

<div class="xqing">
  <div class="tup">
    <p class="title">处理进度</p>
  </div>
  <div class="clear"></div>
    <ul>
		<?php 
			$logCount = count($data['productApply']['orders']['productOrdersLogs']);
			foreach($data['productApply']['orders']['productOrdersLogs'] as $item => $OrdersLog):
			$relaid .= $OrdersLog["action"]=='41'?$OrdersLog["relaid"]:'';
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
	   <?php endforeach; ?> 
    </ul>
  </div> 
  <div class="clear"></div>
<?php } ?>
 </div>
 <?php } ?>
</div> 
<?php if(isset($data['certificationState'])&&$data['certificationState']=='1'&&$data['productApply']['status']!='20'){ ?>
<div class="shen">
  <p class="title">申请记录（<i><?=$data['applyCount'] ?></i>）</p>
  <table>
  <colgroup>
  <col width="100px" />
  <col width="500px" />
  <col width="200px" />
  <col width="200px" />
	<col />  
  </colgroup>
  <thead>
  <tr>
    <th><p class="head">头像<p></th>
    <th><p>申请人<p></th>
    <th><p>申请时间<p></th>
    <th><p>状态<p></th>
    <th><p>操作<p></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($data['applyList'] as $value): ?>
  <tr  class="<?= isset($value['status'])&&!in_array($value['status'],['10','40'])?'cancel':'' ?>">
    <td style="cursor: pointer;" class='username' data-userid='<?= $value['create_by'] ?>' data-applyid='<?= $value['applyid'] ?>' data-status='<?= $value['status'] ?>'><p class="head"><img src="<?= isset($value['createuser'])&&$value['createuser']['headimg']?$value['createuser']['headimg']['file']:'/images/4.png'?>"></p></td>
    <td style="cursor: pointer;" class='username' data-userid='<?= $value['create_by'] ?>' data-applyid='<?= $value['applyid'] ?>' data-status='<?= $value['status'] ?>'><p><?= isset($value['createuser'])&&$value['createuser']['realname']?$value['createuser']['realname']:$value['createuser']['username']?></p></td>
    <td><p><?= isset($value['create_at'])?date('Y-m-d H:i',$value['create_at']):''?></p></td>
    <td><p class="<?= isset($value['status'])&&in_array($value['status'],['30'])?'fail':'' ?>"><?= isset($value['status'])&&$value['status']=='10'?'等待面谈':($value['status']=='20'?'面谈中':($value['status']=='30'?'面谈失败':($value['status']=='50'?'已取消':$value['status']=='60'?'申请失败':'已接单'))) ?></p></td>
    <td><p class="meet"><a   <?= isset($value['status'])&&$value['status']=='10'?'class="see"':'' ?> data-applyid='<?= $value['applyid'] ?>'><?= $value['status']=='40'?'处理中':($value['status']=="10"?'约TA面谈':'');?></a></p></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>
<?php } ?>
<?php if(isset($data['certificationState'])&&$data['certificationState']!='1'){ ?>
  <div class="shen">
     <p class="title">申请记录（<i><?=$data['applyCount'] ?></i>）</p>
     <p class="prompt">
		您还未认证，不能查看申请记录，请先去认证！
	</p>	   
     <p class="sub"><a href="<?= Url::toRoute('/certifications/authentication/'); ?>" class="bon">现在去认证</a></p>
  </div>
<?php } ?>  
<?php if($data['status']=='10'&&$data['productApply']['status']=='20'&&$data['certificationState']=='1'){ ?>
<div class="results">
     <p class="title">面谈结果（请先线下联系接单方进行面谈，确定TA是否作为您的接单方）</p>
     <div class="head">
       <div class="picture"><img src="<?= isset($data['productApply'])&&$data['productApply']?$data['productApply']['createuser']['headimg']['file']:'/images/4.png'?>"></div>
       <div class="infor">
			<?php if(isset($data['productApply'])&&$data['productApply']){ ?>
				<a target="_blank" href='<?=Url::toRoute(["/userinfo/detail","userid"=>$data['productApply']['createuser']["id"]])?>'>
					<p><?= $data['productApply']['createuser']['realname']?$data['productApply']['createuser']['realname']:$data['productApply']['createuser']['username'] ?></p>
				</a>
			<?php } ?>
         <p><?= $data['productApply']['createuser']['mobile'] ?></p>
       </div>
     </div>
     <div class="mit">
	   <a class='quxiao fail' data-applyid='<?= isset($data['productApply'])&&$data['productApply']?$data['productApply']['applyid']:''?>'>面谈失败，重新选择接单方</a>
	   <a class="jiedan" data-applyid='<?= isset($data['productApply'])&&$data['productApply']?$data['productApply']['applyid']:''?>' data-productid='<?= isset($data['productApply'])&&$data['productApply']?$data['productApply']['productid']:''?>' >同意TA作为接单方</a>
	 </div>
</div>  
<?php } ?> 


  
  
<div class="<?= $data['status']=='10'?'product':'show' ?>">
              
  <div class="<?= $data['status']!=='10'?'product-four':(count(explode(',',$data['category']))=='3'?'product-four':(count(explode(',',$data['category']))=='2'?'product-three':'product-two'))?>">
  <div class="prduct-l">
    <div class="top">
       <p class="title">产品信息</p>
       <?php if($data['status']=='10'){ ?>
			<a href="<?= Url::toRoute(['/product/create','productid'=>Yii::$app->request->get('productid')])?>" class="bun"><i></i>编辑</a>
	   <?php } ?>
    </div>
    <div class="infor">
      <dl>
        <dt>债权类型：</dt>
        <dd><?= $data['categoryLabel']?></dd>
        <dt>委托事项：</dt>
        <dd><?= $data['entrustLabel']?></dd>
        <dt>委托金额：</dt>
        <dd><?= $data['accountLabel']?>万</dd>
        <dt><?= $data["type"]=='1'?'固定费用：':'服务佣金：'?></dt>
        <dd><?= isset($data['typenumLabel'])?$data['typenumLabel'].$data['typeLabel']:''?></dd>
        <dt>违约期限：</dt>
        <dd><?= $data['overdue']?>个月</dd>
        <dt>合同履行地：</dt>
        <dd><?= $data['addressLabel']?></dd>
      </dl>
	  <div class="clear"></div>
    </div>
  </div>

<?php if($data['create_by'] == Yii::$app->user->getId()&&in_array($data['status'],['10'])){ ?>
<?php if(in_array('1',explode(',',$data['category']))){ ?> 

  <div class="prduct-r">
    <div class="top">
       <p class="title">房屋抵押信息</p>
    </div>
    <div class="add"><a   class="address" data-type='1'>添加抵押物信息</a></div>
    <div class="editor">
	<?php if(isset($data['productMortgages1'])&&$data['productMortgages1']){foreach ($data['productMortgages1'] as $key => $value){ ?>
      <div class="editor-l "><p><?= isset($value['addressLabel'])?$key+1 .'、'.$value['addressLabel']:'' ?></p></div>
      <div class="editor-r "><a   data-mortgageid='<?= $value['mortgageid']?>' class='anniu1' >删除</a> |<a   class='rever01 revers01' data-mortgageid='<?= $value['mortgageid']?>' data-category='<?= $value['type']?>' data-relation_1='<?= $value['relation_1'] ?>' data-relation_2='<?= $value['relation_2'] ?>' data-relation_3='<?= $value['relation_3'] ?>' data-relation_desc='<?= $value['relation_desc'] ?>' data-productid='<?= Yii::$app->request->get('productid')?>' typed = '1' data-id='20'>&nbsp;编辑</a></div>
    <?php }} ?>
    </div>
  </div>
<?php } ?>
<?php if(in_array('2',explode(',',$data['category']))){ ?>   
  <div class="prduct-r">
    <div class="top">
       <p class="title">机动车抵押信息</p>
    </div>
    <div class="add"><a   class="addcars" data-type='2'>添加机动车抵押信息</a></div>
    <div class="editor">
	<?php if(isset($data['productMortgages1'])&&$data['productMortgages2']){foreach ($data['productMortgages2'] as $key => $value){ ?>
      <div class="editor-l " ><p><?= isset($value['brandLabel'])?$key+1 .'、'.$value['brandLabel']:''?></p></div>
      <div class="editor-r" ><a   data-mortgageid='<?= $value['mortgageid']?>' class='anniu1'>删除</a> |<a   class='rever01 revers02' data-mortgageid='<?= $value['mortgageid']?>' data-category='<?= $value['type']?>'  data-relation_1='<?= $value['relation_1'] ?>' data-relation_2='<?= $value['relation_2'] ?>' data-relation_3='<?= $value['relation_3'] ?>' data-productid='<?= Yii::$app->request->get('productid')?>' data-type='2' data-id='20'>&nbsp;编辑</a></div>
	<?php }} ?>
    </div>
  </div>
<?php } ?>  
<?php if(in_array('3',explode(',',$data['category']))){ ?>  
  <div class="prduct-r" style="border-right:0px solid #e5e5e5;">
    <div class="top">
       <p class="title">合同纠纷类型</p>
    </div>
    <div class="add"><a   class="addcontracts" data-type='3'>添加合同纠纷类型</a></div>
    <div class="editor">
	<?php if(isset($data['productMortgages1'])&&$data['productMortgages3']){ foreach ($data['productMortgages3'] as $key => $value){ ?>
      <div class="editor-l" ><p><?= isset($value['contractLabel'])?$key+1 .'、'.$value['contractLabel']:''?></p></div>
      <div class="editor-r"><a   data-mortgageid='<?= $value['mortgageid']?>' class='anniu1' >删除</a> |<a   class='rever01 revers03' data-mortgageid='<?= $value['mortgageid']?>' data-category='<?= $value['type']?>'  data-relation_1='<?= $value['relation_1'] ?>' data-productid='<?= Yii::$app->request->get('productid')?>' data-type='3' data-id='20'>&nbsp;编辑</a></div>
	<?php }} ?>
    </div>
  </div>
<?php } ?>  
<?php } ?> 

<?php if($data['create_by'] == Yii::$app->user->getId()&&$data['status']!=='10'){ ?>
<div class="prduct-r">
<?php if(in_array('1',explode(',',$data['category']))){ ?>
    <div class="top">
       <p class="title">房屋抵押信息</p>
    </div>
    <div class="editor">
	  <?php if(isset($data['productMortgages1'])&&$data['productMortgages1']){foreach ($data['productMortgages1'] as $key => $value){ ?>
			<div class="editor-l"><p><?= isset($value['addressLabel'])?$key+1 .'、'.$value['addressLabel']:'' ?></p></div>
	 <?php }}else{echo'暂无';} ?>
    </div>
<?php } ?>
<?php if(in_array('2',explode(',',$data['category']))){ ?>
        <div class="top">
       <p class="title">机动车抵押信息</p>
    </div>
    <div class="editor">
       <?php if(isset($data['productMortgages2'])&&$data['productMortgages2']){foreach ($data['productMortgages2'] as $key => $value){ ?>
			<div class="editor-l"><p><?= isset($value['brandLabel'])?$key+1 .'、'.$value['brandLabel']:'' ?></p></div>
	 <?php }}else{echo'暂无';} ?>
    </div>
<?php } ?>
<?php if(in_array('3',explode(',',$data['category']))){ ?>
    <div class="top">
       <p class="title">合同纠纷类型</p>
    </div>
    <div class="editor">
       <?php if(isset($data['productMortgages3'])&&$data['productMortgages3']){foreach ($data['productMortgages3'] as $key => $value){ ?>
			<div class="editor-l"><p><?= isset($value['contractLabel'])?$key+1 .'、'.$value['contractLabel']:'' ?></p></div>
		<?php }}else{echo'暂无';} ?>
    </div>
<?php } ?>
  </div>
  
  
  <div class="prduct-r">
    <div class="top">
       <p class="title">签约图片</p>
    </div>
    <ul>
	   <?php foreach($data["pacts"] as $pact){ ?>
			<li><img class='imgView' src="<?= isset($pact['file'])?$pact['file']:'/images/weixin.png'?>"></li> 
	   <?php } ?>	  
     </ul>
  </div>
  
  
  <div class="prduct-r" style="border-right:0px solid #e5e5e5;">
    <div class="top">
       <p class="title">居间协议</p>
    </div>
    <div class="butn"><a   class='ordersconfirmDetail' data-productid='<?= $data['productid']?>'>点击查看</a></div>
  </div>
<?php } ?>
  <div class="clear"></div>
 </div>
</div>

<?php if($data['status'] =='40'){ ?>
<div class="evaluation">
    <div class="top">
       <p class="title">评价详情</p>
       <a href="javascript:void(0);" class="bun pingjia"><i></i><?= isset($data['commentList'])&&$data['commentList']['Comments1']?'追加评价':'评价' ?></a>
    </div>
    <ul class="list">
	<?php foreach($data['commentList']['Comments1'] as $key => $value): ?>
        <li>
			<div class="star">
			
			  <ul>
				<?= \frontend\services\Func::evaluateNumber(round((($value['truth_score']+$value['assort_score']+$value['response_score'])/3)),$pc=true)?> 
			  </ul>
			  <p><?= date('Y年m月d日 H:i',$value['action_at'])?></p>
			</div>
			<div class="comments">
				<p class="text"><?= $value['memo']?></p>
				<?php if(isset($value['filesImg'])&&$value['filesImg']){ ?>
				<?php foreach($value['filesImg'] as $v):?>
				<p class="pic">
					<img class='imgView' src="<?= $v['file'] ?>">
				</p>
				<?php endforeach; ?>
				<?php } ?>
			</div>
			<div class="portrait">
				<p class="head"><img src="<?= isset($value["userinfo"]["headimg"]['file'])?$value["userinfo"]["headimg"]['file']:'/images/4.png' ?>"></p>
				<p class="name"><?= isset($value["userinfo"]["realname"])?$value["userinfo"]["realname"]:$value["userinfo"]["username"] ?></p>
			</div>
			<div class="clear"></div>
			
		</li>
		<?php endforeach; ?>
		<?php if($data['commentList']['Comments2']){?>
		<?php foreach($data['commentList']['Comments2'] as $key => $value): ?>
			<li>
				<div class="star">
				<p class="zhui">【追加评论】</p>
				<p><?= date('Y年m月d日 H:i',$value['action_at'])?></p>
				</div>
				<div class="comments">
				<p class="text"><?= $value['memo']?></p>
					<?php if(isset($value['filesImg'])&&$value['filesImg']){ ?>
					<?php foreach($value['filesImg'] as $v):?>
					<p class="pic">
							<img class='imgView' src="<?= $v['file'] ?>">
					</p>
					<?php endforeach; ?>
					<?php } ?>
				</div>
				<div class="portrait">
					<p class="head"><img src="<?= isset($value["userinfo"]["headimg"]['file'])?$value["userinfo"]["headimg"]['file']:'/images/4.png' ?>"></p>
					<p class="name"><?= isset($value["userinfo"]["realname"])?$value["userinfo"]["realname"]:$value["userinfo"]["username"] ?></p>
				</div>
				<div class="clear"></div>
			</li>
		<?php endforeach; ?>
		<?php } ?>
    </ul>
  </div>
<?php } ?>

<script id='linkage' type="text/template">
      <div class="list_con1">
        <ul class="area">
          <li> 
		  <span style="width:80px;color:#333;float:left;">
		    所在地址:
		  </span> 
			<?= Html::dropDownList(
				'province_id','310000',
				ArrayHelper::map($data['Province'],'provinceID','province'),
				['id'=>'province','class'=>'selects selects_three','placeholder'=>"请选择",'data-required'=>"true"]
			)?>
			<?= Html::dropDownList(
				'city_id','',
				[""=>"请选择..."],
				['id'=>'city','class'=>'selects selects_three','placeholder'=>"请选择",'data-required'=>"true"]
			)?>
			<?= Html::dropDownList(
				'district_id','',
				[""=>"请选择..."],
				['id'=>'district','class'=>'selects selects_three','placeholder'=>"请选择",'data-required'=>"true"]
			)?>
			</li>
			<li> 
				<span style="width:80px;color:#333;margin:25px 0px 0px 0px;float:left;">详细地址:</span>
				<?= Html::input('text','relation_desc','',['id'=>'seatmortgage','placeholder'=>'详细地址']);?>
			</li>
        </ul>
      </div>
 </script>
 <script id='mycar' type="text/template">
      <div class="list_con1" <?= isset($data['commentList'])&&$data['commentList']['Comments1']?"style='display:none'":""?>>
        <ul class="area">
          <li> <span style="color:#333;margin:0px 20px 0px 20px;float:left;">汽车品牌:</span>
				<?= Html::dropDownList(
					'brand','',
					ArrayHelper::map($data['Brand'],'id','name'),
					['id'=>'brand','class'=>'selects selects_three','placeholder'=>"请选择",'data-required'=>"true"]
				)?>
				<?= Html::dropDownList(
					'audi','',
					[""=>"请选择..."],
					['id'=>'audi','class'=>'selects selects_three','placeholder'=>"请选择",'data-required'=>"true"]
				)?>
				<?= Html::dropDownList(
					'licenseplate','',
					[""=>"请选择...",'1'=>'沪牌','2'=>'非沪牌'],
					['id'=>'licenseplate','class'=>'selects selects_three','placeholder'=>"请选择",'data-required'=>"true"]
				)?>
		  </li>			
        </ul>
      </div>
 </script>
 <script id='mycontract' type="text/template">
      <div class="list_con1">
        <ul class="area">
			<li> <span style="color:#333;margin:2px 20px 0px 20px;float:left;">选择类型:</span>
				<?= Html::dropDownList(
					'contract','',
					[""=>"请选择...",'1'=>'合同纠纷','2'=>'民事诉讼','3'=>'房产纠纷','4'=>'劳动合同','5'=>'其他'],
					['id'=>'contract','class'=>'selects selects_three','placeholder'=>"请选择",'data-required'=>"true"]
				)?>
			</li>			
        </ul>
      </div>
 </script>

 <script id='terminations' type="text/template">
      <div class="list_con1">
        <ul class="area">
			<span style="color:#333;width:80px;float:left;">终止原因:
			</span><code> 
			<textarea type="text" name="applymemo"  id="applymemo" placeholder="请输入拒绝终止的原因，不少于5个字" style="width:448px;height:90px;padding:5px;"></textarea>
			</code></li>
        </ul>
      </div>
	  <div class="tu">
		<ul>
		<a class="addtu" inputName='files' limit = '5' data-type='1' >
		  <li class="upbtn">
		  <?php echo Html::hiddenInput("files",'',['data_url'=>'','id'=>'fileData']);?>
		  <p>点击上传图片</p>
		  </li>
		  </a>
		</ul>
	  </div>
 </script>
 <script id='evaluations' type="text/template">
	  <div class="pj_star" <?= isset($data['commentList'])&&$data['commentList']['Comments1']?"style='display:none'":""?>>
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
			<textarea type="text" name="memo" data-ordersid="<?= $data['productApply']['orders']['ordersid']?>" data-type='<?= isset($data['commentList'])&&$data['commentList']['Comments1']?"2'":"1"?>' id="memo" placeholder="请输入评价内容，不少于5个字" style="width:467px;height:100px;margin-top:20px;"></textarea>
			</code></li>
        </ul>
      </div>
	  <div class="tu" style="margin-left:97px;">
		<ul>
		<a class="addtu" inputName='files' limit = '10' data-type='1' >
		  <li class="upbtn">
		  <?php echo Html::hiddenInput("files",'',['data_url'=>'','id'=>'filesData']);?>
		  <p>点击上传图片</p>
		  </li>
		  </a>
		</ul>
	  </div>
 </script>
<script src="/bate2.0/js/jquery.raty.js" type="text/javascript"></script>
<script type="text/javascript">
// rat('star1','result1',2);
// rat('star2','result2',2);
// rat('star3','result3',2);
function rat(star,result,m){

	star= '#' + star;
	result= '#' + result;
	$(result).hide();

	$(star).raty({
		hints: ['10','20', '30', '40', '50'],
		path: "/bate2.0/images",
		starOff: 'star-off-big.png',
		starOn: 'star-on-big.png',
		size: 25,
		start: 10,
		showHalf: true,
		target: result,
		targetKeep : true,
		click: function (score, evt) {
			//alert('你的评分是'+score*m+'分');
		}
	});

}
</script>
<?php if($data['validflag']=='0'){?>
<body></body>
<script>
$(document).ready(function(){
	layer.msg("该订单已删除",{time:3000,shade: [0.2,'#30393d']},function(){
         window.opener=null;
		 window.close();
	})
})
</script>
<?php 
// return false;
} ?>
 
 
 
<script>
$('.xqing ul').perfectScrollbar();
$(document).ready(function(){
	var relation_1 = "310000";
	var relation_2 = "";
	var relation_3 = "";
	var productid = '<?= $data['productid']?>';
	var _csrf = "<?= Yii::$app->request->csrfToken ?>";
	
	$(document).on("change",'#province',function(){
		var province_id = $(this).val();
		$.ajax({
			url:'<?= Url::toRoute('/product/city');?>',
			type:'post',
			data:{province_id:province_id,_csrf:_csrf},
			dataType:'json',
			success:function(json){
				var html='<option value="">请选择...</option>';
				if(json['code'] == '0000'){
					$('#city').html(html+json['data']['html']);
					if(relation_2){
						if($('#city').find('option[value="'+relation_2+'"]').size()){
							$('#city').val(relation_2)
						}
					}
					$('#city').trigger("change");
				}
			}
		})
	}).trigger("change");
	
	$(document).on("change",'#city',function(){
		var city_id = $(this).val();
		$.ajax({
			url:'<?= Yii\helpers\Url::toRoute('/product/district'); ?>',
			type:'post',
			data:{city_id:city_id,_csrf:_csrf},
			dataType:'json',
			success:function(json){
				var html='<option value="">请选择...</option>';
				if(json['code'] == '0000'){
					$('#district').html(html+json['data']['html']);
					if(relation_3&&$('#district').find('option[value="'+relation_3+'"]').size()){
						$('#district').val(relation_3)
					}
				}
			}
		})
	}).trigger("change");
	
	$(document).on('change','#brand',function(){
		var brand_id = $(this).val();
		$.ajax({
			url:'<?= Yii\helpers\Url::toRoute('/product/audis'); ?>',
			type:'post',
			data:{brand_id:brand_id,_csrf:_csrf},
			dataType:'json',
			success:function(json){
				var html='<option value="" >请选择...</option>';
				if(json['code'] == '0000'){
					$('#audi').html(html+json['data']['html']);
					if(relation_2){
						if($('#audi').find('option[value="'+relation_2+'"]').size()){
							$('#audi').val(relation_2)
						}
						$('#audi').trigger("change")
					}
				}
			}
		})
	}).trigger("change");
	var diyawuData = '';
	var diyawuEdit  = function(obj){
		relation_1 = $(obj).attr('data-relation_1');
		relation_2 = $(obj).attr('data-relation_2');
		relation_3 = $(obj).attr('data-relation_3');
		var category = $(obj).attr('data-category'); 
		var mortgageid = $(obj).attr('data-mortgageid');
		var productid = $(obj).attr('data-productid');
		var dataType = $(obj).attr('data-type');
		$('.anniu1').show().attr('data-mortgageid',mortgageid);
		diyawuData ={};
		diyawuData.id ='20';
		diyawuData.mortgageid =mortgageid;
		diyawuData.productid =productid;
		diyawuData.type =dataType;
		// console.log(diyawuData)
		// $('.layui-layer-btn0').attr('data-mortgageid',mortgageid);
		// $('.layui-layer-btn0').attr('data-productid',productid);
		// $('.layui-layer-btn0').attr('data-type',dataType);
		switch(category){
			case '1':
			 var relation_desc = $(obj).attr('data-relation_desc');
			 $('select[name="province_id"]').val(relation_1).trigger("change");
			 $('#seatmortgage').attr('value',relation_desc);
			break;
			case '2':
			$('select[name="brand"]').val(relation_1).trigger("change");
			$('select[name="licenseplate"]').val(relation_3);
			break;
			case '3':
			$('select[name="contract"]').val(relation_1);			
			break;
		}
		
	}
	
	var diyawuFormSubmit = function(productid,category){
		
		var del = diyawuData?true:false;
		var category = category?category:'';
		var mortgageid = diyawuData?diyawuData.mortgageid:'';;
		switch(category){
			case '1':
				var relation_1 = $('select[name="province_id"]').val();
				var relation_2 = $('select[name="city_id"]').val();
				var relation_3 = $('select[name="district_id"]').val();
				var relation_desc = $('input[name="relation_desc"]').val();
			break;
			case '2':
				var relation_1 = $('select[name="brand"]').val();
				var relation_2 = $('select[name="audi"]').val();
				var relation_3 = $('select[name="licenseplate"]').val();
			break;
			case '3':
				var relation_1 = $('select[name="contract"]').val();
			break;
		}
		
		if(del){
		   
            switch(category){
				case '1':
					var param = {mortgageid:mortgageid,relation_1:relation_1,relation_2:relation_2,relation_3:relation_3,relation_desc:relation_desc,type:category,productid:productid,_csrf:_csrf};	
				break;
				case '2':
					var param = {mortgageid:mortgageid,relation_1:relation_1,relation_2:relation_2,relation_3:relation_3,type:category,productid:productid,_csrf:_csrf};	
				break;
				case '3':
					var param = {mortgageid:mortgageid,relation_1:relation_1,type:category,productid:productid,_csrf:_csrf};	
				break;
			}
            var url = '<?= yii\helpers\Url::toRoute('/product/mortgage-edit')?>';		
			
		}else{
			switch(category){
				case '1':
					var param = {relation_1:relation_1,relation_2:relation_2,relation_3:relation_3,relation_desc:relation_desc,type:category,productid:productid,_csrf:_csrf};	
				break;
				case '2':
					var param = {relation_1:relation_1,relation_2:relation_2,relation_3:relation_3,type:category,productid:productid,_csrf:_csrf};	
				break;
				case '3':
					var param = {relation_1:relation_1,type:category,productid:productid,_csrf:_csrf};	
				break;
			}
			var url = '<?= yii\helpers\Url::toRoute('/product/mortgage-add')?>';
		}
		//alert(param);die;
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});	
		$.ajax({
				url:url,
				type:'post',
				data:param,
				dataType:'json',
				success:function(json){
					if(json.code == '0000'){
						if(del){
							layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>修改成功！",{time:2000},function(){window.location.reload()});
							layer.close(index);							
						}else{
							layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>添加成功！",{time:2000},function(){window.location.reload()});
							layer.close(index);							
						}
					}else{
						
						layer.msg(json.msg);
						layer.close(index);
					}
					
				}
			})
  }
  
  
  $(".see").click(function(){
		 var applyid = $(this).attr('data-applyid');
		 var state = "<?= $data['certificationState']?>";
		 if(state !== '1'){
			 layer.confirm("您还未认证，请先去认证",{title:false,closeBtn:false,btn:['去认证','取消']},function(){location.href="<?= Url::toRoute('/certifications/authentication')?>"});
			 return false;
		 }
		 var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});
		 $.ajax({
			 url:'<?= yii\helpers\Url::toRoute('/product/apply-chat') ?>',
			 type:'post',
			 data:{applyid:applyid,_csrf:_csrf},
			 dataType:'json',
			 success:function(json){
				 if(json.code == '0000'){
					 layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>发起面谈成功",{time:2000},function(){window.location.reload()});
					 layer.close(index);
				 }else{
					 layer.msg(json.msg);
					 layer.close(index);
				 }
			 }
		 })
	 })
  
  
  $(document).on('click','.anniu1',function(){
		var mortgageid = $(this).attr('data-mortgageid');
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});
        $.ajax({
			url:'<?= yii\helpers\Url::toRoute('/product/mortgage-del')?>',
			type:'post',
			data:{mortgageid:mortgageid,_csrf:_csrf},
			dataType:'json',
			success:function(json){
				if(json.code == '0000'){
					layer.close(index);
					layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>删除成功！",{time:2000},function(){window.location.reload()});
				}else{
					layer.close(index);
					layer.msg(json.msg);
				}
			}
		})
  })
	 
	   var InformationFormSubmit = function(news){
		  			var news= news?news:'ture';
					var ordersid = '<?= $data['productApply']['orders']['ordersid']?>';
                     if(news){
						  var memo = $('textarea[name=memo]').val();
						  var param = {ordersid:ordersid,memo:memo,type:0,_csrf:_csrf};
						  var url = '<?= yii\helpers\Url::toRoute('/productorders/orders-process-add')?>';
					 }else{
						 var applymemo = $("#applymemo").val();			
						 var files = $("input[name='files']").val();
						 var ordersid = ordersid;
						 var param = {ordersid:ordersid,applymemo:applymemo,files:files,_csrf:_csrf};
						 var url = '<?= yii\helpers\Url::toRoute('/productorders/orders-termination-apply')?>';
					 }				 
					 var index = layer.load(1,{
						 shade:[0.4,'#fff']
					 });
					 $.ajax({
						 url:url,
						 type:'post',
						 data:param,
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
			 }
	
	
	 
	$(".remind").click(function(){
			layer.open({
			  type: 1,
			  btn:'我知道了',
			  title: '<p><img src="/bate2.0/images/title1.png"></p>',
			  skin: 'data', 
			  area: ['430px', '260px'],
			  content: '<div class="tit"><p class="tit1">材料清单 </p><p class="tit2"> 约见面谈时需准备以下材料</p></div> <p style="min-width:100px;height:26px;">身份证</p><p style="min-width:100px;height:26px;">户口本</p><p style="min-width:100px;height:26px;">银行凭证</p><p style="min-width:100px;height:26px;">公证书</p><p style="min-width:100px;height:26px;">借款合同</p><p style="min-width:100px;height:26px;">他项权证</p><p style="min-width:100px;height:26px;">案件受理通知书(若立案)</p>'
			})		
		})
		$(".revers01,.address").click(function(){
			layer.confirm($("#linkage").html(), {
				skin:'houses',
				type:1,
				move:false,
				btn: ['保存'] ,
				area: ['600px', 'auto'],
			},function(){
				diyawuFormSubmit(productid,'1')
			}
			)
			diyawuData = '';
			if($(this).hasClass("rever01")){
				diyawuEdit(this)
			}else{
				 $('select[name="province_id"]').trigger("change");
			}
	   	})
		$(".revers02,.addcars").click(function(){
			layer.confirm($("#mycar").html(), {
				skin:'cars',
				type:1,
				btn: ['保存'] ,
				area: ['600px', 'auto'],
			},function(){
				diyawuFormSubmit(productid,'2');
			})
			diyawuData = '';
			if($(this).hasClass("rever01")){
				diyawuEdit(this)
			}else{
				$('select[name="brand"]').trigger("change");
			}
	   	})
		$(".revers03,.addcontracts").click(function(){
			layer.confirm($("#mycontract").html(), {
				skin:'contracts',
				type:1,
				btn: ['保存'] ,
				area: ['300px', 'auto'],
			},function(){
				diyawuFormSubmit(productid,'3');
			}
			)
			diyawuData = '';
			if($(this).hasClass("rever01")){
				diyawuEdit(this)
			}
	   	})
		$(".terminations").click(function(){
			layer.confirm($("#terminations").html(), {
					skin:'tian',
					type:1,
					btn: ['提交'] ,
					title:'申请终止',
					area: ['580px', 'auto']
				},function(){
						var applymemo = $("#applymemo").val();			
						var files = $("#fileData").val();
						var ordersid = '<?=$data['productApply']['orders']['ordersid']?>';
						$.ajax({
							url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-termination-apply')?>",
							type:'post',
							data:{ordersid:ordersid,applymemo:applymemo,files:files,_csrf:_csrf},
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

		$(document).on("click",".pingjia",function(){
			var Comments1 = "<?= isset($data['commentList'])&&$data['commentList']['Comments1']?true:false ?>";
			layer.confirm($("#evaluations").html(), {
					skin:Comments1?'zhuiping':'ping',
					type:1,
					btn: ['提交'] ,
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
					var data = {ordersid:ordersid,truth_score:truth_score,assort_score:assort_score,response_score:response_score,memo:memo,files:files,_csrf:_csrf};
				}else if(commentType == 2){
					var url = "<?php echo yii\helpers\Url::toRoute('/productorders/comment-additional')?>";
					var data = {ordersid:ordersid,memo:memo,files:files,_csrf:_csrf};
				}else{
					var url = "<?php echo yii\helpers\Url::toRoute('/productorders/comment-add')?>";
					var data = {ordersid:ordersid,truth_score:truth_score,assort_score:assort_score,response_score:response_score,memo:memo,files:files,_csrf:_csrf};
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
		
		
		
		
		$(".but").click(function(){
			layer.open({
			  type: 1,
			  btn:['确定'],
			  title: '<p><img src="/bate2.0/images/title.png"></p>',
			  skin: 'massage', 
			  area: ['450px', '290px'],
			  content: '<p class="title">留言回复</p><p>您可以直接留言给接单方进行在线交流</p><textarea name="memo" placeholder="请输入留言内容..."></textarea>',
			  yes:function(){
				InformationFormSubmit();
			  }
			})		
		});
		
	$('.jiedan').click(function(){
		 var applyid = $(this).attr('data-applyid');
		 var productid = $(this).attr('data-productid');
		 var index = layer.load(1, {
			   time:2000,
				shade: [0.4,'#fff'] //0.1透明度的白色背景
			});
			$.ajax({
				url:'<?= Url::toRoute('/product/agreement')?>'+'?productid='+productid,
				type:'post',
				data:{_csrf:_csrf},
				dataType:'html',
				success:function(html){
					layer.close(index)
					var confirmindex = layer.confirm("",{
						  title: '确认协议',
						  content: html,
						  type: 0,
						  closeBtn: false,
						  area: ["800px","90%"],
						  btn: ['确认','取消'],
						  formType:0 //prompt风格，支持0-2
						}, function(){
							index = layer.load(1, {
							   time:2000,
								shade: [0.4,'#fff'] //0.1透明度的白色背景
							});
							$.ajax({
								url:'<?= yii\helpers\Url::toRoute('/product/apply-agree') ?>',
								type:'post',
								data:{applyid:applyid,_csrf:_csrf},
								dataType:'json',
								success:function(json){
									layer.close(confirmindex)
									if(json.code == '0000'){
										layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>成功",{time:2000},function(){window.location.reload()});
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
					$(".layui-layer-content").animate({'height':'+=5px'},'fast')
				}
			})
	 })
	 
	 
	 $(".ordersconfirmDetail").click(function(){
			var productid = $(this).attr('data-productid');
				var url = '<?= Yii\helpers\Url::toRoute(['/product/agreement',"type"=>"pdf"])?>'+'&productid='+productid;
				window.open(url)
		})
	 
	 $('.quxiao').click(function(){
		 var applyid = $(this).attr('data-applyid');
		 var index = layer.load(1,{
			 shade:[0.4,'#fff']
		 });
		 $.ajax({
			 url:'<?= yii\helpers\Url::toRoute('/product/apply-veto') ?>',
			 type:'post',
			 data:{applyid:applyid,_csrf:_csrf},
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
	 
	 $(document).on('click','.username',function(){
		
			var applyid = $(this).attr('data-applyid');
			var userid = $(this).attr('data-userid');
			var applyStatus = $(this).attr('data-status');
			var productid = "<?= $data['productid']?>";
			var url = '<?= Url::toRoute(['/userinfo/detail','userid'=>""])?>'+userid+'&applyid='+applyid+'&applyStatus='+applyStatus+'&productid='+productid;
			window.open(url);
	})
	 
	 $('.deleteOrder').click(function(){
		 var productid = $(this).attr('data-productid');
		 var index = layer.load(1,{
			 shade:[0.4,'#fff']
		 });
		 layer.confirm('是否删除该产品?',{
			 title:false,closeBtn:false,
			 btn:['确定','取消']
		 },function(){
			$.ajax({
				url:'<?= yii\helpers\Url::toRoute('/product/product-delete')?>',
				type:'post',
				data:{productid:productid,_csrf:_csrf},
				dataType:'json',
				success:function(json){
					if(json.code == '0000'){
						layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>删除成功",{time:2000},function(){window.location.href="/product/release-list?type=0"});
						layer.close(index);
					}else{
						layer.msg(json.msg);
						layer.close(index);
					}
				}
			})
		 })
		 layer.close(index);
	 })
	 
	 $(document).on("click",".ProductUser",function(){
		var productid = $(this).attr("data-userid");
		var url = "<?=Url::toRoute(["/userinfo/detail","userid"=>""])?>"+productid;
		window.open(url)
	})
	 
	
	})
</script>




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
			layer.msg("最多上传"+limit+"张图片");
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
						layer.msg("最多上传"+limit+"张图片");
						return false;
					}  
					$("input[name='" + inputName + "']").val((aa ? (aa + ",") : '')+data.fileid).trigger("change");
                    if(DataType){
						var div ='<li><img class="closebutton" src="/bate2.0/images/delete.png" style="position: absolute;z-index: 20;display: block;height: 11px; width: 10px;" /><img class="imageWxview" data-img='+wx+data.url+' data_bid='+data.fileid+' src='+wx+data.url+' /></li>';
					}else{
						var div ='<li><i class="closebutton"/><img class="imageWxview" data-img='+wx+data.url+' data_bid='+data.fileid+' src='+wx+data.url+' /></li>';
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