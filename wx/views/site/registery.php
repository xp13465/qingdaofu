<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;?>


<?=wxHeaderWidget::widget(['title'=>'注册','gohtml'=>''])?>
<section>
    <?php \yii\widgets\ActiveForm::begin(['id'=>'reg']);?>
    <div class="phone_num">
        <?=Html::input('text','mobile','',['placeholder'=>'请输入您的手机号码','class'=>'input01'])?>
        <div class="yz">
            <?=Html::input('text','validateCode','',['placeholder'=>'输入验证码','class'=>'input01'])?>
            <?=Html::tag('a','获取验证码',['href'=>'javascript:void(0)','class'=>'validateCode'])?>
        </div>
        <div class="password">
            <?=Html::input('password','password','',['placeholder'=>'设置密码'])?>
            <?=Html::tag('a','显示密码',['href'=>'javascript:void(0)','class'=>'displaypass'])?>
        </div>
    </div>
    <div class="login">
        <?=Html::tag('a','注册',['href'=>'javascript:void(0)','class'=>'denglu'])?>
    </div>
    <div class="rem01">
        <span class="gou"></span>
        <?=Html::hiddenInput('isAgree','1')?>
        <span class="read">我已阅读并同意</span>
        <?=Html::tag('a','注册协议',['href'=>yii\helpers\Url::toRoute('/site/agreement')])?>
    </div>
    <?php \yii\widgets\ActiveForm::end();?>
</section>

<script type="text/javascript">
    $(document).ready(function () {
        curCount = 0;
        curPassCount = 0;
        $('.validateCode').click(function () {
            var mobile = $('input[name="mobile"]').val();
            if(mobile == ""){
                alert("请先输入手机号码");
                return false;
            }
            if(curCount == 0) {
                $.ajax({
                    url: '<?=\yii\helpers\Url::toRoute('/site/getsms')?>',
                    type: 'post',
                    data: {mobile: mobile},
                    dataType: 'json',
                    success: function (json) {
                        if (json.code == '0000') {
                            alert('验证码发送成功');
                            $(".validateCode").attr("disabled", "disabled");
                            $(".validateCode").addClass("huoqudisa");
                            curCount = 60;
                            InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
                        } else {
                            alert(json.msg);
                        }
                    }
                });
            }
        });
        $('.displaypass').click(function () {
            $('input[name="password"]').attr('type','text');
            curPassCount = 2;
            InterValObj2 = window.setInterval(displayPass, 1000); //启动计时器，1秒执行一次
        });

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
        $('.denglu').click(function () {
            var mobile = $('input[name="mobile"]').val();
            var password = $('input[name="password"]').val();
            var validateCode = $('input[name="validateCode"]').val();
            $.ajax({
                url:'<?=\yii\helpers\Url::toRoute('/site/registery')?>',
                type:'post',
                data:{mobile:mobile,password:password,validatecode:validateCode},
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
                        alert('注册成功')
                        $.ajax({
                            url: '<?=\yii\helpers\Url::toRoute('/site/login')?>',
                            type: 'post',
                            data: {mobile: mobile, password: password},
                            dataType: 'json',
                            success: function (json) {
                                if (json.code == '0000') {
                                    window.location = "<?=\yii\helpers\Url::toRoute('/site/index')?>";
                                } else {
                                    alert(json.msg);
                                }
                            }
                        })
                    }else{
                        alert(json.msg);
                    }
                }
            });
        });
    });

</script>