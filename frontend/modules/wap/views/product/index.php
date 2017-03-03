<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use frontend\services\Func;
  
$this->title = "债权超市";
$this->params['breadcrumbs'][] = $this->title;
$action = Yii::$app->controller->action->id;
$queryParams = Yii::$app->request->queryParams;
if(isset($queryParams["_pjax"]))unset($queryParams["_pjax"]);
?>
<script>
  var queryParams = {};
</script>
<div class="list_con">
	<?php
	 Pjax::begin([
		 'id' => 'post-grid-pjax',
	 ]);
	?>
  <div class="list_con1">
    <ul class="area">
      <li class="one"> 
		<span style="color:#999;">区域:</span><?= Html::dropDownList(
			'province_id',$province_id,
			ArrayHelper::map($province,'provinceID','province'),
			['id'=>'province','class'=>'selects selects_three','placeholder'=>"请选择",'style'=>"min-width:150px;",'prompt'=>'不限']
		)?>
	   <?= Html::dropDownList(
				'city_id',$city_id,
				ArrayHelper::map($city,'cityID','city'), 
				['id'=>'city','class'=>'selects selects_three','placeholder'=>"请选择",'style'=>"min-width:150px;",'prompt'=>'不限']
		)?>
		<?= Html::dropDownList(
				'district_id',$district_id,
				ArrayHelper::map($district,'areaID','area'), 
				['id'=>'district','class'=>'selects selects_three','placeholder'=>"请选择",'style'=>"min-width:150px;",'prompt'=>'不限']
		)?>
		<a id="arealink" href="" style="display:none"></a>
		</li>
		<li class="two"> 
			<span style="color:#999;">金额:　</span><span><a href="<?=$temp = Func::searchParamsRoute("",["account"=>""])?>" class="default<?=!$temp||!isset($queryParams['account'])?" current":""?>" >不限</a></span>
			<span><a href="<?=$temp = Func::searchParamsRoute("",["account"=>2])?>"  class="<?=!$temp?"current":""?>" >30万以下</a></span>
			<span><a href="<?=$temp = Func::searchParamsRoute("",["account"=>3])?>"  class="<?=!$temp?"current":""?>" >30-100万</a></span>
			<span><a href="<?=$temp = Func::searchParamsRoute("",["account"=>4])?>"  class="<?=!$temp?"current":""?>" >100-500万</a></span>
			<span><a href="<?=$temp = Func::searchParamsRoute("",["account"=>5])?>"  class="<?=!$temp?"current":""?>" >500万以上</a></span>
		</li>
		<li class="four">
			<span style="color:#999;">状态:　</span><span><a href="<?=$temp = Func::searchParamsRoute("",["status"=>''])?>" class="default<?=!$temp||!isset($queryParams['status'])?" current":""?>" >不限</a></span>
			<span><a href="<?=$temp = Func::searchParamsRoute("",["status"=>2])?>"  class="<?=!$temp?"current":""?>" >发布中</a></span>
			<span><a href="<?=$temp = Func::searchParamsRoute("",["status"=>3])?>"  class="<?=!$temp?"current":""?>" >已撮合</a></span>
		</li>
    </ul>
  </div>
  <div class="list">
    <table>
		<colgroup>
            <col width='100px'/>
            <col width='100px'/>
            <col width='180px'/>
            <col width='180px' />
            <col width='100px' />
            <col width='180px' />
            <col width='100px' />
        </colgroup>
		<thead>
			<tr>
				<th>委托费用</th>
				<th>委托金额</th>
				<th>债权类型</th>
				<th>委托事项</th>
				<th>违约日期</th>
				<th>合同履行地</th>
				<th>操作</th>
			</tr>
		</thead>
		<?php 
		if($curCount){
		foreach($data as $value): ?>
			<tr>
				<td class="<?=$value['status']==10?"red":"gray"?>"><?=$value['typenumLabel']?><i><?= $value['typeLabel'] ?></i></td>
				<td class="<?=$value['status']==10?"black":"gray"?>"><?= $value['accountLabel']?><i>万</i></td>
				<td><p class="omit"><?= $value['categoryLabel'] ?><p></td>
				<td><p class="omit"><?= $value['entrustLabel'] ?></p></td>
				<td><?=$value['overdue']?>个月</td>
				<td><p class="omit"><?=$value['addressLabel']?></p></td>
				<td>
					<a href="#" <?php if(!$value['applySelf']&&in_array($value['status'],['10'])){
							echo 'class="application"';
						}else if($value['applySelf']&&$value['applySelf']['status']=="10"&&in_array($value['status'],['10'])){
							echo 'class="cancel" data-applyid='.$value['applySelf']['applyid'].'';
						}else if($value['applySelf']&&$value['applySelf']['status']=="20"){
							echo 'class="over"';
						} ?> data-id="<?=$value['productid']?>">
					<?= in_array($value['status'],['10'])?($value['applySelf']?($value['applySelf']['status']=="10"?"取消申请":"面谈中"):"立即申请"):'<img src="/bate2.0/images/yi.png">'?>
					</a>
				</td>
			</tr>
		<?php endforeach;
		}else{ ?>
			<tr>
				<td colspan="7">无</td>
			</tr>
		<?php }?>
    </table>
	<div class="pages clearfix ">
		<div class="fenye" style="margin-top:30px"> 
		<span class="fenyes" style="font-size:12px;margin:0px 35px -41px;">共<?=$provider->pagination->totalCount?>条记录，第<?=$provider->pagination->page+1?>/<?=$provider->pagination->pageCount?>页</span>
		 <?= yii\widgets\LinkPager::widget([
			'pagination' => $provider->pagination
		]) ?>
		</div>
	</div>
  </div>
  <script>
  var queryParams = <?=json_encode($queryParams)?>;
  </script>
  <?php Pjax::end() ?>
</div>
<script>
$(document).ready(function(){
	$(document).on("change","#province,#city,#district",function(){
		var province_id = $("#province").val();
		var city_id = $("#city").val();
		var district_id = $("#district").val();
		if($(this).attr("id")=="province"){
			city_id ='';
			district_id ='';
		}
		if($(this).attr("id")=="city"){
			district_id ='';
		}
		var baseurl="<?=Url::toRoute("")?>";
		var url="";
		queryParams['province_id'] = province_id
		queryParams['city_id'] = city_id
		queryParams['district_id'] = district_id
		
		for(k in queryParams){
			url += url?"&":"?";
			url +=k+"="+queryParams[k];
		}
		$("#arealink").attr("href",baseurl+url).click()
		//
		// console.log(queryParams);
		// console.log(baseurl+url);
		
	})
	
})
</script>