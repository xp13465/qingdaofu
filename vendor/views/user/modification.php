<div class="content_right">
    <div class="c_right_bottom clearfix">
        <div class="warning">
            <span>安全级别</span><i></i><i class="zise"></i>　　　　　　<span>较高</span>　　　<span>建议你启动全部安全设置,以保障账号安全</span>
        </div>
        <div class="setmima">
            <div class="mima">
                <i class="display"></i>
                <span class="display">账户密码</span>
                <p class="display">由于互联网存在被盗号的风险,资产宝建议您设置安全级别更高的密码,并建议您定时修改,确保您的账号安全.</p>
                <input type="button" value="修改" class="password">
                <form class="pass">
                <div class="modify">
                    <em class="display"></em>
                    <div>
                        <label for="">当前密码 : </label>
                        <input type="password" name="Current" id=""><b name="cur"></b>
                    </div>
                    <div>
                        <label for="">新密码 : </label>
                        <input type="password" name="New" id=""><b name="news"></b>
                    </div>
                    <div>
                        <label for="">确认新密码 : </label>
                        <input type="password" name="passwords" id=""><b name="pas"></b>
                    </div>
                    <div>
                        <input type="button" value="确定"  id="currents">
                    </div>
                </div>
                </form>
            </div>
            <div class="yanz">
                <i class="display"></i>
                <span class="display">更换手机</span>
                <p class="display">您已完成手机验证,可以正常使用发布信息功能</p>
                <input type="button" value="更换" class="mobile">
                <form class="modifyphone">
                <div class="replace">
                    <em class="display"></em>
                    <div>
                        <label for="">当前手机号 : </label>
                        <input type="text" name="mobile" id="">
                        <span class="display" name="displays">获取</span><b name="mobile"></b>
                    </div>
                    <div>
                        <label for="">验证码 : </label>
                        <input type="text" name="verifyCode" id=""><b name="dis"></b>
                    </div>
                    <div>
                        <label for="">新手机号 : </label>
                        <input type="text" name="newphone" id=""><b name="newphone"></b>
                    </div>
                    <div>
                        <input type="button" value="确定"  class="phone">
                    </div>
                </div>
                </form>
            </div>
        </div>
        <div class="xiushi">

        </div>
        <div class="tishi">
            <span>安全服务提示</span>
            <p>
                1.确认您登录的是清道夫债管家网址：http//xxx.xxx.xxx,注意防范进入钓鱼网站,不要轻信各种工具发送的信息,谨防诈骗.
            </p>
            <p>
                2.建议您安装杀毒软件,并定期更新操作系统等软件补丁,确保账户安全.
            </p>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(".modify").hide();
        $(".password").click(function () {
            $(".modify").fadeToggle();
            $(".replace").hide();
        });
        $(".replace").hide();
        $(".mobile").click(function () {
            $(".replace").fadeToggle();
            $(".modify").hide();
        })
        $('#currents').click(function () {
            var current = $('input[name=Current]').val();
            var news = $('input[name=New]').val();
            var passwords = $('input[name=passwords]').val();
            var flag = true;
            if (!current) {
                flag = false;
                $('b[name=cur]').css('color', 'red');
                $('b[name=cur]').html('请输入原始密码');
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
            var url = "<?php echo yii\helpers\Url::to('/user/modification')?>";
            $.post(url, $('.pass').serialize(), function (v) {
                if (v == 1) {
                    alert('请输入正确的原始密码');
                } else {
                    alert('修改成功');
                    location.href = "<?php echo yii\helpers\Url::to('/user/security')?>";
                }
            }, 'json');
        })
    })
    $(function(){
        var InterValObj; //timer变量，控制时间
        var count = 60; //间隔函数，1秒执行
        var curCount;//当前剩余秒数
        $('span[name=displays]').click(function(){
            var mobile = $('input[name=mobile]').val();
            if(mobile ==''){
                 $('b[name=mobile]').css('color','red');
                $('b[name=mobile]').html('请输入手机号码');
            }
            curCount = count;
            if(!(/^13\d{9}$/g.test(mobile))&&!(/^15\d{9}$/g.test(mobile))&&!(/^18\d{9}$/g.test(mobile))&&!(/^17\d{9}$/g.test(mobile))&&!(/^14\d{9}$/g.test(mobile))) {
                $('b[name=mobile]').css('color','red');
                $('b[name=mobile]').html('请输入格式正确的手机号码');
            }
            var url = "/user/sms";
            __send(url,mobile);
        })
        function __send(url,mobile){
            $.ajax(
                {
                dataType:'json',
                type:'post',
                url:url,
                data:{mobile:mobile},
                success:function(json) {
                    if (json.code == '0000') {
                        $("span[name=displays]").attr("disabled", "disabled");
                        $("span[name=displays]").addClass("huoqudisa");
                        InterValObj= window.setInterval(SetRmainTime, 1000);
                    } else {
                        alert(json.msg);
                    }
                },
                    error:function(){
                        $('b[name=dis]').css('color','red');
                        $('b[name=dis]').html('获取验证码失败，请刷新页面重新！');
                    }
                }
         )
        }
        function SetRmainTime() {
            if (curCount == 0) {
                window.clearInterval(InterValObj);//停止计时器
                $("span[name=displays]").removeAttr("disabled");//启用按钮
                $("span[name=displays]").removeClass("huoqudisa");
                $("span[name=displays]").html('获取');
            }
            else {
                curCount--;
                $("span[name=displays]").html(curCount);
            }
        }
        $('.phone').click(function(){
           var newphone = $('input[name=newphone]').val();
           var verifyCode = $('input[name=verifyCode]').val();

            var flag = true;
            if(!verifyCode){
                flag = false;
                $('b[name=dis]').css('color','red');
                $('b[name=dis]').html('请输入验证码');
            }

            if(!newphone){
                flag = false;
                $('b[name=newphone]').css('color','red');
                $('b[name=newphone]').html('请输入手机号码');
            }
            if(!(/^13\d{9}$/g.test(newphone))&&!(/^15\d{9}$/g.test(newphone))&&!(/^18\d{9}$/g.test(newphone))&&!(/^17\d{9}$/g.test(newphone))&&!(/^14\d{9}$/g.test(newphone))) {
                flag = false;
                $('b[name=newphone]').css('color','red');
                $('b[name=newphone]').html('请输入格式正确的手机号码');
            }
            if( ! flag){
                return;
            }
            var url = "<?php echo Yii\helpers\Url::to('/user/modifymobile')?>";
            $.post(url,$('.modifyphone').serialize(),function(i){
                      if(i == 1){
                          alert('请输入正确的手机');
                      }else if(i == 2){
                          alert('该手机已注册');
                      }else if(i == 3){
                          $('b[name=dis]').css('color','red');
                          $('b[name=dis]').html('验证码错误');
                      }else{
                          alert('修改成功');
                          location.href = "<?php echo yii\helpers\Url::to('/user/security')?>";
                      }
            },'json');
        })
    })
</script>