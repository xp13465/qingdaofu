<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
// echo "<pre>";
// print_r($data);die;
$categoryLabel = "未认证";
$nameLabel = "";
$cardnoLabel = "";
$contactLabel = "";
$mobileLabel = "";
$emailLabel = "";
$casedescLabel ="";
$addressLabel = "";
$enterprisewebsiteLabel = "";
$userid = Yii::$app->user->getId();
if($data['certification']&&$data['certification']['state']==1){
	switch($data['certification']['category']){
		case 1:
			$categoryLabel='已认证个人';
			$nameLabel = '姓名';
			$cardnoLabel='身份证号码';
			$mobileLabel = '联系方式';
			$emailLabel = '邮箱';
			break;
		case 2:
			$categoryLabel='已认证律所';
			$nameLabel = '律所名称';
			$cardnoLabel='执业证号';
			$contactLabel = '联系人';
			$mobileLabel = '联系方式';
			$emailLabel = '邮箱';
			$casedescLabel = '经典案例';
			break;
		case 3:
			$categoryLabel='已认证公司';
			$nameLabel = '企业名称';
			$cardnoLabel='营业执照号';
			$contactLabel = '联系人';
			$mobileLabel = '联系方式';
			$emailLabel = '企业邮箱';
			$addressLabel = '企业地址';
			$enterprisewebsiteLabel = '公司网站';
			$casedescLabel = '经典案例';
			break;
	}
}
$commentdata = $data["commentdata"];
?>
<div class="center">
    <div class="center-l">
      <div class="top">
        <div class="head"><img src="<?=$data['pictureimg']?$data['pictureimg']['file']:"/images/defaulthead.png"?>"></div>
        <div class="editor">
         <p class="name"><?=$data['realname']?:$data["username"]?></p>
         <p class="cation"><?=$categoryLabel?></p>
		 <?php if(Yii::$app->request->get('applyStatus') == '10'){ ?>
           <a class='see' data-applyid ="<?= Yii::$app->request->get('applyid')?>" data-productid ="<?= Yii::$app->request->get('productid')?>" >约TA面谈</a>
		 <?php } ?>
        </div>
      </div>
      <div class="next">
        <ul>
         <li><p class="number"><?=sprintf("%.1f",$commentdata["commentsScoredetail"]["truth_score"]?:"5.0");?></p><p class="text">真实性</p>
         <li><p class="number"><?=sprintf("%.1f",$commentdata["commentsScoredetail"]["assort_score"]?:"5.0");?></p><p class="text">配合性</p>
         <li><p class="number"><?=sprintf("%.1f",$commentdata["commentsScoredetail"]["response_score"]?:"5.0");?></p><p class="text">响应性</p>
        </ul>
      </div>
        
        
		 
			<div class="personal">
		    <p>个人资料</p>
			<dl>
				<dt>昵称</dt>
				<dd><?=$data['realname']?$data['realname']:$data['username']?></dd>
				 <?php if($data['certification']&&$data['certification']['state']==1){ ?>
				<?php if($data['certification']){?>
						
						
						<dt><?= $nameLabel ?></dt>
						<dd><span><?=$data['certification']['name']?></span></dd>
						
						<dt><?= $cardnoLabel ?></dt>
						<dd><span><?=$data['certification']['cardno']?></span></dd>
						
					<?php if(isset($contactLabel)&&$contactLabel){ ?>
						 
						<dt><?= $contactLabel ?></dt>
						<dd><?=$data['certification']['contact']?></dd>
						 
					<?php } ?>
					 
						<dt><?= $mobileLabel ?></dt> 
						<dd><?=$data['certification']['mobile']?></dd> 
						 
					<?php if(isset($addressLabel)&&$addressLabel){ ?>
						
						<dt><?= $addressLabel ?></dt> 
						<dd><?=$data['certification']['address']?></dd>  
						
					<?php } ?>
					<?php if(isset($enterprisewebsiteLabel)&&$enterprisewebsiteLabel){ ?>
						 
						<dt><?= $enterprisewebsiteLabel ?></dt>
						<dd><?=$data['certification']['enterprisewebsite']?></dd>
						 
					<?php } ?>
						 
						<dt><?= $emailLabel ?></dt>
						<dd><?=$data['certification']['email']?></dd>
						 
					<?php if(isset($casedescLabel)&&$casedescLabel){ ?>
						  
						<dt><?= $casedescLabel ?></dt>
						<dd><?=$data['certification']['casedesc']?></dd>
						 
					<?php } ?>
				<?php }?>
				<?php }?>
			 </dl>
             <div class="clear"></div>
			 </div>
			
        
    </div>
    <div class="center-r">
