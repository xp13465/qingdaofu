<style>
.clear{clear:both;}
.rz_4{width: 100%;overflow: hidden;}
.rz_4 li{width: 25%;height:90px;text-align: center;float: left;}
.rz_4 li p{font-size:16px;margin-top:10px;}
#small{font-size:14px;color:#666;}
.bg01{height:32px;width:50px;text-align:center;margin:auto;background:url(/images/home.png) 10px 2px no-repeat;background-size:94px;}
.bg02{height:32px;width:50px;text-align:center;margin:auto;background:url(/images/home.png) 10px -30px no-repeat;background-size:94px;}
.bg03{height:32px;width:50px;text-align:center;margin:auto;background:url(/images/home.png) 10px -63px no-repeat;background-size:94px;}
.bg04{height:32px;width:50px;text-align:center;margin:auto;background:url(/images/home.png) 10px -95px no-repeat;background-size:94px;}

.swiper-slide {width:180px !important;background: gray;}
.swiper-slide{width:100%;overflow:hidden;height:255px !important;background:#fff;}
.swiper-container{background:#fff;margin-top:12px}
.swiper-container .text{font-size:16px;color:#333;padding:10px 0px  0px 10px;background:#fff;}
.swiper-slide ul li{width:180px !important;height:230px;background: #f5f5f5;float:left;margin-left:10px;margin-top:10px;border-radius:3px;/*box-shadow: 0px 0px 5px #ccc;*/}
.swiper-slide .ico{
	background: url(/images/defaulthead.png) center center no-repeat;
    background-size: 38px;
    border-radius: 50%;
	height: 38px; width: 38px;float: left;position:relative;left: 71px;top:12px;}
.swiper-slide .ico img{height:38px;width:38px;border-radius:50%;}
.swiper-slide .gray{float: right;margin-top:12px;margin-bottom:25px;background: url(/images/home.png) 0px -128px no-repeat;height: 20px; width: 20px;position:relative;right: 15px;background-size:94px;}
.swiper-slide .red{float: right;margin-top:12px;margin-bottom:25px;background: url(/images/home.png) -0px -160px no-repeat;height: 20px; width: 20px;position:relative;right: 15px;background-size:94px;}
.swiper-slide .middle{margin-top:15px;width:170px;}
.swiper-slide .middle p{text-align:center;}
.swiper-slide .middle .middle-l{width:85px;height:45px;float:left;text-align:center;border-right:1px solid #ddd;}
.swiper-slide .middle .middle-r{width:80px;height:45px;float:left;text-align:center;}
.swiper-slide .category{width:170px;height:45px;}
.swiper-slide .category dl{width:170px;height:45px;padding-top:12px;}
.swiper-slide .category dl dt{width:54px;line-height:16px;transform: scale(0.9);font-size:12px;color:#333;border:1px solid #ddd;border-radius:3px;float:left;margin:0px 0px 3px 2px;padding:0px 2px ;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}

.swiper-slide .number{font-size:25px;color:#F36E6E;text-align:center;font-family:'微软雅黑';}
.swiper-slide .various{transform: scale(0.9);font-size:12px;color:#666;text-align:center;}
.swiper-slide .btn{border: 1px solid #10a1ec;width:40%;height:26px;text-align:center;margin:25px auto;border-radius:20px;/*box-shadow: 0px 0px 3px #10a1ec;background:#10a1ec;*/}
.swiper-slide .btn a{font-size:12px;color:#10a1ec;line-height:24px;}
.swiper-slide .over{border: 1px solid #999;width:40%;text-align:center;margin:25px auto;border-radius:20px;}
.swiper-slide .over a{color:#666;}
.layui-layer-btn{padding: 10px 10px 10px;pointer-events: auto;border-top: 1px solid #ddd;}
.layui-layer-btn a {
    height: 35px;
    line-height: 35px;
    margin: 0 6px;
    width: 45%;
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
 <section>
  <div id="focus" class="focus">
    <div class="hd" style="<?=(count($banners)==1)?"display:none":""?>">
      <ul>
		<?php foreach($banners as $key=>$banner){?>
			<li class="">1</li>
		<?php }?>
      </ul>
    </div>
    <div class="bd">
      <div class="tempWrap" style="overflow:hidden; position:relative;">
        <ul style="width: 1920px; position: relative; overflow: hidden; padding: 0px; margin: 0px; transition-duration: 200ms; transform: translate(-640px, 0px) translateZ(0px);">
			<?php foreach($banners as $banner){?>
			<li title="<?=$banner["title"]?>" style="display: table-cell; vertical-align: top; width: 640px;"> <a <?=$banner["target"]?"target='_blank'":""?> href="<?=$banner["url"]?:"javascript:void(0)"?>"><img src="<?=$banner["file"]?>"></a> </li>
			<?php }?>
        </ul>
      </div>
    </div>
  </div>
  <ul style="width:100%;max-width:640px;height:80px;">
    <li class="hm-01" style="height:80px;"> <span class="baocun" style="margin-left:15px;">累计交易总量</span>
      <div class="renzhen-01"> <a href="<?php echo \yii\helpers\Url::toRoute(['/site/total-detail',"type"=>"WEB"])?>"><span>查看详情</span></a> <i class="jiantou"></i> </div>
      <p style="color:#10a1ec;font-size:30px;text-indent:15px;font-weight:bold;"><span id="totalSum" attr='<?=$sum?>' style="font-size:30px;"></span><i style="font-size:20px;"></i></p>
    </li>
  </ul>
</section>
<section>
  <div class="rz_4" style="background:#fff;margin-top:12px;">
    <ul>
      <li> <a href="<?php echo yii\helpers\Url::toRoute('/preservation/index')?>">
        <p class="bg01"></p>
        <p id="small">诉讼保全</p>
        </a> </li>
      <li> <a href="<?php echo yii\helpers\Url::toRoute('/policy/add')?>">
        <p class="bg02"></p>
        <p id="small">申请保函</p>
        </a> </li>
      <li> <a href="<?php echo \yii\helpers\Url::toRoute('/estate/index')?>">
        <p class="bg03"></p>
        <p id="small">房产评估</p>
        </a> </li>
      <li> <a href="<?php echo \yii\helpers\Url::toRoute('/property/add')?>">
        <p class="bg04"></p>
        <p id="small">产调查询</p>
        </a> </li>
    </ul>
  </div>
</section>

<section>

    <div class="swiper-container" >
         <p class="text">推荐产品</p>
        <div class="swiper-wrapper">
			<?php foreach($data as $key=>$value):
             $categoryLabel = isset($value['categoryLabel'])?explode(',',$value['categoryLabel']):'';
			?>
            <div class="swiper-slide">
              <ul>
                 <li> 
				<?php if($value ["collectSelf"]){echo'<span class="red heart" data-id="'.$value['productid'].'"></span>';}else{echo '<span class="gray heart" data-id="'.$value['productid'].'"></span>';}?>
                    <a href="<?php echo yii\helpers\Url::toRoute(['/list/detail',"id"=>$value['productid']])?>"> <span class="ico"><img src="<?php if(isset($value['homeUsesrHead'])&& $value['homeUsesrHead']['headimg']['file']){echo Yii::$app->params["wx"].$value['homeUsesrHead']['headimg']['file'];}else{echo '/images/defaulthead';}?>"></span>
					<div class="clear"></div>
                         <div class="category">
                         <dl>
						 <?php if($categoryLabel){ foreach($categoryLabel as $key => $values){ ?>
                         <dt><?= $values ?></dt>
						 <?php } } ?>
                         </dl>
                         </div>
					<div class="clear"></div>
                        <div class="middle">
                           <div class="middle-l">
							<p class="number"><?php if($value['type']==1){echo $value['typenumLabel'];}else{echo $value['typenum'];}?><i style="font-size:12px;"><?= $value['typeLabel'] ?></i></p>
							<p class="various"><?php if($value['type']==1){echo '固定费用';}else{echo '风险费率';}?></p>
						    <i style="width:1px;height:5px;left:43px;top:-52px;border:1px solid #f5f5f5;position:relative;"></i>
                           </div>
                           <div class="middle-r">
							<p class="number"><?= $value['accountLabel']?><i style="font-size:12px;">万</i></p>
							<p class="various">委托金额</p>
						   </div>
                           	<div class="clear"></div>
                        </div>
					</a>
					<div class="btn <?php 
					if($value['applySelf']||in_array($value['status'],['20','30','40'])||$value['create_by'] == Yii::$app->user->getId()){
						echo 'over';
					}?>"> 
					<a href="javascript:void(0);"  
						<?php if(!$value['applySelf']&&in_array($value['status'],['10'])){
							echo 'class="application"';
						}else if($value['applySelf']&&$value['applySelf']['status']=="10"&&in_array($value['status'],['10'])){
							echo 'class="quxiao" data-applyid='.$value['applySelf']['applyid'].'';
						}else if($value['applySelf']&&$value['applySelf']['status']=="20"){
							
						} ?> data-id="<?=$value['productid']?>">
						<?= in_array($value['status'],['10'])?($value['applySelf']?($value['applySelf']['status']=="10"?"取消申请":"面谈中"):"立即申请"):'已撮合'?>
					</a>
					</div>
					</li>
                </ul>
              </div>
			 <?php endforeach;?>
      </div>
   </div>


</section>
<?php echo  \wx\widget\wxFooterWidget::widget()?>
<link  href="/css/swiper.min.css" rel="stylesheet">
<script src="/js/swiper.min.js"></script>
<script>
    var swiper = new Swiper('.swiper-container', {
	   	autoplay:false,
        slidesPerView: 2,
        paginationClickable: true,
        spaceBetween: 0,
        freeMode: true
    });
</script>

<script>
	  TouchSlide({
		  slideCell:"#focus",
		  titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
		  mainCell:".bd ul",
		  effect:"left",
		  autoPlay:true,//自动播放
		  autoPage:true, //自动分页
		  switchLoad:"_src" //切换加载，真实图片路径为"_src"
	  });
</script>
<script>
$(document).ready(function(){
	$(".swiper-slide .ico img").on("error",function(){
		$(this).hide()
	})
    $('.product').bind('click',function(){
        var category = $(this).attr('data-category');
        var id       = $(this).attr('data-id');
        $.ajax({
            url: "<?php echo yii\helpers\Url::toRoute('/site/judge')?>",
            type: 'post',
            data: {category: category, id: id},
            dataType: 'json',
            success: function (json) {
                if (json.code == '0000') {
                    location.href = "<?php echo yii\helpers\Url::toRoute('/list/applyorder/'); ?>?id=" + id + "&category=" + category;
                }else{
                    if(json.code == '3006'){
                        alert(json.msg);
                        location.href = "<?php echo yii\helpers\Url::toRoute('/certification/index/');?>";
                    }else{
                        alert(json.msg);
                        location.href = "<?php echo yii\helpers\Url::toRoute('/site/login/');?>";
                    }
                }
            }
        })
       }
    );
		function format (num) {
			return (parseFloat(num).toFixed(0) + '').replace(/\d{1,3}(?=(\d{3})+(\.\d*)?$)/g, '$&,');
		}
	  
        var totalSum=($('#totalSum').attr("attr")); 
		var label = totalSum>99999999?"万":"元"
		totalSum = totalSum>99999999?totalSum/10000:totalSum;
        var outTime=0;
        var timer = setInterval(function(){
            outTime+=30;
            if(outTime<1000){ 
				$("#totalSum").html(format(totalSum/1000*outTime)).next("i").html(label);
            }else{ 
                $("#totalSum").html(format(totalSum)).next("i").html(label);
				clearInterval(timer)
				setInterval(refTotalSum,30000)
            }
        },30);
		
		function refTotalSum(){
			$.ajax({
				type: "POST",
				url: "<?php echo yii\helpers\Url::toRoute(['/site/total-claims'])?>", 
				dataType: "json",
				success: function(data){ 
					if(data.code=='0000'){ 
						var totalSum=data.result.sum; 
						var label = totalSum>99999999?"万":"元"
						totalSum = totalSum>99999999?totalSum/10000:totalSum;
						var num = format(totalSum)
						if($("#totalSum").html()!=num){
							$("#totalSum").html(num).next("i").html(label)
						}
					}
				}
			});
		}
		
	$(document).on("click",".application",function(){
		var obj = $(this)
			 $.ajax({
                url:'<?php echo yii\helpers\Url::toRoute('/list/apply')?>',
                type:'post',
				async:false,
                data:{productid:$(this).attr("data-id")},
                dataType:'json',
                success:function(json){
					if(json.code=='0000'){
						obj.addClass("quxiao").removeClass("application").html("取消申请").attr("data-applyid",json.result.applyid).parent().addClass("over")
						if(json.result.certification==1){
							layer.confirm(json.msg,{btn:["取消","前往认证"],title:false,closeBtn:false},function(){layer.closeAll();},function(){location.href="<?= yii\helpers\Url::toRoute('/certification/index')?>"});
						}else{
							layer.msg(json.msg,{time:2000},function(){/*location.href="<?= yii\helpers\Url::toRoute('/site/index')?>"*/});
						}
						
						
					}else{
						layer.msg(json.msg)
					}
                }
            })
		})
		
	$(".heart").click(function(){
			var type = $(this).hasClass("red");
			obj = $(this)
			var url = !type?"<?php echo yii\helpers\Url::toRoute('/list/collect')?>":"<?php echo yii\helpers\Url::toRoute('/list/collect-cancel')?>";
			 $.ajax({
                url:url,
                type:'post',
				async:false,
                data:{productid:$(this).attr("data-id")},
                dataType:'json',
                success:function(json){
					if(json.code=='0000'){
						if(type){
							obj.removeClass("red")
							obj.addClass("gray")
						}else{
							obj.removeClass("gray")
							obj.addClass("red")
						}
						layer.msg(json.msg,{time:2000},function(){/*location.href="<?= yii\helpers\Url::toRoute('/site/index')?>"*/});
						
					}else{
						layer.msg(json.msg)
					}
					
                }
            })
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
				url:'<?= yii\helpers\Url::toRoute('/productorders/apply-cancel')?>',
				type:'post',
				data:{applyid:applyid},
				dataType:'json',
				success:function(json){
					layer.close(index)
					if(json.code == '0000'){
						layer.close(index);
						obj.addClass("application").removeClass("quxiao").html("立即申请").attr("data-applyid","").parent().removeClass("over")
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