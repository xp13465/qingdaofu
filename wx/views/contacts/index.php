<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
// echo "<pre>";
// print_r($data);
 // exit;
$this->registerJsFile('@web/js/fastclick.js',['depends'=>['wx\assets\NewAppAsset']]);

if($type==2){
	$backurl = Url::toRoute(["productorders/orders-operator-list","ordersid"=>$ordersid]);
}else{
	$backurl = Url::toRoute("user/index");
}
$gohtml = '<a href="javascript:void(0);" class="add"></a>';
?>
<?=wxHeaderWidget::widget(['title'=>'我的通讯录','homebtn'=>false,'backurl'=>$backurl,'gohtml'=>$gohtml])?>
<section>
<p id="show"></p>
  <div class="type" id="wrapper">
    <div id="scroller" class="type" style="position:absolute;">
			<div id="pullDown" style="display:none">
				<span class="pullDownIcon"></span><span class="pullDownLabel"></span>
			</div>
			<ul id="thelist" class="infor" data-catr="<?=$curCount?>" data-url="<?= yii\helpers\Url::toRoute(['/contacts/index','ordersid'=>$ordersid,'type'=>$type])?>">
			<?php if($type==2){?>

			 
				<?php foreach($data as $key=>$val):?>
					<li>
						<div class="<?=$val['ordersOperator']?"infor_b":"infor_d"?>"><input type='checkbox' class='infor_c' name='jbr[]'  value='<?=$val['userid']?>' ></div>
						<div class="infor_r">
						<span class="portrait"><img src="<?=Yii::$app->params['wx']?><?=$val['userinfo']['headimg']?$val['userinfo']['headimg']['file']:''?>"></span>
						<span class="por"><a href="<?=Url::toRoute(["/user/detail","userid"=>$val['userid']])?>"><?=$val['userinfo']['realname']?:$val['userinfo']['username']?></a></span> <span style="color:#999;"><?=$val['userinfo']['mobile']?></span> 
						</div>
						<div class='clear'></div>
					</li>
			   <?php endforeach;?>
			<?php }else{ ?>
					<?php foreach($data as $key=>$val):?>
					<li  class="int">
					  <span class="portrait"><img src="<?=Yii::$app->params['wx']?><?=$val['userinfo']['headimg']?$val['userinfo']['headimg']['file']:''?>"></span>  
					  <div class="infor_l"> <span><a href="<?=Url::toRoute(["/user/detail","userid"=>$val['userid']])?>"><?=$val['userinfo']['realname']?:$val['userinfo']['username']?></a></span><span style="color:#666;"><a href='tel:<?=$val['userinfo']['mobile']?>'><?=$val['userinfo']['mobile']?></a></span></div>
					<div class='clear'></div>
					</li>
					<?php endforeach;?>
			<?php }?>
			</ul>
		<div id="pullUp" style="display:none;" >
			<span class="pullUpIcon"></span><span class="pullUpLabel"></span>
		</div>
	</div>
