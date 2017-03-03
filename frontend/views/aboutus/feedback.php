<?php
use yii\helpers\Html;
?>
<?php \yii\widgets\ActiveForm::begin(['id'=>'feedback','method'=>'post']) ?>
<div class="m_intro intro_right_con">
    <div class="yj_top">
        <img src="/images/yijian.jpg" height="150" width="819" alt="" />      
    </div>
    <div class="in_top in_top1">
        <h3>您的意见</h3>
    </div>
    <div class="txt">
        <?php echo html::textarea('opinion','',['cols' =>'30','rows'=>'10','placeholder'=>'请详细描述您的问题或建议，您的反馈是我们前进的最大动力。'])?>
    </div>
    <div class="in_top in_top1">
        <h3 style="margin-top:-20px;">您的联系方式</h3>
    </div>
    <div class="haoma">
        <span>手机号码</span>
        <?php echo html::input('type','phone','',['cols' =>'30','rows'=>'10','placeholder'=>'请输入您的手机号码'])?>
    </div>
    <div class="anniu1">
        <a href="javascript:void(0);" class="anniu" onclick="opinions();">提交</a>
    </div>
            
</div>
<?php \yii\widgets\ActiveForm::end()?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#feedback').validate({
            ignore:"",
            rules:{
                opinion:'required',
                phone:'required',
            },
            messages:{
                opinion:'请输入反馈的问题',
                phone:'请输入联系方式',
            },
        });
    })
    function opinions(){
        var r = $('#feedback').valid();
        $('#feedback').submit();
    }
</script>





