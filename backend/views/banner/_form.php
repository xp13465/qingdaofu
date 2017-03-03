<?php
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Banner;

?>

<div class="banner-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'type')->dropDownList(Banner::$type, []) ?>
	<?= $form->field($model, 'title')->textInput(['maxlength' => true,'placeholder' => '']) ?>
	<?= $form->field($model, 'url')->textInput(['maxlength' => true,'placeholder' => 'http://']) ?>
	
    <?= $form->field($model, 'fileid')->label('图片&nbsp;&nbsp;&nbsp;&nbsp;<a class="add_tu_05 btn btn-info btn-xs" inputName="banner-fileid" >上传</a>')->hiddenInput(['maxlength' => true]) ?>
	<img id='bannerImg' class='imgView' src="<?=$model->file?>" style='width:300px;<?=$model->file?"":"display:none"?>' />
    <?= $form->field($model, 'file')->label("")->hiddenInput(['maxlength' => true]) ?>
	
   
	<?= $form->field($model, 'target')->dropDownList([ '0' => '本页', '1' => '新窗口', ], []) ?>
    <?= $form->field($model, 'sort')->textInput(['placeholder' => '数字越大越靠前']) ?>
	<?= $form->field($model, 'starttime')->widget(
		DateTimePicker::className(), [
			'type' => 2, 
			"options"=>['placeholder' => '不填为不失效'],
			'pluginOptions' => [
				'todayHighlight' => true,
				'autoclose' => true,
				'format' => 'yyyy-mm-dd hh:ii',
			]
	]);?> 
	<?= $form->field($model, 'endtime')->widget(
		DateTimePicker::className(), [
			'type' => 2, 
			"options"=>['placeholder' => '不填为不失效'],
			'pluginOptions' => [
				'todayHighlight' => true,
				'autoclose' => true,
				'format' => 'yyyy-mm-dd hh:ii',
			]
	]);?> 
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? "添加" : "保存", ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<input  style='display:none' type="file" name="Filedata" id='id_photos' value="" />
<script>
$(document).ready(function(){
	 
	
	$(".add_tu_05").click(function(){
		var limit = $(this).attr('limit')?$(this).attr('limit'):1;
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
		var aa = $("#"+inputName).val();
		if(!inputName)return false;
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
					$("#"+inputName).val(data.fileid).trigger("change"); 
					var str = '';
					var div =' <li><i class="closebutton" data_bid='+data.fileid+'  style="position:absolute;"><img src="/images/hint33.png" width="35" height="35"></i><p><img src='+data.url+' width="120" height="120"></p></li>';
					$("#banner-file").val(data.url); 
					$("#bannerImg").attr("src",data.url).show(); 
				}else if(data.msg){
					layer.alert(""+data.msg)
				}
			},
			error:function(){
				layer.close(index)
			}
		}); 
	});
	$(document).on("click",".per-r p img:not(.add_tu_05)",function(){
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
			// area: ['80%', 'auto'],
			photos: photojson
		});
    })
	

});
</script>