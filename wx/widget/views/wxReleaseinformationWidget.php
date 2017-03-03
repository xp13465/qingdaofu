<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<?php if(!empty($product) && !empty($uid)){ ?>
<?php
    $user = "";
    if($product['progress_status'] == 1 && $product['uid'] == $uid){
        $userlist_url = Url::toRoute(['/usercenter/view','id'=>$product['id'],'category'=>$product['category']]);
        $user = "申请记录";
    }
    if(in_array($product['progress_status'],[2,3,4]) && $product['uid'] == $uid && $pid){
        $userlist_urls = Url::toRoute(['/usercenter/userinfo','pid'=>$pid,'id'=>$product['id'],'category'=>$product['category']]);
        $user = "接单方";
    }else if(in_array($product['progress_status'],[0,1,2,3,4]) && $product['uid'] != $uid){
        $userlist_urls = Url::toRoute(['/usercenter/ordersuser','id'=>$product['id'],'category'=>$product['category']]);
        $user = "发布方";
    }
    
?>
<style>
.head{width:100%;max-width:640px;height:50px;background:#42566E;}
.head-l{width:70%;height:50px;float:left;text-indent:20px;line-height:50px;color:#fff;font-size:16px;}
.head-r{width:30%;height:50px;text-align:right;padding-right:15px;float:left;line-height:50px;color:#fff;font-size:16px;}
.foot{position: fixed;bottom: 0;width: 100%;max-width: 640px;margin: auto;}
.foot a{color:#666;background:#fff;width:50%;height:50px;float:left;line-height:50px;text-align:center;line-height:50px;font-size:16px;}
.foot a:first-child{border-right:1px solid #ddd;}
.xin{position: fixed;bottom: 50px;text-align:center;width: 100%;max-width: 640px;height:50px;margin: auto;background:#FFAA05;}
.xin a{font-size:16px;color:#fff;line-height:50px;}

</style>



<header>
    <div class="cm-header">
        <span class="icon-back" data-category="<?php echo $product['progress_status']?>" data-uid ="<?php echo $pid ?>"></span>
        <i>产品详情</i>
		<?php if($product['progress_status']==1){ ?>
        <a href="<?php echo isset($userlist_url)?$userlist_url:'javascript:void(0);' ;?>"><img src="/images/application_record@2x.png" width="25" height="25"></a>
		<?php } ?>
    </div>
</header>
<div class="head">
<div class="head-l"><?php echo isset($product['category'])&&$product['category']==2?"清收":"诉讼"; echo isset($product['code'])?$product['code']:''?></div>
<div class="head-r">
<?php
    switch($product['progress_status']){
		case 0:
		echo "待发布";
		break;
		case 1:
		if($product['uid'] == $uid){
			echo "发布中";
		}else{
			echo "申请中";
		}
		break;
		case 2:
		echo "处理中";
		break;
		case 3:
		echo "已终止";
		break;
		case 4:
		echo "已结案";
		break;
	}
?>
</div>
</div>
<?php if(in_array($product['progress_status'],[2,3,4]) && $username['jusername'] ){?>
<div class="cp_xinxi">
        <ul>
            <a href="<?php echo $userlist_urls;?>" style="margin-top:20px;">
                <li>
                    <div class="cp_right" style="width:70%;">
                        <span style="font-size:16px;"><?php if($uid == $product['uid']){echo $user.':'.$username['jusername'];}else{echo $user.':'.$username['username'];}?></span>
                    </div>
                    <div class="arrow_l"  style="float:left;">
                        <i></i>
                    </div>
                    <div class="phone">
                    <span class="dh"></span>
                    <sapn>联系他</sapn>
                    </div>
                </li>
            </a>
        </ul>
    </div>
<?php } ?>
<section>
    <div class="show">
        <div class="show_xx">
            <span style="color:#333;">产品信息</span>
			<a href="<?php echo yii\helpers\Url::toRoute(['/releases/supplement','id'=>$product['id'],'category'=>$product['category']])?>" style="color: #0065b3;; line-height: 50px;float:right;">查看全部</a>
        </div>
        <ul class="revert">
            <?php $cate= ['1'=>'融资金额','2'=>'借款本金','3'=>'借款本金']?>
            <li>
                <div class="revert_l">
                    <span><?php echo $cate[$product['category']]?></span>
                </div>
                <div class="revert_r">
                    <span><?php echo isset($product['money'])?$product['money'].'万':''?></span>
                </div>
            </li>
            <li>
                <div class="revert_l">
                    <span>债权类型</span>
                </div>
                <div class="revert_r">
                    <?php if($product['category'] == 1){ ?>
                        <?php echo isset($product['rate'])?$product['rate'].'%':''?><?php echo isset($product['rate_cat'])?\common\models\FinanceProduct::$ratedatecategory[$product['rate_cat']]:'';?>
                    <?php }else{ ?>
                        <?php echo isset($product['loan_type'])&&$product['loan_type']==0 ? '无':common\models\CreditorProduct::$loan_types[$product['loan_type']]?>
                    <?php } ?>
                </div>
            </li>
            <li>
                <div class="revert_l">
				<?php if(isset($product['category'])&&$product['category'] == 2){ ?>	
                        <span><?php echo isset($product['agencycommissiontype'])&&$product['agencycommissiontype']==1?'服务佣金':'固定费用'?></span>
                    <?php }else{ ?>
                        <span><?php echo isset($product['agencycommission'])?\common\models\creditorProduct::$agencycommissiontype[$product['agencycommissiontype']]:'';?></span>
                    <?php } ?>
                    
                </div>
                <div class="revert_r">
                    <span>
					<?php if(isset($product['category'])&&$product['category'] == 2){ ?>	
                       <span>
                           <?php if($product['agencycommissiontype']==1){ ?>
                               <?php echo isset($product['agencycommission'])?$product['agencycommission'].'%':'暂无' ?>
                           <?php }else{ ?>
                               <?php echo isset($product["agencycommission"])?$product["agencycommission"].'万':'暂无';?>
                           <?php } ?>
                       </span>
                    <?php }else{ ?>
                            <?php if($product['agencycommissiontype']==1){ ?>
                                <?php echo isset($product["agencycommission"])?$product["agencycommission"].'万':'暂无';?>
                            <?php }else{ ?>
                                <?php echo isset($product["agencycommission"])?$product["agencycommission"].'%':'暂无';?>
                            <?php } ?>
                    <?php } ?>
					</span>
                </div>
            </li>
			<li>
			<?php if($product['loan_type'] == 1){ ?>
				<div class="revert_l">
                    <span>抵押物地址</span>
                </div>
				<div class="revert_r">
					<span><?php if($product['category'] == 1){?>
                            <?php echo \frontend\services\Func::getCityNameById($product['city_id']);?>
                            <?php echo \frontend\services\Func::getAreaNameById($product['district_id']);?>
                            <?php echo isset($product['seatmortgage'])?\frontend\services\Func::getSubstrs($product['seatmortgage']):'';?>
                    <?php } else if($product['loan_type'] == 1){?>
                            <?php echo \frontend\services\Func::getCityNameById($product['city_id']);?>
                            <?php echo \frontend\services\Func::getAreaNameById($product['district_id']);?>
                            <?php echo isset($product['seatmortgage'])?\frontend\services\Func::getSubstrs($product['seatmortgage']):'';?>
                    <?php }else{echo '';}?></p>
					<?php } ?>
                    </span>
                </div>
            </li>
        </ul>
    </div>
</section>
 <?php if(in_array($product['progress_status'],[2,3,4])){ ?>
 <div class="cp_xinxi">
        <ul>
            <a href="<?php echo yii\helpers\Url::toRoute(['/protocol/index','id'=>$product['id'],'category'=>$product['category']]);?>">
                <li>
                <div class="cp_right">
                    <span style="font-size:16px;">服务协议</span>
                </div>
                <div class="arrow_l arrow_t">
                    <span  style="color:#999;">点击查看</span>
                    <i></i>
                </div>
                </li>
            </a>
        </ul>
    </div>
  
    <div class="cp_xinxi">
        <ul>
		<?php if($uid == $product['uid']){ ?>
            <a href="<?php echo yii\helpers\Url::toRoute(['/usercenter/speed','id'=>$product['id'],'category'=>$product['category']])?>" style="margin-top:20px;">
        <?php }else{ ?>
		    <a href="<?php echo yii\helpers\Url::toRoute(['/usercenter/speed','id'=>$product['id'],'category'=>$product['category'],'type'=>1])?>" style="margin-top:20px;">
        <?php } ?>		
			   <li>
                    <div class="cp_right">
                        <span style="font-size:16px;">进度处理</span>
                    </div>
                    <div class="arrow_l">
                        <span style="color:#999;"><?php if($uid == $product['uid']){echo "点击查看";}else{echo "填写进度";}?></span>
                        <i></i>
                    </div>
                </li>
            </a>
        </ul>
    </div>
  
 <?php } ?>
<?php if($product['progress_status'] == 4){ ?>
 <div class="cp_xinxi">
       <ul>
          <a href="<?php echo yii\helpers\Url::toRoute(['/usercenter/evaluatelists','id'=>$product['id'],'category'=>$product['category']])?>" style="margin-top:20px;">
                <li>
                    <div class="cp_right">
                        <span style="font-size:16px;">互评</span>
                    </div>
                    <div class="arrow_l">
                        <span style="color:#999;">去查看</span>
                        <i></i>
                    </div>
                </li>
            </a>
        </ul>
</div>
<?php } ?>
<?php if($product['progress_status'] == 1 && $uid == $product['uid'] && $username['app_id'] === "0" ){ ?>
<div class="xin" style="display:none;">
<a href="<?php echo isset($userlist_url)?$userlist_url:'javascript:void(0);' ;?>">新的申请记录,点击查看></a>
</div>
<?php } ?>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.icon-back').bind('touchstart click',function () {
          //  var category = $(this).attr('data-category');
           // var pid  = $(this).attr('data-uid');
           // if(category == 4 && pid){
              //  window.location = "<?php /*echo yii\helpers\Url::toRoute(['/usercenter/release?status=4'])*/ ?>";
            //}else if(category == 4 && !pid){
               // window.location = "<?php /*echo yii\helpers\Url::toRoute(['/usercenter/orders?progress_status=4'])*/ ?>";
           // }else if(category == 1 && pid){
               // window.location = "<?php /*echo yii\helpers\Url::toRoute(['/usercenter/release?status=1'])*/ ?>";
            //}else{
                history.go(-1);
           // }

        });
    })
</script>
<div style="height:50px;width:50px;position:fixed;bottom:50px;">
    <a href="<?php echo yii\helpers\Url::toRoute('/site/index')?>"><img src="/images/back_home.png"></a>
</div>