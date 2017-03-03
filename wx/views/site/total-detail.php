<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
use yii\helpers\ArrayHelper;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>hamer</title>
<meta charset="utf-8">
<meta name="apple-touch-fullscreen" content="YES">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Expires" content="-1">
<meta http-equiv="pragram" content="no-cache">
<link rel="stylesheet" type="text/css" href="/css/touchslide/main.css">
<link rel="stylesheet" type="text/css" href="/css/touchslide/endpic.css">
<script type="text/javascript" src="/js/touchslide/offline.js"></script>
<meta name="viewport" content="width=640, user-scalable=no, target-densitydpi=device-dpi">
<style>
.price{color:#67FFFA;font-size:44pt;display:block;    font-weight: bold;

transform:scale(1,1.2);
-ms-transform:scale(1,1.2);
-moz-transform:scale(1,1.2);
-webkit-transform:scale(1,1.2);
-o-transform:scale(1,1.2);
}
.price_01{color:#615997;font-size:20px;position:absolute; top: 8%;  padding-left: 22%}
.price_1{color:yellow;margin-top: 20%;font-size:40px;display:block;text-align:center;}
.price_02{border-radius:50px;width:40%;height:50px;margin-left:30%;font-size:25px;background:#dede2a;color:#000;}
.price_03{width:50%;height:45px;margin-left:20%;margin-top:8%;font-size:20px;color:#c1c3d6;}
.price_2{padding-left: 25%;padding-top:5%;height:100px;font-size:35px; background:url(../images/total_gg.png) no-repeat center center;}
.price_3{padding-left: 25%;padding-top:5%;height:100px;font-size:35px; background:url(../images/total_gg.png) no-repeat center center;}
.price_4{padding-left: 25%;padding-top:5%;height:100px;font-size:35px; background:url(../images/total_gg.png) no-repeat center center;}
</style>
</head>

<body class="s-bg-ddd pc no-3d" style="/* background-color:#201e2c; */-webkit-user-select: none;">
    
	<section class="u-arrow">
        <p class="css_sprite01"></p>
    </section>
    <section class="p-ct transformNode-2d" style="height: 907px;">
        <div class="translate-back" style="height: 907px;">
            <div class="m-page m-fengye" data-page-type="info_pic3" data-statics="info_pic3" style="height:100%;">
                <div class="page-con lazy-finish" data-position="50% 50%" data-size="cover" style="background-image: url(/images/total_1.png); background-size: cover; height: 909px; background-position: 50% 50%;">
				
				
				</div>
            </div>
            <div class="m-page m-bigTxt f-hide" data-page-type="bigTxt" data-statics="info_list" style="height:100%;">
                <div class="page-con j-txtWrap lazy-finish" data-position="50% 50%" data-size="cover" style="background-image: url(/images/total_2.png); background-size: cover; background-position: 50% 50%;">
                <span class='price price_01'>截至<?=date("Y年m月d日",time())?>，清道夫债权总额</span>
				<span class='price price_1' ><?=round($sum/10000/10000,1)?>亿</span>
				<button class="price_02">债权总额</button>
                <p class="price_03">累计保全金额(元)</p>
                <span class='price price_2' ><?=number_format ($total['Baohan']+$total['Baoquan'])?></span>
				<p class="price_03">累计清收金额（元）</p>
                <span class='price price_3' ><?=number_format ($total['Qingshou'])?></span>
				<p class="price_03">累计诉讼金额（元）</p>
                <span class='price price_4' ><?=number_format ($total['Susong'])?></span>
				
				</div>
            </div>
             
        </div>
    </section>
    <section class="u-pageLoading">
        <img src="/js/touchslide/images/load.gif" alt="loading">
    </section>
    <script src="/js/touchslide/init.mix.js" type="text/javascript" charset="utf-8"></script>
    <script src="/js/touchslide/coffee.js" type="text/javascript" charset="utf-8"></script>
    <script src="/js/touchslide/99_main.js" type="text/javascript" charset="utf-8"></script>
	<?php if($type=="WEB"){?>
	 <div style="height:50px;width:50px;position:fixed;bottom:50px;z-index:99999;">
		<a href="<?php echo yii\helpers\Url::toRoute('/site/index')?>"><img src="/images/back_home.png"></a>
	</div> 
	<?php }?>
	<script type="text/javascript">
		$(document).ready(function () {
			$('.icon-back').click(function () {
				history.go(-1);
			});
		});
	</script>
</body>
</html>