<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<style type="text/css">
    #allmap {width: 1200px;height: 600px;overflow: hidden;margin:0;font-family:"微软雅黑";}
</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=w10qOR1ck89d9YevXrdWPqGg"></script>
<div class="banners">
    <div class="banners2 clearfix">
        <ul>
            <li>
                <img src="/images/b1.png" height="349" width="1000" alt="">
            </li>
            <li>
                <img src="/images/b2.png" height="349" width="1000" alt="">
            </li>
            <li>
                <img src="/images/b3.png" height="349" width="1000" alt="">
            </li>
            <li>
                <img src="/images/b4.png" height="349" width="1000" alt="">
            </li>
        </ul>
        <ol>
            <li class="current-b"><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
        </ol>
    </div>

</div>
<!-- banner 结束-->
<script type="text/javascript">
    /*
     * 智能机浏览器版本信息:
     *
     */
    var browser={
        versions:function(){
            var u = navigator.userAgent, app = navigator.appVersion;
            return {//移动终端浏览器版本信息
                trident: u.indexOf('Trident') > -1, //IE内核
                presto: u.indexOf('Presto') > -1, //opera内核
                webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
                gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
                mobile: !!u.match(/AppleWebKit.*Mobile.*/)||!!u.match(/AppleWebKit/), //是否为移动终端
                ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
                android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
                iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1, //是否为iPhone或者QQHD浏览器
                iPad: u.indexOf('iPad') > -1, //是否iPad
                webApp: u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
            };
        }(),
        language:(navigator.browserLanguage || navigator.language).toLowerCase()
    }

