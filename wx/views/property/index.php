<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
$areas =[
	'2703'=>'长宁区',
	'2704'=>'闸北区',
	'2705'=>'闵行区',
	'2706'=>'徐汇区',
	'2707'=>'浦东新区',
	'2708'=>'杨浦区',
	'2709'=>'普陀区',
	'2710'=>'静安区',
	'2711'=>'卢湾区',
	'2712'=>'虹口区',
	'2713'=>'黄浦区',
	'2714'=>'南汇区',
	'2715'=>'松江区',
	'2716'=>'嘉定区',
	'2717'=>'宝山区',
	'2718'=>'青浦区',
	'2719'=>'金山区',
	'2720'=>'奉贤区',
	'2721'=>'崇明县',
];

?>
<?=wxHeaderWidget::widget(['title'=>'我的产调',"backurl"=>Url::toRoute("/user/index"),'gohtml'=>'<a href="javascript:void(0);" class="reload"><img src="/images/refresh.png"  width="25" height="25"></a>'])?>
<?php if(!empty($data) && $data != ''){ ?>
<section> 
            <p id="show"></p> 
            <div id="wrapper" style="overflow:scroll;">
            <div id="scroller" class="news" style="position:absolute;top:70px;">
			<div id="pullDown">
                    <span class="pullDownIcon"></span><span class="pullDownLabel"></span>
            </div>
			<div id="thelist" data-catr="<?php echo count($data);?>" data-url="<?php echo yii\helpers\Url::toRoute(['/property/index'])?>">
            <?php foreach($data as $val){ 
			$ordersid = date("Ymd",$val['time']).str_pad($val['id'],6,"0",STR_PAD_LEFT);
			?>
				<ul class="thelist" >
					<li>
							<div class="types" data-id="88" data-category="2">
								<div class="rongzi">
									<div class="over" style="margin:0 20px;">
                                     <div class="flo_l" style="width:75%;float:left;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">
										   <span class="rz_ig8"><img src="/images/property_transfer.png"></span>                                
										   <span class="code"><?php echo $ordersid; ?></span>
										   <span class="flo_blue"></span>
										</div>
										<div class='flo_r'>
										<span >
										   <?php 
												echo $val['statusLabel'];
										   ?> 
											</span>
										</div>                            
									</div>                          
								</div>
								<div class="num">
									<ul>
										<li class="listl">								
											<span style="font-size:16px;margin-top:10px;"><?php echo isset($areas[$val['city']])?$areas[$val['city']]:''; ?><?php echo $val['address']; ?></span>
											</li>
										<li class="listr">
											<span class="black" style="margin-top:10px;">
												 ￥<?php echo $val['money']; ?>                       
											</span>
											</li>                            
									</ul>
									<ul>
										<li class="listl" style='width:55%'>								
											 <span class="loan"><?php echo date("Y-m-d H:i",$val['time']); ?>       </span>
										</li>
										<li class="listr" style='width:42%'>
											<span class="loan" style='padding-right:0' >  
												 已用时<i style="color:#f00;"><?php echo $val['yongshi']; ?> </i>分钟                           
											</span>
										</li>                            
									</ul>
								</div>
							</div>
							<?php if(in_array($val['status'],[2,3,4])){?>
								<div class="sup_sq">
									<div class="tip_l">
										<?php if($val['status']!="2"||$val['expressId']||($val['uptime']+86400)<time()){?>
											<!--<a   href="https://www.baidu.com/s?wd=<?=$val['orderId']?>">快递单号：<?=$val['orderId']?></a>-->
											<a  style='color:#999;cursor:default;' href="javascript:void(0)">快递原件</a>  
										<?php }else{?>
											<a   href="<?php echo yii\helpers\Url::toRoute(['/property/express','id'=>$val['id']])?>">快递原件</a>  
										<?php }?>										
									</div>
									<div class="tip_l">       
										<?php if($val['result']['type']=='tips'){?>
										<a class='' href="javascript:layer.alert('<?=$val['result']['attr']?>')">查看结果</a>
										<?php }else if($val['result']['type']=='view'){ ?>
										<a href="<?php echo yii\helpers\Url::toRoute(['/property/view','id'=>$val['result']['attr']])?>">查看结果</a>
										<?php }?>
									</div>
								</div>
							<?php }else if($val['status']=='0'){?>
								<div class="sup_sq">
									<div class="tip_l">
										<a   href="<?php echo yii\helpers\Url::toRoute(['/property/edit','id'=>$val['id']])?>">编辑</a>  									
									</div>
									<div class="tip_l">       
										<a   href="<?php echo yii\helpers\Url::toRoute(['/property/pay','id'=>$val['id']])?>">立即支付</a>
									</div>
								</div>
							<?php } ?>
					</li>
				</ul>
            <?php } ?>
			</div>			
			<div id="pullUp" style="display:none;" >
                  <span class="pullUpIcon"></span><span class="pullUpLabel"></span>
            </div>
        </div>
		  
      </div>
</section>
<?php } ?>
<footer>
    <div class="zhen">
        <a href="<?php echo yii\helpers\Url::toRoute('/property/add')?>">申请产调</a>
    </div>
</footer>

<script type="text/javascript">
    $(document).ready(function(){
        $('.reload').click(function(){
            window.location.reload();
        })
    });
	/*
	var page = "<?php echo Yii::$app->request->get('page')<=0?1:Yii::$app->request->get('page')?>";
    function pullDownAction () {
        setTimeout(function () {
            var el, li, i;
            el = document.getElementById('thelist');
            page  --;
            window.location = "<?php echo yii\helpers\Url::toRoute('/property/index')?>"+"?page="+page;
            myScroll.refresh();
        },1000);
    }

    function pullUpAction () {
        var catr = $('#thelist').attr('data-catr');
        if(catr<10){
            setTimeout(function () {
                var el, li, i;
                el = document.getElementById('thelist');
                myScroll.refresh();
            }, 1000);
        }else{
            setTimeout(function () {
                var el, li, i;
                el = document.getElementById('thelist');
                page  ++;
                window.location = "<?php echo yii\helpers\Url::toRoute('/property/index')?>"+"?page="+page;
                myScroll.refresh();
            },1000);
        }
    }*/
</script>
