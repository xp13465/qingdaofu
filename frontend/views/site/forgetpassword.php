<div class="int">
<div class="inp">
<form class='modifyphone' onsubmit='return false;'>
  <div class="steps">
    <div class="one">
      <span><img src="/images/login/1.png"></span>
      <p>填写账户信息</p>
    </div>
    <div class="two">
    <span id="default"><img src="/images/login/2.png"></span>
    <span id="selected" style="display:none;"><img src="images/2_s.png"></span>
      <p class="reset3">重置密码</p>
    </div>
  </div>
  <ul class="reset1">
  
  
    <li><span>手机号码</span>
      <input type="text" name="mobile" placeholder="用于登陆的用户名">
	    <i name="mobile"></i>
    </li>
    <li> <span>验证码</span>
      <input type="verifyCode" name="verifyCode" id="yan" placeholder="请输入验证码">
      <button class="button displays">发送验证码</button>
	  <i name="dis"></i>
	  
    </li>
    <li>
      <button class="zhu"  id="reset" >下一步</button>
    </li>
    <li><a href="/" style="text-align:center;color:#18a7f9;">返回首页</a> </li>
  </ul>
 
</div>
</div>
</form>
<?php \frontend\widget\JsBlock::begin()?>
<script type="text/javascript">
    $(document).ready(function() {
        var InterValObj; //timer变量，控制时间
        var count = 60; //间隔函数，1秒执行
        var curCount;//当前剩余秒数
        $('.displays').click(function () {
            var mobile = $('input[name=mobile]').val();
            if (mobile == '') {
                $('i[name=mobile]').css('color', 'red');
                $('i[name=mobile]').html('忘记填写手机号码啦');
            }else if (!(/^13\d{9}$/g.test(mobile)) && !(/^15\d{9}$/g.test(mobile)) && !(/^18\d{9}$/g.test(mobile)) && !(/^17\d{9}$/g.test(mobile)) && !(/^14\d{9}$/g.test(mobile))) {
                $('i[name=mobile]').css('color', 'red');
                $('i[name=mobile]').html('请输入格式正确的手机号码');
            }else{
				$('i[name=mobile]').html("")
			}
			curCount = count;
			if($('i[name=mobile]').html()==""){
				var url = "/site/mobilesms";
				__send(url, mobile);
			}
            
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
                        $('i[name=dis]').css('color', 'red');
                        $('i[name=dis]').html('获取验证码失败，请重新刷新页面！');
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
                $('i[name=dis]').css('color', 'red');
                $('i[name=dis]').html('忘记填写验证码啦');
            }
            if (!flag) {
                return;
            }
            var url = "<?php echo yii\helpers\Url::toRoute('/site/modifys')?>";
            $.post(url, $('.modifyphone').serialize(), function (i) {
                if (i == 1) {
                    alert('请输入正确的手机');
                } else if (i == 2) {
                    $('i[name=dis]').css('color', 'red');
                    $('i[name=dis]').html('验证码错误');
                } else {
                   location.href = "<?php echo yii\helpers\Url::toRoute('/site/resets')?>";
                }
            }, 'json');
        })
    })
</script>
<?php \frontend\widget\JsBlock::end();?>