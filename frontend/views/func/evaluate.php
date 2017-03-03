
<?php
$cateandid = Yii::$app->request->get('cateandid');

list($category,$id) = explode(",",$cateandid);
if($category == 1){
    $desc = \common\models\FinanceProduct::findOne(['category'=>$category,'id'=>$id]);
}elseif(in_array($category,[2,3])){
    $desc = \common\models\CreditorProduct::findOne(['category'=>$category,'id'=>$id]);
}else{

}
?>
<script src="/js/jquery-1.11.1.js"></script>
<script src="/js/msgbox/jquery.msgbox.js"></script>
<link rel="stylesheet" href="/css/base.css">
<link rel="stylesheet" href="/css/style.css">
<?php \yii\widgets\ActiveForm::begin(['id'=>'addevaluate','method'=>'post','action'=>"/func/evaluate",'options'=>['enctype'=>"multipart/form-data"],])?>
<div class="alert_pj">
    <p>
        您的<b><?php echo \frontend\services\Func::$category[$category];?></b>单号为:<em class="color"><?php echo $desc->code;?></em>已经结束，感谢您对平台的支持，您可以在以下空白处留下评价，获得更多好评噢，谢谢!</p>
    <div class="ts">　　<img src="/images/starblue.png" height="30" width="411" alt=""></div>
    <div class="stars">
        <p>
            <span class="color">真实性</span><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i>　<span class="score serviceattitude"><?php echo \yii\helpers\Html::hiddenInput('serviceattitude')?></span>　
        </p>
        <p>
            <span class="color">配合度</span><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i>　<span class="score professionalknowledge" ><?php echo \yii\helpers\Html::hiddenInput('professionalknowledge')?></span>　　
        </p>
        <p>
            <span class="color">响应度</span><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i>　<span class="score workefficiency"><?php echo \yii\helpers\Html::hiddenInput('workefficiency')?></span>    　
        </p>
    </div>
    <div class="srpj">
        <div class="pjia">
            <textarea name="content" id="content" cols="30" rows="10" placeholder="请写下您的真实感受，对接单方的帮助改进很大哦"></textarea>
           <span class="uploadSpan"><img src="/images/camera.jpg" height="36" width="49" alt="" ><em name="picture" style="font-size:12px;"></em></span><?php echo \yii\helpers\Html::hiddenInput('picture');?><span class="uploadChakan">查看</span>

        </div>
    </div>
    <p class="high">您还可以选择:<a href="javascript:;"><?php echo \yii\helpers\Html::checkbox('zhuanye',1)?>专业</a><a href="javascript:;"><?php echo \yii\helpers\Html::checkbox('youzhi',1)?>优质</a><a href="javascript:;"><?php echo \yii\helpers\Html::checkbox('gaoxiao',1)?>高效</a><a href="javascript:;"><?php echo \yii\helpers\Html::checkbox('kuaijie',1)?>快捷</a></p>
    <p class="nm"><input type="checkbox" name="isHide" value="1"> 匿名评价<?php echo \yii\helpers\Html::hiddenInput('category',$category);?><?php echo \yii\helpers\Html::hiddenInput('product_id',$id);?></p>
    <div class="pjs">
        <a href="javascript:;" class="evaluateLater">稍后评价</a><a href="javascript:;" class="evaluateSave">评价</a><a href="javascript:;" class="defaultSave">默认评价</a>
    </div>
</div>
<?php \yii\widgets\ActiveForm::end() ?>
<script>
    $(function(){
        $('.stars i').click(function(){
            $(this).addClass('fill');
            $(this).siblings('span').find('input').val($(this).index());
            $(this).prevAll('i').addClass("fill");
            $(this).nextAll('i').removeClass("fill");
        })
        $('.stars i').dblclick(function(){
            $(this).parent('p').find('i').removeClass("fill");
            $(".score input").val('');
        });

        $('.evaluateSave').click(
            function(){
                var serviceattitude = $("input[name='serviceattitude']").val();
                var professionalknowledge = $("input[name='professionalknowledge']").val();
                var workefficiency = $("input[name='workefficiency']").val();
                var content = $('#content').val();

                if(serviceattitude==0||serviceattitude == ''){alert($("input[name='serviceattitude']").parent().parent().children('.color').html()+"不能为空");return false;}
                if(professionalknowledge==0||professionalknowledge == ''){alert($("input[name='professionalknowledge']").parent().parent().children('.color').html()+"不能为空");return false;}
                if(workefficiency==0||workefficiency == ''){alert($("input[name='workefficiency']").parent().parent().children('.color').html()+"不能为空");return false;}
                if(content  == ''){alert("评价内容不能为空");return false;}
                $.ajax({
                    url:"<?php echo \yii\helpers\Url::toRoute('/func/evaluate')?>",
                    type:"post",
                    data:$('#addevaluate').serialize(),
                    dataType:'html',
                    success: function (html) {
                        if(html == 1){
                            $(".msgbox_wrapper").css('display','none');
                            $(".msgbox_bg").css('display','none');
                            $("#jMsgboxBox").css('display','none');
                            window.location = window.location;
                        }
                    }
                });
            }
        );
        $('.defaultSave').click(
            function(){
                $.ajax({
                    url:"<?php echo \yii\helpers\Url::toRoute('/func/evaluate')?>",
                    type:"post",
                    data:{
                        serviceattitude:'5',
                        professionalknowledge:'5',
                        workefficiency:'5',
                        zhuanye:'1',
                        gaoxiao:'1',
                        kuaijie:'1',
                        youzhi:'1',
                        content:'优质专业高效快捷',
                        category:'<?php echo $category;?>',
                        product_id:'<?php echo $id;?>'
                    },
                    dataType:'html',
                    success: function (html) {
                        if(html == 1){
                            $(".msgbox_wrapper").css('display','none');
                            $(".msgbox_bg").css('display','none');
                            $("#jMsgboxBox").css('display','none');
                            window.location = window.location;
                        }
                    }
                });
            }
        );

        $('.evaluateLater').click(
            function(){
                $(".msgbox_wrapper").css('display','none');
                $(".msgbox_bg").css('display','none');
                $("#jMsgboxBox").css('display','none');
            }
        );



    })
</script>