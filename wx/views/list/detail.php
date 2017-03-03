<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
$categoryLabel = "未认证";
$nameLabel = "";
$cardnoLabel = "";
$contactLabel = "";
$mobileLabel = "";
$emailLabel = "";
$casedescLabel ="";
$addressLabel = "";
$enterprisewebsiteLabel = "";
if(isset($data['User']['certification'])&&$data['User']['certification']['state']==1){
	switch($data['User']['certification']['category']){
		case 1:
			$categoryLabel='已认证个人';
			$nameLabel = '姓名';
			$cardnoLabel='身份证号码';
			$mobileLabel = '联系方式';
			$emailLabel = '邮箱';
			break;
		case 2:
			$categoryLabel='已认证律所';
			$nameLabel = '律所名称';
			$cardnoLabel='执业证号';
			$contactLabel = '联系人';
			$mobileLabel = '联系方式';
			$emailLabel = '邮箱';
			$casedescLabel = '经典案例';
			break;
		case 3:
			$categoryLabel='已认证公司';
			$nameLabel = '企业名称';
			$cardnoLabel='营业执照号';
			$contactLabel = '联系人';
			$mobileLabel = '联系方式';
			$emailLabel = '企业邮箱';
			$addressLabel = '企业地址';
			$enterprisewebsiteLabel = '公司网站';
			$casedescLabel = '经典案例';
			break;
	}
}
//var_dump($data);echo Yii::$app->user->getId();

