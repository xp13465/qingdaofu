<?php

if(in_array($category,[1,2,3])){
    $evaluate = \common\models\Evaluate::findOne(['product_id'=>$id,'category'=>$category,'uid'=>Yii::$app->user->getId()]);
    $evaluateOther = \common\models\Evaluate::find()->andWhere(['product_id'=>$id,'category'=>$category])->andWhere("uid <> ".Yii::$app->user->getId())->one();

    if($evaluateOther){
?>
    <div class="pjdetail">
        <span>对方评价详情</span>
        <i></i>
    </div>
    <div class="pinjia xianshi">

        <div class="stars">
            <p>
                <span>服务态度</span><?php echo \frontend\services\Func::evaluateNumber($evaluateOther->serviceattitude)?></i>
            </p>
            <p class="zhuanye">
                <span>专业知识</span><?php echo \frontend\services\Func::evaluateNumber($evaluateOther->professionalknowledge)?>
            </p>
            <p>
                <span>办事效率</span><?php echo \frontend\services\Func::evaluateNumber($evaluateOther->workefficiency)?>
            </p>
        </div>
        <div class="pj_con">
            <p class="">
                <?php echo $evaluateOther->content;?>
            </p>
            <span></span>
        </div>
    </div>
        <?php }if($evaluate){
?>
    <div class="pjdetail">
        <span>评价详情</span>
        <i></i>
    </div>
    <div class="pinjia xianshi">

        <div class="stars">
            <p>
                <span>服务态度</span><?php echo \frontend\services\Func::evaluateNumber($evaluate->serviceattitude)?></i>
            </p>
            <p class="zhuanye">
                <span>专业知识</span><?php echo \frontend\services\Func::evaluateNumber($evaluate->professionalknowledge)?>
            </p>
            <p>
                <span>办事效率</span><?php echo \frontend\services\Func::evaluateNumber($evaluate->workefficiency)?>
            </p>
        </div>
        <div class="pj_con">
            <p class="">
                <?php echo $evaluate->content;?>
            </p>
            <span></span>
        </div>
    </div>
<?php
    }else{?>
    <div class="pinjia">
        <span>评价详情</span>
        <i></i>
        <div class="stars">
            <p>
                <span>服务态度</span><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i>　<span class="score">得分是 : <b id = "serviceattitude"></b></span>　
            </p>
            <p class="zhuanye">
                <span>专业知识</span><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i>　<span class="score">得分是 : <b id = "professionalknowledge"></b></span>　　
            </p>
            <p>
                <span>办事效率</span><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i>　<span class="score">得分是 : <b id = "workefficiency"></b></span>
                　
            </p>
        </div>
        <div class="srpj">
            <textarea id = "content" rows="4" cols="50" placeholder="请手动输入评价,最多输入不超过200个字."></textarea>
            <input type="file">
            <span> <img src="/images/camera.jpg" height="36" width="49" alt=""></span>
        </div>
        <input type="button" value="确定" class="evaluateAdd">
    </div>
    <script>
        $(function(){
            $('.score').hide();
            $('i').click(function(){
                $(this).addClass('fill')
                $(this).siblings('span').find('b').text($(this).index());
                $(this).siblings('.score').show();
                $(this).prevAll('i').addClass("fill");
                $(this).nextAll('i').removeClass("fill");
            })
            $('i').dblclick(function(){
                $('i').removeClass("fill");
            });

        })


        $(document).ready(function (){
            $('.evaluateAdd').click(function () {
                $.ajax({
                    url:'<?php echo \yii\helpers\Url::to('/user/addevaluate')?>',
                    type:'post',
                    data:{
                        serviceattitude:$('#serviceattitude').html(),
                        professionalknowledge:$('#professionalknowledge').html(),
                        workefficiency:$('#workefficiency').html(),
                        content:$('#content').val(),
                        category:<?php echo $category;?>,
                        product_id:<?php echo $id;?>
                    },
                    dataType:'html',
                    success:function(html){
                        if(html == 1){
                            window.location.reload();
                        }else{
                            alert('提交失败');
                        }
                    }
                })
            });
        });

    </script>
<?php
}}else{

}
?>
