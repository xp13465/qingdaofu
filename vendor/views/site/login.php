<?php
use yii\helpers\Html;
use yii\helpers\Url;
use  yii\widgets\ActiveForm;
?>
<div class="banner">
    <span></span>
    <span class="ig01"></span>
    <span class="ig02"></span>
    <span class="ig03"></span>
    <span class="ig04"></span>
</div>
<?php $form = ActiveForm::begin(['id' => 'login-form']);?>
<div class="login">
    <span>登录</span>
    <hr/>
    <br>
    <div>
        <?php echo Html::label('手机号码','mobile',['style'=>'font-weight:normal;width:70px;text-align:right;']);?>
        <?php echo Html::input('text','mobile',$model->mobile,['id'=>'mobilephone']);?>
        <br/><?php echo Html::tag('i','',['style'=>'display:inline-block;color:red;padding-left:80px;']);?>
    </div>
    <div>
        <?php echo Html::label('密码　　','password',['style'=>'font-weight:normal;width:70px;text-align:right;']);?>
        <?php echo Html::input('password','password',$model->mobile,['id'=>'mobilepassword']);?>
        <br/><?php echo Html::tag('i','',['style'=>'display:inline-block;color:red;padding-left:80px;']);?>
    </div>
    <div class="login_l"><?php echo Html::tag('a','登录',['onclick'=>'LoginSubForm()']);?></div>
    <ul>
        <li><?php echo Html::tag('a','忘记密码',['href'=>Url::to('/site/forgetpassword')]);?></li>
        <li><?php echo Html::tag('a','|');?></li>
        <li><?php echo Html::tag('a','立即注册',['href'=>Url::to('/site/signup')]);?></li>
    </ul>
</div>
<?php ActiveForm::end();?>
<script type="text/javascript">
    $(document).ready(function() {
    $("#login-form").validate({
        debug:false,
        focusCleanup:true,
        rules: {
            mobile: {
                required:true
            },
            password:{
                required:true,
                minlength:6,
                remote:{
                    type:'post',
                    url:'<?php echo \yii\helpers\Url::to('/site/checkmobilepass')?>',
                    dataType: "json",
                    data:{mobile:function (){return $('#mobilephone').val();},mobilepassword:function (){return $('#mobilepassword').val();}}
                }
            }
        },

        messages: {
            mobile: {
                required:'请输入手机号'
            },
            password:{
                required:'请输入密码',
                minlength:'最少位数为六',
                remote:'该手机号或者密码错误'
            }
        },
        errorPlacement: function(error, element) {
            element.parent().children('i').html(error.html());
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
</script>
