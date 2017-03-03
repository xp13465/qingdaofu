<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="details">
    <div class="look">
        <h3>查看信息</h3>
    </div>
    <!-- 融资详情 -->
    <div class="finance">
    <?php echo $this->renderFile('@app/views/public/pubdetail.php',['category'=>$dt['category'],'id'=>$dt['id']])?>
    </div>
    <?php echo $this->renderFile('@app/views/public/protocoldetail.php',['category'=>$dt['category'],'id'=>$dt['id']])?>
    <?php echo $this->renderFile('@app/views/public/disposinguser.php',['category'=>$dt['category'],'id'=>$dt['id'],'progress'=>$dt])?>

    <!-- 处置进度 -->
    <?php echo $this->renderFile('@app/views/public/disposingprocess.php',['category'=>$dt['category'],'id'=>$dt['id'],])?>
    <!-- 处置进度结束 -->
        <!-- 延期展示 -->
    <?php echo $this->renderFile('@app/views/public/delaydetail.php',['category'=>$dt['category'],'id'=>$dt['id'],])?>
</div>
<script type="text/javascript">
    function agreement(uid){
        location.href = "<?php echo Url::to('/user/agreements')?>"+"?uid="+uid;
    }
</script>
