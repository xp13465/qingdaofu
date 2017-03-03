<!-- banner 开始-->
<style>
    body{ background:#f5f5f5;}
</style>
<div class="g_banner">
    <img src="../images/guide_banner.png" height="360" width="1920" alt="">
</div>
<!-- banner 结束-->

<!-- 内容开始-->
<div class="guides clearfix">
   <div class="g_left fl">
       <img src="/images/xin1.png" alt="">
       <!--<a href="http://vipwebchat.shifa.tq.cn/sendmain.jsp?admiuin=9709839&uin=9710064&action=chat&tag=&ltype=1&rand=88273560580527198&iscallback=1&agentid=0&comtimes=34&preuin=9710068&RQF=5&RQC=-1&page_templete_id=40244&is_message_sms=0&is_send_mail=0&isAgent=0&sort=2&style=2&page=&localurl=http://www.zcb2016.com/homepage/homepages&spage=&nocache=0.6720186065060061" target="_blank"><img src="/images/xin2.png" alt=""></a>-->
       <img src="/images/xin3.png" alt="">
   </div>
   <div class="g_right fr">
       <h4>新手帮助</h4>
       <div class="ass">
           <a href="<?php echo yii\helpers\Url::toRoute('/site/signup')?>">
               <img src="/images/xin4.png" alt="">
               <p>注册</p>
           </a>
           <a href="<?php if(\Yii::$app->user->getId()){echo yii\helpers\Url::toRoute('/user/security');}else{echo yii\helpers\Url::toRoute('/site/forgetpassword');}?>">
               <img src="/images/xin5.png" alt="">
            
               <p>找回密码</p>
           </a>
           <a href="<?php if(\Yii::$app->user->getId()){echo yii\helpers\Url::toRoute('/certifications/index');}else{echo yii\helpers\Url::toRoute('/site/login');}?>">
               <img src="/images/xin6.png" alt="">
               <p>身份认证</p>
           </a>
           <!--<a href="<?php if(\Yii::$app->user->getId()){echo yii\helpers\Url::toRoute('/publish/publish');}else{echo yii\helpers\Url::toRoute('/site/login');}?>">
               <img src="/images/xin7.png" alt="">
               <p>发布</p>
           </a><a href="<?php if(\Yii::$app->user->getId()){echo yii\helpers\Url::toRoute('/order/index');}else{echo yii\helpers\Url::toRoute('/site/login');}?>">
               <img src="/images/xin8.png" alt="">
               <p>接单</p>
           </a>-->
       </div>
   </div>  
</div>
