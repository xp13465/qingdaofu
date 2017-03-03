<?php

use yii\helpers\Html; 
use yii\helpers\Url;
use \app\models\Product;
use yii\widgets\DetailView; 

/* @var $this yii\web\View */ 
/* @var $model app\models\ProductApply */
$this->title = '接单详情#'.$model['applyid'] ;
$this->params['breadcrumbs'][] = ['label' => '接单列表', 'url' => ['orders']]; 
$this->params['breadcrumbs'][] = $this->title; 
$status = ['0'=>'草稿','10'=>'发布中','20'=>'处理中','30'=>'已终止','40'=>'已结案'];
// echo '<pre>';
// print_r($model);die;
$gohtml = '';
$relaid = '';	
$categoryLabel = "未认证";
$nameLabel = "";
$cardnoLabel = "";
$contactLabel = "";
$mobileLabel = "";
$emailLabel = "";
$casedescLabel ="";
$addressLabel = "";
$enterprisewebsiteLabel = "";
if(isset($model['certification'])&&$model['certification']['state']==1){
	switch($model['certification']['category']){
		case 1:
			$categoryLabel='已认证个人';
			$nameLabel = '姓名';
			$cardnoLabel='身份证号码';
			$mobileLabel = '联系方式';
			$emailLabel = '邮箱';
			break;
		case 2:
			$categoryLabel='已认证律所';
			$nameLabel = '律所名称';
			$cardnoLabel='执业证号';
			$contactLabel = '联系人';
			$mobileLabel = '联系方式';
			$emailLabel = '邮箱';
			$casedescLabel = '经典案例';
			break;
		case 3:
			$categoryLabel='已认证公司';
			$nameLabel = '企业名称';
			$cardnoLabel='营业执照号';
			$contactLabel = '联系人';
			$mobileLabel = '联系方式';
			$emailLabel = '企业邮箱';
			$addressLabel = '企业地址';
			$enterprisewebsiteLabel = '公司网站';
			$casedescLabel = '经典案例';
			break;
	}
}

$widget_tmp = [
			['attribute'=>'',
			'label' => $nameLabel,
			'value'=> isset($model['certification']['name'])?$model['certification']['name']:'',
			],
			['attribute'=>'',
			'label' => $cardnoLabel,
			'value'=> isset($model['certification']['cardno'])?$model['certification']['cardno']:'',
			],
			['attribute'=>'',
			'label' => $mobileLabel,
			'value'=> isset($model['certification']['mobile'])?$model['certification']['mobile']:'',
			],
			['attribute'=>'',
			'label' =>$emailLabel,
			'value'=> isset($model['certification']['email'])?$model['certification']['email']:'',
			],
	];
	 if(isset($model['certification'])&&in_array($model['certification']['category'],['3'])){
			$enterprisewebsi =['attribute'=>'',
								'label' => $enterprisewebsiteLabel,
								'value'=> isset($model['certification']['enterprisewebsite'])?$model['certification']['enterprisewebsite']:'',
								];
			$address = ['attribute'=>'',
							'label' => $addressLabel,
							'value'=> isset($model['certification']['address'])?$model['certification']['address']:'',
					  ];
					
			array_push($widget_tmp,$enterprisewebsi,$address);
	}
	if(isset($model['certification'])&&in_array($model['certification']['category'],['2','3'])){
			$contac = ['attribute'=>'',
							'label' => $contactLabel,
							'value'=> isset($model['certification']['cardno'])?$model['certification']['cardno']:'',
					  ];
			$casedesc = ['attribute'=>'',
							'label' => $casedescLabel,
							'value'=> isset($model['certification']['cardno'])?$model['certification']['cardno']:'',
					  ];
		array_push($widget_tmp,$contac,$casedesc);
	  }
	 
?>

