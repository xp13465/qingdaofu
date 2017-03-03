<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
$this->title ="消息";
// $this->params['breadcrumbs'][] = $this->title;
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
	"10"=>["/protectright/index", 'id'=>""],
	"20"=>["/policy/index", 'id'=>""],
	"30"=>["/property/view", 'id'=>""],
	"40"=>["/product/product-deta", 'productid'=>""],
	"50"=>["/productorders/detail", 'applyid'=>""],
];
// var_dump($data);
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
			<li class="active"><a href="<?=Url::toRoute(["message/group-list"]);?>" class="system-l">产品消息&nbsp;<i><?=$msgCount-$systemCount?:""?></i></a></li>
			<li><a href="<?=Url::toRoute(["message/system-list"]);?>" class="system-r">系统消息&nbsp;<i><?=$systemCount?:""?></i></a></li>
			</ul>
		</div>
		<div class="clear"></div>
		<div class="person">
			<ul>
				<?php foreach($data as $message):
				$urlRoute = $labelUrl[$message["relatype"]];
				foreach($urlRoute as $k=>$v){
					if(strpos($k,"id")>-1&&empty($v))$urlRoute[$k]=$message["relaid"];
				}
				$urlRoute["messageid"]=$message["id"];
				?>
				<li> 
					<div class="infol">
						<p class="<?=$message['isRead']?"unread":"read"?>"><?=$message['relatitle']?>--<?=$message['title']?>&nbsp;<?=$message['isRead']?('<i style="border-radius:60px;font-size:12px;background:#F79D25;padding:0 5px;color:#fff">'.$message['isRead'].'</i>'):""?></p>
						<p class="editor"><span><?=$message["content"]?>&nbsp;&nbsp;&nbsp;&nbsp;<a class='message-detail' data-href="<?=Url::toRoute($urlRoute)?>">点击查看</a></span><span style="margin-left:20px;"><?=$message['timeLabel']?></span></p>
					</div>
					<div class="infor">
					<div class="name"><?=$message['username']?></div><div class="pic"><img src="<?=$message['headimg']?$message['headimg']['file']:$labelImg[$message['relatype']]?>"></div>
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
<script>
$(document).ready(function(){
	$(document).on("click",'.message-detail',function(){
		var url = $(this).attr("data-href");
		if(url)window.open(url)
	})
	
})
</script>