<?php
use yii\helpers\Url;
?>
<!-- banner 开始-->
<div class="list_banner">
    <img src="/images/zc_list1.jpg" height="228" width="1920" alt="">
</div>
<!-- banner 结束-->

<!-- 内容开始-->
<div class="bg">
    <div class="ckrz">
        <h2><span class="color"><?php echo isset($finance['code'])?substr( $finance['code'],0,2):'';?></span><?php echo isset($finance['code'])?substr( $finance['code'],2):''?></h2>
        <div class="ckxq">
            <ul>
                <li>
                    <span>借款本金:</span>
                    <span><em class="color1"><?php echo isset($finance['money'])?$finance['money']:'';?></em>万</span>
                </li>
                <li>
                    <span>借款期限:</span>
                    <span><em><?php echo isset($finance['term'])?$finance['term']:'';?></em>个月</span>
                </li>
                <li>
                    <span>借款利率:</span>
                    <span><em><?php echo isset($finance['rate'])?$finance['rate']:'';?>%</em><?php if($finance['category'] == 1){echo \common\models\FinanceProduct::$ratedatecategory[$finance['rate_cat']];}else{echo \common\models\CreditorProduct::$ratedatecategory[$finance['rate_cat']];}?></span>
                </li>
                <li class="lastLi">
                    <?php $app = Yii::$app->db->createCommand("select count(*) from zcb_apply where category = {$finance['category']} and product_id = {$finance['id']} and app_id = 0")->queryScalar(); ?>
                    <?php $order= Yii::$app->db->createCommand("select count(*) from zcb_apply where category = {$finance['category']} and product_id = {$finance['id']} and app_id = 2")->queryScalar(); ?>
                    <span>申请数:<?php echo $app;?>人</span>
                    <span>收藏数:<?php echo $order;?>人</span>
                    <span>浏览次数:<?php  echo isset($finance['browsenumber'])&& $finance['browsenumber'] == ''? 0 : $finance['browsenumber']?>人</span>
                </li>
            </ul>
        </div>
        <p class="hk">
            <?php if($finance['category'] ==1){?>
            借款本金:<i><?php echo isset($finance['money'])?$finance['money']:''?>万</i><br/>
            期限:<i><?php echo isset($finance['term'])?$finance['term']:''?>天</i><br/>
            利率:<i><?php echo isset($finance['rate'])?$finance['rate']:''?>%<?php echo \common\models\FinanceProduct::$ratedatecategory[$finance['rate_cat']];?></i><br/>
            返点:<i><?php echo isset($finance['rebate'])?$finance['rebate']:''?> </i><br>
            资金到账日:<i> <?php echo isset($finance['fundstime'])?date('Y年m月d日',$finance['fundstime']):''?></i><br/>
            抵押物面积:<i><?php echo isset($finance['mortgagearea'])?$finance['mortgagearea']:''?>m2</i><br/>
            抵押物地址:<i><?php echo isset($finance['city_id']) && $finance['city_id']== 310100 ?'上海市':''?><?php $v = common\models\Area::findOne(['areaID'=>(isset($finance['district_id'])&& $finance['district_id'] == 0)?'1':$finance['district_id']]); echo $v->area;?></i><br>
            <?php if($finance['mortgagecategory']){echo '抵押物类型:'.'<i>'.\common\models\FinanceProduct::$mortgagecategory[$finance['mortgagecategory']].'</i>'."<br>";}else{echo '';}?>
            <?php if($finance['rentmoney']){echo '租金:'.'<i>'.$finance['rentmoney'].'元'.'</i>'."<br>" ;}else{echo '';}?>
            <?php if($finance['status']){echo '状态:'.'<i>'.\common\models\FinanceProduct::$status[$finance['status']].'</i>'."<br>" ;}else{echo '';}?>
            <?php if($finance['mortgagestatus']){echo '抵押物状况:'.'<i>'.\common\models\FinanceProduct::$mortgagestatus[$finance['mortgagestatus']].'</i>'."<br>" ;}else{echo '';}?>
            <?php if($finance['loanyear']){echo '借款年龄:'.'<i>'.$finance['loanyear'].'</i>'."<br>" ;}else{echo '';}?>
            <?php if($finance['obligeeyear']){echo '权利人年龄:'.'<i>'.\common\models\FinanceProduct::$obligeeyear[$finance['obligeeyear']].'</i>'."<br>" ;}else{echo '';}?>
        <?php }else{ ?>
        <?php $value = unserialize($finance['guaranteemethod']);
        $guar_other = $value['other'];
        unset($value['other']);
        ?>
        <?php $judicial = unserialize($finance['judicialstatusA'])?>
        <?php $creditorinfo = unserialize($finance['creditorinfo'])?>
        <?php $borrowinginfo = unserialize($finance['borrowinginfo'])?>
        还款方式 ：<i><?php echo \common\models\CreditorProduct::$repaymethod[$finance['repaymethod']]?></i> <br>
        担保方式：<i><?php foreach($value as $ke => $va):?>
                <?php echo \common\models\CreditorProduct::$guaranteemethod[$va];?>
            <?php endforeach;?><?php if($guar_other){echo $guar_other;}?></i> <br>
        担保物所在地：<i><?php echo isset($finance['seatmortgage'])?\frontend\services\Func::getSubstrs($finance['seatmortgage']):'';?></i><br>
        司法现状：<i><?php foreach($judicial as $ke => $va):?>
                <?php echo \common\models\creditorProduct:: $judicialstatusA[isset($va)?$va:''];?>
            <?php endforeach;?></i> <br>
        债务人主体：<i><?php echo \common\models\creditorProduct::$obligor[isset($finance['obligor'])?$finance['obligor']:''];?></i> <br>
        委托事项：<i><?php echo \common\models\creditorProduct::$commitment[isset($finance['commitment'])?$finance['commitment']:''];?></i> <br>
        委托期限：<i><?php echo \common\models\creditorProduct::$commissionperiod[isset($finance['commissionperiod'])?$finance['commissionperiod']:''];?>月</i> <br>
        代理费用：<i><?php echo \common\models\creditorProduct::$agencycommissiontype[isset($finance['agencycommissiontype'])?$finance['agencycommissiontype']:''];?>
            <?php if($finance['agencycommissiontype']==1){
                echo $finance['agencycommission'].'元';
            }else{
                echo $finance['agencycommission'].'%';
            }?>
        </i> <br>
        付款方式：<i><?php echo \common\models\creditorProduct::$repaymethod[isset($finance['paymethod'])?$finance['paymethod']:''];?></i> <br><br>
        <?php if($finance['paidmoney']){echo '已付本金:'.$finance['paidmoney'].'元'."<br>";}else{echo '';}?>
        <?php if($finance['interestpaid']){echo '已付利息:'.$finance['interestpaid'].'元'."<br>";}else{echo '';}?>
        <?php if($finance['performancecontract']){echo '合同履行地:'.$finance['performancecontract']."<br>";}else{echo '';}?>
        </p>

        <div class="wenjian">
            <p>债权文件</p>
            <p><a href="#">《公证书》、</a><a href="#">《借款合同》、</a><a href="#">《他项权证》、</a><a href="#">《收据》、</a>
                <a href="#">《还款凭证》</a>
            </p>
        </div>

    </div>
    <div class="xinxi clearfix">
        <div class="zqf">

            <p><span class="display">债权方</span><b class="display">信息</b></p>
            <p class="ziliao">
                <?php if(isset($creditorinfo)&&$creditorinfo):?>
                <?php foreach($creditorinfo as $value):?>
                姓名：<i><?php echo isset($value['creditorname'])?\frontend\services\Func::HideStrRepalceByChar($value['creditorname'],'*',1,0):''?></i><br>
                联系方式：<i><?php echo isset($value['creditormobile'])?\frontend\services\Func::HideStrRepalceByChar($value['creditormobile'],'*',3,4):''?></i> <br>
                联系地址：<i><?php echo isset($value['creditoraddress'])?\frontend\services\Func::HideStrRepalceByChar($value['creditoraddress'],'*',4,4):''?></i> <br>
                证件号：<i><?php echo isset($value['creditorcardcode'])?\frontend\services\Func::HideStrRepalceByChar($value['creditorcardcode'],'*',4,4):''?></i>
                <?php endforeach;?>
                <?php endif;?>
            </p>
        </div>
        <div class="qkf">
            <p><span class="display">债务方</span><b class="display">信息</b></p>
            <p class="ziliao">
                <?php if(isset($borrowinginfo)&&$borrowinginfo):?>
                <?php foreach($borrowinginfo as $v):?>
                姓名：<i><?php echo isset($v['borrowingname'])?\frontend\services\Func::HideStrRepalceByChar($v['borrowingname'],'*',1,0):''?> </i><br>
                联系方式：<i><?php echo isset($v['borrowingmobile'])?\frontend\services\Func::HideStrRepalceByChar($v['borrowingmobile'],'*',3,4):''?></i> <br>
                联系地址：<i><?php echo isset($v['borrowingaddress'])?\frontend\services\Func::HideStrRepalceByChar($v['borrowingaddress'],'*',4,4):''?></i> <br>
                证件号：<i><?php echo isset($v['borrowingcardcode'])?\frontend\services\Func::HideStrRepalceByChar($v['borrowingcardcode'],'*',4,4):''?></i>
                <?php endforeach;?>
                <?php endif; ?>
            </p>

        </div>
        <?php }?>
    </div>
    <?php if($finance['uid'] == \Yii::$app->user->getId() || $finance['progress_status'] == 2 || $finance['progress_status'] == 3){?>
              <?php echo '';?>
    <?php }else{?>
        <div class="btns" style="height:80px;width:1200px;">
            <input type="button" value="收　藏" onclick="order(<?php echo $finance['category'] ?>,<?php echo $finance['id']?>,<?php echo $finance['uid']?>)">　　　　　　　　　　
            <input type="button" value="申　请" onclick="applys('<?php echo $finance['category']?>','<?php echo $finance['id']?>',<?php echo $finance['uid']?>)">
        </div>
    <?php }?>

