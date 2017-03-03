<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
?>
<style>
.corpus-d{width:100%;max-width:640px;height:100px;background:#fff;}
.corpus-d ul li{width:33.3%;height:100px;float:left;}
.corpus-d ul li p{font-size:16px;text-align:center;padding-top:20px;color:#656565;}
.corpus-d ul li .pr{font-size:20px;color:#0da3f8;}
.basic_main li span:last-child{float:right;color:#999;margin-right:15px;font-size:14px;}
</style>
<header class="loan_lv" style="position:relative;background:#0da3f8;">
  <div class="ck-header" style="text-align:center;line-height:50px;"> 
  <span class="icon-back"></span> 
  <i style="color:#fff;">
  <?php echo isset($apply['code'])?$apply['code']:''?>
  </i> 
  <?php if(isset($data['app_id']) && $data['app_id'] == 2){ ?>
       <span class="heart1" data-category="<?php echo $apply['category']?>" data-id="<?php echo $apply['id'] ?>"></span>
       <?php }else{ ?>
       <span class="heart" data-category="<?php echo $apply['category']?>" data-id="<?php echo $apply['id'] ?>"></span>
  <?php } ?>
</div>
  <p class="font_12"><?php if($apply['category'] == 1){ echo '借款利率';echo '(';echo isset($apply['rate_cat'])?(\common\models\FinanceProduct::$ratedatecategory[$apply['rate_cat']]):'';echo ')';}else if($apply['category'] == 2){echo isset($apply['agencycommissiontype'])&&$apply['agencycommissiontype']==1 ?'服务佣金':'固定费用';}
        else if($apply['category'] == 3){ ?>
            <?php echo isset($apply['agencycommission'])?\common\models\CreditorProduct::$agencycommissiontype[$apply['agencycommissiontype']]:'';?>
        <?php } ?></p>
    <p class="font_30">
        <?php if($apply['category'] == 1){ ?>
            <?php echo isset($apply['rate'])?$apply['rate']:'';?><span>%</span>
        <?php }else if($apply['category'] == 2){?>
			<?php echo isset($apply['agencycommissiontype'])&&$apply['agencycommissiontype']==1?$apply['agencycommission'].'%':$apply['agencycommission'].'万';?>
        <?php }else if($apply['category'] == 3){?>
            <?php if($apply['agencycommissiontype']==1){ ?>
            <?php echo isset($apply['agencycommission'])?$apply['agencycommission'].'万':''?>
        <?php }else{ ?>
            <?php echo isset($apply['agencycommission'])?$apply['agencycommission'].'%':''?>
        <?php } ?>
		<?php }?>
    </p>
</header>
<section>
  <div class="corpus" style="background:#1bacff;">
    <div class="corpus_l" style="border-right:1px solid #fff;">
      <p class="ben">借款本金</p>
      <p style="font-size:16px;"><?php echo isset($apply['money'])?$apply['money'].'万':''?></p>
    </div>
    <div class="corpus_l">
      <p class="ben">债权类型</p>
      <p style="font-size:16px;"><?php echo isset($apply['loan_type'])&&$apply['loan_type']==0 ? '无':common\models\CreditorProduct::$loan_types[$apply['loan_type']]?></p>
    </div>
  </div>
  <div class="corpus-d">
    <ul>
      <li>
        <p>浏览次数</p>
        <p class="pr"><?php echo isset($apply['browsenumber'])?$apply['browsenumber']:'0';?></p>
      </li>
      <li>
        <p>申请次数</p>
	
        <p class="pr"><?php echo isset($number['shenqing'])?$number['shenqing']:'0';?></p>
      </li>
      <li>
        <p>收藏次数</p>
        <p class="pr"><?php echo isset($number['shoucang'])?$number['shoucang']:'0';?></p>
      </li>
    </ul>
  </div>
</section>
<section>
  <div class="basic" style="margin-top:12px;position:relative;z-index:666;border-bottom:1px solid #ddd;">
    <ul>
      <li> <span class="current">产品信息</span></li>
      <li> <span>发布方信息</span></li>
    </ul>
  </div>
  <div class="basic_01">
    <div class="basic_main current" style="padding-left:15px;background-color: #fff;">
      <ul>
        <li> <span style="font-size:18px;">基本信息</span> <span>&nbsp;</span> </li>
        <li> <span>借款本金</span> <span <?php if(!$apply['money']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($apply['money'])?$apply['money'].'万':'暂无'?></span> </li>
        <li> <span>借款期限</span> <span <?php if(!$apply['term']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($apply['term'])?$apply['term'].'月':'暂无'?></span> </li>
        <li> <span>还款方式</span> <span <?php if(!$apply['repaymethod']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($apply['repaymethod'])?\common\models\CreditorProduct::$repaymethod[$apply['repaymethod']]:'暂无'?></span> </li>
        <li> <span>债权类型</span> <span <?php if(!$apply['loan_type']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($apply['loan_type'])?\common\models\CreditorProduct::$loan_types[$apply['loan_type']]:'暂无'?></span> </li>
		
		<?php if($apply['loan_type'] == 1){ ?>
		<li> <span>抵押物地址：</span> <span <?php if(!$apply['city_id']){echo "style='color:#BDBCBC;'";} ?>>
		<?php echo \frontend\services\Func::getCityNameById($apply['city_id']);?>
        <?php echo \frontend\services\Func::getAreaNameById($apply['district_id']);?>
        <?php echo isset($apply['seatmortgage'])?\frontend\services\Func::getSubstrs($apply['seatmortgage']):'';?>
		</span>
		</li>
		<?php }else if($apply['loan_type'] == 2){ ?>
		 <li> <span>应收账款</span><span <?php if(!$apply['accountr']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($apply['accountr'])?$apply['accountr']:'暂无';?></span> </li>
		<?php }else if($apply['loan_type'] == 3){ ?>
		<li> <span>机动车抵押</span><span <?php if(!$apply['carbrand']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($apply['carbrand'])?$apply['carbrand'].'-'.$apply['audi']:'暂无';?></span> </li>
		<li> <span>车牌类型</span><span <?php if(!$apply['licenseplate']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($apply['licenseplate'])?\common\models\creditorProduct::$licenseplate[$apply['licenseplate']]:'暂无';?></span> </li>
		<?php } ?>
        <li> <span>债务人主体</span> <span <?php if(!$apply['obligor']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($apply['obligor'])?\common\models\creditorProduct::$obligor[$apply['obligor']]:'暂无';?></span> </li>
        <li> <span>逾期日期</span> <span <?php if(!$apply['start']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($apply['start'])?date("Y-m-d",$apply['start']):'暂无';?></span> </li>
        <li> <span>委托事项</span> <span <?php if(!$apply['commitment']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($apply['commitment'])?\common\models\creditorProduct::$commitment[$apply['commitment']]:'暂无'?></span> </li>
        <li> <span>委托代理期限</span> <span <?php if(!$apply['commissionperiod']){echo "style='color:#BDBCBC;'";} ?> ><?php echo isset($apply['commissionperiod'])?$apply['commissionperiod'].'月':'暂无';?></span> </li>
        <?php if(isset($apply['category'])&&$apply['category'] == 2){ ?>
                        <li><span><?php echo isset($apply['agencycommissiontype'])&&$apply['agencycommissiontype']==1?'服务佣金':'固定费用'?></span>
						<span <?php if(!$apply['agencycommissiontype']){echo "style='color:#BDBCBC;'";} ?>>
                           <?php if($apply['agencycommissiontype']==1){ ?>
                               <?php echo isset($apply['agencycommission'])?$apply['agencycommission'].'%':'暂无' ?>
                           <?php }else{ ?>
                               <?php echo isset($apply["agencycommission"])?$apply["agencycommission"].'万':'暂无';?>
                           <?php } ?>
                       </span>
						</li>		
                       
                    <?php }else{ ?>
					<li>
                        <span><?php echo isset($apply['agencycommission'])?\common\models\creditorProduct::$agencycommissiontype[$apply['agencycommissiontype']]:'';?></span>
                        <span <?php if(!$apply['agencycommissiontype']){echo "style='color:#BDBCBC;'";} ?>>
                            <?php if($apply['agencycommissiontype']==1){ ?>
                                <?php echo isset($apply["agencycommission"])?$apply["agencycommission"].'万':'暂无';?>
                            <?php }else{ ?>
                                <?php echo isset($apply["agencycommission"])?$apply["agencycommission"].'%':'暂无';?>
                            <?php } ?>
                    </span>
					</li>
                    <?php } ?>
		
		
        <li> <span style="font-size:18px;">补充信息</span> <span>&nbsp;</span> </li>
        <li> <span>已付本金</span> <span <?php if(!$apply['paidmoney']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($apply['paidmoney'])?$apply['paidmoney'].'元':'暂无'?></span> </li>
        <li> <span>已付利息</span> <span <?php if(!$apply['interestpaid']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($apply['interestpaid'])?$apply['interestpaid'].'元':'暂无'?></span> </li>
        <li> <span>合同履行地</span> <span <?php if(!$apply['place_city_id']){echo "style='color:#BDBCBC;'";} ?>>
		<?php echo isset($apply['place_city_id'])?\frontend\services\Func::getCityNameById($apply['place_city_id']):'';?>
        <?php echo isset($apply['place_district_id'])?\frontend\services\Func::getAreaNameById($apply['place_district_id']):'';?>
		</span> </li>
        <li> <span>借款利率</span> <span <?php if(!$apply['rate']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($apply['rate'])?($apply['rate'].(isset($apply['rate_cat'])&&$apply['rate_cat'] == 1?'%/天':'%/月')):'暂无'?></span> </li>
   
		<?php $creditorfile = $apply['creditorfile']?unserialize($apply['creditorfile']):[];
		      $creditorinfo = $apply['creditorinfo']?unserialize($apply['creditorinfo']):[];
			  $borrowinginfo = $apply['borrowinginfo']?unserialize($apply['borrowinginfo']):[];
		?>
		
		<li> <span>债权文件</span> <span <?php if(isset($creditorfile["imgnotarization"])||isset($creditorfile["imgbenjin"])||isset($creditorfile["imgshouju"])||isset($creditorfile["imgpick"])||isset($creditorfile["imgcreditor"])||isset($creditorfile["imgcontract"])){ echo "class='creditorfile'";}else{echo "style='color:#BDBCBC;'";} ?> >查看</span> </li>
        <li> <span>债权人信息</span> <span <?php if($creditorinfo){echo "class='creditorprofile'";}else{echo "style='color:#BDBCBC;'";}?> >查看</span> </li>
        <li> <span>债务人信息</span> <span <?php if($borrowinginfo){echo "class='uncreditorprofile'";}else{echo "style='color:#BDBCBC;'";}?> >查看</span> </li>
      </ul>
    </div>
    <div class="basic_main" style="padding-left:15px;background-color:#fff;">
      <ul>
	  <?php if($certification['category']==1){?>
	        <li>
                <span>姓名</span>
                <span <?php if(!$certification['name']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($certification['name'])?$certification['name']:'暂无'?></span>
            </li>
            <li>
                <span>身份证号码</span>
                <span <?php if(!$certification['cardno']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($certification['cardno'])?\frontend\services\Func::HideStrRepalceByChar($certification['cardno'],'*',4,4):'暂无'?></span>
            </li>
            <li>
                <span>身份图片</span>
                <span <?php if(!$certification['cardimg']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($certification['cardimg'])?'已上传':'未上传'?></span>
            </li>
            <li>
                <span>邮箱</span>
                <span <?php if(!$certification['email']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($certification['email'])?\frontend\services\Func::HideStrRepalceByChar($certification['email'],'*',3,10):'暂无'?></span>
            </li>
            <li>
                <span>经典案例</span>
                <?php if($certification['casedesc']){ ?>
                     <a href="<?php echo yii\helpers\Url::toRoute(['/usercenter/case','id'=>$certification['id']])?>"><span class="check" style="color:#0065b3;">查看</span></a>
                <?php }else{ ?>
                     <a href="javascript:void(0);"><span <?php if(!$certification['casedesc']){echo "style='color:#BDBCBC;'";} ?>>暂无</span></a>
                <?php } ?>
               
            </li>
	  <?php }else if($certification['category']==2){?>
	    <li>
                    <span>律所名称</span>
                    <span <?php if(!$certification['name']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($certification['name'])?$certification['name']:'暂无'?></span>
                </li>
                <li>
                    <span>执业证号</span>
                    <span <?php if(!$certification['cardno']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($certification['cardno'])?\frontend\services\Func::HideStrRepalceByChar($certification['cardno'],'*',4,4):'暂无'?></span>
                </li>
                <li>
                    <span>身份图片</span>
                    <span <?php if(!$certification['cardimg']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($certification['cardimg'])?'已上传':'未上传'?></span>
                </li>
                <li>
                    <span>联系人</span>
                    <span <?php if(!$certification['contact']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($certification['contact'])?$certification['contact']:'暂无'?></span>
                </li>
                <li>
                    <span>联系方式</span>
                    <span <?php if(!$certification['mobile']){echo "style='color:#BDBCBC;'";} ?>><a href="tel:4008557022"><?php echo isset($certification['mobile'])?\frontend\services\Func::HideStrRepalceByChar($certification['mobile'],'*',3,4):'暂无'?></a></span>
                </li>
                <li>
                    <span>邮箱</span>
                    <span <?php if(!$certification['email']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($certification['email'])?\frontend\services\Func::HideStrRepalceByChar($certification['email'],'*',3,10):'暂无'?></span>
                </li>
                <li>
                    <span>经典案例</span>
                    <?php if($certification['casedesc']){ ?>
                     <a href="<?php echo yii\helpers\Url::toRoute(['/usercenter/case','id'=>$certification['id']])?>"><span class="check" style="color:#0065b3;">查看</span></a>
                <?php }else{ ?>
                     <a href="javascript:void(0);"><span <?php if(!$certification['casedesc']){echo "style='color:#BDBCBC;'";} ?>>暂无</span></a>
                <?php } ?>
                </li>
	  <?php }else{ ?>
	        <li>
                    <span>公司名称</span>
                    <span <?php if(!$certification['name']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($certification['name'])?$certification['name']:'暂无'?></span>
                </li>
                <li>
                    <span>营业许可证号</span>
                    <span <?php if(!$certification['cardno']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($certification['cardno'])?\frontend\services\Func::HideStrRepalceByChar($certification['cardno'],'*',4,4):'暂无'?></span>
                </li>
                <li>
                    <span>身份图片</span>
                    <span <?php if(!$certification['cardimg']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($certification['cardimg'])?'已上传':'未上传'?></span>
                </li>
                <li>
                    <span>联系人</span>
                    <span <?php if(!$certification['contact']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($certification['contact'])?$certification['contact']:'暂无'?></span>
                </li>
                <li>
                    <span>联系方式</span>
                    <span <?php if(!$certification['mobile']){echo "style='color:#BDBCBC;'";} ?>><a href="tel:4008557022"><?php echo isset($certification['mobile'])?\frontend\services\Func::HideStrRepalceByChar($certification['mobile'],'*',3,4):'暂无'?></a></span>
                </li>
                <li>
                    <span>公司经营地址</span>
                    <span <?php if(!$certification['address']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($certification['address'])?$certification['address']:'暂无'?></span>
                </li>
                <li>
                    <span>公司网站</span>
                    <span <?php if(!$certification['enterprisewebsite']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($certification['enterprisewebsite'])?$certification['enterprisewebsite']:'暂无'?></span>
                </li>
                <li>
                    <span>邮箱</span>
                    <span <?php if(!$certification['email']){echo "style='color:#BDBCBC;'";} ?>><?php echo isset($certification['email'])?\frontend\services\Func::HideStrRepalceByChar($certification['email'],'*',3,10):'暂无'?></span>
                </li>
                <li>
                    <span>经典案例</span>
                    <?php if($certification['casedesc']){ ?>
                     <a href="<?php echo yii\helpers\Url::toRoute(['/usercenter/case','id'=>$certification['id']])?>"><span class="check" style="color:#0065b3;">查看</span></a>
                <?php }else{ ?>
                     <a href="javascript:void(0);"><span <?php if(!$certification['casedesc']){echo "style='color:#BDBCBC;'";} ?>>暂无</span></a>
                <?php } ?>
                </li>
	  <?php } ?>
      </ul>
    </div>
  </div>
  
  
  <footer>
    <div class="apply">
        <div class="<?php if(empty($data)||$data['app_id']==2 && $apply['progress_status'] == 1){echo 'apply_sq';}else{echo'apply_sq case01';}?>">
            <?php $cate = [1=>'立即申请',2=>'处理中',3=>'已终止',4=>'已结案']?>
            <?php if( empty($data)||$data['app_id']==2 && $apply['progress_status'] == 1){ ?>
            <a href="javascript:void(0)" class="application" data-category="<?php echo $apply['category']?>" data-id="<?php echo $apply['id'] ?>">立即申请</a>
            <?php }else if($apply['progress_status'] == 1 && isset($data['app_id'])&&$data['app_id']==0){ ?>
                <a href="javascript:void(0)">已申请</a>
            <?php }else{ ?>
                <a href="javascript:void(0)"><?php echo $cate[$apply['progress_status']]?></a>
            <?php }?>
        </div>
    </div>
</footer>
<div style="height:50px;width:50px;position:fixed;bottom:50px;">
    <a href="<?php echo yii\helpers\Url::toRoute('/site/index')?>"><img src="/images/back_home.png"></a>
</div>
</section>

<script type="text/javascript">
    $(document).ready(function(){
		$('.basic li span').click(function(){
            var index = $(this).parent().index();
            $(this).addClass('current').parent().siblings().children().removeClass('current').parents().parent().next().children().eq(index).show().siblings().hide();
        });
        $('.application').click(function(){
        var category = $(this).attr('data-category');
        var id       = $(this).attr('data-id');
        $.ajax({
            url:"<?php echo yii\helpers\Url::to('/list/application')?>",
            type:'post',
            data:{category:category,id:id},
            dataType:'json',
            success:function(json){
                if(json.code == '0000'){
                    alert(json.msg);
                    location.href = "<?php echo yii\helpers\Url::toRoute(['/usercenter/orders','progress_status'=>1]);?>";
                }else{
                    if(json.code == '3015'){
                        alert(json.msg);
                        location.href = "<?php echo yii\helpers\Url::toRoute(['/certification/index']);?>";
                    }else{
                        alert(json.msg);
                    }

                }
            }
        })
        })

        $('.heart').click(function(){
            var category = $(this).attr('data-category');
            var id       = $(this).attr('data-id');
            $.ajax({
                url:"<?php echo yii\helpers\Url::to('/list/collection')?>",
                type:'post',
                data:{category:category,id:id},
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
                        alert(json.msg);
                        location.href = "<?php echo yii\helpers\Url::toRoute('/list/applyorder/');?>?id=" + id + "&category=" + category;
                    }else{
                        alert(json.msg);
                    }
                }
            })
        })

        $('.heart1').click(function(){
            var category = $(this).attr('data-category');
            var id       = $(this).attr('data-id');
            $.ajax({
                url:"<?php echo yii\helpers\Url::toRoute('/usercenter/deletes')?>",
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
            });
        });
		 $('.icon-back').click(function () {
            history.back();
        });
		
		
    })
	
	$('.creditorfile').click(function(){
            window.location.href = "<?php echo \yii\helpers\Url::toRoute(['/list/pro','id'=>$apply['id'],'category'=>$apply['category'],'type'=>1])?>";
        });

        $('.creditorprofile').click(function(){
            window.location.href = "<?php echo \yii\helpers\Url::toRoute(['/list/pro','id'=>$apply['id'],'category'=>$apply['category'],'type'=>2])?>";
        });

        $('.uncreditorprofile').click(function(){
            window.location.href = "<?php echo \yii\helpers\Url::toRoute(['/list/pro','id'=>$apply['id'],'category'=>$apply['category'],'type'=>3])?>";
      });
</script>

