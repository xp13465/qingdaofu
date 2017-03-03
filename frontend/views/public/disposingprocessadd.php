<!-- 填写进度开始 -->

<!-- 填写进度结束 -->
<!-- 分页 -->
<?php
use yii\helpers\Html;
use \common\models\CreditorProduct;
use yii\helpers\Url;
?>
<?php
if($category == 1){
    $user = \common\models\FinanceProduct::findOne(['category'=>$category,'id'=>$id]);
}elseif(in_array($category,[2,3])){
    $user = \common\models\CreditorProduct::findOne(['category'=>$category,'id'=>$id]);
}
?>
<?php \yii\widgets\ActiveForm::begin(['id'=>'financing','method'=>'post','action'=>\yii\helpers\Url::toRoute('/order/disposingprocessadd/')]);?>
<div class="speed">
    <?php echo \yii\helpers\Html::input('hidden','product_id',$id); ?>
    <?php echo \yii\helpers\Html::input('hidden','category',$category); ?>
    <p class="border_l color">处置进度</p>

    
    <?php if($user->uid == Yii::$app->user->getId() || in_array($category,[1,2])){?>
        <?php echo "";?>
    <?php }else{?>
        <p class="anhao">
            <label for="">案号 : </label>
            <?php echo \yii\helpers\Html::dropDownList('audit','',frontend\services\Func::$case);?>
            <?php echo \yii\helpers\Html::input('text','case','');?><br/>
        </p>
    <?php } ?>
    <span class="state"><b>请选择处置类型</b><i class="arrow_s"></i></span>
    <?php echo \yii\helpers\Html::textarea('content','',['placeholder'=>'请填写进度','cols'=>'30','rows'=>'10']);?>
    <div class="new_cz">
        <div class="clearfix">
            <?php echo \yii\helpers\Html::tag('a','添加新进度',['onclick'=>'subFormAdd()','class'=>'add']);?>
        </div>
        <?php if($category == 1){?>
            <!-- 融资 -->
            <div class="checkboxs rz_box" style="display:none; width:500px;">
                <?php echo Html::radio('status','',['value'=>1])?>尽职调查
                <?php echo Html::radio('status','',['value'=>2])?>公证
                <?php echo Html::radio('status','',['value'=>3])?>抵押
                <?php echo Html::radio('status','',['value'=>4])?>放款
                <?php echo Html::radio('status','',['value'=>5])?>返点
                <?php echo Html::radio('status','',['value'=>6])?>其他
            </div>
        <?php }else if($category == 2){ ?>
            <!-- 清收 -->
            <div class="checkboxs cs_box" style="display:none;">
                <?php echo Html::radio('status','',['value'=>1])?>电话
                <?php echo Html::radio('status','',['value'=>2])?>上门
                <?php echo Html::radio('status','',['value'=>3])?>面谈
            </div>
        <?php }else{?>
            <!-- 诉讼 -->
            <div class="checkboxs ss_box" style="display:none;">
                <p>
                    <?php echo Html::radio('status','',['value'=>1,'style'=>'margin-left:0;']);?>债权人上传处置资产
                    <?php echo Html::radio('status','',['value'=>2])?>律师接单
                    <?php echo Html::radio('status','',['value'=>3,'style'=>'margin-left:98px']);?>双方洽谈
                </p>
                <p>
                    <?php echo Html::radio('status','',['value'=>4,'style'=>'margin-left:0;']);?>向法院起诉（财产保全）
                    <?php echo Html::radio('status','',['value'=>5,'style'=>'margin-left:22px;']);?>整理诉讼材料
                    <?php echo Html::radio('status','',['value'=>6,'style'=>'margin-left:70px']);?>法院立案
                </p>
                <p>
                    <?php echo Html::radio('status','',['value'=>7,'style'=>'margin-left:0;']);?>向当事人发出开庭传票
                    <?php echo Html::radio('status','',['value'=>8,'style'=>'margin-left:36px;']);?>开庭前调解
                    <?php echo Html::radio('status','',['value'=>9,'style'=>'margin-left:84px;']);?>开庭
                </p>
                <p>
                    <?php echo Html::radio('status','',['value'=>10,'style'=>'margin-left:0;']);?>判决
                    <?php echo Html::radio('status','',['value'=>11,'style'=>'margin-left:149px;']);?>二次开庭
                    <?php echo Html::radio('status','',['value'=>12,'style'=>'margin-left:98px;']);?>二次判决
                </p>
                <p>
                    <?php echo Html::radio('status','',['value'=>13,'style'=>'margin-left:0;']);?>移交执行局申请执行
                    <?php echo Html::radio('status','',['value'=>14,'style'=>'margin-left:51px;']);?>执行中提供借款人的财产线索
                </p>
                <p>
                    <?php echo Html::radio('status','',['value'=>15,'style'=>'margin-left:0;']);?>调查（公告）
                    <?php echo Html::radio('status','',['value'=>16,'style'=>'margin-left:93px;']);?>拍卖
                    <?php echo Html::radio('status','',['value'=>17,'style'=>'margin-left:126px;']);?>流拍
                </p>
                <p>
                    <?php echo Html::radio('status','',['value'=>18,'style'=>'margin-left:0;']);?>拍卖成功
                    <?php echo Html::radio('status','',['value'=>19,'style'=>'margin-left:121px;']);?>付费
                </p>
            </div>
        <?php }?>
    </div>
</div>
<?php \yii\widgets\ActiveForm::end();?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#financing").validate({
            ignore: "",
            rules: {
                status: "required",
            },
            messages: {
                status: "",
            },
            errorPlacement: function(error, element) {
                 element.parent().children('em').html('&nbsp;'+error.html()+'&nbsp;').addClass('error');
            },
        });
        var category = "<?php echo $category;?>";
        $('.state').click(function(){
            $(this).find('i').toggleClass('arrow_x');
            if(category == 1){
                $('.rz_box').stop().fadeToggle('fast');
            }else if(category == 2){
                $('.cs_box').stop().fadeToggle('fast');
            }else{
                $('.ss_box').stop().fadeToggle('fast');
            }
        })
    });
    function subFormAdd(){
            var r = $('#financing').valid()
            $('#financing').submit();
        }
    $(document).ready(function(){
        var category ="<?php echo $category?>";
        if(category == 1){
            $('.rz_box').click(function(){
                var radio = $('input:radio:checked').val();
                var url = "<?php echo yii\helpers\Url::toRoute('/order/radio')?>";
                $.post(url,{radio:radio,category:category},function(v){
                    $('.state b').html(v);
                    $('.rz_box').stop().fadeOut('fast');
                    $('.state').find('i').removeClass('arrow_x');
                },'json');
            })
        }else if(category == 2){
            $('.cs_box').click(function(){
                var radio = $('input:radio:checked').val();
                var url = "<?php echo yii\helpers\Url::toRoute('/order/radio')?>";
                $.post(url,{radio:radio,category:category},function(v){
                    $('.state b').html(v);
                    $('.cs_box').stop().fadeOut('fast');
                    /*$('.state').find('i').addClass('arrow_x');*/
                    $('.state').find('i').removeClass('arrow_x');
                },'json');
            })
        }else{
            $('.ss_box').click(function(){
                var radio = $('input:radio:checked').val();
                var url = "<?php echo yii\helpers\Url::toRoute('/order/radio')?>";
                $.post(url,{radio:radio,category:category},function(v){
                    $('.state b').html(v);
                    $('.ss_box').stop().fadeOut('fast');
                    $('.state').find('i').removeClass('arrow_x');
                },'json');
            })
        }


    })

</script>
