<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use wx\widget\wxHeaderWidget;
?>
<style>
.selects_two{ 
    width: 29.5%; 
}
</style>
<?=wxHeaderWidget::widget(['title'=>'申请保全','homebtn'=>false,'gohtml'=>'<a href="/preservation/baoquanlist?type=1" class="reload">我的保全</a>'])?>
		<section>  
        <?php \yii\widgets\ActiveForm::begin(['id'=>'Baoquan']);?>    
		    <ul class="infor" style="margin-top:12px;"> 
		    	<li>
		            <div class="infor_l">
		                <span>选择城市</span>
		                   <?php 
						   echo Html::dropDownList('area_pid', '868',
								ArrayHelper::map($provinces, 'id','name'),
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
		                <span>联系方式</span>
		                <?= Html::input('text','phone',isset($model['phone'])?$model['phone']:$model,['placeholder'=>'请输入手机号码','data-required'=>"true"]);?>
		            </div>
		        </li> 		      
		        <li>
		            <div class="infor_l">
		                <span>保全金额</span>
		                <?= Html::input('text','account',isset($model['account'])?round($model['account']/10000):'',['placeholder'=>'请输入债权金额','data-required'=>"true"]);?> 
		                <span>		                   
		                   <i class="select-value" style="color:#0065b3;">万元</i>
		                   </div>
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
							// Html::hiddenInput('type','2',['data-required'=>"true"]);
			                </i>--->
                            
                            
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
                           <input type='text' id = 'fayuan_add' name = 'fayuan_add' disabled  style="width:35%;background:#fff;">
						   <?= Html::hiddenInput('fayuan_address','');?>
			            </div>
			        </li>							
			    </ul>
                <?php yii\widgets\ActiveForm::end()?>		    
		</section>
        
		<footer>
		    <div class="zhen">
		        <a href="javascript:void(0);">点击申请</a>
		    </div>
		</footer>
		<script type="text/javascript">
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
		 
         /*  $(".kuaidi").click(function(){
					$('input[name=type]').val('2');
					$(".kd").show();
					$(".zq").hide();	
					$(".kuaidi").css({"background":"#0065b3","color":"#fff"});
					$(".ziqu").css({"background":"#fff","color":"#000"});								
				})
				$(".ziqu").click(function(){
					$('input[name=type]').val('1');
					$(".kd").hide();
					$(".zq").show();	
					$(".ziqu").css({"background":"#0065b3","color":"#fff"});
					$(".kuaidi").css({"background":"#fff","color":"#000"});									
				});*/
                $("#fayuan_id").change(function(){
                    $("#fayuan_address").val($(this).val())
                })		
                $('input[data-required="true"],select[data-required="true"]').keyup(function(){
			             if($(this).val()==''){
                          $(this).css("color","red")
			             }else{
				          $(this).css("color","")
		                	}
             	});
                
                 $('#area_pid').change(function(){
					
			
                    var pid = $(this).val();
                    $.ajax({
                        url:"<?php echo \yii\helpers\Url::toRoute('/policy/area-city')?>",
                        type:'post',
                        data:{'depdrop_parents':[pid]},
                        dataType:'json',
                        success:function(json){
                            var html='<option value="" selected="">请选择...</option>';
						    if(json['code']=='0000'){
							$('#area_id').html(html+json['html'])
							$('#fayuan_id').html(html)
                            //$('#fayuan_address').html(html)
						}
                        }
                    })
            }).trigger("change");
            
            $('#area_id').change(function () {
             var pid = $("#area_pid").val();
             var id = $(this).val();
                if(pid&&id){
                $.ajax({
                    url:'<?php echo \yii\helpers\Url::toRoute('/policy/fayuan')?>',
                    data:{'depdrop_parents':[pid,id]},
                    type:'post',
                    dataType:'json',
                    success:function(data){ 
						if(data['code']=='0000'){
							var html='<option value="" selected="">请选择...</option>';
							$('#fayuan_id').html(html+data['html'])
						}
                    },
                 });
              }
            });
            
			 $("#fayuan_id").change(function(){
                    $("#fayuan_add").val($(this).find("option:selected").text())
					$('input[name="fayuan_address"]:hidden').val($(this).find("option:selected").text())
                })
            
            $('.zhen').click(function(){
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
					case "account":
						var account = /^\d*$/;
						if(account.test(value) == false){
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
								if($.trim($('input[name=fayuan_address]').val()) == ''){
									flag = false;
									alert("请输入法院地址");
									return false;
								}
								
							}else if($('select[name=type] option:selected').val() == 2){
								if($.trim($('input[name=address]').val()) == ''){
									flag = false;
									alert("请输入快递地址");
									return false;
								}
								
							}
					break;
				}
            })
		    
            if(!flag){
                return;
                }
				var index = layer.load(1, {
		            shade: [0.4,'#fff'] //0.1透明度的白色背景
		         });
                $.ajax({
                   url:"<?= yii\helpers\Url::toRoute('/preservation/baoquan')?>",
                   type:'post',
                   data:$('#Baoquan').serialize(),
                   dataType:'json',
                   success:function(json){
                       if(json.code == '0000'){
						layer.close(index);
                        location.href = "<?= yii\helpers\Url::toRoute(['/preservation/success','id'=>''])?>"+json.result.id;
                       }else{
                        alert(json.msg);
                       }
                   }
                });
            });
            // $('.icon-back').click(function () {
                // history.back();
            // });
			
    });
    
</script>