<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<?=wxHeaderWidget::widget(['title'=>'产品信息','gohtml'=>''])?>
<div class="basic_01">
    <div class="basic_main current" style="padding-left:15px;background-color: #fff;">
      <ul>
        <li> <span style="font-size:18px;">基本信息</span> <span>&nbsp;</span> </li>
        <li> <span>借款本金</span> <span <?php if(!$product['money']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($product['money'])?$product['money'].'万':'暂无'?></span> </li>
        <li> <span>借款期限</span> <span <?php if(!$product['term']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($product['term'])?$product['term'].'月':'暂无'?></span> </li>
        <li> <span>还款方式</span> <span <?php if(!$product['repaymethod']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($product['repaymethod'])?\common\models\CreditorProduct::$repaymethod[$product['repaymethod']]:'暂无'?></span> </li>
        <li> <span>债权类型</span> <span <?php if(!$product['loan_type']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($product['loan_type'])?\common\models\CreditorProduct::$loan_types[$product['loan_type']]:'暂无'?></span> </li>
		
		<?php if($product['loan_type'] == 1){ ?>
		<li> <span>抵押物地址：</span> <span <?php if(!$product['city_id']){echo "style='color:#BDBCBC;'";} ?>>
		<?php echo \frontend\services\Func::getCityNameById($product['city_id']);?>
        <?php echo \frontend\services\Func::getAreaNameById($product['district_id']);?>
        <?php echo isset($product['seatmortgage'])?\frontend\services\Func::getSubstrs($product['seatmortgage']):'';?>
		</span>
		</li>
		<?php }else if($product['loan_type'] == 2){ ?>
		 <li> <span>应收账款</span><span <?php if(!$product['accountr']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($product['accountr'])?$product['accountr']:'暂无';?></span> </li>
		<?php }else if($product['loan_type'] == 3){ ?>
		<li> <span>机动车抵押</span><span <?php if(!$product['carbrand']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($product['carbrand'])?\frontend\services\Func::getCarBrand($product['carbrand']).\frontend\services\Func::getCarAudi($product['audi']):'暂无';?></span> </li>
		<li> <span>车牌类型</span><span <?php if(!$product['licenseplate']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($product['licenseplate'])?\common\models\creditorProduct::$licenseplate[$product['licenseplate']]:'暂无';?></span> </li>
		<?php } ?>
        <li> <span>债务人主体</span> <span <?php if(!$product['obligor']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($product['obligor'])?\common\models\creditorProduct::$obligor[$product['obligor']]:'暂无';?></span> </li>
        <li> <span>逾期日期</span> <span <?php if(!$product['start']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($product['start'])?date("Y-m-d",$product['start']):'暂无';?></span> </li>
        <li> <span>委托事项</span> <span <?php if(!$product['commitment']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($product['commitment'])?\common\models\creditorProduct::$commitment[$product['commitment']]:'暂无'?></span> </li>
        <li> <span>委托代理期限</span> <span <?php if(!$product['commissionperiod']){echo "style='color:#BDBCBC;'";} ?> ><?php echo isset($product['commissionperiod'])?$product['commissionperiod'].'月':'暂无';?></span> </li>
        <?php if(isset($product['category'])&&$product['category'] == 2){ ?>
                        <li><span><?php echo isset($product['agencycommissiontype'])&&$product['agencycommissiontype']==1?'服务佣金':'固定费用'?></span>
						<span <?php if(!$product['agencycommissiontype']){echo "style='color:#BDBCBC;'";} ?>>
                           <?php if($product['agencycommissiontype']==1){ ?>
                               <?php echo isset($product['agencycommission'])?$product['agencycommission'].'%':'暂无' ?>
                           <?php }else{ ?>
                               <?php echo isset($product["agencycommission"])?$product["agencycommission"].'万':'暂无';?>
                           <?php } ?>
                       </span>
						</li>		
                       
                    <?php }else{ ?>
					<li>
                        <span><?php echo isset($product['agencycommission'])?\common\models\creditorProduct::$agencycommissiontype[$product['agencycommissiontype']]:'';?></span>
                        <span <?php if(!$product['agencycommissiontype']){echo "style='color:#BDBCBC;'";} ?>>
                            <?php if($product['agencycommissiontype']==1){ ?>
                                <?php echo isset($product["agencycommission"])?$product["agencycommission"].'万':'暂无';?>
                            <?php }else{ ?>
                                <?php echo isset($product["agencycommission"])?$product["agencycommission"].'%':'暂无';?>
                            <?php } ?>
                    </span>
					</li>
                    <?php } ?>
		
		
        <li> <span style="font-size:18px;">补充信息</span> <span>&nbsp;</span> </li>
        <li> <span>已付本金</span> <span <?php if(!$product['paidmoney']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($product['paidmoney'])?$product['paidmoney'].'元':'暂无'?></span> </li>
        <li> <span>已付利息</span> <span <?php if(!$product['interestpaid']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($product['interestpaid'])?$product['interestpaid'].'元':'暂无'?></span> </li>
        <li> <span>合同履行地</span> <span <?php if(!$product['place_city_id']){echo "style='color:#BDBCBC;'";} ?>>
		<?php echo isset($product['place_city_id'])?\frontend\services\Func::getCityNameById($product['place_city_id']):'';?>
        <?php echo isset($product['place_district_id'])?\frontend\services\Func::getAreaNameById($product['place_district_id']):'';?>
		</span> </li>
        <li> <span>借款利率</span> <span <?php if(!$product['rate']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($product['rate'])?($product['rate'].(isset($product['rate_cat'])&&$product['rate_cat'] == 1?'%/天':'%/月')):'暂无'?></span> </li>
   
		<?php $creditorfile = $product['creditorfile']?unserialize($product['creditorfile']):[];
		      $creditorinfo = $product['creditorinfo']?unserialize($product['creditorinfo']):[];
			  $borrowinginfo = $product['borrowinginfo']?unserialize($product['borrowinginfo']):[];
		?>
		<li> <span>债权文件</span> <span <?php if($creditorfile){ echo "class='creditorfile'";}else{echo "style='color:#BDBCBC;'";} ?> >查看</span> </li>
		
        <li> <span>债权人信息</span> <span <?php if($creditorinfo){echo "class='creditorprofile'";}else{echo "style='color:#BDBCBC;'";}?> >查看</span> </li>
        <li> <span>债务人信息</span> <span <?php if($borrowinginfo){echo "class='uncreditorprofile'";}else{echo "style='color:#BDBCBC;'";}?> >查看</span> </li>
      </ul>
      </ul>
    </div>
	<script>
	$('.creditorfile').click(function(){
            window.location.href = "<?php echo \yii\helpers\Url::toRoute(['/list/pro','id'=>$product['id'],'category'=>$product['category'],'type'=>1])?>";
        });

        $('.creditorprofile').click(function(){
            window.location.href = "<?php echo \yii\helpers\Url::toRoute(['/list/pro','id'=>$product['id'],'category'=>$product['category'],'type'=>2])?>";
        });

        $('.uncreditorprofile').click(function(){
            window.location.href = "<?php echo \yii\helpers\Url::toRoute(['/list/pro','id'=>$product['id'],'category'=>$product['category'],'type'=>3])?>";
        });
	</script>
    <style>
    .basic_main li span:last-child {
    float: right;
    color: #0065b3;
     margin-right: 15px; 
    font-size: 14px;
}
    </style>