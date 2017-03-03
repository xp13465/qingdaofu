<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
?>
<style>
.closebutton{position:absolute;z-index:20;display:block}
.phc:hover .closebutton{display:block;}  
</style>
<?=wxHeaderWidget::widget(['title'=>'认证律所','gohtml'=>''])?>
<section>
    <?php \yii\widgets\ActiveForm::begin(['id'=>'lawyer']);?>
    <div class="shenfen">
        <p>请上传律所执业图片</p>
    </div>
    <div class="tupian">
		<?php echo \yii\helpers\Html::hiddenInput('cardimgimg',isset($certi['cardimgimg'])?$certi['cardimgimg']:'');?>
		<?php echo \yii\helpers\Html::hiddenInput('cardimg',isset($certi['cardimg'])?$certi['cardimg']:'');?>
		
        <?php if(!isset($certi['cardimg']) || $certi['cardimg'] ==''){ ?>
		    <div class="photo1" style="margin-left: 25%;">
				<img src="/images/s1.png" style="display:inline-block;" class="photo001" inputName="cardimg" inputNames="cardimgimg" limit="1" types='1'/>
		    </div>
            <!--<div class="photo1" style='display:none'>
				<img src="/images/s2.png" style="display:inline-block;" class="photo002" inputName="cardimg" inputNames="cardimgimg" limit="1" types='2'/>
		    </div>-->
        <?php }else{ ?>
		   <?php  $i=1;$b=1;?>
		   <?php foreach($certi['img'] as $k=>$v){ ?>
		   <div class="photo1" style="margin:-3% 25%;">
			   <img src = "/images/close.gif" class="closebutton" />
			   <img src="<?php echo isset($v['file'])?Yii::$app->params['wx'].$v['file']:''?>" style="display:inline-block;" inputName="cardimg" inputNames="cardimgimg" limit="1" types='<?php echo $i++?>' data_bid="<?php echo $v['id'] ?>"/>
		    </div>
		   <?php } ?>
		   <?php if(count($certi['img']) == 1){ ?>
			<!--<div class="photo1">
			<img src="/images/s2.png" style="display:inline-block;" class="photo002" inputName="cardimg" inputNames="cardimgimg" limit="1" types='2'/>
		    </div>-->
		   <?php } ?>
        <?php } ?>
    </div>
</section>
<section>
    <input type="hidden"  name="status" value="2" />
    <ul class="jb_x basic02">
        <li class="last_li">
            <span class="ct"></span>
            <span class="color">基本信息</span>
        </li>
        <li>
            <span class="name">律所名称</span>
            <input type="text"  name="name" placeholder="请输入律所名称" style="font-size:14px;width:60%;" value="<?php echo isset($certi['name'])?$certi['name']:''?>"/>
        </li>
        <li>
            <span class="name">执业证号</span>
            <input type="text" name="cardno" placeholder="请输入执业证号" style="font-size:14px;width:60%;" value="<?php echo isset($certi['cardno'])?$certi['cardno']:''?>"/>
        </li>
        <li class="last_li">
            <span class="name">联系人</span>
            <input type="text" name="contact" placeholder="请输入联系人" style="font-size:14px;width:60%;" value="<?php echo isset($certi['contact'])?$certi['contact']:''?>"/>
        </li>
        <li class="last_li">
            <span class="name">联系方式</span>
            <input type="text" name="mobile" placeholder="15808340098" style="font-size:14px;width:60%;" value="<?php echo isset($certi['mobile'])?$certi['mobile']:$mobile?>"/>
        </li>
    </ul>
    <ul class="jb_x basic01">
        <li class="last_li">
            <span class="ct"></span>
            <span class="color">补充信息</span>
            <span class="select">(选填)</span>
        </li>
        <li>
            <span class="name">邮箱</span>
            <input type="text" name="email" placeholder="请输入您常用邮箱" style="font-size:14px;width:60%;" value="<?php echo isset($certi['email'])?$certi['email']:''?>"/>
        </li>
        <li class="last_li last_li01">
            <span class="name anli">经典案例</span>
            <!-- <input type="text" placeholder="关于个人在清收、诉讼等方面的成功
            案例，有利于发布方更加青睐你" /> -->
            <div class="area">
                <textarea class="text" name="casedesc"  placeholder="关于在清收、诉讼等方面的成功案例，有利于发布方更加青睐你" style="font-size:14px;" /><?php echo isset($certi['casedesc'])?$certi['casedesc']:''?></textarea>
            </div>
        </li>
    </ul>
    <?php \yii\widgets\ActiveForm::end();?>
</section>
<footer>
    <div class="zhen">
        <a href="#" class="renz">提交资料</a>
    </div>
