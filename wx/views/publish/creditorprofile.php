<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
use \common\models\CreditorProduct;
?>
<?=wxHeaderWidget::widget(['title'=>'添加债权人信息','gohtml'=>'<a href="javascript:void(0)" class="save">保存</a>'])?>
<form>
    <?php
        $data_new = isset($data[Yii::$app->request->get('keynum')])?$data[Yii::$app->request->get('keynum')]:[];
    ?>
<section>
        <ul class="add_xi">
            <li>
                <span>姓名</span>
                <?php echo Html::input('text','creditorname',!empty($data_new)?$data_new['creditorname']:'',['placeholder'=>'请输入姓名'])?>
            </li>
            <li>
                <span>联系方式</span>
                <?php echo Html::input('text','creditormobile',!empty($data_new)?$data_new['creditormobile']:'',['placeholder'=>'请输入联系方式'])?>
            </li>
            <li>
                <span>联系地址</span>
                <?php echo Html::input('text','creditoraddress',!empty($data_new)?$data_new['creditoraddress']:'',['placeholder'=>'请输入地址'])?>
            </li>
            <li>
                <span>证件号</span>
                <?php echo Html::input('text','creditorcardcode',!empty($data_new)?$data_new['creditorcardcode']:'',['placeholder'=>'请输入证件号'])?>
            </li>
    </ul>

        <div class="add_file">
            <div class="add_file_l">
                <span class="uploadSpan" inputName="creditorcardimage" limit="5" ></span>
                <?php echo \yii\helpers\Html::hiddenInput('creditorcardimage',!empty($data_new)?$data_new['creditorcardimage']:'');?>
                <p>上传证件图片</p>
            </div>
        </div>
</section>
</form>
<script>
    $(function(){
      /* var w = document.body.clientWidth;
        var h = document.body.clientHeight;
        $(document).delegate('.uploadSpan','click',function(){
            var name = $(this).next('input:hidden').attr("name");
            $.msgbox({
                closeImg: '/images/cha01.png',
                async: false,
                width:w*0.9,
                height:h *0.8,
                title:'请选择图片',
                content:"<?php echo \yii\helpers\Url::toRoute(["/common/uploadimage"])?>/?type="+name,
                type:'ajax'
            });
        });*/
        $(document).delegate('.uploadChakan','click',function() {
            var typeName = $(this).prev('div').children('input:hidden').attr("name");
            var name = $(this).prev('div').children('input:hidden').val();
            $.msgbox({
                closeImg: '/images/cha01.png',
                async: false,
                width:w*0.9,
                height:h *0.8,
                title: '显示照片',
                content: "<?php echo \yii\helpers\Url::toRoute(["/common/viewimages"])?>/?name=" + name + "&typeName=" + typeName,
                type: 'ajax'
            });
        });
        $(document).delegate('.save','click',function() {
            if($.trim($('input[name = "creditorname"]').val()) == ''){
                alert('请输入姓名');return false;
            }
            if($.trim($('input[name = "creditormobile"]').val()) == ''){
                alert('请输入联系方式');return false;
            }
            if($.trim($('input[name = "creditoraddress"]').val()) == ''){
                alert('请输入地址');return false;
            }
            if($.trim($('input[name = "creditorcardcode"]').val()) == ''){
                alert('请输入证件号');return false;
            }

            setCookie(getCookie('publishCookieName')+'creditorprofile',$('form').serialize(),'h1');
            <?php

            if(!wx\services\Func::isInt(Yii::$app->request->get('id'))||!yii\helpers\ArrayHelper::isIn(Yii::$app->request->get('category'),[2,3])){
            ?>
            setTimeout("window.location = document.referrer ;",500);
            <?php
            }else{?>
            $.ajax({
                url:"<?php echo \yii\helpers\Url::toRoute(['/publish/creditorprofile','id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category'),'keynum'=>Yii::$app->request->get('keynum')])?>",
                type:'post',
                data:getCookie(getCookie('publishCookieName')+'creditorprofile'),
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
                        alert("保存成功");
                        delCookie(getCookie('publishCookieName')+'creditorprofile');
                        setTimeout("window.location = document.referrer ;",500);
                    }else{
                        alert(json.msg);
                    }
                }
            });
            <?php
            }
            ?>
        });

        function setDefaultValue(){
            if(getCookie(getCookie('publishCookieName')+'creditorprofile') == null || getCookie(getCookie('publishCookieName')+'creditorprofile') == '' ){
                return false;
            }
            var all = formUnserialize(getCookie(getCookie('publishCookieName')+'creditorprofile'));

            for(cname in all){
                var v = decodeURIComponent(all[cname]);
                $('[name="'+cname+'"]').val(v);
            }
        }
        <?php
            if(!wx\services\Func::isInt(Yii::$app->request->get('id'))||!yii\helpers\ArrayHelper::isIn(Yii::$app->request->get('category'),[2,3])){
            ?>
            setDefaultValue();
            <?php
            }?>
    })
