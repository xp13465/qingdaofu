<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;?>
<?=wxHeaderWidget::widget(['title'=>'登录','gohtml'=>Html::tag('a','注册',['href'=>\yii\helpers\Url::toRoute('/site/registery'),'class'=>'zhuce'])])?>
<section>
<style>
.login-t{width:100%;max-width:640px;height:50px;background:#fff;}
.huiyuan{width:50%;height:50px;line-height:50px;float:left;text-align:center;cursor:pointer;}
.huiyuan:hover,.huiyuanhover{border-bottom:2px solid #0da3f8;color:#0da3f8;}
 
</style>
 






 <?php \yii\widgets\ActiveForm::begin(['id'=>'loginform']);?>
  <?=Html::input('hidden','logintype','2')?>
 <div class="login-t">
	<div class="huiyuan huiyuanhover">
			短信快捷登录
	</div>
	<div class="huiyuan">
			会员帐号登录
	</div>
 </div>
    <div class="phone_num">
       
		<div class="password">
            <?=Html::input('text','mobile','',['placeholder'=>'请输入您的手机号码','class'=>'input01'])?>
			<?=Html::tag('a','获取验证码',['href'=>'javascript:void(0)','class'=>'validateCode','style'=>'display:none'])?>
        </div>
        <div class="password">
            <?=Html::input('password','password','',['placeholder'=>'输入密码'])?>
            <?=Html::tag('a','显示密码',['href'=>'javascript:void(0)','class'=>'displaypass'])?>
        </div>
    </div>
    <div class="login">
        <?=Html::tag('a','登录',['href'=>'javascript:void(0)','class'=>'denglu'])?>
    </div>
    <div class="rem">
        <a href="<?php echo yii\helpers\Url::toRoute('/site/forget')?>">忘记密码?</a>
    </div>
    <?php \yii\widgets\ActiveForm::end();?>
</section>

<script type="text/javascript">
    $(document).ready(function () {
        curCount = 0;
        curPassCount = 0;
		
		$(".huiyuan").click(function(){
			var index = ($(this).index())+1;
			// alert(index)
			var val = index==1?"2":"1";
			$('input[name="logintype"]').val(val).trigger("change")
			$(this).addClass("huiyuanhover")
			$(this).siblings().removeClass("huiyuanhover");
			if(index==1){
				$(".rem").hide();
			}else{
				$(".rem").show();
			}
		})
		
		
        $('.displaypass').click(function () {
            if($('input:password').attr('type') == 'password'){
                $('input[name="password"]').attr('type','text');
                $(this).html('隐藏密码');
            }else{
                $('input[name="password"]').attr('type','password');
                $(this).html('显示密码');
            }

        });
		$('.validateCode').click(function () {
            var mobile = $('input[name="mobile"]').val();
            if(mobile == ""){
                layer.msg("请先输入手机号码");
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
                            layer.msg('验证码发送成功');
                            $(".validateCode").attr("disabled", "disabled");
                            $(".validateCode").addClass("huoqudisa");
                            curCount = 60;
                            InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
                        } else {
                            layer.msg(json.msg);
                        }
                    }
                });
            }
        });
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
		$('input[name="logintype"]').change(function(){
			var logintype = $('input[name="logintype"]').val();
			if(logintype == 1){
				$(".validateCode").hide();
				$(".displaypass").show();
				$('input[name="password"]').attr('type','password').val("").attr("placeholder",'输入密码');
                $(".displaypass").html('显示密码');
			}else{
				$(".displaypass").hide();
				$(".validateCode").show();
				$('input[name="password"]').attr('type','text').val("").attr("placeholder",'输入验证码');
                $(".displaypass").html('隐藏密码');
			}
			
		}).trigger("change")
		$("#loginform").on('keydown',function(e){ 
			var ev = document.all ? window.event : e;
			if(ev.keyCode==13) {
					$('.denglu').trigger("click")
			 }
		}) 
        $('.denglu').click(function () {
			var index = layer.load(1, {
				shade: [0.4,'#fff'] //0.1透明度的白色背景
			});
            var mobile = $('input[name="mobile"]').val();
            var password = $('input[name="password"]').val();
            var logintype = $('input[name="logintype"]').val();
            $.ajax({
                url:'<?=\yii\helpers\Url::toRoute('/site/login')?>',
                type:'post',
                data:{mobile:mobile,password:password,logintype:logintype,openid:'<?=isset($openid)?$openid:''?>'},
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
                        window.location = "<?=\yii\helpers\Url::toRoute('/site/index')?>";
                    }else{
						layer.close(index)
                        layer.alert(json.msg,{closeBtn:false,title:false});
                    }
                }
            });
        });
    });

</script>