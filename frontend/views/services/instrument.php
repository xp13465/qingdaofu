<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use yii\widgets\ActiveForm;  
?>
<div class="services">
  <div class="content clearfix">
    <div class="content_right" style="">
      <div class="right-top" style="width:1170px;margin-left:30px;">
        <div class="right-topl">
		<p>快捷文书</p>
		</div>
        <div class="right-topr" >
          <p>在线提交申请后，我们的专业律师将在1个工作日内与您联系</p>
        </div>
      </div>
      <?php 
		$form = ActiveForm::begin([
			'id' => 'financing', 
		]); 
	  ?>
        <input type="hidden" name="_csrf" value="NnFQMkZoLnhPNTd8ID97LGIcJloDXnceaQU7RzUfSUEFC31WCydgHg==">
        <div class="detail pl" style="display: block;">
          <ul>
            <li> 
			<span><i class="red">*</i>期望委托城市</span>
			<?= $form->field($model,'province_id',['options'=>['tag'=>'code'],'template'=>'{input}'])->dropDownList($provinceData,['id' =>'province_id','prompt'=>'请选择...',"style"=>"width:131px;"]);?>
			<?= $form->field($model,'city_id',['options'=>['tag'=>'code'],'template'=>'{input}'])->widget(DepDrop::classname(),['options'=>['prompt'=>'请选择省...','id' =>'city_id',"style"=>"width:131px;"],'pluginOptions'=>['depends'=>['province_id'],'placeholder'=>'请选择...','url'=>url::to(['/services/city'])]]);?>
			<?= $form->field($model,'district_id',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->widget(DepDrop::classname(),['options'=>['prompt'=>'请选择市...',"style"=>"width:131px;"],'pluginOptions'=>['depends'=>['city_id','province_id'],'placeholder'=>'请选择...','url'=>url::to(['/services/area'])]]);?>
			</li>
            <li> 
				<span><i class="red">*</i>详细地址</span> 
				<?= $form->field($model, 'address',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->textInput(['placeholder'=>'例如：xx县xx路xx号','style'=>'width:400px;height:30px;line-height:30px;',]); ?>
			</li>
			<li>
				<span><i class="red">*</i>案件类型</span>
				<?= $form->field($model,'type',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->label('')->dropDownList($model::$type,['prompt' =>'请选择','style' =>'width:407px']);?>
			</li>
			<li> 
				<span style="vertical-align: top;line-height:22px"><i class="red">*</i>案情描述</span> 
				<?= $form->field($model, 'desc',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->textarea(['placeholder'=>'请详细描述您的委托需求，我们会为您推荐该领域的资深律师与您洽商案件并为您撰写文书。例如：2015年9月，朋友找我借了10万元，并约定2个月内还清。但至今未还，我想打官司，但不知道文书怎么写，想请个律师帮写','style'=>'width:395px;height:120px;padding:0 5px;line-height: 22px;',]); ?>
			</li>
			
			 <li> 
				<span><i class="red">*</i>原告姓名</span> 
				<?= $form->field($model, 'plaintiff',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->textInput(['placeholder'=>'如有多名请用“；”隔开。例如：张三；李四；王五','style'=>'width:400px;height:30px;line-height:30px;',]); ?>
			</li>
			 <li> 
				<span><i class="red">*</i>被告姓名</span> 
				<?= $form->field($model, 'defendant',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->textInput(['placeholder'=>'如有多名请用“；”隔开。例如：张三；李四；王五','style'=>'width:400px;height:30px;line-height:30px;',]); ?>
			</li>
			 <li> 
				<span><i class="red">*</i>联系人</span> 
				<?= $form->field($model, 'contacts',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->textInput(['placeholder'=>'联系人','style'=>'width:400px;height:30px;line-height:30px;',]); ?>
			</li>
			 <li> 
				<span><i class="red">*</i>手机号码</span> 
				<?= $form->field($model, 'tel',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->textInput(['placeholder'=>'联系方式','style'=>'width:400px;height:30px;line-height:30px;',]); ?>
			</li>
			 
          </ul>
          <div class="form-group field-policy-fayuan_name">
            <input type="hidden" id="policy-fayuan_name" class="form-control" name="Policy[fayuan_name]" value="">
            <div class="help-block"></div>
          </div>
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
						close:2,
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
	background:url(/bate2.0/images/close.png) no-repeat;
}
.layui-layer-setwin .layui-layer-close2:hover{background-position:right 12px top 0px;}
</style>