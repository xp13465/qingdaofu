<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
$this->registerJsFile('@web/js/jquery.cityselect.js',['depends'=>['wx\assets\NewAppAsset']]);
$this->registerJsFile('@web/js/fastclick.js',['depends'=>['wx\assets\NewAppAsset']]);
?>
 <div class="cm-header">
	<code>产品列表</code>
	<span class="gohtml" ></span>
 </div>
<?php
$category = [0=>'全部',1=>'',2=>'清收',3=>'诉讼'];
$progress = ['2'=>'发布中','3'=>'已撮合'];
$money = [2=>'30万以下',3=>'30-100万',4=>'100-500万',5=>'500万以上'];
$provinces = Yii::$app->request->get('province');
$city = Yii::$app->request->get('city');
$districts = Yii::$app->request->get('district');
?>
<div class="Divgray"></div>

<style>
    .tabFixed {
        position:absolute;
        z-index: 10;
        width: 100%;
    }
    .tabSX {
        position:absolute;
        width: 100%;
        height: 46%;
        z-index: 10;
        max-width: 640px;
    }
    .tabSX .lbTab {
        height: 100%;
    }
    .lbTab {
        height: 44px;
        position: relative;
    }
    .lbTab ul {
        height: 44px;
        border-bottom: 1px solid #ddd;
        border-top:1px solid #ddd;
    }
    .flexbox {
        display: box;
        display: -webkit-box;
        display: -moz-box;
        display: -ms-box;
        -webkit-box-orient: horizontal;
        box-orient: horizontal;
    }
    .lbTab li {
        padding: 11px 0;
        width: 25%;

    }
    .flexbox > * {
        display: block;
        box-flex: 1;
        -webkit-box-flex: 1;
        -moz-box-flex: 1;
        -ms-box-flex: 1;
    }
    .lbTab li.active a {
        color: #ff6666!important;
    }
    .lbTab li a {
        display: block;
        border-right: 1px solid #f4f4f4;
        height: 22px;
        line-height: 22px;
        font-size: 14px;
        text-align: center;
        color: #565c67!important;
        padding: 0 6px;
    }
    a {
        color: #3c3f46;
        text-decoration: none;
    }
    em, i {
        font-style: normal;
    }
    .lbTab li span {
        position: relative;
        display: inline-block;
        line-height: 21px;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        padding-right: 13px;
    }
    .lbTab li.active span:after {
        border-color: transparent transparent #f66 transparent;
        margin-top: -6px;
    }
    .lbTab li span:after {
        content: '';
        position: absolute;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 4px;
        border-color: #cccfd8 transparent transparent transparent;
        right: 0;
        top: 50%;
        margin-top: -2px;
    }
    .lbTab .cont {
        position: absolute;
        width: 100%;
        top: 0px;
        left: 0;
        bottom: 0;
        /*  background-color: #fff;*/
        z-index: 10;
    }
    .lbTab .flexbox section:first-child {
        width: 25%;
    }
    .lbTab section {
        height: 100%;
        border-right: 1px solid #f4f4f4;
        overflow-y: scroll;
    }
    .lbTab .cont dd.active {
        position: relative;
        /*background-color: #f2f5f8;*/
     }

