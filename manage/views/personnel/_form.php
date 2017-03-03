<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\AutoComplete;

/* @var $this yii\web\View */
/* @var $model app\models\Personnel */
/* @var $form yii\widgets\ActiveForm */
// echo "<pre>";
// print_r($organization);die;
?>
<style>
.form-group {
    margin-bottom: 15px;
	margin-top: 13px;
}
</style>
<div class="personnel-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'name')->label('姓名')->textInput(['maxlength' => true,'style'=>'width:500px']) ?>
	<?= $form->field($model, 'job')->label('职业描述')->textInput(['maxlength' => true,'style'=>'width:500px']) ?>
	<input type="button" value="上传图像" id="file"  class='attach_file' limit = '5' attach_file="attach_file" width="120" height="120" >
	<?php if(isset($model->files)&&$model->files){
		echo '<p class="files"></p><p><img class="closebutton" src="/images/hint33.png" style="position: absolute;z-index: 20;display: block;height: 37px; width: 37px;margin-top: 10px;" /><img  class="imageWxview" style="margin-top: 10px;max-width:100px; padding: 5px; border: 1px solid #cccccc;" src="'.$model->files->file .'" align="absmiddle"></p>';
	}else{
		echo "<p class='files'></p>";
	}?>
	
	<?= Html::hiddenInput('Personnel[headimg]',isset($model->headimg)?$model->headimg:'',['id'=>'personnel_headimg'])?>
	<?= $form->field($model, 'email')->label('邮箱')->textInput(['maxlength' => true,'style'=>'width:500px']) ?>
	<?= $form->field($model, 'mobile')->label('联系方式')->textInput(['maxlength' => true,'style'=>'width:500px']) ?>
	<?= $form->field($model, 'tel')->label('固话')->textInput(['maxlength' => true,'style'=>'width:500px']) ?>
	<?= $form->field($model, 'address')->label('联系地址')->textInput(['maxlength' => true,'style'=>'width:500px']) ?>
	<?= $form->field($model, 'office')->label('办公地点')->textInput(['maxlength' => true,'style'=>'width:500px']) ?>
	<?= $form->field($model, 'parentid',['options'=>['tag'=>'label'],'template'=>'直属上级领导']); ?> 
	<?= $form->field($model,'username',['options'=>['tag'=>'div'],'template'=>'{input}'])->label('')->widget(\yii\jui\AutoComplete::classname(), [
					'clientOptions' => [
						'source' => "/personnel/autocomplete",
					],
					"options"=>["class"=>"form-control",'style'=>'width:170px;display:inline-block'],
					'clientEvents' => [
					'select'=>'function(event,ui){
						$("#personnel-parentid").val(ui.item.personnel_id);
						$("span.field-personnel-username label").html(ui.item.name);
						}',
					'change'=>'function(event,ui){
			            if(ui.item){
					    $(".field-personnel-username label").html(ui.item.name);
				     }else{
					    $("span.field-personnel-username label").html("");
				     }  
			         }',
					],
				]) ?>
	<?= $form->field($model, 'username',['options'=>['tag'=>'span'],'template'=>'{input}{label}'])->label($model->username?$model->username->name:'')->hiddenInput() ?>
	<?= $form->field($model,'parentid',['options'=>['tag'=>'div'],'template'=>'{input}'])->hiddenInput(['maxlength' => true]) ?>
	<?= $form->field($model,'checkin')->checkbox() ?>
	<?= $form->field($model, 'organization_id')->label('机构')->dropDownList(ArrayHelper::map($organization,'organization_id','organization_name'), ['prompt' => '请选择','style'=>'width:170px;border-radius: 4px;']) ?>
	
	<?= $form->field($model, 'department_id',['options'=>['tag'=>'label'],'template'=>'部门']); ?>
	<?= Html::dropDownList(
		'Personnel[department_id]',$model->department_pid?$model->department_pid:'',
		[""=>""],
		['id'=>'personnel-department_id','class'=>'form-control','prompt' => '请选择','style'=>'width:170px;border-radius:4px;']
	)?>
	<?= Html::dropDownList(
		'Personnel[department_pid]',$model->department_id?$model->department_id:'',
		[""=>""],
		['id'=>'personnel-department_pid','prompt' => '请选择','class'=>'form-control','style'=>'width:170px;border-radius: 4px; margin: -34px 0px 11px 179px;display:none;']
	)?>
	<?= $form->field($model, 'post_id')->label('岗位')->dropDownList([], ['prompt' => '','style'=>'width:170px;border-radius: 4px;']) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<input  style='display:none' type="file" name="Filedata" id='id_photos' value="" />
