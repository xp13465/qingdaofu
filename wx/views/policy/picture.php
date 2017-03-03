 <?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;

?>
<style>
.closebutton{position:absolute;z-index:20;display:block}
.phc:hover .closebutton{display:block;}   
</style>
 <?=wxHeaderWidget::widget(['title'=>'完善资料','gohtml'=>''])?>
<section> 
<?php \yii\widgets\ActiveForm::begin(['id'=>'Picture']);?>
    <input type='hidden' name = 'id' value=" <?= isset($id)?$id:'' ?>"> 
     <?php $data = ['qisu'=>'起诉书','caichan'=>'财产保全申请书','zhengju'=>'相关证据资料','anjian'=>'案件受理通知书']?>
    <?php foreach(["qisu","caichan","zhengju","anjian"] as $val):?>
    <div class="add_tu" style="margin-top:20px;">				
        <div class="img-box">
        	<p style="border-bottom: 1px solid #ddd;padding-bottom:10px;"><?= $data[$val]?></p>
        	<div class="photo_11" name="0">
                 <?php if(isset($$val)?$$val:''){ ?>
                 <?php foreach($$val as $v){?>
        		  <span class="phc"  >
                          <img src = "/images/close.gif" class="closebutton" />
                          <img data_bid='<?= $v['id'] ?>' class="imagePreview" src='<?= Yii::$app->params['wx'].$v['file'] ?>' style="display:inline-block;"/>   
        		  </span>	
                <?php } ?>
                <?php } ?>
                <span class="pic"  >
                    <?php echo \yii\helpers\Html::hiddenInput($val,$model[$val],['data_url'=>'','data-required'=>'true']);?>
					<img  class="add_tu_05" inputName='<?=$val?>' limit = '5'  src="/images/pc.png">
        		</span>	        				        		
        	</div>
        </div> 
    </div>	
    <?php endforeach;?>
     <?php yii\widgets\ActiveForm::end()?>	
    <footer>
    <div class="zhen" data_id='<?= isset($id)?$id:'' ?>'>
        <a href="javascript:void(0);">保存</a>
    </div>
    </footer>
</section>	
<script type="text/javascript">
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
    
    
   // var w = document.body.clientWidth;
    //var h = document.body.clientHeight;
    if (window.innerWidth)
    winWidth = window.innerWidth;
    else if ((document.body) && (document.body.clientWidth))
    winWidth = document.body.clientWidth;
    // 获取窗口高度
    if (window.innerHeight)
    winHeight = window.innerHeight;
    else if ((document.body) && (document.body.clientHeight))
    winHeight = document.body.clientHeight;
    // 通过深入 Document 内部对 body 进行检测，获取窗口大小
    if (document.documentElement && document.documentElement.clientHeight && document.documentElement.clientWidth)
    {
    winHeight = document.documentElement.clientHeight;
    winWidth = document.documentElement.clientWidth;
    }
	/*
    $('.add_tu_05').on( 'click', function () {
        var name = $(this).prev('input:hidden').attr("name");
        var data_url = $(this).prev('input:hidden').attr("data_url");
        $.msgbox({
        closeImg: '/images/close.png',
        async: false,
        width: winWidth * 0.7,
        height: winHeight*0.8,
        title: '请选择图片',
        content: "<?php echo \yii\helpers\Url::toRoute(["/common/uploadswx"])?>/?type=" + name+'&data_url='+data_url+'&i='+2+'&limit='+5,
        type: 'ajax',
        onClose: function(){
            
        }
    });
   });
   */
   $('.zhen').click(function(){

       var id = $(this).attr('data_id');
       $.ajax({
        url:"<?php echo yii\helpers\Url::toRoute('/policy/picturedata')?>",
        type:'post',
        data:$('#Picture').serialize(),
        dataType:'json',
        success:function(json){
            if(json.code == '0000'){
                  location.href="<?php echo yii\helpers\Url::toRoute('/policy/baohan')?>"+"?id="+id;  
            }else{
                  alert(json.msg);
            }
        }
       })
   });
   $(document).on('click','.imagePreview',function(){
        var id = $(this).parents().children('.pic').children('input').val();
        var index =  $(this).parents(".phc").index();
        $.ajax({
            url:"<?= yii\helpers\Url::toRoute('/preservation/picturecategory')?>",
            type:'post',
            data:{id:id},
            dataType:'json',
            success:function(json){
                if(json.code == '0000'){
		            	WeixinJSBridge.invoke('imagePreview', {  
                              'current' : json.piclist[index],  
				              'urls' : json.piclist  
			             });
        	            }	
             }
             
          })
        });
});

</script>
<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<input  style='display:none' type="file" name="file" id='id_photos' value="" />
<script>	
$(document).ready(function(){
	
		
	//照片异步上传
	$(".add_tu_05").click(function(){
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
					
						var img=new String();
						var arr=new Array();
						var img = $("input[name='" + inputName + "']").attr('data_url');
						arr=img.split(',');
						var str = '';
						var div ='<span class="phc"  ><img src = "/images/close.gif" class="closebutton"/> <img class="imagePreview" data_bid='+data.result.fileid+' src='+wx+data.result.url+' style=" display:inline-block;"/></span>';
						 
						var spandiv = $("input[name='" + inputName + "']").parent("span");
						if(spandiv.index()==0){
							spandiv.parent().prepend(div)
						}else{
							spandiv.prev().after(div);
						}
						//alert(spandiv.parent().children().size());
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