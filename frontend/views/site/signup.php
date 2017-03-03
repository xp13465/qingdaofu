<?php
use \frontend\models\RegForm;
use yii\helpers\Html;
?>

<div class="int">
 <?php $form = RegForm::begin(['id' => 'reg-form']);?>
  <div class="inp">
    <ul>
      <li>
			<?php echo Html::tag('span','手机号码 ',['style'=>'']);?>
            <?php echo Html::input('text','mobile',$model->mobile,['id'=>'mobile','placeholder'=>'用于登陆的用户名']);?>
            <?php echo Html::tag('i','',['style'=>'color:red;margin-left:20px;']);?>
		  
      </li>
      <li> 
							<?php echo Html::tag('span','密码 ',['style'=>'']);?>
                            <?php echo Html::input('password','passwordf','',['id'=>'passwordf','placeholder'=>'字母加数字不低于6位数']);?>
                            <?php echo Html::tag('i','',['style'=>'color:red;margin-left:20px;']);?>
							
	  
      </li>
      <li>  
							<?php echo Html::tag('span','确认密码 ',['style'=>'']);?>
                            <?php echo Html::input('password','passwords','',['id'=>'passwordf','placeholder'=>'再次输入密码']);?>
                            <?php echo Html::tag('i','',['style'=>'color:red;margin-left:20px;']);?>
							
      </li>
        <li> 
		
							<?php echo Html::tag('span','验证码 ',['style'=>'']);?>
                            <?php echo Html::input('text','validateCode','',['id'=>'yan','placeholder'=>'请输入验证码']);?>
                            <?php echo Html::tag('button','发送验证码',['class'=>'button huoqu','style'=>'cursor:pointer']);?>
							<?php echo Html::tag('i','',['style'=>'color:red;margin-left:20px;']);?>
		
       </li>

        <li> 
			<?php echo Html::tag('span','推荐人 ',['style'=>'']);?><?php echo Html::input('text','tjmobile','',['placeholder'=>'推荐人手机号（选填）',]);?>
			<?php echo Html::tag('i','',['style'=>'color:red;margin-left:20px;']);?>
		</li>
	    <?php echo Html::input('checkbox','isAgree',1,['checked'=>'true']);?>
        <?php echo Html::tag('label','我已阅读并同意');?>
        <?php echo Html::tag('span','《注册协议》',['onclick'=>'registerProtocol()','style'=>'cursor:pointer;color:#18a7f9;;']);?>
		
					
       <li> <span>&nbsp;</span>
	     <?php echo Html::input('button','','注册',['onclick'=>'RegSubForm()','class'=>'zhu','style'=>'cursor:pointer']);?>
		 <?php echo Html::tag('i','',['style'=>'color:red;margin-left:20px;']);?>
       </li>
    </ul>           
	</div>
	<?php RegForm::end();?>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        /*-------------------------------------------*/
        var InterValObj; //timer变量，控制时间
        var count = 60; //间隔函数，1秒执行
        var curCount;//当前剩余秒数
        $('.huoqu').click(function(){ 
            if($('input[name=mobile]').val()==''){alert('忘记填写手机号啦'); return false;}
            if($('input[name=verifyCode]').val()==''){alert('忘记填写验证码啦'); return false;}

            curCount = count;
            var mobile = $('#mobile').val();
            var verifyCode = $('#verifyCode').val();
            if(!(/^13\d{9}$/g.test(mobile))&&!(/^15\d{9}$/g.test(mobile))&&!(/^18\d{9}$/g.test(mobile))&&!(/^17\d{9}$/g.test(mobile))&&!(/^14\d{9}$/g.test(mobile))){
                alert('请输入格式正确的手机号');
                return;
            }
            //$('._msg').html('');
            var url = "/site/sms";
			if($("#mobile").next("i").html()==''){
				__send(url,mobile,verifyCode);
			}else{
				alert($("#mobile").next("i").html())
			}
            
			return false;
        });

        function __send(url,mobile,verifyCode){
            $.ajax(
                {
                    dataType:'json',
                    type:'post',
                    url:url,
                    data:{mobile:mobile,verifyCode:verifyCode},
                    success:function(json){
                        if(json.code == '0000'){
                            //设置button效果，开始计时
                            //$('#dj_time').html("验证码已发送，请注意查收！");
                            $(".huoqu").attr("disabled","disabled");
                            $(".huoqu").addClass("huoqudisa");
                            //$('#dj_time').show();
                            InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
                            //$("#dj_time").html("请在" + curCount + "秒内输入验证码");
                        }else{
                            alert(json.msg);
                        }
                    },
                    error:function(){
                        alert('获取验证码失败，请刷新页面重新！');
                    }
                }
            );
        }

        //timer处理函数
        function SetRemainTime() {
            if (curCount == 0) {
                window.clearInterval(InterValObj);//停止计时器
                $(".huoqu").removeAttr("disabled");//启用按钮
                $(".huoqu").removeClass("huoqudisa");
                $(".huoqu").html('获取');
            }
            else {
                curCount--;
                $(".huoqu").html(curCount);
            }
        }

        jQuery.validator.addMethod("isPassword", function(value, element) {
            var tel =/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/;
            return this.optional(element) || (tel.test(value));
        }, "请填写6位以上的字符和数字组合密码");

        $("#reg-form").validate({
            debug:false,
            focusCleanup:true,
            rules: {
                mobile: {
                    required:true,
                    remote:{
                        type:'post',
                        url:'<?php echo \yii\helpers\Url::toRoute('/site/checkmobile')?>',
                        dataType: "json",
                        data:{mobile:function (){return $('#mobile').val()}}
                    }
                },
                validateCode: {
                    required: true
                },
                verifyCode: {
                    required: true
                },
                passwordf:{
                    required:true,
                    minlength:6,
                    isPassword:true
                },
                passwords:{
                    required:true,
                    minlength:6,
                    equalTo:'#passwordf',
                    isPassword:true
                },
                isAgree: "required"
            },

            messages: {
                mobile: {
                    required:'忘记填写手机号啦',
                    remote:'该手机号已注册'
                },
                verifyCode: {
                    required: "忘记填写验证码啦"
                },
                validateCode: {
                    required: "忘记填写短信验证码啦"
                },
                passwordf:{
                    required:'忘记填写密码啦',
                    minlength:'最少位数为六',
                    isPassword:"请填写6位以上的字符和数字组合密码"
                },
                passwords:{
                    required:'忘记填写密码啦',
                    minlength:'最少位数为六',
                    equalTo:'两次密码不一致',
                    isPassword:"请填写6位以上的字符和数字组合密码"
                },
                isAgree:'请确认是否认同注册协议'

            },
            errorPlacement: function(error, element) {
                element.parent().children('i').html(error.html());
                
            },
            success: function (element) {
                element.parent().children('i').html('');
            }

        });
    });

    function registerProtocol(){
        $.msgbox({
            closeImg: '/images/close.png',
            height:600,
            width:1080,
            content:"<?php echo \yii\helpers\Url::toRoute('/protocol/registerprotocal');?>",
            type :'ajax',
            title: '请阅读《用户服务协议》'
        });
    }



    function RegSubForm(){
        $('#reg-form').submit();
    }
</script>