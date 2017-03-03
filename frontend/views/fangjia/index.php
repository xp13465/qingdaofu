<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
// use app\models\protectright;
use yii\helpers\Url;
// use kartik\widgets\ActiveForm;
// use kartik\form\ActiveField;
// use kartik\editable\Editable;

$this->title = '评估结果';
$this->params['breadcrumbs'][] = $this->title;
// $category  = \common\models\CreditorProduct::$category;
// $status  = protectright::$status;

 
?>
<style>
.info_noread table td {border: 0px solid #e5e5e5;color:#333;font-size:14px;height:50px;overflow:hidden;line-height:16px;}
 table .table_info{background:#f5f8fd;}
 table td.tab-top{font-size:14px;font-weight:bolder;color:#666;}
 .search{width:245px;height:40px;}
 .search a{width:80px;height:40px;border:1px solid #ddd;border-left:0px solid #ddd;font-size:16px;color:#333;text-align:center;line-height:40px;background:#f5f8fd;position:absolute;}
#pgForm table td{   padding-left: 0px;line-height: 24px; font-size:14px;    height: 25px}
#pgForm table td input{text-indent:5px;}
.search{margin-left: 20px;text-align: left;}
</style>
 <div class="fa01"> 
            <p class="fa01_img"> <span class="jiedan">房产评估结果</span>
            </p>
            <div style="position:relative;">
               <a class="btn-select" href="javascript:void(0)"> <span class="cur-select">房产评估</span></a>
            </div>
          </div>
      <div class="info_noread">
      
      <div class="search">
      <input type="text" id='keywords' name = 'keywords' value="<?=Yii::$app->request->get("keywords")?>" placeholder="请输入评估地址" style="width:200px;height:40px;border:1px solid #ddd;">
      <a id='search'  href="javascript:void()">搜索</a>
      </div>
        <table style="border: 1px solid #e5e5e5;margin-top:20px;">
		<colgroup>
			<col width='130px'/>
			<col width='115px'/>
			<col width='150px'/>
			<col width='70px' />
			<col width='100px' />
			<col width='60px' />
			<col width='95px' />
		</colgroup>
          <tbody>
            <tr class="table_info">
              <td class="tab-top">时间</td>
              <td class="tab-top">区域</td>
              <td class="tab-top">房源地址</td>
              <td class="tab-top">面积</td>
              <td class="tab-top">均价</td>
              <td class="tab-top">楼层</td>
              <td class="tab-top">评估价格</td>
            </tr>
			 <?php foreach($data->models as $item=>$value): ?>
			 
			
            <tr <?=($item==0||$item%2===0)?"":"class='table_info'"?>>
              <td><?=$value->create_time?date("Y-m-d H:i",$value->create_time):''?></td>
              <td><?=$value->city?>市<?=isset($citySelect[$value->district])?$citySelect[$value->district]:''?></td>
              <td><?=$value->address?><?=$value->buildingNumber?>号<?=$value->unitNumber?>室</td>
              <td><?=$value->size?$value->size:''?>m<sup>2</sup></td>
              <td>
			  <i style="color:#ff981f;margin-right: 0;    float: none;"><?php if($value->totalPrice){
				  echo round($value->totalPrice/$value->size,0)."";
			  }?></i>元/平
			  </td>
              <td><?=$value->floor?>/<?=$value->maxFloor?></td>
              <td style="color:#148fcc;"><?=$value->totalPrice?(round($value->totalPrice/10000)."万"):''?></td>
            </tr>
			<?php endforeach;?>
              
          </tbody>
        </table> 
        <div class="pages clearfix ">
			<div class="fenye" style="margin-top:30px"> 
			<span class="fenyes" style="font-size:12px;margin:0px 35px -41px;">共<?=$data->pagination->totalCount?>条记录，第<?=$data->pagination->page+1?>/<?=$data->pagination->pageCount?>页</span>
			 <?= yii\widgets\LinkPager::widget([
				'pagination' => $data->pagination
			]) ?>
			</div>
		</div>
    </div>
<?php

 /*
<script type="text/javascript">
 $(document).ready(function(){
    $('.preview').click(function(){
		var title = $(this).text();
		var id = $(this).attr('data_id');
		$.ajax({
			url:"<?= yii\helpers\Url::toRoute('/policy/picturecategory')?>",
			type:'post',
			data:{id:id,'_csrf':'<?=Yii::$app->getRequest()->getCsrfToken()?>'},
			dataType:'json',
			success:function(json){
				if(json.code == '0000'){
				   var   photojson = {
						"status": 1,
						"msg": title,
						"title": title,
						"id": 0,
						"start": 0,
						"data": []
					};
					$.each(json.data.data,function(item){
						photojson.data.push({
							"alt": title,
							"pid": 0,
							"src": json.data.data[item],
							"thumb": ""
						})
					  
					})  
					// console.log(photojson)
					layer.photos({
						// area: ['auto', '80%'],
						photos: photojson
					});
				}	
			}
		})
    })
});
</script>
<?php */?>
<script id='fangjia_totalprice_form' type='text/template'>
	<form id="pgForm">
		<table border="0" align="center" cellpadding="10" cellspacing="0">
			<tr><td style="width:65px;float:left;padding-top:5px;">*类型</td>
				<td colspan="3"><?= Html::dropDownList('district', '', ['0'=>'普通住宅'],["style"=>"line-height: 24px;width:300px;height:25px;border-radius:3px;margin-top:5px;padding:0;"]) ?></td>
			</tr>
			<tr><td style="width:65px;float:left;padding-top:15px;">*区域</td>
				<td colspan="3"><?= Html::dropDownList('district', '', $citySelect,["style"=>"line-height: 24px;width:300px;height:25px;border-radius:3px;margin-top:15px;padding:0;"]) ?></td>
			</tr>
			<tr><td style="width:65px;float:left;padding-top:15px;">*地址</td>
				<td colspan="3"><input type="text" name="address" style="width:300px;height:25px;border-radius:3px;margin-top:15px;"></td>
			</tr>
			<tr><td style="padding-top:15px;">*楼栋</td>
			<td><input type="text" name="buildingNumber" style="width:117px;height:25px;border-radius:3px;margin-top:15px;"></td>
			<td style="padding-top:10px;padding-left:15px;">号</td><td><input type="text" name="unitNumber" style="border-radius:3px;width:117px;height:25px;margin-top:15px;"></td><td style="padding-top:15px;padding-left:15px;">室</td></tr>
			<tr><td style="width:65px;float:left;padding-top:15px;">*面积</td>
          <td colspan="3"><input type="text" name="size" style="width:300px;height:25px;border-radius:3px;margin-top:15px;"></td>
			<td style="padding-top:15px;padding-left:15px;">M<sup>2</sup></td></tr>
			<tr><td style="padding-top:15px;">*楼层</td> <td><input type="text"  name="floor" style="border-radius:3px;width:117px;height:25px;margin-top:15px;"></td><td style="width:45px;float:left;padding-top:15px;padding-left:15px;">层/共</td> <td><input type="text"  name="maxFloor" style="border-radius:3px;width:117px;height:25px;margin-top:15px;"></td><td style="padding-top:15px;padding-left:15px;">层</td></tr>
	</table>
	</form>
</script>
<script id='fangjia_totalprice_result' type='text/x-jquery-tmpl'>
	<table height="100%" width="450" border="0" align="center" cellpadding="10" cellspacing="0">
		<div style="width:450px;height:110px;"><div style="width:180px;height:110px;float:left;border-right:1px dashed #dedede;"><p style="text-align:center;margin-top:25px;">评估结果</p><p style="font-size:35px;color:#e0ba29;text-align:center;margin-top:15px;">${$item.priceFormat($data.totalPrice)}万</p></div>
		<div style="width:225px;height:110px;float:left;text-indent:30px;"><p style="color:#0da3f8;text-align:left;">${$data.address}${$data.buildingNumber}号${$data.unitNumber}室</p><p style="text-align:left;">均价：${Math.round($data.totalPrice/$data.size)}元/平米</p><p style="text-align:left;">面积：${$data.size}平米</p><p style="text-align:left;">楼层：第${$data.floor}层，共${$data.maxFloor}层</p></div>
		</div>
	</table>
</script>
<script>
   
	$(document).ready(function(){
		function pg(){
			layer.confirm($("#fangjia_totalprice_form").html(), {
				area:["450px","auto"],
			  btn: ['立即评估'] //按钮
			}, function(){
				// layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
				// console.log($("#pgForm").serialize());return;
				 $.ajax({
					type: "POST",
					url: "<?php echo Url::toRoute(['/fangjia/totalprice'])?>",
					data: $("#pgForm").serialize(),
					dataType: "json",
					success: function(data){ 
						if(data.code=='0000'){ 
							var result = $('#fangjia_totalprice_result').tmpl(data.data, {
								priceFormat: function (price) {
									return parseInt(price/10000);
								}
							}).html()
							
							layer.confirm(result, {
							  btn: ['继续评估'] //按钮
							}, function(){
								pg()
							});
						}else{
							layer.msg(data.msg);
						}
					}
				 });
			});
			
		}
	$(".btn-select").click(function(){
		pg()
	})
	$("#search").click(function(){
		var keywords = $("#keywords").val();
		location.href = "<?php echo Url::toRoute(['index','keywords'=>''])?>"+keywords
	})
	
})
 
</script>
