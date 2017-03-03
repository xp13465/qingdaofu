<?php use yii\helpers\Url;?>
<!-- 顶部开始 -->
<div class="top">
    <div class="top_1 clearfix">
        <div class="left">
            <b><i></i>服务热线：400-855-7022</b>
        </div>
        <div class="right">
            <?php if(\Yii::$app->user->isGuest){?>
            <a href="<?php echo Url::to('/site/signup')?>"><span>成为会员</span></a>
            <a href="<?php echo Url::to('/site/login')?>"><span style="margin-right:0;">立即登录</span></a>
            <?php }else{
                $user = \common\models\User::findOne(['id'=>Yii::$app->user->getId()]); ?>
                <a href="#"><span><?php  echo $user->mobile;?></span></a>
                <a href="<?php echo Url::to('/site/logout')?>"><span style="margin-right:0;">退出</span></a>
            <?php }?>
        </div>
    </div>
</div>
<!-- 顶部结束 -->