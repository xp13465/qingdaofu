<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="lonely">
    <?php if($certification['category'] == 2){?>
    <div class="lone_1">
        <img src="/images/nav_2.png" height="35" width="840" alt="">
    </div>
    <?php }else{?>
        <div class="lone_1">
            <img src="/images/nav_1.png" height="35" width="840" alt="">
        </div>
    <?php }?>
    <div class="gr_info">
        <p><span><?php if($certification['category'] == 2){ echo '律所名称:'; }else{ echo '企业名称:';}?></span><?php echo $certification['name'] ?></p>
        <p><span><?php if($certification['category'] == 2){ echo '执照许可证号:'; }else{ echo '营业执照号:';}?></span><?php echo \frontend\services\Func::HideStrRepalceByChar($certification['cardno'],'*',4,4) ?>　　<em>已认证</em>
        </p>
        <?php if($certification['email']){?>
        <p><span>邮箱:</span><?php echo $certification['email'] ?> </p>
        <?php }else{echo'';}?>
        <?php if($certification['casedesc']){?>
        <p class="break"><span>经典案例:</span><b>
                <?php echo $certification['casedesc']?>
            </b></p>
        <?php }else{ echo'';}?>

    </div>
</div>
<!-- 律所星级评价 -->
<?php $creditor = Yii::$app->db->createCommand("select (sum(serviceattitude)+sum(professionalknowledge)+sum(workefficiency))/(3*count(buid)) from zcb_evaluate  where buid ={$certification['uid']}")->queryScalar(); ?>
<?php $youzhi = Yii::$app->db->createCommand("select count(youzhi) from zcb_evaluate  where buid ={$certification['uid']} && youzhi=1 ")->queryScalar(); ?>
<?php $zhuanye = Yii::$app->db->createCommand("select count(zhuanye) from zcb_evaluate  where buid ={$certification['uid']} && zhuanye=1 ")->queryScalar(); ?>
<?php $gaoxiao = Yii::$app->db->createCommand("select count(gaoxiao) from zcb_evaluate  where buid ={$certification['uid']} && gaoxiao=1 ")->queryScalar(); ?>
<?php $kuaijie = Yii::$app->db->createCommand("select count(kuaijie) from zcb_evaluate  where buid ={$certification['uid']} && kuaijie=1 ")->queryScalar(); ?>
<?php $serviceattitude = Yii::$app->db->createCommand("select sum(serviceattitude)/count(buid)from zcb_evaluate  where buid ={$certification['uid']}")->queryScalar(); ?>
<?php $professionalknowledge = Yii::$app->db->createCommand("select sum(professionalknowledge)/count(buid) from zcb_evaluate  where buid ={$certification['uid']}")->queryScalar(); ?>
<?php $workefficiency = Yii::$app->db->createCommand("select sum(workefficiency)/count(buid) from zcb_evaluate  where buid ={$certification['uid']}")->queryScalar(); ?>
<div class="history">
    <div class="lone_1">
        <img src="/images/nav2.png" height="36" width="840" alt="">
    </div>
    <div class="star_pj clearfix">
        <div class="star_l fl">
            <i></i>
            <p class="color"><?php echo round($creditor,1)?></p>
            <span>综合评价</span>
        </div>
        <div class="stars fl">
            <p><span>服务态度</span><?php echo \frontend\services\Func::evaluateNumber(round($serviceattitude))?></p>
            <p><span>专业知识</span><?php echo \frontend\services\Func::evaluateNumber(round($professionalknowledge))?></p>
            <p><span>办事效率</span><?php echo \frontend\services\Func::evaluateNumber(round($workefficiency))?></p>
        </div>
        <div class="fr">
            <p>
                <b>接单方印象:</b><span>高效(<?php echo $gaoxiao ?>)</span><span>优质(<?php echo $youzhi ?>)</span>
            </p>
            <p class="zys">
                <span>快捷(<?php echo $kuaijie ?>)</span><span>专业(<?php echo $zhuanye ?>)</span>
            </p>
        </div>
    </div>
    <!-- 律所星级评价 -->
    <div class="evaluate">
        <ul>
            <?php foreach($evaluate as $v):?>
                <?php $picture =explode(',',$v['picture']);?>
            <li class="clearfix">
                <div class="e_1">
                    <span><?php $mobile=\common\models\User::findOne(['id'=>$v['uid']]); echo isset($mobile['mobile'])?\frontend\services\Func::HideStrRepalceByChar($mobile['mobile'],'*',3,2):''?></span>
                    <span class="time2"><?php echo isset($v['create_time'])?date('Y-m-d',$v['create_time']):''?></span>
                    <span><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i><i class="nofill"></i></span>
                </div>
                <div class="e_r">
                    <p>
                        <?php echo isset($v['content'])?$v['content']:''?>
                    </p>
                </div>

                <div class="clearfix">
                    <?php foreach($picture as $vc => $k):?>
                        <?php if($k){ ?>
                    <div class="e_img"><img width="60" height="60" src="<?php echo 'http://zcb2016.com/'.substr($k, 1, -1)?>"/></div>
                        <?php } else {echo '';}?>
                    <?php endforeach;?>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<div class="fenye" style="margin-left:-43%;">
    <?php if($pagination->totalCount>$pagination->defaultPageSize){?>
        <?php echo '<span class="fenyes" style="font-size:16px;">'.'共'.(isset($pagination->totalCount)?$pagination->totalCount:0).
            '条记录'.'第'.(Yii::$app->request->get('page')?Yii::$app->request->get('page'):1).'/'.(isset($pagination->totalCount)?ceil($pagination->totalCount/$pagination->defaultPageSize):0)
            .'页'. '</span>';?>
        <?= linkPager::widget([
            'firstPageLabel' => '首页',
            'lastPageLabel' => '尾页',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'pagination' => $pagination,
            'maxButtonCount'=>4,
        ]);?>
    <?php }else{ echo '';} ?>

</div>