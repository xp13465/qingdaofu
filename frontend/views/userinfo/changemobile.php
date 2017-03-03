<?php
use yii\helpers\Url;
$this->title ="修改绑定手机";
$this->params['breadcrumbs'][] = ['label' => "个人资料", 'url' =>["/userinfo/info"] ];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="change">
    <div class="steps">
      <div class="one">
       <p class="blue"><i>1</i></p>
        <p class="reset1">填写账户信息</p>
      </div>
      <div class="two">
        <p class="gray2"><i>2</i></p>
        <p class="reset2">绑定新手机</p>
      </div>
      <div class="three">
        <p class="gray3"><i>3</i></p>
        <p class="reset3">完成</p>
      </div>
    </div>
    <div class="reset">
      <ul>
        <li> 
          <span>手机号</span>
          <span style="margin-left:8px;"><?=$userinfo['mobile']?></span>    
			<li> <span>验证码</span>
          <input type="text" name="password" id="oldcode" placeholder="请输入验证码" maxlength="4">
          <button class="button oldmobilecode">发送验证码</button>
          <i name="dis"></i> </li>
        <li>
          <a  class="first">下一步</a>
        </li>
      </ul>
    </div>
    <div class="reset01">
      <ul>
        <li> <span>新的手机号码</span>
          <input type="text" name="mobile" id='newmobile' placeholder="请输入新的手机号码" maxlength="11">
          <i name="mobile"></i> </li>
        <li> <span>验证码</span>
          <input type="text" name="password" id="newcode" placeholder="请输入验证码" maxlength="4">
          <button class="button validateCode">发送验证码</button>
          <i name="dis"></i> </li>
        <li>
          <a  class="second">下一步</a>
        </li>
      </ul>
    </div>
    <div class="success">
      <p class="pic"><img src="/bate2.0/images/s.png"></p>
      <p class="title">手机号修改成功</p>
      <a >完成</a>
    </div>
  </div>
  
<script>
$(document).ready(function(){
	var curCount = 0;
	var oldmobile = '<?=$userinfo['mobile']?>';
	var csrf = '<?=Yii::$app->request->getCsrfToken()?>'
	var InterValObj;
	var curSmsBtn = '.validateCode';
	function SetRemainTime() {
		console.log(curSmsBtn)
        if (curCount == 0) {
            window.clearInterval(InterValObj);//停止计时器
            $(curSmsBtn).addClass("button").removeClass("second")
            $(curSmsBtn).html('获取验证码');
        }else { 
            curCount--;
            $(curSmsBtn).html(curCount);
        }
    }
	$('.oldmobilecode').click(function () {
            if(!$(this).hasClass("second")){
				var loadindex = layer.load(1)
				$.ajax({
                    url: '<?=\yii\helpers\Url::toRoute('/wap/user/smscode')?>',
                    type: 'post',
                    data: {mobile: oldmobile,type: 4},
                    dataType: 'json',
                    success: function (json) {
						layer.close(loadindex)
                        if (json.code == '0000') {
							curCount = 60;
							curSmsBtn = '.oldmobilecode';
							$(curSmsBtn).addClass("second").removeClass("button")
                            InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
                        }
						layer.msg(json.msg);
                         
                    }
                });
			}
        });
	$('.validateCode').click(function () {
            var mobile = $('#newmobile').val();
            if(mobile == ""){
                layer.msg("请输入手机号码");
                return false;
            }
			var loadindex = layer.load(1)
            if(curCount == 0) {
                $.ajax({
                    url: '<?=\yii\helpers\Url::toRoute('/wap/user/smscode')?>',
                    type: 'post',
                    data: {mobile: mobile,type: 4},
                    dataType: 'json',
                    success: function (json) {
						layer.close(loadindex)
                        if (json.code == '0000') {
                            layer.msg(json.msg);
                            curCount = 60;
							curSmsBtn = '.validateCode';
							$(curSmsBtn).addClass("second").removeClass("button")
                            InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
                        } else {
                            layer.msg(json.msg);
                        }
                    }
                });
            }
    });
	$(".first").click(function(){
		var oldcode = $("#oldcode").val()
		// alert(oldmobile+"\n"+oldcode)
		var loadindex = layer.load(1)
		$.ajax({
            url: '<?=\yii\helpers\Url::toRoute('/wap/user/checksmscode')?>',
            type: 'post',
            data: {mobile: oldmobile,type: 4,code: oldcode},
            dataType: 'json',
            success: function (json) {
				layer.close(loadindex)
                if (json.code == '0000') {
					curCount = 0;
					$(".reset01").show();
					$(".blue2").show();
					$(".gray2").addClass("blue");
					$(".reset2").addClass("active");
					$(".reset").hide();
					
                } else {
                    layer.msg(json.msg);
                }
            }
        });
		return false;
			
	})
	$(".second").click(function(){
			var oldcode = $('#oldcode').val();
			var newmobile = $('#newmobile').val();
			var newcode = $('#newcode').val();
			$.ajax({
                url: '<?=\yii\helpers\Url::toRoute('/userinfo/changemobile')?>',
                type: 'post',
                data: {oldmobile: oldmobile,newmobile:newmobile,oldcode: oldcode,newcode: newcode,_csrf:csrf},
                dataType: 'json',
                success: function (json) {
                    if (json.code == '0000') {
						$(".success").show();
						$(".blue3").show();
						$(".gray3").addClass("blue");
						$(".reset3").addClass("active");
						$(".reset01").hide();
						window.opener.location.reload();  
                    }
					layer.msg(json.msg);
                }
            });
		})
	})
</script>