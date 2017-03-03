<form class="modifyphones">
   <div class="int">
	<div class="inp">
	  <div class="steps">
		<div class="one">
		  <span><img src="/images/login/1.png"></span>
		  <p>填写账户信息</p>
		</div>
		<div class="two"> 
		<span id="selected" ><img src="/images/login/2_s.png"></span>
		  <p style="color:#18a7f9">重置密码</p>
		</div>
	  </div>
	  <ul class="reset2" >
		<li> <span>输入密码</span>
		  <input type="password" name="New" placeholder="再次输入密码"><i name="news"></i>
		</li>
		<li> <span>确认密码</span>
		  <input type="password" name="passwords" placeholder="显示默认注册手机号"><i name="pas"></i>
		</li>
		<li> <span>&nbsp;</span>
		  <button class="zhu" type="button" id="reset">重置密码</button>
		</li>
		<li><span>&nbsp;</span> <a href="/" style="text-align:center;color:#18a7f9;">返回首页</a> </li>
	  </ul>
	</div>
</div>
</form>


<?php \frontend\widget\JsBlock::begin()?>


<script type="text/javascript">
    $('#reset').click(function () {
           var news = $('input[name=New]').val();
           var passwords = $('input[name=passwords]').val();
           var flag = true;
        if (!news) {
           flag = false;
           $('i[name=news]').css('color', 'red');
           $('i[name=news]').html('忘记填写新密码啦');
           }
        if (!passwords) {
           flag = false;
           $('i[name=pas]').css('color', 'red');
           $('i[name=pas]').html('忘记填写新密码啦');
           }
        if (news != passwords) {
           flag = false;
           alert('新密码两次输入的不一致');
               }
           if (!flag) {
           return;
        }
        var url = "<?php echo yii\helpers\Url::toRoute('/site/modify')?>";
        $.post(url,$('.modifyphones').serialize(),function (i){
           if (i == 4) {
              alert('修改成功');
              location.href = "<?php echo yii\helpers\Url::toRoute('/site/login')?>";
           }else{
               alert('修改失败');
           }
         },'json');
        })
     </script>

<?php \frontend\widget\JsBlock::end();?>