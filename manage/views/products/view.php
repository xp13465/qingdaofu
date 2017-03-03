<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ProductOrdersLog;

// echo "<pre>";
// count($progress)
// print_r($data);die;
// print_r($logGroup);die;
$soluActionLabels =   \app\models\AuditLog::$soluActionLabels;
ksort($progress);
ksort($logGroup);
$progress['11']['name']= $data['personnel_name'];
$progress['11']['time']= date('Y-m-d',$data['create_at']);
$progress['11']['timeint']= $data['create_at'];
// $data['26'] 
$actionLabels = \app\models\ProductOrdersLog::$actionLabels;
$difftime = '';
$diff = [];
$lastStatusLabel ="";

$start = $data['create_at'];
foreach($progress as $action=>$val){
	if($action >=40)continue;
// var_dump($val);
	// $time = strtotime($val['time']);
	$time = $val['timeint'];
	$difftime = abs(round(($time-$start)/60,1));
	if($difftime){
		$diff[]=["type"=>isset($soluActionLabels[$action])?$soluActionLabels[$action]:$action,"name"=>$val['name'],"diff"=>$difftime];
	}
	
	$start = $time;
}
$step = 3;


foreach($logGroup as $action=>$val){
	if(!in_array($action,[21,22,23,24,25,26,27,28]))continue;
	$time = ($val['timeint']);
	$step++;
	$difftime = abs(round(($time-$start)/60,1));
	if($difftime){
		$diff[]=["type"=>isset($actionLabels[$action])?$actionLabels[$action]:$action,"name"=>$val['name'],"diff"=>$difftime];
	}
	$lastStatusLabel = isset($actionLabels[$action])?$actionLabels[$action]:'';
	
	$start = $time;
}
if($data["status"] <=40){
	$time = time();
	$curTime = abs(round(($time-$start)/60,1));
	$diff[]=["type"=>"至今","name"=>"待操作","diff"=>$curTime];
}else{
	$lastStatusLabel=\app\models\Solutionseal::$status[$data["status"]];
}

// echo "<pre>";
 // var_dump($progress);
 // var_dump($logGroup);
 // var_dump($diff);
// echo "</pre>";
$allCount = 11;
 
 
$this->title = '产品详情';
$this->params['breadcrumbs'][] = $this->title;
?>

<link rel="stylesheet" href="/css/deta.css">

<script src="/js/jquery.easypiechart.js"></script>
<script src="/js/easypiechart-init.js"></script>
<!--div class="title">
   <div class="tx"><span class="left">产品详情#74</span><span class="right">数据刷新时间:2017-2-17  10:30:29</span></div>
