<?php
use yii\helpers\Url;
?><!-- 导航开始-->
<div class="nav">
    <div class="nav_1 clearfix">
        <div class="logo">
            <a href="<?php echo yii\helpers\Url::to('/homepage/homepages')?>" class="display"><h1>清道夫债管家</h1></a>
        </div>
        <div class="nav_menu">
            <p class="color">服务热线：400-855-7022　　<a href="<?php echo Url::to('/homepage/homemap')?>">进入地图</a></p>
            <ul class="clearfix">
                <li style="list-style:none;"><a href="<?php echo Url::to('/homepage/homepages')?>">首页</a></li>
                <li><a href="<?php echo Url::to('/products/products')?>">产品服务</a></li>
                <li><a href="<?php echo Url::to('/capital/list')?>">产品列表</a></li>
                <li><a href="<?php echo \yii\helpers\Url::to('/aboutus/intro')?>">关于我们</a></li>
                <li style="margin-right:0"><a href="<?php echo Url::to('/message/read')?>"  style="margin-right:0">用户中心</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- 导航结束-->
<script type="text/javascript">
    $('ul li a').each(function(){
        if($($(this))[0].href==String(window.location))
            $(this).parent().addClass('current');
    });
</script>