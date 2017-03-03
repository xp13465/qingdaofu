<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php \yii\widgets\ActiveForm::begin(['id'=>'responsibleperson','method'=>'post','action'=>"/certification/corporation",'options'=>['enctype'=>"multipart/form-data"],])?>
<div class="content_right">
    <div class="gongsi">
        <h3 class="bj">公司</h3>
        <ul class="c_right_2">
            <li><span>企业名称</span><?php echo html::input('text','name','')?></li>
            <li class="card card1 clearfix">
                <span>营业执照号</span><?php echo html::input('text','cardno','')?>
                <span class="color uploadSpan">上传</span>
                <?php echo Html::hiddenInput('cardimg')?><span class="color uploadChakan">查看</span>
                <em name="cardimg"></em><span name="cardimg" style="font-size:14px; margin:-5px 40px;"></span>　
                <i class="wenhao"></i>
                <em class="wenhao">
                    证件上的所有字清晰可见,必须能看得清证件号.照片内容真实有效,不得做任何修改,支持:jpg,jpeg,bmp,png格式照片,不得超过2M.

                </em>
            </li>
            <li><span>联系人</span><?php echo html::input('text','contact','',['class' =>'ml3'])?></li>
            <li><span>联系方式</span><?php echo html::input('text','mobile','')?></li>
            <li><span>邮箱</span><?php echo html::input('text','email','',['class' =>'ml2'])?>　(选填)</li><br>
            <li><span style="color:#0061ac; font-size: 16px;">业务信息</span></li>

            <li><span>企业经营地址</span><?php echo html::input('text','address','');?>　(选填)</li>
            <li><span>企业网站</span><?php echo html::input('text','enterprisewebsite','')?>　(选填)</li>
             <li><span>经典案例</span><?php echo html::textarea('casedesc','',['cols' =>'30','rows'=>'10','placeholder'=>'关于公司在诉讼,清收等方面的成功案例,将作为展示宣传之用,有利于发布方更加青睐你噢'])?>　(选填)</li>
            <li><input type="button" value="认　证" onclick="certification()"></li>
        </ul>
    </div>
</div>
<?php \yii\widgets\ActiveForm::end();?>
<script>
    $.validator.addMethod("email",function(value,element){
        return this.optional( element ) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@(?:\S{1,63})$/.test( value );
    },"请输入正确的邮箱");
    $(document).ready(function() {
        $("#responsibleperson").validate({
            ignore: "",
            rules: {
                name: "required",
                contact: "required",
                mobile:"required",
                cardno:"required",
                email: {
                    email:true,
                },
                cardimg:"required",
                //address:'required',
            },

            messages: {
                name: "忘记填写用户名啦",
                contact: "忘记填写联系人啦",
                mobile: "忘记填写有效手机号码啦",
                cardno:"忘记填写营业执照啦",
                email: {
                    email:"请输入正确的邮箱",
                },
                cardimg:"忘记上传证件啦"
                //address:"请输入公司地址",
            },
        });
    });

    function certification(){
        var cardimg= $('input[name="Certification[cardimg]"]').val();
        if(!cardimg){
            $('em[name=cardimg]').css('color','red');
            $('em[name=cardimg]').html('请上传证件');
        }
        if(!$('#responsibleperson').valid()) {
            var r = $('#responsibleperson').valid();
        }else {
            registerProtocol($("input[name='name']").val(), $("input[name='cardno']").val(), $("input[name='mobile']").val());
        }
    }
    function registerProtocol(name,cardno,mobile){
        $.msgbox({
            height:270,
            width:350,
            content:'<p>您认证的公司信息如下:</p><p>企业名称：' +name+
            '</p><p>营业执照号：' +cardno+'</p><p>联系方式: ' +mobile+
            '</p><p>温馨提示：请先确认后再认证，认证后信息不可修改。</p>',
            type :'confirm',
            onClose:function(v){
                if(v){
                    $('#responsibleperson').submit();
                }else{

                }
            }
        });
    }

    $(document).delegate('.uploadSpan','click',function(){
        var name = $(this).next('input:hidden').attr("name");
        $.msgbox({
            closeImg: '/images/close.png',
            async: false,
            height:530,
            width:630,
            title:'请选择图片',
            content:"<?php echo Url::to(["/func/uploadsimg"])?>/?type="+name,
            type:'ajax'
        });
    });
    $(document).delegate('.uploadChakan','click',function(){
        var typeName = $(this).prev('input:hidden').attr("name");
        var name = $(this).prev('input:hidden').val();
        $.msgbox({
            closeImg: '/images/close.png',
            async: false,
            height:530,
            width:630,
            title:'显示照片',
            content:"<?php echo Url::to(["/func/viewimages"])?>/?name="+name+"&typeName="+typeName,
            type:'ajax'
        });
    });
</script>