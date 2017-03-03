<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
// var_dump($data);
$userid = Yii::$app->user->getId();
if($type==2){
	if($orders&&$userid == $orders["create_by"]){
		$label1 = "我的接单";
		$url1 = ['/productorders/index',"type"=>"0"];
		$label2 = "接单详情";
		$url2 = ['/productorders/detail',"applyid"=>$orders?$orders["applyid"]:""];
	}else{
		$label1 = "我的经办";
		$url1 = ['/productorders/index',"type"=>"1"];
		$label2 = "经办详情";
		$url2 = ['/productorders/detail',"applyid"=>$orders?$orders["applyid"]:""];
	}
	
	$this->params['breadcrumbs'][] = ['label' => $label1, 'url' =>$url1 ];
	$this->params['breadcrumbs'][] = ['label' => $label2, 'url' =>$url2 ];
	$this->title = "添加经办人";
	$this->params['breadcrumbs'][] = $this->title;
} else{
	$this->title = "我的通讯录";
}



$action = Yii::$app->controller->action->id;

?>
<div class="content clearfix">
	
	<?php
	 Pjax::begin([
		 'id' => 'post-grid-pjax',
	 ])
	?>
	<?=$type==2?"":$this->render("/layouts/leftmenu")?>
	<div class="new-list <?=$type==2?"addagent":""?>">
		<?php if($type==2){?>
		<div class="right-too">
			<div class="right-to">
				<p>添加经办人</p>
			</div>
        </div>
		<div class="right-next">
			<div class="right-topl">
				<div class="topl"><p>我的通讯录</p></div>
				<div class="topr"><a class="operatorset" style="background:#ccc;">添加至我的经办人<i id="number"></i></a></div>
			</div>
			<div class="right-topr">
				<a class="add">添加好友</a>
			</div>
		</div>
		<?php }else{?>
		 <div class="right-next">
			<div class="right-topl">
			  <p>我的通讯录</p>
			</div>
			<div class="right-topr">
			  <a class="add">添加好友</a>
			</div>
		  </div>
		<?php }?>
		 
	  
		
	  
      <div class="list">
        <table class="phone">
          <colgroup>
          <col width='480px'/>
          <col width='320px'/>
          <col width='150px'/>
          </colgroup>
          <thead>
            <tr>
              <th><p>姓名</p></th>
              <th><p>联系方式</p></th>
              <th><p>添加时间</p></th>
            </tr>
            </thead>
            <tbody>
			<?php if($type==2){?>
			<?php foreach($data as $key=>$val):?>
				
				<tr>
				  <th><p><span class="<?=$val['ordersOperator']?"infor_b":"infor_d"?>"><input type="checkbox" class='infor_c' name='jbr[]'  value='<?=$val['userid']?>'  ></span><img src="<?=$val['userinfo']['headimg']?$val['userinfo']['headimg']['file']:''?>"><?=$val['userinfo']['realname']?:$val['userinfo']['username']?></p></th>
				  <th><p><?=$val['userinfo']['mobile']?></p></th>
				  <th><p><?=date("Y-m-d H:i",$val['create_at'])?></p></th>
				</tr>
		    <?php endforeach;?>
			<?php }else {?>
			<?php foreach($data as $key=>$val):?>
				<tr>
					<th><a target="_blank" href="<?=Url::toRoute(["/userinfo/detail","userid"=>$val['userid']])?>"><p><img src="<?=$val['userinfo']['headimg']?$val['userinfo']['headimg']['file']:''?>"><?=$val['userinfo']['realname']?:$val['userinfo']['username']?></p></a></th>
					<th><p><?=$val['userinfo']['mobile']?></p></th>
					<th><p><?=date("Y-m-d H:i",$val['create_at'])?></p></th>
				</tr>
			<?php endforeach;?>
			<?php }?>
           </tbody>
        </table>
      </div>
		<div class="pages clearfix ">
			<div class="fenye" style="margin-top:30px"> 
			<span class="fenyes" style="font-size:12px;margin:0px 35px -41px;">共<?=$provider->pagination->totalCount?>条记录，第<?=$provider->pagination->page+1?>/<?=$provider->pagination->pageCount?>页</span>
			 <?= yii\widgets\LinkPager::widget([
				'pagination' => $provider->pagination
			]) ?>
			</div>
		</div>
    </div>
	<?php Pjax::end() ?>
</div>
<script>
$(document).ready(function(){
	var ordersid ='<?=$ordersid?>';
	var csrf = '<?=Yii::$app->request->getCsrfToken()?>'
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
			$(".operatorset").css("background","#0da3f8")
			$("#number").html("("+num+")")
		}else{
			$(".operatorset").css("background","#ccc")
			$("#number").html("")
		}
		
		return false;
	})
	
	$(document).on("click",".operatorset",function(){
		if(operatorIds){
			$.ajax({
					url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-operator-set')?>",
					type:'post',
					data:{ordersid:ordersid,operatorIds:operatorIds,_csrf:csrf},
					dataType:'json',
					success:function(json){
						
						if(json.code == '0000'){
							layer.msg(json.msg,{time:2000},function(){window.opener.location.reload(); location.reload();});
						}else{
							layer.msg(json.msg,{time:2000});
						}
					}
				})
		}
		return false;
	})
   	function searchContacts(){
		var html = '<div class="searchForm">';
		html += "<p style='color:#333;font-size:17px;'>添加好友</p>";
		html += "<p style='color:#999;font-size:13px;'>好友添加后可以在处理的订单里把TA添加为您的经办人</p>";
		html += "<input id='searchmobile' maxlength='11' value=''/>";
		html += '</div>';
		
		var confirmindex =  layer.confirm(html,
			{
				type:1,
				title: '<p><img src="/bate2.0/images/title3.png"></p>',
				skin:'agent',
				move: false,
				btn:["取消","确认"],
				area: ['365px', '205px'],
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
					layer.msg("请输入正确的手机格式！",{time:2000})
					return false;
				}
				$.ajax({
					url:"<?php echo yii\helpers\Url::toRoute('/contacts/search')?>",
					type:'post',
					data:{mobile:searchmobile},
					dataType:'json',
					success:function(json){
						if(json.code == '0000'){
							if(json.data.userData.contactsid){
								layer.msg('已是你的联系人',{time:2000});
							}else{
								layer.close(confirmindex)
								addContacts(json.data.userData);
							}
						}else{
							layer.msg(json.msg,{time:2000});
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
				title: '<p><img src="/bate2.0/images/title3.png"></p>',
				move: false,
				skin:'agent',
				tipsMore: true,
				area: ['365px', '205px'],
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
							layer.msg(json.msg,{time:2000},function(){location.reload();});
						}else{
							layer.msg(json.msg,{time:2000});
						}
					}
				})
				return false;
		});	
	}
	$(document).on("click",".add",searchContacts)	
})
</script>
