<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\modules\Policy;
// use kartik\widgets\ActiveForm;
// use kartik\form\ActiveField;
use kartik\widgets\DepDrop;
// use frontend\widget\JsBlock;
use yii\widgets\ActiveForm;  
if($model->isNewRecord){
	$actionLabel ="申请保函";
	$postUrl =yii\helpers\Url::toRoute(['/policy/create']);
	$returnUrl =yii\helpers\Url::toRoute(['/policy/succeed','id'=>'']);
}else{
	$actionLabel ="保存";
	$postUrl =yii\helpers\Url::toRoute(['/policy/modify','id'=>$model->id]);
	// $returnUrl =yii\helpers\Url::toRoute(['/policy/index','id'=>'']);
	$returnUrl =Yii::$app->request->getReferrer();
	
}

$form = ActiveForm::begin([
    'id' => 'financing', 
]); 
?>
<?php if($showtype==1):?>
	<div class="write-top">
      <p>基本信息</p>
    </div>
	<div class="detail pl" style="display: block;">
        <ul>
			<li> 
				<span><i class="red">*</i>选择城市</span>
				<?=$form->field($model, 'area_pid',['options'=>['tag'=>'code'],'template'=>'{input}'])->label('')->dropDownList(ArrayHelper::map($provinceData, 'id','name'), ['id' =>'cat-id','prompt'=>'请选择...']); ?>
				<i></i>
				<?=$form->field($model, 'area_id',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->widget(DepDrop::classname(), [
					'options'=>[
					'prompt'=>'请选择'
					],
					'pluginOptions'=>[
						'depends'=>['cat-id'],
						'placeholder'=>'请选择',
						'url'=>Url::to(['/policy/area-city'])
					]
				]);
				?>
				<i></i> 
           
				<i></i>
			</li>
			<li>
				<span><i class="red">*</i>选择法院</span>
				<?= $form->field($model, 'fayuan_id',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->widget(DepDrop::classname(), [
					'options'=>['onchange'=>"document.getElementById('policy-fayuan_name').value=this.options[this.selectedIndex].text",'style'=>'width:260px','prompt'=>'请选择...'],
					'pluginOptions'=>[
						'depends'=>['cat-id','policy-area_id'],
						'placeholder'=>'请选择',
						'url'=>Url::to(['/policy/fayuan'])
					]
				]);
				?>
				<i></i> 
			</li>
			<li>
				<span><i class="red">*</i>案件类型</span>
				<?= $form->field($model, 'category',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->label('')->dropDownList(\common\models\CreditorProduct::$category, ['style' =>'width:260px']); ?>
				<i></i> 
			</li>
			<li> 
				<span><i class="red">*</i>案&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号</span>
				<?= $form->field($model, 'anhao',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->textInput(['placeholder'=>'如:(2016)沪108执00211号','style'=>'width:253px;height:30px;line-height:30px;',]); ?>
			</li>
			<li>
				<span><i class="red">*</i>联系方式</span>
				<?= $form->field($model, 'phone',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->textInput(['placeholder'=>'请输入手机号码','style'=>'width:253px;height:30px;line-height:30px;',]); ?>
			</li>
			<li>
				<span><i class="red">*</i>保函金额</span>
				<?= $form->field($model, 'money',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->textInput(['placeholder'=>'请输入保函金额,单位万元','style'=>'width:253px;height:30px;line-height:30px;',]); ?>
			</li>
			<li>
				<span><i class="red">*</i>取函方式</span>
				<?=$form->field($model, 'type',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->label('')->dropDownList(['2'=>'快递','1'=>'自取'], ['style'=>'width:260px','prompt'=>'请选择']); ?> 
			</li>
			<li class='type_list type_list2' > 
				<span><i class="red">*</i>收货地址</span>
				<?= $form->field($model, 'address',['options'=>['tag'=>'code'],'template'=>'{input}{error}'])->textInput(['placeholder'=>'请输入收货地址','style'=>'width:253px',]); ?>
			</li>
			<li class='type_list type_list1' > 
				<span><i class="red">*</i>取函地址</span>
				<?= $form->field($model, 'fayuan_address',['options'=>['tag'=>'code'],'template'=>'{input}'])->textInput(['placeholder'=>'','readonly'=>'readonly','style'=>'width:253px',]); ?>
			</li>
        </ul>
		<?= $form->field($model, 'fayuan_name')->hiddenInput()->label(false); ?>
    </div>
	<div class="write-top">
        <p>完善证据材料</p>
    </div>
<?php endif;?>
    <div class="perfect">
		<?php $data = ['qisu'=>'起诉书','caichan'=>'财产保全申请书','zhengju'=>'相关证据资料','anjian'=>'案件受理通知书'];
		foreach($data as $key=>$val):?>
			<div class="per">
				<div class="per-l"><span><i class="red">*</i><?=$val?></span></div>
				<div class="per-r">
					<ul>
					 <?php 
						$num=0;
						if(isset($model->$key)&&$model->$key){
							$num = count(explode(',',$model->$key));
							$attr=$key."files"; 
							foreach($model->$attr as $files){?>
							<li>
								<i class="closebutton" data_bid="<?=$files['id']?>" style="position:absolute;"><img src="/images/hint33.png" width="35" height="35"></i>
								<p><img src="<?=$files['file']?>" width="120" height="120"></p>
							</li> 
						<?php } ?>
					 <?php } ?>
					  <li class='pic' style="<?=$num>=5?"display:none":""?>">
						<?= $form->field($model, $key,['options'=>['tag'=>'code'],'template'=>'{input}'])->hiddenInput(['id'=>$key]); ?>
						<p><img src="/images/post.jpg" inputName='<?=$key?>' class='add_tu_05' limit = '5' width="120" height="120"></p>
					  </li>
					</ul>
					<div class="bot">上传起诉材料，最多上传5张，每张最大10M<?= $form->field($model, $key,['options'=>['tag'=>'code'],'template'=>'{error}'])->hiddenInput(['id'=>$key]); ?></div>
				</div>
			</div>
		<?php endforeach;?>
    </div>
<input type="button" value="<?=$actionLabel?>" onclick="$('#financing').submit()" class="buttonev01">
<?php ActiveForm::end();?>
 
 <script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<input  style='display:none' type="file" name="Filedata" id='id_photos' value="" />
 
<script>
$(document).ready(function(){
	$('form#financing').on('beforeSubmit', function(e) {
	   // var $form = $(this);
	   // alert(3)
	   var url = '<?=$postUrl?>';
	    $.post(url,$('#financing').serialize(),function(json){
			if(json.code == '0000'){
					layer.alert(json.msg,[],function(){
						<?php if($model->isNewRecord){?>
						window.location = "<?=$returnUrl?>"+json.data.id;
						<?php }else{?>
						window.location = "<?=$returnUrl?>"
						<?php }?>
					});
					layer.close(index);
				  
			}else{
				  layer.alert(json.msg);
			}
		},'json') 
	}).on('submit', function(e){
		e.preventDefault();
	});
	// $("#cat-id").
	setTimeout('$("#cat-id").trigger("change");',1)
	
	$("#policy-type").change(function(){
		var typeid = $(this).val()
		$(".type_list").hide();
		$(".type_list"+typeid).show();
	})
	$("#policy-fayuan_id").change(function(){
		if($(this).val()){
			$('#policy-fayuan_address').val($(this).find("option:selected").text())
		}else{
			$('#policy-fayuan_address').val('')
		}
		
    })
	$("#cat-id,#policy-area_id").change(function(){
		$('#policy-fayuan_address').val('')
		$('#policy-fayuan_name').val('')
    })
	//照片异步上传
	$(document).on("click",".closebutton",function(){
				var upbtn = $(this).parent().parent().children(".pic");
                var id = $(this).parent('li').parent('ul').children('.pic').find('input').val();
                var bid = $(this).attr('data_bid');
                var temp='';
	            var ids =id.split(',');
                for(i in ids){
                     		if(ids[i]==bid){
                     			continue;
                     		}
                     		temp+=temp?","+ids[i]:ids[i];
                     	}
                $(this).parent('li').parent('ul').children('.pic').find('input').val(temp)
             	$(this).parent().remove();
				upbtn.show()
				
     });
	$(".add_tu_05").click(function(){
		var limit = $(this).attr('limit')?$(this).attr('limit'):5;
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
		// alert(aa.split(",").length)
		
		if(limit&&aa.split(",").length>=limit){
			
			layer.close(index)
			layer.alert("最多上传"+limit+"张图片");
			$("#"+inputName).parents(".pic").hide()
			return false;
		} 
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
					var aa = $("#"+inputName).val(); 
					if(limit&&aa.split(",").length>=limit){
						layer.alert("最多上传"+limit+"张图片");
						$("#"+inputName).parents(".pic").hide()
						return false;
					}   
					$("#"+inputName).val((aa ? (aa + ",") : '')+data.fileid).trigger("change"); 
					var str = '';
					var div =' <li><i class="closebutton" data_bid='+data.fileid+'  style="position:absolute;"><img src="/images/hint33.png" width="35" height="35"></i><p><img src='+data.url+' width="120" height="120"></p></li>';
					 
					var spandiv = $("#"+inputName).parent("code").parent("li");
					if(spandiv.index()==0){
						spandiv.parent().prepend(div)
					}else{
						spandiv.prev().after(div);
					}
					if(($("#"+inputName).val()).split(",").length>=limit){
						$("#"+inputName).parents(".pic").hide()
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
			area: ['auto', '80%'],
			photos: photojson
		});
    })
});
</script>	