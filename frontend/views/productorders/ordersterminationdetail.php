<?php

use yii\helpers\ArrayHelper;
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


$title=$data['status']==0?$dataLabel:"终止详情";
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
       <p class="text">产品编号：<i><?=$data['product']['number']?></i> &nbsp;&nbsp; 申请终止时间：<i><?=date("Y-m-d H:i",$data['create_at'])?></i> &nbsp;&nbsp; 申请人：<i><?=$data["createuser"]['realname']?:$data["createuser"]['username']?></i></p>
      </div>
  </div>
  <div class="bottom">
    <div class="prove">
      <div class="jiean">
        <p class="Title">申请终止原因</p>
        <div class="text">
        <?=nl2br($data['applymemo'])?>
        </div>
		<?php if($data['status']!='0'){?>
		
		<p class="Title" style="margin:0;border-top:1px solid #e5e5e5;padding:10px 0;"><?=$data['status']=="10"?"否决":"同意"?>终止原因</p>
			<div class="text">
			 <?=nl2br($data['resultmemo'])?>
			</div>
		<?php }?>
      </div>
      <div class="bton">
		<?php if($data['status']==0&&$accessTerminationAUTH==true&&$data['status']!=Yii::$app->user->getId()){?>
        <div class="bon">
         <a class="active audit-btn" data-attr='agree'>同意终止</a>
         <a class="refused audit-btn" data-attr='veto'>拒绝终止</a>
        </div>
		<?php }?>
        <p>如果有任何问题，您可以选择联系我们，客服热线：<i>400-855-7022</i></p>
      </div>
    </div>
  </div>
 
<script>
$(document).ready(function(){
	var terminationid = '<?=$data['terminationid']?>';
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
				title: type+"终止",
				anim: 2,
				content: '<p class="text">'+type+'原因</p><textarea id="resultmemo" placeholder="请输入'+type+'终止的原因" ></textarea>',
				yes:function(){
					var resultmemo = $("#resultmemo").val();
					$.ajax({
						url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-termination-')?>"+attr,
						type:'post',
						data:{terminationid:terminationid,resultmemo:resultmemo,_csrf:csrf},
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

 