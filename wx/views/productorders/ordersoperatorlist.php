<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
if($data['orders']&&in_array($data['orders']['status'],['0','10','20'])&&$data['accessOrdersADDOPERATOR']){ 
	$gohtml  = Html::a("编辑","javascript:void(0)",["class"=>"btn_edit"]);
}else{
	$gohtml ='';
} 
// var_dump($data);exit;
?>
<?=wxHeaderWidget::widget(['title'=>'接单经办人','gohtml'=>$gohtml,'backurl'=>Url::toRoute(['productorders/detail',"applyid"=>$data['orders']['applyid']]),'reload'=>false,'homebtn'=>true])?>
<style>
</style>
<!-----经办人列表------>
<section>
<?php if(in_array($data['orders']['status'],['0','10','20'])&&$data['accessOrdersADDOPERATOR']){ ?>

  <ul class="keep" style="margin-top:0px;">
    <a href="<?=Url::toRoute(["contacts/index",'type'=>"2","ordersid"=>$ordersid])?>">
    <li class="keep01" style="border:0px solid #ddd;"> <span class="keep_ig2"></span> <span class="baocun" style="font-size:16px;">从通讯录添加</span> <i class="jiantou jiantou01"></i> </li>
    </a>
  </ul>
<?php } ?>
  <ul class="con11" style="margin-top:10px;">
  <?php foreach($data['operators'] as $operator){?>
    <li class="hm-01" data-owner ="<?=$operator['owner']?>">
      <div class="hm-01_shuzi" style="border:0px solid #ddd;margin-top:0px;">
        <div class="num_sz" style="width:60%;height:44px;float:left;">
		<span class="keep_ig<?=$operator['level']==2?"9":"10"?>"></span> 
		<span style="float:left;margin:0px 10px;padding-left:0px;">
		<img src="/bate2.0/images/head.png" style="width:30px;height:30px;border-radius: 50%;vertical-align: middle;">
		</span> 
	    <a href="<?=Url::toRoute(["/user/detail","userid"=>$operator['operatorid']])?>" style="width:60%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;float:left;font-size:14px;line-height:44px;"><?=$operator['userinfo']['realname']?:$operator['userinfo']['username']?></a>
		</div>
		<div class="renzhen-01" style='margin-top:10px;'>
		<?php 
		if($userid != $operator['operatorid']){
			echo '<a href="tel:'.$operator['userinfo']['mobile'].'"><span class="keep_ig3"></span></a>';
		}
		
		if($userid == $operator['create_by']/*||$userid == $operator['operatorid']*/){
			echo '<a style="display:none" href="javascript:void(0)"><span data-id="'.$operator['id'].'" class="keep_ig4"></span></a>';
		}
		?>
		</div>
      </div>
    </li>
  <?php }?>
  </ul>
  <ul class="keep" style="margin-top:10px;">
     
    <li class="keep01" style="border:0px solid #ddd;"> 
	<span class="baocun"><?=$data['orders']['createuser']["realname"]?:$data['orders']['createuser']["username"]?></span> 
	<span style="background:#10a1ec;color:#fff;font-size:12px;">接单方</span> 
	<?php if($userid != $data["orders"]['create_by']){?>
	<div class="renzhen-01" style='padding-right:2px;'>
		<a href="tel:<?=$data["orders"]['createuser']['mobile']?>"><span class="keep_ig3"></span></a>
	</div>
	<?php }?>
	</li>
     
  </ul>
</section>
<!-----经办人列表结束------> 

<style>
.layui-layer-prompt .layui-layer-input {height:50px;background: #ccc;}
.layui-layer-title{background: #fff;border-bottom:0px solid #ddd;text-align: center;padding: 0px 35px 0 20px;}
.layui-layer-btn {padding: 10px 10px 10px;pointer-events: auto;border-top:1px solid #ddd;}
.layui-layer-btn a{
	height: 35px;
	line-height: 35px;
	margin: 0 6px;
	padding: 0px 41px;
	border: 0px solid #dedede; 
	background: #fff ;
	color: #0da3f9;
	border-radius: 2px;
	font-weight: 400;
	cursor: pointer;
	text-decoration: none;
}
.layui-layer-btn .layui-layer-btn0 {
	background-color: #fff;
	color: #0da3f9;
}
.infor li {border-bottom: 0px solid #ddd;}
.infor_r{width:90%;border-bottom:1px solid #ddd;float:right;}
.infor_c{display:none}
.infor_d{width:25px;height:25px;margin-top:15px;float:left;background:url(/bate2.0/images/sers.png) no-repeat -23px -96px;vertical-align:middle;}
.selected{width:25px;height:25px;margin-top:15px;float:left;background:url(/bate2.0/images/sers.png) no-repeat 2px -96px;vertical-align:middle;}
</style>
<script>

$(document).ready(function(){
	$(".btn_edit").click(function(){
		if($(this).html()=="编辑"){
			$(this).html("完成")
			$(".keep_ig4").parent('a').show();
			$(".keep_ig3").parent('a').hide();
		}else{
			$(this).html("编辑");
			$(".keep_ig4").parent('a').hide();
			$(".keep_ig3").parent('a').show();
		}
	})
	var curdel='';
	$(".keep_ig4").click(function(){
		curdel = $(this).parents("li.hm-01")
		var ordersid = '<?=$data['orders']['ordersid']?>';	
		var id = $(this).attr("data-id");
		$.ajax({
			url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-operator-unset')?>",
			type:'post',
			data:{ordersid:ordersid,ids:id},
			async: false,
			dataType:'json',
			success:function(json){
				if(json.code=="0000"){
					layer.msg(json.msg,{time:1000},function(){
						var owner = curdel.attr("data-owner")
						$(".hm-01[data-owner='"+owner+"']").each(function(){
							$(this).remove()
						});
					})
				}else{
					layer.msg(json.msg)
				}
			}
		})
	})

}); 

	 
</script>