<?php
use manage\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Department;
use app\models\Post;
use yii\jui\AutoComplete;
?>

<div class="form-form">
<?php
    $form = ActiveForm::begin([
        'id' => 'form-form',
        'validateOnBlur' => false,
    ])
    ?>

    <div class="row">
    <div class="col-md-9">
    <div class="panel panel-default">
    <div class="panel-body">
	<input type="button" value="上传图像" id="file"  class='attach_file' limit = '5' attach_file="attach_file" width="120" height="120" >
	<?php if(isset($model->files)&&$model->files){
		echo '<p class="files"></p><p><img class="closebutton" src="/images/hint33.png" style="position: absolute;z-index: 20;display: block;height: 37px; width: 37px;margin-top: 10px;" /><img  class="imageWxview" style="margin-top: 10px;max-width:100px; padding: 5px; border: 1px solid #cccccc;" src="'.$model->files->file .'" align="absmiddle"></p>';
	}else{
		echo "<p class='files'></p>";
	}?>
	<?= Html::hiddenInput('Admin[headimg]',isset($model->headimg)?$model->headimg:'',['id'=>'Admin_headimg'])?>
    <?= $form->field($model, 'username')->textInput($this->context->action->id === 'update' ? ['disabled' => 'disabled'] : []) ?>
    <?= $form->field($model, 'password_hash')->passwordInput(['value' => '']) ?>
    <?php //= $form->field($model, 'group')->dropDownList(ArrayHelper::map($html,'id','name'), ['prompt'=>'请选择']) ?>
    <?php //=$form->field($model, 'post_id')->dropDownList(ArrayHelper::map(Post::find()->all(), 'id', 'name'), ['prompt'=>'请选择']) ?> 
	
	<?= $form->field($model, 'personnelid',['options'=>['tag'=>'div'],'template'=>'{label}'])->label("员工")->hiddenInput() ?>
	
	<?php 
	echo AutoComplete::widget([
	    'name' => 'selectSearch',
	    'value' => $model->personnel?$model->personnel->name:'',
		'clientOptions' => [
			'source' => "/personnel/autocomplete",
		],
		"options"=>["class"=>"form-control",'style' => "width:300px;display:inline-block"],
		'clientEvents' => [
		   'select'=>'function(event,ui){
			   $("#admin-personnelid").val(ui.item.personnel_id);
			   $("span.field-admin-personnelid label").html(ui.item.name);
			}',
			'change'=>'function(event,ui){
			    if(ui.item){
					$("#solutionseal-personnel_id").val(ui.item.personnel_id);
					$(".field-solutionseal-personnel_id label").html(ui.item.name);
				}else{
					$("#admin-personnelid").val(0);
					$("span.field-admin-personnelid label").html("");
				}  
			}',
		],
	]);
	
	?>
	<?= $form->field($model, 'personnelid',['options'=>['tag'=>'span'],'template'=>'{input}{label}'])->label( $model->personnel?$model->personnel->name:'')->hiddenInput() ?>
	<?= $form->field($model,'status')->dropDownList(\manage\models\Admin::getStatusList(), ['prompt'=>'请选择']) ?>
    </div>
    </div>
    </div>


    
    <div class="col-sm-12">

    <div class="form-group">
<?php if ($model->isNewRecord): ?>
    <?= Html::submitButton(Yii::t('zcb', 'Create'), ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('zcb', 'Cancel'), ['/admin/index'], ['class' => 'btn btn-default',]) ?>
<?php else: ?>
    <?= Html::submitButton(Yii::t('zcb', 'Save'), ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('zcb', 'Delete'), ['/admin/index', 'id' => $model->primaryKey], [
        'class' => 'btn btn-default',
        'data' => [
            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'method' => 'post',
        ],
    ]) ?>
<?php endif; ?>
    </div>

    </div><!-- col-sm120 -->
        
    </div><!-- row -->

<?php ActiveForm::end(); ?>
    </div>
<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<input  style='display:none' type="file" name="Filedata" id='id_photos' value="" />
<script>
$(document).ready(function(){
	var post_id = "<?= isset($model->post_id)&&$model->post_id?$model->post_id:'0'?>";
	var personnelid = "<?= isset($model->personnelid)&&$model->personnelid?$model->personnelid:'0'?>";
$('#admin-group').change(function(){
		var department_id = $('#admin-group').val();
		var organization_id = $('#personnel-organization_id').val();
		var name = 'Department';
		$.ajax({
			url:"<?= yii\helpers\Url::toRoute('/personnel/hierarchy')?>",
			type:'post',
			data:{department_id:department_id,name:name,organization_id:organization_id},
			dataType:'json',
			success:function(json){
				var html = '<option value="0" selected="">请选择</option>';
			   $('#admin-post_id').html(html+json['data']).val(post_id).trigger("change");
			}
		})
	}).trigger("change");
	
$('#admin-post_id').change(function(){
		var post_id = $('#admin-post_id').val();
		$.ajax({
			url:"<?= yii\helpers\Url::toRoute('/admin/staff')?>",
			type:'post',
			data:{post_id:post_id},
			dataType:'json',
			success:function(json){
				var html = '<option value="0" selected="">请选择</option>';
			   $('#admin-personnelid').html(html+json['data']['html']).val(personnelid);
			}
		})
	}).trigger("change");
	
	
	
	
	
$(document).on("click",".closebutton",function(){
                $('#Admin_headimg').val('');
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
						$("#Admin_headimg").val(data.fileid);
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
