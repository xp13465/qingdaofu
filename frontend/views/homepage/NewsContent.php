<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
$catalog_idArr = ["2"=>"公司新闻","3"=>"财经资讯","4"=>"行业动态"];
// var_dump($news);
?>
<div class="newss">
    <div class="news_navs">
        <div class="nav_m">
            <h3><a href="<?php echo \yii\helpers\Url::toRoute('/homepage/homepages')?>">首页</a>><a href="<?php echo \yii\helpers\Url::toRoute(['/homepage/newslist',"category"=>$news["catalog_id"]])?>"><?=isset($catalog_idArr[$news["catalog_id"]])?$catalog_idArr[$news["catalog_id"]]:"新闻列表"?></a>><?php echo isset($news['title'])?$news['title']:''?></h3>
            <h2><?php echo isset($news['title'])?$news['title']:''?></h2>
            <span class="date"><?php echo isset($news['create_time'])?date('Y-m-d H:i:s',$news['create_time']):''?></span>
        </div>
    </div>
    <div class="wz1 clearfix">
        <div class="news_cons fl">
           <p><?php echo isset($news['albumcontent'])?$news['albumcontent']['content']:''?></p>
			<?php if($news["copy_from"]||$news["copy_url"]){?>
			文章来源 : <a style="color:#000" <?=$news["redirect_url"]?("href='".$news["redirect_url"]."'"):""?> ><?=$news["copy_from"]?> <?=$news["copy_url"]?("(".$news["copy_url"].")"):""?></a>
			<?php }?>
			
            <div class="article">
                <p>
                    <a <?php if(isset($next)&&$next == 0){ echo ' class="current" href = "javascript:;"';}else{ ?> href="<?=Url::toRoute(["/homepage/newscontent",'cid'=>$next])?>" <?php } ?> >上一篇</a>
                    <a <?php if(isset($pre)&&$pre == 0){ echo ' class="current" href = "javascript:;"';}else{ ?> href="<?=Url::toRoute(["/homepage/newscontent",'cid'=>$pre])?>"<?php } ?> >下一篇</a>
                    <a href="<?php echo \yii\helpers\Url::toRoute(['/homepage/newslist',"category"=>$news["catalog_id"]])?>"><?=isset($catalog_idArr[$news["catalog_id"]])?$catalog_idArr[$news["catalog_id"]]:"新闻列表"?></a>
                </p>
            </div>
        </div>
		
        <div class="con_r fr">
            <ul>
                <li class="c_t"><span>热门文章</span></li>
                <?php if(isset($rowss)&&$rowss) {?>
                <?php foreach($rowss as $value):?>
                <li><a href="<?php echo \yii\helpers\Url::toRoute(['/homepage/newscontent/','cid' => $value['id']])?>"><?php echo isset($value['title'])?mb_substr(strip_tags($value['title']),0,13,'utf-8').'...':''?></a></li>
                <?php endforeach; ?>
                <?php }?>
            </ul>
        </div>
    </div>
</div>