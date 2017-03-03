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
    <div class="content_right">
        <div class="rongzi">
            <p class="title_rz clearfix">
                <span class="base fl current" onclick='change(this,0);'>　基本信息</span>
                <span class="supplement fr" onclick='change(this,1);'>　　补充信息</span>
            </p>
            <div class="rz_detail">
                <div class="rz1 x1">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <th>
                                金额<b>(万元)</b>
                            </th>
                            <td>
                                <?php echo isset($details['money'])?$details['money']:''?>
                            </td>
                            <th>
                                返点<b>(%)</b>
                            </th>
                            <td>
                                <?php echo isset($details['rebate'])?$details['rebate']:''?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                利息<b>(%)</b>
                            </th>
                            <td>
                                <?php echo isset($details['rate'])?$details['rate']:''?>(<?php echo isset($details['rate_cat'])&&$details['rate_cat'] == 1?'天':'个月';?>)
                            </td>
                            <th>
                                委托期限
                            </th>
                            <td>
                                <div class="qixian w100 display">
                                    <?php echo isset($details['term'])?$details['term']:''?><?php echo $details['rate_cat'] ==1 ? '天':'个月';?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                抵押物面积<b>(㎡)</b>
                            </th>
                            <td>
                                <?php echo isset($details['mortgagearea'])?$details['mortgagearea']:''?>
                            </td>
                            <th>
                                预期资金到账日
                            </th>
                            <td>
                                <?php echo isset($details['fundstime'])?date('Y年m月d日',$details['fundstime']):''?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                抵押物地址
                            </th>
                            <td colspan="3">
                                <?php echo isset($details['city_id']) && $details['city_id']== 310100 ?'上海市':''?>
                                <?php $v = common\models\Area::findOne(['areaID'=>(isset($details['district_id'])&& $details['district_id'] == 0)?'1':$details['district_id']]); echo isset($v->area)?$v->area:'';?>
                                <?php echo isset($details['seatmortgage'])?\frontend\services\Func::getSubstrs($details['seatmortgage']):'';?>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
        <div class="rz1 rz_bc x1">
            <table>
                <tr>
                    <th>
                        抵押物类型
                    </th>
                    <td>
                        <div class="display w160">
                            <?php echo isset($details['mortgagecategory'])?\common\models\FinanceProduct::$mortgagecategory[$details['mortgagecategory']]:'暂无' ?>
                        </div>
                    </td>
                    <th>
                        状态
                    </th>
                    <td>
                        <div class="display w160">
                            <?php echo isset($details['status'])?\common\models\FinanceProduct::$status[$details['status']]:'暂无'?>
                            <?php if($details['status'] == 2){echo isset($details['rentmoney'])?$details['rentmoney'].'元':'';}else{echo '';}?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>
                        抵押物状况
                    </th>
                    <td>
                        <div class="display w160">
                            <?php echo isset($details['mortgagestatus'])?\common\models\FinanceProduct::$mortgagestatus[$details['mortgagestatus']]:'暂无'?>
                        </div>
                    </td>
                    <th>借款人年龄</th>
                    <td>
                        <?php echo isset($details['loanyear'])?$details['loanyear'].'岁':'暂无'?>
                    </td>
                </tr>

                <tr>
                    <th>权利人年龄</th>
                    <td colspan="3">
                        <div class="display age">
                            <?php echo isset($details['obligeeyear'])?\common\models\FinanceProduct::$obligeeyear[$details['obligeeyear']]:'暂无'?>

                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <script type="text/javascript">
            $(function(){
                $('.x1').hide();
                change('',0);
            });
            function change(ob,num){
                $('.x1').hide();
                $('.x1').eq(num).show();
                $(ob).addClass('current').siblings('span').removeClass('current');
            }
        </script>
    </div>
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
    <div class="content_right">
    <p class="title_rz clearfix">
        <span class="base fl current" onclick='change(this,0);'>　基本信息</span>
        <span class="supplement fr" onclick='change(this,1);'>　　补充信息</span>
    </p>
    <div class="rz1 collection tr">
        <table>
            <tr>
                <th>
                    借款本金<b>(万元)</b>
                </th>
                <td class="w150">
                    <?php echo isset($details['money'])?$details['money']:'暂无'?>
                </td>
                <th>
                    借款期限<b>(月)</b>
                </th>
                <td>
                    <?php echo isset($details['term'])?$details['term']:'暂无'?>
                </td>
            </tr>
            <tr>
                <th>
                    借款利率<b>(%)</b>
                </th>
                <td class="w150">
                    <?php echo isset($details['rate'])?$details['rate']:''?>
                    (<?php echo isset($details['rate_cat'])&&$details['rate_cat'] == 1?'天':'个月';?>)
                </td>
                <th>
                    还款方式
                </th>
                <td colspan="2">
                    <div class="display">
                        <?php echo isset($details['repaymethod'])?\common\models\CreditorProduct::$repaymethod[$details['repaymethod']]:'暂无'?>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    抵押<b>(可多选)</b>
                </th>
                <td colspan="3">
                    <div class="display">
                        <?php foreach($value as $ke => $va):?>
                            <?php echo \common\models\CreditorProduct::$guaranteemethod[$va];?>
                        <?php endforeach;?>
                        <?php if($guar_other){echo $guar_other;}?>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    担保物(抵押物)所在地
                </th>
                <td colspan="3">
                    <?php echo isset($details['city_id']) && $details['city_id']== 310100 ?'上海市':''?>
                    <?php $v = common\models\Area::findOne(['areaID'=>(isset($details['district_id'])&& $details['district_id'] == 0)?'1':$details['district_id']]); echo isset($v->area)?$v->area:'';?>
                    <?php echo isset($details['seatmortgage'])?\frontend\services\Func::getSubstrs($details['seatmortgage']):'';?>
                </td>
            </tr>

            <tr>
                <th rowspan="2">
                    司法现状
                </th>
                <td colspan="3">
                    <div class="display">
                        <?php foreach($judicial as $ke => $va):?>
                            <?php echo isset($va)?\common\models\creditorProduct:: $judicialstatusA[$va]:'暂无';?>
                        <?php endforeach;?>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div class="display">
                        债务人是否能正常联系:
                        <?php echo isset($details['judicialstatusB'])?\common\models\creditorProduct:: $judicialstatusB[$details['judicialstatusB']]:'暂无';?>
                    </div>
                </td>
            </tr>

            <tr>
                <th>
                    债务人主体
                </th>
                <td colspan="3">
                    <div class="display">
                        <?php echo isset($details['obligor'])?\common\models\creditorProduct::$obligor[$details['obligor']]:'暂无';?>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    委托期限<b>(月)</b>
                </th>
                <td colspan="3">
                    <?php echo isset($details['commissionperiod'])?$details['commissionperiod']:'暂无';?>
                </td>
            </tr>

        </table>
    </div>
    <div class="table tr">
        <table style="width:830px;"cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <th>已付本金(元)</th>
                <td><?php echo isset($details['paidmoney'])?$details['paidmoney']:'暂无'?></td>
                <th>已付利息(元)</th>
                <td><?php echo isset($details['interestpaid'])?$details['interestpaid']:'暂无'?></td>
            </tr>
            <tr>
                <th>合同履行地</th>
                <td colspan="3"><?php echo isset($details['performancecontract'])?$details['performancecontract']:'暂无'?></td>
            </tr>
            <?php if(isset($category)&&$category == 2 ){?>
                <tr>
                    <th>代理费用</th>
                    <td class="switch">
                        <?php echo isset($details['agencycommissiontype'])&&$datails['agencycommissiontype']==1?'服务佣金':'固定费用';?>
                        <?php if($details['agencycommissiontype']==1){
                            echo '<span>'.$details['agencycommission'].'</span>%';
                        }else{
                            echo '<span>'.$details['agencycommission'].'</span>万';
                        }?>
                    </td>
                </tr>
            <?php }else{?>
                <tr>
                    <th>代理费用</th>
                    <td class="switch">
                        <?php echo isset($details['agencycommissiontype'])?\common\models\creditorProduct::$agencycommissiontype[$details['agencycommissiontype']]:'暂无';?>
                        <?php if($details['agencycommissiontype']==1){
                            echo '<span>'.$details['agencycommission'].'</span>万';
                        }else{
                            echo '<span>'.$details['agencycommission'].'</span>%';
                        }?>
                    </td>
                </tr>
            <?php }?>
            <tr>
                <th>债权文件上传</th>
                <td colspan="3" style="position:relative;">
                    <span class="tp"></span>
                    <div class="ys">
                        公证书<span class="color uploadsChe" id="1">查看、</span>　
                        借款合同<span class="color uploadsChe" id="2">查看、</span>　
                        他项权证<span class="color uploadsChe" id="3">查看、</span>
                        付款凭证<span class="color uploadsChe" id="4">查看、</span>
                        收据<span class="color uploadsChe" id="5">查看、</span>
                        还款凭证<span class="color uploadsChe" id="6">查看</span>
                        <span>(请您根据类别来上传)</span>
                    </div>
                </td>
            </tr>
            <?php if($details['uid'] == \Yii::$app->user->getId() || $details['progress_status'] == 2 ){?>
                <?php foreach($creditorinfo as $value):?>
                    <?php
                    if($value['creditorname'] ||
                        $value['creditormobile'] ||
                        $value['creditoraddress'] ||
                        $value['creditorcardcode'] ){?>
                        <tr>
                            <th class="th01"><img src="images/r.png" height="17" width="18" alt="" />债权人信息</th>
                            <td colspan="3" bgcolor="#DBE5F1" class="td01">
                                <a href="javascript:void(0)" class="color creditoradd"><img src="images/PEN.png" height="30" width="156" alt=""  /></a>
                            </td>
                        </tr>
                    <?php }else {
                        echo '';
                    }
                    ?>
                    <div class="xinxi">
                        <tr>
                            <th>姓名</th>
                            <td class="num"><?php echo isset($value['creditorname'])?$value['creditorname']:'暂无'?></td>
                            <th>联系方式</th>
                            <td><?php echo isset($value['creditormobile'])?$value['creditormobile']:'暂无'?></td>
                        </tr>
                        <tr>
                            <th>联系地址</th>
                            <td class="num"><?php echo isset($value['creditoraddress'])?$value['creditoraddress']:'暂无'?></td>
                            <th>证件号</th>
                            <td><?php echo isset($value['creditorcardcode'])?$value['creditorcardcode']:'暂无'?></td>
                        </tr>
                    </div>
                <?php endforeach ?>
                <?php foreach($borrowinginfo as $v):?>
                    <?php if($v['borrowingname'] ||
                        $v['borrowingmobile'] ||
                        $v['borrowingaddress'] ||
                        $v['borrowingcardcode']
                    ){ ?>
                        <tr>
                            <th class="th01"><img src="images/r.png" height="17" width="18" alt="" />债务人信息</th>
                            <td colspan="3" bgcolor="#DBE5F1" class="td01">
                                <a href="javascript:void(0)" class="color borrowingadd"><img src="images/PEN.png" height="30" width="156" alt=""  /></a>
                            </td>
                        </tr>
                    <?php }else {
                        echo '';
                    }?>
                    <div class="xinxi">
                        <tr>
                            <th>姓名</th>
                            <td class="num"><?php echo isset($v['borrowingname'])?$v['borrowingname']:'暂无'?></td>
                            <th>联系方式</th>
                            <td><?php echo isset($v['borrowingmobile'])?$v['borrowingmobile']:'暂无'?></td>
                        </tr>
                        <tr>
                            <th>联系地址</th>
                            <td class="num"><?php echo isset($v['borrowingaddress'])?$v['borrowingaddress']:'暂无'?></td>
                            <th>证件号</th>
                            <td><?php echo isset($v['borrowingcardcode'])?$v['borrowingcardcode']:'暂无'?></td>
                        </tr>
                    </div>
                <?php endforeach ?>
            <?php }else{?>
                <?php foreach($creditorinfo as $value):?>
                    <?php
                    if($value['creditorname'] ||
                        $value['creditormobile'] ||
                        $value['creditoraddress'] ||
                        $value['creditorcardcode'] ){
                        ?>
                        <tr>
                            <th class="th01"><img src="images/r.png" height="17" width="18" alt="" />债权人信息</th>
                            <td colspan="3" bgcolor="#DBE5F1" class="td01">
                                <a href="javascript:void(0)" class="color creditoradd"><img src="images/PEN.png" height="30" width="156" alt=""  /></a>
                            </td>
                        </tr>
                    <?php }else{
                        echo '';
                    }
                    ?>
                    <div class="xinxi">
                        <tr>
                            <th>姓名</th>
                            <td class="num"><?php echo isset($value['creditorname'])?\frontend\services\Func::HideStrRepalceByChar($value['creditorname'],'*',1,0):'暂无'?></td>
                            <th>联系方式</th>
                            <td><?php echo isset($value['creditormobile'])?\frontend\services\Func::HideStrRepalceByChar($value['creditormobile'],'*',4,4):'暂无'?></td>
                        </tr>
                        <tr>
                            <th>联系地址</th>
                            <td class="num"><?php echo isset($value['creditoraddress'])?\frontend\services\Func::HideStrRepalceByChar($value['creditoraddress'],'*',4,10):'暂无'?></td>
                            <th>证件号</th>
                            <td><?php echo isset($value['creditorcardcode'])?\frontend\services\Func::HideStrRepalceByChar($value['creditorcardcode'],'*',4,2):'暂无'?></td>
                        </tr>
                    </div>
                <?php endforeach ?>
                <?php foreach($borrowinginfo as $v):?>
                    <?php if($v['borrowingname'] ||
                        $v['borrowingmobile'] ||
                        $v['borrowingaddress'] ||
                        $v['borrowingcardcode']
                    ){?>
                        <tr>
                            <th class="th01"><img src="images/r.png" height="17" width="18" alt="" />债务人信息</th>
                            <td colspan="3" bgcolor="#DBE5F1" class="td01">
                                <a href="javascript:void(0)" class="color borrowingadd"><img src="images/PEN.png" height="30" width="156" alt=""  /></a>
                            </td>
                        </tr>
                    <?php }else {
                        echo '';
                    }?>
                    <div class="xinxi">
                        <tr>
                            <th>姓名</th>
                            <td class="num"><?php echo isset($v['borrowingname'])?\frontend\services\Func::HideStrRepalceByChar($v['borrowingname'],'*',1,0):'暂无'?></td>
                            <th>联系方式</th>
                            <td><?php echo isset($v['borrowingmobile'])?\frontend\services\Func::HideStrRepalceByChar($v['borrowingmobile'],'*',4,4):'暂无'?></td>
                        </tr>
                        <tr>
                            <th>联系地址</th>
                            <td class="num"><?php echo isset($v['borrowingaddress'])?\frontend\services\Func::HideStrRepalceByChar($v['borrowingaddress'],'*',4,10):'暂无'?></td>
                            <th>证件号</th>
                            <td><?php echo isset($v['borrowingcardcode'])?\frontend\services\Func::HideStrRepalceByChar($v['borrowingcardcode'],'*',4,2):'暂无'?></td>
                        </tr>
                    </div>
                <?php endforeach ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <script>
        $(function(){
            $('.tr').hide();
            change('',0);
        });
        function change(ob,num){
            $('.tr').hide();
            $('.tr').eq(num).show();
            $(ob).addClass('current').siblings('span').removeClass('current');
        }

        $('.uploadsChe').click(function(){
            var id = "<?php echo yii::$app->request->get('id')?>";
            var pid = $(this).attr('id');
            $.msgbox({
                closeImg: '/images/close.png',
                height:500,
                width:600,
                content:"<?php echo yii\helpers\Url::to(["/publish/uploadscheck"])?>?id="+id+'&pid='+pid,
                type:'ajax',
            });
        })
    </script>
    <?php
}else{
    die;
}
?>