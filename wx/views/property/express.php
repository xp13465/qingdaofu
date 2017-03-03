<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
use yii\helpers\ArrayHelper;
?>
<?=wxHeaderWidget::widget(['title'=>'填写快递信息','gohtml'=>''])?>
 <?php yii\widgets\ActiveForm::begin(['id'=>'expressform'])?>
 <input type='hidden' name='jid' value="<?=$id?>">
<section>     
		    <ul class="infor" style="margin-top:30px;"> 
		    	<li>
		            <div class="infor_l">
		                <span>联系人</span>
		                <input type="text" name='name' data-required="true" placeholder="收件人姓名"> 		               
		            </div>
		        </li>
		        <li>
		            <div class="infor_l">
		                <span>联系电话</span>
		                <input type="text" name='phone' data-required="true" placeholder="请输入您的手机号码"> 		               
		            </div>
		        </li>
		    	<li>
		            <div class="infor_l">
		                <span>选择区域</span>
		                <?php 
						   echo Html::dropDownList('area', '',
								ArrayHelper::map($provinceData, 'id','name'), 
								[
									'id'=>'area',
									'class'=>'selects',
									'placeholder'=>"请选择",
									'data-required'=>"true",
								]
						   );
						   ?> 
		                 
		            </div>
		        </li>
		        <li class="infor_li">
		            <div class="infor_l">
		                <span>详细地址</span>                
		                <input data-required="true" name='address' placeholder="请输入小区名称或地址">
		            </div>
		        </li> 
		    </ul>
		    
		   <ul class="infor" style="margin-top:30px;"> 
		    	<li>
		            <div class="infor_l">
		                <span>快递费(到付)</span>		                 
		                <span>
		                   <div class="select-area">
		                   <!--<i class="select-value" style="color:red;">￥8</i>-->
		                   </div>
						</span>
		            </div>
		        </li>
		         <!--<li>
		            <div class="infor_l">
		            	<a href="#"><img src="images/"></a>
		                <span>到付</span>
		               <span>微信支付<i style="color:#8E8E93;font-size:12px;">(仅支持微信支付)</i></span>	
                      <input type="radio" style="width:15px;margin-right:15px;float: right;margin-top:15px;"> 					
		            </div>
		        </li>-->
		    </ul>
		</section>
<?php yii\widgets\ActiveForm::end()?>	
<footer>
    <div class="zhen">
        <a id='kuaidi' href="javascript:void(0)">发送快递</a>
    </div>
</footer>
<script>
$(document).ready(function(){
	$('input[data-required="true"],select[data-required="true"]').keyup(function(){
			if($(this).val()==''){
				$(this).css("color","red")
			}else{
				$(this).css("color","")
			}
		}) 
		 
		
        $('#kuaidi').click(function(){
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
					/*case "address":
						var address = /^(\w*[\u4E00-\u9FFF]+\w*)*$/;
						if(address.test(value) == false){
							$(this).css("color","red").focus();
							flag=false;
							alert("请输入正确的地址！")
							return false;
						}
					break;*/
					case "phone":
						var phone = /^(0|86|17951)?1[3|4|5|7|8]\d{9}$/;
						if(phone.test(value) == false){
							$(this).css("color","red").focus();
							flag=false;
							alert("请输入正确的手机格式！")
							return false;
						}
					break;
				}
				
            })
            if(!flag){
                return;
            }
			
            var url = "<?php echo yii\helpers\Url::toRoute('/property/expressdata')?>";
            $.post(url,$('#expressform').serialize(),function(json){
				console.log(json)
                  if(json.code == '0000'){
                      alert(json.msg);
                      window.location = "<?php echo yii\helpers\Url::toRoute(['/property/expressok','id'=>''])?>"+json.result.jid;
                  }else{
                      alert(json.msg);
                  }
            },'json')
        })
})
</script>