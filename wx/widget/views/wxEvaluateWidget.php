<section>
    <div class="apply_yy">
        <div class="apply_fen">
            <span></span>
            <span>收到的评价 (<?php echo round($creditor,1)?>分)</span>
            <?php if(!$evaluate || $evaluate ==''){ echo"";}else{ ?><a href="<?php echo yii\helpers\Url::toRoute(['/usercenter/received','certi'=>isset($pid)?$pid:'']);?>"  class="check01" style="color:#999;">查看全部</a><?php }?>
        </div>
        <?php if(!$evaluate || $evaluate ==''){ echo"";}else{ ?>
            <?php foreach($evaluate as $value){ ?>
                <ul class="apply_num">
                     <li>
                         <p style="color:#666;">13393736381<i style="float:right;">2016-08-02 17:55</i></p>
                         <p><?php echo isset($value['mobile'])?\frontend\services\Func::HideStrRepalceByChar($value['mobile'],'*',3,4):''?></p>
                         <div>
                             <span><?php echo \frontend\services\Func::evaluateNumber(round($value['creditor']))?></span>
                         </div>
                         <p><?php echo isset($value['content'])?$value['content']:''?></p>
                         <?php $picture =explode(',',$value['picture']);?>
                         <div class="square">
                             <?php foreach($picture as $vc => $k){ ?>
                                 <?php if($k){ ?>
                                     <span><img style="height:50px;width:50px; display:inline-block;" src="<?php echo Yii::$app->params['wx'].str_replace("'",'',$k)?>"/></span>
                                 <?php } else {echo '';}?>
                             <?php } ?>
                         </div>
                         <?php $b = [0=>'今天的追评',1=>'1天后的追评',2=>'2天后的追评']?>
                         <?php if(isset($value['create_times'])&&$value['create_times']!==''){ ?>
                             <p class="blue"><?php if($value['create_times'] < 0){echo '3天后追评';}elseif($value['create_times'] >= 3){echo '3天后追评';}else{echo $b[$value['create_times']];}?></p>
                         <?php } ?>
                         <p><?php echo isset($value['contents'])?$value['contents']:''?></p>
                     </li>
                </ul>
            <?php } ?>
        <?php } ?>
    </div>
</section>