</script>

<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<input  style='display:none' type="file" name="file" id='id_photos' value="" />
<script>	
$(document).ready(function(){
	
	$(document).on("click",".closebutton",function(){
		        var wx = "<?php echo Yii::$app->params['wx'];?>";
				var src = $(this).next('img').attr('src').replace(wx,'');
				var img = $(this).parents().children().children('input[name=creditorcardimage]').val();
                             img = img.replace(src,"");
                             img = img.replace(",''","");
                             img = img.replace("'',","");
                             img = img.replace("''","");
                            $('input[name=creditorcardimage]').val(img);			
				$(this).parent('div').remove();    	
    });
	
      if($('input[name=creditorcardimage]').val()){
               var img = $('input[name=creditorcardimage]').val();
				arr=img.split(',');
				 $.each(arr,function(key,value){
                       values = value.substring(1,value.length-1);
					   var wx = "<?php echo Yii::$app->params['wx'];?>";
					   var div ='<div class="add_file_l"><img src = "/images/close.gif" class="closebutton" style="position:absolute"/><img class="imagePreview" src='+wx+values+' style=" height: 51px; width: 51px;display:inline-block;"/> </div>';
                     $('input[name=creditorcardimage]').parents('.add_file').prepend(div);	
                    });
         }
	
	$(document).on('click','.imagePreview',function(){
                var img = $(this).parents().children().children('input[name=creditorcardimage]').val();
				arr=img.split(',');
				var array = [];
				 $.each(arr,function(key,value){
                       values = value.substring(1,value.length-1);
					   var wx = "<?php echo Yii::$app->params['wx'];?>";
                        array.push(wx+values);
                    });
					var piclist = array;
                   WeixinJSBridge.invoke('imagePreview', {  
                            'current' : piclist[0],  
					        'urls' : piclist  
				       });
			
                });
	
	//照片异步上传
	$(document).on('click',".uploadSpan",function(){
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
		if(limit&&aa.split(",").length>limit){
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
				  //console.log(data);
				layer.close(index)
				 var wx = "<?php echo Yii::$app->params['wx'];?>";
				if(data.code == '0000'&&data.result.fileid){
					var aa = $("input[name='" + inputName + "']").val();
					if(limit&&aa.split(",").length>=limit){
						layer.alert("最多上传"+limit+"张图片");
						return false;
					}
                 					
                    $("input[name='" + inputName + "']").val((aa ? (aa + ",") : '')+"'"+data.result.url+"'").trigger("change"); 					
			        var div ='<div class="add_file_l"><img src = "/images/close.gif" class="closebutton" style="position:absolute"/><img class="imagePreview" src='+wx+data.result.url+' style=" height: 51px; width: 51px;display:inline-block;"/> </div>';
                    $("input[name='" + inputName + "']").parents('.add_file').prepend(div);	
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