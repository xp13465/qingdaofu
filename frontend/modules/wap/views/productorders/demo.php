<?php 
use yii\helpers\Html;
use yii\helpers\Url;
$array = [
	'接单'=>[
		// '接单列表' =>  '/wap/productorders/index',
		'接单进行中列表' => '/wap/productorders/list-processing',
		'接单已完成列表' => '/wap/productorders/list-completed',
		'接单已终止列表' => '/wap/productorders/list-aborted',
		'经办进行中列表' => '/wap/productorders/list-processing',
		'经办已完成列表' => '/wap/productorders/list-completed',
		'经办已终止列表' => '/wap/productorders/list-aborted',
		'接单详情' =>  '/wap/productorders/detail',
		// '接单生成' =>  '/wap/productorders/generate',
		'接单居间确认' =>  '/wap/productorders/orders-confirm',
		'接单协议添加' =>  '/wap/productorders/orders-pact-add',
		'接单协议详情' =>  '/wap/productorders/orders-pact-detail',
		'接单协议确认' =>  '/wap/productorders/orders-pact-confirm',
		
		'接单日志' =>  '/wap/productorders/logs',
	],	
	'接单进度'=>[
		'接单进度添加' =>  '/wap/productorders/orders-process-add',
		'接单进度详情' =>  '/wap/productorders/orders-process-detail',
	],
	'接单经办人'=>[
		'经办人列表' =>  '/wap/productorders/orders-operator-list',
		'分配经办人' =>  '/wap/productorders/orders-operator-set',
		'取消经办人' =>  '/wap/productorders/orders-operator-unset',
	],
	'接单结案'=>[
		'申请结案' =>  '/wap/productorders/orders-closed-apply',
		'结案详情' =>  '/wap/productorders/orders-closed-detail',
		'同意结案' =>  '/wap/productorders/orders-closed-agree',
		'否决结案' =>  '/wap/productorders/orders-closed-veto',
	
	],
	'接单终止'=>[
		'申请终止' =>  '/wap/productorders/orders-termination-apply',
		'终止详情' =>  '/wap/productorders/orders-termination-detail',
		'同意终止' =>  '/wap/productorders/orders-termination-agree',
		'否决终止' =>  '/wap/productorders/orders-termination-veto',
	],
	'评价'=>[
		'评价产品' =>  '/wap/productorders/comment-add',
		'追加评价' =>  '/wap/productorders/comment-additional',
		'评价列表' =>  '/wap/productorders/comment-list',
	],
	'用户(发布方|申请方)'=>[
		'用户信息' =>  '/wap/userinfo/detail',
		'评价列表' =>  '/wap/userinfo/comment-list',
		'评价详情' =>  '/wap/userinfo/comment-detail',
	],
	'通讯录'=>[
		'我的通讯录' =>'/wap/contacts/index',
		'搜索用户' =>  '/wap/contacts/search',
		'添加联系人' =>  '/wap/contacts/apply',
		'删除联系人' =>  '/wap/contacts/del',
	],
	'个人中心'=>[
		'个人详情' =>'/wap/userinfo/info',
		'初次设置密码' =>'/wap/userinfo/setpassword',
		'修改密码' =>'/wap/user/modifypassword',
		'修改绑定手机' =>  '/wap/userinfo/changemobile',
		'验证手机短信码' =>  '/wap/user/checksmscode',
		'发送手机短信码' =>  '/wap/user/smscode',
		'修改头像' =>  '/wap/userinfo/uploadsimg',
		'修改昵称' =>  '/wap/userinfo/nkname',
	],
	'地址管理'=>[
		'我的地址列表' =>  '/wap/address/index',
		'新增地址' =>  '/wap/address/create',
		'编辑地址' =>  '/wap/address/update',
		'地址详情' =>  '/wap/address/info',
		'删除地址' =>  '/wap/address/recy',
		'设置默认地址' =>  '/wap/address/default',
		'获取默认地址' =>  '/wap/address/getdefault',
	],
	'消息'=>[
		'系统消息' =>  '/wap/message/system-list',
		'分组消息' =>  '/wap/message/group-list',
	],
	
  ];
  