<script>
$(document).ready(function(){
	var department_pid = "<?= isset($model->department_pid)&&$model->department_pid?$model->department_pid:'0'?>";
	var department_ids = "<?= isset($model->department_id)&&$model->department_id?$model->department_id:'0'?>";
	var post_id = "<?= isset($model->post_id)&&$model->post_id?$model->post_id:''?>";
	var username = "<?= isset($model->parent->name)&&$model->parent->name?$model->parent->name:'' ?>";
	if(username){
		$('#personnel-username').val(username);
	};
	
	$('#personnel-organization_id').change(function(){
		var organization_id = $(this).val();
		var types = 1;
		var name = 'Department';
		$.ajax({
			url:"<?= yii\helpers\Url::toRoute('/personnel/hierarchy')?>",
			type:'post',
			data:{organization_id:organization_id,name:name,types:types},
			dataType:'json',
			success:function(json){
				if(json['code'] == '0000'){
					var html = '<option value="0" selected="">请选择</option>';
					$('#personnel-department_id').html(html + json['data']).val(department_pid).trigger("change");
				}
				
			}
		})
	}).trigger("change");

	$('#personnel-department_id').change(function(){
		var department_id = $('#personnel-department_id').val();
		var organization_id = $('#personnel-organization_id').val();
		var name = 'Department';
		$.ajax({
			url:"<?= yii\helpers\Url::toRoute('/personnel/hierarchy')?>",
			type:'post',
			data:{department_id:department_id,name:name,organization_id:organization_id},
			dataType:'json',
			success:function(json){
				var html = "<option value='0'>请选择</option>";
				if(json['code'] == 'pid'){
					$('#personnel-post_id').html(html+json['post']).val(post_id);
					$('#personnel-department_pid').html(html + json['data']).css('display','block').val(department_ids).trigger("change");
				}else if(json['code'] == 'post'){
						$('#personnel-department_pid').css('display','none').html('');
						$('#personnel-post_id').html(html+json['data']).val(post_id);
				}
			}
		})
	}).trigger("change");
	
	// $('#personnel-username').click(function(){
		// var username = $(this).val();
		// if(username){
			// $(this).attr('disabled','disabled');
		// }
	// })
	
	// if(!department_pid){
		// $('#personnel-department_pid').trigger("change");
	// }
	$('#personnel-department_pid').change(function(){
		var department_id = $('#personnel-department_id').val();
		var name = 'Post';
		$.ajax({
			url:"<?= yii\helpers\Url::toRoute('/personnel/hierarchy')?>",
			type:'post',
			data:{department_id:department_id,name:name},
			dataType:'json',
			success:function(json){
				var html = '<option value="0" selected="">请选择</option>';
				if(json['code'] == '0000'){
					$('#personnel-post_id').html(html+json['data']['html']).val(post_id);
				}
			}
		})
	}).trigger("change");
	
	$(document).on("click",".closebutton",function(){
                $('#personnel_headimg').val('');
				$(this).parent().remove();
    });
	
	 //照片异步上传
	$(document).on('click',".attach_file",function(){
		var limit = $(this).attr('limit')?$(this).attr('limit'):2;
		var attach_file = $(this).attr('attach_file')?$(this).attr('attach_file'):'';
		if(!attach_file)return false;
		$("#id_photos").attr({"attach_file":attach_file,"limit":limit}).click();
	})
	
	$(document).on("change",'#id_photos',function(){ //此处用了change事件，当选择好图片打开，关闭窗口时触发此事件
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});
		var attach_file = $(this).attr('attach_file');
		var limit = $(this).attr('limit')?$(this).attr('limit'):2;
		var data_type = $(this).attr('data_type');
		$.ajaxFileUpload({
			url:"<?php echo yii\helpers\Url::toRoute(['/site/upload','filetype'=>1,'_csrf'=>Yii::$app->getRequest()->getCsrfToken()])?>",
			type: "POST",
			secureuri: false,
			fileElementId: 'id_photos',
			data: {'_csrf':'<?=Yii::$app->getRequest()->getCsrfToken()?>'},
			textareas:{},
			dataType: "json",
			success: function (data) {
				layer.close(index) 
				if(data.error == '0'&&data.fileid){	
					 var aa = $("#album-album_list").val();
						$("#"+attach_file).val(data.fileid).trigger("change");
						$("#personnel_headimg").val(data.fileid);
						var div = '<p><img class="closebutton" src="/images/hint33.png" style="position: absolute;z-index: 20;display: block;height: 37px; width: 37px;margin-top: 10px;" /><img  class="imageWxview" style="margin-top: 10px;max-width:100px; padding: 5px; border: 1px solid #cccccc;" src="'+data.url+'" align="absmiddle"></p>';
						$(".files").html(div);	
                    
				}else if(data.msg){
					layer.alert(""+data.msg)
				}
			},
			error:function(){
				layer.close(index)
			}
		}); 
	 });
	$(document).on("click",".imageWxview",function(){
		var   photojson = {
			"status": 1,
			"msg": "查看",
			"title": "查看",
			"id": 0,
			"start": 0,
			"data": []
		};
		photojson.data.push({
			"alt": "",
			"pid": 0,
			"src": $(this).attr("src"),
			"thumb": ""
		})
		 
		layer.photos({
			area: ['auto', '80%'],
			photos: photojson
		});
    })
})
</script>
