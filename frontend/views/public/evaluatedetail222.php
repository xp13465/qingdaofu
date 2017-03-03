<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php
if($category == 1){
    $desc = \common\models\FinanceProduct::findOne(['category'=>$category,'id'=>$id]);
}elseif(in_array($category,[2,3])){
    $desc = \common\models\CreditorProduct::findOne(['category'=>$category,'id'=>$id]);
}else{

}

if(in_array($category,[1,2,3])){
$evaluate = \common\models\Evaluate::findOne(['product_id'=>$id,'category'=>$category,'uid'=>Yii::$app->user->getId()]);
if($evaluate)$evaluate2  = \common\models\Evaluate::findOne(['product_id'=>$id,'category'=>$category,'uid'=>Yii::$app->user->getId(),'pid'=>$evaluate->id]);
$evaluateOther = \common\models\Evaluate::find()->andWhere(['product_id'=>$id,'category'=>$category])->andWhere("uid <> ".Yii::$app->user->getId())->one();
if($evaluateOther)$evaluateOther2 = \common\models\Evaluate::find()->andWhere(['product_id'=>$id,'category'=>$category,'pid'=>$evaluateOther->id])->andWhere("uid <> ".Yii::$app->user->getId())->one();

 }?>


    <div class="pj_chos clearfix">
        <ul>
            <li class="pjs1"><a href="javascript:;" class="current">受托方评价</a></li>
            <li class="pjs2"><a href="javascript:;">我的评价</a></li>
            <li class="pjs3"><a href="javascript:;">评价</a></li>
        </ul>
    </div>

    <div class="stpj">
        <?php
        if($evaluateOther){
            ?>
            <div class="pinjia pinjia_1 clearfix">
                <span class="shoutuo">受托方评价</span>
                <i></i>
                <div class="stars fl">
                    <p>
                        <span><?php if($desc->uid == Yii::$app->user->getId()){echo "真实性";}else{echo "服务态度";}?></span><?php echo \frontend\services\Func::evaluateNumber($evaluateOther->serviceattitude)?></i>
                    </p>
                    <p class="zhuanye">
                        <span><?php if($desc->uid == Yii::$app->user->getId()){echo "配合度";}else{echo "专业知识";}?></span><?php echo \frontend\services\Func::evaluateNumber($evaluateOther->professionalknowledge)?>
                    </p>
                    <p>
                        <span><?php if($desc->uid == Yii::$app->user->getId()){echo "响应度";}else{echo "办事效率";}?></span><?php echo \frontend\services\Func::evaluateNumber($evaluateOther->workefficiency)?>
                    </p>
                </div>
                <div class="pj_text fl">
                    <p> <?php echo $evaluateOther->content;?></p>
                    <?php if(isset($evaluateOther->picture)&&$evaluateOther->picture){?>
                         <ul class="clearfix">
                             <li><img src="<?php echo 'http://zichanbao.com/'.$evaluateOther['picture'];?>" height="60" width="100" alt=""></li>
                         </ul>
                    <?php }else { echo '';}?>
                </div>
            </div>
        <?php }
        if(isset($evaluateOther2)){
            ?>
            <div class="pinjia pinjia_1 clearfix">
                <span class="shoutuo">追评</span>
                <i></i>
                <div class="stars fl">
                    <p>
                        <span><?php if($desc->uid == Yii::$app->user->getId()){echo "真实性";}else{echo "服务态度";}?></span><?php echo \frontend\services\Func::evaluateNumber($evaluateOther2->serviceattitude)?></i>
                    </p>
                    <p class="zhuanye">
                        <span><?php if($desc->uid == Yii::$app->user->getId()){echo "配合度";}else{echo "专业知识";}?></span><?php echo \frontend\services\Func::evaluateNumber($evaluateOther2->professionalknowledge)?>
                    </p>
                    <p>
                        <span><?php if($desc->uid == Yii::$app->user->getId()){echo "响应度";}else{echo "办事效率";}?></span><?php echo \frontend\services\Func::evaluateNumber($evaluateOther2->workefficiency)?>
                    </p>
                </div>
                <div class="pj_text fl">
                    <p> <?php echo $evaluateOther2->content;?></p>
                   <?php if(isset($evaluateOther2->picture)&&$evaluateOther2->picture){?>
                      <ul class="clearfix">
                       <li><img src="<?php echo 'http://zichanbao.com/'.$evaluateOther2['picture'];?>" height="60" width="100" alt=""></li>
                      </ul>
                <?php }else { echo '';}?>
                </div>
            </div>
        <?php }?>
    </div>

    <div class="mypj">
    <?php
    if($evaluate){
        ?>
        <div class="pinjia pinjia_1 clearfix">
            <span class="shoutuo">我的评价</span>
            <i></i>
            <div class="stars fl">
                <p>
                    <span><?php if($desc->uid == Yii::$app->user->getId()){echo "服务态度";}else{echo "真实性";}?></span><?php echo \frontend\services\Func::evaluateNumber($evaluate->serviceattitude)?></i>
                </p>
                <p class="zhuanye">
                    <span><?php if($desc->uid == Yii::$app->user->getId()){echo "专业知识";}else{echo "配合度";}?></span><?php echo \frontend\services\Func::evaluateNumber($evaluate->professionalknowledge)?>
                </p>
                <p>
                    <span><?php if($desc->uid == Yii::$app->user->getId()){echo "办事效率";}else{echo "响应度";}?></span><?php echo \frontend\services\Func::evaluateNumber($evaluate->workefficiency)?>
                </p>
            </div>
            <div class="pj_text fl">
                <p><?php echo $evaluate->content;?></p>
                <?php if(isset($evaluate->picture)&&$evaluate->picture){?>
                <ul class="clearfix">
                <li><img src="<?php echo 'http://zichanbao.com/'.$evaluate->picture;?>" height="60" width="100" alt=""></li>
                </ul>
                <?php }else{echo '';}?>
            </div>
        </div>
    <?php }
    if(isset($evaluate2)){ ?>
    <div class="pinjia pinjia_1 clearfix">
        <span class="shoutuo">　追评</span>
        <i></i>
        <div class="stars fl">
            <p>
                <span><?php if($desc->uid == Yii::$app->user->getId()){echo "服务态度";}else{echo "真实性";}?></span><?php echo \frontend\services\Func::evaluateNumber($evaluate2->serviceattitude)?>
            </p>
            <p class="zhuanye">
                <span><?php if($desc->uid == Yii::$app->user->getId()){echo "专业知识";}else{echo "配合度";}?></span><?php echo \frontend\services\Func::evaluateNumber($evaluate2->professionalknowledge)?>
            </p>
            <p>
                <span><?php if($desc->uid == Yii::$app->user->getId()){echo "办事效率";}else{echo "响应度";}?></span><?php echo \frontend\services\Func::evaluateNumber($evaluate2->workefficiency)?>
            </p>
        </div>
        <div class="pj_text fl">
            <p><?php echo $evaluate2->content;?></p>
           <?php if(isset($evaluate2->picture)&&$evaluate2->picture){?>
                <ul class="clearfix">
            <li><img src="<?php echo 'http://zichanbao.com/'.$evaluate2['picture'];?>" height="60" width="100" alt=""></li>
                </ul>
           <?php }else { echo '';}?>
        </div>
    </div>
    <?php }?>
