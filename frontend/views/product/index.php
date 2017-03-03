<!DOCTYPE HTML PUBLIC “-//W3C//DTD HTML 4.01 Transitional//EN”　”http://www.w3.org/TR/html4/loose.dtd”><?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use frontend\services\Func;
  
// $this->title = "债权超市";
// $this->params['breadcrumbs'][] = $this->title;
$action = Yii::$app->controller->action->id;
$queryParams = Yii::$app->request->queryParams;
if(isset($queryParams["_pjax"]))unset($queryParams["_pjax"]);
$userid = Yii::$app->user->getId();
?>
<style>
.select-one{position:absolute;width:40px;height:36px;left:218px;top:0px;z-index:999;background:url(/bate2.0/images/selectbg3.png) no-repeat;}

</style>
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
      <li class="one" style="position:relative;"> 
		<span style="color:#999;">区域:</span><?= Html::dropDownList(
			'province_id',$province_id,
			ArrayHelper::map($province,'provinceID','province'),
			['id'=>'province','class'=>'selects selects_three','placeholder'=>"请选择",'style'=>"min-width:150px;",'prompt'=>'不限']
		)?>
        <!--<div class="select-one"></div>-->
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
			<tr class='productList' data-productid='<?= $value['productid']?>' >
				<td class="<?=$value['status']==10?"red":"gray"?>"><?=$value['typenumLabel']?><i><?= $value['typeLabel'] ?></i></td>
				<td class="<?=$value['status']==10?"black":"gray"?>"><?= $value['accountLabel']?><i>万</i></td>
				<td><p class="omit"><?= $value['categoryLabel'] ?><p></td>
				<td><p class="omit"><?= $value['entrustLabel'] ?></p></td>
				<td><?=$value['overdue']?>个月</td>
				<td><p class="omit"><?=$value['addressLabel']?></p></td>
				<td>
					<a href="javascript:void(0);" <?php if(!$value['applySelf']&&in_array($value['status'],['10'])&&$value['create_by']!= $userid){
							echo 'class="application"';
						}else if($value['applySelf']&&$value['applySelf']['status']=="10"&&in_array($value['status'],['10'])){
							echo 'class="cancel" data-applyid='.$value['applySelf']['applyid'].'';
						}else if($value['applySelf']&&$value['applySelf']['status']=="20"){
							echo 'class="over"';
						}else if($value['create_by']== $userid&&$value['status']=="10"){
							echo 'class="over"';
						}else{
							echo 'class="match"';
						} ?> data-id="<?=$value['productid']?>">
					<?= in_array($value['status'],['10'])?($value['applySelf']?($value['applySelf']['status']=="10"?"取消申请":"面谈中"):"立即申请"):''?>
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
		$("#arealink").attr("href",baseurl+url).html(baseurl+url).click()
		
		if (!!window.ActiveXObject || "ActiveXObject" in window){
			// alert('你是使用IE')
			location.href = baseurl+url;
		}
		//
		// console.log(queryParams);
		// alert(baseurl+url);
		
	})
	
	$(document).on('click','.productList td',function(){
		if($(this).index()==6)return application;
		var productid = $(this).parent().attr('data-productid');
		var url = "<?= Url::toRoute('/product/detail')?>?productid="+productid;
		window.open(url)
	})
	var _csrf = "<?= Yii::$app->request->csrfToken ?>";
	var application = $(document).on("click",".application",function(){
		
		<?php if(Yii::$app->user->isGuest){?>
				$.msgbox({
                    height: 120,
                    width: 230,
                    content: '<p style="text-align:center">您还未登录,请先<a href="<?php echo Url::toRoute('/site/login')?>" style="color:#FF9700">登录</a></p>',
                    type: 'confirm',
                    onClose: function (v) {
                        if (v) {
                            location.href = "<?php echo Url::toRoute('/site/login')?>";
                        }
                    }
                });
				return false;
		<?php }else{?>
		
		
		
			var obj = $(this)
			 $.ajax({
                url:'<?php echo yii\helpers\Url::toRoute('/product/apply')?>',
                type:'post',
				async:false,
                data:{productid:$(this).attr("data-id"),_csrf:_csrf},
                dataType:'json',
                success:function(json){
					if(json.code=='0000'){
						obj.addClass("cancel").removeClass("application").html("取消申请").attr("data-applyid",json.data.applyid).parent().addClass("over")
						
						if(json.data.certification==1){
							layer.confirm(json.msg,{btn:["取消","前往认证"],title:false,closeBtn:false},function(){layer.closeAll();},function(){location.href="<?= yii\helpers\Url::toRoute('/certifications/index')?>"});
						}else{
							layer.msg(json.msg,{time:2000},function(){window.location.reload()});
						}
						
						
					}else{
						layer.msg(json.msg)
					}
                }
            })
		<?php }?>
		})
		
	$(document).on("click",".cancel",function(){
			var applyid = $(this).attr('data-applyid');
			var obj = $(this)
			layer.confirm("确定取消？",{title:false,closeBtn:false},function(){
				var index = layer.load(1, {
				   time:2000,
					shade: [0.4,'#fff'] //0.1透明度的白色背景
				});
				$.ajax({
				url:'<?= yii\helpers\Url::toRoute('/product/apply-cancel')?>',
				type:'post',
				data:{applyid:applyid,_csrf:_csrf},
				dataType:'json',
				success:function(json){
					layer.close(index)
					if(json.code == '0000'){
						layer.close(index);
						obj.addClass("application").removeClass("cancel").html("立即申请").attr("data-applyid","").parent().removeClass("over")
						layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>取消成功",{time:2000},function(){/*window.location.reload()*/});

					}else{
						layer.msg(json.msg);
						layer.close(index);
						}
					}
				})
			})
		})
	
})
</script>