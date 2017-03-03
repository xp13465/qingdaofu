<?php
	// $user = common\models\User::findOne(['id'=>$product['uid']]);
	$certification = common\models\Certification::findOne(['uid'=>$userid]);
?>
<li>
    <div class="clearfix">
        <div class="t_l fl">
            <p><b><?php echo common\models\CreditorProduct::$categorys[$product['category']]?></b>　<?php echo isset($product['code'])?$product['code']:'';?></p>
            <p class="color">[<?php echo isset($certification['state'])&&$certification['state'] == 1?'已认证':'未认证'?><i>
                    <?php echo isset($certification['category'])?\common\models\Certification::$certifi[$certification['category']]:'';?></i>]</p>
        </div>
        <div <?php if($product['category'] == 1){echo 'class="t_r fr"';}else if($product['category'] == 2){echo 'class="t_r1 fr"';}else{echo 'class="t_r2 fr"';} ?>>
            <?php if($product['category'] == 1){ ?>
                <span>利率<br><?php echo isset($product['rate'])?$product['rate']:'';?>%<?php echo isset($product['rate_cat'])?\common\models\FinanceProduct::$ratedatecategory[$product['rate_cat']]:''?></span>
            <?php }else{ ?>
                <span><?php echo isset($product['loan_type'])&&$product['loan_type']==0 ? '无':common\models\CreditorProduct::$loan_types[$product['loan_type']]?></span>
            <?php } ?>
        </div>
    </div>
     <div class="r_c clearfix">
                <span class="fl" style="border-left:none;"><b><?php echo isset($product['money'])?$product['money']:'';?></b><br>金额(万元)</span>
                 <span class="fr">
                 <?php if($product['category'] == 1){ ?>
                     <b><?php echo isset($product['rebate'])?$product['rebate']:'';?>%</b><br>返点
                 <?php }else if($product['category'] == 2){?>
                     <b><?php echo isset($product['agencycommissiontype'])&&$product['agencycommissiontype']==1?$product['agencycommission'].'%':$product['agencycommission'].'万';?></b><br><?php echo $product['agencycommissiontype']==1?'服务佣金':'固定费用'?>
                 <?php }else if($product['category'] ==3){ ?>
                    <b>
                     <?php if($product['agencycommissiontype']==1){ ?>
                         <?php echo isset($product['agencycommission'])?$product['agencycommission'].'万':''?>
                     <?php }else{ ?>
                         <?php echo isset($product['agencycommission'])?$product['agencycommission'].'%':''?>
                     <?php } ?>
                    </b>
				<br>
				<?php echo isset($product['agencycommission'])?\common\models\creditorProduct::$agencycommissiontype[$product['agencycommissiontype']]:'';?>
                <?php } ?>
        </span>
    </div>
    <div class="qys">
       <?php if($product['category'] == 1){?>
           <p>区域：
               <?php echo \frontend\services\Func::getCityNameById($product['city_id']);?>
               <?php echo \frontend\services\Func::getAreaNameById($product['district_id']);?>
           </p>
       <?php } else if($product['loan_type'] == 1){?>
           <p>区域：
               <?php echo \frontend\services\Func::getCityNameById($product['city_id']);?>
               <?php echo \frontend\services\Func::getAreaNameById($product['district_id']);?>
           </p>
       <?php }else{ ?>
             <div style="height:40px;"></div>

       <?php } ?>
    </div>
    <?php if($product['progress_status'] == 2){
        echo "<input type='button' value='已申请' class='sqs' style='background:#b5b5b5;'>";
    }else if($product['progress_status'] == 3){
        echo "<input type='button' value='已终止' class='sqs' style='background:#b5b5b5;'>";
    }else if($product['progress_status'] == 4){
        echo "<input type='button' value='已结案' class='sqs' style='background:#b5b5b5;'>";
    }else{
        echo '<a href="javascript:void(0);" class="see data-cbd" data-id='.$product["id"].' data-category='.$product["category"].'>立即申请</a>';
    }?>

</li>