</script>
<!-- 内容开始-->
<div class="index">
    <div class="map">
        <div class="map_img">
            <?php
            $category = [
                0=>'资产类型',
                1=>'　融资　',
                2=>'　清收　',
                3=>'　诉讼　',
            ];
            $money = [
                0=>'资产总额',
                1=>'30万以下',
                2=>'30-100万',
                3=>'100-500万',
                4=>'500万以上',
            ];

            $district = \frontend\services\Func::getDistrictByCity('310100');
            ?>
            <div class="cons1">
                <div class="maps">

                    <div id="allmap">
                        
                    </div>
                    <div class="shrink">
                        
                    </div>
                    

                </div>
                <div class="show">
                    <span class="show1">
                        <b> 收起</b>
                        <b class="zk"> 展开</b>
                        <div>
                            <img src="/images/shouqi.png" alt="">
                            <img src="/images/shouqi1.png" alt="" class="zk1">
                        </div>
                    </span>
                    <!-- <span class="hide1">展开</span> -->
                </div>
            </div>
        <div class="maptext">
            <?php $creditor = Yii::$app->db->createCommand("select sum(money) from zcb_creditor_product  where progress_status <> 0")->queryScalar(); ?>
            <?php $finance = Yii::$app->db->createCommand("select sum(money) from zcb_finance_product  where progress_status <> 0")->queryScalar(); ?>
            <?php $creditopen = Yii::$app->db->createCommand("select count(id) from zcb_creditor_product")->queryScalar(); ?>
            <?php $financepen = Yii::$app->db->createCommand("select count(id) from zcb_finance_product")->queryScalar(); ?>
            <?php $organization = Yii::$app->db->createCommand("select count(category) from zcb_certification where category = 3")->queryScalar(); ?>
            <?php $lawyer = Yii::$app->db->createCommand("select count(category) from zcb_certification where category = 2")->queryScalar(); ?>

            <ul>
                <li class="f24"><i><img src="/images/tu1.jpg" height="70" width="98" alt=""></i>　<b data-to="" data-speed="2500" id="num01"></b><em class="f15"> 笔</em><p>资产发布</p>
                <li class="f24"><i><img src="/images/tu2.jpg" height="70" width="98" alt=""></i>　<b data-to="" data-speed="2500" id="num02"></b><em class="f15"> 亿元</em><p>资产总额</p>
                <li class="f24"><i><img src="/images/tu3.jpg" height="70" width="98" alt=""></i>　<b data-to="" data-speed="2500" id="num03"></b><em class="f15"> 家</em><p>机构入驻</p>
                <li class="f24"><i><img src="/images/tu4.jpg" height="70" width="98" alt=""></i>　<b data-to="" data-speed="2500" id="num04"></b><em class="f15"> 位</em><p>律师入驻</p>
            </ul>
        </div>
    </div>

        <div class="recommend">
            <p class="jd" id="1"><span>推荐产品</span><span class="fr">
                    <?php if(Yii::$app->user->getId()){?>
                    <a href="<?php echo \yii\helpers\Url::toRoute('/publish/publish')?>">一键发布</a>
                    <?php } else{?>
                    <a href="<?php echo \yii\helpers\Url::toRoute('/site/login')?>">一键发布</a>
                    <?php }?>

                </span></p>
            <?php if(isset($release)&&$release): ?>
            <?php foreach($release as $value):?>
            <div class="rec_detail">
                <span><b><?php echo isset($value['city_id']) && $value['city_id']== 310100 ?'上海市':''?><?php $v = common\models\Area::findOne(['areaID'=>(isset($value['district_id'])&& $value['district_id'] == 0)?'1':$value['district_id']]); echo isset($v->area)?$v->area:'';?></b><br>区域</span>
                <span><b><?php echo common\models\CreditorProduct::$categorys[$value['category']]?></b><br>类型</span>
                <span><b><?php echo isset($value['code'])?$value['code']:'';?></b><br>编号</span>
                <span><i><?php echo isset($value['money'])?$value['money']:'';?></i><br>金额(万元)</span>
                <span><em><?php if($value['category'] == 1){echo isset($value['term'])?$value['term']:'';}else{ $cert=\common\models\CreditorProduct::findOne(['id'=>$value['id']]); echo isset($cert['commissionperiod'])?$cert['commissionperiod']:'';}?></em><br>期限(<?php if($value['category'] == 1){echo $value['rate_cat'] == 1 ?'天':'个月';}else{echo '个月';}?>)</span>
                <?php if(in_array($value['progress_status'],[2,3,4])){
                    echo "<span class='last'><input type='button' value='查看' style='background:#b5b5b5;width:100px;text-align:center; margin-left:22px; border-radius:4px;'></span>";
                }else{
                    echo '<a href="javascript:void(0);" class="see" onclick="check('.$value["id"].','.$value["category"].');" style="margin: 18px 25px 0 0;">查看</a>';
                }?>
            </div>
                <?php endforeach;?>
            <?php endif; ?>
        </div>

        <!-- 处置风采 -->
        <div class="management clearfix">
            <span id="2">处置风采:　</span>
            <marquee behavior="scroll" direction="left" scrollamount="4">
                【海华永泰律师事务所】成功处置诉讼1笔 金额500万　　　2016-2-18　　　
            </marquee>
            <a href="javascript:;" class="fr">更多>></a>
        </div>
        <!-- 加入及发布 -->
        <div class="join clearfix">
            <div class="join1 fl clearfix">
                <!-- 
                    <img src="/images/banner1.png" alt="">
                   <div class="joins">
                        <?php if(Yii::$app->user->getId()){?>
                        <a href="<?php echo \yii\helpers\Url::toRoute('/certification/index')?>">加入我们</a>
                        <?php } else{?>
                            <a href="<?php echo \yii\helpers\Url::toRoute('/site/signup')?>">加入我们</a>
                        <?php }?>
                   </div> -->
                <img class="imgs" src="/images/bannero1.jpg" style="position:absolute;z-index:4">
                <embed class="midd" align="middle" width="330" height="189" allowscriptaccess="always"
                allowfullscreen="true" src="http://player.youku.com/player.php/sid/XMTUyNzI3NjM2OA==/v.swf"
                quality="high" type="application/x-shockwave-flash"></embed>
            </div>
            <div class="send fr clearfix">
                <img src="/images/banner2.png" alt="">
                <div class="send1">
                    <?php if(Yii::$app->user->getId()){?>
                        <a href="<?php echo \yii\helpers\Url::toRoute('/publish/publish')?>">一键发布</a>
                    <?php } else{?>
                        <a href="<?php echo \yii\helpers\Url::toRoute('/site/login')?>">一键发布</a>
                    <?php }?>
                </div>
            </div>
        </div>
        <div class="news_information">
            <p class="jd"><span>新闻动态</span>　
                <a class="current">全部</a>
                <a>新闻</a>
                <a>公告</a>
                <a>债权</a>
                <a>清收</a>
                <a href="<?php echo \yii\helpers\Url::toRoute('/homepage/newslist')?>" class="mores">更多>></a>
            </p>
            <div class="notice">
                <div>
                    <ul class="clearfix">
                        <?php if(isset($release)&&$release):?>
                        <?php foreach($news as $v):?>
                        <li class="clearfix">
                            <b class="fl" style="">[<?php echo isset($v['create_time'])?date('m-d',$v['create_time']):''?>]</b>
                            <a href="<?php echo \yii\helpers\Url::toRoute(['/homepage/newscontent/','cid' => $v['id']])?>" class="clearfix">
                                    <span class="notice_1"><?php echo isset($v['title'])?mb_substr($v['title'],0,30,'utf-8').'...':'';?></span>
                            </a>
                        </li>
                            <?php endforeach;?>
                        <?php endif;?>
                    </ul>
                </div>
                <div class="t">
                    <?php $newsadd = \common\models\News::find()->where(['category'=>0])->limit(3)->orderBy('create_time desc')->all();?>
                    <ul class="clearfix">
                        <?php foreach($newsadd as $v):?>
                        <li class="clearfix">
                            <b class="fl">[<?php echo isset($v['create_time'])?date('m-d',$v['create_time']):''?>]</b>
                            <a href="<?php echo \yii\helpers\Url::toRoute(['/homepage/newscontent/','cid' => $v['id']])?>" class="clearfix">
                                <span class="notice_1"><?php echo isset($v['title'])?mb_substr($v['title'],0,30,'utf-8').'...':'';?></span>　　　
                            </a>
                        </li>
                        <?php endforeach;?>
                    </ul>
                </div>
                <div class="t1">
                    <?php $newsadds = \common\models\News::find()->where(['category'=>1])->limit(3)->orderBy('create_time desc')->all();?>
                    <ul class="clearfix">
                        <?php foreach($newsadds as $v):?>
                        <li class="clearfix">
                            <b class="fl">[<?php echo isset($v['create_time'])?date('m-d',$v['create_time']):''?>]</b>
                            <a href="<?php echo \yii\helpers\Url::toRoute(['/homepage/newscontent/','cid' => $v['id']])?>" class="clearfix">
                                <span class="notice_1"><?php echo isset($v['title'])?mb_substr($v['title'],0,30,'utf-8').'...':'';?></span>
                            </a>
                        </li>
                        <?php endforeach;?>
                    </ul>
                </div>
                <div class="t2">
                    <?php $newsadds = \common\models\News::find()->where(['category'=>2])->limit(3)->orderBy('create_time desc')->all();?>
                    <ul class="clearfix">
                        <?php foreach($newsadds as $v):?>
                            <li class="clearfix">
                                <b class="fl">[<?php echo isset($v['create_time'])?date('m-d',$v['create_time']):''?>]</b>
                                <a href="<?php echo \yii\helpers\Url::toRoute(['/homepage/newscontent/','cid' => $v['id']])?>" class="clearfix">
                                    <span class="notice_1"><?php echo isset($v['title'])?mb_substr($v['title'],0,30,'utf-8').'...':'';?></span>
                                </a>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
                <div class="t3">
                    <?php $newsadds = \common\models\News::find()->where(['category'=>3])->limit(3)->orderBy('create_time desc')->all();?>
                    <ul class="clearfix">
                        <?php foreach($newsadds as $v):?>
                            <li class="clearfix">
                                <b class="fl">[<?php echo isset($v['create_time'])?date('m-d',$v['create_time']):''?>]</b>
                                <a href="<?php echo \yii\helpers\Url::toRoute(['/homepage/newscontent/','cid' => $v['id']])?>" class="clearfix">
                                    <span class="notice_1"><?php echo isset($v['title'])?mb_substr($v['title'],0,30,'utf-8').'...':'';?></span>
                                </a>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
            </div>
    <div class="hezuo1">
        <p class="jd"><span>合作伙伴</span></p>
        <div class="huoban">
            <div class="huoban2">
                <ul class="clearfix">
                    <li><a href="javascript:;"><img src="/images/huobanimg_1.png" alt=""></a></li>
                    <li><a href="javascript:;"><img src="/images/huobanimg_2.png" alt=""></a></li>
                    <li><a href="javascript:;"><img src="/images/huobanimg_3.png" alt=""></a></li>
                    <li><a href="javascript:;"><img src="/images/huobanimg_4.png" alt=""></a></li>
                    <li><a href="javascript:;"><img src="/images/huobanimg_5.png" alt=""></a></li>
                    <li><a href="javascript:;"><img src="/images/huobanimg_6.png" alt=""></a></li>
                    <li><a href="javascript:;"><img src="/images/huobanimg_7.png" alt=""></a></li>
                    <li><a href="javascript:;"><img src="/images/huobanimg_8.png" alt=""></a></li>
                    <li><a href="javascript:;"><img src="/images/huobanimg_9.png" alt=""></a></li>
                    <li><a href="javascript:;"><img src="/images/huobanimg_10.png" alt=""></a></li>
                    <li><a href="javascript:;"><img src="/images/huobanimg_11.png" alt=""></a></li>
                    <li><a href="javascript:;"><img src="/images/huobanimg_12.png" alt=""></a></li>
                </ul>
            </div>
            <!-- <div class="left2"><img src="../images/l_arrow.jpg"></div>
            <div class="right2"><img src="../images/r_arrow.jpg"></div> -->
        </div>
    </div>
