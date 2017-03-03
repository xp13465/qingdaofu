<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

?>
<div class="list_banner">
    <img src="/images/zc_list1.jpg" height="228" width="1920" alt="">
</div>

<div class="con">
    <div class="con_m">
        <img src="/images/anli.png" height="32" width="160" alt="" />
        <ul>
            <?php foreach($individual as $value):?>
            <li>
                <h4><?php echo isset($value['name'])?$value['name']:''?></h4>
                <p>
                    <?php echo isset($value['abstract'])?$value['abstract']:''?>
                </p>
                <div class="check">
                    <a href="<?php echo yii\helpers\Url::toRoute(['/homepage/introduce','id'=>$value['id']])?>" class="more">查看更多</a>
                    <a href="#" class="next1">>></a>
                </div>
                <div class="dot"></div>
            </li>
            <?php endforeach;?>
        </ul>
        <div class="fenye clearfix">
            <?= \yii\widgets\LinkPager::widget([
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