$defultval = [
	'接单'=>[
		'接单列表' =>  'page=1&limit=10',
		'接单进行中列表' =>  'page=1&limit=10&type=0',
		'接单已完成列表' =>  'page=1&limit=10&type=0',
		'接单已终止列表' =>  'page=1&limit=10&type=0',
		'经办进行中列表' =>  'page=1&limit=10&type=1',
		'经办已完成列表' =>  'page=1&limit=10&type=1',
		'经办已终止列表' =>  'page=1&limit=10&type=1',
		'接单生成' =>  'applyid=3',
		'接单居间确认' =>  'ordersid=36',
		'接单协议添加' =>  'ordersid=36&files=5,3',
		'接单协议详情' =>  'ordersid=36',
		'接单协议确认' =>  'ordersid=36',
		'接单详情' =>  'applyid=2',
		'接单日志' =>  'ordersid=30',
	],
	"接单进度"=>[
		'接单进度添加' =>  'files=mode.png&type=1&memo=测试进度&ordersid=30',
		'接单进度详情' =>  'processid=23',
	],
	"接单经办人"=>[
		'经办人列表' =>  'ordersid=30',
		'分配经办人' =>  'ordersid=30&operatorIds=5,6',
		'取消经办人' =>  'ordersid=30&ids=1,3',
	],
	"接单结案"=>[
		'申请结案' =>  'applymemo=测试结案申请&price=500000&price2=100000&ordersid=30',
		'结案详情' =>  'closedid=20',
		'同意结案' =>  'resultmemo=测试同意结案&closedid=20',
		'否决结案' =>  'resultmemo=测试否决结案&closedid=20',
	],
	"接单终止"=>[
		'申请终止' =>  'applymemo=测试结案申请&ordersid=30',
		'终止详情' =>  'terminationid=7',
		'同意终止' =>  'resultmemo=测试同意终止&terminationid=7',
		'否决终止' =>  'resultmemo=测试否决终止&terminationid=7',
	],
	'评价'=>[
		'评价产品' =>  'ordersid=30&truth_score=8&assort_score=6&response_score=10&memo=普通评价&files=',
		'追加评价' =>  'ordersid=30&tocommentid=&memo=追加评价&files=',
		'评价列表' =>  'ordersid=30',
	],
	'用户(发布方|申请方)'=>[
		'用户信息' =>  'userid=1&productid=2',
		'评价列表' =>  'userid=5&page=1&limit=10',
		'评价详情' =>  'commentid=12',
	],
	'通讯录'=>[
		'我的通讯录' =>'',
		'搜索用户' =>  'mobile=15000708849',
		'添加联系人' =>  'userid=632',
		'删除联系人' =>  'contactsid=4',
	],
	'个人中心'=>[
		'初次设置密码' =>'password=123456',
		'修改密码' =>'old_password=123456&new_password=123456',
		'修改绑定手机' =>  'oldmobile=15316602556&newmobile=15801834653&oldcode=&newcode=',
		'验证手机短信码' =>  'mobile=15316602556&type=4&code=',
		'发送手机短信码' =>  'mobile=15316602556&type=4',
		'修改头像' =>  'data=base64_image_content&filetype=1&extension=jpg',
		'修改昵称' =>  'nickname=新昵称',
	],
	'地址管理'=>[
		'我的地址列表' =>  '',
		'新增地址' =>  'province=&city=&area=&nickname=&tel=&address=&isdefault=',
		'编辑地址' =>  'id=6&tel=15316602556',
		'地址详情' =>  'id=6',
		'删除地址' =>  'id=6',
		'设置默认地址' =>  '/wap/user/default',
		'获取默认地址' =>  '/wap/user/getdefault',
	],
  ];

  
