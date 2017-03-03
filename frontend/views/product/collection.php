<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
// echo '<pre>';
// print_r($data);die;
$title = $type==1?"经办":'收藏';
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
		<div class="right-next">
				<div class="right-topl">
				<p>我的收藏</p>
				</div>
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
              <th>合同履行地</th>
              <th>状态</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <?php 
		if($data){
		foreach($data as $value): ?>
			<tr class='targets' data-productid="<?= $value['product']['productid'] ?>" >
				<td class='target' data-productid="<?=$value['product']['productid']?>" ><?=$value['product']['number']?></td>
				<td class='target' data-productid="<?=$value['product']['productid']?>" ><p class="omit" style="width:130px;" title="<?=$value['product']['categoryLabel']?>"><?=$value['product']['categoryLabel']?></p></td>
				<td class='target' data-productid="<?=$value['product']['productid']?>" ><p class="omit" style="width:130px;" title="<?=$value['product']['entrustLabel']?>"><?=$value['product']['entrustLabel']?></p></td>
				<td class='target black' data-productid="<?=$value['product']['productid']?>" ><?=$value['product']['accountLabel']?><i>万</i></td>
				<td class='target red' data-productid="<?=$value['product']['productid']?>" ><?= isset($value['product']['type'])=='1'?intval($value['product']['typenumLabel']):intval($value['product']['typenum'])?><i><?=$value['product']['typeLabel']?></i></td>
				<td class='target' data-productid="<?=$value['product']['productid']?>" ><?=$value['product']['overdue']?>个月</td>
				<td class='target' data-productid="<?=$value['product']['productid']?>" ><p class="omit" style="width:150px;" title="<?=$value['product']['entrustLabel']?>"><?=$value['product']['addressLabel']?></p></td>
				<td class='target' data-productid="<?=$value['product']['productid']?>" ><a href="javascript:void(0);" data-productid="<?=$value['product']['productid']?>" class='detele'>取消收藏</a></td>
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
				<span class="fenyes" style="font-size:12px;margin:0px 35px -41px;">共<?=$collectList->pagination->totalCount?>条记录，第<?=$collectList->pagination->page+1?>/<?=$collectList->pagination->pageCount?>页</span>
				<?= yii\widgets\LinkPager::widget([
					'pagination' => $collectList->pagination
				]) ?>
			</div>
		</div>
		<?php Pjax::end() ?>
	</div>
</div>
<script>
$(document).ready(function(){
	$(document).on("click",".targets td",function(){
		if($(this).index()==7)return detele;
		var productid = $(this).attr("data-productid");
		var url = "<?=Url::toRoute(["/product/detail","productid"=>""])?>"+productid;
		window.open(url)
	})
	var _csrf = "<?= Yii::$app->request->csrfToken ?>";
	//ProductDelete
	var detele = $(document).on('click',".detele",function(){
		var productid = $(this).attr("data-productid");
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});	
		$.ajax({
			url:"<?= Url::toRoute(['/product/collect-cancel'])?>",
			type:'post',
			data:{productid:productid,_csrf:_csrf},
			dataType:'json',
			success:function(json){
				if(json.code=='0000'){
					layer.msg("取消成功",{time:500},function(){window.location.reload()});
					layer.close(index);		
				}else{
					layer.msg(json.msg);
					layer.close(index);
				}
			}
		})
	})
})
</script>