<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
?>
<?php if($product['progress_status'] == 1){$clock = 'clock';}else{$clock='';}?>
<?=wxHeaderWidget::widget(['title'=>'产品信息','gohtml'=>'<div class="ck-header cp-header"><span class='.$clock.'></span></div>'])?>
<section>
    <div class="basic">
        <ul>
            <li>
                <span class="current">基本信息</span>
            </li>
            <li>
                <span>补充信息</span>
            </li>
        </ul>
    </div>
    <?php if($product['category'] == 1){ ?>
        <div class="basic_01">
            <div class="basic_main current" style="padding-left:15px;background-color: #fff;">
                <ul>
                    <li>
                        <span>金额</span>
                        <span><?php echo isset($product['money'])?$product['money'].'万':''?></span>
                    </li>
                    <li>
                        <span>返点</span>
                        <span><?php echo isset($product['rebate'])?$product['rebate'].'%':''?></span>
                    </li>
                    <li>
                        <span>利息</span>
                        <span><?php echo isset($product['rate'])?$product['rate'].'%':''?><?php echo isset($product['rate_cat'])?\common\models\FinanceProduct::$ratedatecategory[$product['rate_cat']]:'';?></span>
                    </li>
                    <li>
                        <span>抵押物地址</span>
                        <span>
                        <?php echo \frontend\services\Func::getCityNameById($product['city_id']);?>
                        <?php echo \frontend\services\Func::getAreaNameById($product['district_id']);?>
                        <?php echo \frontend\services\Func::Substr($product['seatmortgage'],'4',"*");?>
                        </span>
                    </li>
                </ul>
            </div>
            <div class="basic_main" style="padding-left:15px;background-color: #fff;">
                <ul>
                    <li>
                        <span>状态</span>
                        <span  style="color:#ddd;">
                            <?php echo isset($product['status'])?\common\models\FinanceProduct::$status[$product['status']]:'暂无'?>
                            <?php if($product['status'] == 2){echo isset($product['rentmoney'])?$product['rentmoney'].'元':'';}else{echo '';}?>
                        </span>
                    </li>
                    <li>
                        <span>抵押物类型</span>
                        <span  style="color:#ddd;">
                            <?php echo isset($product['mortgagecategory'])?\common\models\FinanceProduct::$mortgagecategory[$product['mortgagecategory']]:'暂无'?>
                        </span>
                    </li>
                    <li>
                        <span>抵押物面积</span>
                        <span  style="color:#ddd;"><?php echo isset($product['mortgagearea'])?$product['mortgagearea'].'(㎡)':'暂无'?></span>
                    </li>
                    <li>
                        <span>借款人年龄</span>
                        <span  style="color:#ddd;"><?php echo isset($product['loanyear'])?$product['loanyear'].'岁':'暂无'?></span>
                    </li>
                    <li>
                        <span>权利人年龄</span>
                        <span  style="color:#ddd;"><?php echo isset($product['obligeeyear'])?\common\models\FinanceProduct::$obligeeyear[$product['obligeeyear']]:'暂无'?></span>
                    </li>
                </ul>
            </div>
        </div>
    <?php }else{?>
    <div class="basic_01">
        <div class="basic_main current" style="padding-left:15px;background-color: #fff;">
            <ul>
                <li>
                    <span>借款本金</span>
                    <span><?php echo isset($product['money'])?$product['money'].'万':''?></span>
                </li>
                <li>
                    <span>借款期限</span>
                    <span  style="color:#BDBCBC;"><?php echo isset($product['term'])?$product['term'].'月':'暂无'?></span>
                </li>
                <li>
                    <span>还款方式</span>
                    <span  style="color:#BDBCBC;"><?php echo isset($product['repaymethod'])?\common\models\CreditorProduct::$repaymethod[$product['repaymethod']]:'暂无'?></span>
                </li>
                <li>
                    <span>债权类型</span>
                    <span><?php echo isset($product['loan_type'])&&$product['loan_type']==0?'暂无':common\models\CreditorProduct::$loan_types[$product['loan_type']]?></span>
                </li>
                <?php if($product['loan_type'] == 0 || $product['loan_type'] == 1){ ?>
                    <?php if(is_numeric($product['guaranteemethod'])){ ?>
                    <?php }else{?>
                        <?php $value = unserialize($product['guaranteemethod']);$guar_other = $value['other'];unset($value['other']); ?>
                    <?php } ?>
                    <li>
                        <span>有无抵押物</span>
                        <span>
                            <?php if(is_numeric($product['guaranteemethod'])){ ?>
                                <?php echo $product['guaranteemethod']==0?'无':'有' ?>
                            <?php }else{ ?>
                                <?php foreach($value as $ke => $va):?>
                                    <?php echo \common\models\CreditorProduct::$guaranteemethod[$va];?>
                                <?php endforeach;?>
                                <?php if($guar_other){echo $guar_other;}?>
                            <?php } ?>
                        </span>
                    </li>
                    <li>
                    <span>抵押物地址</span>
                    <span>
                        <?php echo \frontend\services\Func::getProvinceNameById($product['province_id']);?>
                        <?php echo \frontend\services\Func::getCityNameById($product['city_id']);?>
                        <?php echo \frontend\services\Func::getAreaNameById($product['district_id']);?>
                        <?php if($product['uid'] == \Yii::$app->user->getId() || $product['progress_status'] == 2 ){
							//echo \frontend\services\Func::getSubstrs($product['seatmortgage']);
                            echo isset($product['seatmortgage'])?$product['seatmortgage']:'';
                        }else{
							echo isset($product['seatmortgage'])?\frontend\services\Func::getSubstrs($product['seatmortgage']):'';
                            //echo isset($product['seatmortgage'])?\frontend\services\Func::HideStrRepalceByChar($product['seatmortgage'],'*',4,0):'';
                        }?>
                    </span>
                </li>
                <?php }else if($product['loan_type'] == 2){ ?>
                    <li>
                        <span>应收账款</span>
                        <span style="color:#BDBCBC;"><?php echo isset($product['accountr'])?$product['accountr']:'暂无';?></span>
                    </li>
                <?php }else if($product['loan_type'] == 3){ ?>
                    <li>
                        <span>机动车抵押</span>
                        <span><?php echo isset($product['carbrand'])?\frontend\services\Func::getCarBrand($product['carbrand']):'暂无';?> <?php echo isset($product['audi'])?\frontend\services\Func::getCarAudi($product['audi']):'暂无';?></span>
                    </li>
                    <li>
                        <span>车牌类型</span>
                        <span><?php echo isset($product['licenseplate'])?\common\models\creditorProduct::$licenseplate[$product['licenseplate']]:'暂无';?></span>
                    </li>
                <?php } ?>
                <li>
                    <span>债务人主体</span>
                    <span style="color:#BDBCBC;"><?php echo isset($product['obligor'])?\common\models\creditorProduct::$obligor[$product['obligor']]:'暂无';?></span>
                </li>
                <li>
                    <span>逾期日期</span>
                    <span style="color:#BDBCBC;"><?php echo isset($product['start'])&&$product['start']!=0?$product['start']:'暂无';?></span>
                </li>
                <?php if($product['category'] == 2){echo '';}else{?>
                <li>
                    <span>委托事项</span>
                    <span><?php echo isset($product['commitment'])?\common\models\creditorProduct::$commitment[$product['commitment']]:'暂无'?></span>
                </li>
                <?php } ?>
                <li>
                    <span>委托代理期限</span>
                    <span><?php echo isset($product['commissionperiod'])&&$product['commissionperiod']==''?'暂无':$product['commissionperiod'].'月';?></span>
                </li>
                <li>
                    <?php if(isset($product['category'])&&$product['category'] == 2){ ?>	
                        <span><?php echo isset($product['agencycommissiontype'])&&$product['agencycommissiontype']==1?'服务佣金':'固定费用'?></span>
                       <span>
                           <?php if($product['agencycommissiontype']==1){ ?>
                               <?php echo isset($product['agencycommission'])?$product['agencycommission'].'%':'暂无' ?>
                           <?php }else{ ?>
                               <?php echo isset($product["agencycommission"])?$product["agencycommission"].'万':'暂无';?>
                           <?php } ?>
                       </span>
                    <?php }else{ ?>
                        <span><?php echo isset($product['agencycommission'])?\common\models\creditorProduct::$agencycommissiontype[$product['agencycommissiontype']]:'';?></span>
                        <span>
                            <?php if($product['agencycommissiontype']==1){ ?>
                                <?php echo isset($product["agencycommission"])?$product["agencycommission"].'万':'暂无';?>
                            <?php }else{ ?>
                                <?php echo isset($product["agencycommission"])?$product["agencycommission"].'%':'暂无';?>
                            <?php } ?>
                    </span>
                    <?php } ?>
                </li>
            </ul>
        </div>
        <div class="basic_main" style="padding-left:15px;background-color: #fff;">
            <ul>
                <li>
                    <span>已付本金</span>
                    <span style="color:#BDBCBC;"><?php echo isset($product['paidmoney'])?$product['paidmoney'].'元':'暂无'?></span>
                </li>
                <li>
                    <span>已付利息</span>
                    <span style="color:#BDBCBC;"><?php echo isset($product['interestpaid'])?$product['interestpaid'].'元':'暂无'?></span>
                </li>
                <li>
                    <span style="color:#BDBCBC;">合同履行地</span>
                    <span style="color:#BDBCBC;"><?php echo isset($product['performancecontract'])&&$product['performancecontract'] != ''?$product['performancecontract']:'暂无'?></span>
                </li>

                <li>
                    <span>借款利率</span>
                    <span style="color:#BDBCBC;"><?php echo isset($product['rate'])?($product['rate'].(isset($product['rate_cat'])&&$product['rate_cat'] == 1?'%/天':'%/月')):'暂无'?></span>
                </li>
                <li>
                    <span>债权文件</span>
                    <span class="creditorfile">查看</span>
                </li>
                <li>
                    <span>债权人信息</span>
                    <span class="creditorprofile">查看</span>
                </li>
                <li>
                    <span>债务人信息</span>
                    <span class="uncreditorprofile">查看</span>
                </li>

            </ul>
        </div>
    </div>
    <?php } ?>
