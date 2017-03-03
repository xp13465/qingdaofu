<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use wx\widget\wxHeaderWidget;
$categorys = isset($data['category'])?explode(',',$data['category']):'';
$entrusts  = isset($data['entrust'])?explode(',',$data['entrust']):'';
$category = ['1'=>'房产抵押','2'=>'机动车抵押','3'=>'合同纠纷','4'=>'其他'];//债权类型（1房产抵押，2机动车抵押，3合同纠纷，4其他）
$entrust = ['1'=>'诉讼','2'=>'清收','3'=>'债权转让','4'=>'其他'];//（1诉讼,2清收3,债权转让，4其他）
if($data){
	$gohtml = '<a href="javascript:void(0);" class="submits" data-productid="'.$data['productid'].'">保存</a>';
	$backurl= true;
	$title= '编辑信息';
}else{
	$gohtml = '<a href="javascript:void(0);" class="submits" data-productid="">发布</a>';
	$backurl= false;
	$title= '发布债权';
}

?>
<?=wxHeaderWidget::widget(['title'=>$title,'gohtml'=>$gohtml,'backurl'=>$backurl,'homebtn'=>false,'fork'=>isset($data)&&$data['status']=='10'?false:true,'reload'=>false])?>
<section>
<?php ActiveForm::begin(['id'=>'productCreate'],'post', ['enctype' => 'multipart/form-data']) ?>
<?= Html::hiddenInput('productid',Yii::$app->request->get('productid'))?>
  <div class="zqlx">
    <div class="zqlx-l">
      <p>债权类型</p>
      <p class="more">(多选)</p>
    </div>
    <div class="zqlx-r bond">
      <ul>
	    <?php foreach($category as $key=>$value): ?>
			<li <?php if($categorys){ echo in_array($key,$categorys)?'class="activing act"':'class="activing"';}else{echo 'class="activing"';}?>>
				<input type='checkbox' name="category[]" <?php if($categorys){ echo in_array($key,$categorys)?'checked="false"':'';}?> value="<?= $key ?>"  class='infor_c'>
				<a href="javascript:void(0);" class="active"><?= $value ?></a>
			</li>
		<?php endforeach; ?>
        <li class="last focus1">
			<?= Html::input('text','category_other',isset($data['category_other'])?$data['category_other']:'',['placeholder'=>'不超过5个字','maxlength'=>'5','style'=>'padding-left:10px;width:100%;height:30px;line-height:30px;font-size:14px;border-radius:3px;border:1px solid #ddd;']);?>
        </li>
      </ul>
    </div>
  </div>
  <div class="zqlx">
    <div class="zqlx-l">
      <p>委托事项</p>
      <p class="more">(多选)</p>
    </div>
    <div class="zqlx-r Matter">
      <ul>
		<?php foreach($entrust as $key=>$value): ?>
		    <li <?php if($entrusts){ echo in_array($key,$entrusts)?'class="activing act"':'class="activing"';}else{echo 'class="activing"';}?>>
				<input type='checkbox' name="entrust[]" <?php if($entrusts){ echo in_array($key,$entrusts)?'checked="false"':'';}?> value="<?= $key ?>"  class='infor_c'>
				<a href="javascript:void(0);" class="active"><?= $value ?></a>
			</li>
		<?php endforeach; ?>
        <li class="last focus2">
		    <?= Html::input('text','entrust_other',isset($data['entrust_other'])?$data['entrust_other']:'',['placeholder'=>'不超过5个字','maxlength'=>'5','style'=>'padding-left:10px;width:100%;height:30px;line-height:30px;font-size:14px;border-radius:3px;border:1px solid #ddd;']);?>
        </li>
      </ul>
    </div>
  </div>
  <ul class="infor" style="margin-top:10px;">
    <li>
		<div class="infor_l"> <span>委托金额</span>
			<?= Html::input('text','account',isset($data['accountLabel'])?intval($data['accountLabel']):'',['placeholder'=>'请输入']);?>
			<?= Html::tag('a','万',['style'=>'font-size:16px;color:#333;margin-right:15px;float:right;']);?>
		</div>
    </li>
    <li>
      <div class="infor_l"> <span>费用类型</span>
        <div class="gufeng">
          <ul>
            <li <?php if(isset($data['type'])&&$data['type']=='2'){echo 'class="radioBtn"';}else{echo 'class="radioBtn checked"';}?> >
				<input type='radio' name='type' value='1' class='btn_r' <?php if($data['type']){echo $data['type']!='2'?'checked="checked"':'';}else{echo 'checked="checked"';}?>  >
				<?= Html::tag('a','固定费用',['class'=>'gu']); ?>
			</li>
            <li <?php if(isset($data['type'])&&$data['type']=='2'){echo 'class="radioBtn checked"';}else{echo 'class="radioBtn"';}?> >
				<input type='radio' name='type' value='2' class='btn_r' <?php if(isset($data['type'])&&$data['type']=='2'){echo 'checked="checked"';}?>  >
				<?= Html::tag('a','风险代理',['class'=>'feng']); ?>
			</li>
          </ul>
        </div>
      </div>
    </li>
    <li class="gu01" style='border-bottom:none' >
      <div class="infor_l"> <span>固定费用</span>
		<?= Html::input('text','typenum1',isset($data['type'])&&$data['type']=='1'?intval($data['typenumLabel']):'',['placeholder'=>'请输入','style'=>'width:55%;']); ?>
		<?= Html::tag('a','万',['class'=>'suffix']);?>
      </div>
    </li>
    <li class="feng01" style="display:none;">
      <div class="infor_l"> <span>风险代理</span>
		<?= Html::input('text','typenum2',isset($data['type'])&&$data['type']=='2'?intval($data['typenum']):'',['placeholder'=>'请输入','style'=>'width:55%;']); ?>
		<?= Html::tag('a','%',['class'=>'suffix']);?>
      </div>
    </li>
  </ul>
  <ul class="infor" style="margin-top:10px;">
    <li>
      <div class="infor_l"> <span>违约期限</span>
		<?= Html::input('text','overdue',isset($data['overdue'])?intval($data['overdue']):'',['placeholder'=>'请输入','style'=>'width:55%;']);?>
        <?= Html::tag('a','月',['class'=>'suffix']);?>
      </div>
    </li>
  </ul>
  <ul class="infor" style="margin-top:10px;">
    <li class="bot_line">
      <div class="infor_l"> <span>合同履行地</span>
		<?= Html::dropDownList(
				'province_id',isset($data['province_id'])?$data['province_id']:'310000',
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
  </ul>
  <?php ActiveForm::end() ?>
</section>

<style>
.infor li span:first-child {
    width: 30%;
    float: left;
    display: inline-block;
    font-size: 16px;
    color: #333;
}
.zqlx-r ul li:last-child{width:63%;height:30px;float:left;margin-left:3%;background:#fff;}
.infor_c{display:none;}

.layui-layer-btn{padding: 10px 10px 10px;pointer-events: auto;border-top: 1px solid #ddd;}
.layui-layer-btn a {
    height: 35px;
    line-height: 35px;
    margin: 0 6px;
    width: 120px;
    text-align: center;
    border: 0px solid #dedede;
    background: #fff;
    color: #0da3f9;
    border-radius: 2px;
    font-weight: 400;
    cursor: pointer;
    text-decoration: none;
}
.layui-layer-btn .layui-layer-btn0 {background-color: #fff;color: #0da3f9;}

</style> 

<script>
$(document).ready(function(){
	var city_id = "<?= isset($data['city_id'])?$data['city_id']:''?>";
	var district_id = "<?= isset($data['district_id'])?$data['district_id']:''?>";
	$('#province').change(function(){
		var province_id = $(this).val();
		$.ajax({
			url:'<?= Yii\helpers\Url::toRoute('/product/city'); ?>',
			type:'post',
			data:{province_id:province_id},
			dataType:'json',
			success:function(json){
				var html='<option value="0" selected="">请选择...</option>';
				if(json['code'] == '0000'){
					$('#city').html(html+json['html']);
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
						$('#district').html(html+json['html']);
					if(district_id){
						$('#district').val(district_id)
					}
				}
			}
		})
	})
	$('.bond ul li:eq(3),.Matter ul li:eq(3)').click(function(){
		var checked = $(this).children("input").prop("checked");
		var color = ($(this).next().children('input').val())?"":"red";
		if(checked){
			$(this).next().children('input').css("color",color).focus();
		}else{
			$(this).next().children('input').css("color",'').val("");
		}
	})
	$('.zqlx-r .last input').blur(function(){
		$(this).css("color","");
		if($(this).val()){
			$(this).parents().children("li").eq(3).addClass("act").children('input').attr('checked','checked');
		}else{
			$(this).parents().children("li").eq(3).removeClass("act").children('input').attr('checked',false);
		}
	})
	
	
	$('.submits').click(function(){
		var productid = $(this).attr('data-productid');
		if(productid){
			var url = '<?= Yii\helpers\Url::toRoute('/product/edit'); ?>';
		}else{
			var url ='<?= Yii\helpers\Url::toRoute('/product/product-param'); ?>';
		}
		$.ajax({
			url:url,
			type:'post',
			data:$('#productCreate').serialize(),
			dataType:'json',
			success:function(k){
				if(k.code == '0000'){
					if(productid){
						layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>编辑成功！",{time:2000},function(){history.go(-1)});
					}else{
						layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>发布成功！",{time:2000},function(){location.href='<?= yii\helpers\Url::toRoute('wrelease/details')?>?productid='+k.result.productid;});
					}
					
				}else{
					layer.msg(k.msg);
				}
				
			}
		})
	})
	
	$('.icon-close').click(function(){
        var productid = '<?= Yii::$app->request->get('productid') ?>';
		if(productid){
			var url = '<?= Yii\helpers\Url::toRoute('/product/preservation'); ?>';
		}else{
			var url ='<?= Yii\helpers\Url::toRoute('/product/draft'); ?>';
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
								layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>修改成功！",{time:2000},function(){history.go(-1)});
							}else{
								layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>保存成功！",{time:2000},function(){history.go(-1)});
							}
							
					}else{
						layer.msg(k.msg);
					}
				}
			})
		},function(){
			history.go(-1)
		})
	})
})




$(document).ready(function(){
	$(".activing a").click(function(){
		var $child = $(this).parent().children("input")
		if($child.prop("checked")){
			$child.prop("checked",false)
		}else{
			$child.prop("checked",true)
		}
		$child.trigger("change")
	})
	$(".infor_c").change(function(){
		if($(this).prop("checked")){
			$(this).parent('.activing').addClass("act")
		}else{
			$(this).parent('.activing').removeClass("act")
		}
	})
	$(".radioBtn").click(function(){
		var $child = $(this).children("input")
		$child.prop("checked",true).trigger("change")
	})
	$(".btn_r").change(function(){
		$(this).parent().siblings().removeClass('checked')
		$(this).parent().addClass("checked")
	})
	$(".btn_r").change(function(){
		var type = $(this).next("a").attr("class");	
		switch(type){
			case 'gu':
				$(".gu01").show();
				$(".feng01").hide();
				break;
			case 'feng':
				$(".feng01").show();
				$(".gu01").hide();
			break;
		}
	})
})
</script>