<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use wx\widget\wxHeaderWidget;
$applyMiantan = ['20'=>'面谈中','30'=>'面谈失败','40'=>'面谈成功','50'=>'取消申请','60'=>'申请失败'];
?>
<style>
/*.layui-layer-title {text-align: center;padding: 0 35px 0 20px;}*/
.layui-layer-btn{padding: 10px 10px 10px;pointer-events: auto;border-top: 1px solid #ddd;}
.layui-layer-btn a {
    height: 35px;
    line-height: 35px;
    margin: 0 6px;
    width: 45%;
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

<?=wxHeaderWidget::widget(['title'=>'申请记录','gohtml'=>'','backurl'=>true,'reload'=>false])?>
<?php if(isset($data['state'])&&$data['state'] == '1'){ ?>
<div class="cp_xinxi" style="margin-top:12px;">
  <ul>
    <?php foreach($data['apply'] as $key=>$value):?>
		<li style="line-height:30px;">
		<a href="javascript:void(0)">
		<div class="cp_right" style="width:70%;" data-uid='<?= $value['create_by'] ?>' data-productid='<?= $value['productid'] ?>'> <span style="font-size:16px;">接单方:<?= $value['createuser']['realname']?$value['createuser']['realname']:$value['createuser']['username']?></span>
			<p style="font-size:14px;color:#999;line-height:25px;"><?= date('Y-m-d H:i',$value['create_at']);?></p>
			<?php if($value['status'] == '30'){ ?>
				<!--<p style="font-size:14px;color:#999;line-height:25px;">备注：某某原因</p>-->
			<?php } ?>
		</div>
		<?php if($value['status'] == '10'){ ?>
			<div class='but' style="width:30%;float:right;">
				<button class="button" data-productid = '<?= $value['productid'] ?>' data-id='<?= $value['applyid'] ?>'>选择他</button>
			</div>
		<?php }else if(in_array($value['status'],['20','30','40','50','60'])){ ?> 
				<div style="width:30%;float:right;">
				<button  class="button01"><?= $applyMiantan[$value['status']]; ?></button>
			</div>
		<?php } ?>
		</a>
		</li>
	
	<?php endforeach; ?>
  </ul>
</div>
<p style="font-size:12px;color: #AAA7A7;margin:10px 10px;line-height:20px;">选择申请记录中其中一个作为意向接单方，如果选择后面谈不符合可以重新选择其他申请方作为意向接单方</p>
</body>
<?php }else if(isset($data['state'])&&$data['state'] == '0'){?>
        
		<script>
			$(document).ready(function(){
				layer.msg("您的认证申请已提交，请耐心等待客服的审核。",{time:3000,shade: [0.2,'#30393d']},function(){history.go(-1)})
				
			})
		</script>
	<?php }else if(isset($data['state'])&&$data['state'] == '2'){ ?>
		<script>
			$(document).ready(function(){
				// layer.msg("您的认证申请审核失败，请重新去提交申请。",{time:3000,shade: [0.2,'#30393d']},function(){location.href='<?= yii\helpers\Url::toRoute('/certification/index')?>';})
				layer.confirm("您的认证申请审核失败，请重新去提交申请。",{btn:["取消","前往认证"],title:false,closeBtn:false},function(){layer.closeAll()},function(){location.href="<?= yii\helpers\Url::toRoute('/certification/index')?>"}
				);
			})
		</script>
	<?php }else{ ?>
		<script>
			$(document).ready(function(){
				 //layer.msg("您还为认证请先去认证。",{time:3000,shade: [0.2,'#30393d']},function(){location.href='<?= yii\helpers\Url::toRoute('/certification/index')?>';})
				layer.confirm("您还未认证请先去认证。",{btn:["取消","前往认证"],title:false,closeBtn:false},function(){layer.closeAll()},function(){location.href="<?= yii\helpers\Url::toRoute('/certification/index')?>"});
			})
		</script>
	<?php } ?>
<script>
$(document).ready(function(){
         $('.but').click(function(){
			 var productid = $(this).children().attr('data-productid');
			 var id = $(this).children().attr('data-id');
			 window.location.href = '/wrelease/details?applyid='+id+'&productid='+productid;
			 
		 })
		 
		 $('.cp_right').click(function(){
			 var userid = $(this).attr('data-uid');
			 var productid = $(this).attr('data-productid');
			 window.location.href='<?= yii\helpers\Url::toRoute('/user/detail');?>'+'?userid='+userid+'&productid='+productid;
		 })
	})

</script>