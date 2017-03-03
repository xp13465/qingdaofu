<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use wx\widget\wxHeaderWidget;
//var_dump($data);
?>

<?=wxHeaderWidget::widget(['title'=>isset($data['number'])?$data['number']:'','gohtml'=>'','backurl'=>true,'reload'=>false])?>
<!-------产品详情信息编辑------->
<div class="Divgray"></div>

<section>
  <div class="show">
    <div class="show_xx">
	<span style="color:#333;">基本信息</span> 
	<?php if($data['create_by'] == Yii::$app->user->getId()&&in_array($data['status'],['0','10'])){ ?>
		<a href="<?= yii\helpers\Url::toRoute(['/product/create','productid'=>$data['productid']])?>" style="color: #10a1ec; font-size:16px;line-height: 50px;float:right;">编辑</a> 
	<?php } ?>
	</div>
    <ul class="revert">
         <li>
            <div class="revert_l"> <span>债权类型</span> </div>
            <div class="revert_r"> <span><?= isset($data['categoryLabel'])?$data['categoryLabel']:''?></span> </div>
          </li>
          <li>
            <div class="revert_l"> <span>委托事项</span> </div>
            <div class="revert_r"><?= isset($data['entrustLabel'])?$data['entrustLabel']:''?></div>
          </li>
          <li>
            <div class="revert_l"> <span>委托金额</span> </div>
            <div class="revert_r"> <span><?= isset($data['accountLabel'])?$data['accountLabel'].'万':''?></span> </div>
          </li>
		  <li>
			<div class="revert_l"> <span><?php if($data['type']==1){echo '固定费用';}else{echo '风险代理';}?></span></div>
			<div class="revert_r"> <span><?php if($data['type']==1){echo $data['typenumLabel'].$data['typeLabel'];}else{ echo $data['typenum'].$data['typeLabel'];}?></span> </div>
		  </li>
		  <li>
			<div class="revert_l"> <span>逾期期限</span> </div>
			<div class="revert_r"> <span><?=($data['overdue'])?>个月</span> </div>
		  </li>
		  <li>
			<div class="revert_l"> <span>合同履行地</span> </div>
			<div class="revert_r"> <span><?=($data['addressLabel'])?></span> </div>
		  </li>
    </ul>

  </div>
<?php $a = ['1'=>'抵押物地址','2'=>'机动车抵押','3'=>'添加合同纠纷类型'];?>
 
	<ul class="revert" style="margin-top:10px;">
			<?php if(isset($data['productMortgages1'])&&$data['productMortgages1']){ foreach ($data['productMortgages1'] as $key => $value){ ?>
				<li>
					<div class="revert_l"> <span>抵押物地址</span> </div>
					<div class="revert_r rever01" data-mortgageid='<?= $value['mortgageid']?>' data-category='<?= $value['type']?>' data-relation_1='<?= $value['relation_1'] ?>' data-relation_2='<?= $value['relation_2'] ?>' data-relation_3='<?= $value['relation_3'] ?>' data-relation_desc='<?= $value['relation_desc'] ?>' typed = '1'> <span><?= isset($value['addressLabel'])?$value['addressLabel']:'' ?></span><i></i> </div>
				</li>
			<?php }}; ?>
			<?php if($data['create_by'] == Yii::$app->user->getId()&&in_array($data['status'],['10'])&&in_array('1',explode(',',$data['category']))){ ?>
				<li class="li_infor"> 
					<p class="address" style="float:none;color:#666;text-align:center;width:100%;height:50px;line-height:50px;font-size:16px;">添加抵押物地址信息</p>
				</li>
			<?php } ?>
	</ul>

  
  <ul class="revert" style="margin-top:10px;">
       
		<?php if(isset($data['productMortgages2'])&&$data['productMortgages2']){ foreach ( $data['productMortgages2'] as $key => $value){ ?>
          <li>
            <div class="revert_l"> <span>机动车抵押</span> </div>
            <div class="revert_r rever01" data-mortgageid='<?= $value['mortgageid']?>' data-category='<?= $value['type']?>'  data-relation_1='<?= $value['relation_1'] ?>' data-relation_2='<?= $value['relation_2'] ?>' data-relation_3='<?= $value['relation_3'] ?>' > <span><?= isset($value['brandLabel'])?$value['brandLabel']:''?></span><i></i> </div>
          </li>
		<?php }} ; ?>
		<?php if($data['create_by'] == Yii::$app->user->getId()&&in_array($data['status'],['10'])&&in_array('2',explode(',',$data['category']))){ ?>
         <li class="li_infor"> 
              <p class="car" style="float:none;color:#666;text-align:center;width:100%;height:50px;line-height:50px;font-size:16px;">添加机动车信息</p>
        </li>
		<?php } ?>
    </ul>
	
	<ul class="revert" style="margin-top:10px;">
		<?php if(isset($data['productMortgages3'])&&$data['productMortgages3']){ foreach ($data['productMortgages3'] as $key => $value){ ?>
          <li>
            <div class="revert_l"> <span>合同纠纷类型</span> </div>
            <div class="revert_r rever01" data-mortgageid='<?= $value['mortgageid']?>' data-category='<?= $value['type']?>'  data-relation_1='<?= $value['relation_1'] ?>' > <span><?= isset($value['contractLabel'])?$value['contractLabel']:''?></span><i></i> </div>
          </li>
		<?php }}; ?>
		<?php if($data['create_by'] == Yii::$app->user->getId()&&in_array($data['status'],['10'])&&in_array('3',explode(',',$data['category']))){ ?>
         <li class="li_infor"> 
              <p class="contract" style="float:none;color:#666;text-align:center;width:100%;height:50px;line-height:50px;font-size:16px;">添加合同纠纷类型</p>
        </li>
		<?php } ?>
    </ul>


 </section>
 
 <!-------编辑删除地址------->
