<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
use yii\helpers\ArrayHelper;
// $provinceData = array_merge([""=>"请选择区域"],ArrayHelper::map($provinceData, 'id','name'));
?>
<style>
.selects{
    height: 40px;
	// position:absolute;
    width: 60%;
	
	// border:1px solid #ccc;
	background:none;
	color:#000; 
	background:url('/images/sj.png') right no-repeat;
}

</style>

<div style="height:50px;width:50px;position:fixed;bottom:50px;z-index:99999;display:none">
    <a href="<?php echo yii\helpers\Url::toRoute('/site/index')?>"><img src="/images/back_home.png"></a>
</div>
<header>
    <div class="cm-header">
        <span class="icon-back" ></span>
        <i>编辑信息</i>
		<a class="editsave" href="javascript:void(0)">保存</a>
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

<style>
.selects{
    height: 40px;
	// position:absolute;
    width: 60%;
	
	// border:1px solid #ccc;
	background:none;
	color:#888; 
	background:url('/images/sj.png') right no-repeat;
}
.infor_l input{width:60%}
.prompt_div{
	background:none;
	padding-left:0;
}
</style>
<section>     
 <?php yii\widgets\ActiveForm::begin(['id'=>'propertyform'])?>
 <input type='hidden' name='id' value='<?=$id?>'/>
		    <ul class="infor" style="margin-top:12px;"> 
				<li>
		            <div class="infor_l">
		                <span>产调类型</span>
		                <?php 
						   echo Html::dropDownList('type', $data['type'],
								//["1"=>"交易中心版","2"=>"电子版"],
								["2"=>"电子版"],
								[
									'class'=>'selects',
									'id'=>"type",
									'placeholder'=>"请选择",
								]
						   );
						   ?> 
		            </div>
		        </li>
		    	<li>
		            <div class="infor_l">
		                <span>产证区域</span>
		                <?php 
						   echo Html::dropDownList('area', $data['city'],
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
		                <span>产证地址</span>                 
						<?= Html::input('text','address',$data['address'],['placeholder'=>"请输入详细地址",'data-required'=>"true",])?>
		            </div>
		        </li> 
		         <li class="infor_li">
		            <div class="infor_l">
		                <span>电话号码</span>                 
						 <?= Html::input('text','phone',$data['phone'],['placeholder'=>"请输入电话号码",'data-required'=>"true",])?>
		            </div>
		        </li>
				<li class="infor_li">
		            <div class="infor_l">
		                <span>产权人姓名</span>                 
						<?= Html::input('text','name',$data['name'],['placeholder'=>"选填",'id'=>"name",'data-required'=>"false",])?>
		            </div>
		        </li> 
		    </ul>	
<?php yii\widgets\ActiveForm::end()?>	 
<div class='prompt_div'>
<!--p class='prompt_text'>1. 长宁，闸北，闵行，普陀，静安，虹口，松江，青浦，崇明接受交易中心版订单。</p>
<p class='prompt_text'>2. 杨浦需提供产权人姓名。</p>
<p class='prompt_text'>3. 其他区域不接受交易中心版订单，请您谨慎下单，或致电客服:15021476881。</p-->
<p class='prompt_text'>电子版需地址＋产证编号。另电子版本不接急单，请谨慎下单~</p>
<!--<p class='prompt_text'>4. 如需"<b>非交易中心版本</b>"可在下单时产调地址后备注(<b>电子版</b>)，或致电客服:15021476881。</p>

	<!--<p class='prompt_text'>1.小管家人肉【交易中心版本】若有延误请谅解！</p>
	<p class='prompt_text'>2.因宝山，浦东，虹口限制查阅量<span class='emphasis' >2</span>日内出产调！如情况紧急可以联系小管家，提供<span class='emphasis' >【非交易中心版本】</span></p>
	<p class='prompt_text'>3.交易中心接单时间为：上午<span class='emphasis' >09:00-11:30</span>、下午<span class='emphasis' >13:30-16:30</span><br/>如周六周日下单，顺延至下个工作日，请谅解！</p>
-->
	</div>	
		</section>

	  
<script type="text/javascript">



    $(document).ready(function(){
		$('input[data-required="true"],select[data-required="true"]').keyup(function(){
			if($(this).val()==''){
				$(this).css("color","red")
			}else{
				$(this).css("color","")
			}
		}) 
		
		$("#type").change(function(){
			if($(this).val()==2){
				// $("#zl i").html("￥60元");
				$("#name").prev().html("产证编号");
			}else{
				// $("#zl i").html("￥25元");
				$("#name").prev().html("产权人姓名");
			}
		})
		
        $('.editsave').click(function(){
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
			var loadindex = layer.load(1, {
			  shade: [0.4,'#fff'], //0.1透明度的白色背景
			  time: 2000
			});
            var url = "<?php echo yii\helpers\Url::toRoute('/property/editdata')?>";
            $.post(url,$('#propertyform').serialize(),function(json){
                  if(json.code == '0000'){
                      // alert(json.msg);
                      window.location = "<?php echo yii\helpers\Url::toRoute(['/property/pay','id'=>''])?>"+json.result.id;
                  }else{
                      alert(json.msg);
                  }
            },'json')
        })
		// layer.alert("<p style='font-weight:normal;'>上海市房地产交易中心10月1号到10月7号放假七天，10月8号正常上班！<br/>清道夫债管家放假时间与房产交易中心一致，另9月30日14:00之后的订单将在节后统一处理，敬请谅解!<br/>在此祝大家国庆节快乐！</p>",{title:'重要通知！'})
		// $("#area").change(function(){
			// if($(this).val()=='2704'||$(this).val()=='2710')layer.alert("原闸北及静安系统尚未合并，查询原闸北区域的房产，请选择闸北区，选错区域导致的退单我们会扣除相应的手续费，请谨慎下单！",{title:'温馨提示'})
			// if($(this).val()=='2707')layer.alert("浦东交易中心因人流较大，限制查阅量，本周出单时间会有所延长，预计出单时间1个工作日，请安排好时间，谨慎下单！",{title:'温馨提示'})
			// if($(this).val()=='2717')layer.alert("宝山交易中心因限流，故产调时间小管家无法估算。请您谨慎下单。谢谢~",{title:'温馨提示'})
			// if($(this).val()=='2708')layer.alert("杨浦交易中心已系统升级，所有查阅房产必须提供权利人（之一）姓名，请将权利人姓名填写在房屋地址后面，若未能提供相匹配权利人姓名的订单将作为错单受理，扣除相应的费用，请您谨慎下单！ ",{title:'温馨提示'})
			// if($(this).val()=='2719')layer.alert("金山交易中心已系统升级，所有查阅房产必须提供权利人（之一）姓名，请将权利人姓名填写在房屋地址后面，若未能提供相匹配权利人姓名的订单将作为错单受理，扣除相应的费用，请您谨慎下单！ ",{title:'温馨提示'})
			// if($(this).val()=='2716')layer.alert("嘉定交易中心目前暂停接单，恢复时间待通知。嘉定交易中心已系统升级，所有查阅房产必须提供权利人（之一）姓名，请将权利人姓名填写在房屋地址后面，若未能提供相匹配权利人姓名的订单将作为错单受理。",{title:'温馨提示'})
			// if($(this).val()=='2706')layer.alert("因徐汇交易中心系统升级，交易中心版产调暂时不受理，具体恢复时间待通知。如需电子版可在下单时产调地址后备注(电子版)。请您谨慎下单。",{title:'温馨提示'})
			// if($(this).val()=='2720')layer.alert("因奉贤区交易中心系统升级，交易中心版产调暂时不受理，具体恢复时间待通知。如需电子版可在下单时产调地址后备注(电子版)。请您谨慎下单。",{title:'温馨提示'})
			// if($(this).val()=='2718')layer.alert("因青浦交易中心9月22日全天停电，故所有青浦产调9月23日（星期五）出单，请谨慎下单，如需要电子版本可联系小管家。谢谢！",{title:'温馨提示'})
			// if($("#type").val()=="1"){
				// if($(this).val()=='2716' || $(this).val()=='2719'|| $(this).val()=='2706'|| $(this).val()=='2717'|| $(this).val()=='2720'|| $(this).val()=='2713'|| $(this).val()=='2711'|| $(this).val()=='2707'|| $(this).val()=='2714'){
					// layer.alert("此区域暂停接单,默认电子版，请谨慎下单！谢谢",{title:'温馨提示'})
				// }
			// }
		// })
		$("#area,#type").change(function(){
			var type = $("#type").val();
			var area = $("#area").val();
			if(type=="1"){
				if(area=='2716' || area=='2719'|| area=='2706'|| area=='2717'|| area=='2720'|| area=='2713'|| area=='2711'|| area=='2707'|| area=='2714'){
					layer.alert("此区域暂停接单,默认电子版，请谨慎下单！谢谢",{title:'温馨提示'})
				}
			}
		})
    })
</script>