<div class="product-apply-view"> 
    <?= DetailView::widget([ 
        'model' => $model ,
        'attributes' => [ 
            ['attribute'=>'applyid',
			 'label'=>'申请ID',
			],
            ['attribute'=>'status',
			 'label'=>'状态',
			 'value'=>isset($model['product'])&&$model['product']['status']=='30'?'已终止':(isset($model['product'])&&$model['product']['status']=='40'?'已结案':\app\models\ProductApply::$ApplyStatus[$model['status']]),
			],
            ['attribute'=>'validflag',
			 'label'=>'回收状态',
			'value'=> $model['validflag'] == '1'?'未回收':'已回收',
			],
            ['attribute'=>'create_at',
			'label'=>'创建时间',
			'value'=> isset($model['create_at'])?date('Y-m-d H:i:s',$model['create_at']):'',
			],
            ['attribute'=>'create_by',
			 'label'=>'申请人',
			 'value'=>isset($model['create_by'])?(isset($model['certification']['name'])&&$model['certification']['name']?$model['certification']['name']:(isset($model['user']['realname'])&&$model['user']['realname']?$model['user']['realname']:$model['user']['username'])):'',
			],
			['attribute'=>'modify_at',
			'label'=>'修改时间',
			'value'=> isset($model['modify_at'])?date('Y-m-d H:i:s',$model['modify_at']):'',
			],
			['attribute'=>'modify_by',
			'label'=>'修改人',
			 'value'=>isset($model['modify_by'])?(isset($model['certification']['name'])&&$model['certification']['name']?$model['certification']['name']:(isset($model['user']['realname'])&&$model['user']['realname']?$model['user']['realname']:$model['user']['username'])):'',
			],
			['attribute'=>'productid',
			 'label'=>'产品详情查询',
			 'value'=>(Html::a('查看',"/products/{$model['productid']}",['target'=>'_blank']))."&nbsp;&nbsp;&nbsp;&nbsp;".(isset($model['productOrders'])&&$model['productOrders']['productOrdersOperators']?Html::a('经办人','javascript:void(0);',['class'=>'jingbanr']):'')."&nbsp;&nbsp;&nbsp;&nbsp;".
					  (isset($model['productOrders'])&&$model['productOrders']['productOrdersLogs']?Html::a('产品进度','javascript:void(0);',['class'=>'jindu']):'')."&nbsp;&nbsp;&nbsp;&nbsp;".
					  (isset($model['productMortgages1'])&&$model['productMortgages1']||isset($model['productMortgages2'])&&$model['productMortgages2']||isset($model['productMortgages3'])&&$model['productMortgages3']?Html::a('抵押物','javascript:void(0);',['class'=>'productMortgages']):'')."&nbsp;&nbsp;&nbsp;&nbsp;".
					  (isset($model["pacts"])&&$model["pacts"]?Html::a('签约图片','javascript:void(0);',['class'=>'pacts']):'')."&nbsp;&nbsp;&nbsp;&nbsp;".
					  (isset($model['productOrders'])&&$model['productOrders']['status']!=='0'?Html::a('协议','agreement?type=pdf&productid='.$model['productid'],['class'=>'xieyi','target'=>'_blank']):''),
			 'format' => 'raw', 
			],
        ], 
    ]) ?> 

</div> 

<div class="product-view">
    <p>
	<h3>接单方用户认证信息（<?php echo isset($model['certification'])&&$model['certification']['state']=='0'?'已发出申请等待审核':(isset($model['certification'])&&$model['certification']['state']=='1'?'已认证':'未认证') ?>）</h3>
    </p>
<?php if(isset($model['certification'])&&$model['certification']['state']=='1'): ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $widget_tmp,
    ]) ?>
<?php endif; ?>
</div>


<link href="/css/product.css" rel="stylesheet">

<script id='jindu' type="text/template">
<div class="chuli">
<div class="progress">
<div class="xqing">
  <div class="clear"></div>
    <ul style="padding-left:0">
		<?php 
			$logCount = count($model['productOrders']['productOrdersLogs']);
			if($model['productOrders']['productOrdersLogs']){ foreach($model['productOrders']['productOrdersLogs'] as $item => $OrdersLog):
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
								
								echo nl2br($OrdersLog['memo']);
								
								switch($OrdersLog['action']){
									case 40:
										if($OrdersLog['trigger']){
											echo Html::a($OrdersLog["triggerLabel"],'javascript:void(0);',['class'=>'jiean',"target"=>"_blank","style"=>""]);
										}else{
											echo Html::a("查看详情",'javascript:void(0);',['class'=>'jiean',"target"=>"_blank","style"=>""]);
										}
										break;
									case 41:
										echo Html::a("查看详情",'javascript:void(0);',['class'=>'jiean',"target"=>"_blank","style"=>""]);
										break;
									case 42:
										echo Html::a("查看详情",'javascript:void(0);',['class'=>'jiean',"target"=>"_blank","style"=>""]);
										break;
									case 50:
										if($OrdersLog['trigger']){
											echo Html::a($OrdersLog["triggerLabel"],'javascript:void(0);',['class'=>'zhongzhi',"targe="=>"_blank","style"=>""]);
										}else{
											echo Html::a("查看详情",'javascript:void(0);',['class'=>'zhongzhi',"target"=>"_blank","style"=>""]);
										}
										break;
									case 51:
										echo Html::a("查看详情",'javascript:void(0);',['class'=>'zhongzhi',"target"=>"_blank","style"=>""]);
										break;
									case 52:
										echo Html::a("查看详情",'javascript:void(0);',['class'=>'zhongzhi',"target"=>"_blank","style"=>""]);
										break;
								}
								echo "。&nbsp;操作人员：";
								if($OrdersLog["action_by"]==$model['product']['create_by']){
									echo "发布方";
								}else{
									echo ($OrdersLog["actionUser"]["realname"]?:$OrdersLog["actionUser"]["username"]);
									echo "&nbsp;<a>".$OrdersLog["actionUser"]["mobile"]."</a>";
								}
								 
								?> </p>
								 <?php 
								foreach($OrdersLog['filesImg'] as $file){
									echo '<span class="pig">'.Html::img(Yii::$app->params["www"].$file['file'],['data-img'=>$file['file'],"class"=>"imgView"]).'</span> ';
								}
								?>
							</div>
						</div>
						<div class="clear"></div>
					</li>
			<?php endforeach;?>
      <?PHP  }else{echo '暂无';}  ?>			
    </ul>
  </div>
