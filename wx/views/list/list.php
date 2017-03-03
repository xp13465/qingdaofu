<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
use wx\services\Func;

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->registerJsFile('@web/js/jquery.cityselect.js',['depends'=>['wx\assets\AppAsset']])


?>
<?php
$category = [0=>'全部',1=>'',2=>'清收',3=>'诉讼'];
$progress = [0=>'不限',1=>'发布中',2=>'处理中','3,4'=>'已结案'];
$money = [0=>'不限',1=>'30万以下',2=>'30-100万',3=>'100-500万',4=>'500万以上'];

?>
<div class="Divgray"></div>
<header class="hid" style="z-index:2;">
    <div class="header">
        <div class="select_p">
            <div class="qb" style="border-bottom: 1px solid #ddd;">
                <span style="font-size:20px;"><?php echo isset($category[Yii::$app->request->get('cat')])?$category[Yii::$app->request->get('cat')]:'所有产品'?></span>
                <i class="icon-arrow"></i>
            </div>
            <!--<div class="search"></div>-->
        </div>
     <div class="dd">
            <a href='javascript:void(0)' id="0" <?php if(Yii::$app->request->get('cat') == 0){echo 'class="current"';}?>>全部</a>
            <!--<a href='javascript:void(0)' id="1" <?php /* if(Yii::$app->request->get('cat') == 1){echo 'class="current"';}*/?>></a>-->
            <a href='javascript:void(0)' id="2" <?php if(Yii::$app->request->get('cat') == 2){echo 'class="current"';}?>>清收</a>
            <a href='javascript:void(0)' id="3" <?php if(Yii::$app->request->get('cat') == 3){echo 'class="current"';}?>>诉讼</a>
        </div>
    </div>
