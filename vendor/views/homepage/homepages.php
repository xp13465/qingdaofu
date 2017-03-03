
<style type="text/css">
    #allmap {width: 1200px;height: 600px;overflow: hidden;margin:0;font-family:"微软雅黑";}
</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=w10qOR1ck89d9YevXrdWPqGg"></script>
<div class="list_banner index_banner">
    <!-- <div class="banner_con">
        <p>清道夫债管家清道夫债管家清道夫债管家清道夫债管家清道夫债管家清道夫债管家</p>
    </div> -->

    <img src="/images/index_bg.jpg" height="480" width="1920" alt="" style="overflow:hidden;">
</div>
<!-- banner 结束-->
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

                    <div id="allmap"></div>



            </div>

        </div>
        <div class="maptext">
            <?php $creditor = Yii::$app->db->createCommand("select sum(money) from zcb_creditor_product")->queryScalar(); ?>
            <?php $finance = Yii::$app->db->createCommand("select sum(money) from zcb_finance_product")->queryScalar(); ?>
            <?php $creditopen = Yii::$app->db->createCommand("select count(id) from zcb_creditor_product")->queryScalar(); ?>
            <?php $financepen = Yii::$app->db->createCommand("select count(id) from zcb_finance_product")->queryScalar(); ?>
            <?php $organization = Yii::$app->db->createCommand("select count(category) from zcb_certification where category = 3")->queryScalar(); ?>
            <?php $lawyer = Yii::$app->db->createCommand("select count(category) from zcb_certification where category = 2")->queryScalar(); ?>

            <ul>
                <li class="f24"><i><img src="/images/tu1.jpg" height="70" width="98" alt=""></i>　<b><?php echo $creditopen+$financepen?></b><em class="f15"> 笔</em><p>资产发布</p></li>
                <li class="f24"><i><img src="/images/tu2.jpg" height="70" width="98" alt=""></i>　<b><?php echo round(($creditor+$finance)/1000,2);?>亿</b><em class="f15"> 元</em><p>资产总额</p></li>
                <li class="f24"><i><img src="/images/tu3.jpg" height="70" width="98" alt=""></i>　<b><?php echo $organization ?></b><em class="f15"> 家</em><p>机构入驻</p></li>
                <li class="f24"><i><img src="/images/tu4.jpg" height="70" width="98" alt=""></i>　<b><?php echo $lawyer?></b><em class="f15"> 家</em><p>律师入驻</p></li>
            </ul>
        </div>
    </div>
    <div class="example">
        <p class="jd"><span>经典案例</span><a href="<?php echo yii\helpers\Url::to('/homepage/personal')?>" class="display">更多>></a></p>
        <div class="jieshao">

        </div>
    </div>
    <div class="center clearfix">
        <div class="new">
            <p class="jd clearfix"><span>新闻动态</span> <a href="<?php echo \yii\helpers\Url::to('/homepage/newslist')?>" class="display">更多>></a></p>
            <div class="new_text">
                <ul class="clearfix">
                    <?php foreach($news as $v):?>
                           <li><a href="<?php echo \yii\helpers\Url::to(['/homepage/newscontent/','cid' => $v['id']])?>"><i><?php echo date('m',$v['create_time'])?>月 <br> <?php echo date('d',$v['create_time'])?><em style="font-size:14px; vertical-align:bottom;">日</em></i><span><?php echo $v['title'];?></span><p><?php echo mb_substr($v['abstract'],0,45,'utf-8').'.';?></p></a></li>
                        <?php endforeach;?>
                </ul>
            </div>
                <div class="fenye clearfix">
                    <?php echo \yii\widgets\LinkPager::widget([
                        'prevPageLabel' => '上一页',
                        'nextPageLabel' => '下一页',
                        'pagination' => $pagination,
                        'maxButtonCount'=>4,
                    ]);?>
                </div>
        </div>
        <div class="zixin">
            <p class="jd"><span>资信查询</span></p>
            <ul class="clearfix">
                <li style="border-top:1px solid #ccc;"><a href="http://gsxt.saic.gov.cn/"><i class="first"></i><span>企业信用信息查询</span></a></li>
                <li><a href="http://www.court.gov.cn/zgcpwsw/"><i class="second"></i><span>企业涉诉信息查询</span></a></li>
                <li><a href="http://shixin.court.gov.cn/"><i class="third"></i><span>失信被执行人查询</span></a></li>
                <li><a href="http://zhixing.court.gov.cn/search/"><i class="four"></i><span>法院执行信息查询</span></a></li>
                <li><a href="http://www.nacao.org.cn/"><i class="five"></i><span>组组机构信息查询</span></a></li>
            </ul>
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
                
                </ul>
           </div>
            <div class="left2 hover"><span> < </span></div>
            <div class="right2 hover"><span> > </span></div>
        </div>
    </div>

    <div class="bottom clearfix">
        <div class="jianjie">
            <p class="jd clearfix"><span>关于我们</span><a href="<?php echo yii\helpers\Url::to('/aboutus/intro')?>" class="display">更多>></a></p>
            <div class="jq_con1">
                <img src="/images/jianj1.png" alt="">
                <p>清道夫债管家，国内知名金融资产综合服务平台，隶属于直向资产管理有限公司。公司自成立以来，致力于互联网金融资产创新服务，整合金融行业优质资源，让资产交易与清收更便捷、更高效。</p>