</div>
</div>  
</script>

<script id='productMortgages' type="text/template">
		<div class="prduct-r">
		<?php if(in_array('1',explode(',',$model['product']['category']))){ ?>
			<div class="top">
			<p class="title">房屋抵押信息</p>
			</div>
			<div class="editor">
			<?php if(isset($model['productMortgages1'])&&$model['productMortgages1']){foreach ($model['productMortgages1'] as $key => $value){ ?>
					<div class="editor-l"><p><?= isset($value['addressLabel'])?$key+1 .'、'.$value['addressLabel']:'' ?></p></div>
			<?php }}else{echo'暂无';} ?>
			</div>
		<?php } ?>
		<?php if(in_array('2',explode(',',$model['product']['category']))){ ?>
				<div class="top">
			<p class="title">机动车抵押信息</p>
			</div>
			<div class="editor">
			<?php if(isset($model['productMortgages2'])&&$model['productMortgages2']){foreach ($model['productMortgages2'] as $key => $value){ ?>
					<div class="editor-l"><p><?= isset($value['brandLabel'])?$key+1 .'、'.$value['brandLabel']:'' ?></p></div>
			<?php }}else{echo'暂无';} ?>
			</div>
		<?php } ?>
		<?php if(in_array('3',explode(',',$model['product']['category']))){ ?>
			<div class="top">
			<p class="title">合同纠纷类型</p>
			</div>
			<div class="editor">
			<?php if(isset($model['productMortgages3'])&&$model['productMortgages3']){foreach ($model['productMortgages3'] as $key => $value){ ?>
					<div class="editor-l"><p><?= isset($value['contractLabel'])?$key+1 .'、'.$value['contractLabel']:'' ?></p></div>
				<?php }}else{echo'暂无';} ?>
			</div>
		<?php } ?>
		</div>
</script>

<script id='pacts' type="text/template">
<div class="prduct-r">
    <ul>
	   <?php if(isset($model["pacts"])){foreach($model["pacts"] as $pact){ ?>
			<li><img class='imgView' src="<?= isset($pact['file'])?Yii::$app->params["www"].$pact['file']:'/images/weixin.png'?>"></li> 
	   <?php }}else{echo '暂无';} ?>	  
     </ul>
  </div>
</script>  

<script id='zhongzhi' type="text/template">
		<div class="prduct-r">
			<div class="editor">
			<?php if(isset($model['productOrders'])&&$model['productOrders']['productOrdersTerminations']){foreach ($model['productOrders']['productOrdersTerminations'] as $key => $value){ ?>
					<?php if($value['applymemo']){ ?>
						<div class="top">
							<p class="title" style="height: 11px;width: 325px;">申请终止原因&nbsp;&nbsp;&nbsp;(申请时间：<i><?= date('Y-m-d H:i',$value['create_at'])?></i>)</p> 
						</div>
						<div class="editor-l"><p style="margin: 0px 5px 0px 23px;"><?= $value['applymemo'] ?></p></div>
					<?php } ?>
					<?php if($value['resultmemo']){ ?>
						<div class="top">
							<p class="title" style="height: 11px; width: 325px;"><?=$value['status']=="10"?"否决":"同意"?>终止原因&nbsp;&nbsp;&nbsp;(申请时间：<i><?= date('Y-m-d H:i',$value['create_at'])?></i>)</p>
						</div>
						<div class="editor-l"><p style="margin: 0px 5px 0px 23px;"><?= $value['resultmemo'] ?></p></div>
					<?php } ?>
			<?php }}else{echo'暂无';} ?>
			</div>
		</div>
</script>

