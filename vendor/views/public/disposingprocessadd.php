<!-- 填写进度开始 -->

<!-- 填写进度结束 -->
<!-- 分页 -->
<?php \yii\widgets\ActiveForm::begin(['id'=>'financing','method'=>'post','action'=>\yii\helpers\Url::to('/order/disposingprocessadd/')])?>
<span>添加进度</span>
<i></i>
<div class="yanchangs">

    <p id="process" class="zt">状态：<?php echo \yii\helpers\html::dropDownList('status','',frontend\services\Func::$Status)?><em></em>
    </p>
    <b>详情：</b><?php echo \yii\helpers\html::textarea('content','',['placeholder'=>'请填写进度','class'=>'reasons'])?><br/><em></em>

    <div style="margin-left: 43px;"><?php echo \yii\helpers\html::input('button','subbtn','提交',['onclick'=>'subFormAdd()','style'=>'margin:20px auto']);?></div>
    <?php echo \yii\helpers\html::input('hidden','product_id',$id);?>
    <?php echo \yii\helpers\html::input('hidden','category',$category);?>

</div>
<?php \yii\widgets\ActiveForm::end();?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#financing").validate({
            ignore: "",
            rules: {
                status: "required",
                content: "required",
            },
            messages: {
                status: "",
                content: "请填写进度",
            },
            errorPlacement: function(error, element) {
                 element.parent().children('em').html('&nbsp;'+error.html()+'&nbsp;').addClass('error');
            },
        });
    });

    function subFormAdd(){
            var r = $('#financing').valid()
            $('#financing').submit();
        }
</script>
