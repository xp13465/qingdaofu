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
                  <p class="mt">您已申请清道夫债管家会员认证,请耐心等待人工审核。</p>
                </div>
                
                
            <img src="/images/su_2.jpg" alt="" class="lc">

         <?php }else if(isset($certi['uid'])&& $certi['uid'] && isset($certi['state']) && $certi['state']==2){?>

            <p class="success">会员资格认证申请失败</p>
                <div class="zc clearfix">
                    <img src="/images/duihao.jpg" height="122" width="113" alt="">
                    <p class="hello1">尊敬的: <span class="color"><?php echo $certi['name']?></span>,您好!</p>
                    <p class="mt">您已申请清道夫债管家会员认证,由于：失败原因是否<a href="<?php echo Url::toRoute('/certification/dex')?>" style="color: #0061ac;">重新认证</a></p>
                </div>
                <br>
           
            <p class="bt">如果你想完整的使用网站功能请进行资格认证</p>
            <img src="/images/su_2.jpg" alt="" class="lc">

        <?php }else if(isset($certi['uid'])&& $certi['uid'] && isset($certi['state']) && $certi['state']==1){ ?>
            <?php exit('<script>location.href = "/certifications/index " </script>')?>
          <?php }else{


            ?>
            <p class="success">注册会员成功</p>
                 <div class="zc clearfix">
                     <img src="/images/duihao.jpg" height="122" width="113" alt="">
                     <p class="hello1">尊敬的: <span class="color"><?php $user = app\models\User::findOne(["id"=>\Yii::$app->user->getId()]);echo $user->mobile;?></span>,您好!</p>
                      <p class="mt">恭喜您注册成功,<!--点击这里<a href="<?php echo Url::toRoute('/publish/publish')?>" style="text-decoration:underline;color:#0065B3;">一键发布</a>。--></p>
                 </div>
                <br>
            <p class="bt">如果你想完整的使用网站功能请进行资格认证　<a href="<?php echo Url::toRoute('/certifications/index')?>"><img src="/images/cg_03.png" alt="" style=""></a></p>
            <div class="lc">
                <img src="/images/blue11.png" alt="">
                <img src="/images/arrowone.png" alt="" style="margin-bottom:15px;">
                <img src="/images/gray2.png" alt="">
                <img src="/images/arrowone.png" alt="" style="margin-bottom:15px;">
                <img src="/images/gray3.png" alt="">
            </div>
        <?php }?>
    </div>
</div>

 <?php $cookies = Yii::$app->request->cookies;
        $values =  $cookies->getValue('name');
if($values == 222){
    ?> 
    <div class="yinss" style='position: absolute;'>
        <div class="yins">
            <div class="yin1">
                <img src="/images/yin1.png" height="290" width="652" alt="">
                <a href="javascript:;" class="skip1">马上开始</a>
                <a href="javascript:;" class="skip">跳过引导</a>
            </div>
            <div class="yin2">
                <img src="/images/yin2.png" height="303" width="841" alt="">
                <a href="javascript:;" class="skip1">下一步</a>
                <a href="javascript:;" class="skip">跳过引导</a>
            </div>
            <div class="yin3">
                <img src="/images/yin3.png" height="306" width="841" alt="">
                <a href="javascript:;" class="skip1">下一步</a>
                <a href="javascript:;" class="skip">跳过引导</a>
            </div>
            <div class="yin4">
                <img src="/images/yin4.png" height="366" width="841" alt="">
                <a href="javascript:;" class="skip1">下一步</a>
                <a href="javascript:;" class="skip">跳过引导</a>
            </div>
            <div class="yin5">
                <img src="/images/yin5.png" height="366" width="814" alt="">
                <a href="javascript:;" class="skip1" id="skip">完成</a>
                <a href="javascript:;" class="skip">跳过引导</a>
            </div>
        </div>
    </div>
	<script>
	$(document).ready(function(){
		$(".yinss").css("height",$("body").height())
	})
	</script>
 <?php }else{ echo '';}?> 
<script>
    $(function(){
        $('.yin1').show();
        $('.skip').click(function(){
            $('.yins').hide();
            $('.yinss').css('background','none');
            $('.yinss').css('display','none');
        })
        $('.skip1').click(function(){
            $(this).parent('div').hide();
            $(this).parent('div').next('div').show('slow');
        })
        $('.yin5 .skip1').click(function(){
            $(this).parent('div').hide();
            $('.yinss').css('background','none');
        })
        $('#skip').click(function(){
            $('.yinss').css('display','none');
        })
    })
</script>
