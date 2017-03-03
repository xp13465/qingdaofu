<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="list_banner">
    <!-- <div class="banner_con">
        <p>清道夫债管家清道夫债管家清道夫债管家清道夫债管家清道夫债管家清道夫债管家</p>
    </div> -->
    <img src="/images/zc_list1.jpg" height="228" width="1920" alt="">
</div>
<!-- banner 结束-->
<!-- 内容开始-->
<div class="list_con">
    <div class="list_con1">
        <div class="list_menu">
            <span id = '0' <?php if(in_array(Yii::$app->request->get('cat'),[0])){echo "class='current'";}?>>不限</span>
            <span id = '1' <?php if(in_array(Yii::$app->request->get('cat'),[1])){echo "class='current'";}?>><i class="display rz"></i><b></b>融资</span>
            <span id = '3' <?php if(in_array(Yii::$app->request->get('cat'),[3])){echo "class='current'";}?>><i class="display ss"></i><b></b>诉讼</span>
            <span id = '2' <?php if(in_array(Yii::$app->request->get('cat'),[2])){echo "class='current'";}?>><i class="display cs"></i><b></b>清收</span>
        </div>
        <ul class="area">
            <li class="one">
                <span>区域:　</span><a href="#" id = '0'>不限</a>　<a href="#" id = '1'>上海</a>
                　　<!--input type="text" class="sr"><a href="#" class="search">搜索</a-->
            </li>
            <li class="two">
                <span>金额:　</span><a href="#" id = '0'<?php if(in_array(Yii::$app->request->get('money'),[0])){echo "class='current'";}?>>不限</a>　
                <a href="javascript:;"  id = '1' <?php if(in_array(Yii::$app->request->get('money'),[1])){echo "class='current'";}?>>30万以下</a>　
                <a href="javascript:;" id = '2' <?php if(in_array(Yii::$app->request->get('money'),[2])){echo "class='current'";}?>>30-100万</a>　
                <a href="javascript:;" id = '3' <?php if(in_array(Yii::$app->request->get('money'),[3])){echo "class='current'";}?>>100-500万</a>　
                <a href="javascript:;" id = '4' <?php if(in_array(Yii::$app->request->get('money'),[4])){echo "class='current'";}?>>500万以上</a>
            </li>
            <li class="three">
                <span>期限:　</span><a href="#" id = '0' <?php if(in_array(Yii::$app->request->get('term'),[0])){echo "class='current'";}?>>不限</a>　
                <a href="javascript:;"  id = '1' <?php if(in_array(Yii::$app->request->get('term'),[1])){echo "class='current'";}?>>3个月以下</a>　
                <a href="javascript:;"  id = '2' <?php if(in_array(Yii::$app->request->get('term'),[2])){echo "class='current'";}?>>3-6个月</a>　
                <a href="javascript:;"  id = '3' <?php if(in_array(Yii::$app->request->get('term'),[3])){echo "class='current'";}?>>6-9个月</a>　
                <a href="javascript:;"  id = '4' <?php if(in_array(Yii::$app->request->get('term'),[4])){echo "class='current'";}?>>9-12个月</a>　
                <a href="javascript:;"  id = '5' <?php if(in_array(Yii::$app->request->get('term'),[5])){echo "class='current'";}?>>12个月以上</a>

            </li>

        </ul>
    </div>
    <?php foreach($creditor as $value):?>
        <div class="bianhao current">

            <h3>资产编号 :<?php echo isset($value['code'])?$value['code']:'';?></h3>
            <div class="clearfix">
                <div class="left1">
                    <p><span><strong><?php echo isset($value['money'])?$value['money']:'';?></strong><br></span>
                        <span class="wan">万</span></p>
                </div>
                <div class="right1">
                    <p class="display time">
                        <i>发布时间 :<?php echo isset($value['create_time'])?date('Y年m月d日',$value['create_time']):'';?></i><br><br>
                        　　<i><strong><?php echo isset($value['term'])?$value['term']:'';?></strong><b><?php if($value['category'] == 1){echo \common\models\FinanceProduct::$ratedatecategory[$value['rate_cat']];}else{echo '天';}?></b>期限</i><br>
                        <i>区域 : <?php echo isset($value['city_id']) && $value['city_id']== 310100 ?'上海市':''?><?php $v = common\models\Area::findOne(['areaID'=>(isset($value['district_id'])&& $value['district_id'] == 0)?'1':$value['district_id']]); echo $v->area;?></i>
                    </p>
                    <p class="display lx" style="border-right: none;margin-right:0;">
                        <i>类型</i>
                        <br>
                        <i><em><?php echo common\models\CreditorProduct::$categorys[$value['category']]?></em></i>
                    </p>
                    <p class="display lx">
                        <i>状态</i>
                        <br>
                        <i><b><?php echo \common\models\CreditorProduct::$progressstatus[$value['progress_status']]?></b></i>
                    </p>
                    <p class="display people" style="margin-right:0;">
                        <?php $app = Yii::$app->db->createCommand("select count(*) from zcb_apply where category = {$value['category']} and product_id = {$value['id']} and app_id = 0")->queryScalar(); ?>
                        <?php $order= Yii::$app->db->createCommand("select count(*) from zcb_apply where category = {$value['category']} and product_id = {$value['id']} and app_id = 2")->queryScalar(); ?>
                        <i>申请数：<em><?php echo $app;?>人</em></i><br>
                        <i>收藏数：<em><?php echo $order;?>人</em></i><br>
                        <i>浏览次数：<em><?php echo isset($value['browsenumber'])&& $value['browsenumber'] == ''? 0 : $value['browsenumber']?>人</em></i>
                    </p>

                </div>
                <a href = "<?php echo \yii\helpers\Url::to(['/capital/applyorder/','tid'=>$value['id'],'category'=>$value['category'],'browsenumber'=>1])?>" class="see" onclick="browse(1);">查看</a>
            </div>
        </div>
    <?php endforeach;?>
    <!-- 分页开始 -->
    <div class="fenye clearfix">
        <?= linkPager::widget([
            'firstPageLabel' => '首页',
            'lastPageLabel' => '最后一页',
            'prevPageLabel' => '上一页',
            'nextPageLabel' => '下一页',
            'pagination' => $pagination,
            'maxButtonCount'=>4,
        ]);?>
    </div>
    <!-- 分页开始 -->
</div>
</div>
<script type="text/javascript">
     $(document).ready(function() {
         function searchForm(cat,district,money,term){
             window.location = "<?php echo \yii\helpers\Url::to('/capital/list/')?>"+"?cat="+cat+"&&district="+district+"&&money="+money+"&&term="+term;
         }
         $('.list_menu span').click(function(){
            searchForm($(this).attr('id'),0,$('.area .two a.current').attr('id'),$('.area .three a.current').attr('id'))
        });
        $('.area .two a').click(function(){
            searchForm($('.list_menu span.current ').attr('id'),0,$(this).attr('id'),$('.area .three a.current').attr('id'))
        });
         $('.area .three a').click(function(){
             searchForm($('.list_menu span.current').attr('id'),0,$('.area .two a.current').attr('id'),$(this).attr('id'))
         });
     });
    </script>
<!-- 内容结束-->