　　              <p style="margin-top:-10px;">公司总部位于上海陆家嘴，服务面向全国区域，实现全方位资产管理。公司以共享式平台，运用互联网技术与数据分析，将金融资产标准化，与覆盖全国的资产交易和处置资源精准匹配，让资产管理变得更简单、更安全。

                </p>
                
            </div>
        </div>
        <div class="fbzs">
            <p class="jd"><span>发布指数</span></p>
            <div class="data">
                <?php echo $this->renderFile('@app/views/homepage/flotr.php',['release'=>$release])?>
                <div class="icon1"></div>
            </div>
        </div>
    </div>

</div>
    </div>
<script type="text/javascript">
    $(function(){

        $('.jieshao').load('<?php echo \yii\helpers\Url::to("/homepage/individual")?>')
        $('.individual').click(function(){
            $('.jieshao').load('<?php echo \yii\helpers\Url::to("/homepage/individual")?>')
        })
        $('.law').click(function(){
            $('.jieshao').load('<?php echo \yii\helpers\Url::to("/homepage/lawcase")?>')
        })
        $('.type li').click(function(){
            $(this).addClass('current').siblings('li').removeClass('current');
        })

        $('.jieshao').load('<?php echo \yii\helpers\Url::to('/homepage/individual')?>')
        $('.individual').click(function(){
            $('.jieshao').load('<?php echo \yii\helpers\Url::to('/homepage/individual')?>')
        })
        $('.law').click(function(){
            $('.jieshao').load('<?php echo \yii\helpers\Url::to('/homepage/lawcase')?>')
        })
        $('.type li').click(function(){
            $(this).addClass('current').siblings('li').removeClass('current');
        })

        $('.jieshao').load('<?php echo \yii\helpers\Url::to('/homepage/individual')?>');

    })
</script>
<script type="text/javascript">
    // 百度地图API功能
    var map = new BMap.Map("allmap");
    map.centerAndZoom('上海市', 11);
    map.enableScrollWheelZoom();
    // 创建地址解析器实例
    var myGeo = new BMap.Geocoder();
    <?php  foreach($data as $key=>$val){
                                    $district_name= \Yii::$app->db->createCommand("select area from zcb_area where areaID={$key}")->queryScalar();?>
    myGeo.getPoint("<?php echo $district_name?>", function(point){
        if (point) {
            var myLabel = new BMap.Label("<a href='<?php echo \yii\helpers\Url::to('/homepage/homemap')?>/?category=0&&money=0&&district=<?php echo $key;?>&&nsearch=' target = '_blank'><?php echo $district_name.' '.$val.'件'?></a>", {offset:new BMap.Size(0,0), position:point});
            myLabel.setTitle("<?php echo $val?>件");
            myLabel.setStyle({
                "color":"white",
                "fontSize":"12px",
                "border":"0",
                "height":"88px",
                "width":"88px",
                "textAlign":"center",
                "lineHeight":"88px",
                "background":"url(/images/ccircle.png) no-repeat",
                "cursor":"pointer"
            });
            map.addOverlay(myLabel);
        }
    }, "上海市");

                                <?php }?>
    function searchMapForm(){
       window.location = "<?php echo \yii\helpers\Url::to('/homepage/homepages/')?>"+"?category="+$('.category').attr('id')+"&&money="+$('.money').attr('id')+"&&district="+$('.district').attr('id')+"&&nsearch="+$('.nsearch').val();
    }


</script>