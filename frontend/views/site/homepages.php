<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<style>

	#pgForm table td{   padding-left: 0px;line-height: 24px; font-size:14px;    height: 25px}
	#pgForm table td input{text-indent:5px;}
	.ban-w{width:100%;height:95px;background:#fff;}
	.ban-wr{width:1200px;height:95px;margin:auto;}
	.ban-wr ul{text-indent: 0px;padding-top:15px;}
	.ban-wr ul li{width:290px;height:80px;line-height:30px;float:left;text-align:center;background:url(/images/line01.png)center right no-repeat;}	
	.ban-wr ul li p{font-size:35px;color:#333;}
	.ban-wr ul li p i{font-size:30px;color:#333;}
	.ban-wr ul li span{padding:3px 30px;font-size:14px;color:#999;line-height:50px;}	
	.ban-wr ul li span.lj{width:50px;height:50px;background:url(/images/home.png)left 0px top -397px no-repeat;}
	.ban-wr ul li span.zc{width:50px;height:50px;background:url(/images/home.png)left 0px top -426px no-repeat;}
	.ban-wr ul li span.rz{width:50px;height:50px;background:url(/images/home.png)left 0px top -459px no-repeat;}
	.ban-wr ul li span.ls{width:50px;height:50px;background:url(/images/home.png)left 0px top -491px no-repeat;}
	
	
	.ban-wr ul li:last-child{border-right:0px solid #ddd;}	
	.notice{width:1200px;height:150px;margin:0 auto;}  
	.noticed{width:400px;height:150px;float:left;}  
	.noticed ul li{width:400px;height:30px;line-height:30px;}
	.rec_r{ z-index: 1;}
	#move{ margin: 40px auto; width: 1180px;margin:auto;}
	#move div{display: inline-block;
			width: 202px;height: 135px;
			border-radius: 3px;
			background-color: #fff;
			text-align: center;
			margin: 10px 45px;
			position: relative;
			padding-top: 100px;
			color: #000;
			font-size: 20px;
			text-decoration: none;
			line-height: 30px;
			overflow: hidden;
			cursor:pointer;
			 }
	#move div:hover{
			-webkit-box-shadow: 0px 0px 15px #d5dbe0;  
			-moz-box-shadow: 0px 0px 15px #d5dbe0;  
			box-shadow:0px 0px 15px #d5dbe0;  
	        }
	#move div:hover *{
			color:#0da3f8;
	        }
	#move div:last-child{width:190px}		 
	#move div i{position: absolute;top: 15px;left: 0;display: inline-block;width: 100%;text-align: center;}
	#move img{border:none;width: 100px}
	#move div p{margin-top:35px;}	
	#move div a{color:#999;font-size:14px;}
	.huoban .weixin{cursor:pointer;bottom: 209px;left:1005px;}
	.huoban .weixin div{ left: 110px;   top: -80px;}
  .picScroll-left{ width:1180px;  overflow:hidden; margin:auto; }
  .picScroll-left .hd{ overflow:hidden;  height:30px; padding:0 10px; position:relative;z-index:2;}
  .picScroll-left .hd .prev{ width:30px; height:30px;float:right; margin-right:5px;  cursor:pointer; background:url(images/rec_l.png) no-repeat;background-size:10px;}
  .picScroll-left .hd .next{ width:30px; height:30px;float:right; margin-right:5px;  cursor:pointer; background:url(images/rec_r.png) no-repeat;background-size:10px;}
  .picScroll-left .hd .prevStop{ background-position:-60px 0; }
  .picScroll-left .hd .nextStop{ background-position:-60px -50px; }
  .picScroll-left .hd ul{ float:right; overflow:hidden; zoom:1; margin-top:10px; zoom:1; }
  .picScroll-left .bd{width:1180px;margin:20px auto;}
  .picScroll-left .bd ul{ width:1180px;overflow:hidden; zoom:1; }
  .picScroll-left .bd ul li{width:178px; margin:0 8px; float:left; text-align:center;  border:1px solid #ccc;}
  .picScroll-left .bd .picList .pic{width:178px;height:70px;vertical-align:middle;}
  .picScroll-left .bd ul li:hover{ border-color:#5dc2fa;}	
 </style>

<div class="banners">
    <div class="banners2 clearfix">
        <ul>
		</li> 
			<?php foreach($Banners as $banner){?>
			<li title="<?=$banner["title"]?>" style='background:url("<?=$banner["file"]?>") center center no-repeat;height:400px;width:1920px;background-size:1920px 400px;' ></li>
			<?php }?>
        </ul>
        <ol style="<?=(count($Banners)==1)?"display:none":""?>">
			<?php foreach($Banners as $key=>$banner){?>
			 <li class="current-b"><a><?=$key+1?></a></li>
			<?php }?> 
        </ol>
    </div>

</div>  
<div class="ban-w">
	<div class="ban-wr">
		<ul>
			<li>
              <p><font id="num01">0</font><i>笔</i></p>
              <span class="lj">累计交易总量</span>
            </li>
			<li>
              <p><font id="num02">0</font><i><?php echo $PSum>=100000000?"亿":"万";?></i></p>
              <span class="zc">资产总额</span>
            </li>
			<li>
              <p><font id="num03">0</font><i>家</i></p>
              <span class="rz">入住机构</span>
            </li>
			<li>
              <p><font id="num04">0</font><i>家</i></p>
              <span class="ls">律所入住</span>
            </li>
		</ul>
	</div>	
</div> 
<!-- banner 结束--> 
<link href="/css/base.css" rel="stylesheet">
<link href="/css/style.css" rel="stylesheet">
<link href="/css/layer.css" rel="stylesheet" >
<!-- 内容开始-->
<div class="index" style="background:#f4f4f4;">
    <div class="map">
		<div class="recommend">
		  <p class="jd" id="1"> 
			<span style="width:3px;height:30px;background:#0da3f8;margin-left:15px;"></span>
			<span>推荐产品</span>
			<span class="rec_r" style="width:30px;float:right;"></span>
			<span class="rec_l" style="width:30px;float:right;"></span>
		  </p>
		  <div class="rec_detail">
			<div class="rec_details">
			  <ul class="clearfix">
				<?php foreach($Tjdata as $data): ?>
				<li class="recom">
				<em class='apply'>
				  <div class="clearfix" style="padding-top:10px;">
				  <p>债权<?=$data['number']?></p>
				  <?php foreach(explode(",",$data['categoryLabel']) as $category)echo Html::a($category,"javascript:void(0)",["class"=>"ty"])?>
				  </div>
				  <div class="clearfix">
					 <p class="lei"><?php echo $data['type']==2?'风险代理':'固定费用'?></p>
					 <p class="number"><?= isset($data['typenumLabel'])?$data['typenumLabel']:'' ?><i style="font-size:16px;"><?= isset($data['typeLabel'])?$data['typeLabel']:'' ?></i></p>
				  </div>
				  <p class="adress"><?=$data['addressLabel']?></p>
				  <div class="r_c" > 
					  <span class="fl" style="border-left:none;">
						委托金额<br>
                        <i class="line"></i>
						<b><?= isset($data['accountLabel'])?str_replace("万","",$data['accountLabel']):0 ?><i style="font-size:14px;">万</i></b>
					  </span> 
					  <span class="fr"> 
					  违约期限<br>
					   <b><?=$data['overdue']?><i style="font-size:14px;">个月</i> </b>   
					  </span>
				  </div>
				  </em>
				  <a href="javascript:void(0);"
						<?php if(!$data['applySelf']&&in_array($data['status'],['10'])){
							echo 'class="see btn application"';
						}else if($data['applySelf']&&$data['applySelf']['status']=="10"&&in_array($data['status'],['10'])){
							echo 'class="see btn quxiao" data-applyid='.$data['applySelf']['applyid'].'';
						}else if($data['applySelf']&&$data['applySelf']['status']=="20"){
							echo 'class="see btn" ';
						}else{echo 'class="see btn" style ="background-color:#B0B0B0"';} ?> data-id="<?=$data['productid']?>">
						<?= in_array($data['status'],['10'])?($data['applySelf']?($data['applySelf']['status']=="10"?"取消申请":"面谈中"):"立即申请"):'已撮合'?>
					</a>
				</li>
				<?php endforeach; ?>
			  </ul>
			</div>
		  </div>
		</div>
		<div class="hezuo1" style="height:340px;">
			<p class="jd">
			  <span style="width:3px;height:30px;background:#0da3f8;margin-left:15px;"></span>
			  <span>产品服务</span>
			</p>
			<div class="huoban" style="padding:0">
				<div id="move">
					<div class="big">
						<i><img src="/images/tu1.png"></i>
						<p class="leave">诉讼保全</p>
						<a class="link" href="javascript:void(0)">查封债务人的财产</br>如房产股票等现金价值等~</a> 
						<p class="over" style="width:200px;height:50x;line-height:20px;display:none;font-size:12px;color:#8C8B8B;">诉讼保全指的是查封被申请人的具体财产,如房产银行账户等.</p>       
					</div>
					<div class="big">
						<i><img src="/images/tu2.png" /></i>
						<p class="leave">申请保函</p>
						<a class="link" href="javascript:void(0)">诉讼保全过程中法院要求</br>提供的材料</a>
						<p class="over" style="width:200px;height:50x;line-height:20px;display:none;font-size:12px;color:#8C8B8B;">申请保函是在诉讼保全过程中法院要求提供诉讼前财产保全相应的担保所需要的材料,由担保公司或保险公司开具.</p>
					</div>
					<div>
						<i><img src="/images/tu3.png"></i>
						<p>房产评估</p>
						<a href="javascript:void(0)">免费评估</a>
					</div>
					<div class="qrcode">
						<i><img src="/images/tu4.png"></i>
						<p>产调查询</p>
						<a href="javascript:void(0)">关注清道夫微信公众号<br/>智能下单</a>
					</div>
				</div>
			</div>
		</div>
		 
        <style>
        .news{width:1200px;height:350px;margin:40px auto ;}
        .news-l{width:735px;height:350px;float:left;background:#fff;}
        .news-l .list{width:695px;height:310px;margin:20px;}
		.news-l .list .tab{width:695px;height:40px;border-bottom:1px solid #e5e5e5;}
		.news-l .list .tab ul li{width:75px;height:40px;float:left;text-align:center;margin-left:50px;}
		.news-l .list .tab ul li:first-child{margin-left:0px;}
		.news-l .list .tab ul li a{font-size:17px;line-height:28px;color:#333;display:block;}
		.news-l .list .tab ul li a:hover{color:#0da3f8;}
		.news-l .list .tab ul li:hover{border-bottom:2px solid #0da3f8;}
		.news-l .list .tab ul li.active{border-bottom:2px solid #0da3f8;}
		.news-l .list .tab ul li.active a{color:#0da3f8;}
		.news-l .list .disappear{display:none;}
		.news-l .list .switch .graphic{width:695px;height:90px;margin-top:15px;}
		.news-l .list .switch .graphic .pic{width:130px;height:90px;float:left;}
		.news-l .list .switch .graphic .pic img{width:130px;height:80px;padding:5px 0px;}
		.news-l .list .switch .graphic .tex{width:545px;height:90px;float:right;}
		.news-l .list .switch .graphic .tex p.top{width:545px;height:30px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:16px;color:#333;}
		.news-l .list .switch .graphic .tex p.next{width:545px;height:60px;line-height:20px;overflow:hidden;font-size:12px;color:#999;}
		.news-l .list .switch .new{width:695px;height:150px;margin-top:15px;overflow:hidden;}
		.news-l .list .switch .new ul li{width:695px;height:30px;line-height:30px;float:left;}
		.news-l .list .switch .new ul li i{width:5px;height:5px;margin-top:15px;float:left;border-radius:20px;background:#0da3f8;}
		.news-l .list .switch .new .title{width:485px;height:30px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;float:left;margin-left:10px;} 
		.news-l .list .switch .new .title a{font-size:14px;color:#666;}
		.news-l .list .switch .new .title a:hover{color:#0da3f8}
		.news-l .list .switch .new .time{width:80px;height:30px;float:right;} 
		.news-l .list .switch .new .time span{font-size:14px;color:#666;}		
		
		.news-r{width:445px;height:350px;float:left;background:#fff;margin-left:20px;}
		.news-r .video{width:405px;height:310px;margin:20px;}
		.news-r .video .tab{width:405px;height:40px;border-bottom:1px solid #e5e5e5;}
		.news-r .video .tab ul li{width:80px;height:40px;}
		.news-r .video .tab ul li.active{border-bottom:2px solid #0da3f8;}
		.news-r .video .tab ul li.active a{font-size:17px;line-height:28px;color:#0da3f8;}
		.news-r .video .video-bg{width:405px;height:245px;margin-top:20px;}
        </style>
     <div class="news">
       <div class="news-l">
          <div class="list">
            <div class="tab">
              <ul>
                  <li class="gsxw active"><a target='_blank' href="<?php echo \yii\helpers\Url::toRoute(['/homepage/newslist/',"category"=>"2"])?>" >公司新闻</a></li>
                  <li class="cjzx"><a target='_blank' href="<?php echo \yii\helpers\Url::toRoute(['/homepage/newslist/',"category"=>"3"])?>" >财经资讯</a></li>
                  <li class="hydt"><a target='_blank' href="<?php echo \yii\helpers\Url::toRoute(['/homepage/newslist/',"category"=>"4"])?>" >行业动态</a></li>
              </ul>
            </div>
			<?php 
				$num =0 ;
				foreach(["2"=>"公司新闻","3"=>"财经资讯","4"=>"行业动态"] as $key=> $title){
				$num ++;
				?>
				<div class="switch <?=$num>1?"disappear":""?>">
					<?php foreach($categoryNews[$key] as $item=> $new){ if($item)break;
					// var_dump($new->attributes);
					if($new->attributes&&$new->files['file']){
						$fmImg = $new->files['file'];
					}else{
						preg_match('/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|png))\"?.+>/i',$new->albumcontent->content,$match);
						$fmImg = $match&&isset($match[1])&&$match[1]?$match[1]:"/images/placeholder.png";
					}
				
					?>
					<div class="graphic">
						<div class="pic"> <a target='_blank' title="<?=$new['title']?>" href="<?php echo \yii\helpers\Url::toRoute(['/homepage/newscontent/','cid' => $new['id']])?>"><img src="<?=$fmImg?>"></a></div>
						<div class="tex">
						  <a target='_blank' title="<?=$new['title']?>" href="<?php echo \yii\helpers\Url::toRoute(['/homepage/newscontent/','cid' => $new['id']])?>"><p class="top"><?php echo \frontend\services\Func::truncate_utf8_string($new['title'],30)?></p></a>
						  <p class="next"><?php echo \frontend\services\Func::truncate_utf8_string($new->albumcontent->introduce,130)?></p>
						</div>
					</div>
					<?php }?>
					<div class="new">
					  <ul>
						<?php foreach($categoryNews[$key] as $item=> $new){ if(!$item)continue;?>
							<li>
							   <i></i>
							   <div class="title"><a target='_blank' title="<?=$new['title']?>" href="<?php echo \yii\helpers\Url::toRoute(['/homepage/newscontent/','cid' => $new['id']])?>"><?php echo \frontend\services\Func::truncate_utf8_string($new['title'],50)?></a></div>
							   <div class="time"><span><?php echo isset($new['create_time'])?date('Y-m-d',$new['create_time']):''?></span></div>
							</li>
						<?php }?>
					  </ul>
					</div>
				</div>
			<?php }?>
          </div>
       </div>
       <div class="news-r">
         <div class="video">
            <div class="tab">
              <ul>
                <li class="active"><a>公司视频</a></li>
              </ul>
            </div>
            <div class="video-bg">
				<img class="imgs" onclick="$(this).hide().next().show()" src="/images/bannero1.jpg" height="245" width="405" style="position:absolute;">
				<div style="display:none;text-align:center;" >
				<embed  flashvars="isAutoPlay=true" src="http://player.youku.com/player.php/sid/XMTUyNzI3NjM2OA==/v.swf&auto=1" allowFullScreen="true" quality="high" width="390" height="260" align="center" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>
				</div>
			</div>
         </div>
       </div>
     </div>       
        
		<div class="hezuo1 ">
                  <p class="jd" style="position:relative;top:20px;">
                      <span style="width:3px;height:30px;background:#0da3f8;margin-left:15px;"></span>
                      <span>合作伙伴</span>
                  </p>           
            <div class="picScroll-left">
              <div class="hd">
                 <a class="next"></a> <a class="prev"></a> </div>
              <div class="bd">
                <ul class="picList">
                  <li>
                    <div class="pic" style="background:url(/images/huobanimg_1.png) center center no-repeat;"></div>
                  </li>
                  <li>
                    <div class="pic" style="background:url(/images/huobanimg_2.png) center center no-repeat;"></div>
                  </li>
                  <li>
                    <div class="pic"  style="background:url(/images/huobanimg_3.png) center center no-repeat;"></div>
                  </li>
                  <li>
                    <div class="pic"  style="background:url(/images/huobanimg_4.png) center center no-repeat;"></div>
                  </li>
                  <li>
                    <div class="pic"  style="background:url(/images/huobanimg_5.png) center center no-repeat;"></div>
                  </li>
                  <li>
                    <div class="pic"  style="background:url(/images/huobanimg_6.png) center center no-repeat;"></div>
                  </li>
                  <li>
                    <div class="pic"  style="background:url(/images/huobanimg_7.png) center center no-repeat;"></div>
                  </li>
                  <li>
                    <div class="pic"  style="background:url(/images/huobanimg_8.png) center center no-repeat;"></div>
                  </li>
                  <li>
                    <div class="pic"  style="background:url(/images/huobanimg_9.png) center center no-repeat;"></div>
                  </li>
                  <li>
                    <div class="pic"  style="background:url(/images/huobanimg_10.png) center center no-repeat;"></div>
                  </li>
                  <li>
                    <div class="pic"  style="background:url(/images/huobanimg_11.png) center center no-repeat;"></div>
                  </li>
                  <li>
                    <div class="pic"  style="background:url(/images/huobanimg_12.png) center center no-repeat;"></div>
                  </li>
                </ul>
              </div>
            </div>
		</div>
   </div>
</div>

<script type="text/javascript" src="js/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript">
	jQuery(".picScroll-left").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",autoPlay:true,vis:6,trigger:"click"});
</script>
<script type="text/javascript">
	/*
	$('.join1').click(function(){
       $('.imgs').hide();
    }) 
	
    $('.data-cbd').click(function(){
        var url ="<?php echo Url::toRoute("/capital/certification"); ?>";
        var id  = $(this).attr('data-id');
        var category = $(this).attr('data-category');
        $.post(url,{id:id,category:category},function(v){
            if(v.status == 0){
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
            }else if(v.status == 1){
                location.href = "<?php echo Url::toRoute('/capital/applyorder/'); ?>?id=" + id + "&category=" + category;
            }else if(v.status == 2){
                $.msgbox({
                    height: 120,
                    width: 230,
                    content: '<p style="text-align:center">您还未认证,请进行身份认证!</p>',
                    type: 'confirm',
                    onClose: function (v) {
                        if (v) {
                            location.href = "<?php echo Url::toRoute('/certification/index')?>";
                        }
                    }
                });
            }
        },'json');
    })
	*/
	/*
   $(document).ready(function(){
	   
        $('.jd a').click(function(){
            var index=$(this).index()-1;
            $('.notice').children().eq(index).show().siblings('div').hide();
            $(this).addClass('current').siblings('a').removeClass('current');
        })

    })
	*/
</script> 
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
		
		var num01=document.getElementById("num01");
        var bSpan=document.getElementById("num02");
        var cSpan=document.getElementById("num03");
        var dSpan=document.getElementById('num04');
        var n="<?php echo $PCount?>",n1="<?php echo $PSum>=100000000?round(($PSum)/10000/10000,2):round(($PSum)/10000,2);?>",n2="<?php echo $organization ?>",n3="<?php echo $lawyer?>";
        var s= parseInt(num01.innerHTML);
        var outTime=0;
        var timer = setInterval(function(){
            outTime+=30;
            if(outTime<1000){
                num01.innerHTML=parseInt(n/1000*outTime);
                bSpan.innerHTML=(n1/1000*outTime).toFixed(2);
                cSpan.innerHTML= parseInt(n2/1000*outTime);
                dSpan.innerHTML= parseInt(n3/1000*outTime);
            }else{
                num01.innerHTML=n;
                bSpan.innerHTML=n1;
                cSpan.innerHTML=n2;
                dSpan.innerHTML=n3;
				clearInterval(timer)
            }
        },30);
		
		
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
		$("#move div").eq(3).siblings().click(function(){
			
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
			if($(this).index()==2){
				pg();
			}else if($(this).index()==0){
				window.open("<?php echo \yii\helpers\Url::toRoute(['/protectright/create'])?>");
			}else if($(this).index()==1){
				window.open("<?php echo \yii\helpers\Url::toRoute(['/policy/create'])?>");
			}
			<?php }?>
		})
		$("#move div").eq(3).mouseover(function(){
			$(".huoban .weixin div").show()
		}).mouseout(function(){
			$(".huoban .weixin div").hide()
		})
		/*$("#move div").eq(0).mouseover(function(){
			$(".huoban .weixin").css("left",'100px')
			$(".huoban .weixin div").show()
		}).mouseout(function(){
			$(".huoban .weixin").css("left",'805px')
			$(".huoban .weixin div").hide()
		})
		$("#move div").eq(1).mouseover(function(){
			$(".huoban .weixin").css("left",'350px')
			$(".huoban .weixin div").show()
		}).mouseout(function(){
			$(".huoban .weixin").css("left",'805px')
			$(".huoban .weixin div").hide()
		})*/

$(document).ready(function(){
	$(".qrcode").click(function(){
		var html ="<div class='weixin'><p class='top'>扫一扫，关注微公众号</p><p class='img'><img src='/bate2.0/images/dipian.png'></p><p class='til'>1.打开微信扫一扫功能</p><p class='tex'>2.扫描并关注清道夫微信公众号</p></div>"
			layer.confirm(html, {
			  title: '',
			  closeBtn: 1,
			  area: ["300px !important","370px !important"],
			  shadeClose: true,
			  btn: [] //按钮
			})
		  }) 
		
  })


/*$(document).ready(function(){
	$(".qrcode").click(function(){
		layer.alert('<div class="weixin"><p class="top">扫一扫，关注微公众号</p><p class="img"><img src="/bate2.0/images/dipian.png"></p><p class="til">1.打开微信扫一扫功能</p><p class="tex">2.扫描并关注清道夫微信公众号</p></div>',  {
			tips: [1, '#33b3f5'],
			scrollbar :false,
			title :false,
			btn :[],
			time: 0000
		});
	 });
})*/


$(document).on("click",".application",function(){
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
                data:{productid:$(this).attr("data-id")},
                dataType:'json',
                success:function(json){
					if(json.code=='0000'){
						obj.addClass("quxiao").removeClass("application").html("取消申请").attr("data-applyid",json.data.applyid).addClass("over");
						if(json.data.certification==1){
							layer.confirm(json.msg,{btn:["取消","前往认证"],title:false,closeBtn:false},function(){layer.closeAll();},function(){location.href="<?= yii\helpers\Url::toRoute('/certification/index')?>"});
						}else{
							layer.msg(json.msg,{time:2000},function(){/*location.href="<?= yii\helpers\Url::toRoute('/site/index')?>"*/});
						}
						
						
					}else{
						layer.msg(json.msg,{time:20000})
					}
                }
            })
			<?php }?>
		})
		
		$(document).on("click",".quxiao",function(){
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
				data:{applyid:applyid},
				dataType:'json',
				success:function(json){
					layer.close(index)
					if(json.code == '0000'){
						layer.close(index);
						obj.addClass("application").removeClass("quxiaoquxiao").html("立即申请").attr("data-applyid","").parent().removeClass("over")
						layer.msg("<p><i><img src='/bate2.0/images/tr.png'></i></p>取消成功");

					}else{
						layer.msg(json.msg);
						layer.close(index);
						}
					}
				})
			})
		})
})

$(document).ready(function(){
		$(".news-l .tab ul li").mousemove(function(){
				$(this).addClass("active").siblings().removeClass("active")
				$(this).parents(".list").children("div.switch").eq($(this).index()).removeClass("disappear").siblings(".switch").addClass("disappear");
		})
		
		$(".ewmm").mouseover(function(){
			$(this).next("img").show()
		}).mouseout(function(){
			$(this).next("img").hide()
		}).trigger("mouseout")
	
	})

</script>
<div id="template" style="display: none;"> <!-- markup --></div>
<style>
	.layui-layer-setwin .layui-layer-close2 {
		background:url(/images/index/close.png) no-repeat;
		position: absolute;
		right: -10px;
		top: 5px;
		width: 35px;
		height: 35px;
	}
      .layui-layer-setwin .layui-layer-close1 {
		background:url(/images/close2.png) no-repeat;
		position: absolute;
		right: -10px;
		top: 0px;
		width: 35px;
		height: 35px;
	}
    .layui-layer-setwin .layui-layer-close1 {background-position:none;cursor: pointer;}
	layui-layer-dialog {border-radius:20px;}
	.layui-layer-dialog .layui-layer-content {overflow-y: hidden;}
	/*#layui-layer1 {width: 709px !important;}
	.layui-layer-dialog .layui-layer-content{
	  width:700px !important;
	  height:385px !important;
	  background:url(/images/index/phone.png) 385px 0px no-repeat;
	  }*/
	.spinner {
	width: 144px;
	height: 145px;
	opacity:0;
	margin: 113px 252px;
	-webkit-animation:rotateplane 1s ease 0.5s;
	animation:rotateplane 1s ease 0.5s;
	animation-fill-mode: forwards;
	}
	@-webkit-keyframes rotateplane {
	0% {
	  opacity:0;
	 -webkit-transform:scale(0.1) rotate(0deg); 
	  -moz-transform:scale(0.1) rotate(0deg); 
	  -o-transform:scale(0.1) rotate(0deg); 
	  transform:scale(0.1) rotate(0deg); 
	  }
	100% {
	  opacity:1;
	 -webkit-transform:scale(1) rotate(360deg); 
	  -moz-transform:scale(1) rotate(360deg); 
	  -o-transform:scale(1) rotate(360deg); 
	  transform:scale(1) rotate(360deg); 
	}
	}
	
	@keyframes rotateplane {
	0% {
	 opacity:0;
	  -webkit-transform:scale(0.1) rotate(0deg); 
	  -moz-transform:scale(0.1) rotate(0deg); 
	  -o-transform:scale(0.1) rotate(0deg); 
	  transform:scale(0.1) rotate(0deg); 
	} 100% {
	  opacity:1;
	  -webkit-transform:scale(1) rotate(360deg); 
	  -moz-transform:scale(1) rotate(360deg); 
	  -o-transform:scale(1) rotate(360deg); 
	  transform:scale(1) rotate(360deg); 
	}
	}
	.text{width:245px;position: absolute;top:100px;text-align:center;font-family:"微软雅黑"}
	.text h1{font-weight:bold;color:#333;}
	.text p{font-size:18px;color:#333;margin-top:15px;}
	
	.recommend .clearfix{height:71px;}
	.recommend .clearfix p{font-size:16px;text-align:center;color:#333;}
	.recommend .clearfix .ty{height:20px;line-height:20px;color:#81a0b7;font-size:12px;float:left;border-radius:10px;background:#eff9ff;padding:0px 5px;margin-left:4px;margin-top:10px;}
	.recommend .clearfix .number{color:#ff1f3f;font-size:42px;font-weight:bold;margin-top:3px;}
	.recommend .clearfix .adress{width:80%;height:24px;overflow:hidden;margin:auto;color:#0da3f8;font-size:12px;line-height:24px;background:#eff9ff;position:absolute;margin-top:20px;left:25px;}
	.recommend .clearfix .lei{width:125px;height:20px;font-size:14px;color:#a5a5a5;margin:-10px auto;}
	.recommend .rec_detail .r_c b {font-size: 18px;color: #333;}
	.recommend .fr b {font-size: 18px !important;color: #333 !important;}
	.recommend .rec_detail .r_c {position:absolute;margin: 50px auto 0;color: #666;border-bottom: 0px solid #ccc;height: 55px;}
	.recommend .see{transition:all 0.5s;-webkit-transition:all 0.5s; transition:all 0.3s;-moz-transition:all 0.3s;-o-transition:all 0.3s;display:black;position: absolute;top: 290px;left: 12px;width: 90%;height:45px;line-height: 45px;text-align: center;color: #fff;font-size: 16px;background: #0da3f8;border-radius: 5px;border: none;cursor: pointer;margin-top: 18px;float: right;}
	.recommend .see:hover{background:#33b3f5}
	.recommend .r_c span {height: 40px;width: 100px;color:#a5a5a5;text-align: center;border-left: 1px solid #ccc;line-height: 25px;margin-top: 3px;}
	.recommend .r_c .fl .line{height:10px;width:10px;position:absolute;top:3px;left:95px;background:#fff;}
	.layui-layer-setwin .layui-layer-close2:hover {background-position: 0px 0px}
	.abc{background:#fff url(/images/index/phone.png) right 40px bottom 0px no-repeat;}
</style>
<script>
$(document).ready(function(){
	// $(".recom").click(function(){
		// var html ="<div style='position: absolute;'><div class='text'><h1>重要通知</h1><p>PC产品功能正在升级中....</p><p>关注清道夫债管家微信公众号</p><p>全新改版，功能完善</p></div><div class='spinner'><img src='/images/index/code.png'></div></div>"
			// layer.confirm(html, {
			  // title: '',
			  // skin:'abc',
			  // closeBtn: 1,
			  // area: ["700px !important","480px"],
			  // shadeClose: true,
			  // btn: [] //按钮
			// })
		  // })
	
	$('.apply').click(function(){
		var productid = "<?= $data['productid']?>";
		var url = "<?= Url::toRoute(['/product/detail','productid'=>""])?>"+productid;
		window.open(url)
	})
		
  })
</script>