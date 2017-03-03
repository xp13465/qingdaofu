<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
//var_dump($data);die;
$categoryLabel = "未认证";
$nameLabel = "";
$cardnoLabel = "";
$contactLabel = "";
$mobileLabel = "";
$emailLabel = "";
$casedescLabel ="";
$addressLabel = "";
$enterprisewebsiteLabel = "";
if($data['certification']&&$data['certification']['state']==1){
	switch($data['certification']['category']){
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
$userDetail = $data['titleLabel'];
$gohtml = "";
if($data['canContacts']==true){
	$gohtml="<a class='' href='tel:{$data['mobile']}'>电话</a>";
}
?>
<?=wxHeaderWidget::widget(['title'=>$userDetail,'gohtml'=>$gohtml,'backurl'=>true,'backurl'=>true])?>
 <!---选择TA作为意向接单方--->
<section>
  <div class="show">
    <div class="show_xx"> <span style="color:#333;">基本信息</span> 
	<a href="#" style="color:orange; line-height: 50px;float:right;"><?=$categoryLabel?></a> 
	</div>
	<?php if($data['certification']&&$data['certification']['state']==1){ ?>
	<ul class="revert">
        <?php if($data['certification']){?>
				<li>
					<div class="revert_l"><span>昵称</span></div>
					<div class="revert_r"><?=$data['realname']?$data['realname']:$data['username']?></span></div>
				</li>
				<li> 
					<div class="revert_l"><span><?= $nameLabel ?></span> </div>
					<div class="revert_r"><span><?=$data['certification']['name']?></span> </div>
				</li>
				<li> 
					<div class="revert_l"><span><?= $cardnoLabel ?></span> </div>
					<div class="revert_r"><span><?=$data['certification']['cardno']?></span></div>
				</li>
			<?php if(isset($contactLabel)&&$contactLabel){ ?>
				<li> 
					<div class="revert_l"><span><?= $contactLabel ?></span></div>
					<div class="revert_r"><span><?=$data['certification']['contact']?></span></div>
				</li>
			<?php } ?>
				<li> 
					<div class="revert_l"><span><?= $mobileLabel ?></span></div> 
					<div class="revert_r"><span><?=$data['certification']['mobile']?></span> </div> 
				</li>
			<?php if(isset($addressLabel)&&$addressLabel){ ?>
				<li> 
				<div class="revert_l"><span><?= $addressLabel ?></span> </div> 
				<div class="revert_r"><span><?=$data['certification']['address']?></span></div>  
				</li>
			<?php } ?>
			<?php if(isset($enterprisewebsiteLabel)&&$enterprisewebsiteLabel){ ?>
				<li> 
					<div class="revert_l"><span><?= $enterprisewebsiteLabel ?></span> </div>
					<div class="revert_r"><span><?=$data['certification']['enterprisewebsite']?></span> </div>
				</li>
			<?php } ?>
				<li> 
					<div class="revert_l"><span><?= $emailLabel ?></span> </div>
					<div class="revert_r"><span><?=$data['certification']['email']?></span> </div>
				</li>
			<?php if(isset($casedescLabel)&&$casedescLabel){ ?>
				<li> 
					<div class="revert_l"><span><?= $casedescLabel ?></span> </div>
					<div class="revert_r"><span><?=$data['certification']['casedesc']?></span> </div>
				</li>
			<?php } ?>
		<?php }?>
    </ul>
	<?php } ?>
  </div>
  <div class="pj_a">
    <div class="cp_xinxi" style="margin-top:10px;">
      <ul>
        <a href="#">
        <li>
          <div class="cp_right"> <span style="font-size:16px;">收到的评价(<?=count($commentdata['Comments1'])?($commentdata['commentsScore']."分"):"无"?>)</span> </div>
          <div class="arrow_l arrow_t"> <!--<span  style="color:#999;">查看全部</span> <i></i> --></div>
        </li>
        </a>
      </ul>
    </div>
	<ul class="apply_num">
	<?php foreach($commentdata['Comments1'] as $key => $value): ?>
      <li style="border-bottom:1px solid #ddd;padding:0px 10px;"> 
			<span style="float:left;margin-right:10px;"><img src="<?= isset($value["userinfo"]["headimg"]['file'])?Yii::$app->params['wx'].$value["userinfo"]["headimg"]['file']:'/images/dog.png' ?>" style="width:30px;height:30px;border-radius: 50%;vertical-align: middle;"></span>
			<p><?= isset($value["userinfo"]["realname"])?$value["userinfo"]["realname"]:$value["userinfo"]["username"] ?><i style="float:right;color:#8C898A;"><?= date('Y-m-d H:i',$value['action_at'])?></i></p>
			<p class="xing">
				<?php echo \frontend\services\Func::evaluateNumber(round((($value['truth_score']+$value['assort_score']+$value['response_score'])/3)))?> 
			</p>
			<p><?= $value['memo']?></p>
			<?php if(isset($value['filesImg'])&&$value['filesImg']){ ?>
				<div class="figure">
				<?php foreach($value['filesImg'] as $v):?>
					<span>
						<img src="<?= Yii::$app->params['wx'].$v['file'] ?>" class='imageWxview' data-img = '<?= Yii::$app->params["wx"].$v['file']?>' style="height:50px;width:50px; display:inline-block;">
					</span>
				<?php endforeach; ?>
				</div>
			<?php } ?>
      </li>
	<?php endforeach; ?>
    </ul>
  </div>
</section>

