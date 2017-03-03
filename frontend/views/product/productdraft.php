 <?php
 use yii\helpers\Html;
 use yii\helpers\url;
 // echo "<pre>";
 // print_r($data);die;
 
$userid = Yii::$app->user->getId();
 
$categoryLabel = "未认证";
$nameLabel = "";
$cardnoLabel = "";
$contactLabel = "";
$mobileLabel = "";
$emailLabel = "";
$casedescLabel ="";
$addressLabel = "";
$enterprisewebsiteLabel = "";
if(isset($data['User']['certification'])&&$data['User']['certification']['state']==1){
	switch($data['User']['certification']['category']){
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
 
 
 ?>
 <div class="product-details">
    <div class="product-t">
      <div class="details">
        <div class="details-t">
          <p class="top"></p>
          <p class='names'>债权<?= $data['number']?><i data-id="<?=$data['productid']?>"  class='name <?=$data['collectionPeople']?"name1":""?>'></i></p>
        </div>
        <div class="product-l">
          <div class="top">
            <ul>
              <li class="fy">
                <p class="cate"><?php if($data['type']==1){echo '固定费用';}else{echo '风险代理';}?></p>
                <p class="red"><?php if($data['type']==1){echo $data['typenumLabel'];}else{ echo $data['typenum'];}?><i><?php if($data['type']==1){echo $data['typeLabel'];}else{ echo $data['typeLabel'];}?></i></p>
              </li>
              <li class="je">
                <p class="cate">委托金额</p>
                <p class="black"><?= isset($data['accountLabel'])?$data['accountLabel']:'' ?><i>万</i></p>
              </li>
              <li class="qx">
                <p class="cate">违约期限</p>
                <p class="black"><?=($data['overdue'])?><i>个月</i></p>
              </li>
            </ul>
          </div>
          <div class="bot">
            <dl>
              <dt>债权类型：</dt>
              <dd><?= isset($data['categoryLabel'])?$data['categoryLabel']:''?></dd>
              <dt>委托类型：</dt>
              <dd  class="rg"><?= isset($data['entrustLabel'])?$data['entrustLabel']:''?></dd>
              <dt>合同履行地：</dt>
              <dd><?=($data['addressLabel'])?></dd>
              <dt>发布时间：</dt>
              <dd  class="rg"><?= date('Y-m-d H:i',$data['create_at'])?></dd>
            </dl>
          </div>
        </div>
        <div class="product-r"> 
          <div class="portrait">
		    <a href="<?= Url::toRoute(['/userinfo/detail','userid'=>$data['create_by']])?>">
            <div class="pic"><img src="<?=  isset($data['User']['pictureimg'])&&$data['User']['pictureimg']?$data['User']['pictureimg']['file']:'/bate2.0/images/dipian.png' ?>"></div>
            <div class="infor">
              <p><?= $data['User']['realname']?$data['User']['realname']:(isset($data['User']['certification'])&&$data['User']['certification']?$data['User']['certification']['name']:$data['User']['username'])?></p>
              <p class="orange"><?=$categoryLabel?></p>
            </div>
			</a>
          </div>
          <div class="an">
			<?php if(in_array($data['status'],['20','30','40'])){ ?>
		 		<a  style='background-color: #c6d7e1;'>已撮合</a>
			<?php }else if($data['applyPeople']){ ?>
		        <?php if($data['apply']&&$data['apply']['status']=="10"){?>
					<a  class='quxiao' data-applyid='<?=$data['apply']["applyid"]?>'style='background-color:#67c6ff;'>取消申请</a>
				<?php }else{ ?>
					<a  style='background-color: #c6d7e1;'>面谈中</a> 
				<?php }?>
			<?php }else if(!$data['applyPeople']&&$data['create_by']!= $userid){ ?>
				<a  class="application" data-id="<?=$data['productid']?>">立即申请</a>
			<?php }else{  ?>
				<a style='background-color: #c6d7e1;'>立即申请</a>
		    <?php } ?>
            <ul>
             <li title="收藏次数" class="first"><i></i><?= isset($data['collectionTotal'])?$data['collectionTotal']:0  ?></li>
             <li title="浏览次数" class="second"><i></i><?= isset($data['browsenumber'])?$data['browsenumber']:0 ?></li>
             <li title="申请次数" class="third"><i></i><?= isset($data['applyTotal'])?$data['applyTotal']:0 ?></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="product-b">
     <div class="tab">
       <ul>
        <li class="active"><a class="zhai">债权人信息</a></li>
        <li><a class="geng">更多信息</a></li>
       </ul>
     </div>
			<div class="infor">
			<?php if(isset($data['User']['certification'])&&$data['User']['certification']['state']=='1'){ ?>
				<table border="1">
					<colgroup>
						<col width='150px'>
						<col width='800px'>
					</colgroup>     
					<tr>
						<td class="first"><p><?= $nameLabel ?></p></td>
						<td class="second"><p><?= $data['User']['certification']['name']?></p></td>
					</tr>
					<tr>
						<td class="first"><p><?= $cardnoLabel ?></p></td>
						<td class="second"><p class="wei"><?= $categoryLabel;?></p></td>
					</tr>
					<?php if($contactLabel&&$contactLabel!='*'){ ?>
					<tr>
						<td class="first"><p><?= $contactLabel ?></p></td>
						<td class="second"><p><?= $data['User']['certification']['contact']?></p></td>
					</tr>
					<?php } ?>
					<tr>
						<td class="first"><p><?= $mobileLabel ?></p></td>
						<td class="second"><p><?= $data['User']['certification']['mobile']?></p></td>
					</tr>
					<tr>
						<td class="first"><p><?= $emailLabel ?></p></td>
						<td class="second"><p><?= $data['User']['certification']['email']?></p></td>
					</tr>
					
					<?php  if($addressLabel&&$addressLabel!='*'){ ?>
						<tr>
							<td class="first"><p><?= $addressLabel ?></p></td>
							<td class="second"><p><?= $data['User']['certification']['address']?></p></td>
						</tr>
					<?php } ?>
					<?php  if($enterprisewebsiteLabel&&$enterprisewebsiteLabel!='*'){ ?>
						<tr>
							<td class="first"><p><?= $enterprisewebsiteLabel ?></p></td>
							<td class="second"><p><?= $data['User']['certification']['enterprisewebsite']?></p></td>
						</tr>
					<?php } ?>
					
					<?php if($casedescLabel&&$casedescLabel!='*'){ ?>
						<tr>
							<td class="first"><p><?= $casedescLabel ?></p></td>
							<td class="second"><p><?= $data['User']['certification']['casedesc']?></p></td>
						</tr>
					<?php } ?>
					
				</table>
				 <?php }else{ ?>
					 <table border="1">
					<colgroup>
						<col width='150px'>
						<col width='800px'>
					</colgroup>     
					<tr>
						<td class="first"><p>昵称</p></td>
						<td class="second"><p><?= $data['User']['realname']?$data['User']['realname']:$data['User']['username'] ?></p></td>
					</tr>
					<tr>
						<td class="first"><p>是否认证</p></td>
						<td class="second"><p class="wei"><?= $categoryLabel;?></p></td>
					</tr>
					</table>
				<?php } ?>
			</div>
	  
     <div class="more">
       <p class="tp">只有发布方同意您成为TA的接单方后才有权限查看债权详情信息</p>
       <p>已认证的用户获得债权的青睐可能性更大哦！</p>
	   <?php if($data['certification']['state']!='1'){ ?>
           <p class="bm"><a href="<?= Url::toRoute('/certifications/authentication')?>">现在去认证</a></p>
	   <?php } ?>
     </div>
    </div>
  </div>
<script>
$(document).ready(function(){
	$('.tab ul li').click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		var i = $(this).index();
		var divindex = i+1;
		 $(this).parents(".product-b").children('.tab').siblings().hide()
		 $(this).parents(".product-b").children().eq(divindex).show()
	})

	var _csrf = "<?= Yii::$app->request->csrfToken ?>";
	$(".name").click(function(){
			var type = $(this).hasClass("name1");
			var url = !type?"<?php echo yii\helpers\Url::toRoute('/product/collect')?>":"<?php echo yii\helpers\Url::toRoute('/product/collect-cancel')?>";
			 $.ajax({
                url:url,
                type:'post',
				async:false,
                data:{productid:$(this).attr("data-id"),_csrf:_csrf},
                dataType:'json',
                success:function(json){
					if(json.code=='0000'){
						var type = $(".name").hasClass("name1");
						if(!type){
							$(".name").addClass("name1")
						}else{
							$(".name").removeClass("name1")
						}
					}
					layer.msg(json.msg)
                    
                }
            })
		})
		$(".application").click(function(){
			 $.ajax({
                url:'<?php echo yii\helpers\Url::toRoute('/product/apply')?>',
                type:'post',
				async:false,
                data:{productid:$(this).attr("data-id"),_csrf:_csrf},
                dataType:'json',
                success:function(json){
					if(json.code=='0000'){
						if(json.data.certification==1){
							layer.confirm(json.msg,{btn:["取消","前往认证"],title:false,closeBtn:false},function(){layer.closeAll();location.reload()},function(){location.href="<?= yii\helpers\Url::toRoute('/certifications/index')?>"},function(){location.reload()});
						}else{
							layer.msg(json.msg,{time:2000,title:false,closeBtn:false},function(){location.reload()});
						}
						
					}else{
						layer.msg(json.msg)
					}
                }
            })
		})

		
		$('.quxiao').click(function(){
			var applyid = $(this).attr('data-applyid');
			layer.confirm("确定取消申请？",{title:false,closeBtn:false},function(){
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
						
						layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>取消成功",{time:2000},function(){location.reload()});
						layer.close(index);
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