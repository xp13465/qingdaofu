<?php
use yii\helpers\Html;
use yii\helpers\url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use wx\widget\wxHeaderWidget;
$categorys = isset($data['category'])?explode(',',$data['category']):'';
$entrusts  = isset($data['entrust'])?explode(',',$data['entrust']):'';
$category = ['1'=>'房产抵押','2'=>'机动车抵押','3'=>'合同纠纷','4'=>'其他'];//债权类型（1房产抵押，2机动车抵押，3合同纠纷，4其他）
$entrust = ['1'=>'诉讼','2'=>'清收','3'=>'债权转让','4'=>'其他'];//（1诉讼,2清收3,债权转让，4其他）
if(isset($data)&&$data){
	$gohtml = '保存为草稿';
	if($data['status']=='0'){
		$title= '发布债权';
	}else{
		$title= '保存';
	}
	
}else{
	$gohtml = '保存为草稿';
	$title= '发布债权';
}
?>
<style>
select::-ms-expand { display: none;
            appearance:none;
            -moz-appearance:none;
            -webkit-appearance:none;
            -ms-appearance:none;
         }

</style>
<div class="cont">
    <div class="content clearfix">
      <div class="content_right contents">
        <div class="right-toop">
          <div class="right-topl">
            <p>发布债权</p>
          </div>
        </div>
	<?php ActiveForm::begin(['id'=>'productCreate'],'post', ['enctype' => 'multipart/form-data']) ?>
		<?= Html::hiddenInput('productid',Yii::$app->request->get('productid'))?>
          <div class="detail pl" style="display: block;">
            <ul>
              <li> <span style="float:left;vertical-align: middle;line-height:10px;"><i class="red">*</i>债权类型</span>
                <div class="types bond">
                  <ul>
				   <?php foreach($category as $key => $value): ?>
                    <li <?php if($categorys){ echo in_array($key,$categorys)?'class="activing act"':'class="activing"';}else{echo 'class="activing"';}?>>
                     <input type="checkbox" name="category[]" data-type='1' <?php if($categorys){ echo in_array($key,$categorys)?'checked="false"':'';}?>  value="<?= $key ?>" class="infor_c other01">
                     <a href="javascript:void(0);"><?= $value ?></a>
                    </li>
					<?php endforeach; ?>
					<?= Html::input('text','category_other',isset($data['category_other'])?$data['category_other']:'',['placeholder'=>'请输入其他类型的名称，不超过5个字','style'=>'display:none']);?>
                  </ul>
                </div>
              </li>
              <div class="clear"></div>
              <li> <span style="float:left;vertical-align: middle;line-height:10px;"><i class="red">*</i>委托类型</span>
                <div class="types Matter">
                  <ul>
				  <?php foreach($entrust as $key => $value): ?>
                    <li <?php if($entrusts){ echo in_array($key,$entrusts)?'class="activing act"':'class="activing"';}else{echo 'class="activing"';}?>>
						<input type="checkbox" name="entrust[]" data-type='1' <?php if($entrusts){ echo in_array($key,$entrusts)?'checked="false"':'';}?> value="<?= $key ?>" class="infor_c other02">
						<a href="javascript:void(0);"><?= $value ?></a>
                    </li>
					<?php endforeach; ?>
					<?= Html::input('text','entrust_other',isset($data['entrust_other'])?$data['entrust_other']:'',['placeholder'=>'请输入其他类型的名称，不超过5个字','style'=>'display:none']);?>
                  </ul>
                </div>
              </li>
              <div class="clear"></div>
              <li> 
				<span><i class="red">*</i>委托金额</span>
				<code class="field-servicesinstrument-contacts required">
				<?= Html::input('text','account',isset($data['accountLabel'])?intval($data['accountLabel']):'',['id'=>'servicesinstrument-contacts','class'=>'form-control','placeholder'=>'请输入委托金额，单位万元','style'=>'width:400px;height:30px;line-height:30px;']) ?>
                <div class="help-block"></div>
                </code>
			  </li>
              
                <li>
                  <span style="float:left;"><i class="red">*</i>费用类型</span>
                    <div class="radioBtn">
					  <label>
						  <input type='radio' name='type' value='1' class='btn_r' <?php if(isset($data['type'])&&$data['type']){echo $data['type']!='2'?'checked="checked"':'';}else{echo 'checked="checked"';}?>  >
						  固定费用
					  </label> 
                    </div>
                    <div class="radioBtn">
						<label>
							<input type='radio' name='type' value='2' class='btn_r' <?php if(isset($data['type'])&&$data['type']=='2'){echo 'checked="checked"';}?>  >
							风险费率
						</label> 
                    </div>
                </li>
                <li>
                <div class="fx" radio-data="1" <?=isset($data['type'])&&$data['type']=='2'?'style="display:none"':'style="display:block"'?> >
				     <?= Html::input('text','typenum1',isset($data['type'])&&$data['type']=='1'?($data['typenumLabel']?intval($data['typenumLabel']):""):'',['placeholder'=>'请输入固定费用用于支付给接单方的报酬，单位万元'])?>
                </div>
                </li>
                <li>
                <div class="fx" radio-data="2" <?php if(isset($data['type'])&&$data['type']=='2'){echo 'style="display:block"';}?> >
					<?= Html::input('text','typenum2',isset($data['type'])&&$data['type']=='2'?intval($data['typenum']):'',['placeholder'=>'请输入风险费率用于支付给接单方的报酬，单位%'])?>
                </div>
                </li>
				<li> 
					<span><i class="red">*</i>违约期限</span> <code class="field-servicesinstrument-tel required">
					<?= Html::input('text','overdue',isset($data['overdue'])?intval($data['overdue']):'',['id'=>'servicesinstrument-tel','class'=>'form-control','placeholder'=>'请输入已经违约的时间，单位月','style'=>'width:400px;height:30px;line-height:30px;'])?>
					<div class="help-block"></div>
					</code> 
				</li>
              <li> <span><i class="red">*</i>合同履行地:</span> 
						<?= Html::dropDownList(
						'province_id',isset($data['province_id'])?$data['province_id']:'310000',
						ArrayHelper::map($province,'provinceID','province'),
						['id'=>'province','class'=>'form-control','placeholder'=>"请选择",'style'=>'width:131px;']
						)?>
						<?= Html::dropDownList(
								'city_id','',
								[""=>"请选择..."],
								['id'=>'city','class'=>'form-control','placeholder'=>"请选择",'style'=>'width:131px;','data-krajee-depdrop'=>'depdrop_2193fc09']
						)?>
						<?= Html::dropDownList(
								'district_id','',
								[""=>"请选择..."],
								['id'=>'district','class'=>'form-control','placeholder'=>"请选择",'style'=>'width:131px;','data-krajee-depdrop'=>'depdrop_26c092ab']
						)?>
				</li>
            </ul>
            <div class="form-group field-policy-fayuan_name">
              <input type="hidden" id="policy-fayuan_name" class="form-control" name="Policy[fayuan_name]" value="">
              <div class="help-block"></div>
            </div>
          </div>
          <input class="btn buttonev01" data-productid="<?= isset($data['productid'])&&$data['productid']?$data['productid']:'' ?>" data-type='<?= isset($title)&&$title=='保存'?'1':''?>' type="button" value="<?= $title ?>">
		  <?php if(isset($data['status'])&&$data['status'] == '0' || !$data){ ?>
		  <span style='text-align: center; margin-left:50px'>先不发布，<a href='javascript:void(0);' class='icon-close' style='color:#0da3f8'><?= $gohtml ?></a></span>
		  
		  <?php } ?>
        <?php ActiveForm::end() ?>
      </div>
    </div>
