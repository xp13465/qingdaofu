<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use yii\widgets\ActiveForm;  
?>
<div class="services">
 <div class="content clearfix ">
    <div class="content_right" style="">
      <div class="right-top" style="width:1170px;margin-left:30px;">
        <div class="right-topl" >
			<p class='a'>预约律师</p>
		</div>
        <div class="right-topr">
			<p  class='a'>1.律师工作时间:工作日9:00-18:00；</br>2.律师咨询费用以实际律师收费为准！请知悉！不方便面对面和律师交流的客户，可以与律师电话沟通！</p>
        </div>
      </div>
        <?php 
		$form = ActiveForm::begin([
			'id' => 'financing', 
		]); 
	  ?>
        <div class="detail pl" style="display: block;">
          <ul>
			 <li> 
				<span><i class="red">*</i>预约申请人</span> 
				<?= $form->field($model, 'contacts',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->textInput(['placeholder'=>'联系人','style'=>'width:400px;height:30px;line-height:30px;',]); ?>
			</li>
			 <li> 
				<span><i class="red">*</i>手机号码</span> 
				<?= $form->field($model, 'tel',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->textInput(['placeholder'=>'联系方式','style'=>'width:400px;height:30px;line-height:30px;',]); ?>
			</li>
            <li> 
			<span><i class="red">*</i>所在城市</span>
			<?= $form->field($model,'province_id',['options'=>['tag'=>'code'],'template'=>'{input}'])->dropDownList($provinceData,['id' =>'province_id','prompt'=>'请选择...',"style"=>"width:131px;"]);?>
			<?= $form->field($model,'city_id',['options'=>['tag'=>'code'],'template'=>'{input}'])->widget(DepDrop::classname(),['options'=>['prompt'=>'请选择省...','id' =>'city_id',"style"=>"width:131px;"],'pluginOptions'=>['depends'=>['province_id'],'placeholder'=>'请选择...','url'=>url::to(['/services/city'])]]);?>
			<?= $form->field($model,'district_id',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->widget(DepDrop::classname(),['options'=>['prompt'=>'请选择市...',"style"=>"width:131px;"],'pluginOptions'=>['depends'=>['city_id','province_id'],'placeholder'=>'请选择...','url'=>url::to(['/services/area'])]]);?>
			</li>
			
			<li> 
				<span style="vertical-align: top;line-height:22px"><i class="red">*</i>问题描述</span> 
				<?= $form->field($model, 'desc',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->textarea(['placeholder'=>'请详细描述您要咨询的问题，方便我们为您推荐领域内资深律师，例如：我要咨询投资融资领域','style'=>'width:395px;height:80px;padding:0 5px;line-height: 22px;',]); ?>
			</li>
          </ul>
        </div>
        <input class='btn buttonev01' type="button" value="立即预约" onclick="$(&#39;#financing&#39;).submit()">
		<?php ActiveForm::end();?>
     </div>
   </div>
  </div>
<script id="success" type="template"> 
    <div class="succes">
        <p><img src="/bate2.0/images/suc.png"></p>
        <p class="til">恭喜您！预约成功！</p>
        <p  class="tex">提交成功，我们的工作人员将在第一时间与您取得联系。</p>
        <div class="button">
            <a href="<?=Url::toRoute(["services/agreement"])?>" class="default01" >继续预约</a>
            <a href="<?=Url::toRoute(["services/index"])?>" class="default active">返回上一页</a>
        </div>
    </div>
 </script> 
<script>
$(document).ready(function(){
	setTimeout('$("#province_id").val("310000").trigger("change")',200)
	$('form#financing').on('beforeSubmit',function(e){
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});
		var url = $(this).attr("action");
		$.ajax({
			url:url,
			type:'post',
			data:$('#financing').serialize(),
			dataType:'json',
			success:function(json){
				if(json.code == '0000'){
					layer.close(index);
					layer.open({
					  title:false,
					  scrollbar :false,
					  btn:[],
					  closeBtn:2,
					  area:["405px","auto"],
					  content:$("#success").html()
					  })
			       }else{
						layer.close(index);
						layer.msg(json.msg);
			      }
			},
			error:function(){
				layer.close(index);
			}
		})
	}).on('submit', function(e){
		e.preventDefault();
	});
 });	
	
</script>
<style>
.layui-layer-setwin .layui-layer-close2 {
	position: absolute;
	right: -8px;
	top: 5px;
	width: 30px;
	height: 30px;
	background:url(/bate2.0/images/close1.png) no-repeat;
	background-position:0px px;}
.layui-layer-setwin .layui-layer-close2:hover{background-position:right 10px top 0px;}
</style>