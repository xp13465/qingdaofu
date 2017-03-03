<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
use yii\helpers\ArrayHelper;
?>
<?=wxHeaderWidget::widget(['title'=>'申请保函','homebtn'=>false,'gohtml'=>'<a href="/policy/index?type=1" class="reload">我的保函</a>'])?>
<style>
.curtab{ border-bottom:2px solid #0065b3; color:#0065b3; }
.closebutton{position:absolute;z-index:20;display:block}
.phc:hover .closebutton{display:block;}  
</style>

<section>
<?php yii\widgets\ActiveForm::begin(['id'=>'policyform'])?>  
	<div class="title-t">
				<ul>
					<li class="T-one curtab" >①基本信息</li>
					<li class="T-two">②完善信息</li>
				</ul>
			</div>
				<div class="jiben">
                   <ul class="infor" style="margin-top:12px;"> 
   	                   <li>
    		            <div class="infor_l">
    		                <span>选择区域</span>
    		                 <?php 
    						   echo Html::dropDownList('area_pid', '868',
    								ArrayHelper::map($provinceData, 'id','name'), 
    								[
    									'id'=>'area_pid',
    									'class'=>'selects selects_two',
    									'placeholder'=>"请选择",
    									'data-required'=>"true",
    								]
    						   );
    						   ?>
    						    <?php 
    						   echo Html::dropDownList('area_id', '',
    								[""=>"请选择..."], 
    								[
    									'id'=>'area_id',
										'class'=>'selects selects_two',
    									'placeholder'=>"请选择",
    									'data-required'=>"true",
    								]
    						   );
    						   ?>
    		                
    		            </div>
    		        </li>
    		        <li>
    		            <div class="infor_l">
    		                <span>选择法院</span>
    						
    		                <?php 
    						   echo Html::dropDownList('fayuan_id', '',
    								[""=>"请选择..."], 
    								[
    									'id'=>'fayuan_id',
										'class'=>'selects',
    									'onchange'=>"document.getElementById('fayuan_name').value=this.options[this.selectedIndex].text",
    									'placeholder'=>"请选择",
    									'data-required'=>"true",
    								]
    						   );
    						   ?>
    						   <input type='hidden' id = 'fayuan_name' name = 'fayuan_name'>
    		            </div>
    		        </li>
                    <li>
		            <div class="infor_l">
		                <span>案件类型</span>
                           <?= Html::dropDownList('category','',\common\models\CreditorProduct::$category,['data-required'=>'true','class'=>'selects'])?>
		            </div>
		            </li> 					
                    <li>
    		            <div class="infor_l">
    		                <span>案&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号</span>
    		                <?= Html::input('text','anhao','',['placeholder'=>"如:(2016)沪108执00211号",'data-required'=>"true",'style'=>'width:65%;'])?>               
    		            </div>
    		        </li>
    				<li>
    		            <div class="infor_l">
    		                <span>联系方式</span>
    		                <?= Html::input('text','phone','',['placeholder'=>"请输入手机号码",'data-required'=>"true",'style'=>'width:60%;'])?>               
    		            </div>
    		        </li> 
    		        <li>
    		            <div class="infor_l">
    		                <span>保函金额</span>
    		               <?= Html::input('text','money','',['placeholder'=>"请输入保全金额",'data-required'=>"true",'style'=>'width:50%;'])?>
    		                <span>		                   
    		                   <i class="select-value" style="color:#0065b3;">万元</i>
    						</span>
    		            </div>
    		        </li> 
                 </ul>			     	  
					 <ul class="infor" style="margin-top:12px;">
			        <li class="li_infor" style="border-bottom:0px solid #ddd;">
			            <div class="infor_l">
			                <span class="infor_loan_l">取函方式</span>
                             <select name="type" data-required = "true" style="width:65%;left:35%;height:50px;background:#fff;-webkit-appearance:none;">
                                    <option value="" selected="">请选择</option>
                                    <option value="2">快递</option>
                                    <option value="1">自取</option>
                              </select>
                            
			                <!--<i class="infor_loan_r">    
                               <a href="javascript:void(0);"  class="kuaidi" style="width:50%;background:#0065b3;color:#fff;">快递</a>
			                    <a href="javascript:void(0);"  class="ziqu" style="width:50%;">自取</a>
								 //Html::hiddenInput('type','2',['data-required'=>"true"]);
			                </i>-->
			            </div>
			        </li>						   
			        <li class="kd" style="display:none;height:80px;border-bottom:0px solid #ddd;">
			            <div class="infor_l">
			                <span>收货地址</span>
			                <input type="text" name="address"  placeholder="请输入收货地址"  style="width:64%;">    
			            </div>
			        </li>
			        <li class="zq" style="display:none;height:80px;">
			            <div class="infor_l">
			                <span>取函地址</span>							                
                           <input type='text' name ='fayuan_add' id = 'fayuan_add' disabled  style="width:35%;background:#fff;">
						   <?= Html::hiddenInput('fayuan_address','');?>
			            </div>
			        </li>							
			     </ul>
                <footer>
                  <div class="zhen">
                      <a href="javascript:void(0);" class="xia">下一步</a>
                  </div>
                </footer>
		    </div>
  
           <div class="bigon" style="display:none;">		    
			 <?php $data = ['qisu'=>'起诉书','caichan'=>'财产保全申请书','zhengju'=>'相关证据资料','anjian'=>'案件受理通知书']?>
            <?php foreach(["qisu","caichan","zhengju","anjian"] as $val):?>
		    <div class="add_tu" style="margin-top:12px;">				
		        <div class="img-box">
                     
		        	<p style="border-bottom: 1px solid #ddd;padding-bottom:10px;"><?= $data[$val]?></p>
		        	<div class="photo_11" name="0">
                         <?php if(isset($$val)?$$val:''){ ?>
                         <?php foreach($$val as $v){?>
		        		  <span class="phc"  >
                                  <img class="imagePreview" src = "/images/close.gif" class="closebutton"/>
                                  <img class="imagePreview" src='<?= Yii::$app->params['wx'].$v['file'] ?>'/>   
		        		  </span>	
                        <?php } ?>
                        <?php } ?>
                        <span class="pic"  >
                            <?php echo \yii\helpers\Html::hiddenInput($val,isset($model[$val])?$model[$val]:'',['data_url'=>'','img-required'=>'true']);?>
							<img  class="add_tu_05" inputName='<?=$val?>' limit = '5'  src="/images/pc.png">
		        		</span>	        				        		
		        	</div>
		        </div> 
		    </div>	
            <?php endforeach;?>
              <footer>
                <div class="zhen">
                    <a class="shang">上一步</a>
                    <a class="baocun">保存</a>                   
                  </div>
               </footer>
		    </div>    
<?php yii\widgets\ActiveForm::end()?>
		</section>	
		<script>
        $(document).ready(function(){
           $('input[data-required="true"],select[data-required="true"]').on("keyup change",function(){
				if($(this).val()==''){
					$(this).css("color","red")
				}else{
					$(this).css("color","")
				}
			}) 
			$('input[img-required="true"]').change(function(){
				var name = $(this).attr("name")
				var value = $(this).val()
                if(value==''){
					$(this).parents(".photo_11").prev("p").css("color","red")
                }else{
					$(this).parents(".photo_11").prev("p").css("color","")
				}
				
            })
		$('#area_pid').change(function () {
            var pid = $(this).val();
            if(pid){
                $.ajax({
                    url:'<?php echo \yii\helpers\Url::toRoute('/policy/area-city')?>',
                    data:{'depdrop_parents':[pid]},
                    type:'post',
                    success:function(data){
						var html='<option value="" selected="">请选择...</option>';
						if(data['code']=='0000'){
							$('#area_id').html(html+data['html'])
							$('#fayuan_id').html(html)
                            $('#fayuan_address').html(html)
						}
                    },
					dataType:'json'
                });
            }
        }).trigger("change");
		
		$('#area_id').change(function () {
            var pid = $("#area_pid").val();
            var id = $(this).val();
            if(pid&&id){
                $.ajax({
                    url:'<?php echo \yii\helpers\Url::toRoute('/policy/fayuan')?>',
                    data:{'depdrop_parents':[pid,id]},
                    type:'post',
                    success:function(data){ 
						if(data['code']=='0000'){
							var html='<option value="" selected="">请选择...</option>';
							$('#fayuan_id').html(html+data['html'])
						}
                    },
					dataType:'json'
                });
            }
        });
          
            $("#fayuan_id").change(function(){
                    $("#fayuan_add").val($(this).find("option:selected").text())
					$('input[name="fayuan_address"]:hidden').val($(this).find("option:selected").text())
                })
		
		
		
        $('.baocun').click(function(){
			var flag = true;
            $('input[data-required="true"],select[data-required="true"]').each(function(){
				var name = $(this).attr("name")
				var value = $(this).val()
                if(value==''){
					$(this).css("color","red").focus();
                    flag=false;
					return false;
                }
				switch(name){
					case "phone":
						var phone = /^(0|86|17951)?1[3|4|5|7|8]\d{9}$/;
						if(phone.test(value) == false){
							$(this).css("color","red").focus();
							flag=false;
							alert("请输入正确的手机格式！")
							return false;
						}
					break;
					case "money":
						var money = /^\d*$/;
						if(money.test(value) == false){
							$(this).css("color","red").focus();
							flag=false;
							alert("请输入正确的金额！")
							return false;
						}
					break;
				}
				
            })
			$('input[img-required="true"]').each(function(){
				var name = $(this).attr("name")
				var value = $(this).val()
                if(value==''){
					$(this).parents(".photo_11").prev("p").css("color","red")
					alert("请上传"+$(this).parents(".photo_11").prev("p").html())
					flag=false;
					return false;
                }else{
					$(this).parents(".photo_11").prev("p").css("color","#000")
				}
				
            });
            if(!flag){
				layer.close(index);
                return;
            }
			var index = layer.load(1, {
		            shade: [0.4,'#fff'] //0.1透明度的白色背景
		         });
            var url = "<?php echo yii\helpers\Url::toRoute('/policy/adddata')?>";
            $.post(url,$('#policyform').serialize(),function(json){
                  if(json.code == '0000'){
                      alert(json.msg);
					  layer.close(index);
                      window.location = "<?php echo yii\helpers\Url::toRoute('/policy/addok')?>";
                  }else{
                      alert(json.msg);
                  }
            },'json')
        })
    })
        
        
        
        
			$(document).ready(function(){
				$(".shang").click(function(){
					$(".jiben").show();
					$(".bigon,.relation").hide();
					$(".T-one").addClass("curtab").siblings().removeClass("curtab");
				})	
				$(".xia").click(function(){
					$('.cm-header').children('.reload').replaceWith('<a href="javascript:;" onclick="reloads()">样本展示</a>')
					var flag = true;
					$("div.jiben").find('input[data-required="true"],select[data-required="true"]').each(function(){
						var name = $(this).attr("name")
						var value = $(this).val()
						if(value==''){
							$(this).css("color","red").focus();
							flag=false;
							return false;
						}
						switch(name){
							case "phone":
								var phone = /^(0|86|17951)?1[3|4|5|7|8]\d{9}$/;
								if(phone.test(value) == false){
									$(this).css("color","red").focus();
									flag=false;
									alert("请输入正确的手机格式！")
									return false;
								}
							break;
							case "money":
								var money = /^\d*$/;
								if(money.test(value) == false){
									$(this).css("color","red").focus();
									flag=false;
									alert("请输入正确的金额！")
									return false;
								}else if(value.length > 6){
            						  $(this).css("color","red").focus();
            							flag=false;
            							alert("请输入的金额不要大于十亿！");
                                        return false;
            						}
							break;
							case "type":
							if($('select[name=type] option:selected').val() == 1){
								if($('input[name=fayuan_address]').val() == ''){
									flag = false;
									alert("请输入法院地址");
									return false;
								}
								
							}else if($('select[name=type] option:selected').val() == 2){
								if($('input[name=address]').val() == ''){
									flag = false;
									alert("请输入快递地址");
									return false;
								}
							}
							break;
						}
					})
				
					if(!flag){
						return false;
					}
					$(".bigon").show();
					$(".jiben,.relation").hide();
					$(".T-two").addClass("curtab").siblings().removeClass("curtab");
				})	
				

			});

	$(document).ready(function(){
		$("select[name='type']").change(function(){
			if($(this).val()==0){
				$(".kd").hide();
			    $(".zq").hide();
			}else if($(this).val()==2){
				$(".zq").hide();
			    $(".kd").show();
			}else if($(this).val()==1){
				$(".kd").hide();
			    $(".zq").show();
			}
		})
			
			
			/* $(document).ready(function(){
               $(".kuaidi").click(function(){
				   $('input[name=type]').val('2');
					$(".kd").show();
					$(".zq").hide();	
					$(".kuaidi").css({"background":"#0065b3","color":"#fff"});
					$(".ziqu").css({"background":"#fff","color":"#333"});								
				})
				$(".ziqu").click(function(){
					$('input[name=type]').val('1');
					$(".kd").hide();
					$(".zq").show();	
					$(".ziqu").css({"background":"#0065b3","color":"#fff"});
					$(".kuaidi").css({"background":"#fff","color":"#333"});									
				});*/
                
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
                height: winHeight * 0.8,
                title: '请选择图片',
                content: "<?php echo \yii\helpers\Url::toRoute(["/common/uploadswx"])?>/?type=" + name+'&data_url='+data_url+'&i='+3+'&limit='+5,
                type: 'ajax',
                onClose: function(){
                    
                }
            });
           });*/
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
		function reloads(){
					var wx = "<?php echo Yii::$app->params['wxs'];?>";
					var pic_list = [''+wx+'/images/baohan1.jpg']
					WeixinJSBridge.invoke('imagePreview', {  
                                      'current' : pic_list[0],  
						              'urls' : pic_list
					             });
				};
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