<?php if($data['create_by'] == Yii::$app->user->getId()&&in_array($data['status'],['10'])){ ?> 
<div class="choosed">
  <p>新增抵押物地址 <span class="close"></span></p>
<ul class="infor">
  <li class="infor_li infor_li01 guaran" style="height:auto;border-bottom:0px solid #ddd;">
    <div class="infor_l" style="width:100%;float:left;border-bottom:1px solid #ddd;"> <span>抵押物地址</span>
      <?= Html::dropDownList(
				'province_id','310000',
				ArrayHelper::map($province,'provinceID','province'),
				['id'=>'province','class'=>'selects selects_three','placeholder'=>"请选择",'data-required'=>"true"]
	  )?>
      <?= Html::dropDownList(
				'city_id','',
				[""=>"请选择..."],
				['id'=>'city','class'=>'selects selects_three','placeholder'=>"请选择",'data-required'=>"true"]
		)?>
	  <?= Html::dropDownList(
				'district_id','',
				[""=>"请选择..."],
				['id'=>'district','class'=>'selects selects_three','placeholder'=>"请选择",'data-required'=>"true"]
	   )?>
    </div>
  </li>
  <li class="infor_li guaran" style="border-bottom:0px solid #ddd;">
  <li class="infor_li guaran" style="height:auto;border-bottom:1px solid #ddd;">
    <div class="infor_l"> <span>详细地址</span>
	  <?= Html::input('text','relation_desc','',['id'=>'seatmortgage','placeholder'=>'详细地址','style'=>'width:65%;height:30px;line-height:35px;']);?>
    </div>
  </li>
  <li class="anniu" data-id ='' data-mortgageid ='' data-type='1' data-productid = '<?= Yii::$app->request->get('productid') ?>'><a hre="#" style="font-size:16px;color:#fff;">保存</a></li>
  <li class="anniu1" data-mortgageid ='' data-type='1' data-productid = '<?= Yii::$app->request->get('productid') ?>' style="border:1px solid #f17a76;"><a hre="#" style="font-size:16px;color:#f17a76;">删除该地址</a></li></ul>
</div>

<div class="mycar">
  <p>添加机动车类型 <span class="close"></span></p>
<ul class="infor">
  <li class="infor_li infor_li01 guaran" style="height:auto;border-bottom:1px solid #ddd;">
    <div class="infor_l"> <span>选择车型</span>
	    <?= Html::dropDownList(
				'brand','',
				ArrayHelper::map($brand,'id','name'),
				['id'=>'brand','class'=>'selects selects_three','placeholder'=>"请选择",'data-required'=>"true"]
	    )?>
		<?= Html::dropDownList(
				'audi','',
				[""=>"请选择..."],
				['id'=>'audi','class'=>'selects selects_three','placeholder'=>"请选择",'data-required'=>"true"]
		)?>
		<?= Html::dropDownList(
				'licenseplate','',
				[""=>"请选择...",'1'=>'沪牌','2'=>'非沪牌'],
				['id'=>'licenseplate','class'=>'selects selects_three','placeholder'=>"请选择",'data-required'=>"true"]
		)?>
      
    </div>
  </li> 
  <li class="anniu" data-id ='' data-mortgageid ='' data-type='2' data-productid = '<?= Yii::$app->request->get('productid') ?>'><a hre="#" style="font-size:16px;color:#fff;">保存</a></li>
  <li class="anniu1"  data-mortgageid ='' data-type='2' data-productid = '<?= Yii::$app->request->get('productid') ?>' style="border:1px solid #f17a76;"><a hre="#" style="font-size:16px;color:#f17a76;">删除</a></li>
</ul>
</div>
<div class="contracts" style="display:none;">
  <p>添加合同纠纷类型 <span class="close"></span></p>
<ul class="infor">
  <li class="infor_li infor_li01 guaran" style="height:auto;border-bottom:1px solid #ddd;">
    <div class="infor_l"> <span>选择类型</span>
	  <?= Html::dropDownList(
				'contract','',
				[""=>"请选择...",'1'=>'合同纠纷','2'=>'民事诉讼','3'=>'房产纠纷','4'=>'劳动合同','5'=>'其他'],
				['id'=>'contract','class'=>'selects selects_three','placeholder'=>"请选择",'data-required'=>"true"]
		)?>
    </div>
  </li>
  <li class="anniu" data-id ='' data-mortgageid ='' data-type='3' data-productid = '<?= Yii::$app->request->get('productid') ?>' ><a hre="#" style="font-size:16px;color:#fff;">保存</a></li>
  <li class="anniu1" data-mortgageid ='' data-type='3' data-productid = '<?= Yii::$app->request->get('productid') ?>' style="border:1px solid #f17a76;"><a hre="#" style="font-size:16px;color:#f17a76;">删除</a></li>
</ul>
</div>
<?php } ?>
<style>
.clear{clear:both;}
.close{width: 24px;height: 24px;background: url(/images/ser1.png) -24px -24px no-repeat;background-size: 96px;position: absolute;right: 6px;top: 6px;}
</style>
<script>