</section>
<script>
    $(function(){
        $('.basic li span').click(function(){
            var index = $(this).parent().index();
            $(this).addClass('current').parent().siblings().children().removeClass('current').parents().parent().next().children().eq(index).show().siblings().hide();
        })

        $('.creditorfile').click(function(){
            window.location.href = "<?php echo \yii\helpers\Url::toRoute(['/list/pro','id'=>$product['id'],'category'=>$product['category'],'type'=>1])?>";
        });

        $('.creditorprofile').click(function(){
            window.location.href = "<?php echo \yii\helpers\Url::toRoute(['/list/pro','id'=>$product['id'],'category'=>$product['category'],'type'=>2])?>";
        });

        $('.uncreditorprofile').click(function(){
            window.location.href = "<?php echo \yii\helpers\Url::toRoute(['/list/pro','id'=>$product['id'],'category'=>$product['category'],'type'=>3])?>";
        });
        $('.clock').click(function(){
            var id = "<?php echo $product['id']?>";
            var category = "<?php echo $product['category']?>";
            $.ajax({
                url:"<?php echo yii\helpers\Url::toRoute('/list/remind/')?>",
                type:'post',
                data:{id:id,category:category},
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
                        alert(json.msg);
                        location.reload();
                    }else{
                        alert(json.msg);
                    }

                }
            })

        })
    })

</script>