.lbTab .cont .dl02, .lbTab .cont .dl03 {
        border-bottom: 1px solid #f4f4f4;
        height: 100%;
        line-height: 43px;
        font-size: 14px;
        white-space: nowrap;
        text-overflow: ellipsis;
        background-color: #fff;
        color: #3c3f46;
 }



    .lbTab .cont dd {
        padding: 0 16px;
        border-bottom: 1px solid #f4f4f4;
        height: 44px;
        line-height: 43px;
        font-size: 14px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        background-color: #fff;
        color: #3c3f46;
    }
    .lbTab .cont dd a {
        display: block;
        text-align:center;
        margin: 0 -16px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
    .lbTab .cont dd a.current{background:#EEF3F6;color:#0065b3;}
    .Divgray{width:100%;height:100%;position:fixed;background:#000;opacity:0.4;/*z-index:10;*/display:none;}
    /* .hid{width:100%;max-width:640px;height:44px;position:relative;border-bottom:1px solid #ddd;background-color:#fff; */}
    .a-header{top:0;color:#333;z-index:960;position:fixed;left:0;right:0;text-align:center;border-bottom:1px solid #ddd;background-color:#fff;max-width:640px;margin:0 auto;}
    .lbTab .flexbox section:nth-child(2) {
        width: 35%;
    }
    .lbTab .flexbox section:nth-child(3) {
        width: 40%;
        border-right: none;
    }
    .cont section::-webkit-scrollbar,
    .cont section::-webkit-scrollbar{
        width:0;
        height:0;
    }
	.product{padding:6px 0;}
	.type .rongzi {padding: 6px 0px;height:26px;line-height:14px;}
	.rongzi-ul{height:26px;padding:6px 0;}
	.rongzi-ul li{margin-top:0;color:#999;}
	.rongzi .code{    vertical-align: inherit;}
	.num li{margin-top:0;}
	.num .blue{    line-height: 20px;    height: 32px;    font-size: 20px;    color: #ff981f;    padding: 6px;}
	.num .black{    line-height: 20px;    height: 32px;    font-size: 20px;    color: #148fcc;    padding: 6px;}
	.num .loan{padding:6px 0;    font-size: 12px;    line-height: 12px;}
</style>
<section>
    <div class="list" style="position:relative;z-index:960;">
        <ul>
            <li class="one">
                <a style="display:block" href='javascript:void(0)' <?php if($provinces || $city || $districts){echo 'class="current"';}?> id="0">
				<?php if(!Yii::$app->request->get('district')&&Yii::$app->request->get('city')){
                            echo Yii::$app->request->get('city')?\frontend\services\Func::getCityNameById(Yii::$app->request->get('city')):'区域';
						}else if(!Yii::$app->request->get('city')){
							echo Yii::$app->request->get('province')?\frontend\services\Func::getProvinceNameById(Yii::$app->request->get('province')):'区域';
						}else{
							echo Yii::$app->request->get('district')?\frontend\services\Func::getAreaNameById(Yii::$app->request->get('district')):'区域';
                        }
					
				?>
				</a>
                <span <?php if(Yii::$app->request->get('district')&&Yii::$app->request->get('district')!=''){echo 'class="triangle"';}else{echo  'class="triangle triangle01"';}?>></span>
            </li>
            <li>
                <a style="display:block" href='javascript:void(0)' id="0" <?php if(in_array(Yii::$app->request->get('status'),['2','3'])){echo 'class="current state"';}else{echo 'class="state"';}?>><?php echo isset($progress[Yii::$app->request->get('status')])?  $progress[Yii::$app->request->get('status')]:'状态'?></a>
                <span <?php if(in_array(Yii::$app->request->get('status'),['2','3'])){echo 'class="triangle"';}else{echo  'class="triangle triangle01"';}?>></span>
            </li>
            <li>
                <a style="display:block" href='javascript:void(0)' id="0" <?php if(in_array(Yii::$app->request->get('account'),['2','3','4','5'])){echo 'class="current moneys"';}else{echo 'class="moneys"';}?>><?php echo isset($money[Yii::$app->request->get('account')])?  $money[Yii::$app->request->get('account')]:'金额'?></a>
                <span <?php if(in_array(Yii::$app->request->get('account'),['2','3','4','5'])){echo 'class="triangle"';}else{echo  'class="triangle triangle01"';}?>></span>
            </li>
        </ul>
    </div>
    <section class="tabSX tabFixed" style="z-index:0;">
        <div class="lbTab">
            <div class="districtcls" style="display: none;">
                <div class="cont flexbox" style="display: -webkit-box;">
                    <section id="searchnew" class="dl01">
                        <dl style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
			             <dd><a href="javascript:void(0);" class='buxian'>不限</a></dd>
						<?php foreach($province as $key => $value) :?>
							<dd class="areas_b"><a href="javascript:void(0)" <?php if(Yii::$app->request->get('province') == $key){echo 'class="current"';} ?> id="<?= $key ?>" style="<?php if ($key == 310000) echo "color:red;";?>"  ><?= $value ?></a></dd>
						<?php endforeach;?>
                        </dl>
                    </section>

                    <section class="dl02">
                        <dl style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
							<?php foreach($citys as $key=>$value) :?>
								<?php //if($key == 310000):?>
									<?php foreach($value as $key => $city):?>
										<dd class="areas_d"><a href="javascript:void(0)" <?php if(Yii::$app->request->get('city') == $key){echo 'class="current"';}?> id="<?= $key ?>" ><?= $city ?></a></dd>
									<?php endforeach;?>                         
								<?php //endif;?>
							<?php endforeach;?>
                        </dl>
                    </section>
                    <section class="dl03">
                        <dl id="comarea_dl_25" class="column3" style="display: block; transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
							
							<?php foreach($areas as $key=>$value) :?>
								<?php //if($key == 310100):?>
										
									<?php foreach($value as $key=>$area):?>
										<dd class="areas_e"><a href="javascript:void(0)" <?php if(Yii::$app->request->get('district') == $key){echo 'class="current"';} ?>id="<?= $key ?>"><?= $area ?></a></dd>
									<?php endforeach;?>                         
								<?php //endif;?>
							<?php endforeach;?>
                            <dd><a href="#">闸北区</a></dd>
                        </dl>
                    </section>
                </div>
            </div>
        <div class="zone" style="display:none">
            <a href='javascript:void(0)' id="1" <?php if(Yii::$app->request->get('status') == '1'){echo 'class="current"';}?>>不限</a>
            <a href='javascript:void(0)' id="2" <?php if(Yii::$app->request->get('status') == '2'){echo 'class="current"';}?>>发布中</a>
            <a href='javascript:void(0)' id="3" <?php if(Yii::$app->request->get('status')=='3'){echo 'class="current"';}?>>已撮合</a>
        </div>
        <div class="zone_a" style="display:none">
            <a href='javascript:void(0)' id="1" <?php if(Yii::$app->request->get('account') == 1){echo 'class="current"';}?>>不限</a>
            <a href='javascript:void(0)' id="2" <?php if(Yii::$app->request->get('account') == 2){echo 'class="current"';}?>>30万以下</a>
            <a href='javascript:void(0)' id="3" <?php if(Yii::$app->request->get('account') == 3){echo 'class="current"';}?>>30-100万</a>
            <a href='javascript:void(0)' id="4" <?php if(Yii::$app->request->get('account') == 4){echo 'class="current"';}?>>100-500万</a>
            <a href='javascript:void(0)' id="5" <?php if(Yii::$app->request->get('account') == 5){echo 'class="current"';}?>>500万以上</a>
        </div>

        </div>
    </section>
</section>
<section>
	<p id="show"></p>
	<div id="wrapper">
		<div id="scroller" class="type" style="position:absolute;">
			<div id="pullDown" style="display:none">
				<span class="pullDownIcon"></span><span class="pullDownLabel"></span>
			</div>
			<ul id="thelist" class="thelist" data-catr="<?=count($products['data'])?>" data-url="<?php echo yii\helpers\Url::toRoute(['/list/index',"province"=>Yii::$app->request->get('province'),"city"=>Yii::$app->request->get('city'),"district"=>Yii::$app->request->get('district'),"account"=>Yii::$app->request->get('account'),"status"=>Yii::$app->request->get('status')])?>">
				<?php foreach($products['data'] as $data):?>
				  <li class="product <?php if(in_array($data['status'],['20','30','40'])){echo 'over';}?>" style="margin-top:12px;">
					<?php if(in_array($data['status'],['20','30','40'])){echo '<span class="flo_c" ></span>';}?>
					<a href='<?php echo yii\helpers\Url::toRoute(['/list/detail',"id"=>$data['productid']])?>'><div class="rongzi"> <span class="code" style="width:35%;"><?=$data['number']?></span> <span style="text-align:right;overflow: hidden;width: 182px;float: right;color: #999;text-overflow: ellipsis;white-space: nowrap;"><?=$data['addressLabel']?>  </span> </div>
					<ul class="rongzi-ul" style="margin-left:15px;">
					<?php 
					$categoryLabel = isset($data['categoryLabel'])&&$data['categoryLabel']?explode(",",$data['categoryLabel']):[];
						foreach( $categoryLabel as $label):?>
					  <li><?=$label?></li>
					<?php endforeach;?>
					</ul>
					<div class="num"  style="background:#fff;">
					  <ul>
						<li> <span class="blue"><?php if($data['type']==1){echo $data['typenumLabel'];}else{echo $data['typenum'];}?><i style="font-size:12px;"><?= $data['typeLabel'] ?></i> </span> <span class="loan"><?php if($data['type']==1){echo '固定费用';}else{echo '风险费率';}?></span> </li>
						<li> <span class="black"><?=str_replace("万","",$data['accountLabel'])?><i style="font-size:12px;">万</i></span> <span class="loan"> 委托金额 </span> </li>
						<li> <span class="black"><?=$data['overdue']?><i style="font-size:12px;">个月</i> </span><span class="loan">违约期限</span> </li>
					  </ul>
					</div>
					</a>
				  </li>
				<?php endforeach;?>
			</ul>
			<div id="pullUp" style="display:none;" >
					<span class="pullUpIcon"></span><span class="pullUpLabel"></span>
			</div>
	</div>
</div>
</section>

<?php echo  \wx\widget\wxFooterWidget::widget()?>
<script>
datalistClass = '#scroller ul.thelist li.product'
//province=0&city=0&district=0&account=0&status=0&page=1&limit=10
city = ''
function searchForm(province,city,district,account,status){
    window.location = "<?php echo \yii\helpers\Url::toRoute('/list/index')?>"+"?province="+province+"&&city="+city+"&&district="+district+"&&account="+account+"&&status="+status;

}

function searchArea(province,city,district,account,status){
    searchForm(province,city,district,account,status);
}
function area(city_id, province_id){
		//var cat = $('.dd a.current').attr('id');
        city=city_id;
		var status = $('.zone a.current').attr('id');
		var account = $('.zone_a a.current').attr('id');
        $.ajax({
            url:"<?php echo yii\helpers\Url::toRoute('/list/area')?>",
            type:'post',
            data:{fatherID:city_id},
            dataType:'json',
            success:function(json){
                var area_con = "";
				//var a = '<dd><a href="javascript:void(0);" id="0" class="aredQubu">全部</a></dd>';
                $.each(json,function(n,value){
                    $.each(value,function(ared_id,val){
						if(ared_id == '0'){
                             area_con += '<dd><a href="javascript:void(0)" class="aredQubu" id="' + ared_id + '" onclick="searchArea('+province_id+',' + city_id + ','+ ared_id +','+account+','+status+')">' + val + '</a></dd>';
						    }else{
							 area_con += '<dd><a href="javascript:void(0)" id="' + ared_id + '" onclick="searchArea('+province_id+',' + city_id + ','+ ared_id +','+account+','+status+')">' + val + '</a></dd>';
							}
                        
                    });
                });
                $(".dl03 dl").html(area_con);
            }
        })
}

$(document).ready(function(){
        // $('.qb').click(function() {
            // $('.dd').slideToggle();
            // $('.Divgray').toggle();
            // $('.zone').hide();
            // $('.zone_a').hide();
            // $('.dd a').click(function(){
                // $('.Divgray').hide();
                // searchForm($(this).attr('id'),$('.areas_e a.current').attr('id'),$('.zone a.current').attr('id'),$('.zone_a a.current').attr('id'),$('.areas_b a.current').attr('id'),$('.areas_d a.current').attr('id'),0);
            // });
        // });
        $('.list ul li a').click(function(){
            $(this).addClass('current').parent().siblings().children().removeClass('current');
            $(this).next().removeClass('triangle01').parent().siblings().children('span').addClass('triangle01');
        })

        $('.dl01 a').click(function(){
            $(this).addClass('current').parent().siblings().children().removeClass('current');
            $(this).next().removeClass('triangle01').parent().siblings().children('span').addClass('triangle01');
        })

        $('.dl02 dl dd a').click(function(){
            $(this).addClass('current').parent().siblings().children().removeClass('current');
            $(this).next().removeClass('triangle01').parent().siblings().children('span').addClass('triangle01');
        })
		var cur = '';
        function curTrigger(thiscur){
			if(cur == thiscur){
				// console.log(cur)
				$('.Divgray').hide();
				cur ='';
			}else{
				cur = thiscur
				$('.Divgray').show();
			}
		}
        $('.one').click(function () {
            $('.districtcls').toggle();
            $('.Divgray').show();
            $('.tabFixed').attr('style','z-index:11');
            $('.dd').hide();
            $('.zone_a').hide();
            $('.zone').hide();
            $('.dl02').hide();
            $('.dl03').hide();
			curTrigger('one')
        });
		$('.buxian').click(function(){
			$('.districtcls').hide();
			curTrigger('one')
			searchForm('','','',$('.zone_a a.current').attr('id')?$('.zone_a a.current').attr('id'):'',$('.zone a.current').attr('id')?$('.zone a.current').attr('id'):'');
		})
		$(document).on('click','.cityQubu',function(){
			searchForm($('.areas_b a.current').attr('id')?$('.areas_b a.current').attr('id'):'','','',$('.zone_a a.current').attr('id')?$('.zone_a a.current').attr('id'):'',$('.zone a.current').attr('id')?$('.zone a.current').attr('id'):'');
			$('.dl03').hide();
		})
		$(document).on('click','.aredQubu',function(){
			var areas = $('.areas_d a.current').attr('id')?$('.areas_d a.current').attr('id'):(city?city:'');
			searchForm($('.areas_b a.current').attr('id')?$('.areas_b a.current').attr('id'):'',areas,'',$('.zone_a a.current').attr('id')?$('.zone_a a.current').attr('id'):'',$('.zone a.current').attr('id')?$('.zone a.current').attr('id'):'');
		})


        $('.dl01').click(function () {
            $('.dl02').show();
        })

        $('.dl02').click(function () {
				$('.dl03').show();
        })
            

        $('.state').click(function(){
			
            $('.zone').toggle();
            $('.Divgray').show();
            $('.tabFixed').attr('style','z-index:11');
            $('.dd').hide();
            $('.districtcls').hide();
            $('.zone_a').hide();
			curTrigger('state')
            $('.zone a').click(function(){
                searchForm($('.areas_b a.current').attr('id')?$('.areas_b a.current').attr('id'):'',$('.areas_d a.current').attr('id')?$('.areas_d a.current').attr('id'):'',$('.areas_e a.current').attr('id')?$('.areas_e a.current').attr('id'):'',$('.zone_a a.current').attr('id')?$('.zone_a a.current').attr('id'):'',$(this).attr('id'));
            });
        })
        $('.moneys').click(function(){
			
            $('.zone_a').toggle();
            $('.Divgray').show();
            $('.tabFixed').attr('style','z-index:11');
            $('.districtcls').hide();
            $('.zone').hide();
            $('.dd').hide();
			curTrigger('moneys')
            $('.zone_a a').click(function(){
                searchForm($('.areas_b a.current').attr('id')?$('.areas_b a.current').attr('id'):'',$('.areas_d a.current').attr('id')?$('.areas_d a.current').attr('id'):'',$('.areas_e a.current').attr('id')?$('.areas_e a.current').attr('id'):'',$(this).attr('id'),$('.zone a.current').attr('id')?$('.zone a.current').attr('id'):'');
            });
     })

     /*
    $('.product').bind('click',function(){
            var category = $(this).attr('data-category');
            var id       = $(this).attr('data-id');
            var progress_status = $(this).attr('data-progress');
            $.ajax({
                url: "<?php echo yii\helpers\Url::toRoute('/site/judge')?>",
                type: 'post',
                data: {category: category,id:id},
                dataType: 'json',
                success: function (json) {
                    if (json.code == '0000') {
                        if (progress_status == 1) {
                            location.href = "<?php echo yii\helpers\Url::toRoute('/list/applyorder/'); ?>?id=" + id + "&category=" + category;
                        } else if (progress_status == 2) {
                            alert('已被接单');
                        } else if (progress_status == 3) {
                            alert('已终止');
                        } else {
                            alert('已结案');
                        }
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
        })
	*/
       $('.dl01 a').bind('click',function(){
            var province_id = $(this).attr('id');
           var cat = $('.dd a.current').attr('id');
           var progress = $('.zone a.current').attr('id');
           var money = $('.zone_a a.current').attr('id');
            $.ajax({
                url:"<?php echo yii\helpers\Url::toRoute('/list/city')?>",
                type:'post',
                data:{fatherID:province_id},
                dataType:'json',
                success:function(json){
                    var city_con = "";
					//var a = '<dd><a href="javascript:void(0);" id="0" class="cityQubu">全部</a></dd>';
                    $.each(json,function(n,value){
                        $.each(value,function(city_id,val){
                            if(city_id == '0'){
                             city_con += '<dd><a href="javascript:void(0)" class="cityQubu" id="' + city_id + '" onclick="area(' + city_id + ',' + province_id + ')" >' + val + '</a></dd>';
						    }else{
							 city_con += '<dd><a href="javascript:void(0)" id="' + city_id + '" onclick="area(' + city_id + ',' + province_id + ')" >' + val + '</a></dd>';
							}
                            
                        });
                    });
                    $(".dl02 dl").html(city_con);
                }
            })
          });
    });


</script>


