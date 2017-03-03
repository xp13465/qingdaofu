<div class="banner">
    <span></span>
    <span class="ig01"></span>
    <span class="ig02"></span>
    <span class="ig03"></span>
    <span class="ig04"></span>
</div>
<form class="modifyphone">
<div class="login login2">
    <span>找回密码</span>
    <hr/>
    <div class="gain">
    <br>
        手机号码　<input type="text" name="mobile"/>
        <a href="#" class="displays" >获取</a><b name="mobile"></b>
    </div>
    <div>
        验证码　　<input type="text" name="verifyCode"/><b name="dis"></b>
    </div>
    <div class="gain gain1">
        输入新密码<input type="password" name="New" /><b name="news"></b>
    </div>
    <div>
        确认新密码<input type="password" name="passwords" /><b name="pas"></b>
    </div>
    <div class="login_l">
        <a href="javascript:void(0);" id="reset">确定重置</a>
    </div>
</div>
    </form>
<script type="text/javascript">
    $(document).ready(function() {
        var InterValObj; //timer变量，控制时间
        var count = 60; //间隔函数，1秒执行
        var curCount;//当前剩余秒数
        $('.displays').click(function () {
            var mobile = $('input[name=mobile]').val();
            if (mobile == '') {
                $('b[name=mobile]').css('color', 'red');
                $('b[name=mobile]').html('请输入手机号码');
            }
            curCount = count;
            if (!(/^13\d{9}$/g.test(mobile)) && !(/^15\d{9}$/g.test(mobile)) && !(/^18\d{9}$/g.test(mobile)) && !(/^17\d{9}$/g.test(mobile)) && !(/^14\d{9}$/g.test(mobile))) {
                $('b[name=mobile]').css('color', 'red');
                $('b[name=mobile]').html('请输入格式正确的手机号码');
            }
            var url = "/site/mobilesms";
            __send(url, mobile);
        })

        function __send(url, mobile) {
            $.ajax(
                {
                    dataType: 'json',
                    type: 'post',
                    url: url,
                    data: {mobile: mobile},
                    success: function (json) {
                        if (json.code == '0000') {
                            $(".displays").attr("disabled", "disabled");
                            $(".displays").addClass("huoqudisa");
                            InterValObj = window.setInterval(SetRmainTime, 1000);
                        } else {
                            alert(json.msg);
                        }
                    },
                    error: function () {
                        $('b[name=dis]').css('color', 'red');
                        $('b[name=dis]').html('获取验证码失败，请刷新页面重新！');
                    }
                }
            )
        }

        function SetRmainTime() {
            if (curCount == 0) {
                window.clearInterval(InterValObj);//停止计时器
                $(".displays").removeAttr("disabled");//启用按钮
                $(".displays").removeClass("huoqudisa");
                $(".displays").html('获取');
            }
            else {
                curCount--;
                $(".displays").html(curCount);
            }
        }

        $('#reset').click(function () {
            var newphone = $('input[name=newphone]').val();
            var verifyCode = $('input[name=verifyCode]').val();
            var news = $('input[name=New]').val();
            var passwords = $('input[name=passwords]').val();
            var flag = true;
            if (!verifyCode) {
                flag = false;
                $('b[name=dis]').css('color', 'red');
                $('b[name=dis]').html('请输入验证码');
            }
            if (!news) {
                flag = false;
                $('b[name=news]').css('color', 'red');
                $('b[name=news]').html('请输入新密码');
            }
            if (!passwords) {
                flag = false;
                $('b[name=pas]').css('color', 'red');
                $('b[name=pas]').html('请输入新密码');
            }
            if (news != passwords) {
                flag = false;
                alert('新密码两次输入的不一致');
            }
            if (!flag) {
                return;
            }
            var url = "<?php echo yii\helpers\Url::to('/site/modify')?>";
            $.post(url, $('.modifyphone').serialize(), function (i) {
                if (i == 1) {
                    alert('请输入正确的手机');
                } else if (i == 2) {
                    $('b[name=dis]').css('color', 'red');
                    $('b[name=dis]').html('验证码错误');
                } else {
                    alert('修改成功');
                    location.href = "<?php echo yii\helpers\Url::to('/site/login')?>";
                }
            }, 'json');
        })
    })
</script>