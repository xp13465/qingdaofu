<?php

if($category == 1){
    $details = \common\models\FinanceProduct::findOne(['id'=>$id,'category'=>$category]);
}else if(in_array($category,[2,3])){
    $details = \common\models\CreditorProduct::findOne(['id'=>$id,'category'=>$category]);
}else{
    die;
}
?>


<?php

if($category == 1){
    ?>
    <p class="wz">
        借款本金:<?php echo isset($details['money'])?$details['money']:''?>万元<br/>
        期限:<?php echo isset($details['term'])?$details['term']:''?><?php echo \common\models\FinanceProduct::$ratedatecategory[$details['rate_cat']];?><br/>
        利率:<?php echo isset($details['rate'])?$details['rate']:''?>%<?php echo \common\models\FinanceProduct::$ratedatecategory[$details['rate_cat']];?><br/>
        返点:<?php echo isset($details['rebate'])?$details['rebate']:''?> <br>
        资金到账日: <?php echo isset($details['fundstime'])?date('Y年m月d日',$details['fundstime']):''?><br/>
        抵押物面积:<?php echo isset($details['mortgagearea'])?$details['mortgagearea']:''?>m2<br/>
        抵押物地址:<?php echo isset($details['city_id']) && $details['city_id']== 310100 ?'上海市':''?><?php $v = common\models\Area::findOne(['areaID'=>(isset($details['district_id'])&& $details['district_id'] == 0)?'1':$details['district_id']]); echo $v->area;?><?php echo isset($details['seatmortgage'])?$details['seatmortgage']:''?><br>
        <?php if($details['mortgagecategory']){echo '抵押物类型:'.\common\models\FinanceProduct::$mortgagecategory[$details['mortgagecategory']]."<br>";}else{echo '';}?>
        <?php if($details['rentmoney']){echo '租金:'.$details['rentmoney'].'元'."<br>" ;}else{echo '';}?>
        <?php if($details['status']){echo '状态:'.\common\models\FinanceProduct::$status[$details['status']]."<br>" ;}else{echo '';}?>
        <?php if($details['mortgagestatus']){echo '抵押物状况:'.\common\models\FinanceProduct::$mortgagestatus[$details['mortgagestatus']]."<br>" ;}else{echo '';}?>
        <?php if($details['loanyear']){echo '借款年龄:'.$details['loanyear']."<br>" ;}else{echo '';}?>
        <?php if($details['obligeeyear']){echo '权利人年龄:'.\common\models\FinanceProduct::$obligeeyear[$details['obligeeyear']]."<br>" ;}else{echo '';}?>
    </p>
<?php
}else if(in_array($category,[2,3])){
    ?>
    <?php $value = unserialize($details['guaranteemethod']);
    $guar_other = $value['other'];
    unset($value['other']);
    ?>
    <?php $judicial = unserialize($details['judicialstatusA'])?>
    <?php $credi = unserialize($details['creditorfile'])?>
    <?php $creditorinfo = unserialize($details['creditorinfo'])?>
    <?php $borrowinginfo = unserialize($details['borrowinginfo'])?>
    <span><?php echo \common\models\CreditorProduct::$categorys[$details['category']]?>详情</span>
    <i></i>
    <p class="wz">
        借款本金:<?php echo isset($details['money'])?$details['money']:''?>万元<br/>
        借款期限:<?php echo isset($details['term'])?$details['term']:''?>月<br/>
        借款利率:<?php echo isset($details['rate'])?$details['rate']:''?>%<?php echo \common\models\CreditorProduct::$ratedatecategory[$details['rate_cat']];?><br/>
        还款方式:<?php echo \common\models\CreditorProduct::$repaymethod[$details['repaymethod']]?><br/>
        担保方式:<?php foreach($value as $ke => $va):?>
            <?php echo \common\models\CreditorProduct::$guaranteemethod[$va];?>
        <?php endforeach;?><?php if($guar_other){echo $guar_other;}?><br/>
        担保物(抵押物)所在地:<?php echo isset($details['city_id']) && $details['city_id']== 310100 ?'上海市':''?><?php $v = common\models\Area::findOne(['areaID'=>(isset($details['district_id'])&& $details['district_id'] == 0)?'1':$details['district_id']]); echo $v->area;?><?php echo isset($details['seatmortgage'])?$details['seatmortgage']:''?><br/>
        司法现状:(1)仲裁:<?php foreach($judicial as $ke => $va):?>
            <?php echo \common\models\creditorProduct:: $judicialstatusA[isset($va)?$va:''];?>
        <?php endforeach;?><br/>
        <em>(2)欠款人是否能正常联系:
            <?php echo \common\models\creditorProduct:: $judicialstatusB[isset($details['judicialstatusB'])?$details['judicialstatusB']:''];?>
        </em><br/>
        债务人主体:<?php echo \common\models\creditorProduct::$obligor[isset($details['obligor'])?$details['obligor']:''];?><br/>
        委托事项:<?php echo \common\models\creditorProduct::$commitment[isset($details['commitment'])?$details['commitment']:''];?><br/>
        委托期限:<?php echo \common\models\creditorProduct::$commissionperiod[isset($details['commissionperiod'])?$details['commissionperiod']:''];?>月<br/>
        代理费用:<?php echo \common\models\creditorProduct::$agencycommissiontype[isset($details['agencycommissiontype'])?$details['agencycommissiontype']:''];?>
                  <?php if($details['agencycommissiontype']==1){
                      echo $details['agencycommission'].'元';
                  }else{
                      echo $details['agencycommission'].'%';
                  }?><br/>
        付款方式:<?php echo \common\models\creditorProduct::$repaymethod[isset($details['paymethod'])?$details['paymethod']:''];?><br>



        <?php if($details['paidmoney']){echo '已付本金:'.$details['paidmoney'].'元'."<br>";}else{echo '';}?>
        <?php if($details['interestpaid']){echo '已付利息:'.$details['interestpaid'].'元'."<br>";}else{echo '';}?>
        <?php if($details['performancecontract']){echo '合同履行地:'.$details['performancecontract']."<br>";}else{echo '';}?>


        <?php if( $credi['imgnotarization'] ||
                   $credi['imgcontract'] ||
                   $credi['imgcreditor'] ||
                   $credi['imgpick'] ||
                   $credi['imgshouju'] ||
                   $credi['imgbenjin']
                    ){
             echo '债权文件上传:'."<br>";
             }else {
              echo '';
             }
    ?>
        <?php if($credi['imgnotarization']){echo '《公证书》:'.'<span class="suolve" status="0">详情<i class="box"></i></span>'."<br>";}else{echo '';}?>
        <?php if($credi['imgcontract']){echo '《接款合同》:'.'<span class="suolve" status="1">详情<i class="box"></i></span>'."<br>";}else{echo '';}?>
        <?php if($credi['imgcreditor']){echo '《他项权证》:'.'<span class="suolve" status="2">详情<i class="box"></i></span>'."<br>";}else{echo '';}?>
        <?php if($credi['imgpick']){echo '《付款凭证》:'.'<span class="suolve" status="3">详情<i class="box"></i></span>'."<br>";}else{echo '';}?>
        <?php if($credi['imgshouju']){echo '《收据》:'.'<span class="suolve" status="4">详情<i class="box"></i></span>'."<br>";}else{echo '';}?>
        <?php if($credi['imgbenjin']){echo '《还款凭证(本金、利息)》:'.'<span class="suolve" status="5">详情<i class="box"></i></span>'."<br>";}else{echo '';}?>




        <?php if($details['uid'] == \Yii::$app->user->getId() || $details['progress_status'] == 2 ){?>
            <?php foreach($creditorinfo as $value):?>
                <?php
                if($value['creditorname'] ||
                    $value['creditormobile'] ||
                    $value['creditoraddress'] ||
                    $value['creditorcardcode'] ){
                    echo '债权方信息:'."<br>";
                }else {
                    echo '';
                }
                ?>
        <?php if($value['creditorname']){echo '姓名：'.$value['creditorname']."<br>";}else{echo '';}?>
        <?php if($value['creditormobile']){echo '联系方式：'.$value['creditormobile']."<br>";}else{echo '';}?>
        <?php if($value['creditoraddress']){echo '联系地址：'.$value['creditoraddress']."<br>";}else{echo '';}?>
        <?php if($value['creditorcardcode']){echo '证件号：'.$value['creditorcardcode']."<br>";}else{echo '';}?>
            <?php endforeach ?>




            <?php foreach($borrowinginfo as $v):?>
                <?php if($v['borrowingname'] ||
                    $v['borrowingmobile'] ||
                    $v['borrowingaddress'] ||
                    $v['borrowingcardcode']
                ){
                    echo '欠款方信息:'."<br>";
                }else {
                    echo '';
                }?>
                <?php if($v['borrowingname']){echo '姓名：'.$v['borrowingname']."<br>";}else{echo '';}?>
                <?php if($v['borrowingmobile']){echo '联系方式：'.$v['borrowingmobile']."<br>";}else{echo '';}?>
                <?php if($v['borrowingaddress']){echo '联系地址：'.$v['borrowingaddress']."<br>";}else{echo '';}?>
                <?php if($v['borrowingcardcode']){echo '证件号：'.$v['borrowingcardcode']."<br>";}else{echo '';}?>
            <?php endforeach ?>
        <?php }else{?>
            <?php foreach($creditorinfo as $value):?>
                <?php
                if($value['creditorname'] ||
                    $value['creditormobile'] ||
                    $value['creditoraddress'] ||
                    $value['creditorcardcode'] ){
                    echo '债权方信息:'."<br>";
                }else{
                    echo '';
                }
                ?>
        <?php if($value['creditorname']){echo '姓名：'.\frontend\services\Func::HideStrRepalceByChar($value['creditorname'],'*',1,0)."<br>";}else{echo '';}?>
        <?php if($value['creditormobile']){echo '联系方式：'.\frontend\services\Func::HideStrRepalceByChar($value['creditormobile'],'*',4,4)."<br>";}else{echo '';}?>
        <?php if($value['creditoraddress']){echo '联系地址：'.\frontend\services\Func::HideStrRepalceByChar($value['creditoraddress'],'*',4,10)."<br>";}else{echo '';}?>
        <?php if($value['creditorcardcode']){echo '证件号：'.\frontend\services\Func::HideStrRepalceByChar($value['creditorcardcode'],'*',4,2)."<br>";}else{echo '';}?>
            <?php endforeach ?>



            <?php foreach($borrowinginfo as $v):?>
                <?php if($v['borrowingname'] ||
                    $v['borrowingmobile'] ||
                    $v['borrowingaddress'] ||
                    $v['borrowingcardcode']
                ){
                    echo '欠款方信息:'."<br>";
                }else {
                    echo '';
                }?>
                <?php if($v['borrowingname']){echo '姓名：'.\frontend\services\Func::HideStrRepalceByChar($v['borrowingname'],'*',1,0)."<br>";}else{echo '';}?>
                <?php if($v['borrowingmobile']){echo '联系方式：'.\frontend\services\Func::HideStrRepalceByChar($v['borrowingmobile'],'*',4,4)."<br>";}else{echo '';}?>
                <?php if($v['borrowingaddress']){echo '联系地址：'.\frontend\services\Func::HideStrRepalceByChar($v['borrowingaddress'],'*',4,10)."<br>";}else{echo '';}?>
                <?php if($v['borrowingcardcode']){echo '证件号：'.\frontend\services\Func::HideStrRepalceByChar($v['borrowingcardcode'],'*',4,2)."<br>";}else{echo '';}?>
            <?php endforeach ?>
                <?php } ?>

                
    </p>
    <script type="text/javascript">
        $(function(){
            $('.box').hide();
            $('.suolve').hover(function(){
                var img ={
                    0:"<?php echo $credi['imgnotarization']?>",
                    1:"<?php echo $credi['imgcontract']?>",
                    2:"<?php echo $credi['imgcreditor']?>",
                    3:"<?php echo $credi['imgpick']?>",
                    4:"<?php echo $credi['imgshouju']?>",
                    5:"<?php echo $credi['imgbenjin']?>",
                };
                var img_index = $(this).attr('status');
                $('i.box').html('<img src="/'+img[img_index]+'" >');
                /*$('i.box').html('<img src="/g.jpg" >');*/
                $('i.box').find('img').each(function(){
                    var $imgH = $('i.box').outerHeight();
                    var $imgW = $('i.box').outerWidth();
                    $('i.box').find('img').css({
                        "width":$imgW,
                        "height":$imgH
                    })

                })
                $(this).find('i').stop().fadeToggle(500);
            });
        });

    </script>
    <?php
       }else{
       die;
}
?>


