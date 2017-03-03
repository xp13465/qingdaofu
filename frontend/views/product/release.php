<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
 
$title = $type==1?"经办":'发布';
$this->title = "我的".$title;
// $this->params['breadcrumbs'][] = $this->title;
$action = Yii::$app->controller->action->id;
// var_dump($data);die;
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
             <li class='<?= $action == "release-list"?"current":""?>' ><a href="<?=Url::toRoute(["/product/release-list","type"=>$type])?>">所有订单</a></li>
             <li class='<?= $action == "list-processing"?"current":""?>'><a href="<?=Url::toRoute(["/product/list-processing","type"=>$type])?>">进行中</a></li>
             <li class='<?= $action == "list-completed"?"current":""?>'><a href="<?=Url::toRoute(["/product/list-completed","type"=>$type])?>">已完成</a></li>
             <li class='<?= $action == "list-aborted"?"current":""?>'><a href="<?=Url::toRoute(["/product/list-aborted","type"=>$type])?>">已终止</a></li>
             <!--<li><a href="#">接单回收站</a></li>-->
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
              <th>发布时间</th>
              <th>状态</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <?php 
		if($data){
		foreach($data as $value): ?>
			<tr>
				<td class='target' data-productid="<?=$value['productid']?>" ><?=$value['number']?></td>
				<td class='target' data-productid="<?=$value['productid']?>" ><p class="omit" style="width:130px;" title="<?=$value['categoryLabel']?>"><?=$value['categoryLabel']?></p></td>
				<td class='target' data-productid="<?=$value['productid']?>" ><p class="omit" style="width:130px;" title="<?=$value['entrustLabel']?>"><?=$value['entrustLabel']?></p></td>
				<td class='target black' data-productid="<?=$value['productid']?>" ><?=$value['accountLabel']?><i>万</i></td>
				<td class='target red' data-productid="<?=$value['productid']?>" ><?= isset($value['type'])=='1'?intval($value['typenumLabel']):intval($value['typenum'])?><i><?=$value['typeLabel']?></i></td>
				<td class='target' data-productid="<?=$value['productid']?>" ><?=$value['overdue']?>个月</td>
				<?php /*<td class='target' data-productid="<?=$value['productid']?>" ><p class="omit" style="width:150px;" title="<?=$value['entrustLabel']?>"><?=$value['addressLabel']?></p></td>*/?>
				<td class='target' data-productid="<?=$value['productid']?>" ><p class="omit" style="width:150px;" title="<?=date("Y-m-d H:i:s",$value['create_at'])?>"><?=date("Y-m-d H:i:s",$value['create_at'])?></p></td>
				<td class='target' data-productid="<?=$value['productid']?>" ><?=$value['statusLabel']?></td>
			</tr>
        <?php endforeach;
		}else{ ?>
			<tr>
				<td colspan="8">无</td>
			</tr>
		<?php }?>
            </tr>
			</tbody>
          </table>
        </div>
		<div class="pages clearfix ">
			<div class="fenye" style="margin-top:30px"> 
				<span class="fenyes" style="font-size:12px;margin:0px 35px -41px;">共<?=$productList->pagination->totalCount?>条记录，第<?=$productList->pagination->page+1?>/<?=$productList->pagination->pageCount?>页</span>
				<?= yii\widgets\LinkPager::widget([
					'pagination' => $productList->pagination
				]) ?>
			</div>
		</div>
		<?php Pjax::end() ?>
	</div>
</div>
<script>
$(document).ready(function(){
	$(document).on("click",".target",function(){
		var productid = $(this).attr("data-productid");
		var url = "<?=Url::toRoute(["/product/product-deta","productid"=>""])?>"+productid;
		window.open(url)
	})
})
</script>