<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
 // var_dump($data);
?>
<style>
.layui-layer-btn{padding: 10px 10px 10px;pointer-events: auto;border-top: 1px solid #ddd;}
.layui-layer-btn a {
    height: 35px;
    line-height: 35px;
    margin: 0 6px;
    width: 120px;
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
<?=wxHeaderWidget::widget(['title'=>'终止详情','gohtml'=>"<a class='' href='tel:4008557022'>平台介入</a>",'backurl'=>true,'reload'=>false])?>
<div class="xqing" style="margin-top:12px;">
  <div class="title1" style="width:100%;height:auto;background:#fff;padding-left:20px;">
    <p class="article">申请事项：<?=$dataLabel?></p>
    <p class="article">申请时间：<?=date("Y-m-d H:i",$data['create_at'])?></p>
    <p class="article">申请终止原因：<?=$data['applymemo']?></p>
	<?php if($data['status']!=0){?>
	<p class="article"><?=$data['status']==10?"否决终止":"同意终止"?>原因：<?=$data['resultmemo']?></p>
	<?php }?>
	<?php foreach($data['filesImg'] as $filesImg){?>
    <span class="pig"><img src="<?=Yii::$app->params['wx'].$filesImg['file']?>"></span>
	<?php }?>

	</div>
</div>
<?php if($data['status']==0&&$accessTerminationAUTH){?>
<footer>
  <div class="bottom" style="height:120px;"> 
  <a class="btn_audit" data-attr='agree' href="javascript:void(0)" style="background:#10a1ec;">同意终止</a> 
  <a class="btn_audit" data-attr='veto' href="javascript:void(0)" style="background:#fff;color:#666;border:1px solid #ddd;">拒绝终止</a> 
  
  </div>
</footer>
<?php }?>

<script>
$(document).ready(function(){
	var terminationid = '<?=$data['terminationid']?>';
	$(".btn_audit").click(function(){
		var attr =$(this).attr("data-attr");
			layer.confirm("<textarea id='resultmemo' name='resultmemo' style='width:100%;height:60px;resize:none'  placeholder='理由...'/></textarea>",{
			  title: '确定'+$(this).html(),
			  closeBtn: false,
			  btn: ['确认','取消'],
			  formType:0 //prompt风格，支持0-2
			}, function(){
				var resultmemo = $("#resultmemo").val();
				
				$.ajax({
					url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-termination-')?>"+attr,
					type:'post',
					data:{terminationid:terminationid,resultmemo:resultmemo},
					dataType:'json',
					success:function(json){
						if(json.code=="0000"){
							layer.msg(json.msg,{},function(){history.go(-1)})
						}else{
							layer.msg(json.msg)
						}
					}
				})
			});
	})
	
	
})
</script>