$array1 = [
	'产品'=>[
		'产品列表'   =>  '/wap/product/index',
		'产品详情' 	 	 =>  '/wap/product/detail',
		'进行中列表' => '/wap/product/list-processing',
		'已完成列表' => '/wap/product/list-completed',
		'已终止列表' => '/wap/product/list-aborted',
		'发布方详情'   	 =>  '/wap/product/product-details',
		'新增发布'    	 =>  '/wap/product/release',
		'草稿保存'    	 =>  '/wap/product/draft',
		'删除发布产品'    	 =>  '/wap/product/product-delete',
		'产品编辑'   	 =>  '/wap/product/edit',
		'产品收藏'   	 =>  '/wap/product/collect',
		'产品取消收藏'   	 =>  '/wap/product/collect-cancel',
		'产品收藏列表'   	 =>  '/wap/product/collect-list',
	],	
	'完善信息'=>[
		'抵押物新增'   	 =>  '/wap/product/mortgage-add',
		'抵押物编辑'   	 =>  '/wap/product/mortgage-edit',
		'抵押物删除'   	 =>  '/wap/product/mortgage-del',
		'抵押物详情'   	 =>  '/wap/product/mortgage-detail',
	],
	'申请'=>[
		'接单方产品申请'   	 =>  '/wap/product/apply',
		'接单方申请取消'   	 =>  '/wap/product/apply-cancel',
		'接单方申请人列表'   =>  '/wap/product/applicant-list',
		'接单方详情'   =>  '/wap/product/apply-details',
		'选择接单方面谈'   	 =>  '/wap/product/apply-chat',
		'选择接单方接单'   	 =>  '/wap/product/apply-agree',
		'取消接单方接单'   	 =>  '/wap/product/apply-veto',
	],
  ];
$defultval1 = [
	'产品'=>[
		'产品列表' =>  'province=0&city=0&district=0&account=0&status=0&page=1&limit=10',
		'产品详情' 	 	 =>  'productid=3',
		'进行中列表' => 'page=1&limit=10',
		'已完成列表' => 'page=1&limit=10',
		'已终止列表' => 'page=1&limit=10',
		'发布方详情'   	 =>  'productid=3',
		'新增发布'     =>  'applymemo=发布&category=3&category_other=30',
		'草稿保存'     =>  'status=10',
		'删除发布产品'    	 =>  'productid=39',
		'产品编辑'     =>  'productid=117&account=1&category_other=10',
		'产品收藏'     =>  'productid=3&create_by=634',
		'产品取消收藏'     => 'productid=3',
		'产品收藏列表'   	 =>  'page=1&limit=10',
		
	],
	'完善信息'=>[
		'抵押物新增'   	 =>  'productid=5&type=3&relation_1=23145',
		'抵押物编辑'   	 =>  'mortgageid=5&type=2',
		'抵押物删除'   	 =>  'mortgageid=7',
		'抵押物详情'   	 =>  'mortgageid=16',
	],
	'申请'=>[
		'接单方产品申请'   	 =>  'productid=3',
		'接单方申请取消'   	 =>  'applyid=13',
		'接单方申请人列表'   =>  'productid=1',
		'接单方详情'   =>  'userid=1&page=1&limit=10',
		'选择接单方面谈'   	 =>  'applyid=13',
		'取消接单方接单'   	 =>  'applyid=13',
		'选择接单方接单'   	 =>  'applyid=13',
	],
  ];
// var_dump($array);
$array = array_merge($array,$array1);
$defultval = array_merge($defultval,$defultval1);
$html = '';
$No=1;
foreach($array as $group => $data){
	$html.="<h2>{$group}</h2>";
	foreach($data as $title => $url){
		$html .="<div class='demodiv' >";
		$html .= $No.".".$title;
		$html .='&nbsp;Url:'.$url.'&nbsp;&nbsp;';
		$html .= Html::a($title, 'javascript:void(0)', ['class'=>'btn' , 'title' => '', 'rel' => $url] );
		$html .='<br/>';
		$html .= Html::input('text', 'params',(isset($defultval[$group][$title])?$defultval[$group][$title]:''),['style'=>'width:100%' , 'title' => '请输入参数；例子a=1&b=2&c=3', 'placeholder'=>"请输入参数；例子a=1&b=2&c=3" ]) ;
		$html .="</div>";
		$No++;
	}
}
?>
<table width='1350px' align=center>
<tr>
<th width='650' style='position:fixed' >测试Token:<input name='token' id='token' size='40' value='6c3c11dc8a478a265fb69ad15920ccd3'/></th>
<th width='700' valign=top>返回</th>
</tr>
<tr>
<td valign=top  style='position:fixed'><div class='postdiv' > <?php echo $html?></div></td>
<td valign=top><div class="resultdiv"></div></td>
</tr>
</table>
<style>