<?php
				Pjax::begin([
					'id' => 'post-grid-pjax',
				])
				?>
          <div class="top">
             <ul>
             <li class="<?=$status==1?"active":""?>"><a href="<?=Url::toRoute(["userinfo/detail",'userid'=>$data['id'],"status"=>1])?>" class="top-l">收到的评价</a>
             <li class="<?=$status==2?"active":""?>"><a href="<?=Url::toRoute(["userinfo/detail",'userid'=>$data['id'],"status"=>2])?>" class="top-r">发布的产品</a>
             </ul>
          </div>
		 
		
<?php if($status==1){?>
			<div class="evalua">
				<ul class="list">
				<?php foreach($commentdata['Comments1'] as $key => $value): 
				// var_dump($value);
				$score = ($value['truth_score']+$value['assort_score']+$value['response_score'])/3;
				?>
					<li>
						  <div class="star">
							<ul>
							<?php for($i=1;$i<=5;$i++){
								echo $score>$i?'<li class="bright"></li>':'<li class="gray"></li>';
							 }?> 
							</ul>
							<p><?= date('Y年m月d日 H:i',$value['action_at'])?></p>
						  </div>
						  <div class="comments">
							  <p class="text"><?= nl2br($value['memo'])?></p>
							  <?php foreach($value['filesImg'] as $v):?>
								<p class="pic"><img class='imgView' src="<?=$v['file'] ?>"></p>
								<?php endforeach; ?>
						  </div>
						  <div class="portrait">
							  <p class="head"><img src="<?= isset($value["userinfo"]["headimg"]['file'])?$value["userinfo"]["headimg"]['file']:'/images/defaulthead.png' ?>"></p>
							  <p class="name"><?= isset($value["userinfo"]["realname"])?$value["userinfo"]["realname"]:$value["userinfo"]["username"] ?></p>
						  </div>
						  <div class="clear"></div>
					</li>
				<?php endforeach; ?>
				</ul>
			</div>
<?php }elseif($status ==2){?>
			<div class="evalua01" style="display:block">
						<div class="list">
							<table>
								<colgroup>
									<col width='160px'/>
									<col width='160px'/>
									<col width='160px'/>
									<col width='200px'/>
									<col width='210px'/>
								</colgroup>
							<thead>
							
							<tr>
								<th>委托费用</th>
								<th>委托金额</th>
								<th>违约期限</th>
								<th>产品编号</th>
								<th>操作</th>
							</tr>
							</thead>
							<tbody>
							<?php 
								if($data['product']){
								foreach($data['product'] as $value): ?>
									<tr class='productList' data-productid="<?= $value['productid'] ?>">
										<td class="red"><?= isset($value['type'])=='1'?intval($value['typenumLabel']):intval($value['typenum'])?><i><?=$value['typeLabel']?></i></td>
										<td class="black"><?=$value['accountLabel']?><i>万</i></td>
										<td><?=$value['overdue']?>个月</td>
										<td><p class="omit w150"><?=$value['number']?></p></td>
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
										<td colspan="8">无</td>
									</tr>
								<?php }?>
							</tbody>
							</table>
						</div>     
						<div class="clear"></div>
						<div class="pages clearfix ">
						<div class="fenye" style="margin-top:30px"> 
								<span class="fenyes" style="font-size:12px;margin:0px 35px -41px;">共<?=$provider->pagination->totalCount?>条记录，第<?=$provider->pagination->page+1?>/<?=$provider->pagination->pageCount?>页</span>
								<?= yii\widgets\LinkPager::widget([
									'pagination' => $provider->pagination
								]) ?>
						</div>
						</div>
			</div>
				 
				
				
<?php }?>
<?php Pjax::end() ?>	
        </div>
		<div class="clear"></div>
</div>
<script>
$(document).ready(function(){
var _csrf = "<?= Yii::$app->request->csrfToken ?>";
$(".see").click(function(){
		 var applyid = $(this).attr('data-applyid');
		 var productid = $(this).attr('data-productid');
		 var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});
		 $.ajax({
			 url:'<?= yii\helpers\Url::toRoute('/product/apply-chat') ?>',
			 type:'post',
			 data:{applyid:applyid,_csrf:_csrf},
			 dataType:'json',
			 success:function(json){
				 if(json.code == '0000'){
					 layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>发起面谈成功",{time:500},function(){location.href='<?= Url::toRoute('/product/product-deta')?>'+'?productid='+productid;});
					 layer.close(index);
				 }else{
					 layer.msg(json.msg);
					 layer.close(index);
				 }
			 }
		 })
	 })
	 $(document).on('click','.productList td',function(){
		if($(this).index()==4)return application;
		var productid = $(this).parent().attr('data-productid');
		var url = "<?= Url::toRoute('/product/detail')?>?productid="+productid;
		window.open(url)
	})
	var application = $(document).on("click",".application",function(){
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