</div>

</div>
  
<!--<div>
    <div class="xuanfu" id="div2">
        <h3>公告</h3>
        <p>尊敬的用户您好，欢迎访问清道夫债管家。
        <span>平台目前尚未正式上线，</span>具体上线日期以平台公告为准，感谢您的关注与支持，谢谢!</p>
        <a href="javascript:;">关闭</a>
    </div>
</div>  -->
<script type="text/javascript">
    $('.join1').click(function(){
       $('.imgs').hide();
    })
    function check(id, category){
        var url ="<?php echo Url::toRoute("/capital/certification"); ?>";
        $.post(url,{id:id,category:category},function(v){
            if(v.status == 0){
                $.msgbox({
                    height: 180,
                    width: 330,
                    content: '<p style="text-align:center">您还未登录,请先<a href="<?php echo Url::toRoute('/site/login')?>" style="color:#FF9700">登录</a></p>',
                    type: 'confirm',
                    onClose: function (v) {
                        if (v) {
                            location.href = "<?php echo Url::toRoute('/site/login')?>";
                        }
                    }
                });
            }else if(v.status == 1){
                location.href = "<?php echo Url::toRoute('/capital/applyorder/'); ?>?id="+id+"&category="+category;
            }else if(v.status == 2){
                $.msgbox({
                    height: 180,
                    width: 330,
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
    }

   $(document).ready(function(){
        $('.jd a').click(function(){
            var index=$(this).index()-1;
            $('.notice').children().eq(index).show().siblings('div').hide();
            $(this).addClass('current').siblings('a').removeClass('current');
        })

    })
</script>
<script toype="text/javascript">
    if(browser.versions.ios || browser.versions.android || browser.versions.iPhone || browser.versions.iPad){
        $('#allmap').css('display','none');
        $('.show').css('display','none');
    }else{
    // 百度地图API功能
    var map = new BMap.Map("allmap");
    map.centerAndZoom('中国', 5);
    //map.enableScrollWheelZoom();
    // 创建地址解析器实例
    var myGeo = new BMap.Geocoder();
    <?php  foreach($data as $key=>$val){
                                    $district_name= \Yii::$app->db->createCommand("select province from zcb_province where provinceID={$key}")->queryScalar();?>
    myGeo.getPoint("<?php echo $district_name?>", function(point){
        if (point) {
            var myLabel = new BMap.Label("<a href='<?php echo \yii\helpers\Url::toRoute('/homepage/homemap')?>/?category=0&&money=0&&district=<?php echo $key;?>&&nsearch=' target = '_blank'><?php echo $district_name.' '.$val.'件'?></a>", {offset:new BMap.Size(0,0), position:point});
            myLabel.setTitle("<?php echo $val?>件");
            myLabel.setStyle({
                "color":"white",
                "fontSize":"12px",
                "border":"0",
                "height":"80px",
                "width":"80px",
                "textAlign":"center",
                "lineHeight":"80px",
                "background":"url(/images/mappoint.png) no-repeat",
                "cursor":"pointer"
            });
            map.addOverlay(myLabel);
        }
    }, "中国");
     <?php }?>
    }
    function searchMapForm(){
       window.location = "<?php echo \yii\helpers\Url::toRoute('/homepage/homepages/')?>"+"?category="+$('.category').attr('id')+"&&money="+$('.money').attr('id')+"&&district="+$('.district').attr('id')+"&&nsearch="+$('.nsearch').val();
    }


</script>

<script>
    -function move(){
        var num01=document.getElementById("num01");
        var bSpan=document.getElementById("num02");
        var cSpan=document.getElementById("num03");
        var dSpan=document.getElementById('num04');
        var n="<?php echo $creditopen+$financepen?>",n1="<?php echo round(($creditor+$finance)/1000,2);?>",n2="<?php echo $organization ?>",n3="<?php echo $lawyer?>";
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
            }
        },30);
    }();
</script>


