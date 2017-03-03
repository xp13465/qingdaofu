<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SolutionsealSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '垫资解查封管理';
$this->params['breadcrumbs'][] = $this->title;
$type = isset($type)&&$type?$type:'';
if(!$type){
	$data = [
			'view'=>function($url,$data){
					return Html::a('<span class="glyphicon glyphicon-eye-open"></span>','/solutionseal/view/'.$data->solutionsealid, ['title' => '跟新','data-id'=>$data->solutionsealid]);
				},
			'auditBtns'=>function($uid,$data){
					 return $data->auditBtns($uid);
				 },
			];
}else{
	$data = [
	     'view'=>function($url,$data){
			 return Html::a('<span class="glyphicon glyphicon-eye-open"></span>','/solutionseal/view/'.$data->solutionsealid, ['title' => '跟新','data-id'=>$data->solutionsealid]);
		 }
	];
}

?>
<div class="solutionseal-index">
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
             [
				'class' => 'yii\grid\SerialColumn',
				'headerOptions' => ['width' => '50'],
			],

            [
				'attribute' => 'personnel_name',
				'value' =>  function ($data) {
					$html = $data->personnel_name;
					if($data->personnelName && $data->personnelName == $data->personnel_name){
						$html.='(员工)';
					}else if($data->personnelName){
						$html.="({$data->personnelName})";
					}else{
						$html.='(未关联)';
					}
					return $html;
				},
				'headerOptions' => ['width' => '120'],
			],
			[
				'attribute' => 'entrust_other',
				'value' => "entrust_other",
				'headerOptions' => ['width' => '120'],
			],
			[
				'attribute' => 'account',
				'value' => function ($data) {
					return $data->account;
				},
				'headerOptions' => ['width' => '70'],
			],
			
			[
				'attribute'=>'typenum',
				'label'=>'服务佣金',
				'value'=>"fuwufei",
				'options' => ['style' => 'width:110px;'],
			],	
			[
				'attribute' => 'earnestmoney',
				'value' => function ($data) {
					return $data->earnestmoney>0?($data->actualamount.'元'):'';
				},
				'headerOptions' => ['width' => '80'],
			],
			[
				'attribute' => 'pact',
				'format' => 'raw', 
				'value' => function ($data) {
					$pact = $data->getPact();
					if($pact){
						return Html::a('查看','javascript:void(0)', ['class'=>'btnLates btn btn-info btn-xs',"data-pact"=>$pact , 'title' => '查看' ] );
					}else{
						return '';
					}
				},
				'headerOptions' => ['width' => '80'],
			],
			[
                'attribute' => 'status',
				'label' => '状态',
                'filter' => \app\models\Solutionseal::$status,
                'value' => function ($data) {
					$status = \app\models\Solutionseal::$status;
					return isset($status[$data->status])?$status[$data->status]:"";
					 
                },
                'format' => 'raw',
				'options' => ['style' => 'width:100px;'],
            ],
			[
				'attribute' => 'productid',
				'filter'=>["1"=>"未生成","2"=>"已生成"],
				'format' => 'raw', 
				'value' => function ($data) {
					$html = "";
					if($data->productid){
						$html.=   "".Html::a('查看', ["/products/view","id"=>$data->productid], ['target'=>'_blank','class'=>'btn btn-info btn-xs' ,"data-productid"=>$data->productid , 'title' => '查看' ] );
					}elseif($data->status=="40"){
						$pact = $data->getPact($type=true);
						 $html.=   "".Html::a('生成', 'javascript:void(0)', ['class'=>'btnGenerate btn btn-info btn-xs' ,"data-solutionsealid"=>$data->solutionsealid, 'title' => '生成','data-pact'=>$pact] );
					}else{
						$html.=   "";
					}
					return $html;
				},
				'headerOptions' => ['width' => '60'],
			],
			[
				'attribute' => 'number',
				'value' => function ($data) {
					return $data->number;
				},
				'headerOptions' => ['width' => '140'],
			],
			[
				'attribute' => 'borrowmoney',
				'value' => function ($data) {
					return $data->borrowmoney>0?($data->borrowmoney.'元'):'';
				},
				'headerOptions' => ['width' => '90'],
			],
			[
				'attribute' => 'backmoney',
				'value' => function ($data) {
					return $data->backmoney>0?($data->backmoney.'元'):'';
				},
				'headerOptions' => ['width' => '90'],
			],
			[
				'attribute' => 'actualamount',
				'value' => function ($data) {
					return $data->actualamount>0?($data->actualamount.'元'):'';
				},
				'headerOptions' => ['width' => '90'],
			],
			[
				'attribute' => 'payinterest',
				'value' => function ($data) {
					return $data->payinterest>0?($data->payinterest.'元'):'';
				},
				'headerOptions' => ['width' => '90'],
			],
			[
				'attribute' => 'closeddate',
				'value' => function ($data) {
					return $data->closeddate!="0000-00-00"?date('Y-m-d',$data->closeddate):"";
				},
				'headerOptions' => ['width' => '100'],
			],
            ['class' => 'yii\grid\ActionColumn',
			 'header'=>'操作',
			 'template'=>'{view}{auditBtns}',
			 'buttons'=>$data,
			 'options' => ['style' => 'width:100px;'],
			],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

