<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
 

 



?>
<style>
.basic_01 .basic_main ul li {
	border-bottom: 0px solid #ddd;
}
.zqlx .zqlx-r ul li {
	width:45%;
	border-bottom: 0px solid #ddd;
}
.infor_c{display:none;}
</style>

<?=wxHeaderWidget::widget(['title'=>'申请终止','gohtml'=>"<a class='' href='tel:4008557022'>平台介入</a>",'backurl'=>true,'reload'=>false])?>
<section>
  <div class="basic" style="position:relative;z-index:666;">
    <ul>
      <!-- <li> <span class="current">终止原因</span></li>-->
      <!--<li> <span class="">平台介入</span></li>-->
    </ul>
  </div>
  <div class="basic_01">
    <div class="basic_main current" style="display: block;">
      <!--<p style="font-size:16px;text-indent:20px;margin-top:12px;">请选择终止原因：</p>
      <div class="zqlx">
        <div class="zqlx-r" style="width:90%;">
          <ul>
            <li class="activing"><input type="checkbox" name="diya" id="diya" class="infor_c"><a href="#" class="active">材料复核有问题</a></li>
            <li class="activing"><input type="checkbox" name="diya" id="diya" class="infor_c"><a href="#" class="active">没有按时支付佣金</a></li>
            <li class="activing"><input type="checkbox" name="diya" id="diya" class="infor_c"><a href="#" class="active">合作不愉快</a></li>
            <li class="activing"><input type="checkbox" name="diya" id="diya" class="infor_c"><a href="#" class="active">其他</a></li>
          </ul>
        </div>
      </div> -->
      <div class="xq">
        <textarea type="text" name="applymemo" id="applymemo" placeholder="请详细描述申请终止的缘由，让接/发布方做的更好" style="width:100%;height:60px;padding-left:20px;resize:none;"></textarea>
		<ul>  
			<li class='pic'>
			<?php echo Html::hiddenInput("files",'',['data_url'=>'']);?>
			<a href="javascript:void(0)" class="addtu" inputName='files' limit = '10' >
			<img src="/bate2.0/images/tian.png">
			</a>
			</li> 
		  </ul>
      </div>
    </div>
  </div>
</section>
<footer>
  <div class="bottom" style="height:120px;">
	<a href="javascript:void(0)" data-ordersid='<?= Yii::$app->request->get('ordersid')?>' class="agreed" style="background:#fff;color:#666;border:1px solid #ddd;">申请终止</a>
	<a href="javascript:void(0);" class="refused" style="background:#10a1ec;">再考虑一下</a> 
  </div>
</footer>

<script>

$(document).ready(function(){
	$(".agreed").click(function(){
		var applymemo = $("#applymemo").val();			
		var files = $("input[name='files']").val();
		var ordersid = $(this).attr('data-ordersid');
		$.ajax({
			url:"<?php echo yii\helpers\Url::toRoute('/productorders/orders-termination-apply')?>",
			type:'post',
			data:{ordersid:ordersid,applymemo:applymemo,files:files},
			dataType:'json',
			success:function(json){
				if(json.code=="0000"){
					layer.msg(json.msg,{},function(){history.go(-1)})
				}else{
					layer.msg(json.msg)
				}
			}
		})
	})

}); 

	 
</script>

<script type="text/javascript">
    $(document).ready(function(){
		$('.basic li span').click(function(){
            var index = $(this).parent().index();
            $(this).addClass('current').parent().siblings().children().removeClass('current').parents().parent().next().children().eq(index).show().siblings().hide();
        });
		$('.refused').click(function(){
			history.go(-2);
		})
    })

$(document).ready(function(){
	$(".activing a").click(function(){
		var $child = $(this).parent().children("input")
		if($child.prop("checked")){
			$child.prop("checked",false)
		}else{
			$child.prop("checked",true)
		}
		$child.trigger("change")
	})
	$(".infor_c").change(function(){
		if($(this).prop("checked")){
			$(this).parent('.activing').addClass("act")
		}else{
			$(this).parent('.activing').removeClass("act")
		}
	})
	$(".radioBtn").click(function(){
		var $child = $(this).children("input")
		$child.prop("checked",true).trigger("change")
	})
	$(".btn_r").change(function(){
		$(this).parent().siblings().removeClass('checked')
		$(this).parent().addClass("checked")
	})
})
	
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
					var div ='<li><img src = "/images/close.gif" class="closebutton" onclick="javascript:void(0)" style="position: absolute;z-index: 20;display: block;"> <img onclick="javascript:void(0)" class="imagePreview" data_bid='+data.result.fileid+' src='+wx+data.result.url+' style=" display:inline-block;width:65px;height:65px;"/></li>';
					
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