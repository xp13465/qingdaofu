<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
$category = ['1'=>'进行中','2'=>'已完成'];
 
?>
<style>
.closebutton{position: absolute;z-index: 20;display: block;}
</style>
<?=wxHeaderWidget::widget(['title'=>'添加进度','gohtml'=>'<a href="javascript:void(0);" data-orders="'.Yii::$app->request->get('ordersid').'"class="agree">保存</a>','backurl'=>true,'reload'=>false])?>

<div class="xq">
 <textarea type="text" name="memo" id="memo" placeholder="请填写进度详情" style="width:100%;height:60px;padding-left:10px;rezise:none"></textarea>
  <ul>  
   <li class='pic'>
	<?php echo Html::hiddenInput("files",'',['data_url'=>'']);?>
	<a href="javascript:void(0)" class="addtu" inputName='files' limit = '10' >
	<img src="/bate2.0/images/tian.png">
	</a>
	</li> 
  </ul>
</div>
<form name="creditor" id="creditor" style="margin-top:12px;">
  <ul class="infor">
    <li style="border-bottom:0px solid #ddd;">
      <a href="#">
      <div class="infor_l"> <span style="color:#333;">进度类型</span>
        <div class="select-area" style="width:70%;float:right;">
			<span style="color:#999;font-size:16px;width:85%;">请选择处置类型
				<select class="select-value" id="selectvalue" style="background:#fff;color:#000;-webkit-appearance:none;">
				<?php foreach($options as $key => $val):?>
				  <option <?=$val["disabled"]==1?"disabled":""?> value="<?=$val["id"]?>"><?=$val["name"]?></option>
				<?php endforeach; ?>
				</select>		
			</span> <i></i>
		</div>
      </div>
      </a>
    </li>
  </ul>
</form>
 
<script>

$(document).ready(function(){
	$(".agree").click(function(){
		var memo = $("#memo").val();
		var type = $("#selectvalue").val();
		var files = $("input[name='files']").val();
		var ordersid = $(this).attr('data-orders');
		$.ajax({
			url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-process-add')?>",
			type:'post',
			data:{ordersid:ordersid,memo:memo,files:files,type:type},
			dataType:'json',
			success:function(json){
				if(json.code=="0000"){
					location.href='<?php echo yii\helpers\Url::toRoute(['/productorders/detail',"applyid"=>Yii::$app->request->get("applyid")])?>';
					// history.go(-1);
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
					var div ='<li><img onclick="javascript:void(0)" src = "/images/close.gif" class="closebutton"> <img  onclick="javascript:void(0)" class="imageWxview" data_bid='+data.result.fileid+' src='+wx+data.result.url+' style=" display:inline-block;width:65px;height:65px;"/></li>';
					
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