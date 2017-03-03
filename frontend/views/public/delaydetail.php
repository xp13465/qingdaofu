<!-- 延期展示 -->
<?php
$delay = \common\models\DelayApply::findOne(['category'=>$category,'product_id'=>$id,'is_agree'=>0]);
if(!$delay){return '';}
$apply = \common\models\Apply::findOne(['category'=>$category,'product_id'=>$id,'app_id'=>1]);
if($category == 1){
    $desc = \common\models\FinanceProduct::findOne(['category'=>$category,'id'=>$id]);
}elseif(in_array($category,[2,3])){
    $desc = \common\models\CreditorProduct::findOne(['category'=>$category,'id'=>$id]);
}else{

}
?>
<div class="zhanshi button">
    <p>延期时间:<?php echo date("Y年m月d日 H:i",strtotime(date("Y-m-d 23:59:59",$apply->agree_time).' + '.($desc->term+$delay->delay_days)." days "))?></p>
    <p>延期原因:<?php echo $delay->dalay_reason;?></p>
    <input type="button"  value="同意" onclick="agreeSub(<?php echo $delay->id;?>)"/>
    <input class="fou" type="button"  value="驳回"  onclick="disagreeSub(<?php echo $delay->id;?>)"/>
</div>

<script type="text/javascript">
    function agreeSub(id){
        $.ajax(
            {
                url: '<?php echo \yii\helpers\Url::toRoute('/apply/isagree');?>',
                type: 'post',
                data: {id: id, is_agree: 1},
                dataType: 'html',
                success: function (html) {
                    if (html == 1) {
                        alert('已同意');
                        window.location = window.location;
                    } else {
                        alert('验证失败');
                    }
                },
            }
        );
    }
    function disagreeSub(id){
        $.ajax(
            {
                url:'<?php echo \yii\helpers\Url::toRoute('/user/isagree');?>',
                type:'post',
                data:{id:id,is_agree:2},
                dataType:'html',
                success:function(html){
                    if(html == 1){
                        alert('已拒绝');
                        window.location = window.location;
                    }else{
                        alert('验证失败');
                    }
                },
            }
        );
    }
</script>