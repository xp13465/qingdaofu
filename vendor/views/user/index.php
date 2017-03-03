<?php
use yii\helpers\Url;
?>
<div class="content_right">
    <div class="content_zc">
        <?php if(isset($certi['uid'])&& $certi['uid'] && isset($certi['state']) && $certi['state']==0 ){?>

            <p class="success">会员资格认证</p>
                <div class="zc clearfix">
                   <img src="/images/duihao.jpg" height="122" width="113" alt="">
                   <p class="hello1">尊敬的: <span class="color"><?php echo $certi['name']?></span>,您好!</p>
                  
                </div>
                <br>
                <p class="mt">您已申请清道夫债管家会员认证,请耐心等待人工审核。</p>
            <p class="bt">如果你想完整的使用网站功能请进行资格认证</p>
            <img src="/images/su_2.jpg" alt="" class="lc">

         <?php }else if(isset($certi['uid'])&& $certi['uid'] && isset($certi['state']) && $certi['state']==2){?>

            <p class="success">会员资格认证申请失败</p>
                <div class="zc clearfix">
                    <img src="/images/duihao.jpg" height="122" width="113" alt="">
                    <p class="hello1">尊敬的: <span class="color"><?php echo $certi['name']?></span>,您好!</p>
                   
                </div>
                <br>
            <p class="mt">您已申请清道夫债管家会员认证,由于：失败原因是否<a href="<?php echo Url::to('/certification/dex')?>" style="color: #0061ac;">重新认证</a></p>
            <p class="bt">如果你想完整的使用网站功能请进行资格认证</p>
            <img src="/images/su_2.jpg" alt="" class="lc">

        <?php }else if(isset($certi['uid'])&& $certi['uid'] && isset($certi['state']) && $certi['state']==1){ ?>
            <?php exit('<script>location.href = "/certification/index " </script>')?>
          <?php }else{


            ?>
            <p class="success">注册会员成功</p>
                 <div class="zc clearfix">
                     <img src="/images/duihao.jpg" height="122" width="113" alt="">
                     <p class="hello1">尊敬的: <span class="color"><?php $user = app\models\User::findOne(["id"=>\Yii::$app->user->getId()]);echo $user->mobile;?></span>,您好!</p>
                    
                 </div>
                <br>
            <p class="mt">您已注册清道夫债管家会员成功!</p>
            <p class="bt">如果你想完整的使用网站功能请进行资格认证　　<a href="<?php echo Url::to('/certification/dex')?>" style="color: #0061ac;">立即认证>></a></p>
            <img src="/images/su_2.jpg" alt="" class="lc">
        <?php }?>

    </div>
</div>