<script id='jiean' type="text/template">
	<?php if(isset($model['productOrders'])&&$model['productOrders']['productOrdersClosed']){ ?>
			<div class="jiean" style="margin: 0px 35px 0px 42px;">
				<p class="title1">合同编号：<i class="red"><?= $model['product']['number'] ?></i></p>
				<p class="title">兹委托人【委托金额】：<i class="red"><?= $model['product']['account']/10000 ?></i>万元整【<?= $model['product']["type"]=='1'?'固定费用':'服务佣金'?>】：<i class="red"><?= $model['product']["type"]=='1'?$model['product']['typenum']/10000:$model['product']['typenum'].'%'?></i>万元整,委托事项经友好协商已结清。</p>
				<p class="title">实际【结案金额】：<i class="red"><?= $model['productOrders']['productOrdersClosed']['price']/10000 ?></i>万元整，【实收佣金】：<i class="red"><?= $model['productOrders']['productOrdersClosed']['price2']/10000?></i>万元整已支付。</p>
				<p class="title">因本协议履行而产生的任何纠纷，甲乙双方应友好协商解决，如协商不成，任何一方均有权向乙方注册地人民法院提起诉讼。</p>
		    </div>
	<?php }else{echo'暂无';} ?>
</script>

<script id='jingbanr' type="text/template">
		<div class="shen">
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
			<th><p>经办人<p></th>
			<th><p>联系方式<p></th>
			<th><p>经办人等级<p></th>
		</tr>
		</thead>
		<tbody>
		<?php if($model['productOrders']&&$model['productOrders']['productOrdersOperators']){ foreach($model['productOrders']['productOrdersOperators'] as $value): ?>
		<tr  class="<?= isset($value['status'])&&!in_array($value['status'],['10','40'])?'cancel':'' ?>" data-applyid="" data-status="">
			<td class='username'><p class="head"><img src="<?= isset($value['userinfo'])&&$value['userinfo']['headimg']?$value['userinfo']['headimg']['file']:'/images/4.png'?>"></p></td>
			<td class='username'><p><?= (isset($value['operatorCertification'])&&$value['operatorCertification']['name']?$value['operatorCertification']['name']:(isset($value['userinfo'])&&$value['userinfo']['realname']?$value['userinfo']['realname']:$value['userinfo']['username']))?></p></td>
			<td><p><?= isset($value['userinfo'])?$value['userinfo']['mobile']:''?></p></td>
			<td><p><?=$value['level']==1?"一级":"二级"?></p></td>
		</tr>
		<?php endforeach; } ?>
		</tbody>
		</table>
		</div>
</script>

<script>
$(document).ready(function(){
var scrollid = '';
	$(".jindu").click(function(){
		    layer.open({
					skin:'houses',
					type: 1,
					title:'处理进度',
					shadeClose:true,
					move:false,
					shade: 0.8,
					btn:false,
					area: ['900px', 'auto'],
					content:$("#jindu").html(), //iframe的url
					end:function(){
						if(scrollid){
							$("#"+scrollid).remove();
						}
					}
					}); 
					var obj = $('.xqing ul').perfectScrollbar();
					scrollid = obj.id;
					$("#"+scrollid).css("z-index","99999999")
			
	})
	
	$(".productMortgages").click(function(){
		    layer.open({
					skin:'houses',
					type: 1,
					title:'抵押物',
					shadeClose:true,
					move:false,
					shade: 0.8,
					btn:false,
					area: ['350px', 'auto'],
					content:$("#productMortgages").html(), //iframe的url
					}); 
	})
	
	$(".pacts").click(function(){
		    layer.open({
					skin:'houses',
					type: 1,
					title:'协议图片',
					shadeClose:true,
					move:false,
					shade: 0.8,
					btn:false,
					area: ['400px','auto'],
					content:$("#pacts").html(), //iframe的url
					}); 
	})
	$(document).on('click','.zhongzhi',function(){
		layer.open({
					skin:'houses',
					type: 1,
					title:'终止原因',
					shadeClose:true,
					move:false,
					btn:false,
					area: ['400px','auto'],
					content:$("#zhongzhi").html(), //iframe的url
					}); 
	})
	$(document).on('click','.jiean',function(){
		layer.open({
					skin:'houses',
					type: 1,
					title:'结清证明',
					shadeClose:true,
					move:false,
					btn:false,
					area: ['860px','200px'],
					content:$("#jiean").html(), //iframe的url
					}); 
	})

	$(document).on('click','.applys',function(){
		   var applyid = $(this).attr('data-applyid');
		   var status = $(this).attr('data-status');
		   if(status == '10' || status == '20'|| status == '40'){
				var url = '<?= Url::toRoute('/products/orders-list')?>'+'?id='+applyid;
				window.open(url)
		   }else{
			   return false;
		   }
		   
	})
	
	$(".jingbanr").click(function(){
			layer.confirm($("#jingbanr").html(), {
				skin:'houses',
				title:'经办人（<i style="color:#01a3f6;"><?=count($model['productOrders']['productOrdersOperators'])?></i>）',
				type:1,
				move:false,
				btn:false,
				//scrollbar:false,
				area: ['900px', 'auto'],
			})
	})
})
</script>