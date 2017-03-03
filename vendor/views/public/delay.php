<div class="yanqi" >
    <span>延期申请</span>
    <i></i>
    <?php \yii\widgets\ActiveForm::begin(['id'=>'delaying','method'=>'post','action'=>\yii\helpers\Url::to('/order/disposingdelayadd/')])?>
    <div class="yanchang">
        <p>延长申请提示: 距离案件结束还有*天,是否需要申请延长处置期限?
        <?php echo \yii\helpers\html::textarea('dalay_reason','',['placeholder'=>'请填写申请原因','class'=>'reasons'])?><br/>　　<em style="text-align: left;"></em></p>
        <p class="data">连续延长天数
            <?php echo \yii\helpers\html::input('text','delay_days','');?><em></em></p>

        <?php echo \yii\helpers\html::input('button','subbtn','是',['onclick'=>'subForm()','style'=>'margin:20px auto']);?>
        <?php echo \yii\helpers\html::input('hidden','product_id',$id);?>
        <?php echo \yii\helpers\html::input('hidden','category',$category);?>
    </div>
    <?php \yii\widgets\ActiveForm::end();?>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#delaying").validate({
            ignore: "",
            rules: {
                delay_days: "required",
                dalay_reason: "required",
            },
            messages: {
                delay_days: "请填写延长天数",
                dalay_reason: "请填写申请原因",
            },
            errorPlacement: function(error, element) {
                element.parent().children('em').html('&nbsp;'+error.html()+'&nbsp;').addClass('error');
            },
        });
    });


    function subForm(){
        $('#delaying').submit();
    }

</script>