</header>
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
</style>
<section>
    <div class="list" style="position:relative;">
        <ul >
            <li class="one">
                <a href='javascript:void(0)' <?php if(Yii::$app->request->get('city_id')){echo 'class="current"';}?> id="0"><?php echo Yii::$app->request->get('city_id')?\frontend\services\Func::getCityNameById(Yii::$app->request->get('city_id')):'区域'?></a>
                <span <?php if(Yii::$app->request->get('city_id')){echo 'class="triangle"';}else{echo  'class="triangle triangle01"';}?>></span>
            </li>
            <li>
                <a href='javascript:void(0)' id="0" <?php if(in_array(Yii::$app->request->get('progress'),[1,2,3])){echo 'class="current state"';}else{echo 'class="state"';}?>><?php echo isset($progress[Yii::$app->request->get('progress')])?  $progress[Yii::$app->request->get('progress')]:'状态'?></a>
                <span <?php if(in_array(Yii::$app->request->get('progress'),[1,2,3,4])){echo 'class="triangle"';}else{echo  'class="triangle triangle01"';}?>></span>
            </li>
            <li>
                <a href='javascript:void(0)' id="0" <?php if(in_array(Yii::$app->request->get('money'),[1,2,3,4])){echo 'class="current moneys"';}else{echo 'class="moneys"';}?>><?php echo isset($money[Yii::$app->request->get('money')])?  $money[Yii::$app->request->get('money')]:'金额'?></a>
                <span <?php if(in_array(Yii::$app->request->get('money'),[1,2,3,4])){echo 'class="triangle"';}else{echo  'class="triangle triangle01"';}?>></span>
            </li>
        </ul>
    </div>
    <section class="tabSX tabFixed" style="z-index:0;">
        <div class="lbTab">
            <div class="districtcls" style="display: none;">
                <div class="cont flexbox" style="display: -webkit-box;">
                    <section id="searchnew" class="dl01">
                        <dl style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
			             <dd><a href="#" class='buxian'>不限</a></dd>
						<?php foreach($province as $key => $value) :?>
							<dd class="areas_b"><a href="javascript:void(0)" <?php if(Yii::$app->request->get('province_id') == isset($key)?$key:310000){echo 'class="current"';} ?> id="<?= $key ?>" style="<?php if ($key == 310000) echo "color:red;";?>"  ><?= $value ?></a></dd>
						<?php endforeach;?>
                        </dl>
                    </section>
                    <section class="dl02">
                        <dl style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
							<?php foreach($citys as $key=>$value) :?>
								<?php if($key == 310000):?>
									<?php foreach($value as $key => $city):?>
										<dd class="areas_d"><a href="javascript:void(0)" <?php if(Yii::$app->request->get('city_id') == isset($key)?$key:310000){echo 'class="current"';} ?> id="<?= $key ?>" ><?= $city ?></a></dd>
									<?php endforeach;?>                         
								<?php endif;?>
							<?php endforeach;?>
                        </dl>
                    </section>
                    <section class="dl03">
                        <dl id="comarea_dl_25" class="column3" style="display: block; transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
							<?php foreach($areas as $key=>$value) :?>
								<?php if($key == 310100):?>
									<?php foreach($value as $area):?>
										<dd class="areas_e"><a href="javascript:void(0)" <?php if(Yii::$app->request->get('area_id') == isset($key)?$key:310100){echo 'class="current"';} ?>id="<?= $key ?>"><?= $area ?></a></dd>
									<?php endforeach;?>                         
								<?php endif;?>
							<?php endforeach;?>
                            <dd><a href="#">闸北区</a></dd>
                        </dl>
                    </section>
                </div>
            </div>
        <div class="zone" style="display:none">
            <a href='javascript:void(0)' id="0" <?php if(Yii::$app->request->get('progress') == 0){echo 'class="current"';}?>>不限</a>
            <a href='javascript:void(0)' id="1" <?php if(Yii::$app->request->get('progress') == 1){echo 'class="current"';}?>>发布中</a>
            <a href='javascript:void(0)' id="2" <?php if(Yii::$app->request->get('progress') == 2){echo 'class="current"';}?>>处理中</a>
            <a href='javascript:void(0)' id="3,4" <?php if(Yii::$app->request->get('progress') == 3 || Yii::$app->request->get('progress') == 4){echo 'class="current"';}?>>已结案</a>
        </div>
        <div class="zone_a" style="display:none">
            <a href='javascript:void(0)' id="0" <?php if(Yii::$app->request->get('money') == 0){echo 'class="current"';}?>>不限</a>
            <a href='javascript:void(0)' id="1" <?php if(Yii::$app->request->get('money') == 1){echo 'class="current"';}?>>30万以下</a>
            <a href='javascript:void(0)' id="2" <?php if(Yii::$app->request->get('money') == 2){echo 'class="current"';}?>>30-100万</a>
            <a href='javascript:void(0)' id="3" <?php if(Yii::$app->request->get('money') == 3){echo 'class="current"';}?>>100-500万</a>
            <a href='javascript:void(0)' id="4" <?php if(Yii::$app->request->get('money') == 4){echo 'class="current"';}?>>500万以上</a>
        </div>

        </div>
    </section>