<style>
/**{margin:0px;padding:0px;}
ul li{list-style:none;}
a{text-decoration:none;}
.btn{height:30px;margin:auto;}
.btn ul li{width:80px;height:30px;border-radius:3px;background:#2e8ded;float:left;margin-left:20px;}
.btn ul li a{font-size:14px;color:#fff;text-align:center;line-height:30px;}*/

.blue{border-radius:5px;}
.blue .layui-layer-content{padding:20px;}
.blue .layui-layer-title{color:#fff;background:#2e8ded;}
.blue .layui-layer-btn{text-align:left;}
.blue .text ul li span{float:left;margin-right:20px;}
.blue .text ul li input{width:250px;height:30px;border:1px solid #ddd;border-radius:3px;}
.blue .text ul li textarea{width:250px;height:80px;border:1px solid #ddd;border-radius:3px;margin-top:20px;}
</style>
<!--面谈成功-->

<script id='success' type='text/template'>
<div class="text">
     <ul>
		<li>
		  <span style="margin-top:5px;">客户姓名</span>
		    <input type="text" name="custname" style="width:230px;" >
		</li>
		<li>
		  <span style="margin-top:25px;">联系方式</span>
		    <input type="text" name="custmobile" maxlength="11" style="width:230px;margin-top:20px;">
		</li>
	    <li>
		  <span style="margin-top:25px;">金&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;额</span>
		    <input type="text" name="account" style="width:230px;margin-top:20px;" >
			<i style="float:right;margin-top:23px;">元</i>
		</li>
		<li>
		<li>
			<span style="margin-top:25px;">金&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;额</span>
			<select style="width:100px;height:30px;margin-right:20px;" id="mySelect">
				<option value="1">固定费用</option><option value="2">固定费率</option>
			</select>
			<input type="text" name="typenum" style="width:110px;margin-top:20px;">
			<i style="float:right;margin-top:25px;" id="money">元</i>
		</li>
		<li>
			<span style="margin-top:20px;">备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注</span>
			<textarea style="width:230px;" name='memo'></textarea>
		</li>
		</ul>
</div>
</script>

<!--面谈失败-->
<script id="fail" type='text/template'>

<div class="text"><ul><li><span style="margin-top:20px;">备&nbsp;&nbsp;&nbsp;&nbsp;注</span><textarea name='memo'></textarea></li></ul></div>
</script>
<!--合同确认-->
<script id='htong' type='text/template'>
<div class="text">
	<ul style="clear:both;">
		<li>
			<span style="margin-top:20px;">备&nbsp;&nbsp;&nbsp;&nbsp;注</span>
			<textarea name='memo'></textarea>
		</li>
	</ul>
<div style="width:260px;height:120px;margin-left:65px;">
	<ul>  
	    <input type="hidden" name='pact'/>
		<img src="/images/take.png" class="attach_file"  data_type="1" limit = '3' attach_file="pact" style="width:53px;height:53px;margin:4px 10px 0px -5px;"> 
	   
	</ul>
</div>
</div>
</script>
<!--放款确认-->
<script id='fkuan' type='text/template'>
<div class="text"><ul><li><span style="margin-top:5px;">金&nbsp;&nbsp;&nbsp;&nbsp;额</span><input type="text" name="borrowmoney"><i>元</i></li><li><span style="margin-top:20px;">备&nbsp;&nbsp;&nbsp;&nbsp;注</span><textarea name='memo'></textarea></li>
<li>
<!--<img src="/images/take.png" style="margin:20px 0px 0px 80px;" class="attach_file"  data_type="1" limit = '5' attach_file="pact">
<input type="hidden" name='pact'/>-->
</li>
</ul>
</div>
</script>
<!--定金确认-->
<script id='djin' type='text/template'>
<div class="text"><ul><li><span style="margin-top:5px;">定金金额</span><input type="text" name="earnestmoney"><i>元</i></li><li><span style="margin-top:20px;">备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注</span><textarea name='memo'></textarea></li></ul></div>
</script>
<!--回款确认-->
<script id='hkuan' type='text/template'>
<div class="text"><ul><li><span style="margin-top:5px;">回款金额</span><input type="text" name="backmoney" style="width:180px;"><i style="font-size:12px;">元</i></li><li><span style="margin-top:25px;">支付利息</span><input type="text" name="payinterest" style="width:180px;margin-top:20px;"><i style="font-size:12px;color:#f93333;">预估值：待定</i></li><li><span style="margin-top:25px;">佣&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;金</span><input type="text" name="actualamount" style="width:180px;margin-top:20px;"><i style="font-size:12px;color:#f93333;">预估值：待定</i></li><li><span style="margin-top:20px;">备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注</span><textarea name='memo' style="width:180px;"></textarea></li></ul></div>
</script>

<script id='pact' type='text/template'>
		
</script>

<script type="text/javascript" src="/js/jquery.fileupload.js"></script> 
<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<input  style='display:none' type="file" name="Filedata" id='id_photos' value="" />

<script>
	$(document).ready(function(){
		 $(document).on('click','.delete',function(){
			
			 var id = $(this).attr('data-id');
			 var afterstatus = $(this).attr('data-afterstatus');
			 var beforestatus = $(this).attr('data-beforestatus');
			 var kefuID = $(this).attr('data-kefuID');
       		 var windcontrolId = $(this).attr('data-windcontrolId');
			switch(afterstatus){
				 case '12':
					var html = $('#fail').html();
					var title = '面谈失败';
					var style = ['385px', '260px'];
				 break;
				 case '20':
					var html =  $('#success').html();
					var title = '面谈成功';
					var style = ['400px', '460px'];
				 break;
				 case '30':
					var html = $('#htong').html();
					var title = '合同确认';
					var style = ['385px', '380px'];
				 break;
				 case '40':
				    if(afterstatus == beforestatus){
						var html = $('#fkuan').html();
						var title = '放款确认';
						var style = ['385px', '300px'];
					}else{
						var html = $('#djin').html();
						var title = '定金确认';
						var style = ['385px', '295px'];
					}
				 break;
				 case '60':
					var html = $('#hkuan').html();
					var title = '回款确认';
					var style = ['385px', '400px'];
				 break;
			 }
			 var obj = $(this);
		     var load = layer.load(1,{shade:[0.4,'#fff']});
			 layer.confirm('',
			 {   
				 skin:'blue',
				 type:1,
				 content:html,
				 title:title,
				 area:style,
				 conseBtn:false,
				 btn:['确定','取消'],
			 },function(){
				
						var memo = $('textarea[name=memo]').val();
						switch(afterstatus){
						case '12':
							var route_url = "<?= yii\helpers\Url::toRoute('solutionseal/interview-failure')?>";
							var params = {id:id,afterstatus:afterstatus,beforestatus:beforestatus,memo:memo};
						break;
						case '20':
							var custname = $('input[name=custname]').val();
							var custmobile = $('input[name=custmobile]').val();
							var account = $('input[name=account]').val();
							var type = $("option:selected").val();
							var typenum = $('input[name=typenum]').val();
							if(!custname){
								   $('input[name=custname]').css('border-color','#a94442').prev('span').css('color','#a94442');
								   return false;
							}
							if(!custmobile || !(/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/.test(custmobile))){
								   $('input[name=custmobile]').css('border-color','#a94442').prev('span').css('color','#a94442');
								   return false;
								}
							if(!account){
								   $('input[name=account]').css('border-color','#a94442').prev('span').css('color','#a94442');
								   return false;
								}
							if(!typenum){
								   $('input[name=typenum]').css('border-color','#a94442').prev('span').css('color','#a94442');
								   return false;
								}
							var route_url = "<?= yii\helpers\Url::toRoute('solutionseal/interview-success')?>";
							var params = {id:id,type:type,typenum:typenum,account:account,afterstatus:afterstatus,beforestatus:beforestatus,memo:memo,custname:custname,custmobile:custmobile};
													
						break;
						case '30':
						    var pact= $('input[name=pact]:hidden').val();
							if(!pact){
								$('.attach_file').after('<i>请上传图片</i>');
								   return false;
							}
							var route_url = "<?= yii\helpers\Url::toRoute('solutionseal/contract-confirmation')?>";
							var params = {id:id,pact:pact,afterstatus:afterstatus,beforestatus:beforestatus,memo:memo}
							
						break;
						case '40':
							if(afterstatus == beforestatus){
								var borrowmoney = $('input[name=borrowmoney]').val();
								if(!borrowmoney){
								$('input[name=borrowmoney]').css('border-color','#a94442').prev('span').css('color','#a94442');
								   return false;
								}else{
									$('input[name=borrowmoney]').css('border-color','').prev('span').css('color','');
								}
								var route_url = "<?= yii\helpers\Url::toRoute('solutionseal/loan-confirmation')?>";
								var params = {id:id,borrowmoney:borrowmoney,afterstatus:afterstatus,beforestatus:beforestatus,memo:memo}
								 
							}else{
								var earnestmoney = $('input[name=earnestmoney]').val();
								
								if(!earnestmoney){
								$('input[name=earnestmoney]').css('border-color','#a94442').prev('span').css('color','#a94442');
								   return false;
								}else{
									$('input[name=earnestmoney]').css('border-color','').prev('span').css('color','');
								}
								var route_url = "<?= yii\helpers\Url::toRoute('solutionseal/deposit-confirmation')?>"; 
								var params = {id:id,earnestmoney:earnestmoney,afterstatus:afterstatus,beforestatus:beforestatus,memo:memo,kefuID:kefuID,windcontrolId:windcontrolId};
								
							}
						break;
						case '60':
							var payinterest =  $('input[name=payinterest]').val();
							var actualamount = $('input[name=actualamount]').val();
							var backmoney = $('input[name=backmoney]').val();
							var route_url = "<?= yii\helpers\Url::toRoute('solutionseal/payment-confirmation')?>";
							var params = {id:id,payinterest:payinterest,actualamount:actualamount,backmoney:backmoney,afterstatus:afterstatus,beforestatus:beforestatus,memo:memo};
							 
						break;
						}
						$.ajax({
							url:route_url,
							type:'post',
							data:params,
							dataType:'json',
							success:function(json){
								if(json.code == '0000'){
										layer.msg(json.msg,{time:2000,shade:false},function(){window.location.reload();});
										layer.close(load);
								}else{
										layer.msg(json.msg);
										layer.close(load)
								}
							}
						})
			},function(){
				layer.close(load);
			})
			switch(afterstatus){
				 case '20':
					$('input[name=custname]').val($(this).attr('data-custname'));
					$('input[name=custmobile]').val($(this).attr('data-custmobile'));
					$('input[name=account]').val($(this).attr('data-account'));
					var type = $(this).attr('data-type');
					if(type == '1'){
						var typenum = $(this).attr('data-typenum');
						$('input[name=typenum]').val(typenum);
						$('#money').html('元');
						$("select").val("1");
					}else{
						$('input[name=typenum]').val($(this).attr('data-typenum'));
						$('#money').html('%');
						$("select").val("2");
					}
                    $("#mySelect").change(function () {  
						var ss = $(this).children('option:selected').val();  
								if (ss == "1") {  
										    $('#money').html('元');
											$("select").val("1");
									    } 
								else if (ss == "2") {  
											$('#money').html('%');
											$("select").val("2");
										}  
					        });					        
					               
				 break;
				 case '40':
				    if(afterstatus == beforestatus){
						$('input[name=borrowmoney]').val($(this).attr('data-borrowmoney'));
					}else{
						$('input[name=earnestmoney]').val($(this).attr('data-earnestmoney'));
					}
				 break;
				 case '60':
					$('input[name=payinterest]').val($(this).attr('data-payinterest'));
					$('input[name=backmoney]').val($(this).attr('data-backmoney'));
					$('input[name=actualamount]').val($(this).attr('data-actualamount'));
				 break;
			 }
			layer.close(load);
		 })
	
	
	
	$(document).on("click",".closebutton",function(){
                var id = $(this).parent().siblings('input').val();
                var bid = $(this).next().attr('data_bid');
                var temp='';
                var ids =id.split(',');
                for(i in ids){
                	if(ids[i]==bid){
                		continue;
                	}
                	temp+=temp?","+ids[i]:ids[i];
                }
               	$(this).parent().siblings('input').val(temp)
            	$(this).parent().remove();
    });
	
	$(document).on('click',".attach_file",function(){
		var limit = $(this).attr('limit')?$(this).attr('limit'):2;
		var attach_file = $(this).attr('attach_file')?$(this).attr('attach_file'):'';
		var data_type = $(this).attr('data_type');
		if(!attach_file)return false;
		$("#id_photos").attr({"attach_file":attach_file,"limit":limit,'data_type':data_type}).click();
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
					var aa = $("input[name="+attach_file+"]").val();
					var div = '<li style="width:50px;height:50px;float:left;margin:5px 10px 0px 0px;"><img class="closebutton" src="/images/cha01.png" style="position: absolute;z-index: 20;display: block;height: 11px; width: 10px;" /><img class="imgView" style="height:50px;width:50px;" src="'+data.url1+'" align="absmiddle" data_bid="'+data.fileid+'"></li>';
                    $("input[name="+attach_file+"]").val((aa ? (aa + ",") : '')+data.fileid);
					$("input[name="+attach_file+"]").before(div);
				}else if(data.msg){
					layer.alert(""+data.msg)
				}
			},
			error:function(){
				layer.close(index)
			}
		}); 
	 });
	 
	 $(document).on('click','.btnLates',function(){
		 var data = $(this).attr('data-pact');
		 if(!data)return false;
			var data = JSON.parse($(this).attr('data-pact'));
		 var html = '';
		 $.each(data, function (index,obj) {
			 html +='<img src="'+obj.file+'" class="imgView" style="float:left;margin:20px 0px 0px 20px;width:100px; height:100px;">';
            });
			layer.confirm('',
				{
				type: 1,
				skin:'blue',
				title: '服务合同',
				content: html,
				area: ['385px', '295px'],
				shade: 0.5,
				btn:false,
				closeBtn: 1,
				});
	 })
	
	$(document).on("click",".btnGenerate",function(){
		var id = $(this).attr("data-solutionsealid");
		if(!id)return false;
		$.ajax({
					 url:'<?= yii\helpers\Url::toRoute('solutionseal/generate') ?>',
					 type:'post',
					 data:{id:id},
					 dataType:'json',
					 success:function(json){
						 if(json.code == '0000'){
								layer.msg(json.msg,{time:2000},function(){
									// obj.children('span').removeClass('glyphicon glyphicon-trash').addClass('glyphicon glyphicon-refresh');
									// obj.attr('data-validflag','1')
									 
								});
								layer.close(load);
						}else{
								layer.msg(json.msg);
								layer.close(load)
						}
					 }
				 })
	})


})
</script>