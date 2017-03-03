
<div class="newss">
    <div class="news_navs">
        <div class="nav_m">
            <h3><a href="<?php echo \yii\helpers\Url::to('/homepage/homepages')?>">首页</a>><a href="<?php echo \yii\helpers\Url::to('/homepage/newslist')?>">公司新闻</a>><?php echo isset($news['title'])?$news['title']:''?></h3>
        </div>
    </div>
    <div class="news_cons">
       <h2><?php echo isset($news['title'])?$news['title']:''?></h2>
        <span class="date"><?php echo isset($news['create_time'])?date('Y-m-d H:i:s',$news['create_time']):''?></span>
            <p><?php echo isset($news['content'])?$news['content']:''?></p>
        <div class="article">
            <p><a href="<?php echo \yii\helpers\Url::to('/homepage/homepages')?>" class="color">返回>></a></p>
        </div>
    </div>
</div>
