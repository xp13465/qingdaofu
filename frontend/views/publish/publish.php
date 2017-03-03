<?php
use \yii\helpers\Url;
?>
<div class="content_right">
    <div class="imgs">
       <?php /* <a href="<?php echo Url::toRoute("/publish/financing");?>" class="display">
            <img src="/images/rongzi.png" height="171" width="170" alt="">
            <span>融资</span>
            <p>一键发布 · 轻松配对</p>
        </a>*/?>
        <a style="    margin-left: 100px;" href="<?php echo Url::toRoute(["/publish/collection",'id'=>1]);?>" class="display">
            <img src="/images/cuishou.png" height="170" width="169" alt="">
            <span>清收</span>
            <p>不良债权 · 使命必达</p>
        </a>
        <a style="    margin-left: 100px;"  href="<?php echo Url::toRoute("/publish/litigation");?>" class="display">
            <img src="/images/susong.png" height="171" width="170" alt="">
            <span>诉讼</span>
            <p>知名律所 · 全程服务</p>
        </a>
    </div>
</div>