<style>
	#error,.ig{margin:0;padding:0;}
	#error,.ig{width:100%;/*border-top:1px solid #ccc;*/overflow:hidden;}
	#error .ig_mid{width:1000px;margin:0 auto;}
	#error .ig-l{float:left;}
	#error .img01{width:465px;height:305px;margin:76px 0 0 160px;}
	#error .img02{width:200px;height:192px;margin-bottom:30px;display:block;margin-left:95px;}
	#error .ig-r{float:right;padding: 267px 90px 0 0;color:#0065b3;}
	#error .ig-r h2{padding:0 0 15px 60px;}
	#error .ig-r p{font-size:18px;font-family:"微软雅黑";line-height:28px;padding-left:60px;}
	#error .ig-r a{width:120px;height:30px;display:inline-block;border:1px solid #0065b3;text-align:center;line-height:30px;text-decoration:none;border-radius:6px;color:#0065b3;margin-top:20px;font-size:18px;}
	#error .ig-r a:hover{background-color: #0065b3;color:#fff;}
</style>
		
<div class="ig" id = "error">
	<div class="ig_mid">
		<div class="ig-l">
			<img src="/images/sorry.png" alt="" class="img01" />
			<img src="/images/fish.png" alt="" class="img02" />
		</div>
		
		<div class="ig-r">
			<!--<p style="color:red"><?=$message?></p>-->
			<h2>您还可以：</h2>
			<p>1、返回上一页</p>
			<p>2、返回到首页</p>
			<a href="<?php echo Yii::$app->getUser()->getReturnUrl();?>">返回到上一页</a>
			<a href="<?php echo Yii::$app->getHomeUrl();?>">返回到首页</a>
		</div>
	</div>
</div>	