</section>
<?php if($type ==2){?>
<footer>
<div class="tom">
<div class="tom-l" style="background:#fff"><p class="numbers" >已选择0个联系人</p></div>
<div class="tom-r"><a href="#" class="operatorset">分配经办人</a></div>
</div>
  <!--<div class="bottom">
   <a href="#" class="operatorset" >分配经办人</a>
  </div>-->
</footer>
<?php }?>
<style>
.add{background:url("/bate2.0/images/sers1.png") top -240px left -72px no-repeat;background-size:96px;width:22px !important;height:22px !important;display:block;position:inherit !important;}
.tom{height:50px;position: fixed;bottom: 0;z-index: 1; width: 100%;max-width: 640px;margin: 0 auto;}
.tom-l{width:75%;height:50px;line-height:50px;float:left;text-align:left;text-indent:20px;}
.tom-r{width:25%;height:50px;line-height:50px;float:left;text-align:center;background:#B0B0B0;}
.tom-r a{width:100%;line-height:50px;float:left;text-align:center;color:#DCD9D9;background:#B0B0B0;}

#searchmobile {height:40px;background: #eee;box-shadow:none;border:none;border-radius:3px;width:100%;}
.searchForm{padding:20px;background: #fff;width:100%; }
.searchResult{background: #eee;border-radius:3px;width:100%;     padding: 10px 20px;}
.layui-layer-title{border-bottom:0px solid #ddd;text-align: center;padding: 0px 35px 0 20px;}
.layui-layer-btn {padding: 10px 10px 10px;pointer-events: auto;border-top:1px solid #ddd;}
.layui-layer-btn a{
	height: 35px;
	line-height: 35px;
	margin: 0 6px;
	width:120px;
	text-align:center;
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
.por{width: 35%;
    color: #333;
    text-indent: 10px;
    height: 45px;
    float: left;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;}

.infor li {border-bottom: 0px solid #ddd;height:auto;}
.infor_r{width:90%;border-bottom:1px solid #ddd;float:right;}
.infor_c{display:none}
.infor_d{width:25px;height:25px;margin-top:15px;float:left;background:url(/bate2.0/images/sers.png) no-repeat -23px -96px;vertical-align:middle;}
.infor_b{width:25px;height:25px;margin-top:15px;float:left;background:url(/bate2.0/images/sers.png) no-repeat -71px -216px;vertical-align:middle;}
.selected{width:25px;height:25px;margin-top:15px;float:left;background:url(/bate2.0/images/sers.png) no-repeat 2px -96px;vertical-align:middle;}
.infor .int{border-bottom:1px solid #ddd;}
.portrait{width:30px !important;height:30px;float:left;margin:0px 10px;padding-left:0px;}
.portrait img{width:30px;height:30px;border-radius: 50%;vertical-align: middle;}
</style>

<script>

$(document).ready(function(){
	datalistClass = '#scroller ul.infor li';
	var ordersid ='<?=$ordersid?>';
	var operatorIds = '';
	$(document).on("click",".infor_d",function(){
		var $child = $(this).children("input.infor_c")
		if($child.prop("checked")){
			$child.prop("checked",false)
		}else{
			$child.prop("checked",true)
		}
		$child.trigger("change")
	})
	$(document).on("change",".infor_c",function(){
		if($(this).prop("checked")){
			$(this).parent('.infor_d').addClass("selected")
		}else{
			$(this).parent('.infor_d').removeClass("selected")
		}
		operatorIds = '';
		var num  = 0;
		$(".infor_d .infor_c:checked").each(function(){
			num++;
			operatorIds += operatorIds?","+$(this).val():$(this).val();
		});
		if(operatorIds){
			$(".operatorset").css("background","#10a1ec").css("color","#fff")
		}else{
			$(".operatorset").css("background","").css("color","#DCD9D9")
		}
		$(".numbers").html("已选择"+num+"个经办人")
		return false;
	})
	
	$(document).on("click",".operatorset",function(){
		if(operatorIds){
			$.ajax({
					url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-operator-set')?>",
					type:'post',
					data:{ordersid:ordersid,operatorIds:operatorIds},
					dataType:'json',
					success:function(json){
						
						if(json.code == '0000'){
							layer.msg(json.msg,{time:1000},function(){location.href='<?php echo yii\helpers\Url::toRoute(['/productorders/orders-operator-list',"ordersid"=>$ordersid])?>';});
						}else{
							layer.msg(json.msg,{time:1000});
						}
					}
				})
		}
		return false;
	})
	function searchContacts(){
		var html = '<div class="searchForm">';
		html += "<input id='searchmobile' maxlength='11' value=''/>";
		html += '</div>';
		
		var confirmindex =  layer.confirm(html,
			{
				type:1,
				title: '添加联系人',
				move: false,
				btn:["取消","确认"],
				zIndex: layer.zIndex,
				formType: 1 //prompt风格，支持0-2
			}, function(){
				// alert($("#searchmobile").val())
				layer.close(confirmindex)
			}, function(){
				var searchmobile = $("#searchmobile").val();
				var phone = /^(0|86|17951)?1[3|4|5|7|8]\d{9}$/;
				if(phone.test(searchmobile) == false){
					$(this).css("color","red").focus();
					flag=false;
					layer.msg("请输入正确的手机格式！",{time:1000})
					return false;
				}
				$.ajax({
					url:"<?php echo yii\helpers\Url::toRoute('/contacts/search')?>",
					type:'post',
					data:{mobile:searchmobile},
					dataType:'json',
					success:function(json){
						if(json.code == '0000'){
							if(json.result.userData.contactsid){
								layer.msg('已是你的联系人',{time:1000});
							}else{
								layer.close(confirmindex)
								addContacts(json.result.userData);
							}
						}else{
							layer.msg(json.msg,{time:1000});
						}
					}
				})
				return false;
		  }
		  
		  
		  );	
	 	}
	function addContacts(userdata){
		var html = '<div class="searchResult">';
		html += '<p>电话：'+userdata.mobile+"</p>";
		html += '<p>姓名：'+userdata.realname+"</p>";
		html += '<p>帐号：'+userdata.username+"</p>";
		html += '</div>';
		var confirmindex =  layer.confirm(html,
			{
				type:1,
				title: '添加联系人',
				move: false,
				tipsMore: true,
				btn:["重新输入","确认添加"],
				zIndex: layer.zIndex,
				formType: 1 //prompt风格，支持0-2
			}, function(){
				layer.close(confirmindex)
				searchContacts();
			}, function(){
				$.ajax({
					url:"<?php echo yii\helpers\Url::toRoute('/contacts/apply')?>",
					type:'post',
					data:{userid:userdata.id},
					dataType:'json',
					success:function(json){
						if(json.code == '0000'){
							layer.msg(json.msg,{time:1000},function(){location.reload();});
						}else{
							layer.msg(json.msg,{time:1000});
						}
					}
				})
				return false;
		});	
	}
	$(document).on("click",".add",searchContacts)	

	
	
})
</script>