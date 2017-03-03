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
    <span class="bord_l">融资详情</span>
    <div class="rongzi">
        <div class="title_rz clearfix" style="background-color:#fff; margin-top:20px;">
            <span class="base fl current" onclick='change(this,0);' style="padding-left:0;">　基本信息</span>
            <span class="supplement fr" onclick='change(this,1);' style="padding-left:0;">　　补充信息</span>
        </div>
        <div class="rz_detail">
            <div class="rz1 x1">
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <th>
                            金额<b>(万元)</b>
                        </th>
                        <td width="200px;">
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
                            利息<b>(%/<?php echo isset($details['rate_cat'])&&$details['rate_cat'] == 1?'天':'月';?>)</b>
                        </th>
                        <td>
                            <?php echo isset($details['rate'])?$details['rate']:''?>
                        </td>
                        <th>
                            借款期限(<?php echo $details['rate_cat'] ==1 ? '天':'月';?>)
                        </th>
                        <td>
                            <div class="qixian w100 display">
                                <?php echo isset($details['term'])?$details['term']:''?>
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
                        <!--<th>
                            预期资金到账日
                        </th>
                        <td>
                            <?php /*echo isset($details['fundstime'])?date('Y年m月d日',$details['fundstime']):''*/?>
                        </td>-->
                        <th>
                            抵押物地址
                        </th>
                        <td colspan="3">
                            <?php echo \frontend\services\Func::getProvinceNameById($details['province_id']);?>
                            <?php echo \frontend\services\Func::getCityNameById($details['city_id']);?>
                            <?php echo \frontend\services\Func::getAreaNameById($details['district_id']);?>
                            <?php if($details['uid'] == \Yii::$app->user->getId() || $details['progress_status'] == 2 ){
                                echo isset($details['seatmortgage'])?$details['seatmortgage']:'';
                            }else{
								echo isset($details['seatmortgage'])?\frontend\services\Func::getSubstrs($details['seatmortgage']):'';
                                //echo isset($details['seatmortgage'])?\frontend\services\Func::HideStrRepalceByChar($details['seatmortgage'],'*',4,0):'';
                            }?>
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
<?php } else if(in_array($category,[2,3])){?>
    <?php if(is_numeric($details['guaranteemethod'])){ ?>
        <?php }else{?>
    <?php $value = unserialize($details['guaranteemethod']);
    $guar_other = $value['other'];
    unset($value['other']);
    ?>
        <?php } ?>
    <?php $judicial = unserialize($details['judicialstatusA'])?>
    <?php $credi =unserialize($details['creditorfile'])?>
    <?php $creditorinfo = unserialize($details['creditorinfo'])?>
    <?php $borrowinginfo = unserialize($details['borrowinginfo'])?>
    <span class="bord_l"><?php echo \common\models\CreditorProduct::$categorys[$details['category']]?>详情</span>
    <!-- <i></i> -->
    <div class="content_right">
       <div class="title_rz clearfix" style="background-color:#fff;">
           <span class="base fl current" onclick='change(this,0);' style="padding-left:0;">　基本信息</span>
           <span class="supplement fr" onclick='change(this,1);' style="padding-left:0;">　　补充信息</span>
       </div>
        <div class="rz1 collection tr mytr">
            <table width="800px;">
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
                        还款方式
                    </th>
                    <td>
                        <div class="display">
                            <?php echo isset($details['repaymethod'])?\common\models\CreditorProduct::$repaymethod[$details['repaymethod']]:'暂无'?>
                        </div>
                    </td>
                    <th>
                        债权类型
                    </th>
                    <td>
                        <?php echo isset($details['loan_type'])&&$details['loan_type']==0?'暂无':common\models\CreditorProduct::$loan_types[$details['loan_type']]?>
                    </td>
                </tr>
                <?php if(isset($details->category)&&$details->category == 2){ ?>
                    <tr>
                        <th style="height:35px;">代理费用</th>
                        <td class="switch" colspan="3">
                            <?php echo isset($details['agencycommission'])&&$details['agencycommissiontype'] == 1?'服务佣金':'固定费用';?>
                            <?php if($details['agencycommissiontype']==1){ ?>
                                <span><?php echo isset($details["agencycommission"])?$details["agencycommission"].'%':'暂无';?></span>
                            <?php }else{ ?>
                                <span><?php echo isset($details["agencycommission"])?$details["agencycommission"].'万':'暂无';?></span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php }else{ ?>
                <tr>
                    <th style="height:35px;">代理费用</th>
                    <td class="switch" colspan="3">
                        <?php echo isset($details['agencycommission'])&&$details['agencycommissiontype'] == 1?'固定费用':'风险费率';?>
                        <?php if($details['agencycommissiontype']==1){ ?>
                            <span><?php echo isset($details["agencycommission"])?$details["agencycommission"].'万':'暂无';?></span>
                        <?php }else{ ?>
                            <span><?php echo isset($details["agencycommission"])?$details["agencycommission"].'%':'暂无';?></span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
                <?php if($details['loan_type']==1){?>
                <tr>
                    <th>
                        抵押<b>(可多选)</b>
                    </th>
                    <td colspan="3">
                        <div class="display">
                            <?php if(is_numeric($details['guaranteemethod'])){ ?>
                                  <?php echo $details['guaranteemethod']==0?'无':'有' ?>
                            <?php }else{ ?>
                                <?php foreach($value as $ke => $va):?>
                                    <?php echo \common\models\CreditorProduct::$guaranteemethod[$va];?>
                                <?php endforeach;?>
                                <?php if($guar_other){echo $guar_other;}?>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>
                        担保物(抵押物)所在地
                    </th>
                    <td colspan="3">
                        <?php echo \frontend\services\Func::getProvinceNameById($details['province_id']);?>
                        <?php echo \frontend\services\Func::getCityNameById($details['city_id']);?>
                        <?php echo \frontend\services\Func::getAreaNameById($details['district_id']);?>
                         <?php if($details['uid'] == \Yii::$app->user->getId() || $details['progress_status'] == 2 ){
                            echo isset($details['seatmortgage'])?$details['seatmortgage']:'';
                        }else{
							echo isset($details['seatmortgage'])?\frontend\services\Func::getSubstrs($details['seatmortgage']):'';
                            //echo isset($details['seatmortgage'])?\frontend\services\Func::HideStrRepalceByChar($details['seatmortgage'],'*',4,0):'';
                        }?>
                    </td>
                </tr>
                <?php }else if($details['loan_type'] == 2){ ?>
                    <tr>
                        <th>
                            应收账款
                        </th>
                        <td colspan="3">
                            <div class="display">
                                <?php echo isset($details['accountr'])?$details['accountr']:'暂无';?>
                            </div>
                        </td>
                    </tr>

                <?php } else if($details['loan_type']==3){ ?>
                    <tr>
                        <th>
                            机动车抵押
                        </th>
                        <td>
                            <div class="display">
                                <?php echo isset($details['carbrand'])?\frontend\services\Func::getCarBrand($details['carbrand']):'暂无';?> <?php echo isset($details['audi'])?\frontend\services\Func::getCarAudi($details['audi']):'暂无';?>
                            </div>
                        </td>
                        <th>
                            车牌类型
                        </th>
                        <td>
                            <div class="display">
                                <?php echo isset($details['licenseplate'])?\common\models\creditorProduct::$licenseplate[$details['licenseplate']]:'暂无';?>
                            </div>
                        </td>
                    </tr>
                   <!-- <tr>

                    </tr>-->


                <?php } ?>
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
                        委托代理期限<b>(月)</b>
                    </th>
                    <td colspan="3">
                        <?php echo isset($details['commissionperiod'])?$details['commissionperiod']:'暂无';?>
                    </td>
                </tr>


    </table>
</div>
<div class="table tr font16" style="margin-top:20px;">
    <table style="width:800px;"cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <th style="height:35px;width:180px;">已付本金(元)</th>
            <td width="200px;"><?php echo isset($details['paidmoney'])?$details['paidmoney']:'暂无'?></td>
            <th width="180px;">已付利息(元)</th>
            <td><?php echo isset($details['interestpaid'])?$details['interestpaid']:'暂无'?></td>
        </tr>
        <tr>
            <th style="height:35px;">合同履行地</th>
            <td><?php echo isset($details['performancecontract'])&&$details['performancecontract'] == ''?'暂无':$details['performancecontract']?></td>
            <th>
                借款利率<b>(%/<?php echo isset($details['rate_cat'])&&$details['rate_cat'] == 1?'天':'月';?>)</b>
            </th>
            <td class="w150">
                <?php echo isset($details['rate'])?$details['rate']:'暂无'?>
            </td>
        </tr>
            <?php if(isset($category)&&$category == 2 ){?>
                <?php echo '';?>
            <?php }else{?>
                <tr>
                    <th>付款方式</th>
                    <td colspan="3">
                        <?php echo isset($details['paymethod'])&&$details['paymethod']==''?'暂无':\common\models\creditorProduct::$paymethod[$details['paymethod']];?>
                    </td>
                </tr>
            <?php }?>
            <tr>
                <th style="height:35px;">债权文件上传</th>
                <td colspan="3" style="position:relative;height:35px;">
                    <span class="tp" style="padding-left:0;"></span>
                    <div style="font-size:14px;" class="mytext">
                        公证书<span class="color uploadsChe" id="1"  style="font-size:12px; padding-left:0;">查看</span>
                        借款合同<span class="color uploadsChe" id="2" style="font-size:12px; padding-left:0;">查看</span>
                        他项权证<span class="color uploadsChe" id="3" style="font-size:12px; padding-left:0;">查看</span>
                        付款凭证<span class="color uploadsChe" id="4" style="font-size:12px; padding-left:0;">查看</span>
                        收据<span class="color uploadsChe" id="5" style="font-size:12px; padding-left:0;">查看</span>
                        还款凭证<span class="color uploadsChe" id="6" style="font-size:12px; padding-left:0;">查看</span>
                        <span style="font-size:12px; padding-left:0;">(请您根据类别来上传)</span>
                    </div>
                </td>
            </tr>
        <?php if($details['uid'] == \Yii::$app->user->getId() || $details['progress_status'] == 2 ){?>
            <?php if(empty($creditorinfo)){echo "";}else{ ?>
			<div class="xinxi">
               <tr  style="height:35px;">
                   <td colspan="4" bgcolor="#DBE5F1" class="td01">债权人信息</td> 
                </tr>
				<?php foreach($creditorinfo as $value){ ?>
                    <tr style="height:35px;">
                        <th>姓名</th>
                        <td class="num"><?php echo isset($value['creditorname'])?$value['creditorname']:'暂无'?></td>
                        <th>联系方式</th>
                        <td><?php echo isset($value['creditormobile'])?$value['creditormobile']:'暂无'?></td>
                    </tr>
                    <tr style="height:35px;">
                        <th>联系地址</th>
                        <td class="num"><?php echo isset($value['creditoraddress'])?$value['creditoraddress']:'暂无'?></td>
                        <th>证件号</th>
                        <td><?php echo isset($value['creditorcardcode'])?$value['creditorcardcode']:'暂无'?></td>
                    </tr>
            <?php } ?> 
			</div>
			<?php } ?>
	    
        <?php if(empty($borrowinginfo)){echo "";}else{ ?>
		<div class="xinxi">
                    <tr  style="height:35px;">
                       
                        <td colspan="4" bgcolor="#DBE5F1" class="td01">债务人信息</td>
                    </tr>
                <?php foreach($borrowinginfo as $v){ ?>
                    <tr  style="height:35px;">
                        <th>姓名</th>
                        <td class="num"><?php echo isset($v['borrowingname'])?$v['borrowingname']:'暂无'?></td>
                        <th>联系方式</th>
                        <td><?php echo isset($v['borrowingmobile'])?$v['borrowingmobile']:'暂无'?></td>
                    </tr>
                    <tr  style="height:35px;">
                        <th>联系地址</th>
                        <td class="num"><?php echo isset($v['borrowingaddress'])?$v['borrowingaddress']:'暂无'?></td>
                        <th>证件号</th>
                        <td><?php echo isset($v['borrowingcardcode'])?$v['borrowingcardcode']:'暂无'?></td>
                    </tr>
            <?php } ?>
			 </div>
			<?php } ?>
            <?php }else{ ?>
            <?php if(empty($creditorinfo)){echo "";}else{ ?>
			      <tr>
                    <td colspan="4" bgcolor="#DBE5F1" class="td01">债权人信息</td>
                  </tr>
                <?php foreach($creditorinfo as $value){ ?>
                        
                    <div class="xinxi">
                        <tr>
                            <th>姓名</th>
                            <td class="num"><?php echo isset($value['creditorname'])?\frontend\services\Func::HideStrRepalceByChar($value['creditorname'],'*',1,0):'暂无'?></td>
                            <th>联系方式</th>
                            <td><?php echo isset($value['creditormobile'])?\frontend\services\Func::HideStrRepalceByChar($value['creditormobile'],'*',4,4):'暂无'?></td>
                        </tr>
                        <tr>
                            <th>联系地址</th>
                            <td class="num"><?php echo isset($value['creditoraddress'])?\frontend\services\Func::HideStrRepalceByChar($value['creditoraddress'],'*',6,0):'暂无'?></td>
                            <th>证件号</th>
                            <td><?php echo isset($value['creditorcardcode'])?\frontend\services\Func::HideStrRepalceByChar($value['creditorcardcode'],'*',4,2):'暂无'?></td>
                        </tr>
                    </div>
                <?php } ?>
			<?php } ?>
            <?php if(empty($creditorinfo)){echo "";}else{ ?>
			            <tr>
						   <td colspan="4" bgcolor="#DBE5F1" class="td01">债务人信息</td>
                        </tr>
                    <?php foreach($borrowinginfo as $v){ ?>
                    <div class="xinxi">
                        <tr>
                            <th>姓名</th>
                            <td class="num"><?php echo isset($v['borrowingname'])?\frontend\services\Func::HideStrRepalceByChar($v['borrowingname'],'*',1,0):'暂无'?></td>
                            <th>联系方式</th>
                            <td><?php echo isset($v['borrowingmobile'])?\frontend\services\Func::HideStrRepalceByChar($v['borrowingmobile'],'*',4,4):'暂无'?></td>
                        </tr>
                        <tr>
                            <th>联系地址</th>
                            <td class="num"><?php echo isset($v['borrowingaddress'])?\frontend\services\Func::HideStrRepalceByChar($v['borrowingaddress'],'*',6,0):'暂无'?></td>
                            <th>证件号</th>
                            <td><?php echo isset($v['borrowingcardcode'])?\frontend\services\Func::HideStrRepalceByChar($v['borrowingcardcode'],'*',4,2):'暂无'?></td>
                        </tr>
                    </div>
                <?php } ?>
			<?php } ?>
            <?php } ?>
            </tbody>
        </table>
        </div>
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
                content:"<?php echo yii\helpers\Url::toRoute(["/publish/uploadscheck"])?>?id="+id+'&pid='+pid,
                type:'ajax',
            });
        })
    </script>
    <?php
}else{
    die;
}
?>