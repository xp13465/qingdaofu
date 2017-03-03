<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<style>
.num{background:#F3F7FA;}
.num p{font-size:16px;line-height:30px;padding-left:20px;}
</style>
<header>
    <div class="cm-header">
        <span class="icon-back" ></span>
        <i>我的发布</i>
    </div>
</header>
<section>
    <div class="cp_s">
        <ul>
            <?php $status = [0=>'全部',1=>'已发布',2=>'处理中',3=>'终止',4=>'结案']?>
            <?php foreach($status as $k => $v) { ?>
                <li class="cp_sy" id="<?php echo $k;?>"><a href="javascript:void(0);" <?php if(\Yii::$app->request->get('status') == $k){echo 'class="current"';}?> ><?php echo $v;?></a></li>
            <?php } ?>
        </ul>
    </div>
    <?php if(!empty($rele) ){ ?>
<p id="show"></p> 
<div id="wrapper" style="overflow:scroll;">
	<div id="scroller" class="type">
		<div id="pullDown" style="display:none;">
			<span class="pullDownIcon"></span><span class="pullDownLabel"></span>
		</div>
        <ul id="thelist"  data-catr="<?php echo count($rele) ?>">
            <?php foreach($rele as $value){ ?>
            <li>
                <div class="types" data-id = "<?php echo $value['id'];?>" data-category="<?php echo $value['category'];?>">
                    <div class="rongzi" style="border-bottom: 0px solid #ddd;">
                        <div class="over">
                            <div class="flo_l" style="width:75%;float:left;">
                                <?php $key = [1=>'rz_ig',2=>'rz_ig01',3=>'rz_ig02']?>
                               
                                <span class="code  <?php echo $key[$value['category']];?>"><?php echo isset($value['code'])?$value['code']:''?></span>
                            </div>
                            <?php $status = [0=>'待发布',1=>'发布中',2=>'处理中',3=>'已终止']?>
                            <span class="flo_r"><?php echo isset($value['progress_status'])&&$value['progress_status']==4?'':$status[$value['progress_status']] ?></span>
                        </div>
                    </div>
                    <div class="num">
                    <p>借款本金:<?php echo isset($value['money'])?$value['money']:''?>万</p>
                    <p>
				<?php if(isset($value['category'])&&$value['category'] == 2){ ?>	
                        <?php echo isset($value['agencycommissiontype'])&&$value['agencycommissiontype']==1?'服务佣金:':'固定费用:'?>
                           <?php if($value['agencycommissiontype']==1){ ?>
                               <?php echo isset($value['agencycommission'])?$value['agencycommission'].'%':'暂无' ?>
                           <?php }else{ ?>
                               <?php echo isset($value["agencycommission"])?$value["agencycommission"].'万':'暂无';?>
                           <?php } ?>
                    <?php }else{ ?>
                        <?php echo isset($value['agencycommission'])&&$value['agencycommissiontype']==1?'固定费用:':'风险费率:'?>
                        <?php if($value['agencycommissiontype']==1){ ?>
                            <?php echo isset($value["agencycommission"])?$value["agencycommission"].'万':'暂无';?>
                        <?php }else{ ?>
                            <?php echo isset($value["agencycommission"])?$value["agencycommission"].'%':'暂无';?>
                        <?php } ?>
                    <?php } ?>
				    </p>
					<?php if($value['loan_type'] == 1){ ?>
					    <p>抵押物地址:
					<?php if($value['category'] == 1){?>
                            <?php echo \frontend\services\Func::getCityNameById($value['city_id']);?>
                            <?php echo \frontend\services\Func::getAreaNameById($value['district_id']);?>
                            <?php echo isset($value['seatmortgage'])?\frontend\services\Func::getSubstrs($value['seatmortgage']):'';?>
                    <?php } else if($value['loan_type'] == 1){?>
                            <?php echo \frontend\services\Func::getCityNameById($value['city_id']);?>
                            <?php echo \frontend\services\Func::getAreaNameById($value['district_id']);?>
                            <?php echo isset($value['seatmortgage'])?\frontend\services\Func::getSubstrs($value['seatmortgage']):'';?>
                    <?php }else{echo '';}?></p>
					<?php } ?>
                   
                    </div>
                </div>
                <?php if($value['progress_status'] == 1){?>
                    <div class="sup_sq">
                        <div class="tip_l">
                            <?php if(isset($value['app_id'])&&$value['app_id'] == 0){ ?>
                                <span class="tip_icon"></span>
                                <span class="tip_jl">您有新的申请记录</span>
                            <?php }else{ echo '';}?>
                        </div>
                        <div class="tip_r">
                            <!--<a href="#">补充信息</a>-->
                            <a href="<?php echo yii\helpers\Url::toRoute(['/usercenter/view','id'=>$value['id'],'category'=>$value['category']])?>" class="current">查看申请</a>
                        </div>
                    </div>
                <?php }else if($value['progress_status'] == 2){ ?>
                    <div class="sup_sq">
                        <div class="tip_r">
                            <a href="<?php echo yii\helpers\Url::toRoute(['/usercenter/speed','id'=>$value['id'],'category'=>$value['category'],'type'=>1])?>">查看进度</a>
                        </div>
                    </div>
                <?php }else if($value['progress_status'] == 4){ ?>
                    <div class="jiean">
                        <span></span>
                    </div>
                    <?php if($creditor[$value['id'].'_'.$value['category']] == 1 || $creditor[$value['id'].'_'.$value['category']] >= 2){ ?>
                        <div class="sup_sq">
                            <div class="tip_r">
                                <a href="<?php echo yii\helpers\Url::toRoute(['/usercenter/evaluatelists','id'=>$value['id'],'category'=>$value['category']])?>" class="current">查看评价</a>
                            </div>
                        </div>
                    <?php }else{ ?>
                        <div class="sup_sq">
                            <div class="tip_r">
                                <a href="<?php echo yii\helpers\Url::toRoute(['/releases/addevaluation','id'=>$value['id'],'category'=>$value['category']])?>" class="current">去评价</a>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </li>
            <?php } ?>
        </ul>
		<div id="pullUp" style="display:none;" >
			<span class="pullUpIcon"></span><span class="pullUpLabel"></span>
		</div>
	</div>
</div>
    <?php } ?>
</section>
<div style="height:50px;width:50px;position:fixed;bottom:50px;z-index:99999;">
    <a href="<?php echo yii\helpers\Url::toRoute('/site/index')?>"><img src="/images/back_home.png"></a>
</div>

<script type="text/javascript" src="/js/fastclick.js"></script>
<script type="text/javascript">
    function release(status,page){
        location.href = "<?php echo yii\helpers\Url::toRoute('/usercenter/release')?>?status="+status+"&page="+page;
    }
    $(document).ready(function(){
        $('.cp_s ul').delegate('li','click',function(){
            release($(this).attr('id'),0);
        });

        $('.types').click(function(){
            var id = $(this).attr('data-id');
            var category = $(this).attr('data-category');
            location.href = "<?php echo yii\helpers\Url::toRoute('/releases/index')?>?id="+id+'&category='+category;
        });
        $('.icon-back').click(function () {
            var type = "<?php echo Yii::$app->request->get('type')?>";
            if(type == 1){
                history.go(-1);
            }else{
                window.location = "<?php echo yii\helpers\Url::toRoute('/usercenter/index')?>";
            }
        });
    });

    var page = "<?php echo Yii::$app->request->get('page')<=0?1:Yii::$app->request->get('page')?>";
    function pullDownAction () {
                setTimeout(function () {
                    var el, li, i;
                    el = document.getElementById('thelist');
                    page--;
                    $('.cp_s ul li').each(function(){
                        if($(this).children().attr('class') == "current") {
                            release($(this).attr('id'), page);
                        }
                        });
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
                $('.cp_s ul li').each(function(){
                    if($(this).children().attr('class') == "current") {
                        release($(this).attr('id'), page);
                    }
                });
                myScroll.refresh();
            }, 1000);
        }
    }
</script>
