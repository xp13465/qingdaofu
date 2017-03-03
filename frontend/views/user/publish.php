<?php
use \yii\helpers\Url;
?>
<div class="content_right">
    <div class="imgs">
        <a href="<?php echo Url::toRoute("/user/financing");?>" class="display">
            <img src="/images/rongzi.png" height="189" width="189" alt="">
            <span>融资</span>
            <p>一键发布 · 轻松配对</p>
        </a>
        <a href="<?php echo Url::toRoute("/user/collection");?>" class="display">
            <img src="/images/cuishou.png" height="189" width="189" alt="">
            <span>清收</span>
            <p>不良债权 · 使命必达</p>
        </a>
        <a href="<?php echo Url::toRoute("/user/litigation");?>" class="display">
            <img src="/images/susong.png" height="189" width="189" alt="">
            <span>诉讼</span>
            <p>知名律所 · 全程服务</p>
        </a>
    </div>
</div>