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
        <span class="bord_l">融资详情</span>
        <!-- <i></i> -->
        <?php echo $this->renderFile('@app/views/public/pubdetail.php',['category'=>1,'id' =>$progress['id']])?>
    </div>
    <?php echo $this->renderFile('@app/views/public/protocoldetail.php',['category'=>$dt['category'],'id'=>$dt['id']])?>
    <?php echo $this->renderFile('@app/views/public/disposinguser.php',['category'=>$progress['category'],'id'=>$progress['id'],'progress'=>$progress])?>

    <!-- 处置进度 -->


    <?php echo $this->renderFile('@app/views/public/disposingprocess.php',['category'=>$progress['category'],'id'=>$progress['id'],])?>
    <!-- 处置进度结束 -->
    <br/>
    <input type="button" value="终止" style="background:#0065b3;"/>
    <br/>
    <!-- 延期展示 -->
    <?php echo $this->renderFile('@app/views/public/delaydetail.php',['category'=>$progress['category'],'id'=>$progress['id'],])?>
    <br/>
</div>
<script type="text/javascript">
    function agreement(uid){
        location.href = "<?php echo Url::toRoute('/user/agreement')?>"+"/uid/"+uid;
    }
    $(function(){
            $('.yanchangs').css('display','none');
            $('.affirma').click(function(){
                $('.yanchangs').stop().fadeToggle();
            });
        })
    $(function(){
        var char = "<?php echo $progress['term']?>";
        if(char <= 12){
            $('.yanqi').show();
        }else{
            $('.yanqi').hide();
        }
    })
</script>
