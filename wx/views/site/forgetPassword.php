<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
?>
<?=wxHeaderWidget::widget(['title'=>'忘记密码','gohtml'=>''])?>
<section>
    <?php \yii\widgets\ActiveForm::begin(['id'=>'forget'])?>
    <div class="yan_zhen">
        <p></p>
    </div>
    <div class="phone_num">
        <div class="yz">
            <?= Html::input('text','mobile','',['class'=>'input01','placeholder'=>'输入手机号']);?>
            <?= Html::tag('a','获取验证码',['href'=>"javascript:void(0)",'class'=>'validateCode'])?>
        </div>
        <div class="yz">
            <?= Html::input('text','Verification','',['class'=>'input01','placeholder'=>'验证码']);?>
        </div>
        <div class="password">
            <?= Html::input('password','password','');?>
            <?= Html::tag('a','显示密码',['href'=>'javascript:void(0)','placeholder'=>'显示密码','class'=>'pass']);?>
        </div>
    </div>
    <div class="login">
        <?= Html::tag('a','重置密码',['href'=>'javascript:void(0)','class'=>'denglu'])?>
    </div>
    <?php \yii\widgets\ActiveForm::end();?>
</section>

<script type="text/javascript">
    $(document).ready(function(){
        // $('input[name=Verification]').focus(function(){
            // var mobile = $('input[name=mobile]').val();
            // $('.yan_zhen').children('p').html("验证码已经发送到"+mobile.substring(0,3)+"xxxx"+mobile.substring(7)+"上");
        // });
        curCount = 0;
        curPassCount = 0;
        $('.validateCode').click(function(){
              var mobile = $('input[name=mobile]').val();
              $.ajax({
                  url:"<?=\yii\helpers\Url::toRoute('/site/getsms')?>",
                  type:'post',
                  data:{mobile:mobile},
                  dataType:'json',
                  success:function(json){
                      if(json.code == '0000'){
						  $('.yan_zhen').children('p').html("验证码已经发送到"+mobile.substring(0,3)+"xxxx"+mobile.substring(7)+"上");
                          $(".validateCode").attr("disabled","disabled");
                          $(".validateCode").addClass("huoqudisa");
                          curCount = 60;
                          InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
                      }else{
                          alert(json.msg);
                      }
                  }
              })
        });
        $('.pass').click(function(){
            $('input[name=password]').prop('type','text');
            curPassCount = 2;
            InterValObj2 = window.setInterval(displayPass, 1000); //启动计时器，1秒执行一次
        })

        function displayPass(){
            if(curPassCount == 0){
                $('input[name="password"]').attr('type','password');
            }else{
                curPassCount--;
            }
        }
        function SetRemainTime() {
            if (curCount == 0) {
                window.clearInterval(InterValObj);//停止计时器
                $(".validateCode").removeAttr("disabled");//启用按钮
                $(".validateCode").removeClass("huoqudisa");
                $(".validateCode").html('获取验证码');
            }
            else {
                curCount--;
                $(".validateCode").html(curCount);
            }
        }
        $('.denglu').click(function(){
            $.ajax({
                url:"<?php echo yii\helpers\Url::toRoute('/site/forget')?>",
                type:'post',
                data:$('#forget').serialize(),
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
                        window.location = "<?php echo yii\helpers\Url::toRoute('/site/index');?>";
                    }else{
                        alert(json.msg);
                    }
                }
            })
        })
    })
</script>
