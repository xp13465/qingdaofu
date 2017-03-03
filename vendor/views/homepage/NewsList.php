<?php
use yii\widgets\LinkPager;
?>
<div class="list_banner">
    <!-- <div class="banner_con">
        <p>清道夫债管家清道夫债管家清道夫债管家清道夫债管家清道夫债管家清道夫债管家</p>
    </div> -->

    <img src="/images/zc_list1.jpg" height="228" width="1920" alt="">
</div>

<div class="list_news">
    <div class="list_news_m">
        <div class="top2"><img src="/images/new.png" height="32" width="160" alt="" /></div>
        <?php foreach($news as $value):?>
        <div class="news clearfix">
            <ul class="date06">
                <li class="fir_li"><?php echo isset($value['create_time'])?date('Y-m-d',$value['create_time']):''?></li>
            </ul>
            <div class="myImg">
                <img src="/images/list_t.png" height="178" width="46" alt="" />
            </div>
            <ul class="date01">
                <li class="fir_li">
                    <p><a href="<?php echo \yii\helpers\Url::to(['/homepage/newscontent/','cid' => $value['id']])?>"><?php echo isset($value['title'])?$value['title']:''?></a></p>
                    <div>
						<span>
							<?php echo isset($value['abstract'])?$value['abstract']:''?>
						</span>
                    </div>
                </li>
            </ul>
        </div>
        <?php endforeach;?>
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
    </div>
</div>