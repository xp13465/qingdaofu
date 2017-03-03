<?php 
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="services">
	<div class="ban"></div>
	<div class="gory">
	  <div class="cate">
		<ul>
		  <li>
			<p class="ico1"></p>
			<p class="title">免费咨询</p>
			<p class="text">微信扫描公众号</p>
			<p class="text">“清道夫债管家”</p>
			<p class="text">关注后提交待解决问题</p>
			<div class="btn qrcode"> <a>立即扫描</a> </div>
		  </li>
		  <li>
			<p class="ico2"></p>
			<p class="title">预约律师</p>
			<p class="text">根据需求预约专业律师</p>
			<p class="text">面对面交谈或电话咨询</p>
			<p class="text"></p>
			<div class="btn"> <a href="<?=Url::toRoute(["services/reservation"])?>">立即预约</a> </div>
		  </li>
		  <li>
			<p class="ico3"></p>
			<p class="title">快捷文书</p>
			<p class="text">专业律师为您提供诉讼</p>
			<p class="text">文书解决方案</p>
			<p class="text"></p>
			<div class="btn"><a href="<?=Url::toRoute(["services/instrument"])?>">立即提供</a> </div>
		  </li>
		  <li>
			<p class="ico4"></p>
			<p class="title">合同起草</p>
			<p class="text">根据您的需求,匹配专</p>
			<p class="text">业律师为您起草合同</p>
			<p class="text"></p>
			<div class="btn"><a href="<?=Url::toRoute(["services/agreement"])?>">立即起草</a> </div>
		  </li>
		</ul>
	  </div>
	</div>
	<div class="steps">
	  <div class="step">
		<div class="step-one">
		  <ul>
			<li class="line01"></li>
			<li class="tep01"></li>
			<li  class="line02"></li>
			<li  class="tep02"></li>
			<li  class="line02"></li>
			<li  class="tep03"></li>
			<li  class="line01"></li>
		  </ul>
		</div>
		<div class="step-two">
		  <ul>
			<li class="bigger">
			  <p class="title">服务选择</p>
			  <p class="text">根据您的实际情况</p>
			  <p class="text">选择相对应的服务类型</p>
			</li>
			<li class="small">
			  <p class="title">填写信息</p>
			  <p class="text">填写您反馈的信息</p>
			  <p class="text">并留下姓名以及联系方式</p>
			</li>
			<li  class="middle">
			  <p class="title">专业服务</p>
			  <p class="text">根据您反馈的信息</p>
			  <p class="text">我们安排专业律师为您提供免费咨询服务</p>
			</li>
		  </ul>
		</div>
	  </div>
	</div>
</div>
<script>
$(document).ready(function(){
	$(".qrcode").click(function(){
		layer.alert('<div class="weixin"><p class="top">扫一扫，关注微公众号</p><p class="img"><img src="/bate2.0/images/dipian.png"></p><p class="til">1.打开微信扫一扫功能</p><p class="tex">2.扫描并关注清道夫微信公众号</p></div>',  {
			tips: [1, '#33b3f5'],
			scrollbar :false,
			title :false,
			btn :[],
			time: 5000
		});
	 });
})

</script>
<style>
.layui-layer-setwin .layui-layer-close2 {
	position: absolute;
	right: -8px;
	top: 5px;
	width: 30px;
	height: 30px;
	background:url(/bate2.0/images/close1.png) no-repeat;
}
.layui-layer-setwin .layui-layer-close2:hover{background-position:right 10px top 0px;}
</style>