?>
<style>
.layui-layer-btn{padding: 10px 10px 10px;pointer-events: auto;border-top: 1px solid #ddd;}
.layui-layer-btn a {
    height: 35px;
    line-height: 35px;
    margin: 0 6px;
    width: 120px;
    text-align: center;
    border: 0px solid #dedede;
    background: #fff;
    color: #0da3f9;
    border-radius: 2px;
    font-weight: 400;
    cursor: pointer;
    text-decoration: none;
}
.layui-layer-btn .layui-layer-btn0 {background-color: #fff;color: #0da3f9;}
</style>
<body>
<header class="loan_lv" style="position:relative;background:#0da3f8;">
	<div class="ck-header" style="text-align:center;line-height:50px;"> 
	<span class="" style="left:0;position: absolute;top: 0;">
	<span class="icon-back"></span>
	</span>
	<i style="color:#fff;font-size:16px;"> 债权<?=$data['number']?> </i> 
	<span class="heart <?=$data['collectionPeople']?"heart1":""?>"  data-id="<?=$data['productid']?>"></span>
    </div>
</header>
<section>
<div class="loan_lv">
	<p class="font_12"><?php if($data['type']==1){echo '固定费用';}else{echo '风险代理';}?></p>
	<p class="font_30"><?= isset($data['typenumLabel'])?$data['typenumLabel']:'' ?><i><?= isset($data['typenumLabel'])?$data['typeLabel']:''?></i></p>
</div>
  <div class="corpus" style="background:#1bacff;">
    <div class="corpus_l" style="border-right:1px solid #dcecff;">
      <p class="ben">委托金额</p>
      <p style="font-size:16px;"><?= isset($data['accountLabel'])?str_replace("万","",$data['accountLabel']):0 ?><i style="font-size:12px;">万</i></p>
    </div>
    <div class="corpus_l">
      <p class="ben">违约期限</p>
      <p style="font-size:16px;"><?=$data['overdue']?><i style="font-size:12px;">个月</i></p>
    </div>
  </div>
  <div class="corpus-d">
    <ul>
      <li>
        <p>浏览次数</p>
        <p class="pr"><?= $data['browsenumber'] ?></p>
      </li>
      <li>
        <p>申请次数</p>
        <p class="pr"><?= isset($data['applyTotal'])?$data['applyTotal']:0 ?></p>
      </li>
      <li>
        <p>收藏次数</p>
        <p class="pr"><?= isset($data['collectionTotal'])?$data['collectionTotal']:0  ?></p>
      </li>
    </ul>
  </div>
</section>
<section>
  <div class="basic" style="border-bottom:1px solid #ddd;margin-top:12px;position:relative;z-index:666;">
    <ul>
      <li> <span class="current">产品信息</span></li>
      <li> <span class="">发布方信息</span></li>
    </ul>
  </div>
  <div class="basic_01" style="margin-top:12px;">
    <div class="basic_main current" style="padding-left: 15px; display: block; background-color: rgb(255, 255, 255);">
      <ul>
        <li> <span style="font-size:18px;color:#333;">基本信息</span> <span>&nbsp;</span> </li>
        <li> <span>债权类型</span> <span><?= isset($data['categoryLabel'])?$data['categoryLabel']:''?></span> </li>
        <li> <span>委托事项</span> <span><?= isset($data['entrustLabel'])?$data['entrustLabel']:''?></span> </li>
        <li> <span>委托金额</span> <span><?= isset($data['accountLabel'])?$data['accountLabel']:'' ?>万</span> </li>
		<li>
			<span><?php if($data['type']==1){echo '固定费用';}else{echo '风险代理';}?></span>
			<span><?php if($data['type']==1){echo $data['typenumLabel'].$data['typeLabel'];}else{ echo $data['typenum'].$data['typeLabel'];}?></span>
		</li>
		<li>
			<span>逾期期限</span>
			<span><?=($data['overdue'])?>个月</span>
		  </li>
		  <li>
			<span>合同履行地</span> 
			<span><?=($data['addressLabel'])?></span> 
		  </li>
	 </ul>
    </div>
	<div class="basic_main" style="padding-left: 15px; display: none; background-color: rgb(255, 255, 255);">
      <ul>
	    <li> <span style="font-size:18px;color:#333;">认证信息</span> <span style="color:orange;float:right;"><?=$categoryLabel?></span> </li>
      
				<li> <span>昵称</span> <span><?=$data['User']['realname']?$data['User']['realname']:$data['User']['username']?></span> </li>
			<?php if($data['User']['certification']){?>
				<li> <span><?= $nameLabel ?></span> <span><?=$data['User']['certification']['name']?></span> </li>
				<li> <span><?= $cardnoLabel ?></span> <span><?=$data['User']['certification']['cardno']?></span> </li>
			<?php if(isset($contactLabel)&&$contactLabel){ ?>
				<li> <span><?= $contactLabel ?></span> <span><?=$data['User']['certification']['contact']?></span> </li>
			<?php } ?>
				<li> <span><?= $mobileLabel ?></span> <span><?=$data['User']['certification']['mobile']?></span> </li>
			<?php if(isset($addressLabel)&&$addressLabel){ ?>
				<li> <span><?= $addressLabel ?></span> <span><?=$data['User']['certification']['address']?></span> </li>
			<?php } ?>
			<?php if(isset($enterprisewebsiteLabel)&&$enterprisewebsiteLabel){ ?>
				<li> <span><?= $enterprisewebsiteLabel ?></span> <span><?=$data['User']['certification']['enterprisewebsite']?></span> </li>
			<?php } ?>
				<li> <span><?= $emailLabel ?></span> <span><?=$data['User']['certification']['email']?></span> </li>
			<?php if(isset($casedescLabel)&&$casedescLabel){ ?>
				<li> <span><?= $casedescLabel ?></span> <span><?=$data['User']['certification']['casedesc']?></span> </li>
			<?php } ?>
		<?php }?>
      </ul>
    </div>
  </div>
  <footer>
   <?php if(in_array($data['status'],['20','30','40'])){ ?>
	<div class="apply">
      <div class="apply_sq"> <a href="javascript:void(0)" style='background-color: #B0B0B0;'>已撮合</a></div>
    </div>
  <?php }else if($data['applyPeople']){ ?>
	<div class="apply">
      <div class="apply_sq"> 
	  <?php if($data['apply']&&$data['apply']['status']=="10"){?>
	  <a href="javascript:void(0)" class='quxiao' data-applyid='<?=$data['apply']["applyid"]?>'style='background-color: #B0B0B0;'>取消申请</a>
	  <?php }else{ ?>
		 <a href="javascript:void(0)" style='background-color: #B0B0B0;'>面谈中</a> 
		  
	  <?php }?>
	  </div>
    </div>
   <?php }else if(!$data['applyPeople']&&$data['create_by']!= $userid){ ?>
    <div class="apply">
      <div class="apply_sq"> <a href="javascript:void(0)" class="application" data-id="<?=$data['productid']?>">立即申请</a> </div>
    </div>
  <?php } ?> 
  </footer>
</section>
<div style="height:50px;width:50px;position:fixed;bottom:50px;z-index:99999;">
	<a href="<?= yii\helpers\Url::toRoute('/site/index') ?> ">
		<img src="/images/back_home.png">
	</a>
</div>
<script type="text/javascript">
    $(document).ready(function(){
		$(".icon-back").click(function(){
			history.go(-1);
			var index = layer.load(1, {
				time:2000,
				shade: [0.4,'#fff'] //0.1透明度的白色背景
			});
		})
		$(".heart").click(function(){
			var type = $(this).hasClass("heart1");
			
			var url = !type?"<?php echo yii\helpers\Url::toRoute('/list/collect')?>":"<?php echo yii\helpers\Url::toRoute('/list/collect-cancel')?>";
			 $.ajax({
                url:url,
                type:'post',
				async:false,
                data:{productid:$(this).attr("data-id")},
                dataType:'json',
                success:function(json){
					if(json.code=='0000'){
						var type = $(".heart").hasClass("heart1");
						if(!type){
							$(".heart").addClass("heart1")
						}else{
							$(".heart").removeClass("heart1")
						}
					}
					layer.msg(json.msg)
                    
                }
            })
		})
		$(".application").click(function(){
			 $.ajax({
                url:'<?php echo yii\helpers\Url::toRoute('/list/apply')?>',
                type:'post',
				async:false,
                data:{productid:$(this).attr("data-id")},
                dataType:'json',
                success:function(json){
					if(json.code=='0000'){
						if(json.result.certification==1){
							layer.confirm(json.msg,{btn:["取消","前往认证"],title:false,closeBtn:false},function(){layer.closeAll();},function(){location.href="<?= yii\helpers\Url::toRoute('/certification/index')?>"},function(){location.reload()});
						}else{
							layer.msg(json.msg,{time:2000,title:false,closeBtn:false},function(){location.reload()});
						}
						
					}else{
						layer.msg(json.msg)
					}
                }
            })
		})
		$('.basic li span').click(function(){
            var index = $(this).parent().index();
            $(this).addClass('current').parent().siblings().children().removeClass('current')
			$(".basic_main").eq(index).show().siblings().hide()
			// console.log($(this).parents().parent().next().children().eq(1).siblings().hide())
			// $(this).parents().parent().next().children().eq(1).show().siblings().hide();
        });
		
		$('.quxiao').click(function(){
			var applyid = $(this).attr('data-applyid');
			layer.confirm("确定取消申请？",{title:false,closeBtn:false},function(){
				var index = layer.load(1, {
				   time:2000,
					shade: [0.4,'#fff'] //0.1透明度的白色背景
				});
				$.ajax({
				url:'<?= yii\helpers\Url::toRoute('/productorders/apply-cancel')?>',
				type:'post',
				data:{applyid:applyid},
				dataType:'json',
				success:function(json){
					layer.close(index)
					if(json.code == '0000'){
						layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>取消成功",{time:500},function(){window.location.reload()});
						layer.close(index);
					}else{
						layer.msg(json.msg);
						layer.close(index);
						}
					}
				})
			})
		})
		
    })	
</script> 

</body>