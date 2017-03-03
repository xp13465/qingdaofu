<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <style>
        .as { margin:20px 50px;width:300px;}
        .as a{ font-size:14px; display:block; line-height:30px; color:#333;}
        .as a:hover{ color:#0065b3;}
    </style>
</head>
<body>
     <!-- 首页-->
     <div class="as">
        <a href ="<?php echo yii\helpers\Url::toRoute(['/'])?>">首页</a>
        <a href="<?php echo yii\helpers\Url::toRoute('/site/help')?>">新手帮助</a>
        <a href="<?php echo yii\helpers\Url::toRoute('/homepage/newslist')?>">新闻列表</a><!--新闻的-->
        <a href="<?php echo yii\helpers\Url::toRoute('/homepage/business')?>">业务流程</a>
        <a href="<?php echo yii\helpers\Url::toRoute('/protocol/falvdeclaration')?>">法律声明</a>
        <!--资产列表-->
        <a href="<?php echo yii\helpers\Url::toRoute('/capital/list')?>">产品列表</a>
        <a href="<?php echo yii\helpers\Url::toRoute(['/capital/list','cate'=>1])?>">融资</a>
        <a href="<?php echo yii\helpers\Url::toRoute(['/capital/list','cate'=>2])?>">清收</a>
        <a href="<?php echo yii\helpers\Url::toRoute(['/capital/list','cate'=>3])?>">诉讼</a>
        <!--产品服务-->
        <a href="<?php echo yii\helpers\Url::toRoute('/products/products')?>">产品服务</a>
        <!--关于我们-->
        <a href="<?php echo yii\helpers\Url::toRoute('/aboutus/intro')?>">关于我们</a>
        <a href="<?php echo yii\helpers\Url::toRoute('/aboutus/intro')?>">公司简介</a>
        <a href="<?php echo yii\helpers\Url::toRoute('/aboutus/serviceclause')?>">服务协议</a>
        <!--合同连接-->
        <a href="<?php echo yii\helpers\Url::toRoute("/protocol/mediacyfinancing")?>" target ="_blank">《居间协议（融资人）》</a>
        <a href="<?php echo yii\helpers\Url::toRoute("/protocol/mediacyentrust")?>" target ="_blank">《居间协议（清收委托人）》</a>
        <a href="<?php echo yii\helpers\Url::toRoute("/protocol/mediacycollection")?>" target ="_blank">《居间协议（清收公司）》</a>
        <a href="<?php echo yii\helpers\Url::toRoute("/protocol/mediacyinvestment")?>" target ="_blank">《居间协议（投资人）》</a>
        <a href="<?php echo yii\helpers\Url::toRoute("/protocol/mediacylawer")?>" target ="_blank">《居间协议（法律服务-律师事务所）》</a>
        <a href="<?php echo yii\helpers\Url::toRoute("/protocol/mediacylawentrust")?>" target ="_blank">《居间协议（法律服务-委托人）》</a>
        <a href="<?php echo yii\helpers\Url::toRoute('/aboutus/helpcenter')?>">帮助中心</a>
        <!--分页连接-->
        <a href="<?php echo yii\helpers\Url::toRoute('/aboutus/feedback')?>">意见反馈</a>
        <a href="<?php echo yii\helpers\Url::toRoute('/aboutus/cooperation')?>">商务合作</a>
        <a href="<?php echo yii\helpers\Url::toRoute('/aboutus/contactus')?>">联系我们</a>
         <!--产调查询-->
        <a href="<?php echo yii\helpers\Url::toRoute('/produce/produces')?>" >产调查询</a>
     </div>
</body>
</html>