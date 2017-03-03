<?php
use yii\helpers\html;
use yii\helpers\Url;
?>
<?php \yii\widgets\ActiveForm::begin(['id'=>'responsibleperson','method'=>'post','action'=>"/certification/corporation",'options'=>['enctype'=>"multipart/form-data"],])?>
<div class="content_right">
    <div class="gongsi">
        <h3 class="bj">　　公司</h3>
        <ul class="c_right_2">
            <li><span>企业名称</span><?php echo html::input('text','name','')?></li>
            <li class="card clearfix">
                <span>营业执照号</span><?php echo html::input('text','cardno','')?>
                <input type="file" accept = "image/*" name="Certification[cardimg]"><span class="color display">上传</span>　
                <em name="cardimg"></em>
                <span class="shuoming display" style="margin-top:-15px;">
								工商营业执照必须在有效期范围内,格式要求:原件照片,扫描或复印件加盖企业公章后扫描件,照片内容真实有效,
								支持:jpg,jpeg,bmp,png格式照片,大小不得超过2M.营业执照上的所有信息清晰可见,照片内容真实有效,不得做任何修改
                </span>
            </li>
            <li><span>联系人</span><?php echo html::input('text','contact','',['class' =>'ml3'])?></li>
            <li><span>联系方式</span><?php echo html::input('text','mobile','')?></li>
            <li><span>邮箱</span><?php echo html::input('text','email','',['class' =>'ml2'])?>　(选填)</li><br>
            <li><span style="color:#0061ac; font-size: 16px;">业务信息</span></li>

            <li><span>企业经营地址</span><?php echo html::input('text','address','');?></li>
            <li><span>企业网站</span><?php echo html::input('text','enterprisewebsite','')?>　(选填)</li>
             <li><span>经典案例</span><?php echo html::textarea('casedesc','',['cols' =>'30','rows'=>'10','placeholder'=>'关于公司在融资,诉讼,清收等方面的成功案例,将作为展示宣传之用,有利于发布方更加青睐你奥'])?></li>
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
                address:'required',
            },

            messages: {
                name: "请输入用户名",
                contact: "请输联系人",
                mobile: "请输入有效手机号码",
                cardno:"请输入营业执照",
                email: {
                    email:"请输入正确的邮箱",
                },
                address:"请输入公司地址",
            },
        });
    });

    function certification(){
        var r = $('#responsibleperson').valid()
        $('#responsibleperson').submit();
        var cardimg= $('input[name="Certification[cardimg]"]').val();
        if(!cardimg){
            $('em[name=cardimg]').css('color','red');
            $('em[name=cardimg]').html('请上传证件');
        }
    }
    $(function(){
        $(':file').click(function(){
            $('input[name="Certification[cardimg]"]').change(function(i){
                if(i){
                    $('em[name=cardimg]').css('color','red');
                    $('em[name=cardimg]').html('已上传');
                }
            })
        })
    })
</script>