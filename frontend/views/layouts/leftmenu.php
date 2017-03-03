<?php 
use yii\helpers\Html;
use app\models\Protectright;
use yii\helpers\Url;
$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
$type = Yii::$app->request->get("type",0);
$controller = strtolower($controller);
$controllerAction =$controller.strtolower($action);
$controllerActionType =$controller.strtolower($action).strtolower($type);
$userid  = Yii::$app->user->getId();
$User = \app\models\User::getCertifications($userid,true,['id','username','realname','mobile','picture','isSetPassword']);
$msgCount = \app\models\Message::find()->where(["validflag"=>"1","isRead"=>"0","belonguid"=>Yii::$app->user->getId()])->count();

$categoryLabel = "你还没有认证";
if($User['certification']&&$User['certification']['state']==1){
	switch($User['certification']['category']){
		case 1:
			$categoryLabel='已认证个人';
			break;
		case 2:
			$categoryLabel='已认证律所';
			break;
		case 3:
			$categoryLabel='已认证公司';
			break;
	}
}



?>
<div class="content_menu">
  <div class="menu_top">
  <div class="menu_top1">
    <div class="head"><img src="<?=Yii::$app->user->identity->headimg?Yii::$app->user->identity->headimg->file:'/images/defaulthead.png'?>"></div>
    <div class="date">
    <p style="cursor:pointer" onclick="window.open('<?=Url::toRoute(['/userinfo/detail',"userid"=>Yii::$app->user->identity->id])?>')"><?=Yii::$app->user->identity->realname?:Yii::$app->user->identity->username?></p>
    <a href="<?=Url::toRoute('/userinfo/info')?>">编辑资料</a>
    </div>
  </div>
  <div class="menu_top2">
    <ul>
      <li><a title="<?=$categoryLabel?>" href="<?=Url::toRoute(['/certifications/index'])?>">&nbsp;<?=$User['certification']&&$User['certification']['state']==1?"":"<i></i>"?></a></li>
      <li><a title="修改绑定手机" target="_blank" href="<?=Url::toRoute('/userinfo/changemobile')?>">&nbsp;</a></li>
      <li><a title="安全中心" href="<?=Url::toRoute('/user/security')?>">&nbsp;</a></li>
    </ul>
  </div>
  
  </div>
  <div class="menu_center">
    <ul>
		<li class="border-t">
			<i class="left"></i>
			<a href="<?=Url::toRoute(["product/release-list","type"=>"0"])?>" class="<?=$type!=1&&in_array($action,["release-list"])?"current":""?>">我的发布<b class="fo1"></b></a>
			<i class="right"></i>
		</li>
		<li>
			<a href="<?=Url::toRoute(["productorders/index","type"=>"0"])?>" class="<?=$type!=1&&in_array($controller,["productorders"])?"current":""?>">我的接单<b class="fo2"></b></a>
		</li>
		<li class="border-t">
			<i class="left"></i>
			<a href="<?=Url::toRoute(["product/preservation"])?>" class="<?=$type!=1&&in_array($action,["preservation"])?"current":""?>">我的草稿<b class="fo3"></b></a>
			<i class="right"></i>
		</li>
		<li>
			<a href="<?=Url::toRoute(["/product/collect-list"])?>" class="<?=$type!=1&&in_array($action,["collect-list"])?"current":""?>">我的收藏<b class="fo4"></b></a>
		</li>
		<li class="border-t">
			<i class="left"></i>
			<a href="<?=Url::toRoute(["productorders/index","type"=>"1"])?>" class="<?=$type==1&&in_array($controller,["productorders"])?"current":""?>">经办事项<b class="fo5"></b></a>
			<i class="right"></i>
		</li>
		<li>
			<a href="<?=Url::toRoute(["contacts/index"])?>">我的通讯录<b class="fo6"></b></a>
		</li>
		
		<li class="border-t">
			<i class="left"></i>
			<a class="<?=$type!=1&&in_array($controller,["policy"])?"current":""?>" href="<?=Url::toRoute(["policy/index"])?>">我的保函<b class="fo7"></b></a>
			<i class="right"></i>
		</li>
		<li>
			<a class="<?=$type!=1&&in_array($controller,["protectright"])?"current":""?>" href="<?=Url::toRoute(["protectright/index"])?>">我的保全<b class="fo8"></b></a>
		</li>
		<li >
			<i class="left"></i>
			<a  class="<?=$type!=1&&in_array($controller,["fangjia"])?"current":""?>"  href="<?=Url::toRoute(["fangjia/index"])?>">房产评估<b class="fo9"></b></a>
			<i class="right"></i>
		</li>
		<li class="border-t">
			<i class="left"></i>
			<a  class="<?=$type!=1&&in_array($controller,["message"])?"current":""?>"  href="<?=Url::toRoute(["/message"])?>">消息<?=$msgCount?"<i>{$msgCount}</i>":""?><b class="fo10"></b></a>
			<i class="right"></i>
		</li>
    </ul>
  </div>
</div>