<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$userid = Yii::$app->user->getId();
// var_dump($data);

$label1 = "我的经办";
$url1 = ['/productorders/index',"type"=>"0"];
$label2 = "经办详情";
$url2 = ['/productorders/detail',"applyid"=>$data["orders"]["applyid"]];
if($userid == $data['orders']["create_by"]){
	$label1 = "我的接单";
	$url1 = ['/productorders/index'];
	$label2 = "接单详情";
	$url2 = ['/productorders/detail',"applyid"=>$data["orders"]["applyid"]];
}else if($userid == $data['product']["create_by"]){
	$label1 = "我的发布";
	$url1 = ['/product/index'];
	$label2 = "发布详情";
	$url2 = ['/product/product-deta',"productid"=>$data["product"]["productid"]];
}


$title="结清证明";
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => $label1, 'url' =>$url1 ];
$this->params['breadcrumbs'][] = ['label' => $label2, 'url' =>$url2 ];
$this->params['breadcrumbs'][] = $this->title;
$action = Yii::$app->controller->action->id;

?>

 <!-- 内容--> 
  <div class="top">
      <div class="above">
       <p class="title"><?=$title?></p>
       <p class="text">产品编号：<i><?=$data['product']['number']?></i> &nbsp;&nbsp; 申请结案时间：<i><?=date("Y-m-d H:i",$data['create_at'])?></i> &nbsp;&nbsp; 申请人：<i><?=$data["createuser"]['realname']?:$data["createuser"]['username']?></i></p>
      </div>
  </div>
  <div class="bottom">
	<div class="prove">
      <div class="jiean">
        <p class="Title">结清证明</p>
        <p class="title1">合同编号：<i class="red"><?= $data['product']['number']?></i></p>
        <p class="title">兹委托人【委托金额】：<i class="red"><?= $data['product']['accountLabel'] ?></i>万元整【<?= $data['product']["type"]=='1'?'固定费用':'服务佣金'?>】：<i class="red"><?= $data['product']["type"]=='1'?$data['product']['typenumLabel']:$data['product']['typenum'].'%'?></i><?= $data['product']["type"]=='1'?'万元整':''?>,委托事项经友好协商已结清。</p>
        <p class="title">实际【结案金额】：<i class="red"><?= $data['priceLabel']?></i>万元整，【实收佣金】：<i class="red"><?= $data['price2Label']?></i>万元整已支付。</p>
        <p class="title">因本协议履行而产生的任何纠纷，甲乙双方应友好协商解决，如协商不成，任何一方均有权向乙方注册地人民法院提起诉讼。</p>
      </div>
      <div class="jtime">
        <p class="title-date">特此证明</p>
        <p class="title-time">2016-11-17</p>
      </div>
	  <div class="bton">
		<?php if($data['status']==0&&$accessClosedAUTH==true&&$data['status']!=Yii::$app->user->getId()){?>
        <div class="bon">
         <a class="active audit-btn" data-attr='agree'>同意结案</a>
         <a class="refused audit-btn" data-attr='veto'>拒绝结案</a>
        </div>
		<?php }?>
        <p>如果有任何问题，您可以选择联系我们，客服热线：<i>400-855-7022</i></p>
      </div>
    </div>
	</div> 
      
     
 
<script>
$(document).ready(function(){
	var closedid = '<?=$data['closedid']?>';
	var csrf = '<?=Yii::$app->request->getCsrfToken()?>'
	$(".audit-btn").click(function(){
			var type =$(this).attr("class")=="active audit-btn"?"同意":"拒绝";
			var attr =$(this).attr("data-attr");
			layer.open({
				btn:[type,'取消'],
				skin:'ju',
				type: 1,
				shadeClose: false,
				move: false,
				title: type+"结案",
				anim: 2,
				area: ['550px', 'auto'],
				content: '<p class="text">'+type+'原因</p><textarea id="resultmemo" placeholder="请输入'+type+'结案的原因"></textarea>',
				yes:function(){
					var resultmemo = $("#resultmemo").val();
					$.ajax({
						url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-closed-')?>"+attr,
						type:'post',
						data:{closedid:closedid,resultmemo:resultmemo,_csrf:csrf},
						dataType:'json',
						success:function(json){
							if(json.code=="0000"){
								layer.msg(json.msg,{},function(){location.reload()})
								opener.location.reload();
							}else{
								layer.msg(json.msg)
							}
						}
					})
				}
			})
		})
	})

</script>

 