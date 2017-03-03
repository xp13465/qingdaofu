<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$labelImg=[
	"1"=>"/bate2.0/images/ling.png",
	"10"=>"/bate2.0/images/bao.png",
	"20"=>"/bate2.0/images/csh.png",
	"30"=>"/bate2.0/images/cja.png",
	"40"=>"/bate2.0/images/defaulthead.png",
	"50"=>"/bate2.0/images/defaulthead.png",
];
$labelUrl=[
	"1"=>["/message/system"],
	"10"=>["/preservation/audit", 'id'=>""],
	"20"=>["/policy/baohan", 'id'=>""],
	"30"=>["/property/view", 'id'=>""],
	"40"=>["/wrelease/details", 'productid'=>""],
	"50"=>["/productorders/detail", 'applyid'=>""],
];
//var_dump($data);
$msgCount = \app\models\Message::find()->where(["validflag"=>"1","isRead"=>"0","belonguid"=>Yii::$app->user->getId()])->count();
?>
<div class="content clearfix">
	<?=$this->render("/layouts/leftmenu")?>   
	<?php
	 Pjax::begin([
		 'id' => 'post-grid-pjax',
	 ])
	?>
    <div class="new-list">
		<div class="title">
			<ul>
			<li><a href="<?=Url::toRoute(["message/group-list"]);?>" class="system-l ">产品消息&nbsp;<i><?=$msgCount-$systemCount?:""?></i></a></li>
			<li  class="active"><a href="<?=Url::toRoute(["message/system-list"]);?>" class="system-r">系统消息&nbsp;<i><?=$systemCount?:""?></i></a></li>
			</ul>
		</div>
		<div class="clear"></div>
		<div class="person">
			<ul>
			<?php foreach($data as $message):?>
				<li> 
				<div class="infol">
					<p class='<?=$message['isRead']?"read":"unread"?>'>系统消息--<?=$message['title']?></p>
					<p class="editor"><span><?=$message['relatitle']?><?=$message['content']?></span><span style="margin-left:20px;"><?=date("Y/m/d H:i",$message['create_time'])?></span></p>
				</div>
				<div class="infor">
					<!--<p><span class="name">张三丰</span><span class="pic"><img src="images/4.png"></span></p>-->
				</div>
				<div class="clear"></div>
			</li>
			<?php endforeach;?>
			</ul>
		</div>
		<div class="pages clearfix ">
			<div class="fenye" style="margin-top:30px"> 
			<span class="fenyes" style="font-size:12px;margin:0px 35px -41px;">共<?=$provider->pagination->totalCount?>条记录，第<?=$provider->pagination->page+1?>/<?=$provider->pagination->pageCount?>页</span>
			 <?= yii\widgets\LinkPager::widget([
				'pagination' => $provider->pagination
			]) ?>
			</div>
		</div>
    </div>
	<?php Pjax::end() ?>
  </div>