</div-->
<div class="cont">
<div class="con">
  <div class="pro">
      <div class="title"><span class="liu">流程进度图</span></div>
      <div class="gress">
			<?php echo $this->render("_statusNode",["progress"=>$progress,'logGroup'=>$logGroup]) ?>
      </div>
  </div>
 <div class="total">
    <div class="tal-l">
    <p class="dq">当前进度</p>
    <p class="hk"><?=$lastStatusLabel?></p>
        <div class="row" style="width:150px;margin:auto;">
                <div class="knob">
                    <span class="chart" data-percent="<?=$step/$allCount*100?>">
                        <span class="percent"><?=round($step/$allCount*100)?>% </span>
                    </span>
            </div>
        </div>
    <p title="<?=date("Y-m-d H:i:s",$model['create_at'])?>" class="start">开始时间:<?=date("Y-m-d",$data['create_at'])?></p>
	<?php if($model['status']==40){?>
    <p title="<?=date("Y-m-d H:i:s",$model['modify_at'])?>" class="over">结束时间:<?=date("Y-m-d",$model['modify_at'])?></p>
	<?php }?>
    </div>
    <div class="tal-m">
       <p>总统计图</p>
	   <div id="container" style="height: 200px; margin: 0 auto"></div>
	    
		
    </div>
    <div class="tal-r">
      <div class="fei">
        <p>费用明细</p>
        <div class="detail">
        <ul>
          <li><span class="text">定金支付</span><span class="money"><?= $data['earnestmoney']?></span><span class="time" title="<?=isset($progress[40])?date('Y-m-d H:i:s',$progress[40]["timeint"]):''?>" ><?= isset($progress[40])?$progress[40]['time']:''?></span></li>
          <?php if(isset($logGroup[26])&&$logGroup[26]){?>
         
		  <li><span class="text">运作产调</span><span class="money"><?= isset($logGroup[26])?count($logGroup[26]):'0'?>次</span><span class="time" title="<?=date('Y-m-d H:i:s',$logGroup[26]["timeint"])?>"><?=$logGroup[26]["time"]?></span></li>
          <?php }?>
		   <?php if($data['borrowmoney']>0){?>
		  
		  <li><span class="text">财务出账</span><span class="money"><?= $data['borrowmoney']?></span><span class="time" title="<?= isset($data["borrowtime"])&&$data["borrowtime"]?date('Y-m-d H:i:s',$data["borrowtime"]):''?>"><?= isset($data["borrowtime"])&&$data["borrowtime"]?date('Y-m-d',$data["borrowtime"]):''?></span></li>
         <?php }?>
		<?php if(isset($progress[60])&&$progress[60]){?>
			<li><span class="text">资方利息支付</span><span class="money"><?= $data['payinterest']?></span><span class="time"><?= isset($progress[60])?$progress[60]['time']:''?></span></li>
          <li><span class="text">佣金支付</span><span class="money"><?= $data['actualamount']?></span><span class="time"><?= isset($progress[60])?$progress[60]['time']:''?></span></li>
          <li><span class="text">回款</span><span class="money"><?= $data['backmoney']?></span><span class="time"><?= isset($progress[60])?$progress[60]['time']:''?></span></li>
			<?php }?>
		</ul>
        </div>
      </div>
      <div class="yu">
        <div class="nub">
         <div class="net">
         <span class="text">预估佣金</span>
         <span class="number"><?=$data->costestimates['yongjin']?>元<i class="tips" id="step" title="<?=$data->costestimates['yongjinStr']?>" > </i></span>
         </div>
         
        </div>
      </div>
    </div>
  </div>

<div class="tail">
  
<div class="chuli">
       <div class="title">
         <p>业务明细</p>
       </div>
      <div class="pro">
           <div class="pro-l">
                     <div class="infro">
                       <ul>
                         <li>
                           <span class="line"></span>
                           <div class="text">
                             <p class="name">客户&nbsp;&nbsp;  <?= $data['custname']?></p>
                             <p class="phone"><?= $data['custmobile']?></p>
                           </div>
                           <span class="pic"></span>
                           <div class="clear"></div>
                         </li>
						 <li>
                           <span class="line sales"></span>
                           <div class="text">
                             <p class="name">销售&nbsp;&nbsp;  <?=$data["personnel_name"]?></p>
                             <p class="phone"></p>
                           </div>
                           <span class="pic"></span>
                           <div class="clear"></div>
                         </li>
						 <li>
                           <span class="line other"></span>
                           <div class="text">
                             <p class="name">风控&nbsp;&nbsp;  <?=$data->personnelModelName?></p>
                             <p class="phone"><?=$data->personnelModelMobile?></p>
                           </div>
                           <span class="pic"></span>
                           <div class="clear"></div>
                         </li>
                       </ul>
                     </div>
                    </div>
                    <div class="xqing" style="overflow:hidden;width:70%">
                      <ul style="height:200px;width:100%;">
					<?php  
						if($model['productOrders']['productOrdersLogs']):
						$logCount = count($model['productOrders']['productOrdersLogs'] );
                        foreach($model['productOrders']['productOrdersLogs'] as $item=>$OrdersLog):
					
					?> 
					<li class="<?=$item?"read":"";?>">
						<div class="time">
							<p class="dates"><?=date("Y-m-d H:i",$OrdersLog['action_at'])?></p>
						</div>
						<div class="<?=($item+1)==$logCount?"title3":"title1"?>"> <i class="<?=$item?"system":"blue";?> top"> <span> &nbsp;  </span> </i>
							<div class="message">
								<p class="article <?=$OrdersLog['class']?:""?>"> 
								<?php 
								echo "[".$OrdersLog['actionLabel']."]";
								
								echo nl2br($OrdersLog['memoLabel']);
								
								echo "。&nbsp;操作人员：";
								echo ($OrdersLog["actionUser"]["realname"]?:$OrdersLog["actionUser"]["username"]);
								echo "&nbsp;<a>".$OrdersLog["actionUser"]["mobile"]."</a>";
								 
								 
								?> </p>
								 <?php 
								foreach($OrdersLog['filesImg'] as $file){
									echo '<span class="pig">'.Html::img(Yii::$app->params["www"].$file['file'],['data-img'=>$file['file'],"class"=>"imgView"]).'</span> ';
								}
								?>
							</div>
						</div>
						<div class="clear"></div>
					</li>
					<?php endforeach;
					endif;
					?> 
						 
                      </ul>
                    </div> 
        <div class="clear"></div>
       </div>
