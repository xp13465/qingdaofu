<?php
use \yii\helpers\Url;
?>
<div class="content_right">
    <div class="imgs">
        <a href="<?php echo Url::to("/user/financing");?>" class="display">
            <img src="/images/rongzi.png" height="189" width="189" alt="">
            <span>融资</span>
        </a>
        <a href="<?php echo Url::to("/user/collection");?>" class="display">
            <img src="/images/cuishou.png" height="189" width="189" alt="">
            <span>清收</span>
        </a>
        <a href="<?php echo Url::to("/user/litigation");?>" class="display">
            <img src="/images/susong.png" height="189" width="189" alt="">
            <span>诉讼</span>
        </a>
    </div>
</div>