.postdiv{ height:510px;overflow-y:auto;overflow-x:hidden;padding:0 5px;}
.demodiv{border-bottom:1px solid #ccc;width:600px;margin:0 auto 10px;padding:10px;}
.result{border:1px solid #ccc;width:650px;margin-bottom:10px;display:none;word-break:break-all; word-wrap:break-word;}
.close{color:red;float:right;margin-right:10px;cursor:pointer;padding:0 5px;display:block;}
</style>
<script src="/js/jquery-1.11.1.js"></script>
<script>
$(document).ready(function(){
	$('.btn').click(function(){
		var url = $(this).attr('rel')
		var params = $(this).parent().children("input").val()
		var token = "&token="+$("#token").val();
		 $.ajax({
					type: "POST",
					url: url,
					data: params+token,
					dataType: "html",
					success: function(data){ 
					 
						var now = (new Date()).toLocaleString();
						var html = "<div class='result' >"
							+"<p><b>操作时间：</b>"+now+"<span class='close' >X</span></p>"
							+"<p><b>请求URL：</b>"+url+"</p>"
							+"<p><b>请求参数：</b>"+params+"</p>";
							// +"<p><b>返回内容：</b></p><pre>"+data+"</pre>"
							
							if(data.substr(0,1)=='{'||data.substr(0,1)=='['){
								var json=JSON.parse(data);
								html=html+"<p><b>返回内容：</b></p><pre>"+(JSON.stringify(json, null, 4))+"</pre>";
								
							}else{
								html=html+"<p><b>返回内容：</b></p><pre>"+(data)+"</pre>";
							}
							html=html+"</div>";
						$(".resultdiv").prepend(html)
						$(".result").eq(0).slideDown(500)
						// alert(html)
					}
		});
		
	})
	$(document).on("click",'.close',function(){
		$(this).parents(".result").slideUp(500)
	})
	
	var formatJson = function(json, options) {
		var reg = null,
			formatted = '',
			pad = 0,
			PADDING = '    '; // one can also use '\t' or a different number of spaces
	 
		// optional settings
		options = options || {};
		// remove newline where '{' or '[' follows ':'
		options.newlineAfterColonIfBeforeBraceOrBracket = (options.newlineAfterColonIfBeforeBraceOrBracket === true) ? true : false;
		// use a space after a colon
		options.spaceAfterColon = (options.spaceAfterColon === false) ? false : true;
	 
		// begin formatting...
		if (typeof json !== 'string') {
			// make sure we start with the JSON as a string
			json = JSON.stringify(json);
		} else {
			// is already a string, so parse and re-stringify in order to remove extra whitespace
			json = JSON.parse(json);
			json = JSON.stringify(json);
		}
	 
		// add newline before and after curly braces
		reg = /([\{\}])/g;
		json = json.replace(reg, '\r\n$1\r\n');
	 
		// add newline before and after square brackets
		reg = /([\[\]])/g;
		json = json.replace(reg, '\r\n$1\r\n');
	 
		// add newline after comma
		reg = /(\,)/g;
		json = json.replace(reg, '$1\r\n');
	 
		// remove multiple newlines
		reg = /(\r\n\r\n)/g;
		json = json.replace(reg, '\r\n');
	 
		// remove newlines before commas
		reg = /\r\n\,/g;
		json = json.replace(reg, ',');
	 
		// optional formatting...
		if (!options.newlineAfterColonIfBeforeBraceOrBracket) {			
			reg = /\:\r\n\{/g;
			json = json.replace(reg, ':{');
			reg = /\:\r\n\[/g;
			json = json.replace(reg, ':[');
		}
		if (options.spaceAfterColon) {			
			reg = /\:/g;
			json = json.replace(reg, ':');
		}
	 
		$.each(json.split('\r\n'), function(index, node) {
			var i = 0,
				indent = 0,
				padding = '';
	 
			if (node.match(/\{$/) || node.match(/\[$/)) {
				indent = 1;
			} else if (node.match(/\}/) || node.match(/\]/)) {
				if (pad !== 0) {
					pad -= 1;
				}
			} else {
				indent = 0;
			}
	 
			for (i = 0; i < pad; i++) {
				padding += PADDING;
			}
	 
			formatted += padding + node + '\r\n';
			pad += indent;
		});
	 
		return formatted;
	};
})
</script>
