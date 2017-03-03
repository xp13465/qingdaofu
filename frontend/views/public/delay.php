<?php if(in_array($category,[2,3])){?>
    <?php
    $delay = \common\models\DelayApply::findOne(['category'=>$category,'product_id'=>$id]);
    $apply = \common\models\Apply::findOne(['category'=>$category,'product_id'=>$id,'app_id'=>1]);
    if($category == 1){
        $desc = \common\models\FinanceProduct::findOne(['category'=>$category,'id'=>$id]);
    }elseif(in_array($category,[2,3])){
        $desc = \common\models\CreditorProduct::findOne(['category'=>$category,'id'=>$id]);
    }
    if($category == 1 && $desc['rate_cat'] == 1){
        $delays = floor((strtotime(date("Y-m-d 23:59:59",$apply['agree_time']).' + '.$desc['term']." days ")-time())/3600/24) ;
    }else if($category == 1 && $desc['rate_cat'] == 2){
        $delays = floor((strtotime('+'.$desc['term']."months",$apply['agree_time'])-time())/3600/24) ;
    }else{
        $delays = floor((strtotime('+'.$desc['commissionperiod']."months",$apply['agree_time'])-time())/3600/24) ;
    }
    ?>
    <?php if($delays <=7 && $delay['uid'] != \yii::$app->user->getId()){?>
    <div class="liji clearfix">
        <span class="icon_1">距离案件处理结束日期还有<b class="color"><?php echo isset($delays)&&$delays < 0 ? 0 : $delays ;?></b>天,可提前7天申请延期，只可申请一次！</span><a href="javascript:;" class="sq" style="background: #4196d5;color: #fff;">立即申请</a>
    </div>
        <?php }else if(isset($delay['is_agree'])&&$delay['is_agree'] == 0){ ?>
        <div class="liji clearfix">
            <span class="icon_2">申请成功，等待发布确认。</span>
        </div>
        <?php }else if(isset($delay['is_agree'])&&$delay['is_agree'] == 1){ ?>
        <div class="liji clearfix">
            <span class="icon_4">申请成功，案件到期时间为<b class="color"><?php echo date("Y-m-d H:i",strtotime(date("Y-m-d 23:59:59",$apply['agree_time']).' + '.$desc['term']." days "))?></b>。</span>
        </div>
        <?php }else if(isset($delay['is_agree'])&&$delay['is_agree'] == 2){ ?>
        <div class="liji clearfix">
            <span class="icon_3">申请失败，案件与<b class="color">2016-03-09</b>正常结案。</span>
        </div>
    <?php }else{ ?>
        <div class="liji clearfix">
            <span class="icon_1">距离案件处理结束日期还有<b class="color"><?php echo isset($delays)&&$delays < 0 ? 0 : $delays;?></b>天,可提前7天申请延期，只可申请一次！</span><a href="javascript:;" class="sqs">立即申请</a>
        </div>
    <?php }?>
<div class="yanqi" style="display:none;">
    <span class="bord_l">延期申请</span>
    <!-- <i></i>  -->
    <?php \yii\widgets\ActiveForm::begin(['id'=>'delaying','method'=>'post','action'=>\yii\helpers\Url::toRoute('/order/disposingdelayadd/')])?>
    <div class="yanchang button">
        <p>延长申请提示: 距离案件结束还有<?php echo isset($delays)&&$delays < 0 ? 0 : $delays ;?>天,是否需要申请延长处置期限?
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
        $('.sq').click(function(){
            $('.yanqi').fadeToggle(1000);
        })
    });


    function subForm(){
        $('#delaying').submit();
    }

</script>
<?php }?>