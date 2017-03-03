<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
$type=0;
$title = $type==1?"经办":'草稿';
$this->title = "我的".$title;
// $this->params['breadcrumbs'][] = $this->title;
$action = Yii::$app->controller->action->id;
// echo '<pre>';
// print_r($data);
?>
<div class="content clearfix">
<div>
<?=$this->render("/layouts/leftmenu")?>
	<div class="new-list">
		<?php
		Pjax::begin([
			'id' => 'post-grid-pjax',
		])
		?>
			<div class="right-next">
				<div class="right-topl">
				<p>我的草稿</p>
				</div>
			</div>
			<div class="draft">
				<ul>
				<?php foreach($data as $value):?>
				<li> 
					<div class="pro">
						<p><?= $value['number']?></p>
						<p class="editor"><a href="javascript:void(0);" class='detele' data-productid='<?= $value['productid']?>'>删除</a> | <a  href="javascript:void(0);" class='target' data-productid='<?= $value['productid']?>' >编辑</a><span><?= date('Y-m-d H:i',$value['create_at'])?></span></p>
					</div>
				</li>
				<?php endforeach; ?>
				</ul>
			</div>
			<div class="pages clearfix ">
				<div class="fenye" style="margin-top:30px"> 
					<span class="fenyes" style="font-size:12px;margin:0px 35px -41px;">共<?=$Preservation->pagination->totalCount?>条记录，第<?=$Preservation->pagination->page+1?>/<?=$Preservation->pagination->pageCount?>页</span>
					<?= yii\widgets\LinkPager::widget([
						'pagination' => $Preservation->pagination
					]) ?>
				</div>
			</div>
<?php Pjax::end() ?>
</div>
</div>
</div>
<script>
$(document).ready(function(){
	$(document).on("click",".target",function(){
		var productid = $(this).attr("data-productid");
		var url = "<?=Url::toRoute(["/product/create","productid"=>""])?>"+productid;
		window.open(url)
	})
	var _csrf = "<?= Yii::$app->request->csrfToken ?>";
	//ProductDelete
	$(document).on('click',".detele",function(){
		var productid = $(this).attr("data-productid");
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});	
		$.ajax({
			url:"<?= Url::toRoute(['/product/product-delete'])?>",
			type:'post',
			data:{productid:productid,_csrf:_csrf},
			dataType:'json',
			success:function(json){
				if(json.code=='0000'){
					layer.msg("删除成功",{time:500},function(){window.location.reload()});
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
