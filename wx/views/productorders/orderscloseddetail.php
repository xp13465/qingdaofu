<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
 // var_dump($data);
?>
<style>
.layui-layer-btn{padding: 10px 10px 10px;pointer-events: auto;border-top: 1px solid #ddd;}
.layui-layer-btn a {
    height: 35px;
    line-height: 35px;
    margin: 0 6px;
    width: 120px;
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
.layui-layer-title{text-align:center;padding: 0 35px 0 20px;}
</style><?=wxHeaderWidget::widget(['title'=>'结案详情','gohtml'=>'','backurl'=>true,'homebtn'=>true,'reload'=>false])?>
<div style="background:#fff;">
  <div class="jiean">
    <p class="Title">结清证明</p>
    <p class="title">合同编号：<i class="red"><?=$data['product']['number']?></i></p>
    <p class="title">兹委托人【委托金额】：<i class="red"><?=str_replace("万","",$data['product']['accountLabel'])?></i>万元整【委托费用】：<i class="red"><?=$data['product']['typenumLabel']?></i><?=$data['product']['typeLabel']?>,委托事项经友好协商已结清。</p>
    <p class="title">实际【结案金额】：<i class="red"><?=$data['priceLabel']?></i>万元整，【实收佣金】：<i class="red"><?=$data['price2Label']?></i>万元整已支付。</p>
    <p class="title">因本协议履行而产生的任何纠纷，甲乙双方应友好协商解决，如协商不成，任何一方均有权向乙方注册地人民法院提起诉讼。</p>
  </div>
  <div class="jietime">
    <div class="jtime">
      <p class="title-date">特此证明</p>
      <p class="title-time"><?=date("Y-m-d",$data['create_at'])?></p>
    </div>
  </div>
</div>
<?php if($accessClosedAUTH && $data['status']==="0"){?>
<footer>
  <div class="bottom" style="height:120px;"> 
	<a class="agreed btn_audit" data-attr='agree' href="javascript:void(0);" style="background:#10a1ec;">同意结案</a> 
	<a class="refused btn_audit" data-attr='veto' href="javascript:void(0);" style="background:#fff;color:#666;border:1px solid #ddd;">拒绝结案</a>
  </div>
</footer>
<?php }?>
<script>
$(document).ready(function(){
	var closedid = '<?=$data['closedid']?>';
	$(".btn_audit").click(function(){
		var attr =$(this).attr("data-attr");
			layer.confirm("<textarea id='resultmemo' name='resultmemo' style='width:100%;height:60px;resize:none'  placeholder='理由...'/></textarea>",{
			  title: '确定'+$(this).html(),
			  closeBtn: false,
			  btn: ['确认','取消'],
			  formType:0 //prompt风格，支持0-2
			}, function(){
				var resultmemo = $("#resultmemo").val();
				$.ajax({
					url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-closed-')?>"+attr,
					type:'post',
					data:{closedid:closedid,resultmemo:resultmemo},
					dataType:'json',
					success:function(json){
						if(json.code=="0000"){
							if(attr == 'agree'){
								layer.msg(json.msg,{},function(){location.href='<?= yii\helpers\Url::toRoute(['/wrelease/details','productid'=>$data['product']['productid'],'type'=>'1'])?>';})
							}else{
								layer.msg(json.msg,{},function(){location.href='<?= yii\helpers\Url::toRoute(['/wrelease/details','productid'=>$data['product']['productid']])?>';})
							}
						}else{
							layer.msg(json.msg)
						}
					}
				})
			});
	})
})
</script>
