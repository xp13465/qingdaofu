<?php 
use yii\helpers\Html;
use yii\helpers\Url;
$array = [
	'产品'=>[
		'产品列表'   =>  '/wap/product/index',//*
		'产品详情' 	 	 =>  '/wap/product/detail',//*
		'进行中列表' => '/wap/product/list-processing',//*
		'已完成列表' => '/wap/product/list-completed',//*
		'已终止列表' => '/wap/product/list-aborted',//*
		'发布方基本信息详情'   	 =>  '/wap/product/product-details',//*
		'发布方查询产品状态详情'   	 =>  '/wap/product/product-deta',
		'新增发布'    	 =>  '/wap/product/release',//*
		'草稿保存'    	 =>  '/wap/product/draft',//*
		'删除发布产品'    	 =>  '/wap/product/product-delete',//*
		'产品编辑'   	 =>  '/wap/product/edit',//*
		'产品收藏'   	 =>  '/wap/product/collect',//*
		'产品取消收藏'   	 =>  '/wap/product/collect-cancel',//*
		'产品收藏列表'   	 =>  '/wap/product/collect-list',//*
		'协议数据'   	 =>  '/wap/product/agreement',
		'单条数据查询'   =>  '/wap/product/view',//*
	],	
	'完善信息'=>[
		'抵押物新增'   	 =>  '/wap/product/mortgage-add',//*
		'抵押物编辑'   	 =>  '/wap/product/mortgage-edit',//*
		'抵押物删除'   	 =>  '/wap/product/mortgage-del',//*
		'抵押物详情'   	 =>  '/wap/product/mortgage-detail',
	],
	'申请'=>[
		'接单方产品申请'   	 =>  '/wap/product/apply',//*
		'接单方申请取消'   	 =>  '/wap/product/apply-cancel',//*
		'接单方申请人列表'   =>  '/wap/product/applicant-list',//*
		'接单方详情'   =>  '/wap/product/apply-details',
		'选择接单方面谈'   	 =>  '/wap/product/apply-chat',//*
		'选择接单方接单'   	 =>  '/wap/product/apply-agree',//*
		'取消接单方接单'   	 =>  '/wap/product/apply-veto',//*
	],
	'省、市、区份\车系'=>[
		'省份'   	 =>  '/wap/product/province',//*
		'城市'   	 =>  '/wap/product/city',//*
		'市区'   =>  '/wap/product/district',//*
		'车类型'   =>  '/wap/product/brand',//*
		'车系'   	 =>  '/wap/product/audi',//*
	],
  ];
$defultval = [
	'产品'=>[
		'产品列表' =>  'province=0&city=0&district=0&account=0&status=0&page=1&limit=10',
		'产品详情' 	 	 =>  'productid=3',
		'进行中列表' => 'page=1&limit=10',
		'已完成列表' => 'page=1&limit=10',
		'已终止列表' => 'page=1&limit=10',
		'发布方基本信息详情'   	 =>  'productid=3',
		'发布方查询产品状态详情'   	 =>  'productid=3',
		'新增发布'     =>  'applymemo=发布&category=3&category_other=30',
		'草稿保存'     =>  'status=10',
		'删除发布产品'    	 =>  'productid=39',
		'产品编辑'     =>  'productid=117&account=1&category_other=10',
		'产品收藏'     =>  'productid=3&create_by=634',
		'产品取消收藏'     => 'productid=3',
		'产品收藏列表'   	 =>  'page=1&limit=10',
		'协议数据'   	 =>  'productid=69',
		'单条数据查询'   =>  'productid=69',
		
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
	'省、市、区份\车系'=>[
		'省份'   	 =>  '',
		'城市'   	 =>  '',
		'市区'   =>  '',
		'车类型'   =>  '',
		'车系'   	 =>  '',
	],
  ];
 //var_dump($result);
$html = '';
foreach($array as $group => $data){
	$html.="<h2>{$group}</h2>";
	foreach($data as $title => $url){
		$html .="<div class='demodiv' >";
		$html .= $title;
		$html .='&nbsp;Url:'.$url.'&nbsp;&nbsp;';
		$html .= Html::a($title, 'javascript:void(0)', ['class'=>'btn' , 'title' => '', 'rel' => $url] );
		$html .='<br/>';
		$html .= Html::input('text', 'params',(isset($defultval[$group][$title])?$defultval[$group][$title]:''),['style'=>'width:100%' , 'title' => '请输入参数；例子a=1&b=2&c=3', 'placeholder'=>"请输入参数；例子a=1&b=2&c=3" ]) ;
		$html .="</div>";
	}
}
?>
<table width='1350px' align=center>
<tr>
<th width='650'   style='position:fixed'>测试Token:<input name='token' id='token' size='40' value='6c3c11dc8a478a265fb69ad15920ccd3'/></th>
<th width='700'  valign=top>返回</th>
</tr>
<tr>
<td valign=top  style='position:fixed'><div class='postdiv' > <?php echo $html?></div></td>
<td valign=top><div class="resultdiv"></div></td>
</tr>
</table>
<style>

.postdiv{ height:410px;overflow-y:auto;overflow-x:hidden;padding:0 5px;}
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
