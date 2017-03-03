<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
$this->registerJsFile('@web/js/fastclick.js',['depends'=>['wx\assets\NewAppAsset']]);
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
?>
<div class="cm-header">
	<code>消息</code>
	<span class="gohtml" ></span>
</div>
<section>
<p id="show"></p>
<div id="wrapper" >
  <div id="scroller" >
	<div id="pullDown" style="display:none">
				<span class="pullDownIcon"></span><span class="pullDownLabel"></span>
	</div>
	<div class="xi-a lx" >
		<ul>
			<li class="xiaoxi_b"> 
				<a href="<?=Url::toRoute($labelUrl[1])?>">
				<span class="mynews"><?=$systemCount?:""?></span> 
				<img src="<?=$labelImg[1]?>">
				<p> <span style="float:none;">系统消息</span></p>
				<p> <span class="wans">系统消息通知</span></p>
				<span class="xi-icon"></span> 
				</a>
			</li>
		</ul>
		<ul  <?=$curCount?'style="border-top: 1px solid #ddd;"':''?> id="thelist" class='thelist' data-catr="<?=$curCount?>" data-url="<?php echo yii\helpers\Url::toRoute(['/message/index'])?>">
		<?php foreach($data as $message):
		$urlRoute = $labelUrl[$message["relatype"]];
		foreach($urlRoute as $k=>$v){
			if(strpos($k,"id")>-1&&empty($v))$urlRoute[$k]=$message["relaid"];
		}
		$urlRoute["messageid"]=$message["id"];
		?>
			<li class="xiaoxi_b chayue"> 
				<a href="<?=Url::toRoute($urlRoute)?>">
				<span class="mynews"><?=$message["isRead"]?:""?></span> 
				<img src="<?=$message['headimg']?Yii::$app->params['wx'].$message['headimg']['file']:$labelImg[$message['relatype']]?>">
				<p> <span style="float:none;"><?=$message['relatitle']?>&nbsp;</span> <span class="lx-time" style="color:#ff9821;"><?=$message['title']?></span> </p>
				<p> <span class="wans"><?=$message["content"]?></span> <span class="lx-time"><?=$message['timeLabel']?></span> </p>
				</a>
			</li>
		<?php endforeach;?>
		</ul>
	</div>
	<div id="pullUp" style="display:none;" >
			<span class="pullUpIcon"></span><span class="pullUpLabel"></span>
	</div>
	</div>
  </div>
</section>
<?php echo  \wx\widget\wxFooterWidget::widget()?>
<script>
    $(document).ready(function(){
			datalistClass = "#scroller ul.thelist li";
            // $('.xiaoxi_b').click(function(){
                // window.location  = "<?php echo \yii\helpers\Url::toRoute('/message/categorylist')?>?type="+$(this).attr('data-content-id');
            // });
        }
    );
</script>
