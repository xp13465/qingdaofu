<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

// var_dump(\frontend\services\Func::permutations([1,2,3,4]));
$title = $type==1?"经办":'接单';
$this->title = "我的".$title;
// $this->params['breadcrumbs'][] = $this->title;
$action = Yii::$app->controller->action->id;

?>

<div class="content clearfix">
<?=$this->render("/layouts/leftmenu")?>
  <div class="new-list">
	<?php
	 Pjax::begin([
		 'id' => 'post-grid-pjax',
	 ])
	?>
    <div class="fa01">
		<ul>
            <li class='<?=$action=="index"?"current":""?>'><a href="<?=Url::toRoute(["productorders/index","type"=>$type])?>" >所有<?=$title?></a></li>
            <li class='<?=$action=="list-processing"?"current":""?>'><a href="<?=Url::toRoute(["productorders/list-processing","type"=>$type])?>">进行中</a></li>
            <li class='<?=$action=="list-completed"?"current":""?>'><a href="<?=Url::toRoute(["productorders/list-completed","type"=>$type])?>">已完成</a></li>
            <li class='<?=$action=="list-aborted"?"current":""?>'><a href="<?=Url::toRoute(["productorders/list-aborted","type"=>$type])?>">已终止</a></li>
            <!--<li class='recy'><a href="<?=Url::toRoute(["productorders/recy","type"=>$type])?>"><?=$title?>回收站</a></li>-->
        </ul>
    </div>
    <div class="list">
      <table>
		<colgroup>
			<col width='130px'/>
			<col width='130px'/>
			<col width='130px'/>
			<col width='100px' />
			<col width='100px' />
			<col width='100px' />
			<col width='150px' />
			<col width='130px' />
		</colgroup>
        <thead>
		<tr>
			<th>产品编号</th>
			<th>债权类型</th>
			<th>委托事项</th>
			<th>委托金额</th>
			<th>委托费用</th>
			<th>违约日期</th>
			<?php /*<th>合同履行地</th>*/ ?>
			<th>申请时间</th>
			<th>状态
				<?php /*<div class="state">
				  <a class="btn-select" id="btn_select">
					  <span class="cur-select">状态</span>
					  <select name="status" onchange="changestatus(this.value)">
						<option value="0" selected="selected">全部产品</option>
						<option value="1">发布中</option>
						<option value="2">面谈中</option>
						<option value="3">处理中</option>
						<option value="4">已终止</option>
						<option value="5">已结案</option>
						</select>
				  </a>
				</div>*/ ?>
			</th>
		</tr>
        </thead>
		<?php 
		if($curCount){
		foreach($data as $value): ?>
			<tr>
				<td class='target' data-productid="<?=$value['product']['productid']?>" data-applyid="<?=$value['applyid']?>" ><?=$value['product']['number']?></td>
				<td class='target' data-productid="<?=$value['product']['productid']?>" data-applyid="<?=$value['applyid']?>" ><p class="omit" style="width:130px;" title="<?=$value['product']['categoryLabel']?>"><?=$value['product']['categoryLabel']?></p></td>
				<td class='target' data-productid="<?=$value['product']['productid']?>" data-applyid="<?=$value['applyid']?>" ><p class="omit" style="width:130px;" title="<?=$value['product']['entrustLabel']?>"><?=$value['product']['entrustLabel']?></p></td>
				<td class='target black' data-productid="<?=$value['product']['productid']?>" data-applyid="<?=$value['applyid']?>" ><?=$value['product']['accountLabel']?><i>万</i></td>
				<td class='target red' data-productid="<?=$value['product']['productid']?>" data-applyid="<?=$value['applyid']?>" ><?=$value['product']['typenumLabel']?><i><?=$value['product']['typeLabel']?></i></td>
				<td class='target' data-productid="<?=$value['product']['productid']?>" data-applyid="<?=$value['applyid']?>" ><?=$value['product']['overdue']?>个月</td>
				<?php /*<td class='target' data-productid="<?=$value['product']['productid']?>" data-applyid="<?=$value['applyid']?>" ><p class="omit" style="width:150px;" title="<?=$value['product']['entrustLabel']?>"><?=$value['product']['addressLabel']?></p></td>*/?>
				<td class='target' data-productid="<?=$value['product']['productid']?>" data-applyid="<?=$value['applyid']?>"  ><p class="omit" style="width:150px;" title="<?=date("Y-m-d H:i:s",$value['create_at'])?>"><?=date("Y-m-d H:i:s",$value['create_at'])?></p></td>
				<td class='target' data-productid="<?=$value['product']['productid']?>" data-applyid="<?=$value['applyid']?>" ><?=$value['statusLabel']?></td>
			</tr>
        <?php endforeach;
		}else{ ?>
			<tr>
				<td colspan="8">无</td>
			</tr>
		<?php }?>
      </table>
    </div>
	<div class="pages clearfix ">
		<div class="fenye" style="margin-top:30px"> 
		<span class="fenyes" style="font-size:12px;margin:0px 35px -41px;">共<?=$provider->pagination->totalCount?>条记录，第<?=$provider->pagination->page+1?>/<?=$provider->pagination->pageCount?>页</span>
		 <?= yii\widgets\LinkPager::widget([
			'pagination' => $provider->pagination
		]) ?>
		</div>
	</div>
	<?php Pjax::end() ?>
  </div>
</div>
<script>
$(document).ready(function(){
	$(document).on("click",".target",function(){
		var applyid = $(this).attr("data-applyid");
		var url = "<?=Url::toRoute(["/productorders/detail","applyid"=>""])?>"+applyid;
		window.open(url)
	})
})
</script>