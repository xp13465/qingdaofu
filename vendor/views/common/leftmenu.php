<div class="content_menu">
    <div class="menu_top">
        <div class="m_left fl">
             <img src="/images/touxiang.png" height="90" width="90" alt="">
        </div>
        <div class="niname fr">
            <div class="niname">
                <span class="color">您 好 ! <br /><b><?php $user = app\models\User::findOne(['id'=>\Yii::$app->user->getId()]); if($user){echo $user->mobile;}else{echo '';} ?></b></span>
            </div>
        </div>
    </div>
    <div class="menu_center">
        <ul>
        <li class="xiaoxi">
            <a href="<?php echo \yii\helpers\Url::to('/message/unread')?>"><span>系统消息</span><i class="display"><em><?php echo Yii::$app->db->createCommand('select count(*) from zcb_message where isRead = 0 and belonguid = '.\Yii::$app->user->getId())->queryScalar()?></em></i></a>
        </li>
        <li class="info2">
            <span class="fb3">发布管理<i></i></span>
            <ol id="hover">
                <li><a href="<?php echo \Yii\helpers\Url::to('/publish/publish')?>">发布信息</a></li>
                <li><a href="<?php echo \Yii\helpers\Url::to('/list/release')?>">查看信息</a></li>
            </ol>
        </li>
        <li>
            <a href="<?php echo \Yii\helpers\Url::to('/order/ordersave')?>">接单管理</a>
        </li>
        <li>
            <a href="<?php echo \Yii\helpers\Url::to('/certification/index')?>">认证管理</a>
        </li>
        <li><a href="<?php echo \Yii\helpers\Url::to('/user/security')?>">安全中心</a></li>
    </ul>
    </div>
</div>