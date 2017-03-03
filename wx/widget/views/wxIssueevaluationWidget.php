<?php if(!$launchevaluation || $launchevaluation =='' ){ echo ""; }else{ ?>
<section>
    <div class="pj_a">
        <div class="jd_detail">
            <span class="show_line"></span>
            <span class="service">给出的评价</span>
        </div>
    <?php foreach($launchevaluation as $value){ ?>
        <ul class="apply_num">
            <li>
                <p class="blue"><?php echo isset($value['mobile'])?\frontend\services\Func::HideStrRepalceByChar($value['mobile'],'*',3,4):''?></p>
                <p class="star_a">
                    <?php echo \frontend\services\Func::evaluateNumber(round($creditor))?>
                </p>
                <?php $picture =explode(',',$value['picture']);?>
                <div class="figure">
                    <?php foreach($picture as $vc => $k){ ?>
                        <?php if($k){ ?>
                            <span style="margin:13px;"><img style="height:50px;width:50px; display:inline-block;" src="<?php echo Yii::$app->params['wx'].str_replace("'",'',$k)?>"/></span>
                        <?php } else {echo '';}?>
                    <?php } ?>
                </div>
                <p><?php echo isset($value['content'])?$value['content']:''?></p>
                <?php $b = [0=>'今天的追评',1=>'1天后的追评',2=>'2天后的追评']?>
                <?php if(isset($value['create_times'])&&$value['create_times']!==''){ ?>
                    <p class="blue"><?php if($value['create_times'] < 0){echo '3天后追评';}elseif($value['create_times'] >= 3){echo '3天后追评';}else{echo $b[$value['create_times']];}?></p>
                <?php } ?>
                <p><?php echo isset($value['contents'])?$value['contents']:''?></p>
            </li>
        </ul>
    <?php } ?>
    </div>
</section>
<?php } ?>