</div>  
   <div class="wen">
       <div class="title">
         <p>文档明细</p>
         <a target="_blank" href="<?=Url::toRoute(["/products/downzip","id"=>$model['productid']])?>">下载全部</a>
       </div>
       <div class="tu">
          <ul class='btnLates'>
		  
		  <?php 
			if(isset($model["pacts"])&&$model["pacts"]){
				$pact = '';
				foreach($model["pacts"] as $value){
					$pact .= $pact?','. $value['file']:$value['file'];
				}
			?>
            <li data-pact="<?= $pact ?>"><img class='pact' src="/images/y.png"><p>服务合同(<?=count($model["pacts"])?>)</p></li>      
			<?php 
			}
			?>
			<?php foreach($logGroup as $action=>$log):
				$pact = '';
				foreach($log as $key => $vlaue){
					if(isset($vlaue["filesImg"])&&$vlaue["filesImg"]){
						foreach($vlaue["filesImg"] as $v){
							$pact .= $pact?','. $v['file']:$v['file'];
						}
					}
					
				}
				$fileNum=$log["fileNum"];
				if($fileNum<=0)continue;
				 
				
			?>
            <li data-pact="<?= $pact ?>"><img src="/images/f.png"><p><?=isset($actionLabels[$action])?$actionLabels[$action]:''?>(<?=$fileNum?>)</p></li>
			<?php endforeach;?>
              
          </ul>  
       </div>
     </div>
