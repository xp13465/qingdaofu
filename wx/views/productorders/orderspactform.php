<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
$ordersid = yii::$app->request->get("ordersid");
?>

<?=wxHeaderWidget::widget(['title'=>'签约协议图片','gohtml'=>'','backurl'=>true,'reload'=>false])?>
<p style="font-size:16px;color:#333;margin-top:12px;text-indent:20px;">协议照片</p>
<div class="xq">
  <ul>
	<?php foreach($data["pacts"] as $pact){ ?>
	
	<li>
		<?php if($data['OrdersStatus']=='ORDERSPACT'&&$data['accessOrdersORDERCOMFIRM']){?>
			<img src="/images/close.gif" class="closebutton" style="position: absolute;z-index: 20;display: block;"> 
		<?php }?>
		<img class="imageWxview" data_bid="<?= $pact['id']?>" data-img='<?= Yii::$app->params["wx"].$pact['file'] ?>' src="<?=Yii::$app->params["wx"].$pact['file']?>" style=" display:inline-block;width:65px;height:65px;">
	</li>
	<?php }?>
	<?php if($data['OrdersStatus']=='ORDERSPACT'&&$data['accessOrdersORDERCOMFIRM']){?>
    <li class='pic'>
	<?php echo Html::hiddenInput("files",$data["pact"],['data_url'=>'']);?>
	<a href="javascript:void(0)" class="addtu" inputName='files' limit = '10' >
	<img src="/bate2.0/images/tian.png">
	</a>
	</li>
	<?php }?>
  </ul>
</div>
<?php if($data['OrdersStatus']=='ORDERSPACT'&&$data['accessOrdersORDERCOMFIRM']){?>
<footer>
  <div class="bottom" style="height:120px;"> <a class="agreed" href="javascript:void(0);" style="background:#10a1ec;">保存</a> <a class="refused" href="javascript:void(0);" style="background:#fff;color:#666;border:1px solid #ddd;">确认上传并开始处置</a> </div>
</footer>
<?php }?>
<script>

$(document).ready(function(){
	var ordersid = '<?=$ordersid?>';
	$(".agreed").click(function(){
		var files = $("input[name='files']").val();
		$.ajax({
			url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-pact-add')?>",
			type:'post',
			data:{ordersid:ordersid,files:files},
			dataType:'json',
			success:function(json){
				if(json.code=="0000"){
					
					// history.go(-1);
					layer.msg(json.msg,{time:1000},function(){location.href='<?php echo yii\helpers\Url::toRoute(['/productorders/detail',"applyid"=>Yii::$app->request->get("applyid")])?>';})
				}else{
					layer.msg(json.msg)
				}
			}
		})
	})
	$(".refused").click(function(){
		var files = $("input[name='files']").val();
		$.ajax({
			url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-pact-confirm')?>",
			type:'post',
			data:{ordersid:ordersid,files:files},
			dataType:'json',
			success:function(json){
				if(json.code=="0000"){
					layer.msg(json.msg,{time:1000},function(){location.href='<?php echo yii\helpers\Url::toRoute(['/productorders/detail',"applyid"=>Yii::$app->request->get("applyid")])?>';})
				}else{
					layer.msg(json.msg)
				}
			}
		})
	})

}); 

	 
</script>
<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<input  style='display:none' type="file" name="file" id='id_photos' value="" />
<script>	
$(document).ready(function(){
    $(document).on("click",".closebutton",function(){
                var id = $(this).parents().children('.pic').children('input').val();
                var bid = $(this).next().attr('data_bid');
                var temp='';
                var ids =id.split(',');
                for(i in ids){
                    		if(ids[i]==bid){
                    			continue;
                    		}
                    		temp+=temp?","+ids[i]:ids[i];
                    	}
               	$(this).parents().children('.pic').children('input').val(temp)
            	$(this).parent().remove();
    });
		
	//照片异步上传
	$(".addtu").click(function(){
		var limit = $(this).attr('limit')?$(this).attr('limit'):5;
		var inputName = $(this).attr('inputName')?$(this).attr('inputName'):'';
		if(!inputName)return false;
		$("#id_photos").attr({"inputName":inputName,"limit":limit}).click();
	})
	$(document).on("change",'#id_photos',function(){ //此处用了change事件，当选择好图片打开，关闭窗口时触发此事件
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});
		var inputName = $(this).attr('inputName');
		var limit = $(this).attr('limit')?$(this).attr('limit'):5;
		var aa = $("input[name='" + inputName + "']").val();
		if(limit&&aa.split(",").length>=limit){
			layer.close(index)
			layer.alert("最多上传"+limit+"张图片");
			return false;
		} 
		if(!inputName)return false;
		$.ajaxFileUpload({
			url:"<?php echo yii\helpers\Url::toRoute(['/common/upload','filetype'=>1,'_csrf'=>Yii::$app->getRequest()->getCsrfToken()])?>",
			type: "POST",
			secureuri: false,
			fileElementId: 'id_photos',
			data: {},
			textareas:{},
			dataType: "json",
			success: function (data) {
				// console.log(data);
				layer.close(index)
				 var wx = "<?php echo Yii::$app->params['wx'];?>";
				if(data.code == '0000'&&data.result.fileid){
					var aa = $("input[name='" + inputName + "']").val();
					if(limit&&aa.split(",").length>=limit){
						layer.alert("最多上传"+limit+"张图片");
						return false;
					}  
					$("input[name='" + inputName + "']").val((aa ? (aa + ",") : '')+data.result.fileid).trigger("change"); 
					var div ='<li><img src = "/images/close.gif" class="closebutton" onclick="javascript:void(0)" style="position: absolute;z-index: 20;display: block;"> <img onclick="javascript:void(0)" class="imageWxview" data-img='+wx+data.result.url+' data_bid='+data.result.fileid+' src='+wx+data.result.url+' style=" display:inline-block;width:65px;height:65px;"/></li>';
					
					var spandiv = $("input[name='" + inputName + "']").parent("li");
					if(spandiv.index()==0){
						spandiv.parent().prepend(div)
					}else{
						spandiv.prev().after(div);
					}
				}else if(data.msg){
					
					layer.alert(""+data.msg)
				}
			},
			error:function(){
				layer.close(index)
			}
		}); 
	});
});
</script>	