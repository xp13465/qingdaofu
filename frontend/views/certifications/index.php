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
if(isset($data)&&in_array($data['state'],['0','1'])){
	switch($data['category']){
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
?>
<?php if(isset($data)&&in_array($data['state'],['0','1'])){ ?>
<div class="content_right1">
         <div class="right-t">
            <div class="right-lt"><img src="/bate2.0/images/h555.png"></div>
            <div class="right-rt">
                <div class="ret">
                    <span style = "margin-left:0px;" ><?= isset($data['state'])&&$data['state']==0?'认证审核中':$categoryLabel ?><i style="color:#fff;"> |</i> </span>
                    <span>清道夫债管家</span>
                    <p class="wan"><?= isset($data['state'])&&$data['state']==0?'客服将在一个工作日，审核完成':'已认证成功，请快去接单吧' ?></p>
                </div>
            </div>
         </div>
         <div class="right-m">
          <p class="r-header">认证信息</p>
           <p><?= $nameLabel.'：'.$data['name'] ?></p>
          <p><?= $cardnoLabel.'：'.$data['cardno'] ?></p>
		  <?php if(in_array($data['category'],['2','3'])){echo '<p>'.$contactLabel.'：'.$data['contact'] .'</p>';}?>
          <p><?= $mobileLabel.'：'.$data['mobile'] ?></p>
          <?php if(isset($data['email'])&&$data['email']!=="*"){echo '<p>'.$emailLabel.'：'.$data['email'] .'</p>';}?>
		  <?php if(in_array($data['category'],['3'])&&$data['address']!=="*"){echo '<p>'.$addressLabel.'：'.$data['address'] .'</p>';}?>
		  <?php if(in_array($data['category'],['3'])&&isset($data['enterprisewebsite'])&&$data['enterprisewebsite']){echo '<p>'.$enterprisewebsiteLabel.'：'.$data['enterprisewebsite'] .'</p>';}?>
		  <?php if(in_array($data['category'],['2','3'])&&$data['casedesc']){echo '<p>'.$casedescLabel.'：'.$data['casedesc'] .'</p>';}?>
		  
         </div>
</div>
<?php }else if(isset($data)&&$data['state'] == 2){ ?>
<div class="content_right1">
         <div class="right-t">
            <div class="right-lt"><img src="/bate2.0/images/h555.png"></div>
            <div class="right-rt">
                <div class="ret">
                    <span style="margin-left:0px;">认证失败<i style="color:#fff;"> |</i> </span>
                    <span>清道夫债管家</span>
                    <p class="wan"><a href="<?= Url::toRoute(['/certifications/edit','category'=>$data['category'],'uid'=>$data['uid']])?>">重新认证</a></p>
                </div>
            </div>
         </div>
		  <div class="right-m">
		  <p class="r-header">失败原因</p>
		  <p><?= isset($data['resultmemo'])?nl2br($data['resultmemo']):''; ?></p>
		  </div> 
</div>
<?php } ?>