</div>

<div class="pj_3">
    <?php if($evaluate){
        if($evaluate2){}
        else{
        ?>
        <?php \yii\widgets\ActiveForm::begin(['id'=>'add','method'=>'post','action'=>"/user/addevaluate",'options'=>['enctype'=>"multipart/form-data"],])?>
<div class="pinjia pinjia_1 clearfix">
    <span class="shoutuo">　追评</span>
    <i></i>
    <div class="stars fl">
        <p>
            <span><?php if($desc->uid == Yii::$app->user->getId()){echo "服务态度";}else{echo "真实性";}?></span><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i>　<span class="score" style="display: none">得分是 : <?php echo html::input('text','serviceattitude','serviceattitude',['style'=>'display:none'])?></span>　
        </p>
        <p class="zhuanye">
            <span><?php if($desc->uid == Yii::$app->user->getId()){echo "专业知识";}else{echo "配合度";}?></span><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i>　<span class="score" style="display: none">得分是 : <?php echo html::input('text','professionalknowledge','professionalknowledge',['style'=>'display:none'])?></span>　　
        </p>
        <p>
            <span><?php if($desc->uid == Yii::$app->user->getId()){echo "办事效率";}else{echo "响应度";}?></span><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i>　<span class="score" style="display: none">得分是 : <?php echo html::input('text','workefficiency','workefficiency',['style'=>'display:none'])?></span>
            　
        </p>
    </div>

    <div class="srpj">
        <div class="pjia">
            <?php echo html::textarea('content','',['rows' =>'4','cols'=>'50','placeholder'=>'请手动输入评价,最多输入不超过200个字.'])?>
            <input type="file" class="file_wj" accept = 'image/*' name="Evaluate[picture]"/> <span><img src="/images/camera.jpg" height="36" width="49" alt="" >　<em name="picture" style="font-size:12px;"></em></span>
            <?php echo html::input('text','category',$category,['style'=>'display:none']); ?>
            <?php echo html::input('text','product_id',$id,['style'=>'display:none']); ?>
        </div>
    </div>

    <input type="button" value="确定" class="evaluateAddc" onclick="evalu();" >
</div>
    </div>
<?php \yii\widgets\ActiveForm::end() ?>
<script>
    $(document).ready(function (){
        $('.pinjia i').click(function(){
            $(this).addClass('fill');
            $(this).siblings('span').find('input').val($(this).index());
            $(this).prevAll('i').addClass("fill");
            $(this).nextAll('i').removeClass("fill");
        })
        $('.pinjia i').dblclick(function(){
            $('.shoupin i').removeClass("fill");
            $(".score input").val('');
        });
        $("#add").validate({
            ignore: "",
            rules: {
                serviceattitude: "required",
                professionalknowledge:"required",
                workefficiency:"required",
            },
            messages: {
                serviceattitude: "请选择服务态度",
                professionalknowledge:"请选择专业知识",
                workefficiency:"请选择办事效率",
            },
        });
    });
    function evalu(){
        var r = $('#add').valid()
        $('#add').submit();
    }

    $(function(){
        $(':file').click(function(){
            $('input[name="Evaluate[picture]"]').change(function(i){
                if(i){
                    $('em[name=picture]').css('color','red');
                    $('em[name=picture]').html('已上传');
                }
            })
        })
    })
    </script>
<?php }}else{?>
<?php \yii\widgets\ActiveForm::begin(['id'=>'add','method'=>'post','action'=>"/user/addevaluate",'options'=>['enctype'=>"multipart/form-data"],])?>
<div class="pinjia pinjia_1 clearfix">
    <span class="shoutuo">　评价</span>
    <i></i>
    <div class="stars fl">
        <p>
            <span><?php if($desc->uid == Yii::$app->user->getId()){echo "服务态度";}else{echo "真实性";}?></span><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i>　<span class="score" style="display: none">得分是 : <?php echo html::input('text','serviceattitude','serviceattitude',['style'=>'display:none'])?></span>　
        </p>
        <p class="zhuanye">
            <span><?php if($desc->uid == Yii::$app->user->getId()){echo "专业知识";}else{echo "配合度";}?></span><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i>　<span class="score" style="display: none">得分是 : <?php echo html::input('text','professionalknowledge','professionalknowledge',['style'=>'display:none'])?></span>　　
        </p>
        <p>
            <span><?php if($desc->uid == Yii::$app->user->getId()){echo "办事效率";}else{echo "响应度";}?></span><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i>　<span class="score" style="display: none">得分是 : <?php echo html::input('text','workefficiency','workefficiency',['style'=>'display:none'])?></span>
            　
        </p>
    </div>

    <div class="srpj">
        <div class="pjia">
            <?php echo html::textarea('content','',['rows' =>'4','cols'=>'50','placeholder'=>'请手动输入评价,最多输入不超过200个字.'])?>
            <input type="file" class="file_wj" accept = 'image/*' name="Evaluate[picture]"/> <span><img src="/images/camera.jpg" height="36" width="49" alt="" >　<em name="picture" style="font-size:12px;"></em></span>
            <?php echo html::input('text','category',$category,['style'=>'display:none']); ?>
            <?php echo html::input('text','product_id',$id,['style'=>'display:none']); ?>
        </div>
    </div>

    <input type="button" value="确定" class="evaluateAddc" onclick="evalu();" >
</div>

<?php \yii\widgets\ActiveForm::end() ?>
<script>
    $(document).ready(function (){
        $('.pinjia i').click(function(){
            $(this).addClass('fill');
            $(this).siblings('span').find('input').val($(this).index());
            $(this).prevAll('i').addClass("fill");
            $(this).nextAll('i').removeClass("fill");
        })
        $('.pinjia i').dblclick(function(){
            $('.shoupin i').removeClass("fill");
            $(".score input").val('');
        });
        $("#add").validate({
            ignore: "",
            rules: {
                serviceattitude: "required",
                professionalknowledge:"required",
                workefficiency:"required",
            },
            messages: {
                serviceattitude: "请选择服务态度",
                professionalknowledge:"请选择专业知识",
                workefficiency:"请选择办事效率",
            },
        });
    });
    function evalu(){
        var r = $('#add').valid()
        $('#add').submit();
    }

    $(function(){
        $(':file').click(function(){
            $('input[name="Evaluate[picture]"]').change(function(i){
                if(i){
                    $('em[name=picture]').css('color','red');
                    $('em[name=picture]').html('已上传');
                }
            })
        })
    })
    </script>
<?php }?>

<script>
    $('.stpj').show();
    $('.mypj').hide();
    $('.pj_3').hide();

    $('.pjs1').click(function(){
        $('.stpj').css('display','block');
        $('.mypj ,.pj_3').css('display','none');
    })
    $('.pjs2').click(function(){
        $('.mypj').css('display','block');
        $('.stpj,.pj_3').css('display','none');
    })
    $('.pjs3').click(function(){
        $('.pj_3').css('display','block');
        $('.mypj,.stpj').css('display','none');
    })
    $('.pj_chos li a').click(function(){
        $(this).addClass('current').parent().siblings('li').find('a').removeClass('current')
    })
</script>
</body>
</html>