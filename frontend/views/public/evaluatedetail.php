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
    $pubEvaluate = \common\models\Evaluate::findAll(['product_id'=>$id,'category'=>$category,'uid'=>Yii::$app->user->getId()]);
    $otherEvaluate = \common\models\Evaluate::find()->andWhere(['product_id'=>$id,'category'=>$category])->andWhere("uid <> ".Yii::$app->user->getId())->all();



 ?>
<div class="pj">
    <div class="sd" style="overflow:hidden;">
        <div class="comment_left">
            <span class="fachu current">发出的评价</span>
            <span class="shoudao">收到的评价</span>
        </div>
        <?php if(isset($pubEvaluate[0])){
                if(!isset($pubEvaluate[1])){
            ?>

        <div class="icon_comment addevaluate">
            <a href="javascript:;"><img src="/images/icon-comment.png"/></a>
            <span>追加评价</span>
        </div>
        <?php }}else{?>

        <div class="icon_comment addevaluate">
            <a href="javascript:;"><img src="/images/icon-comment.png"/></a>
            <span>写评价</span>
        </div>
        <?php }?>
    </div>
    <div class="jxs">
        <div class="jx jx1">
            <?php if(isset($otherEvaluate[0])){?>
                <div class="shang">
                    <img src="/images/sj.png" height="38" width="44" alt="" class="m_src" />
                    <div class="pj_text1">
                        <p class="p_text"><?php echo $otherEvaluate[0]['content'];?></p>
                        <div class="dafen">
                            <span style="display:block;padding-left: 0px;" class="shuzi"><?php echo round(($otherEvaluate[0]->serviceattitude+$otherEvaluate[0]->professionalknowledge+$otherEvaluate[0]->workefficiency)/3.0,1)?></span>
						<span style="padding-left: 0px;" >
							<?php echo \frontend\services\Func::evaluateNumber(floor(($otherEvaluate[0]->serviceattitude+$otherEvaluate[0]->professionalknowledge+$otherEvaluate[0]->workefficiency)/3))?>
						</span>
                        </div>
                    </div>
                    <div class="tupian">
                        <?php $picture = array_filter(explode(',',$otherEvaluate[1]['picture']));
						if($picture){
                        foreach($picture as $p){ ?>
                            <span><img width="60" height="60" src="<?php echo trim($p,"'")?>"/> </span>
						<?php } } ?>
                    </div>
                    <div class="riqi">
                        <span><?php echo $otherEvaluate[0]['create_time']?date("Y.m.d",$otherEvaluate[0]['create_time']):'';?></span>

                    </div>
                </div>
            <?php } if(isset($otherEvaluate[1])){?>
                <div class="xia" style="border-top:1px solid #ccc;width:735px;margin:0 auto;">
                    <div class="pj_text1">
                        <p class="p_text">
                            <span class="zhuijia">追加评价:</span>
                            <?php echo $otherEvaluate[1]['content'];?>
                        </p>
                        <div class="dafen">
                            <span style="display:block;padding-left: 0px;" class="shuzi"><?php echo round(($otherEvaluate[1]->serviceattitude+$otherEvaluate[1]->professionalknowledge+$otherEvaluate[1]->workefficiency)/3.0,1)?></span>
						<span style="padding-left: 0px;" >
							<?php echo \frontend\services\Func::evaluateNumber(floor(($otherEvaluate[1]->serviceattitude+$otherEvaluate[1]->professionalknowledge+$otherEvaluate[1]->workefficiency)/3))?>
						</span>
                        </div>
                    </div>
                    <div class="tupian">
                        <?php $picture = array_filter(explode(',',$otherEvaluate[1]['picture']));
						if($picture){
                        foreach($picture as $p){ ?>
                            <span><img width="60" height="60" src="<?php echo trim($p,"'")?>"/> </span>
						<?php } } ?>
                    </div>
                    <div class="riqi">
                        <span><?php echo $otherEvaluate[1]['create_time']?date("Y.m.d",$otherEvaluate[0]['create_time']):'';?></span>
                    </div>
                </div>
            <?php }?>
        </div>
        <div class="jx jx2">
            <?php if(isset($pubEvaluate[0])){?>
                <div class="shang">
                    <img src="/images/sj.png" height="38" width="44" alt="" class="m_src" />
                    <div class="pj_text1">
                        <p class="p_text"><?php echo $pubEvaluate[0]['content'];?></p>
                        <div class="dafen">
                            <span style="display:block;padding-left: 0px;" class="shuzi"><?php echo round(($pubEvaluate[0]->serviceattitude+$pubEvaluate[0]->professionalknowledge+$pubEvaluate[0]->workefficiency)/3.0,1)?></span>
						<span style="padding-left: 0px;" >
							<?php echo \frontend\services\Func::evaluateNumber(floor(($pubEvaluate[0]->serviceattitude+$pubEvaluate[0]->professionalknowledge+$pubEvaluate[0]->workefficiency)/3))?>
						</span>
                        </div>
                    </div>
                    <div class="tupian">
                        <?php $picture = explode(',',$pubEvaluate[0]['picture']);
                            foreach($picture as $p){
                                ?>
                                <span><img width="60" height="60" src="<?php echo trim($p,"'")?>"/> </span>
                                <?php
                            }
                        ?>
                    </div>
                    <div class="riqi">
                        <span><?php echo $pubEvaluate[0]['create_time']?date("Y.m.d",$pubEvaluate[0]['create_time']):'';?></span>
                        <?php  if(!isset($pubEvaluate[1])){?><a href="javascript:void(0)" class="addevaluate">追加评价</a><?php }?><?php echo Html::hiddenInput("cateandid",$category.",".$id)?>
                    </div>
                </div>
            <?php } if(isset($pubEvaluate[1])){?>
                <div class="xia" style="border-top:1px solid #ccc;width:735px;margin:0 auto;">
                    <div class="pj_text1">
                        <p class="p_text">
                            <span class="zhuijia">追加评价:</span>
                            <?php echo $pubEvaluate[1]['content'];?>
                        </p>
                        <div class="dafen">
                            <span style="display:block;padding-left: 0px;" class="shuzi"><?php echo round(($pubEvaluate[1]->serviceattitude+$pubEvaluate[1]->professionalknowledge+$pubEvaluate[1]->workefficiency)/3.0,1)?></span>
						<span style="padding-left: 0px;" >
							<?php echo \frontend\services\Func::evaluateNumber(floor(($pubEvaluate[1]->serviceattitude+$pubEvaluate[1]->professionalknowledge+$pubEvaluate[1]->workefficiency)/3))?>
						</span>
                        </div>
                    </div>
                    <div class="tupian">
                        <?php $picture = explode(',',$pubEvaluate[1]['picture']);
                        foreach($picture as $p){
                            ?>
                            <span><img width="60" height="60" src="<?php echo trim($p,"'")?>"/> </span>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="riqi">
                        <span><?php echo $pubEvaluate[1]['create_time']?date("Y.m.d",$pubEvaluate[0]['create_time']):'';?></span>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.jx1').show()
        $('.sd .comment_left span').click(function(){
            $('.jxs>div').eq($(this).index()).css('display','block').siblings('div').css('display','none')
        })
    })

    $(document).ready(function(){
        $('.addevaluate').click(function () {
            var cateandid = "<?php echo $category.",".$id;?>";

            $.msgbox({
                closeImg: '/images/yuan2.png',
                height:700,
                width:760,
                content:'<?php echo Url::toRoute("/func/evaluate")?>/?cateandid='+cateandid,
                type :'ajax',
                title: '评价'
            });
        });

        $(document).delegate('.uploadSpan','click',function(){
            var name = $(this).next('input:hidden').attr("name");
            $.msgbox({
                closeImg: '/images/yuan2.png',
                async: false,
                height:530,
                width:630,
                title:'请选择图片',
                content:"<?php echo Url::toRoute(["/func/uploadsimg"])?>/?type="+name,
                type:'ajax'
            });
        });
        $(document).delegate('.uploadChakan','click',function(){
            var typeName = $(this).prev('input:hidden').attr("name");
            var name = $(this).prev('input:hidden').val();
            $.msgbox({
                closeImg: '/images/yuan2.png',
                async: false,
                height:530,
                width:630,
                title:'显示照片',
                content:"<?php echo Url::toRoute(["/func/viewimages"])?>/?name="+name+"&typeName="+typeName,
                type:'ajax'
            });
        });

    });
</script>