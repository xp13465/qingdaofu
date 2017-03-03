<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
	// echo '<pre>';
 // print_r($data['accessOrdersADDCOMMENT']);die;

 $backurl = yii\helpers\Url::toRoute(['/productorders/detail',"applyid"=>$data['orders']['applyid']]);
?>
<style>

.apply_num li{border-bottom: 1px solid #ddd;padding:0 10px;}
.apply_num li:last-child{border-bottom: 0px solid #ddd;}

</style>
<?=wxHeaderWidget::widget(['title'=>'评价列表','gohtml'=>'','backurl'=>$backurl,'reload'=>false])?>
  <div class="pj_a">
    <div class="cp_xinxi" style="margin-top:10px;">
      <ul>
        <a href="#">
        <li>
          <div class="cp_right"> <span style="font-size:16px;">评价</span> </div>
        </li>
        </a>
      </ul>
    </div>
    <ul class="apply_num">
	<?php foreach($data['Comments1'] as $key => $value): ?>
      <li> 
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
						<img src="<?= Yii::$app->params['wx'].$v['file'] ?>" style="height:50px;width:50px; display:inline-block;">
					</span>
				<?php endforeach; ?>
				</div>
			<?php } ?>
      </li>
	<?php endforeach; ?>
    </ul>
  </div>
  <?php if($data['Comments2']){?>
  <div class="pj_a">
    <div class="cp_xinxi" style="margin-top:10px;">
      <ul>
        <a href="#">
        <li>
          <div class="cp_right"> <span style="font-size:16px;">追加</span> </div>
        </li>
        </a>
      </ul>
    </div>
    <ul class="apply_num">
      <?php foreach($data['Comments2'] as $key => $value): ?>
      <li> <span style="float:left;margin-right:10px;"><img src="<?= isset($value["userinfo"]["headimg"]['file'])?Yii::$app->params['wx'].$value["userinfo"]["headimg"]['file']:'/images/dog.png' ?>" style="width:30px;height:30px;border-radius: 50%;vertical-align: middle;"></span>
        <p><?= isset($value["userinfo"]["realname"])?$value["userinfo"]["realname"]:$value["userinfo"]["username"] ?><i style="float:right;color:#8C898A;"><?= date('Y-m-d H:i',$value['action_at'])?></i></p>
        <p><?= $value['memo']?></p>
		<?php if(isset($value['filesImg'])&&$value['filesImg']){ ?>
			<div class="figure">
				<?php foreach($value['filesImg'] as $k => $v):?>
					<span>
						<img src="<?= Yii::$app->params['wx'].$v['file'] ?>" style="height:50px;width:50px; display:inline-block;">
					</span>
				<?php endforeach; ?>
			</div>
		<?php } ?>
      </li>
	  <?php endforeach; ?>
    </ul>
  </div>
  <?php }?>
<?php if($data['accessOrdersADDCOMMENT']){?>
<footer>
  <div class="case">
    <a href="<?= yii\helpers\Url::toRoute(['/productorders/comment-form','ordersid'=>Yii::$app->request->get('ordersid'),'type'=>2])?>" class="agree">追加评价</a> 
  </div>
</footer>
<?php }?>
<script>

$(document).ready(function(){
	/*var ordersid = '30';
	$(".agree").click(function(){
		$.ajax({
			url:"<?php echo yii\helpers\Url::toRoute('/productorders/comment-add')?>",
			type:'post',
			data:{ordersid:ordersid},
			dataType:'json',
			success:function(json){
				if(json.code=="0000"){
					layer.msg(json.msg,{},function(){location.reload()})
				}else{
					layer.msg(json.msg)
				}
			}
		})
	})*/
	
 
}); 

	 
</script>