</div>
<!-- 内容结束-->
<script type="text/javascript">
     function order(category,id,uid){
         var url = "<?php echo Url::to('/capital/collectionlist')?>";
         $.post(url,{category:category,id:id,uid:uid},function(v){
               if(v == 0){
                   alert('请登录用户');
                   location.href = "<?php echo Url::to('/site/login')?>";
               }else if (v == 1){
                   alert('请不要收藏自己发布的数据');
               }else if(v == 2){
                   alert('请不要重复操作');
                   location.href="<?php echo Url::to('/order/ordersave')?>";
               }else{
                   alert('收藏成功');
                   location.href = "<?php echo Url::to('/order/ordersave')?>";
               }
         },'json')
     }
     function applys(category,id,uid){
         var url = "<?php echo Url::to('/capital/applysuccessful')?>";
         $.post(url,{category:category,id:id,uid:uid},function(i){
              if(i == 0){
                  alert('请登录用户');
                  location.href="<?php echo Url::to('/site/login')?>";
              }else if (i== 1){
                  alert('请不要申请自己发布的数据');
              }else if(i == 2){
                  alert('请不要重新申请');
                  location.href="<?php echo Url::to('/order/orderapply')?>";
              }else{
                  alert('申请成功');
                  location.href="<?php echo Url::to('/order/orderapply')?>";
              }
         },'json');
     }

</script>
