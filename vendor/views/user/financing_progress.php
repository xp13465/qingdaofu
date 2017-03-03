<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="detaila">
    <div class="look">
        <h3>查看信息</h3>
    </div>
    <!-- 融资详情 -->
    <div class="finance">
        <span>融资详情</span>
        <i></i>
        <?php echo $this->renderFile('@app/views/public/pubdetail.php',['category'=>1,'id' =>$progress['id']])?>
    </div>
    <!-- 处置方详情 -->
    <div class="xieyi">
        <span>服务协议</span>
        <i></i>
        <p>
            <a>BS20151215003协议：</a>
            <a onclick="agreement(<?php echo $progress['uid']?>)">BS20151215003协议</a>
        </p>
    </div>
    <?php //echo $this->renderFile('@app/views/public/disposinguser.php',['category'=>$progress['category'],'id'=>$progress['id'],])?>

    <!-- 处置进度 -->


    <?php echo $this->renderFile('@app/views/public/disposingprocess.php',['category'=>$progress['category'],'id'=>$progress['id'],])?>


    <!-- 处置进度结束 -->


    <br /><input type="button" value="终止" style="background:#0065b3;"/><br />

    <?php echo $this->renderFile('@app/views/public/disposingprocessadd.php',['category'=>$progress['category'],'id'=>$progress['id'],])?>
    <!-- 	延期 -->

    <?php echo $this->renderFile('@app/views/public/delay.php',['category'=>$progress['category'],'id'=>$progress['id'],])?>
</div>
<script type="text/javascript">
    function agreement(uid){
        location.href = "<?php echo Url::to('/user/agreement')?>"+"/uid/"+uid;
    }

</script>