</footer>
<script type="text/javascript">
    $(document).ready(function(){
        $('.renz').click(function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::toRoute('/certification/add')?>',
                type:'post',
                data:$('#lawyer').serialize(),
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
						layer.msg(json.msg,{time:2000},function(){location.href = "<?=\yii\helpers\Url::toRoute('/certification/index')?>";});
                    }else{
						layer.msg(json.msg);
                    }
                }
            });
        });
    })
</script>

<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<input  style='display:none' type="file" name="file" id='id_photos' value="" />
<script>	
$(document).ready(function(){
	
	$(document).on("click",".closebutton",function(){
		        var wx = "<?php echo Yii::$app->params['wx'];?>";
                var id = $(this).parents().children('input[name=cardimgimg]').val();
                var bid = $(this).next().attr('data_bid');
				var types = $(this).next().attr('types');
				//alert(types);
                var temp='';
                var ids =id.split(',');
                for(i in ids){
                    		if(ids[i]==bid){
                    			continue;
                    		}
                    		temp+=temp?","+ids[i]:ids[i];
                    	}
				var src = $(this).next('img').attr('src').replace(wx,'');
				var img = $(this).parents().children('input[name=cardimg]').val();
                            img = img.replace(src,"");
                            img = img.replace(",''","");
                            img = img.replace("'',","");
                            img = img.replace("''","");
                            $('input[name=cardimg]').val(img);			
               	$(this).parents().children('input[name=cardimgimg]').val(temp);
				
				//if(types == 1){
					var imgs = '<img src="/images/s1.png" style="display:inline-block;" class="photo001" inputName="cardimg" inputNames="cardimgimg" limit="1" types="1"/>';
				    $(this).parent().html(imgs);
		
				// }else{
					// var imgs = '<img src="/images/s2.png" style="display:inline-block;" class="photo002" inputName="cardimg" inputNames="cardimgimg" limit="1" types="2"/>';
				    // $(this).parent().html(imgs);
	
				// }
				
                            		
            	
    });
	
	//照片异步上传
	$(document).on('click',".photo001",function(){
		var limit = $(this).attr('limit')?$(this).attr('limit'):1;
		var inputName = $(this).attr('inputName')?$(this).attr('inputName'):'';
		var inputNames = $(this).attr('inputNames')?$(this).attr('inputNames'):'';
		var types = $(this).attr('types');
		if(!inputName)return false;
		$("#id_photos").attr({"inputName":inputName,"inputNames":inputNames,"limit":limit,'types':types}).click();
	})
	
	$(document).on('click',".photo002",function(){
		var limit = $(this).attr('limit')?$(this).attr('limit'):1;
		var inputName = $(this).attr('inputName')?$(this).attr('inputName'):'';
		var inputNames = $(this).attr('inputNames')?$(this).attr('inputNames'):'';
		var types = $(this).attr('types');
		if(!inputName)return false;
		$("#id_photos").attr({"inputName":inputName,"inputNames":inputNames,"limit":limit,'types':types}).click();
	})
	$(document).on("change",'#id_photos',function(){ //此处用了change事件，当选择好图片打开，关闭窗口时触发此事件
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});
		
		var inputName = $(this).attr('inputName');
		var inputNames = $(this).attr('inputNames');
		var limit = $(this).attr('limit')?$(this).attr('limit'):1;
		var aa = $("input[name='" + inputName + "']").val();
		var bb = $("input[name='" + inputNames + "']").val();
		// if(limit&&aa.split(",").length > limit){
			// layer.close(index)
			// layer.alert("最多上传"+limit+"张图片");
			// return false;
		// } 
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
					// if(limit&&aa.split(",").length>=limit){
						// layer.alert("最多上传"+limit+"张图片");
						// return false;
					// }
                 					
					$("input[name='" + inputNames + "']").val((bb ? (bb + ",") : '')+data.result.fileid).trigger("change");
                    $("input[name='" + inputName + "']").val((aa ? (aa + ",") : '')+"'"+data.result.url+"'").trigger("change"); 					
						//var div ='<span class="phc"  ><img src = "/images/close.gif" class="closebutton"/> <img class="imagePreview" data_bid='+data.result.fileid+' src='+wx+data.result.url+' style=" display:inline-block;"/></span>';
											
						//if($('input[name=file]').attr('types')==1){
							var div = '<img src = "/images/close.gif" class="closebutton"/><img src="'+wx+data.result.url+'"  data_bid="'+data.result.fileid+'" style="display:inline-block;" inputName="cardimg" inputNames="cardimgimg" limit="1" types="1"/>';	
							var spandiv = $("input[name='" + inputName + "']").next().html(div);
						// }else{
							// var div = '<img src = "/images/close.gif" class="closebutton"/><img src="'+wx+data.result.url+'"  data_bid="'+data.result.fileid+'" style="display:inline-block;" class="photo002" inputName="cardimg" inputNames="cardimgimg"  limit="1" types="2"/>';	
							// var spandiv = $("input[name='" + inputName + "']").next().next().html(div);
						// }
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





