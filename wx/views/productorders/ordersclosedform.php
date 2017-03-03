<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
$category = ['1'=>'进行中','2'=>'已完成'];
?>
<style>
</style>
<?=wxHeaderWidget::widget(['title'=>'申请结案','gohtml'=>'<a href="#" class="prove">证明示例</a>','backurl'=>yii\helpers\Url::toRoute(['/productorders/detail','applyid'=>Yii::$app->request->get('applyid')]),'reload'=>false])?>
<section>
  <div class="information"></div>
  <ul class="infor">
    <li>
      <div class="infor_l"> <span style="width:35%;color:#333;">实际结案金额</span>
        <input type="text" name="price" id="applymemo" value="" placeholder="实际结案金额">
        <span style="color:#666;">万</span> </div>
    </li>
    <li>
      <div class="infor_l"> <span style="width:35%;color:#333;">实收佣金</span>
        <input type="text" name="price" id="price2" value="" placeholder="实际收到佣金">
        <span style="color:#666;">万</span> </div>
    </li>
  </ul>
  <p style="font-size:14px;color: #AAA7A7;margin-left:10px;line-height:26px;margin-top:12px"><i style="color:#148fcc;">注：</i>点击申请结案后，系统会根据你填写的信息发送给发布方一份结清证明，等待发布方确认无误后确认结案。</p>
</section>
<footer>
  <div class="bottom"> <a href="javascript:void(0);" class="agreed" data-ordersid='<?= Yii::$app->request->get('ordersid') ?>'style="background:#10a1ec;">立即申请</a> </div>
</footer>
<script>

$(document).ready(function(){
	
	$(".agreed").click(function(){
		var price = $("input[name='price']").val();	
		var price2 = $("#price2").val();			
		var files = $("input[name='files']").val();
		var ordersid = $(this).attr('data-ordersid');
		$.ajax({
			url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-closed-apply')?>",
			type:'post',
			data:{ordersid:ordersid,price:price,price2:price2,files:files},
			dataType:'json',
			success:function(json){
				if(json.code=="0000"){
					layer.msg(json.msg,{},function(){location.href='<?= yii\helpers\Url::toRoute(['/productorders/detail','applyid'=>Yii::$app->request->get('applyid')])?>';});
				}else{
					layer.msg(json.msg)
				}
			}
		})
	})
	
 
}); 
$(document).ready(function(){
	$(".prove").click(function(){
			layer.open({
			  title: '示例证明',
			  shadeClose: true,
			  shade: 0.8,
			  area: ['100%', '100%'],
			  content: '<p class="title">合同编号：<i class="red">BX201610170002</i></p><p class="title">兹委托人【委托金额】：<i class="red">333</i>万元整【委托费用】：<i class="red">33</i>万,委托事项经友好协商已结清。</p><p class="title">实际【结案金额】：<i class="red">0</i>万元整，【实收佣金】：<i class="red">0</i>万元整已支付。</p><p class="title">因本协议履行而产生的任何纠纷，甲乙双方应友好协商解决，如协商不成，任何一方均有权向乙方注册地人民法院提起诉讼。</p>  <div class="jietime"><div class="jtime"><p class="title-date">特此证明</p><p class="title-time"><?=date("Y-m-d",time())?></p></div></div>'
			}); 		
		})
})
	 
</script>