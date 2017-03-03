<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$logintype=Yii::$app->request->get("logintype");
$logintype=in_array($logintype,[1,2])?$logintype:1;
?>
<style>
.main{height:627px;}
.login{margin-top: 120px}
</style>
<div class="main">
 <?php $form = ActiveForm::begin(['id' => 'login-form',"action"=>Url::toRoute(["site/login",'logintype'=>$logintype,"backurl"=>$backurl])]);?>
  <div class="login">
    <p><?=$logintype==2?"手机验证码登录":"帐号密码登录"?></p>
    <b style='font-size:12px;' class='errormsg'><?=$model->errors?$model->errors['password'][0]:''?></b> 
	 <?php echo Html::input('hidden','logintype',$logintype,['id'=>'logintype']);?>
	 <?php echo Html::tag('a',($logintype==1?"手机验证码登录":"帐号密码登录"),['href'=>Url::toRoute(['/site/login','logintype'=>($logintype==1?"2":"1")]),'style'=>'float:right;margin-right:30px;color:#18a7f9;font-size:12px;']);?>
	 
    <ul>
    <li class='phoneli'><span class="phone"> &nbsp&nbsp </span>
      <?php echo Html::input('text','mobile',$model->mobile,['id'=>'mobilephone','placeholder'=>'请输入手机号码']);?>
	  <?php echo Html::tag('i','',['style'=>'display:none']);?>
    </li>
    <li class='posswordli'> <span class="possword"> &nbsp&nbsp </span>
      <?php echo Html::input('password','password',$model->mobile,['id'=>'mobilepassword','style'=>($logintype==2?"width:150px":""),'placeholder'=>($logintype==1?"请输入密码":"请输入验证码")]);?>
	   <?php echo Html::tag('i','',['style'=>'display:none']);?>
	  <?php echo Html::tag('span','发送动态密码',['class'=>'huoqu','style'=>($logintype==2?"display:inline-block;":"display:none;").'cursor:pointer;font-size:14px;text-align:center;background: #18a7f9;color:#fff; width: 96px; /* border: 1px solid #ccc;    border-radius: 5px;   */   height: 38px;    line-height: 36px']);?>
	 
    </li> 
    <input id="remember" type="checkbox" style="background:red;margin-top:30px;margin-left:30px;position: relative;top: 2px;" >
    <label style='font-size:12px' for="remember">两周内自动登录</label> 
	<?php echo Html::tag('a','忘记密码',['href'=>Url::toRoute('/site/forgetpassword'),'style'=>'margin-left:140px;color:#18a7f9;font-size:12px']);?>
	</br>
	<?php echo Html::tag('button','登录',['class'=>'dlu','onclick'=>'']);?>
    </ul>
  </div>
  <?php ActiveForm::end();?>
</div>
 
<?php \frontend\widget\JsBlock::begin()?>
<script type="text/javascript">
    $(document).ready(function() { 
		/*$("#loginbtn").click(function(){
			if($("#logintype").val()==1){
				$("#logintype").val(2)
				$("#loginbtn").html("帐号密码登录")
				$("#mobilepassword").attr('placeholder','请输入验证码')
			}else{
				$("#logintype").val(1)
				$("#loginbtn").html("手机验证码登录")
				$("#mobilepassword").attr('placeholder','请输入密码')
			}
		})*/
		 var count = 60; 
		var curCount;//当前剩余秒数
		$("#mobilephone").on("focus",function(){
			$(this).parent("li").addClass('phonelihover');
		}).on("blur",function(){
			$(this).parent("li").removeClass('phonelihover');
		})
		$("#mobilepassword").on("focus",function(){
			$(this).parent("li").addClass('posswordlihover');
		}).on("blur",function(){
			$(this).parent("li").removeClass('posswordlihover');
		})
		//timer处理函数
        function SetRemainTime() {
            if (curCount == 0) {
                window.clearInterval(InterValObj);//停止计时器
                $(".huoqu").removeAttr("disabled");//启用按钮
                $(".huoqu").css({'background':"#18a7f9"});
                $(".huoqu").html('发生动态密码');
            }
            else {
                curCount--;
				console.log(curCount)
                $(".huoqu").css({'background':"#ddd"}).html(curCount);
            }
        }
		$('.huoqu').click(function(){ 
			if($(".huoqu").attr('disabled'))return;
            if($('#mobilephone').val()==''){alert('忘记填写手机号啦'); return false;}  
            var mobile = $('#mobilephone').val();
            if(!(/^13\d{9}$/g.test(mobile))&&!(/^15\d{9}$/g.test(mobile))&&!(/^18\d{9}$/g.test(mobile))&&!(/^17\d{9}$/g.test(mobile))&&!(/^14\d{9}$/g.test(mobile))){
                alert('请输入格式正确的手机号');
                return;
            }
            var url = "/site/sms";
			if($("#mobilephone").next("i").html()==''){
				
				$.ajax(
                {
                    dataType:'json',
                    type:'post',
                    url:url,
                    data:{mobile:mobile,type:3},
                    success:function(json){
                        if(json.code == '0000'){
							alert(json.msg);
							curCount = count;
                            //设置button效果，开始计时
                            //$('#dj_time').html("验证码已发送，请注意查收！");
                            $(".huoqu").attr("disabled","disabled"); 
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
                });
			}else{
				alert($("#mobilephone").next("i").html())
			}
            
			return false;
        });
		
        $("#login-form").validate({
            debug:false,
            focusCleanup:true,
            rules: {
                mobile: {
                    required:true
                }<?php if($logintype==1){?>
				,
                password:{
                    required:true,
                    minlength:6,
                    remote:{
                        type:'post',
                        url:'<?php echo \yii\helpers\Url::toRoute('/site/checkmobilepass')?>',
                        dataType: "html",
                        data:{mobile:function (){return $('#mobilephone').val();},mobilepassword:function (){return $('#mobilepassword').val();}}
                    }
                }
				<?php }?>
            },

            messages: {
                mobile: {
                    required:'忘记填写手机号啦',
                }<?php if($logintype==1){?>
				,
                password:{
                    required:'忘记填写密码啦',
                    minlength:'最少位数为六',
                    remote:'该手机号或者密码错误'
                }
				<?php }?>
            },
            errorPlacement: function(error, element) {
               
			   element.parent().children('i').html(error.html());
			   var errorhtml = $("#mobilephone").next("i").html()?$("#mobilephone").next("i").html():$("#mobilepassword").next("i").html()
			   $(".errormsg").html(errorhtml);
            },
            success: function (element) {
                element.parent().children('i').html('');
            }

        });
    });
    function LoginSubForm(){
        $('#login-form').submit();
    }

    $("#mobilephone").change(function(){
        $("#mobilepassword").removeData("previousValue");
    });

    $('input').keyup(function(event){
        if(event.keyCode ==13){
            LoginSubForm();
        }
    });
</script>
<?php \frontend\widget\JsBlock::end();?>