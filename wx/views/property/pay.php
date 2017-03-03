<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
use yii\helpers\ArrayHelper;
?>

<div style="height:50px;width:50px;position:fixed;bottom:50px;z-index:99999;">
    <a href="<?php echo yii\helpers\Url::toRoute('/site/index')?>"><img src="/images/back_home.png"></a>
</div>
<header>
    <div class="cm-header">
        <span class="icon-back" ></span>
        <i>支付中</i>
    </div>
</header>
<script type="text/javascript">
    $(document).ready(function () {
        $('.icon-back').click(function () {
			<?php if(isset($prevpage)&&$prevpage){?>
			location.href='<?=$prevpage?>';
			<?php }else{ ?>
            history.go(-1);
			<?php }?>
        });
    });
</script>

<script type="text/javascript" src="/js/layer/layer.js" ></script>
<style>
.layui-layer-setwin a {border-top: 1px solid #ddd;top: 160px;position: relative;width: 300px !important;height: 36px !important;overflow: hidden;background:none;}
.layui-layer-rim {border:none !important;border-radius: 5px;box-shadow: none;}
.layui-layer-title {padding: 0 80px 0 20px;height: 42px;line-height: 42px;border-bottom: 1px solid #eee;font-size: 14px;color: #333;overflow: hidden;background-color: #F8F8F8;border-radius: 2px 2px 0 0;}
.layui-layer-setwin {position: absolute;right: 1px !important;top: 15px;}
.layui-layer-btn {text-align: center;padding: 0 10px 12px;pointer-events: auto;}
.layui-layer-btn .layui-layer-btn0 {border-color:#fff;background-color:#fff;color:#4898d5;}
.layui-layer-btn a {
	height: 28px;
	line-height: 28px;
	margin: 0 6px;
	padding: 0 15px;
	/* border: 1px solid #dedede; */
	/* background-color: #f1f1f1; */
	color: #333;
	border-radius: 2px;
	font-weight: 400;
	cursor: pointer;
	text-decoration: none;
}
.layui-layer-title {
	padding: 0;
	height: 42px;
	line-height: 42px;
	border-bottom: 1px solid #eee;
	font-size: 16px;
	color: #fff;
	overflow: hidden;
	text-align:center;
	background-color: #0da3f8;
	border-radius: 2px 2px 0 0;
}
</style>
<section>     
 <?php yii\widgets\ActiveForm::begin(['id'=>'propertyform'])?>
		   <ul data-catr="10">
                <li class="examine">
                    <p>
                       <span><?=$data['phone']?></span>
                       <a href ='<?php echo yii\helpers\Url::toRoute(['/property/edit','id'=>$data['id']])?>' ><span class="time01" style="color:#0da3f8;">编辑</span></a>
                    </p>
                    <p>
                       <span class="time02"><?=$data['address']?></span>
                    </p>
                </li> 
               </ul>
		    <ul class="infor" style="margin-top:12px;"> 
		    	<li>
		            <div class="infor_l">
		                <span>订单金额</span>		                 
		                <span>
		                   <div class="select-area">
		                   <i class="select-value" style="color:red;">￥<?=$data['money']?></i>
		                   </div>
						</span>
		            </div>
		        </li>
		        
		    </ul>
		      <ul class="infor" style="margin-top:12px;"> 
		           <li>
			            <div class="infor_l">
			                <span>服务时间</span>
			                <input type="text" name="money" readonly style='font-size: 15px' placeholder="工作日9:00-16:30"> 
			                <span style="padding-top:10px;">		                   
			                   <img src="/images/tips.png" class="time">		                   
							</span>
			            </div>
		            </li>
		        </ul>
		        <ul class="infor" style="margin-top:12px;"> 
			        <li>
			            <div class="infor_l">	
			            	<a href="#" class="rz_ig09"></a>
			                <span>微信支付<i style="color:#8E8E93;font-size:12px;">(仅支持微信支付)</i></span>	
	                      <input type="radio" checked=checked style="width:15px;margin-right:15px;float: right;margin-top:20px;"> 					
			            </div>
			        </li>
		        </ul>
<?php yii\widgets\ActiveForm::end()?>				
		</section>
    <footer>
    <div class="zhen">      	
        <div  id="zl">总计<i style="color:red;">￥<?=$data['money']?>元</i></div>
        <button id="zr">确认支付</button>
    </div>
    </footer>
	
	  
<script>
		function onBridgeReady(paydata){ 
		   WeixinJSBridge.invoke(
			   'getBrandWCPayRequest', paydata,
			   function(res){
				   if(res.err_msg == 'get_brand_wcpay_request:cancel') {
					location.reload();
						alert("您已取消了此次支付");
						return;
					} else if(res.err_msg == 'get_brand_wcpay_request:fail') {
						alert("支付失败,请重新尝试");
						return;
					} else if(res.err_msg == 'get_brand_wcpay_request:ok') {
						alert("支付成功！");
						window.location = "<?php echo yii\helpers\Url::toRoute(['/property/addok','id'=>$data['id']])?>";
					} else {
						alert("未知错误"+res.error_msg);
						return;
					} 
			   }
		   ); 
		}
													
		 
		$(document).ready(function(){
			
			layer.alert('<p><img src="/images/ze.png" width="276" height="150"></p> 尊上,请您确定你填写的地址与产证是否一致，若因填写产证地址模糊错误等导致无法拉取产调，小管家只退10元噢。', {
			  area: ['300px', '350px'], //宽高
			  title:'',
			  btn:'朕知道了', 
			  shadeClose:true,
			  closeBtn :0,
			  skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
			  
			  
			})	
			$(".time").click(function(){
				layer.open({
					type: 1,
					title: '服务时间',
				  //skin: 'layui-layer-rim', //加上边框
					area: ['300px', '220px'], //宽高
					btn:'朕知道了', 
					content: '<p class="prompt_text prompt_text_bold"  style="padding:15px;color:#000;font-weight:bold;">交易中心接单时间为：上午<span class="emphasis" >09:00-11:30</span>、下午<span class="emphasis" >13:30-16:30</span>如周六周日下单，顺延至下个工作日，请谅解！</p>'
				});											
			}) 
			$("#zr").click(function(){	
				// alert("支付接口开发中");
				var nowTime = new Date().getTime();
				var clickTime = $(this).attr("ctime");
				if( clickTime != 'undefined' && (nowTime - clickTime < 2000)){
					alert('操作过于频繁，稍后再试');
					return false;
				}else{
					$(this).attr("ctime",nowTime);
				}
				var loadindex = layer.load(1, {
				  shade: [0.4,'#fff'], //0.1透明度的白色背景
				  time: 3000
				});
				$.ajax({
					 url:'<?php echo yii\helpers\Url::toRoute("/property/paydata")?>',
					 type:'post',
					 data:{id:'<?=$data['id']?>',openid:'<?=$openid?>'},
					 dataType:'json',
					 // async:false,
					 success:function(json){ 
						    if(json.code == '0000'){
								onBridgeReady(json.result.paydata); 
						    }else{
							    alert(json.msg);
						    }
					 }
				 })
				 
			})
		});
	</script>