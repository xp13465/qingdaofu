<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
use kartik\widgets\ActiveForm;
?>

<div class="cm-header">
        <span class="icon-back"></span>
        <i>房产评估</i> 
        <a href="<?php echo yii\helpers\Url::toRoute('/estate/list');?>" class="reload">我的评估</a>		
    </div>
		<section>   
		    <ul class="infor" style="margin-top:12px;"> 
            <?php \yii\widgets\ActiveForm::begin(['id'=>'Estate']);?> 
				<li>
		            <div class="infor_l">
		                <span>房产类型</span>
                        <?= Html::dropDownList('district','',["0"=>"普通住宅"],['class'=>'selects'])?>
		                <span> 
						</span>
		            </div>
		        </li>
		    	<li>
		            <div class="infor_l">
		                <span>选择区域</span>
                        <?= Html::dropDownList('district','',$citySelect,['class'=>'selects'])?>
		                <span> 
						</span>
		            </div>
		        </li>
				
		        <li class="infor_li">
		            <div class="infor_l">
		                <span>小区/地址</span>       
		                <input data-required="true" name="address" style='width:52%'placeholder="请输入小区名称或地址">
		            </div>
		        </li> 
		    </ul>
		    
		   <ul class="infor" style="margin-top:12px;"> 
		        <li>
		            <div style="width:50%;float:left;">
		                <span>楼栋</span>                               
		                <input data-required="true" name="buildingNumber" placeholder="楼栋号" >
		                <span>
		                   <div class="select-area">
		                   <i class="select-value">号</i>
		                   </div>
						</span>
		            </div> 
		            <div style="width:50%;float:left;">
		                <span>&nbsp;</span>                               
		                <input  data-required="true" name="unitNumber" placeholder="室号" >
		                <span>
		                   <div class="select-area">
		                   <i class="select-value">室</i>
		                   </div>
						</span>
		            </div> 
		        </li> 
		    	<li>
		            <div class="infor_l">
		                <span>面积</span>
		                <input data-required="true" type="text" name="size"  placeholder="请输入面积"> 
		                <span>
		                   <div class="select-area">
		                   <i class="select-value">平米</i>
		                   </div>
						</span>
		            </div>
		        </li>
		        <li>
		           <div style="width:50%;float:left;">
		                <span>楼层</span>                               
		                <input  data-required="true" name="floor" placeholder="楼层" >
		                <span>
		                   <div class="select-area">
		                   <i class="select-value">层</i>
		                   </div>
						</span>
		            </div> 
		            <div style="width:50%;float:left;">
		                <span>&nbsp;</span>                               
		                <input  data-required="true" name="maxFloor" placeholder="共几层" >
		                <span>
		                   <div class="select-area">
		                   <i class="select-value">层</i>
		                   </div>
						</span>
		            </div>           
		        </li> 
                 <?php \yii\widgets\ActiveForm::end();?>
		    </ul>
           
		</section>
    <footer>
	    <div class="zhen">
	        <a href="javascript:void(0);" class="renz">立即评估</a>
	    </div>
    </footer>
    <script type="text/javascript">
    $(document).ready(function () {
		$('input[data-required="true"]').keyup(function(){
			if($(this).val()==''){
				$(this).css("color","red")
			}else{
				$(this).css("color","")
			}
        }) 
        $('.renz').click(function(){
			
            var flag = true;
            $('input[data-required="true"]').each(function(){
                var name = $(this).attr("name")
				var value = $(this).val()
                if(value==''){
					$(this).css("color","red").focus();
                    flag=false;
					return false;
                }
				switch(name){
					case "address":
						var address = /^(\w*[\u4E00-\u9FFF]+\w*)*$/;
						if(address.test(value) == false){
							$(this).css("color","red").focus();
							flag=false;
							alert("请输入正确的地址！")
							return false;
						}
					break;
                    case "phone":
						var phone = /^(0|86|17951)?1[3|4|5|7|8]\d{9}$/;
						if(phone.test(value) == false){
							$(this).css("color","red").focus();
							flag=false;
							alert("请输入正确的手机格式！")
							return false;
						}
					break;
					case "size":
						var size =  /^([1-9][\d]{0,7}|0)(\.[\d]{1,2})?$/;
						if(size.test(value) == false){
							$(this).css("color","red").focus();
							flag=false;
							alert("请输入正确的面积！")
							return false;
						}
					break;
					case "buildingNumber":
						var buildingNumber = /^\d*$/;
						if(buildingNumber.test(value) == false){
							$(this).css("color","red").focus();
							flag=false;
							alert("请输入正确的楼栋号！")
							return false;
						}
					break;
					case "unitNumber":
						var unitNumber = /^\d*$/;
						if(unitNumber.test(value) == false){
							$(this).css("color","red").focus();
							flag=false;
							alert("请输入正确的室号！")
							return false;
						}
					break;
					case "floor":
						var floor = /^\d*$/;
						if(floor.test(value) == false){
							$(this).css("color","red").focus();
							flag=false;
							alert("请输入正确的楼层！")
							return false;
						}
					break;
					case "maxFloor":
						var maxFloor = /^\d*$/;
						if(maxFloor.test(value) == false || value< parseInt($("input[name='floor']").val()) ){
							$(this).css("color","red").focus();
							flag=false;
							alert("请输入正确的总楼层！")
							return false;
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
                url:"<?= \yii\helpers\Url::toRoute("/estate/pinggu")?>",
                type:'post',
                data:$('#Estate').serialize(),
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
						layer.close(index);
                        location.href = "<?= \yii\helpers\Url::toRoute("/estate/result")?>"+"?id="+json.result;
                    }else{
                        layer.msg(json.msg);
						layer.close(index);
                    }
                }
                
            })
        })
        $('.icon-back').click(function () {
            history.back();
        });
    });
</script>