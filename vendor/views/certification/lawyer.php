<?php
use yii\helpers\html;
use yii\helpers\Url;
?>
<?php \yii\widgets\ActiveForm::begin(['id'=>'pastor','method'=>'post','action'=>"/certification/lawyer",'options'=>['enctype'=>"multipart/form-data"],])?>
<div class="content_right">
    <div class="lvshi">
        <h3 class="bj">　　律师</h3>
        <ul class="c_right_2">
            <li><span>姓名</span><?php echo html::input('text','name','',['class' =>'ml2'])?></li>
            <li class="card">
                <span>身份证</span>　<?php echo html::input('text','cardno','')?>
                <input type="file" accept = 'image/*' name="Certification[cardimgs]"/>
                <span class="color display">上传</span>
                　　<em name="cardimg"></em>　<span class="shuoming display">
								证件上的所有字清晰可见,必须能看得清证件号.照片内容真实有效,不得做任何修改,支持:jpg,jpeg,bmp,png格式照片,不得超过2M.
                     </span>
            </li>
            <li class="picss"><span>律师执业证</span><?php echo html::input('text','law_cardno','')?>
                <input type="file" accept = 'image/*' name="Certification[law_cardnos]"/>　
                <span class="color display">上传</span>
                <em name="law_cardnos"></em>
            </li>
            <li><span>联系地址</span><?php echo html::input('text','address','')?></li>
            <li><span>联系方式</span><?php echo html::input('text','mobile','')?></li>
            <li><span>邮箱</span><?php echo html::input('text','email','',['class' =>'ml2'])?>　(选填)</li>
            <li><span>学历</span><?php echo html::input('text','education_level','',['class' =>'ml2'])?>　(选填)</li>
            <li><span>语言</span><?php echo html::input('text','lang','',['class' =>'ml2'])?>　(选填)</li>
            <li><span>从业年限</span><?php echo html::input('text','working_life','')?>　(选填)</li>
            <li><span>专业领域</span><?php echo html::input('text','professional_area','')?>　(选填)</li>
            <li><span>清收经典案例</span><?php echo html::textarea('casedesc','',['cols' =>'30','rows'=>'10','placeholder'=>'关于公司在融资,诉讼,清收等方面的成功案例,将作为展示宣传之用,有利于发布方更加青睐你奥'])?>　(选填)</li>
            <li><input type="button" value="认　证" onclick="certification()"></li>
        </ul>
    </div>
</div>
<script>
    $.validator.addMethod("isIdCardNo", function(value, element) {
        return this.optional(element) || idCardNoUtil.checkIdCardNo(value);
    }, "请输入正确的身份证号码");
    $.validator.addMethod("email",function(value,element){
        return this.optional( element ) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@(?:\S{1,63})$/.test( value );
    },"请输入正确的邮箱");
    $(document).ready(function() {
        $("#pastor").validate({
            ignore: "",
            rules: {
                name: "required",
                cardno:{
                    required:true,
                    isIdCardNo:true,
                },
                certificate:"required",
                law_cardno:'required',
                address: "required",
                mobile:"required",
                email: {
                    email:true,
                },
            },

            messages: {
                name: "请输入用户名",
                cardno: {
                    required:"请输入证件号",
                    isIdCardNo:'请输入正确的身份证号码',
                },
                certificate:"请上传证件",
                law_cardno:'律师执业证',
                address: "请输入地址",
                mobile: "请输入有效手机号码",
                email: {
                    email:"请输入正确的邮箱",
                },
            },
        });
    });

    function certification(){
        var r = $('#pastor').valid()
        if(r){
            $('#pastor').submit();
        }else{
            if($('.pl').eq(0).css('display') == 'none'){
                alert('请切换到必填项填写完成')
            }
        }
    }
    $(function(){
        $(':file').click(function(){
            $('input[name="Certification[cardimgs]"]').change(function(i){
                if(i){
                    $('em[name=cardimg]').css('color','red');
                    $('em[name=cardimg]').html('已上传');
                }
            })
            $('input[name="Certification[law_cardnos]"]').change(function(a){
                if(a){
                    $('em[name=law_cardnos]').css('color','red');
                    $('em[name=law_cardnos]').html('已上传');
                }
            })
        })
    })

</script>