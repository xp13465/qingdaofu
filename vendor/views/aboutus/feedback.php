<?php
use yii\helpers\html;
?>
<?php \yii\widgets\ActiveForm::begin(['id'=>'feedback','method'=>'post','action'=>\yii\helpers\Url::to("/aboutus/feedback")])?>
<div class="m_intro">
    <div class="t_top">
        <img src="/images/house.png" height="33" width="33" alt="" />
        <h3>关于我们>意见反馈</h3>
    </div>
    <div class="retro">
        <div class="t_retro">
            <?php echo html::textarea('opinion','',['cols' =>'30','rows'=>'10','placeholder'=>'请输入您的意见反馈'])?>
            <img src="/images/advice.png" alt="" />
        </div>
        <div class="b_retro">
            <?php echo html::textarea('phone','',['cols' =>'30','rows'=>'10','placeholder'=>'请留下您的手机号码'])?>
            <img src="/images/advicep.png" alt="" />
        </div>
        <a href="#" onclick="opinions();">提交</a>
    </div>
</div>
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




