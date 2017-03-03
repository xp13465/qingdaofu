<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '接单详情';
?>
<div class="row">
    <div class="col-lg-10">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <tbody>
<?php 
	if($desc['category'] == 1){ ?>
                    <tr>
                        <td>ID</td>
                        <td><?php echo $desc['id']?$desc['id']:''?></td>
                    </tr>
                    <tr>
                        <td>类型</td>
                        <td>融资</td>
                    </tr>
                    <tr>
                        <td> 金额<b>(万元)</b></td>
                        <td><?php echo isset($desc['money'])?$desc['money']:''?></td>
                    </tr>
                    <tr>
                        <td>返点<b>(%)</b></td>
                        <td><?php echo isset($desc['rebate'])?$desc['rebate']:''?></td>
                    </tr>
                    <tr>
                        <td>利息<b>(%/<?php echo isset($desc['rate_cat'])&&$desc['rate_cat'] == 1?'天':'月';?>)</b></td>
                        <td><?php echo isset($desc['rate'])?$desc['rate']:''?></td>
                    </tr>
                    <tr>
                        <td>借款期限(<?php echo $desc['rate_cat'] ==1 ? '天':'月';?>)</td>
                        <td><?php echo isset($desc['term'])?$desc['term']:''?></td>
                    </tr>
                    <tr>
                        <td>抵押物面积<b>(㎡)</b></td>
                        <td><?php echo isset($desc['mortgagearea'])?$desc['mortgagearea']:''?></td>
                    </tr>
                    <tr>
                        <td>预期资金到账日</td>
                        <td><?php echo isset($desc['fundstime'])?date('Y年m月d日',$desc['fundstime']):''?></td>
                    </tr><tr>
                        <td>抵押物地址</td>
                        <td><?php echo \frontend\services\Func::getProvinceNameById($desc['province_id']);?>
                            <?php echo \frontend\services\Func::getCityNameById($desc['city_id']);?>
                            <?php echo \frontend\services\Func::getAreaNameById($desc['district_id']);?>
                            <?php echo isset($desc['seatmortgage'])?$desc['seatmortgage']:'';?>
                        </td>
                    </tr>
                    <tr>
                        <td>抵押物类型</td>
                        <td>
                            <?php echo isset($desc['mortgagecategory'])?\common\models\FinanceProduct::$mortgagecategory[$desc['mortgagecategory']]:'暂无' ?>
                        </td>
                    </tr>
                    <tr>
                        <td>状态</td>
                        <td><?php echo isset($desc['status'])?\common\models\FinanceProduct::$status[$desc['status']]:'暂无'?>
                            <?php if($desc['status'] == 2){echo isset($desc['rentmoney'])?$desc['rentmoney'].'元':'';}else{echo '';}?>
                        </td>
                    </tr>
                    <tr>
                        <td> 抵押物状况</td>
                        <td>
                            <?php echo isset($desc['mortgagestatus'])?\common\models\FinanceProduct::$mortgagestatus[$desc['mortgagestatus']]:'暂无'?>
                        </td>
                    </tr>
                    <tr>
                        <td> 借款人年龄</td>
                        <td><?php echo isset($desc['loanyear'])?$desc['loanyear'].'岁':'暂无'?></td>
                    </tr>
                    <tr>
                        <td> 权利人年龄</td>
                        <td><?php echo isset($desc['obligeeyear'])?\common\models\FinanceProduct::$obligeeyear[$desc['obligeeyear']]:'暂无'?></td>
                    </tr>
<?php 
	} else{  
		$value = @unserialize($desc['guaranteemethod'])?unserialize($desc['guaranteemethod']):[];
		$guar_other = @$value['other'];
		unset($value['other']);  
		$judicial = unserialize($desc['judicialstatusA'])?unserialize($desc['judicialstatusA']):[];
		$credi = unserialize($desc['creditorfile'])?unserialize($desc['creditorfile']):[];
		$creditorinfo = unserialize($desc['creditorinfo'])?unserialize($desc['creditorinfo']):[];
		$borrowinginfo = unserialize($desc['borrowinginfo'])?unserialize($desc['borrowinginfo']):[];
?>
                    <tr>
                        <td>ID</td>
                        <td><?php echo $desc['id']?$desc['id']:''?></td>
                    </tr>
                    <tr>
                        <td>类型</td>
                        <td><?php if($desc['category']==2){echo '清收';}else{echo '诉讼';}?></td>
                    </tr>
                    <tr>
                        <td> 借款本金<b>(万元)</b></td>
                        <td><?php echo isset($desc['money'])?$desc['money']:'暂无'?></td>
                    </tr>
                    <tr>
                        <td>借款期限<b>(月)</b></td>
                        <td><?php echo isset($desc['term'])?$desc['term']:'暂无'?></td>
                    </tr>
                    <tr>
                        <td> 借款利率<b>(%/<?php echo isset($desc['rate_cat'])&&$desc['rate_cat'] == 1?'天':'月';?>)</b></td>
                        <td><?php echo isset($desc['rate'])?$desc['rate']:''?></td>
                    </tr>
                    <tr>
                        <td> 还款方式</td>
                        <td><?php echo isset($desc['repaymethod'])?\common\models\CreditorProduct::$repaymethod[$desc['repaymethod']]:'暂无'?></td>
                    </tr>
                    <tr>
                        <td>  抵押<b>(可多选)</b></td>
                        <td><?php foreach($value as $ke => $va):?>
                                <?php echo \common\models\CreditorProduct::$guaranteemethod[$va];?>
                            <?php endforeach;?>
                            <?php if($guar_other){echo $guar_other;}?>
                        </td>
                    </tr>
                    <tr>
                        <td> 担保物(抵押物)所在地</td>
                        <td> <?php echo \frontend\services\Func::getProvinceNameById($desc['province_id']);?>
                            <?php echo \frontend\services\Func::getCityNameById($desc['city_id']);?>
                            <?php echo \frontend\services\Func::getAreaNameById($desc['district_id']);?>
                            <?php echo isset($desc['seatmortgage'])?\frontend\services\Func::getSubstrs($desc['seatmortgage']):'';?>
                        </td>
                    </tr>
                    <tr>
                        <td>  司法现状</td>
                        <td><?php foreach($judicial as $ke => $va):?>
                                <?php echo isset($va)?\common\models\creditorProduct:: $judicialstatusA[$va]:'暂无';?>
                            <?php endforeach;?>
                        </td>
                    </tr>
                    <tr>
                        <td> 债务人是否能正常联系:</td>
                        <td>
                            <?php echo isset($desc['judicialstatusB'])?\common\models\creditorProduct:: $judicialstatusB[$desc['judicialstatusB']]:'暂无';?>
                        </td>
                    </tr>
                    <tr>
                        <td> 债务人主体</td>
                        <td><?php echo isset($desc['obligor'])?\common\models\creditorProduct::$obligor[$desc['obligor']]:'暂无';?></td>
                    </tr>
                    <tr>
                        <td> 委托代理期限<b>(月)</b></td>
                        <td> <?php echo isset($desc['commissionperiod'])?$desc['commissionperiod']:'暂无';?></td>
                    </tr>
                    <tr>
                        <td> 已付本金(元)</td>
                        <td><?php echo isset($desc['paidmoney'])?$desc['paidmoney']:'暂无'?></td>
                    </tr>
                    <tr>
                        <td> 已付利息(元)</td>
                        <td><?php echo isset($desc['interestpaid'])?$desc['interestpaid']:'暂无'?></td>
                    </tr>
                    <tr>
                        <td> 合同履行地</td>
                        <td><?php echo isset($desc['performancecontract'])&&$desc['performancecontract']!=''?$desc['performancecontract']:'暂无'?></td>
                    </tr>
                    <?php if(isset($category)&&$category == 2 ){?>
                        <?php echo '';?>
                    <?php }else{?>
                    <tr>
                        <td> 代理费用</td>
                        <td> <?php echo isset($desc['agencycommission'])?\common\models\creditorProduct::$agencycommissiontype[$desc['agencycommissiontype']]:'暂无';?>
                            <?php if($desc['agencycommissiontype']==1){?>
                                <span><?php echo $desc['agencycommission'] ?></span><?php echo isset($desc["agencycommission"])?'元':'';?>
                            <?php }else{ ?>
                                <span><?php echo $desc['agencycommission']?></span><?php echo isset($desc["agencycommission"])?'%':'';?>
                            <?php }?>
                        </td>
                    </tr>
                    <tr>
                            <td> 付款方式</td>
                            <td>
                                <?php echo isset($desc['repaymethod'])?\common\models\creditorProduct::$repaymethod[$desc['repaymethod']]:'暂无';?>
                            </td>
                    </tr>
                    <?php }?>
                    <tr>
                        <th>债权文件上传</th>
                        <td>
                                <p>公证书<span class="color uploadsChe" id="1"  style="font-size:12px; padding-left:10px;padding-right:10px;cursor:pointer;">查看</span></p>
                                <p>借款合同<span class="color uploadsChe" id="2" style="font-size:12px; padding-left:10px;padding-right:10px;cursor:pointer;">查看</span></p>
                                <p>他项权证<span class="color uploadsChe" id="3" style="font-size:12px; padding-left:10px;padding-right:10px;cursor:pointer;">查看</span></p>
                                <p>付款凭证<span class="color uploadsChe" id="4" style="font-size:12px; padding-left:10px;padding-right:10px;cursor:pointer;">查看</span></p>
                                <p>收据<span class="color uploadsChe" id="5" style="font-size:12px;  padding-left:10px;padding-right:10px;cursor:pointer;">查看</span></p>
                                <p>还款凭证<span class="color uploadsChe" id="6" style="font-size:12px; padding-left:10px;padding-right:10px;cursor:pointer;">查看</span></p>
                                <p><span style="font-size:12px; padding-left:0;">(请您根据类别来上传)</span></p>
                        </td>
                    </tr>
                        <?php foreach($creditorinfo as $value):?>
                            <?php
                            if($value['creditorname'] ||
                                $value['creditormobile'] ||
                                $value['creditoraddress'] ||
                                $value['creditorcardcode'] ){?>
                                <tr >
                                    <th >债权人信息</th>
                                    <td ></td>
                                </tr>
                            <?php }else {echo '';} ?>
                                <tr>
                                    <th>姓名</th>
                                    <td><?php echo isset($value['creditorname'])&&$value['creditorname']!=''?$value['creditorname']:'暂无';?></td>
                                </tr>
                                <tr>
                                    <th>联系方式</th>
                                    <td><?php echo isset($value['creditormobile'])&&$value['creditormobile']!=''?$value['creditormobile']:'暂无';?></td>
                                </tr>
                                <tr>
                                    <th>联系地址</th>
                                    <td><?php echo isset($value['creditoraddress'])&&$value['creditoraddress']!=''?$value['creditoraddress']:'暂无';?></td>
                                </tr>
                                <tr>
                                    <th>证件号</th>
                                    <td><?php echo isset($value['creditorcardcode'])&&$value['creditorcardcode']!=''?$value['creditorcardcode']:'暂无';?></td>
                                </tr>
                        <?php endforeach ?>
                        <?php foreach($borrowinginfo as $v):?>
                            <?php if($v['borrowingname'] ||
                                $v['borrowingmobile'] ||
                                $v['borrowingaddress'] ||
                                $v['borrowingcardcode']
                            ){ ?>
                                <tr>
                                    <th>债务人信息</th>
                                    <td></td>
                                </tr>
                            <?php }else {echo '';}?>
                                <tr>
                                    <th>姓名</th>
                                    <td><?php echo isset($v['borrowingname'])&&$v['borrowingname']!=''?$v['borrowingname']:'暂无';?></td>
                                </tr>
                                <tr>
                                    <th>联系方式</th>
                                    <td><?php echo isset($v['borrowingmobile'])&&$v['borrowingmobile']!=''?$v['borrowingmobile']:'暂无';?></td>
                                </tr>
                                <tr>
                                    <th>联系地址</th>
                                    <td><?php echo isset($v['borrowingaddress'])&&$v['borrowingaddress']!=''?$v['borrowingaddress']:'暂无';?></td>
                                </tr>
                                <tr>
                                    <th>证件号</th>
                                    <td><?php echo isset($v['borrowingcardcode'])&&$v['borrowingcardcode']!=''?$v['borrowingcardcode']:'暂无';?></td>
                                </tr>
                        <?php endforeach ?>
<?php 	
	} 
		$disposingprocess = \common\models\DisposingProcess::find()->Where(['category'=>$desc['category'],'product_id'=>$desc['id']])->orderBy('create_time desc')->all();
		$delay = \common\models\DelayApply::findOne(['category'=>$desc['category'],'product_id'=>$desc['id'],'is_agree'=>1]);
	if($disposingprocess){ ?>
                <tr>
                    <th>协议编号</th>
                    <td><?php echo isset($desc['code'])&&$desc['code']!=''?$desc['code']:'暂无';?></td>
                </tr>
                <tr>
                    <td>处置进度</td>
                </tr>
			<?php 	foreach($disposingprocess as $dp):?>
                    <tr>
                        <th>日期</th>
                        <td><?php echo date('Y',$dp->create_time).'年'.date('m',$dp->create_time).'月'.date('d',$dp->create_time).'日'.date('H',$dp->create_time).':'.date('i',$dp->create_time)?></td>
                    </tr>
                    <tr>
                        <th>状态</th>
                        <td><?php echo \frontend\services\Func::$Status[$desc['category']][$dp->status];?></td>
                    </tr>
                    <tr>
                        <th>详情</th>
                        <td><?php echo $dp->content;?></td>
                    </tr>
                    <?php $dd = array(0=>'一审',1=>'二审',2=>'再审',3=>'执行');?>
                    <tr>
                        <th>暗号</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th><?php echo isset($dd[$dp->audit])?$dd[$dp->audit]:''?></th>
                        <td><?php echo $dp->case;?></td>
                    </tr>
			<?php	endforeach;?>
<?php 		if($delay){?>
                    <tr>
                        <th>延期申请</th>
                    </tr>
                    <tr>
                        <th>申请时间</th>
                        <td><?php echo date('Y年m月d日 H:i',$delay['create_time']);?></td>
                    </tr>
                    <tr>
                        <th>申请原因</th>
                        <td><?php echo $delay['dalay_reason'];?></td>
                    </tr>
                    <tr>
                        <th>延长天数</th>
                        <td><?php echo $delay['dalay_reason'];?></td>
                    </tr>
<?php 		}	
		}
?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $('.uploadsChe').click(function(){
		
		
		
		
		
 
		
		
        var id = "<?php echo yii::$app->request->get('id')?>";
        var pid = $(this).attr('id');
		
		
		
		
		
		layer.open({
		  type: 2,
		  title: $(this).parent().text(),
		  shadeClose: true,
		  shade: 0.8,
		  // area: ['380px', '90%'],
		  content: "<?php echo yii\helpers\Url::to(["/product/uploadscheck"])?>?id="+id+'&pid='+pid //iframe的url
		}); 
		/*
        $.msgbox({
            closeImg: '/images/close.png',
            height:500,
            width:600,
            content:"<?php echo yii\helpers\Url::to(["/product/uploadscheck"])?>?id="+id+'&pid='+pid,
            type:'ajax',
        });*/
    })
})
</script>