$(document).ready(function(){
	var relation_1 = "310000";
	var relation_2 = "";
	var relation_3 = "";
	
	$('#province').change(function(){
		var province_id = $(this).val();
		$.ajax({
			url:'<?= Yii\helpers\Url::toRoute('/product/city'); ?>',
			type:'post',
			data:{province_id:province_id},
			dataType:'json',
			success:function(json){
				var html='<option value="">请选择...</option>';
				if(json['code'] == '0000'){
					$('#city').html(html+json['html']);
					if(relation_2){
						if($('#city').find('option[value="'+relation_2+'"]').size()){
							$('#city').val(relation_2)
						}
					}
					$('#city').trigger("change")
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
				var html='<option value="">请选择...</option>';
				if(json['code'] == '0000'){
					$('#district').html(html+json['html']);
					if(relation_3&&$('#district').find('option[value="'+relation_3+'"]').size()){
						$('#district').val(relation_3)
					}
				}
			}
		})
	}).trigger("change");
	
	$('#brand').change(function(){
		var brand_id = $(this).val();
		$.ajax({
			url:'<?= Yii\helpers\Url::toRoute('/product/audi'); ?>',
			type:'post',
			data:{brand_id:brand_id},
			dataType:'json',
			success:function(json){
				var html='<option value="" >请选择...</option>';
				if(json['code'] == '0000'){
					$('#audi').html(html+json['html']);
					if(relation_2){
						if($('#audi').find('option[value="'+relation_2+'"]').size()){
							$('#audi').val(relation_2)
						}
						$('#audi').trigger("change")
					}
				}
			}
		})
	}).trigger("change");
	
	$('.rever01').click(function(){
		$(".Divgray").show();
		relation_1 = $(this).attr('data-relation_1');
		relation_2 = $(this).attr('data-relation_2');
		relation_3 = $(this).attr('data-relation_3');
		var category = $(this).attr('data-category'); 
		var mortgageid = $(this).attr('data-mortgageid');
		$('.anniu1').show().attr('data-mortgageid',mortgageid);
		$('.anniu').attr('data-id','20');
		$('.anniu').attr('data-mortgageid',mortgageid).html('<a hre="#" style="font-size:16px;color:#fff;">保存</a>');
		switch(category){
			case '1':
			 $(".choosed").show().attr('class','choosed choosed01');
			 var relation_desc = $(this).attr('data-relation_desc');
			 $('select[name="province_id"]').val(relation_1).trigger("change");
			 $('#seatmortgage').attr('value',relation_desc);
			break;
			case '2':
			$(".mycar").show().attr('class','mycar mycar01');
			$('select[name="brand"]').val(relation_1).trigger("change");
			$('select[name="licenseplate"]').val(relation_3);
			break;
			case '3':
			$(".contracts").show().attr('class','contracts contracts01');
			$('select[name="contract"]').val(relation_1);			
			break;
		}
		
	})
	
	$('.anniu').click(function(){
		var del = $(this).attr('data-id');
		var category = $(this).attr('data-type');
		var productid = $(this).attr('data-productid');
		switch(category){
			case '1':
				var relation_1 = $('select[name="province_id"]').val();
				var relation_2 = $('select[name="city_id"]').val();
				var relation_3 = $('select[name="district_id"]').val();
				var relation_desc = $('input[name="relation_desc"]').val();
			break;
			case '2':
				var relation_1 = $('select[name="brand"]').val();
				var relation_2 = $('select[name="audi"]').val();
				var relation_3 = $('select[name="licenseplate"]').val();
			break;
			case '3':
				var relation_1 = $('select[name="contract"]').val();
			break;
		}
		
		if(del){
		    var mortgageid = $(this).attr('data-mortgageid');
            switch(category){
				case '1':
					var param = {mortgageid:mortgageid,relation_1:relation_1,relation_2:relation_2,relation_3:relation_3,relation_desc:relation_desc,type:category,productid:productid};	
				break;
				case '2':
					var param = {mortgageid:mortgageid,relation_1:relation_1,relation_2:relation_2,relation_3:relation_3,type:category,productid:productid};	
				break;
				case '3':
					var param = {mortgageid:mortgageid,relation_1:relation_1,type:category,productid:productid};	
				break;
			}
            var url = '<?= yii\helpers\Url::toRoute('/product/mortgage-edit')?>';		
			
		}else{
			switch(category){
				case '1':
					var param = {relation_1:relation_1,relation_2:relation_2,relation_3:relation_3,relation_desc:relation_desc,type:category,productid:productid};	
				break;
				case '2':
					var param = {relation_1:relation_1,relation_2:relation_2,relation_3:relation_3,type:category,productid:productid};	
				break;
				case '3':
					var param = {relation_1:relation_1,type:category,productid:productid};	
				break;
			}
			var url = '<?= yii\helpers\Url::toRoute('/product/address')?>';
		}
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});	
		$.ajax({
				url: url,
				type:'post',
				data:param,
				dataType:'json',
				success:function(json){
					if(json.code == '0000'){
						if(del){
							layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>修改成功！",{time:500},function(){window.location.reload()});
							layer.close(index);							
						}else{
							layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>添加成功！",{time:500},function(){window.location.reload()});
							layer.close(index);							
						}
					}else{
						
						layer.msg(json.msg);
						layer.close(index);
					}
					
				}
			})
  })
  
  
  $('.anniu1').click(function(){
		var mortgageid = $(this).attr('data-mortgageid');
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});
        $.ajax({
			url:'<?= yii\helpers\Url::toRoute('/product/mortgage-del')?>',
			type:'post',
			data:{mortgageid:mortgageid},
			dataType:'json',
			success:function(json){
				if(json.code == '0000'){
					layer.close(index);
					layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>删除成功！",{time:500},function(){window.location.reload()});
				}else{
					layer.close(index);
					layer.msg(json.msg);
				}
			}
		})
  })
 
	$('.address').on('click',function(){
		relation_2 = "";
		relation_3 = "";
		$('.anniu1').hide();
		var html='<option value="">请选择...</option>';
		$('.anniu').attr('data-id','');
		$('#city').val(relation_2);
		$('#district').html(html);
		$('input[name=relation_desc]').val('');
		$('.anniu').html('<a hre="#" style="font-size:16px;color:#fff;">添加</a>');
		$(".choosed").show().attr('class','choosed');		
		$(".Divgray").show();
		})
    $(".close").click(function(){
		$(".choosed").hide();
		$(".Divgray").hide();
		})	
	$(".car").click(function(){
		relation_2 = "";
		relation_3 = "";
		var html='<option value="">请选择...</option>';
		$('.anniu1').hide();
		$('.anniu').attr('data-id','');
		$('#audi').val(relation_2);
		$('#licenseplate').val(relation_3);
		
		//$('#audi').html(html);
		$('.anniu').html('<a hre="#" style="font-size:16px;color:#fff;">添加</a>');
		$(".mycar").show().attr('class','mycar');		
		$(".Divgray").show();
		})
    $(".close").click(function(){
		$(".mycar").hide();
		$(".Divgray").hide();
		})	
	$(".contract").click(function(){
		relation_1 = "";
		$('.anniu1').hide();
		$('.anniu').attr('data-id','');
		$('#contract').val(relation_1);
		$('.anniu').html('<a hre="#" style="font-size:16px;color:#fff;">添加</a>');
		$(".contracts").show().attr('class','contracts');		
		$(".Divgray").show();
		})
    $(".close").click(function(){
		$(".contracts").hide();
		$(".Divgray").hide();
		})	 
})
$(document).ready(function(){
	$(".address01").click(function(){
		$(".choosed01").show();		
		$(".Divgray").show();
		})
    $(".close").click(function(){
		$(".choosed01").hide();
		$(".Divgray").hide();
		})	
	$(".car01").click(function(){
		$(".mycar01").show();		
		$(".Divgray").show();
		})
    $(".close").click(function(){
		$(".mycar01").hide();
		$(".Divgray").hide();
		})	
	$(".contract01").click(function(){
		$(".contracts01").show();		
		$(".Divgray").show();
		})
    $(".close").click(function(){
		$(".contracts01").hide();
		$(".Divgray").hide();
		})	 
})		
		
</script>