</div>
<script>
$(document).ready(function(){
	var inputBlur = 1;
	
	
	var city_id = "<?= isset($data['city_id'])?$data['city_id']:''?>";
	var district_id = "<?= isset($data['district_id'])?$data['district_id']:''?>";
	var _csrf = "<?= Yii::$app->request->csrfToken ?>";
	$('#province').change(function(){
		var province_id = $(this).val();
		$.ajax({
			url:'<?= Yii\helpers\Url::toRoute('/product/city'); ?>',
			type:'post',
			data:{province_id:province_id,_csrf:_csrf},
			dataType:'json',
			success:function(json){
				var html='<option value="0" selected="">请选择...</option>';
				if(json['code'] == '0000'){
					$('#city').html(html+json['data']['html']);
					if(city_id){
						$('#city').val(city_id).trigger("change")
					}
				}
			}
		})
	}).trigger("change");
	
	$('#city').change(function(){
		var city_id = $(this).val();
		$.ajax({
			url:'<?= Yii\helpers\Url::toRoute('/product/district'); ?>',
			type:'post',
			data:{city_id:city_id},
			dataType:'json',
			success:function(json){
				var html='<option value="0" selected="">请选择...</option>';
				if(json['code'] == '0000'){
						$('#district').html(html+json['data']['html']);
					if(district_id){
						$('#district').val(district_id)
					}
				}
			}
		})
	});
	
	$('.buttonev01').click(function(){
		var productid = $(this).attr('data-productid');
		var Types = $(this).attr('data-type');
		if(productid){
			var url = '<?= Yii\helpers\Url::toRoute('/product/edit'); ?>';
		}else{
			var url ='<?= Yii\helpers\Url::toRoute('/product/release'); ?>';
		}
		$.ajax({
			url:url,
			type:'post',
			data:$('#productCreate').serialize(),
			dataType:'json',
			success:function(k){
				if(k.code == '0000'){
					if(productid){
						if(Types == 1){
							layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>保存成功！",{time:2000},function(){window.location.href="<?= Url::toRoute(['/product/product-deta'])?>?productid="+productid});
						}else{
							layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>发布成功！",{time:2000},function(){window.location.href="<?= Url::toRoute(['/product/product-deta'])?>?productid="+productid});
						}
					}else{
						layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>发布成功！",{time:2000},function(){window.location.href="<?= Url::toRoute(['/product/product-deta'])?>?productid="+k.data.productid});
					}
					
				}else{
					layer.msg(k.msg,{time:1500});
				}
				
			}
		})
	})
	var dataLength = $('#productCreate').serialize().length;
	$('.icon-close').click(function(){
        var productid = '<?= Yii::$app->request->get('productid') ?>';
		if(productid){
			var url = '<?= Yii\helpers\Url::toRoute('/product/preservation-edit'); ?>';
		}else{
			var url ='<?= Yii\helpers\Url::toRoute('/product/draft'); ?>';
		}
		if($('#productCreate').serialize().length-1 == dataLength){
			layer.msg("请填写最少一项数据",{time:2000});
			return false;
		}
		 layer.confirm('保存为草稿?',{
			 title:false,closeBtn:false,
			 btn:['确定','取消']
		 },function(){
				 $.ajax({
					url:url,
					type:'post',
					data:$('#productCreate').serialize(),
					dataType:'json',
					success:function(k){
					if(k.code == '0000'){
							if(productid){
								layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>修改成功！",{time:2000},function(){window.location.href="<?= Url::toRoute(['/product/preservation'])?>"});
							}else{
								layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>保存成功！",{time:2000},function(){window.location.href="<?= Url::toRoute(['/product/preservation'])?>"});
							}
							
					}else{
						layer.msg(k.msg);
					}
				}
			})
		 }//,function(){
			// history.go(-1)
		// }
		)
	})
	
	
	
	$(".code").mouseover(function(){
		layer.tips('<img src="/bate2.0/images/dipian.png" class="tu" width="120" height="120">', $(this), {
		  tips: [1, '#33b3f5'],
		  area: ["140px !important","140px !important"],
		  scrollbar :true,
		  time: 0
		});
	$(".code").mouseout(function(){
		$(".layui-layer,.layui-layer-tips,.layer-anim,.tu").hide();
		});
	})

	$(window).on("scroll",function(){
			if($(window).scrollTop()>92){
				$(".header-animation").slideDown('fast');
			}else{
				$(".header-animation").slideUp('fast');
			}
		})	
		$(window).trigger("scroll")
		//$(".header-animation").show();
        // $(".header-animation").animate({top: '+92px'}, "slow");
	
	
	$(".activing a").click(function(){
		var $child = $(this).parent().children("input");
		if($child.prop("checked")){
			$child.prop("checked",false)
		}else{
			$child.prop("checked",true)
		}
		$child.trigger("change")
		
		if($(this).parent('li').index()==3){
			var $li =$child.parent();
			var $input = $li.next('input');
			var checked = $child.prop("checked");
			var color = ($input.val())?"":"red";
			// inputBlur = 2
			if(checked){
				$input.css("color",color).show().focus();
			}else{
				$input.css("color",'').val("").hide().blur();
			}
		}
		
		
	})
	$('.types input').on("keyup",function(){
		if($(this).val()){
			$(this).css("color","");
		}else{
			$(this).css("color","red");
		}
		
	})
	$('.types input').on("blur",function(){
		if($(this).val()){javascript:void(0);
			$(this).parent().children("li").eq(3).addClass("act").children('input').attr('checked',true);
		}
	})
	$(".infor_c").change(function(){
		if($(this).prop("checked")){
			$(this).parent('.activing').addClass("act")
		}else{
			$(this).parent('.activing').removeClass("act")
		}
	})
	
	$(".btn_r").change(function(){
		var value = $(this).val();
		$(".fx").hide();
		$(".fx[radio-data='"+value+"']").show();
	})
	$(".btn_r:checked").trigger("change")
	
})
</script>