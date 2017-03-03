<?php if(yii::$app->request->get('category') == 1){
     $com = \common\models\FinanceProduct::findOne(['id'=>yii::$app->request->get('id')]);
}else{
     $com = \common\models\CreditorProduct::findOne(['id'=>yii::$app->request->get('id')]);
}?>
<div class="rzs">
    <h2>融资发布</h2>
    <h1>恭喜您! 发布成功啦!</h1>
    <p>您发布的 "<span><?php if($com['category'] == 1){ echo '融资';} else if($com['category'] == 2){ echo '清收';}else{echo '诉讼';}?><?php echo isset($com['money'])?$com['money']:''?>万</span>" 已经发布到 <span>首页-产品列</span>表科目下</p>
    <div class="look1">
        <a href="<?php echo yii\helpers\Url::toRoute('/capital/list');?>">立即查看</a>
        <a href="<?php echo yii\helpers\Url::toRoute('/publish/publish'); ?>">再发一条</a>
    </div>
    <div class="wei">
        <img src="/images/erweima.png" height="151" width="151" alt="" />
        <ol>
            <span>这个消息我只告诉您呢</span>
            <li>1.我们已经帮您免费生成融资页</li>
            <li>2.有合适的接单就赶紧联系奥</li>
            <li>3.虽然着急,但也要了解清楚再决定呢</li>
            <li>4.扫一扫,可以拉产调额</li>
        </ol>
    </div>
</div>