</section>
<section>
    <p id="show"></p>
    <div id="wrapper" style="overflow:scroll;">
        <div id="scroller" class="type" style="position:absolute;">
            <div id="pullDown" style="display:none">
                <span class="pullDownIcon"></span><span class="pullDownLabel"></span>
            </div>
            <ul id="thelist" data-catr="<?php echo count($catr);?>" >
            <?php foreach($catr as $value){ ?>
                <li class="product" data-category="<?php echo $value['category']?>" data-id="<?php echo $value['id'] ?>" data-progress="<?php echo $value['progress_status']?>">
                    <div class="rongzi">
                        <?php if($value['category'] == 1){?>
                            <span class="rz_ig"></span>
                        <?php }else if($value['category'] == 2){?>
                            <span class="rz_ig01"></span>
                        <?php }else if($value['category'] == 3){?>
                            <span class="rz_ig02"></span>
                        <?php } ?>
                        <span class="code"><?php echo isset($value['code'])?$value['code']:''?></span>
                        <?php if($value['category'] == 1){?>
                            <p>抵押物地址：
                                <?php echo \frontend\services\Func::getCityNameById($value['city_id']);?>
                                <?php echo \frontend\services\Func::getAreaNameById($value['district_id']);?>
                                <?php echo isset($value['seatmortgage'])?\frontend\services\Func::getSubstrs($value['seatmortgage']):'';?>
                            </p>
                        <?php } else if($value['loan_type'] == 1){?>
                            <p>    抵押物地址：
                                <?php echo \frontend\services\Func::getCityNameById($value['city_id']);?>
                                <?php echo \frontend\services\Func::getAreaNameById($value['district_id']);?>
                                <?php echo isset($value['seatmortgage'])?\frontend\services\Func::getSubstrs($value['seatmortgage']):'';?>
                            </p>
                        <?php }else{echo '';}?>
                    </div>
                    <div class="num">
                        <ul>
                            <li>
							<span class="blue">
								<?php echo isset($value['money'])?$value['money']:'','<i style="font-size:14px;">万</i>'?>
							</span>
                                <span class="loan">借款本金</span>
                            </li>
                            <li>
							<span class="black">
								<?php if($value['category'] == 1){ ?>
                                    <?php echo isset($value['rebate'])?$value['rebate']:'','%';?>
                                <?php }else if($value['category'] == 2){?>
                                    <?php echo isset($value['agencycommissiontype'])&&$value['agencycommissiontype']==1?$value['agencycommission'].'%':$value['agencycommission'].'万';?>
                                <?php }else if($value['category'] == 3){?>
                                    <?php if($value['agencycommissiontype']==1){ ?>
                                        <?php echo isset($value['agencycommission'])?$value['agencycommission'].'万':''?>
                                    <?php }else{ ?>
                                        <?php echo isset($value['agencycommission'])?$value['agencycommission'].'%':''?>
                                    <?php } ?>
                                <?php } ?>
							</span>
                            <span class="loan">
                                <?php if($value['category'] == 1){ echo '返点';}
                                else if($value['category'] == 2){echo isset($value['agencycommissiontype'])&&$value['agencycommissiontype']==1?'服务佣金':'固定费用';}
                                else if($value['category'] == 3){ ?>
                                    <?php echo isset($value['agencycommissiontype'])?\common\models\CreditorProduct::$agencycommissiontype[$value['agencycommissiontype']]:'';?>
                                <?php } ?>
                            </span>
                            </li>
                            <li>
                                <?php if($value['category'] == 1){ ?>
                                    <span class="chen">
                                    <?php echo isset($value['rate'])?$value['rate']:'';?><span class="sx">%</span>
                                </span>
                                <?php }else{ ?>
                                    <span class="blue font">
                                    <?php echo isset($value['loan_type'])&&$value['loan_type']==0 ? '无':common\models\CreditorProduct::$loan_types[$value['loan_type']]?>
                                </span>
                                <?php } ?>
                                <span class="loan"><?php if($value['category'] == 1){ echo '借款利率';echo '(';echo isset($value['rate_cat'])?(\common\models\FinanceProduct::$ratedatecategory[$value['rate_cat']]):'';echo ')';}else{echo'债权类型';}?></span>
                            </li>
                        </ul>
                        <?php if($value['progress_status'] == 4){ ?>
                            <div class="jiean">
                                <span></span>
                            </div>
                        <?php } ?>
                    </div>
                </li>
            <?php } ?>
        </ul>
		<div id="pullUp" style="display:none;" >
			<span class="pullUpIcon"></span><span class="pullUpLabel"></span>
		</div>
	</div>
</div>
</section>

<?php echo  \wx\widget\wxFooterWidget::widget()?>
<script type="text/javascript" src="/js/fastclick.js"></script>
<script>

function searchForm(cat,area_id,progress,money,province_id,city_id,page){
    window.location = "<?php echo \yii\helpers\Url::toRoute('/list/list')?>"+"?cat="+cat+"&&area_id="+area_id+"&&progress="+progress+"&&money="+money+"&&province_id="+province_id+"&&city_id="+city_id+"&&page="+page;

}

function searchArea(cat,area_id,progress,money, city_id, province_id){
    searchForm(cat,area_id,progress,money,province_id,city_id);
}


    function area(city_id, province_id){
               var cat = $('.dd a.current').attr('id');
                var progress = $('.zone a.current').attr('id');
                var money = $('.zone_a a.current').attr('id');
            $.ajax({
                url:"<?php echo yii\helpers\Url::toRoute('/list/area')?>",
                type:'post',
                data:{fatherID:city_id},
                dataType:'json',
                success:function(json){
                    var area_con = "";
                    $.each(json,function(n,value){
                        $.each(value,function(ared_id,val){
                            area_con += '<dd><a href="javascript:void(0)" id="' + ared_id + '" onclick="searchArea('+cat+',' + ared_id + ','+progress+','+money+',' + city_id + ',' + province_id + ')">' + val + '</a></dd>';
                        });
                    });
                    $(".dl03 dl").html(area_con);
                }
            })
}

