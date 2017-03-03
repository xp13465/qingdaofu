<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Album */
/* @var $form yii\widgets\ActiveForm */
// echo "<pre>";
// print_r($model);die;
?>

<link rel="stylesheet" type="text/css" href="/../css/common.css" />
<!-- 引入百度编辑器 -->
<script type="text/javascript">
    window.UEDITOR_HOME_URL = "/ueditor/";
	
</script>

<script type="text/javascript" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>

<script type="text/javascript">
    window.UEDITOR_CONFIG.initialFrameWidth = 710;
	 window.UEDITOR_CONFIG.initialFrameHeight = 300;
	var ue = UE.getEditor('album-content', {
    autoHeightEnabled: true,
    autoFloatEnabled: true,
});
</script>
<div id="append_parent"></div>
<div class="container" id="cpcontainer">
    <?php $form = ActiveForm::begin(); ?>
	<table class="form_table">
    <tr>
        <td class="tb_title"><?= $form->field($model, 'title',['options'=>['tag'=>'label'],'template'=>'标题']); ?>：</td>
    </tr>
    <tr>
        <td>
		    <?= Html::input('text','Album[title]',$model->title,['maxlength' => true,'style'=>'width:500px;border-radius: 4px;','id'=>'album-title'])?> 		
            <input name="style[bold]" type="checkbox" id="style[bold]" <?php if (isset($style['bold']) && $style['bold'] == 'Y'): ?> checked="checked"<?php endif; ?> value="Y" >&nbsp;&nbsp;<?= '加粗' ?>
            <input name="style[underline]" type="checkbox" <?php if (isset($style['underline']) && $style['underline'] == 'Y'): ?> checked="checked"<?php endif; ?> id="Album[underline]" value="Y" >&nbsp;&nbsp;<?= '下划线' ?>
            <?= Html::input('','style[color]',isset($style['color'])&&$style['color']?$style['color']:'',['class'=>"color{required:false}",'id'=>"style[color]",'size'=>'5']).'&nbsp;&nbsp;颜色'?> 
			<?= $form->field($model,'title',['template'=>'{error}'])->textInput(); ?>

		</td>
    </tr>
	<tr>
        <td class="tb_title"><?= $form->field($model, 'title_second')->label('副标题：')->textInput(['maxlength' => true,'style'=>'width:500px']) ?></td>
    </tr>
	<tr>
        <td class="tb_title"> <?= $form->field($model, 'catalog_id')->label('分类：')->dropDownList([ '0' => '默认新闻（草稿）', '1' => '公告','2' => '公司新闻','3' => '财经资讯','4' => '行业动态'], ['prompt' => '','style'=>'width:170px;border-radius: 4px;']) ?></td>
    </tr>
	<tr>
        <td class="tb_title"> <?= $form->field($model, 'special_id')->label('专题编号：')->dropDownList([ '1' => 'Y', '2' => 'N','3' => 'N','4' => 'N','5' => 'N','6' => 'N', ], ['prompt' => '','style'=>'width:170px;border-radius: 4px;']) ?></td>
    </tr>
	<tr>
        <td class="tb_title"><?= $form->field($model, 'copy_from')->label('来源：')->textInput(['maxlength' => true,'style'=>'width:500px']) ?></td>
    </tr>
	<tr>
        <td class="tb_title"><?= $form->field($model, 'copy_url')->label('来源网址：')->textInput(['maxlength' => true,'style'=>'width:500px']) ?></td>
    </tr>
	<tr>
        <td class="tb_title"><?= $form->field($model, 'redirect_url')->label('跳转链接：')->textInput(['maxlength' => true,'style'=>'width:500px']) ?></td>
    </tr>
	
	<tr>
        <td class="tb_title"><?= $form->field($model,'attach_file',['options'=>['tag'=>'label'],'template'=>'封面图片']); ?>：</td>
    </tr>
    <tr>
        <td>
		    <input type="button" value="请选择文件" id="file"  class='attach_file' data_type="1" limit = '5' attach_file="attach_file" width="120" height="120" >
			<p>
		       <?php if(isset($model->attach_file)&&$model->attach_file){ ?>
					<span style="color:#555">大图：</span>
					<img class="imageWxview" style="max-width:300px; padding: 5px; border: 1px solid #cccccc;" src="<?= $model->files['file'] ?>" align="absmiddle">
					<span style="color:#555">小图：</span>
					<img class="imageWxview" style="max-width:100px; padding: 5px; border: 1px solid #cccccc;" src="<?= $model->files['file'] ?>" align="absmiddle">
			    <?php } ?>
		    </p>
			
			<?= Html::hiddenInput('Album[attach_thumb]',isset($model->attach_thumb)?$model->attach_thumb:'',['id'=>'attach_thumb'])?>
			<?= $form->field($model,'attach_file',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->hiddenInput(['id'=>'attach_file'])?>
		</td>
    </tr>

	<tr>
        <td class="tb_title"><?= $form->field($model,'content',['options'=>['tag'=>'label'],'template'=>'详情描述']); ?>：</td>
    </tr>
    <tr>
        <td>
			<?= Html::textarea('Album[content]',isset($model->content)?$model->content:'',['id'=>'album-content'])?>
			<?= $form->field($model,'content',['template'=>'{error}'])->textInput(); ?>
        </td>
    </tr>
	
	<tr>
        <td class="tb_title"><?= $form->field($model,'introduce',['options'=>['tag'=>'label'],'template'=>'摘要']); ?>：</td>
    </tr>
    <tr>
        <td>
			<?= Html::textarea('Album[introduce]',isset($model->introduce)?$model->introduce:'',['id'=>'album-introduce','style'=>'border-radius: 4px; padding: 5px 2px;border: 1px solid;border-color: #666 #ccc #ccc #666;background: #F9F9F9;color: #333;font-size: 14px;width:500px;height:80px'])?>
			<?= $form->field($model,'introduce',['template'=>'{error}'])->textInput(); ?>
		</td>
    </tr>
	
	<tr>
        <td class="tb_title"><?= $form->field($model,'album_list',['options'=>['tag'=>'label'],'template'=>'组图']); ?>：</td>
    </tr>

	<tr>
        <td class="tb_title">
		<!-- 拖拽文件到此处或者 -->
			<div>
			    <div class="resumable-drop" onclick="jQuery('#id_photos');" ondragenter="jQuery(this).addClass('resumable-dragover');" ondragend="jQuery(this).removeClass('resumable-dragover');" ondrop="jQuery(this).removeClass('resumable-dragover');">
						 <a href="javascript:;" class="resumable-browse" id="albumFile" limit = "5" data_type="2" album_list="album_list">选择文件</a>
			    </div>	
			</div>
			<div class="tu">
				<ul>
					<?php  if(isset($model->album_lists)&&$model->album_lists){ foreach($model->album_lists as $value){  ?>
					   <li style="float: left;height: 127px;margin-right: 85px;margin-top: 20px;width: 50px;">
						   <img class="closebutton" src="/images/hint33.png" style="position: absolute;z-index: 20;display: block;height: 35px; width: 35px;" />
						   <img class="imageWxview"  data_bid='<?= $value['id']?>' src='<?= $value['file'] ?>' width="120" height="120"/>
					   </li>
					<?php } } ?>
				</ul>
			</div>
			<?= Html::hiddenInput('Album[album_list]',isset($model->album_list)?$model->album_list:'',['id'=>'album-album_list']) ?>
			<?= $form->field($model,'album_list',['template'=>'{error}'])->textInput(); ?>
		</td>
    </tr>
	
	<tr>
        <td class="tb_title"><?= $form->field($model, 'tags')->label('标签(逗号或空格隔开)：')->textInput(['maxlength' => true,'style'=>'width:500px']) ?></td>
    </tr>
	
	<tr>
        <td class="tb_title">
		     <?= '收藏数量:&nbsp;&nbsp;'.Html::input('text','Album[favorite_count]',$model->favorite_count,['maxlength' => true,'style'=>'width:111px;border-radius: 4px;','id'=>'album-favorite_count'])?>
			 <?= '查看次数:&nbsp;&nbsp;'.Html::input('text','Album[view_count]',$model->view_count,['maxlength' => true,'style'=>'width:111px;border-radius: 4px;','id'=>'album-view_count'])?>
			 <?= '排序:&nbsp;&nbsp;'.Html::input('text','Album[sort_order]',$model->sort_order,['maxlength' => true,'style'=>'width:111px;border-radius: 4px;','id'=>'album-sort_order'])?>
		</td>
    </tr>
	<tr>
        <td class="tb_title"><?= $form->field($model, 'status',['options'=>['tag'=>'label'],'template'=>'显示状态']); ?>：</td>
    </tr>
	<tr>
        <td class="tb_title">
		     <?= '是否显示:&nbsp;&nbsp;'.Html::dropDownList('Album[status]',$model->status,[ '1' => 'Y', '2' => 'N'],['maxlength' => true,'style'=>'width:73px;border-radius: 4px;','id'=>'album-status'])?>
			 <?= '推荐:&nbsp;&nbsp;'.Html::dropDownList('Album[commend]',$model->commend,[ '1' => 'Y', '2' => 'N' ],['maxlength' => true,'style'=>'width:73px;border-radius: 4px;','id'=>'album-commend'])?>
			 <?= '头条:&nbsp;&nbsp;'.Html::dropDownList('Album[top_line]',$model->top_line,[ '1' => 'Y', '2' => 'N'],['maxlength' => true,'style'=>'width:73px;border-radius: 4px;','id'=>'album-top_line'])?>
			 <?= '允许评论:&nbsp;&nbsp;'.Html::dropDownList('Album[reply_allow]',$model->reply_allow,[ '1' => 'Y', '2' => 'N' ],['maxlength' => true,'style'=>'width:73px;border-radius: 4px;','id'=>'album-reply_allow'])?>
		</td>
    </tr>
	<tr>
        <td class="tb_title"><?= $form->field($model, 'attention_count',['options'=>['tag'=>'label'],'template'=>'关注']); ?>：</td>
    </tr>
	<tr>
        <td class="tb_title">
		     <?= '关注次数:&nbsp;&nbsp;'.Html::input('text','Album[attention_count]',$model->attention_count,['maxlength' => true,'style'=>'width:187px;border-radius: 4px;','id'=>'album-attention_count'])?>
			 <?= '评论次数:&nbsp;&nbsp;'.Html::input('text','Album[reply_count]',$model->reply_count,['maxlength' => true,'style'=>'width:187px;border-radius: 4px;','id'=>'album-reply_count'])?>
			
		</td>
    </tr>
	<tr>
        <td class="tb_title"><?= $form->field($model, 'seo_title')->label('SEO标题：')->textInput(['maxlength' => true,'style'=>'width:500px']) ?></td>
    </tr>
	<tr>
        <td class="tb_title"><?= $form->field($model, 'seo_keywords')->label('SEO关键字：')->textInput(['maxlength' => true,'style'=>'width:500px']) ?></td>
    </tr>
	<tr>
        <td class="tb_title"><?= $form->field($model,'seo_description',['options'=>['tag'=>'label'],'template'=>'SEO描述']); ?>：</td>
    </tr>
    <tr>
        <td>
			<?= Html::textarea('Album[seo_description]',isset($model->seo_description)?$model->seo_description:'',['id'=>'album-seo_description','style'=>'border-radius: 4px; padding: 5px 2px;border: 1px solid;border-color: #666 #ccc #ccc #666;background: #F9F9F9;color: #333;font-size: 14px;width:500px;height:80px'])?>
        </td>
    </tr>

</table>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<script type="text/javascript" src="/js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="/js/jquery.fileupload.js"></script>
<script type="text/javascript" src="/js/jscolor/jscolor.js"></script>
<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<input  style='display:none' type="file" name="Filedata" id='id_photos' value="" />
<script>


$(document).ready(function(){
	
	//阻止浏览器默认行。 
			$(document).on({ 
				dragleave:function(e){    //拖离 
					e.preventDefault(); 
				}, 
				drop:function(e){  //拖后放 
					e.preventDefault(); 
					 // var dt = e.originalEvent.dataTransfer;
					  // if(dt.files.length > 0){
						// $("#id_photos").trigger('change');
  					  // }
				}, 
				dragenter:function(e){    //拖进 
					e.preventDefault();
				}, 
				dragover:function(e){    //拖来拖去 
					e.preventDefault(); 
				}
			});
	
	//照片异步上传
	$(document).on("click",".closebutton",function(){
                var album_listId = $('#album-album_list').val();
				
                var bid = $(this).next().attr('data_bid');
                var temp='';
                var ids =album_listId.split(',');
                for(i in ids){
                    		if(ids[i]==bid){
                    			continue;
                    		}
                    		temp+=temp?","+ids[i]:ids[i];
                    	}
				$("input[name='Album[album_list]']").val(temp);
				$(this).parent().remove();				
				
    });
	 //照片异步上传
	$(document).on('click',".attach_file",function(){
		var limit = $(this).attr('limit')?$(this).attr('limit'):2;
		var attach_file = $(this).attr('attach_file')?$(this).attr('attach_file'):'';
		var data_type = $(this).attr('data_type');
		if(!attach_file)return false;
		$("#id_photos").attr({"attach_file":attach_file,"limit":limit,'data_type':data_type}).click();
	})
	
	$(document).on('click','.resumable-browse',function(){
		var limit = $(this).attr('limit')?$(this).attr('limit'):2;
		var album_list = $(this).attr('album_list')?$(this).attr('album_list'):'';
		var data_type = $(this).attr('data_type');
		if(!album_list)return false;
		$("#id_photos").attr({"album_list":album_list,"limit":limit,'data_type':data_type}).click();
	})

$(document).on("change",'#id_photos',function(){ //此处用了change事件，当选择好图片打开，关闭窗口时触发此事件
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});
		var attach_file = $(this).attr('attach_file');
		var limit = $(this).attr('limit')?$(this).attr('limit'):2;
		var data_type = $(this).attr('data_type');
		// var album_list = $(this).attr('album_list');
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
                    if(data_type == 2){
						$("#album-album_list").val((aa ? (aa + ",") : '')+data.fileid).trigger("change");
						var div ='<li style="float: left;height: 127px;margin-right: 85px;margin-top: 20px;width: 50px;"><img class="closebutton" src="/images/hint33.png" style="position: absolute;z-index: 20;display: block;height: 35px; width: 35px;" /><img class="imageWxview"  data_bid='+data.fileid+' src='+data.url+' width="120" height="120"/></li>';
						$(".tu").children('ul').append(div);						
					}else{
						$("#"+attach_file).val(data.fileid).trigger("change");
						$("#attach_thumb").val(data.fileid);
						var div = '<span style="color:#555">大图：</span><img class="imageWxview" style="max-width:300px; padding: 5px; border: 1px solid #cccccc;" src="'+data.url+'" align="absmiddle"><span style="color:#555">小图：</span><img  class="imageWxview" style="max-width:100px; padding: 5px; border: 1px solid #cccccc;" src="'+data.url+'" align="absmiddle">';
						$("#file").next('p').html(div);
					}		
                    
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
});
</script>