</div>


  <div class="product">
    <div class="pro">
      <div class="title">
         <p>产品详情</p>
       </div>
       <div class="infor">
          <table border="1">
              <colgroup>
                  <col width="100px">
                  <col width="490px">
              </colgroup>     
              <tbody>
              <tr>
                  <td class="first"><p>产品ID</p></td>
                  <td class="second"><p><?= $model['productid'] ?></p></td>
              </tr>
              <tr>
                  <td class="first"><p>产品编号</p></td>
                  <td class="second"><p><?= $model['number'] ?></p></td>
              </tr>
              <tr>
                  <td class="first"><p>债权类型</p></td>
                  <td class="second"><p><?= $model['categoryLabel'] ?></p></td>
              </tr>
              <tr>
			       
                  <td class="first"><p>委托权限</p></td>
                  <td class="second"><p><?= $model['entrustLabel'] ?></p></td>
              </tr>
              <tr>
                  <td class="first"><p>委托金额</p></td>
                  <td class="second"><p><?= $model['accountLabel'] ?>万</p></td>
              </tr>
          </tbody>
         </table>
       </div>
    </div>
    <div class="use">
	<?php
	$categoryLabel = "未认证";
	$nameLabel = "";
	$cardnoLabel = "";
	$contactLabel = "";
	$mobileLabel = "";
	$emailLabel = "";
	$casedescLabel ="";
	$addressLabel = "";
	$enterprisewebsiteLabel = "";
	if(isset($model['userName'])&&$model['userName']['state']==1){
		switch($model['userName']['category']){
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
	
	
    <div class="title">
         <p>用户认证信息</p>
       </div>
       <div class="infor">
			<?php if(isset($model['userName'])&&$model['userName']['state']=='1'){ ?>
				<table border="1">
					<colgroup>
						<col width="100px">
						<col width="320px">
					</colgroup>     
					<tr>
						<td class="first"><p><?= $nameLabel ?></p></td>
						<td class="second"><p><?= $model['userName']['name']?></p></td>
					</tr>
					<tr>
						<td class="first"><p><?= $cardnoLabel ?></p></td>
						<td class="second"><p class="wei"><?= $categoryLabel;?></p></td>
					</tr>
					<?php if($contactLabel&&$contactLabel!='*'){ ?>
					<tr>
						<td class="first"><p><?= $contactLabel ?></p></td>
						<td class="second"><p><?= $model['userName']['contact']?></p></td>
					</tr>
					<?php } ?>
					<tr>
						<td class="first"><p><?= $mobileLabel ?></p></td>
						<td class="second"><p><?= $model['userName']['mobile']?></p></td>
					</tr>
					<tr>
						<td class="first"><p><?= $emailLabel ?></p></td>
						<td class="second"><p><?= $model['userName']['email']?></p></td>
					</tr>
					
					<?php  if($addressLabel&&$addressLabel!='*'){ ?>
						<tr>
							<td class="first"><p><?= $addressLabel ?></p></td>
							<td class="second"><p><?= $model['userName']['address']?></p></td>
						</tr>
					<?php } ?> 
					
				</table>
				 <?php }else{ ?>
					 <table border="1">
					<colgroup>
						<col width="100px">
						<col width="320px">
					</colgroup>     
					<tr>
						<td class="first"><p>昵称</p></td>
						<td class="second"><p><?= $model['userMobile']['realname']?$model['userMobile']['realname']:$model['userMobile']['username'] ?></p></td>
					</tr>
					<tr>
						<td class="first"><p>是否认证</p></td>
						<td class="second"><p class="wei"><?= $categoryLabel;?></p></td>
					</tr>
					</table>
				<?php } ?>
	   
           
        </div>
    </div>
  </div>
</div>
</div>

 
<script src="/js/divscroll.js"></script>
<script>
$(document).ready(function(){
	$('.xqing ul').perfectScrollbar();
	
})
</script>

<script type="text/javascript">
 
$(document).ready(function(){
	$(".tips").mouseover(function(){
		var title =$(this).attr("title")
		if(!title)return false;
		layer.tips(title, '#step', {
			tips: [1, '#3595CC'],
			area:["240px","auto"],
			time: 4000
		});
	})
	
	$('.btnLates').delegate('li','click',function(){
		 var data = $(this).attr('data-pact');
		 var data =data.split(',');
		 if(!data)return false;
		 var html = '';
		 $.each(data, function (index,obj) {
			 html +='<img src="<?=Yii::$app->params["www"]?>'+obj+'" class="imgView" style="float:left;margin:20px 0px 0px 20px;width:100px; height:100px;">';
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
	
	// $(document).on('click','.pact',function(){
		
	 // })
})
</script>
 
<script src="/js/highcharts.js"></script>
 
<script language="JavaScript">
$(document).ready(function() {  
   var chart = {
       plotBackgroundColor: null,
       plotBorderWidth: null,
       plotShadow: false
   };
     
   var tooltip = {
      pointFormat: '{point.username}:<b>{point.percentage:.1f}%</b>'
   };
   var plotOptions = {
      pie: {
         allowPointSelect: true,
         cursor: 'pointer',
         dataLabels: {
            enabled: true,
            format: '<b>{point.name}</b>',
            style: {
               color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
            }
         }
      }
   };
   var series= [{
      type: 'pie',
      name: 'Browser share',
      data: [
	  <?php 
		$totalTime = 0;
		foreach($diff as $d){
		  $totalTime +=$d['diff'];
		 $title =$d['type']."：".($d['diff']>1440?(round($d['diff']/60/24,1)."天"):($d['diff']>60?(round($d['diff']/60,1)."小时"):($d['diff']."分钟")));
		  echo "{name:'{$title}',y:{$d['diff']},username:'{$d['name']}'},";
	  }?>
      ]
   }];    
	var title = {
      text: '总耗时：<?=$totalTime>1440?(round($totalTime/60/24,1)."天"):($totalTime>60?(round($totalTime/60,1)."小时"):($totalTime."分钟"))?>'   
   };      
   var json = {};   
   json.chart = chart; 
   json.title = title;     
   json.tooltip = tooltip;  
   json.series = series;
   json.plotOptions = plotOptions;
   json.credits = {enabled:false};
   $('#container').highcharts(json);  
});
</script>
</body>
</html>