$(document).ready(function(){
        $('.qb').click(function() {
            $('.dd').slideToggle();
            $('.Divgray').toggle();
            $('.zone').hide();
            $('.zone_a').hide();
            $('.dd a').click(function(){
                $('.Divgray').hide();
                searchForm($(this).attr('id'),$('.areas_e a.current').attr('id'),$('.zone a.current').attr('id'),$('.zone_a a.current').attr('id'),$('.areas_b a.current').attr('id'),$('.areas_d a.current').attr('id'),0);
            });
        });
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

            
        $('.one').click(function () {
            $('.districtcls').toggle();
            $('.Divgray').toggle();
            $('.tabFixed').attr('style','z-index:11');
            $('.dd').hide();
            $('.zone_a').hide();
            $('.zone').hide();
            $('.dl02').hide();
            $('.dl03').hide();
        });
		$('.buxian').click(function(){
			$('.districtcls').hide();
            $('.Divgray').hide();
			searchForm($('.dd a.current').attr('id'),0,$('.zone a.current').attr('id'),$('.zone_a a.current').attr('id'),0,0,0);
		})


        $('.dl01').click(function () {
            $('.dl02').show();
        })

        $('.dl02').click(function () {
            $('.dl03').show();
        })
            

        $('.state').click(function(){
            $('.zone').slideToggle();
            $('.Divgray').toggle();
            $('.tabFixed').attr('style','z-index:11');
            $('.dd').hide();
            $('.districtcls').hide();
            $('.zone_a').hide();
            $('.zone a').click(function(){
                searchForm($('.dd a.current').attr('id'),$('.areas_e a.current').attr('id'),$(this).attr('id'),$('.zone_a a.current').attr('id'),$('.areas_b a.current').attr('id'),$('.areas_d a.current').attr('id'),0);
            });
        })
        $('.moneys').click(function(){
            $('.zone_a').slideToggle();
            $('.Divgray').toggle();
            $('.tabFixed').attr('style','z-index:11');
            $('.districtcls').hide();
            $('.zone').hide();
            $('.dd').hide();
            $('.zone_a a').click(function(){
                searchForm($('.dd a.current').attr('id'),$('.areas_e a.current').attr('id'),$('.zone a.current').attr('id'),$(this).attr('id'),$('.areas_b a.current').attr('id'),$('.areas_d a.current').attr('id'),0);
            });
     })

            
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
                    $.each(json,function(n,value){
                        $.each(value,function(city_id,val){
                            city_con += '<dd><a href="javascript:void(0)" id="' + city_id + '" onclick="area(' + city_id + ',' + province_id + ')" >' + val + '</a></dd>';
                        });
                    });
                    $(".dl02 dl").html(city_con);
                }
            })
          });
    });
var page = "<?php echo Yii::$app->request->get('page')<=0?1:Yii::$app->request->get('page')?>";
function pullDownAction () {
        setTimeout(function () {
            var el, li, i;
            el = document.getElementById('thelist');
            page--;
            searchForm($('.dd a.current').attr('id'), $('.areas_e a.current').attr('id'), $('.zone a.current').attr('id'), $('.zone_a a.current').attr('id'), $('.areas_b a.current').attr('id'), $('.areas_d a.current').attr('id'), page);
            myScroll.refresh();
        }, 1000);
}

function pullUpAction () {
    var catr = $('#thelist').attr('data-catr');
    if(catr <10 ) {
        setTimeout(function () {
            var el, li, i;
            el = document.getElementById('thelist');
            myScroll.refresh();
        }, 1000);

   }else{
        setTimeout(function () {
            var el, li, i;
            el = document.getElementById('thelist');
            page++;
            searchForm($('.dd a.current').attr('id'), $('.areas_e a.current').attr('id'), $('.zone a.current').attr('id'), $('.zone_a a.current').attr('id'), $('.areas_b a.current').attr('id'), $('.areas_d a.current').attr('id'), page);
            myScroll.refresh();
        }, 1000);
    }
}

</script>


