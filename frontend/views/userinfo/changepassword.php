<?php
use yii\helpers\Url;

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
          <span style="margin-left:8px;">13393736381</span>    
        <li> <span>验证码</span>
          <input type="text" name="password" id="yan" placeholder="请输入验证码" maxlength="4">
          <button class="button">发送验证码</button>
          <button class="second">55s</button>
          <i name="dis"></i> </li>
        <li>
          <a  class="first">下一步</a>
        </li>
      </ul>
    </div>
    <div class="reset01">
      <ul>
        <li> <span>新的手机号码</span>
          <input type="text" name="mobile" placeholder="请输入新的手机号码" maxlength="11">
          <i name="mobile"></i> </li>
        <li> <span>验证码</span>
          <input type="text" name="password" id="yan" placeholder="请输入验证码" maxlength="4">
          <button class="button">发送验证码</button>
          <button class="second">55s</button>
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
	$(".first").click(function(){
		$(".reset01").show();
		$(".blue2").show();
		$(".gray2").addClass("blue");
		$(".reset2").addClass("active");
		$(".reset").hide();
		})
	$(".second").click(function(){
		$(".success").show();
		$(".blue3").show();
		$(".gray3").addClass("blue");
		$(".reset3").addClass("active");
		$(".reset01").hide();
		})
	})
</script>